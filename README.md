# 중흥복싱클럽 블로그 (jungheung-boxing-blog)

맥미니 + Docker + Cloudflare Tunnel 로 운영하는 WordPress 블로그.
`blog.광주복싱.com` 에서 접속되며, 본 홈페이지 `www.광주복싱.com` (가비아 웹호스팅) 과는 분리된 서브도메인입니다.

```
브라우저 ──HTTPS──▶ Cloudflare ──Tunnel──▶ 맥미니 cloudflared ──▶ wordpress ──▶ mysql
                                                    (공인 IP / 포트포워딩 없음)
```

---

## 1. 맥미니 초기 세팅 (최초 1회)

### 1-1. Docker Desktop 설치
1. https://www.docker.com/products/docker-desktop/ 에서 **Apple Silicon** 또는 **Intel** 버전 다운로드
2. 설치 후 실행 → 좌상단 🐳 아이콘 생기면 OK
3. Settings → General → **"Start Docker Desktop when you sign in"** 체크
4. Settings → Resources → Memory 를 4GB 이상 권장

### 1-2. 저장소 내려받기
```bash
cd ~
git clone https://github.com/silobust-hash/jungheung-boxing-blog.git
cd jungheung-boxing-blog
```

### 1-3. 맥미니 슬립 방지
> 맥미니가 자면 블로그도 내려갑니다.

- **시스템 설정 → 디스플레이 → 고급 → "디스플레이가 꺼진 경우 자동으로 잠자기 해제 방지"** 켜기
- **시스템 설정 → 에너지 → "전원이 연결되었을 때 디스플레이가 꺼지면 컴퓨터를 자동으로 잠자게 함"** 끄기
- 터미널에서 확인: `pmset -g | grep sleep` → `sleep 0` 이어야 함

---

## 2. Cloudflare 로 DNS 이전 (최초 1회)

가비아에서 도메인만 유지하고, DNS 관리를 Cloudflare 로 옮깁니다. **본 홈페이지 `www.광주복싱.com` 은 그대로 유지됩니다** (A 레코드가 자동 import 됨).

### 2-1. Cloudflare 가입 & 도메인 추가
1. https://dash.cloudflare.com 가입 (무료 플랜)
2. **Add a site** → `광주복싱.com` 입력
   - Cloudflare 가 자동으로 퓨니코드 `xn--hc0bj51alpe00g.com` 로 변환함
3. Free 플랜 선택 → Continue
4. Cloudflare 가 기존 가비아 DNS 레코드를 자동 스캔함

### 2-2. ⚠ 기존 레코드 검증 (중요)
스캔 결과에서 **다음 두 레코드가 반드시 있어야** 본 홈페이지가 안 끊깁니다:

| Type  | Name | Content                             | Proxy       |
|-------|------|-------------------------------------|-------------|
| A     | @    | (가비아 웹호스팅 IP, 예: 211.x.x.x) | 회색 (DNS only) |
| CNAME | www  | @                                   | 회색 (DNS only) |

없으면 **+ Add record** 로 수동 추가하세요. 가비아 웹호스팅 IP 는 **마이가비아 → 웹호스팅 → 관리 → 접속정보** 에서 확인.

> 가비아 웹호스팅은 Cloudflare 프록시(주황색 구름)와 호환성 이슈가 있을 수 있어 **회색(DNS only)** 로 두는 게 안전합니다.

### 2-3. 네임서버 변경 (가비아)
1. Cloudflare 가 알려주는 네임서버 2개를 복사 (예: `kia.ns.cloudflare.com`, `walt.ns.cloudflare.com`)
2. **마이가비아 → My가비아 → 도메인 → 관리 → 네임서버** 이동
3. 1차/2차 네임서버를 위 값으로 교체 → 저장
4. 전파 대기 (보통 30분, 최대 24시간)
5. Cloudflare 대시보드에서 상태가 **Active** 로 바뀌면 완료

확인:
```bash
dig NS xn--hc0bj51alpe00g.com +short
# kia.ns.cloudflare.com.
# walt.ns.cloudflare.com.
```

---

## 3. Cloudflare Tunnel 설정 (최초 1회)

