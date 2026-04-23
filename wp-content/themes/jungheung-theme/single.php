<?php get_header(); ?>

<main class="single-wrap">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('post-single'); ?>>
            <header class="post-header">
                <?php
                $terms = get_the_category();
                if (empty($terms)) {
                    $post_type = get_post_type();
                    if ($post_type === 'boxing') {
                        echo '<span class="post-cat cat-boxing">🥊 중흥복싱클럽</span>';
                    } elseif ($post_type === 'adjuster') {
                        echo '<span class="post-cat cat-adjuster">📋 손해사정</span>';
                    }
                } else {
                    foreach ($terms as $t) {
                        printf('<a class="post-cat" href="%s">%s</a>', esc_url(get_category_link($t)), esc_html($t->name));
                    }
                }
                ?>
                <h1 class="post-title"><?php the_title(); ?></h1>
                <div class="post-meta">
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    <span class="meta-sep">·</span>
                    <span><?php the_author(); ?></span>
                </div>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumb"><?php the_post_thumbnail('large'); ?></div>
            <?php endif; ?>

            <div class="post-content">
                <?php the_content(); ?>
                <?php
                wp_link_pages(array(
                    'before' => '<nav class="post-pages">' . esc_html__('페이지:', 'jungheung'),
                    'after'  => '</nav>',
                ));
                ?>
            </div>

            <footer class="post-footer">
                <?php the_tags('<div class="post-tags">', ' ', '</div>'); ?>
            </footer>
        </article>

        <nav class="post-nav">
            <div class="prev"><?php previous_post_link('%link', '← %title'); ?></div>
            <div class="next"><?php next_post_link('%link', '%title →'); ?></div>
        </nav>

        <?php if (comments_open() || get_comments_number()) : comments_template(); endif; ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
