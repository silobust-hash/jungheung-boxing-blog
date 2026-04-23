<?php
/**
 * WP-CLI eval-file 대상. 중흥복싱클럽 블로그의 카테고리/페이지/메뉴를 실제 정보로 시드합니다.
 * 실행:  ./scripts/seed.sh
 *
 * 멱등(idempotent): 같은 슬러그가 있으면 건너뜀.
 * 플래그: jungheung_seeded option 에 현재 버전 기록.
 */

if (!defined('WP_CLI') || !WP_CLI) {
    echo "이 스크립트는 WP-CLI 컨텍스트에서만 실행할 수 있습니다.\n";
    exit(1);
}

$SEED_VERSION = '1.1.0';
$installed = get_option('jungheung_seeded', '0');
if ($installed === $SEED_VERSION) {
    WP_CLI::success("이미 시딩되어 있습니다 (version $installed). 재실행하려면 'wp option delete jungheung_seeded' 후 다시 실행.");
    return;
}

WP_CLI::log("▶ 중흥복싱클럽 시드 시작 (version $SEED_VERSION)");

// 체육관 정보 상수 (테마와 동일)
define('GYM_PHONE',   '062-521-9848');
define('GYM_ADDRESS', '광주광역시 북구 서방로 34, 3층');
define('GYM_HOURS',   '오후 4:30 ~ 10:00');
define('GYM_MAIN',    'https://xn--oy2b35ckwh3a.com/');

// ───────────────────────────────────────────────────────
// 1. 기본 샘플 콘텐츠 제거
// ───────────────────────────────────────────────────────
$defaults_to_remove = array(
    array('slug' => 'hello-world',      'type' => 'post'),
    array('slug' => 'sample-page',      'type' => 'page'),
    array('slug' => 'privacy-policy',   'type' => 'page'),
);
foreach ($defaults_to_remove as $d) {
    $p = get_page_by_path($d['slug'], OBJECT, $d['type']);
    if ($p) {
        wp_delete_post($p->ID, true);
        WP_CLI::log("  - 기본글 삭제: {$d['slug']}");
    }
}

// ───────────────────────────────────────────────────────
// 2. 카테고리
// ───────────────────────────────────────────────────────
$categories = array(
    array('name' => '공지사항',   'slug' => 'notice',      'description' => '체육관 공지 · 휴관 안내 · 이벤트'),
    array('name' => '수업 후기',  'slug' => 'review',      'description' => '회원 후기와 수업 리포트'),
    array('name' => '복싱 기초',  'slug' => 'basics',      'description' => '잽·스트레이트·훅·어퍼컷 — 초심자를 위한 복싱 이론'),
    array('name' => '대회 소식',  'slug' => 'competition', 'description' => '대회 참가기 및 결과'),
    array('name' => '손해사정',   'slug' => 'adjuster',    'description' => '손해사정 관련 상담/사례'),
);
foreach ($categories as $cat) {
    if (!term_exists($cat['slug'], 'category')) {
        wp_insert_term($cat['name'], 'category', array(
            'slug'        => $cat['slug'],
            'description' => $cat['description'],
        ));
        WP_CLI::log("  + 카테고리: {$cat['name']}");
    }
}

// ───────────────────────────────────────────────────────
// 3. 고정 페이지
// ───────────────────────────────────────────────────────
$about_content = '<!-- wp:paragraph -->
<p><strong>중흥복싱클럽</strong>은 광주 북구 서방로에 위치한 복싱 전문 체육관입니다. 관장님이 회원 한 사람 한 사람을 직접 지도하며, 기본기부터 실전 스파링까지 정통 복싱의 모든 것을 가르칩니다.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"주먹은 거짓말을 하지 않는다."</p><cite>— 홍관장</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:heading -->
<h2>우리의 원칙</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>관장 1:1 직접 지도 — 보조 트레이너에게 맡기지 않습니다</li>
<li>정통 복싱 커리큘럼 — 기본기부터 실전까지 단계별</li>
<li>안전한 실전 스파링 — 라운드·강도·상대를 세심하게 조절</li>
<li>전 연령 환영 — 초등·중·고·대학·직장인</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>체육관 소개와 시설 사진은 <a href="' . GYM_MAIN . '">체육관 공식 홈페이지</a>에서 자세히 확인하실 수 있습니다.</p>
<!-- /wp:paragraph -->';

