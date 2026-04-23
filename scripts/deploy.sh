#!/usr/bin/env bash
# 원격 저장소에서 최신 변경을 내려받아 필요한 서비스만 재시작합니다.
# 사용법:
#   ./scripts/deploy.sh                  # origin/현재브랜치 에서 pull
#   ./scripts/deploy.sh main             # 특정 브랜치로 강제 전환 후 pull
#
# cron 예시 (5분마다 자동 배포 체크):
#   */5 * * * * cd $HOME/jungheung-boxing-blog && ./scripts/deploy.sh >> logs/deploy.log 2>&1

set -euo pipefail

cd "$(dirname "$0")/.."

mkdir -p logs

BRANCH="${1:-}"
if [[ -n "$BRANCH" ]]; then
    git checkout "$BRANCH"
fi
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)

timestamp() { date '+%Y-%m-%d %H:%M:%S'; }
log() { echo "[$(timestamp)] $*"; }

log "배포 체크: branch=$CURRENT_BRANCH"

git fetch --quiet origin "$CURRENT_BRANCH"

LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse "origin/$CURRENT_BRANCH")

if [[ "$LOCAL" == "$REMOTE" ]]; then
    log "변경 없음. 종료."
    exit 0
fi

log "업데이트 발견: $LOCAL → $REMOTE"
CHANGED_FILES=$(git diff --name-only "$LOCAL" "$REMOTE")

git merge --ff-only "origin/$CURRENT_BRANCH"
log "git pull 완료"

RESTART_ALL=0
RESTART_WP=0

while IFS= read -r f; do
    case "$f" in
        docker-compose.yml)
            RESTART_ALL=1 ;;
        wp-content/mu-plugins/*)
            RESTART_WP=1 ;;
        wp-content/themes/*)
            # 테마 파일은 볼륨 마운트로 즉시 반영됨. 재시작 불필요.
            ;;
        scripts/*|*.md|.gitignore|.env.example|wp-content/themes/jungheung-theme/assets/images/README.md)
            # 문서/템플릿/스크립트 — 런타임에 영향 없음, 재시작 불필요.
            ;;
        *)
            RESTART_WP=1 ;;
    esac
done <<< "$CHANGED_FILES"

if [[ "$RESTART_ALL" -eq 1 ]]; then
    log "docker-compose 변경 감지 → 전체 재구성"
    docker compose up -d
elif [[ "$RESTART_WP" -eq 1 ]]; then
    log "wordpress 재시작 (mu-plugin 등 변경)"
    docker compose restart wordpress
else
    log "테마/문서 변경만 있음 — 재시작 생략"
fi

log "배포 완료"
