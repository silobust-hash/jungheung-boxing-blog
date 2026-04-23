    <footer class="site-footer">
        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-widgets">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>
        <?php endif; ?>

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
            &copy; <?php echo esc_html(date('Y')); ?>
            <?php bloginfo('name'); ?>. All Rights Reserved.
        </p>
    </footer>

    <?php wp_footer(); ?>
</body>

</html>
