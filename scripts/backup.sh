#!/usr/bin/env bash
# DB (mysqldump) 와 wp-content 를 타임스탬프 붙여 백업합니다.
# 사용법:
#   ./scripts/backup.sh              # 기본 ./backups 로 저장
#   ./scripts/backup.sh /path/to/dir # 지정 경로로 저장
# 크론 예시 (매일 새벽 3시):
#   0 3 * * * cd ~/jungheung-boxing-blog && ./scripts/backup.sh >> backups/cron.log 2>&1

set -euo pipefail

cd "$(dirname "$0")/.."

BACKUP_DIR="${1:-./backups}"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
mkdir -p "$BACKUP_DIR"

if [[ ! -f .env ]]; then
  echo "❌ .env 파일이 없습니다. .env.example 을 복사해서 만드세요." >&2
  exit 1
fi

set -a
# shellcheck disable=SC1091
source .env
set +a

echo "▶ DB 덤프 중..."
docker compose exec -T db mysqldump \
  -u root -p"${MYSQL_ROOT_PASSWORD}" \
  --single-transaction --quick --routines --triggers \
  "${MYSQL_DATABASE:-wordpress}" \
  | gzip > "$BACKUP_DIR/db-$TIMESTAMP.sql.gz"

echo "▶ wp-content 아카이빙 중..."
tar czf "$BACKUP_DIR/wp-content-$TIMESTAMP.tar.gz" wp-content

# 30일 초과 백업 자동 삭제
find "$BACKUP_DIR" -maxdepth 1 -type f \
  \( -name 'db-*.sql.gz' -o -name 'wp-content-*.tar.gz' \) \
  -mtime +30 -delete

echo "✅ 완료:"
ls -lh "$BACKUP_DIR/db-$TIMESTAMP.sql.gz" "$BACKUP_DIR/wp-content-$TIMESTAMP.tar.gz"