### 3-1. 터널 생성
1. Cloudflare 대시보드 → 좌측 **Zero Trust** 클릭 (처음이면 팀 이름 설정, Free 플랜 OK)
2. **Networks → Tunnels → + Create a tunnel**
3. 커넥터 종류: **Cloudflared** 선택
4. 이름: `jungheung-blog-tunnel` → Save
5. 다음 화면 **Install and run a connector** 에서 **토큰 문자열**을 복사
   - `eyJhIjoi...` 로 시작하는 긴 문자열
   - 다른 설치 방법 탭(Docker/Linux 등) 무시하고 **토큰만 복사**

### 3-2. 퍼블릭 호스트명 연결
같은 화면에서 **Next → Public Hostnames** 탭:

| 항목              | 값                        |
|-------------------|---------------------------|
| Subdomain         | `blog`                    |
| Domain            | `xn--hc0bj51alpe00g.com`    |
| Path              | (비움)                    |
| Service - Type    | `HTTP`                    |
| Service - URL     | `wordpress:80`            |

저장하면 Cloudflare 가 `blog.광주복싱.com` CNAME 레코드를 자동 생성합니다.

---

## 4. `.env` 작성 & 기동

```bash
cp .env.example .env
```

`.env` 를 열어 아래 값을 채웁니다:

```bash
# 랜덤 비밀번호 생성 예시
openssl rand -base64 32
```

- `BLOG_DOMAIN` = `blog.xn--hc0bj51alpe00g.com`
- `MYSQL_ROOT_PASSWORD`, `MYSQL_PASSWORD` = 위에서 생성한 값
- `CLOUDFLARE_TUNNEL_TOKEN` = 3-1 에서 복사한 토큰

기동:
```bash
docker compose up -d
docker compose ps      # 세 컨테이너 모두 Up / healthy 확인
docker compose logs -f # Ctrl+C 로 빠져나오기
```

### 4-1. WordPress 초기 설치
1. 브라우저에서 **https://blog.광주복싱.com** 접속
2. 언어 → 한국어 선택
3. 사이트 제목 / 관리자 계정 / 비밀번호 / 이메일 입력 → **워드프레스 설치**
4. 로그인 후 **외모 → 테마** → `Jungheung Boxing Theme` 활성화

### 4-2. 샘플 콘텐츠 시드 (선택)
체육관 소개 / 프로그램 / 오시는 길 / 문의 페이지와 카테고리 · 주 메뉴를 실제 정보 (062-521-9848 등) 로 한 번에 생성합니다.

```bash
./scripts/seed.sh
```
멱등하므로 여러 번 실행해도 기존 글은 덮지 않습니다. 다시 돌리고 싶으면:
```bash
docker compose run --rm cli wp --allow-root option delete jungheung_seeded
./scripts/seed.sh
```

---

## 5. 일상 운영

### 블로그 글 쓰기 / 플러그인 설치
- 관리자 화면: `https://blog.광주복싱.com/wp-admin`
- 플러그인/미디어는 WP 관리 UI 로 설치/업로드. 파일은 `wp-content/plugins/` `wp-content/uploads/` 에 저장되며 `.gitignore` 로 git 추적 제외됨

### 테마 수정
- 맥에서 `wp-content/themes/jungheung-theme/` 직접 편집
- 브라우저 새로고침하면 즉시 반영 (볼륨 마운트)
- 변경사항 커밋:
  ```bash
  git add wp-content/themes/jungheung-theme
  git commit -m "테마: 헤더 레이아웃 수정"
  git push
  ```

### 컨테이너 관리
```bash
docker compose ps                  # 상태 확인
docker compose logs -f wordpress   # WP 로그
docker compose logs -f cloudflared # 터널 로그
docker compose restart wordpress   # WP 만 재시작
docker compose down                # 전체 중지 (데이터는 유지)
docker compose up -d               # 다시 기동
docker compose pull && \
  docker compose up -d             # 이미지 최신 버전으로 갱신
```

### 로컬에서만 관리 UI 열기 (Cloudflare 우회)
Tunnel 이 꺼졌거나 복구 중일 때, 맥미니 내부에서만:
```
http://localhost:8080
```
(외부에서는 접속 불가, `127.0.0.1` 바인딩)

---

## 6. 백업 & 복구

### 수동 백업
```bash
./scripts/backup.sh
# ./backups/db-20260423-153000.sql.gz
# ./backups/wp-content-20260423-153000.tar.gz
```

