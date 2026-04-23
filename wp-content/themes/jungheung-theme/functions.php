<?php
// Theme settings
function jungheung_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'jungheung_theme_setup');

// Enqueue styles
function jungheung_enqueue_styles() {
    wp_enqueue_style('jungheung-style', get_stylesheet_uri(), array(), time());
}
add_action('wp_enqueue_scripts', 'jungheung_enqueue_styles');

// Custom Post Types
function jungheung_register_cpt() {
    register_post_type('boxing', array(
        'labels' => array(
            'name' => '복싱 이야기',
            'singular_name' => '복싱'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-groups'
    ));

    register_post_type('adjuster', array(
        'labels' => array(
            'name' => '손해사정사 업무',
            'singular_name' => '손해사정'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-clipboard'
    ));
}
add_action('init', 'jungheung_register_cpt');
