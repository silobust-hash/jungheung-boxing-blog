#!/usr/bin/env bash
# 백업 파일로부터 DB 와 wp-content 를 복구합니다.
# 사용법:
#   ./scripts/restore.sh backups/db-20260101-030000.sql.gz
#   ./scripts/restore.sh backups/db-20260101-030000.sql.gz backups/wp-content-20260101-030000.tar.gz

set -euo pipefail

cd "$(dirname "$0")/.."

DB_DUMP="${1:-}"
CONTENT_TARBALL="${2:-}"

if [[ -z "$DB_DUMP" ]]; then
  echo "사용법: $0 <db-dump.sql.gz> [wp-content.tar.gz]" >&2
  exit 1
fi
if [[ ! -f "$DB_DUMP" ]]; then
  echo "❌ DB 덤프 파일을 찾을 수 없습니다: $DB_DUMP" >&2
  exit 1
fi
if [[ ! -f .env ]]; then
  echo "❌ .env 파일이 없습니다." >&2
  exit 1
fi

set -a
# shellcheck disable=SC1091
source .env
set +a

read -rp "⚠  현재 DB 를 '$DB_DUMP' 로 덮어씁니다. 계속할까요? (yes/no) " confirm
if [[ "$confirm" != "yes" ]]; then
  echo "취소되었습니다."
  exit 0
fi

echo "▶ DB 복구 중..."
gunzip -c "$DB_DUMP" | docker compose exec -T db mysql \
  -u root -p"${MYSQL_ROOT_PASSWORD}" \
  "${MYSQL_DATABASE:-wordpress}"

if [[ -n "$CONTENT_TARBALL" ]]; then
  if [[ ! -f "$CONTENT_TARBALL" ]]; then
    echo "❌ wp-content 아카이브를 찾을 수 없습니다: $CONTENT_TARBALL" >&2
    exit 1
  fi
  echo "▶ wp-content 복구 중..."
  tar xzf "$CONTENT_TARBALL"
fi

echo "▶ WordPress 컨테이너 재시작..."
docker compose restart wordpress

echo "✅ 복구 완료"
