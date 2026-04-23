<?php get_header(); ?>

<main class="single-wrap">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('post-single'); ?>>
            <header class="post-header">
                <?php
                foreach (get_the_category() as $t) {
                    $extra = $t->slug === 'adjuster' ? ' cat-adjuster' : '';
                    printf(
                        '<a class="post-cat%s" href="%s">%s</a>',
                        esc_attr($extra),
                        esc_url(get_category_link($t)),
                        esc_html($t->name)
                    );
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
