<?php
/**
 * Plugin Name: Jungheung Site Defaults
 * Description: 사이트 기본 설정(한국 시간대, 고유주소, 보안 강화 등)을 자동 적용합니다. mu-plugin 이라 비활성화 불가.
 * Version: 1.0.0
 * Author: Antigravity
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 최초 1회만 도는 기본값 세팅.
 * 새 옵션이 추가되면 OPTION_VERSION 을 올려 재적용 유도.
 */
add_action('init', function () {
    $installed = get_option('jungheung_defaults_version');
    $target    = '1.0.0';
    if ($installed === $target) {
        return;
    }

    if (get_option('timezone_string') !== 'Asia/Seoul') {
        update_option('timezone_string', 'Asia/Seoul');
        update_option('gmt_offset', '');
    }
    if (get_option('date_format') !== 'Y년 n월 j일') {
        update_option('date_format', 'Y년 n월 j일');
    }
    if (get_option('time_format') !== 'A g:i') {
        update_option('time_format', 'A g:i');
    }

    if (get_option('permalink_structure') !== '/%postname%/') {
        update_option('permalink_structure', '/%postname%/');
        if (function_exists('flush_rewrite_rules')) {
            flush_rewrite_rules(false);
        }
    }

    update_option('blog_public', '1');
    update_option('default_ping_status', 'closed');
    update_option('default_pingback_flag', '');

    update_option('jungheung_defaults_version', $target);
});

// XML-RPC 비활성화 (로그인 무차별 대입 공격 경로 차단)
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function ($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});

// WP 버전 노출 제거
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// REST API 에서 사용자 열거 차단 (비로그인 기준)
add_filter('rest_endpoints', function ($endpoints) {
    if (is_user_logged_in()) {
        return $endpoints;
    }
    foreach (array('/wp/v2/users', '/wp/v2/users/(?P<id>[\d]+)') as $route) {
        if (isset($endpoints[$route])) {
            unset($endpoints[$route]);
        }
    }
    return $endpoints;
});

// 사이트맵에서 사용자(작성자) 목록 제거 — 개인정보 보호.
// 글/페이지/CPT 사이트맵은 그대로 유지.
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
    if ($name === 'users') {
        return false;
    }
    return $provider;
}, 10, 2);

// robots.txt 에 사이트맵 URL 명시 (검색엔진 자동 발견용)
add_filter('robots_txt', function ($output, $public) {
    if ((int) $public === 1) {
        $sitemap_url = home_url('/wp-sitemap.xml');
        $output .= "\nSitemap: $sitemap_url\n";
    }
    return $output;
}, 10, 2);

// author=숫자 로 사용자명 탐색하는 열거 차단
add_action('template_redirect', function () {
    if (!is_admin() && isset($_GET['author']) && is_numeric($_GET['author'])) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
});

// 검색 페이지는 검색엔진에 넣지 않음
add_action('wp_head', function () {
    if (is_search() || is_404()) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    }
}, 1);

// 외부 이미지 lazy-load 는 WP 가 기본 처리. 추가로 async/defer 로 프론트 스크립트 가볍게.
add_filter('script_loader_tag', function ($tag, $handle) {
    if (is_admin()) {
        return $tag;
    }
    if (in_array($handle, array('comment-reply'), true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}, 10, 2);
