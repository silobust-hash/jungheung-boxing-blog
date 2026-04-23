<?php get_header(); ?>

<main id="site-main" class="home-main">
    <?php if (is_home() && !is_front_page()) : ?>
        <header class="archive-header">
            <h1 class="archive-title"><?php single_post_title(); ?></h1>
        </header>

        <div class="post-list">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
            <?php endwhile; else : ?>
                <p class="no-posts">아직 등록된 게시글이 없습니다.</p>
            <?php endif; ?>
        </div>

        <?php
        the_posts_pagination(array(
            'prev_text' => '← 이전',
            'next_text' => '다음 →',
        ));
        ?>
    <?php else : ?>
        <section class="hero">
            <h2><?php echo esc_html(get_bloginfo('name')); ?></h2>
            <p><?php echo esc_html(get_bloginfo('description')); ?></p>
        </section>

        <div class="split-section">
            <section class="category-card boxing">
                <h3>🥊 중흥복싱클럽 이야기</h3>
                <?php
                $boxing_query = new WP_Query(array(
                    'post_type'      => 'boxing',
                    'posts_per_page' => 5,
                ));
                if ($boxing_query->have_posts()) :
                    while ($boxing_query->have_posts()) : $boxing_query->the_post(); ?>
                        <div class="post-item">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <div class="post-meta"><?php echo esc_html(get_the_date()); ?></div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                    $archive = get_post_type_archive_link('boxing');
                    if ($archive) :
                        printf('<a class="card-more" href="%s">더 보기 →</a>', esc_url($archive));
                    endif;
                else :
                    echo '<p class="no-posts">아직 등록된 게시글이 없습니다.</p>';
                endif;
                ?>
            </section>

            <section class="category-card adjuster">
                <h3>📋 손해사정사 업무</h3>
                <?php
                $adjuster_query = new WP_Query(array(
                    'post_type'      => 'adjuster',
                    'posts_per_page' => 5,
                ));
                if ($adjuster_query->have_posts()) :
                    while ($adjuster_query->have_posts()) : $adjuster_query->the_post(); ?>
                        <div class="post-item">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <div class="post-meta"><?php echo esc_html(get_the_date()); ?></div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                    $archive = get_post_type_archive_link('adjuster');
                    if ($archive) :
                        printf('<a class="card-more" href="%s">더 보기 →</a>', esc_url($archive));
                    endif;
                else :
                    echo '<p class="no-posts">아직 등록된 게시글이 없습니다.</p>';
                endif;
                ?>
            </section>
        </div>

        <?php
        $recent = new WP_Query(array(
            'post_type'      => 'post',
            'posts_per_page' => 6,
            'ignore_sticky_posts' => true,
        ));
        if ($recent->have_posts()) : ?>
            <section class="recent-section">
                <h3 class="section-title">최근 블로그 글</h3>
                <div class="post-list post-list-grid">
                    <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                        <article <?php post_class('post-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a class="post-card-thumb" href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('jungheung-card'); ?>
                                </a>
                            <?php endif; ?>
                            <div class="post-card-body">
                                <h4 class="post-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="post-meta"><?php echo esc_html(get_the_date()); ?></div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
