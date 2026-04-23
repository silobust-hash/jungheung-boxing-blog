    <footer class="site-footer">
        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-widgets">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>
        <?php endif; ?>

        <div class="footer-main">
            <div class="footer-brand">
                <p class="footer-title">중흥복싱클럽</p>
                <p class="footer-sub"><?php echo esc_html(JUNGHEUNG_EN_NAME); ?> · HEAD COACH HONG</p>
                <p class="footer-tagline">&ldquo;<?php echo esc_html(JUNGHEUNG_TAGLINE); ?>&rdquo;</p>
            </div>

            <div class="footer-contact">
                <p class="footer-label">CONTACT</p>
                <p>
                    <a class="footer-phone-link" href="tel:<?php echo esc_attr(JUNGHEUNG_PHONE_TEL); ?>">
                        📞 <?php echo esc_html(JUNGHEUNG_PHONE); ?>
                    </a>
                </p>
                <p>📍 <?php echo esc_html(JUNGHEUNG_ADDRESS); ?></p>
                <p>🕓 <?php echo esc_html(JUNGHEUNG_HOURS); ?></p>
                <p>
                    <a href="<?php echo esc_url(JUNGHEUNG_NAVER_MAP); ?>" target="_blank" rel="noopener">네이버 지도에서 보기 →</a>
                </p>
            </div>

            <div class="footer-links">
                <p class="footer-label">BLOG</p>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">블로그 홈</a></li>
                    <?php if ($archive = get_post_type_archive_link('boxing')) : ?>
                        <li><a href="<?php echo esc_url($archive); ?>">🥊 중흥복싱 글</a></li>
                    <?php endif; ?>
                    <?php if ($archive = get_post_type_archive_link('adjuster')) : ?>
                        <li><a href="<?php echo esc_url($archive); ?>">📋 손해사정 글</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo esc_url(JUNGHEUNG_MAIN_SITE); ?>" rel="noopener"><?php echo esc_html(JUNGHEUNG_MAIN_SITE_LABEL); ?></a></li>
                </ul>
            </div>
        </div>

        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-nav" aria-label="푸터 메뉴">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'menu-footer',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ));
                ?>
            </nav>
        <?php endif; ?>

        <p class="site-copyright">
            &copy; <?php echo esc_html(date('Y')); ?> 중흥복싱클럽. All Rights Reserved.
            &nbsp;·&nbsp; <?php echo esc_html(JUNGHEUNG_ADDRESS); ?> &nbsp;·&nbsp; <?php echo esc_html(JUNGHEUNG_PHONE); ?>
        </p>
    </footer>

    <?php wp_footer(); ?>
</body>

</html>
