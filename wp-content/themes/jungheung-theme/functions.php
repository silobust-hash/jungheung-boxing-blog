<?php
if (!defined('ABSPATH')) {
    exit;
}

define('JUNGHEUNG_THEME_VERSION', '1.1.0');

function jungheung_theme_setup() {
    load_theme_textdomain('jungheung', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 80,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('editor-styles');

    set_post_thumbnail_size(1200, 630, true);
    add_image_size('jungheung-card', 600, 400, true);

    register_nav_menus(array(
        'primary' => __('주 메뉴', 'jungheung'),
        'footer'  => __('푸터 메뉴', 'jungheung'),
    ));
}
add_action('after_setup_theme', 'jungheung_theme_setup');

function jungheung_enqueue_styles() {
    $style_path = get_template_directory() . '/style.css';
    $version = file_exists($style_path) ? filemtime($style_path) : JUNGHEUNG_THEME_VERSION;
    wp_enqueue_style('jungheung-style', get_stylesheet_uri(), array(), $version);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'jungheung_enqueue_styles');

function jungheung_register_cpt() {
    register_post_type('boxing', array(
        'labels' => array(
            'name'          => '복싱 이야기',
            'singular_name' => '복싱',
            'add_new_item'  => '새 복싱 글 작성',
            'edit_item'     => '복싱 글 편집',
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'boxing'),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'menu_icon'    => 'dashicons-groups',
        'show_in_rest' => true,
    ));

    register_post_type('adjuster', array(
        'labels' => array(
            'name'          => '손해사정사 업무',
            'singular_name' => '손해사정',
            'add_new_item'  => '새 손해사정 글 작성',
            'edit_item'     => '손해사정 글 편집',
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'adjuster'),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'menu_icon'    => 'dashicons-clipboard',
        'show_in_rest' => true,
    ));
}
add_action('init', 'jungheung_register_cpt');

function jungheung_widgets_init() {
    register_sidebar(array(
        'name'          => __('사이드바', 'jungheung'),
        'id'            => 'sidebar-main',
        'description'   => __('글 목록 우측에 표시되는 영역입니다.', 'jungheung'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    register_sidebar(array(
        'name'          => __('푸터', 'jungheung'),
        'id'            => 'footer-1',
        'description'   => __('푸터에 표시되는 영역입니다.', 'jungheung'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'jungheung_widgets_init');

function jungheung_excerpt_length($length) {
    return 40;
}
add_filter('excerpt_length', 'jungheung_excerpt_length', 999);

function jungheung_excerpt_more($more) {
    return '…';
}
add_filter('excerpt_more', 'jungheung_excerpt_more');

function jungheung_body_classes($classes) {
    if (is_singular() && !is_front_page()) {
        $classes[] = 'has-single-layout';
    }
    return $classes;
}
add_filter('body_class', 'jungheung_body_classes');
