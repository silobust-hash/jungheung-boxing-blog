<?php get_header(); ?>

<main class="archive-wrap">
    <header class="archive-header">
        <h1 class="archive-title">
            <?php printf(esc_html__('"%s" 검색 결과', 'jungheung'), esc_html(get_search_query())); ?>
        </h1>
    </header>

    <?php if (have_posts()) : ?>
        <div class="post-list">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-card'); ?>>
                    <div class="post-card-body">
                        <h2 class="post-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="post-meta">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                        </div>
                        <div class="post-card-excerpt"><?php the_excerpt(); ?></div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php
        the_posts_pagination(array(
            'prev_text' => '← 이전',
            'next_text' => '다음 →',
        ));
        ?>
    <?php else : ?>
        <div class="no-posts">
            <p>검색 결과가 없습니다. 다른 키워드로 시도해 보세요.</p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
