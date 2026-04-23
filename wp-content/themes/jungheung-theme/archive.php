<?php get_header(); ?>

<main class="archive-wrap">
    <header class="archive-header">
        <h1 class="archive-title">
            <?php
            if (is_category('adjuster')) {
                echo '📋 손해사정사 업무';
            } else {
                the_archive_title();
            }
            ?>
        </h1>
        <?php the_archive_description('<p class="archive-desc">', '</p>'); ?>
    </header>

    <?php if (have_posts()) : ?>
        <div class="post-list">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a class="post-card-thumb" href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium_large'); ?>
                        </a>
                    <?php endif; ?>
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
        <p class="no-posts">아직 등록된 게시글이 없습니다.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