### 자동 백업 (매일 새벽 3시)
```bash
crontab -e
```
다음 줄 추가:
```
0 3 * * * cd $HOME/jungheung-boxing-blog && ./scripts/backup.sh >> backups/cron.log 2>&1
```
> `backups/` 는 30일 이상 된 파일을 스크립트가 자동 삭제합니다.
> 중요한 스냅샷은 iCloud Drive / 외장 디스크로 별도 복사 권장.

### 복구
```bash
./scripts/restore.sh backups/db-20260423-153000.sql.gz backups/wp-content-20260423-153000.tar.gz
```

---

## 7. 자동 배포 (git pull 기반)

맥미니는 공인 IP 없이 Cloudflare Tunnel 로만 외부에 노출돼 있어 GitHub Actions 에서 SSH 푸시는 못 합니다. 대신 **맥미니에서 주기적으로 `git pull`** 하는 방식을 씁니다.

### 수동 배포
```bash
./scripts/deploy.sh
```
동작:
1. `git fetch && git merge --ff-only` 로 현재 브랜치 최신화
2. 변경 파일을 분석하여 필요한 컨테이너만 재시작
   - `docker-compose.yml` 바뀜 → `docker compose up -d`
   - `mu-plugins/` 바뀜 → `docker compose restart wordpress`
   - 테마 파일만 바뀜 → 재시작 없이 즉시 반영 (볼륨 마운트)

### 자동 배포 (5분마다 체크)
```bash
crontab -e
```
추가:
```
*/5 * * * * cd $HOME/jungheung-boxing-blog && ./scripts/deploy.sh >> logs/deploy.log 2>&1
```

GitHub 에 push 하면 최대 5분 내 맥미니에 반영됩니다.

### 브랜치 전환 배포
```bash
./scripts/deploy.sh main   # main 브랜치로 전환 후 pull
```

---

## 8. 문제 해결

### "Error establishing a database connection"
```bash
docker compose logs db
docker compose restart db wordpress
```

### 로그인 후 리다이렉트 루프
`.env` 의 `BLOG_DOMAIN` 이 실제 접속 도메인과 다를 때 발생.
수정 후:
```bash
docker compose up -d --force-recreate wordpress
```

### 터널이 연결 안 됨
```bash
docker compose logs cloudflared
```
- `failed to sufficient TLS` → `CLOUDFLARE_TUNNEL_TOKEN` 오타 확인
- Cloudflare 대시보드 → Zero Trust → Networks → Tunnels 에서 상태가 **HEALTHY** 인지 확인

### 이미지 / CSS 가 http:// 로 불러와져서 깨짐
브라우저 개발자도구에서 mixed content 경고 확인.
`WORDPRESS_CONFIG_EXTRA` 가 적용됐는지:
```bash
docker compose exec wordpress grep WP_HOME /var/www/html/wp-config.php
```

---

## 9. 디렉터리 구조

```
.
├── docker-compose.yml       # db + wordpress + cloudflared + cli (profile)
├── .env                     # 비밀값 (git 제외)
├── .env.example             # 템플릿
├── .gitignore
├── README.md                # 이 문서
├── scripts/
│   ├── backup.sh            # DB + wp-content 백업
│   ├── restore.sh           # 복구
│   ├── deploy.sh            # git pull + 스마트 재시작
│   ├── seed.sh              # 샘플 콘텐츠 시드 (wrapper)
│   └── seed.php             # WP-CLI eval-file 대상
└── wp-content/
    ├── mu-plugins/
    │   └── jungheung-defaults.php  # 시간대·고유주소·보안 (git 추적)
    ├── themes/
    │   └── jungheung-theme/        # 커스텀 테마 (git 추적)
    │       └── inc/site-info.php   # 체육관 정보 상수 (전화/주소 등)
    ├── plugins/             # 런타임 설치물 (git 제외)
    └── uploads/             # 미디어 업로드 (git 제외)
```

---

## 10. 체육관 정보 수정하기

테마 여러 곳에 노출되는 전화번호·주소·운영시간은 한 파일에 모여 있습니다:
```
wp-content/themes/jungheung-theme/inc/site-info.php
```
상수 값만 바꾸고 저장하면 헤더/푸터/히어로에 즉시 반영됩니다 (재시작 불필요).

```php
define('JUNGHEUNG_PHONE',   '062-521-9848');
define('JUNGHEUNG_ADDRESS', '광주광역시 북구 서방로 34, 3층');
define('JUNGHEUNG_HOURS',   '오후 4:30 ~ 10:00');
// ...
```
