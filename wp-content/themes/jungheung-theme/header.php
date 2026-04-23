<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
    <a class="skip-link screen-reader-text" href="#site-main">본문으로 건너뛰기</a>

    <header class="site-header">
        <div class="header-inner">
            <div class="logo-container">
                <?php
                $logo_path = get_template_directory() . '/assets/images/character.png';
                $logo_url  = get_template_directory_uri() . '/assets/images/character.png';
                ?>
                <a class="logo-link" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php if (has_custom_logo()) : the_custom_logo(); ?>
                    <?php elseif (file_exists($logo_path)) : ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="character-logo">
                    <?php endif; ?>
                    <span class="site-title-group">
                        <span class="site-title-text"><?php bloginfo('name'); ?></span>
                        <span class="site-title-sub"><?php echo esc_html(JUNGHEUNG_EN_NAME); ?> · BLOG</span>
                    </span>
                </a>
            </div>

            <nav class="main-nav" aria-label="주 메뉴">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'menu-primary',
                        'depth'          => 2,
                        'fallback_cb'    => false,
                    ));
                } else {
                    echo '<ul class="menu-primary">';
                    printf('<li><a href="%s">홈</a></li>', esc_url(home_url('/')));
                    printf('<li><a href="%s">🥊 중흥복싱</a></li>', esc_url(get_post_type_archive_link('boxing')));
                    printf('<li><a href="%s">📋 손해사정</a></li>', esc_url(get_post_type_archive_link('adjuster')));
                    printf('<li class="menu-external"><a href="%s" rel="noopener">%s</a></li>',
                        esc_url(JUNGHEUNG_MAIN_SITE),
                        esc_html(JUNGHEUNG_MAIN_SITE_LABEL)
                    );
                    echo '</ul>';
                }
                ?>
            </nav>

            <a class="header-phone" href="tel:<?php echo esc_attr(JUNGHEUNG_PHONE_TEL); ?>" aria-label="전화 문의">
                <span class="phone-icon">📞</span>
                <span class="phone-number"><?php echo esc_html(JUNGHEUNG_PHONE); ?></span>
            </a>
        </div>
    </header>