$programs_content = '<!-- wp:paragraph -->
<p>중흥복싱클럽은 회원의 연령·체력·목표에 맞춰 4가지 트랙으로 운영됩니다.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>01 · 정통 복싱</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>잽·스트레이트·훅·어퍼컷. 펀치의 기본기부터 실전 콤비네이션까지 단계별 지도. 미트·샌드백 훈련과 관장 직접 피드백.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>02 · 복싱 다이어트</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>시간당 고칼로리 소모. 복싱의 유산소·근력 루틴으로 체지방을 깎습니다. 라운드제 서킷 구성으로 여성 회원에게도 인기.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>03 · 학생반 (초·중·대)</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>예절·집중력·체력. 복싱으로 아이와 학생들이 스스로 서는 법을 배웁니다. 연령별 안전 지도, 자신감·예의 교육 병행.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>04 · 직장인반·성인반</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>쌓인 스트레스를 샌드백에. 퇴근 후 운동 가능한 스케줄. 체형 관리·스트레스 해소·컨디션 향상을 한 번에.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>수강료와 체험 신청은 <strong>' . GYM_PHONE . '</strong>로 전화 주세요.</p>
<!-- /wp:paragraph -->';

$location_content = '<!-- wp:heading -->
<h2>주소</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>' . GYM_ADDRESS . '<br>구)중흥3동사무소 사거리 대로변 3층</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>전화</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><a href="tel:+82625219848">' . GYM_PHONE . '</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>운영시간</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>' . GYM_HOURS . '<br><em>※ 운영일·공휴일은 전화로 확인 부탁드립니다.</em></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>시설</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul>
<li>정규 규격 3단 로프 링 (태극기 아래 실전 스파링 공간)</li>
<li>헤비백 · 스피드백 · 미트 존</li>
<li>남녀 샤워실 완비</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p><a href="https://map.naver.com/p/search/%EC%A4%91%ED%9D%A5%EB%B3%B5%EC%8B%B1%ED%81%B4%EB%9F%BD" target="_blank" rel="noreferrer noopener">📍 네이버 지도에서 보기 →</a></p>
<!-- /wp:paragraph -->';

$contact_content = '<!-- wp:paragraph -->
<p>상담·등록·체험 문의는 아래 연락처로 주세요. 관장님이 직접 안내해드립니다.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>📞 전화</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><a href="tel:+82625219848"><strong>' . GYM_PHONE . '</strong></a><br>운영시간: ' . GYM_HOURS . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>📍 방문</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>' . GYM_ADDRESS . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>※ 문의 플러그인(Contact Form 7, WPForms 등) 설치 후 이 페이지에 폼을 삽입할 수 있습니다.</p>
<!-- /wp:paragraph -->';

$pages = array(
    array('title' => '체육관 소개',  'slug' => 'about',    'content' => $about_content),
    array('title' => '프로그램',     'slug' => 'programs', 'content' => $programs_content),
    array('title' => '오시는 길',    'slug' => 'location', 'content' => $location_content),
    array('title' => '문의',         'slug' => 'contact',  'content' => $contact_content),
);
$page_ids = array();
foreach ($pages as $p) {
    $existing = get_page_by_path($p['slug']);
    if ($existing) {
        $page_ids[$p['slug']] = $existing->ID;
        WP_CLI::log("  = 페이지 유지: {$p['title']} (id={$existing->ID})");
        continue;
    }
    $id = wp_insert_post(array(
        'post_title'   => $p['title'],
        'post_name'    => $p['slug'],
        'post_content' => $p['content'],
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ));
    $page_ids[$p['slug']] = $id;
    WP_CLI::log("  + 페이지: {$p['title']} (id=$id)");
}

// ───────────────────────────────────────────────────────
// 4. 주 메뉴 + primary 위치 할당
// ───────────────────────────────────────────────────────
$menu_name = '주 메뉴';
$menu = wp_get_nav_menu_object($menu_name);
$menu_id = $menu ? $menu->term_id : wp_create_nav_menu($menu_name);
WP_CLI::log($menu ? "  = 메뉴 유지: $menu_name" : "  + 메뉴 생성: $menu_name");

function jungheung_ensure_menu_item($menu_id, $title, $type, $target, $extra = array()) {
    $items = wp_get_nav_menu_items($menu_id);
    foreach ((array) $items as $item) {
        if ($item->title === $title) {
            return $item->ID;
        }
    }
    $args = array_merge(array(
        'menu-item-title'  => $title,
        'menu-item-status' => 'publish',
        'menu-item-type'   => $type,
    ), $extra);
    if ($type === 'custom') {
        $args['menu-item-url'] = $target;
    } elseif ($type === 'post_type' || $type === 'post_type_archive' || $type === 'taxonomy') {
        $args['menu-item-object-id'] = $target;
    }
    return wp_update_nav_menu_item($menu_id, 0, $args);
}

