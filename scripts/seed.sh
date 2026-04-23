#!/usr/bin/env bash
# 샘플 페이지/카테고리/메뉴를 한 번에 생성합니다 (멱등).
# 사용법:  ./scripts/seed.sh
# 의존:    docker compose, 기동 중인 db·wordpress 컨테이너

set -euo pipefail

cd "$(dirname "$0")/.."

if [[ ! -f .env ]]; then
    echo "❌ .env 파일이 없습니다." >&2
    exit 1
fi

# db/wordpress 기동 중인지 체크
if ! docker compose ps --services --status running | grep -q '^db$'; then
    echo "❌ db 컨테이너가 실행 중이 아닙니다. 먼저 'docker compose up -d' 하세요." >&2
    exit 1
fi

echo "▶ WP-CLI 로 시드 스크립트 실행 중..."
docker compose run --rm \
    -v "$(pwd)/scripts:/seed:ro" \
    cli wp --allow-root eval-file /seed/seed.php

echo "✅ 시드 완료. 관리자 → 외모 → 메뉴 에서 결과 확인."
