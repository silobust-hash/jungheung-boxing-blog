<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php bloginfo('name'); ?> | 손해사정사 홍덕연 & 중흥복싱클럽 홍관장
    </title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
        <div class="logo-container">
            <?php
            $logo_path = get_template_directory_uri() . '/assets/images/character.png';
            ?>
            <img src="<?php echo esc_url($logo_path); ?>" alt="홍관장 캐릭터" class="character-logo">
            <h1><a href="<?php echo esc_url(home_url('/')); ?>" style="text-decoration:none; color:inherit;">
                    손해사정사 홍덕연 &amp; 중흥복싱클럽 홍관장
                </a></h1>
        </div>
        <nav class="main-nav">
            <!-- Add future menu items here -->
        </nav>
    </header>