jungheung_ensure_menu_item($menu_id, '홈', 'custom', home_url('/'));
if (!empty($page_ids['about'])) {
    jungheung_ensure_menu_item($menu_id, '체육관 소개', 'post_type', $page_ids['about'], array('menu-item-object' => 'page'));
}
if (!empty($page_ids['programs'])) {
    jungheung_ensure_menu_item($menu_id, '프로그램', 'post_type', $page_ids['programs'], array('menu-item-object' => 'page'));
}
jungheung_ensure_menu_item($menu_id, '🥊 중흥복싱', 'post_type_archive', 'boxing', array('menu-item-object' => 'boxing'));
jungheung_ensure_menu_item($menu_id, '📋 손해사정', 'post_type_archive', 'adjuster', array('menu-item-object' => 'adjuster'));
if (!empty($page_ids['location'])) {
    jungheung_ensure_menu_item($menu_id, '오시는 길', 'post_type', $page_ids['location'], array('menu-item-object' => 'page'));
}
if (!empty($page_ids['contact'])) {
    jungheung_ensure_menu_item($menu_id, '문의', 'post_type', $page_ids['contact'], array('menu-item-object' => 'page'));
}
jungheung_ensure_menu_item($menu_id, '체육관 홈 ↗', 'custom', GYM_MAIN, array('menu-item-classes' => 'menu-external'));

$locations = get_theme_mod('nav_menu_locations');
if (!is_array($locations)) {
    $locations = array();
}
$locations['primary'] = (int) $menu_id;
set_theme_mod('nav_menu_locations', $locations);
WP_CLI::log("  ⚑ primary 위치에 '$menu_name' 할당");

// ───────────────────────────────────────────────────────
// 5. 샘플 글 (CPT: boxing + 카테고리글 하나)
// ───────────────────────────────────────────────────────
$welcome_content = '<!-- wp:paragraph -->
<p>안녕하세요, 중흥복싱클럽입니다. 오늘 블로그를 오픈했습니다.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>앞으로 이곳에서 체육관 공지, 회원 수업 후기, 복싱 기초 이론, 대회 소식 등을 꾸준히 공유하겠습니다. 자주 들러주세요.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"기본기 없는 화려함은 세 라운드도 버티지 못한다."</p></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>체육관 방문·체험·상담은 <a href="tel:+82625219848">' . GYM_PHONE . '</a>로 전화 주세요.</p>
<!-- /wp:paragraph -->';

if (!get_page_by_path('welcome', OBJECT, 'boxing')) {
    wp_insert_post(array(
        'post_title'   => '중흥복싱클럽 블로그를 오픈했습니다',
        'post_name'    => 'welcome',
        'post_type'    => 'boxing',
        'post_status'  => 'publish',
        'post_content' => $welcome_content,
    ));
    WP_CLI::log("  + 샘플 글: 중흥복싱클럽 블로그를 오픈했습니다");
}

$basics_content = '<!-- wp:paragraph -->
<p>복싱을 처음 배우는 분들이 가장 먼저 만나는 네 가지 펀치를 정리합니다.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>01 · 잽 (JAB) — 견제의 시작</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>앞손으로 뻗는 직선 펀치. 거리를 재고 상대의 리듬을 깨는 가장 기본적인 기술입니다. 모든 공방의 출발점.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>02 · 스트레이트 (STRAIGHT) — 결정타</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>뒷손으로 밀어넣는 직선 펀치. 체중과 골반 회전을 실어 던지는 복싱의 간판 펀치입니다. 잽 뒤에 이어지는 원투의 "투".</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>03 · 훅 (HOOK) — 근거리의 칼</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>팔을 갈고리처럼 휘두르는 옆 궤적 펀치. 상대의 가드 옆을 노리는 근거리 공방의 핵심. 빗맞아도 무겁습니다.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>04 · 어퍼컷 (UPPERCUT) — 가드 사이로</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>아래에서 위로 올려치는 펀치. 상대의 가드 사이 턱을 겨누는 결정타. 클린치·근접 상황에서 특히 위력적입니다.</p>
<!-- /wp:paragraph -->';

$basics_cat = get_term_by('slug', 'basics', 'category');
if ($basics_cat && !get_page_by_path('basic-punches-4', OBJECT, 'post')) {
    $post_id = wp_insert_post(array(
        'post_title'   => '기본 펀치 4종 — 잽·스트레이트·훅·어퍼컷',
        'post_name'    => 'basic-punches-4',
        'post_type'    => 'post',
        'post_status'  => 'publish',
        'post_content' => $basics_content,
    ));
    wp_set_post_categories($post_id, array((int) $basics_cat->term_id));
    WP_CLI::log("  + 샘플 글: 기본 펀치 4종 (복싱 기초 카테고리)");
}

// ───────────────────────────────────────────────────────
// 완료
// ───────────────────────────────────────────────────────
update_option('jungheung_seeded', $SEED_VERSION);
flush_rewrite_rules(false);
WP_CLI::success("시딩 완료 (version $SEED_VERSION)");
