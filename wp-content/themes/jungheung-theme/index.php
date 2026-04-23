<?php get_header(); ?>

<main>
    <section class="hero">
        <h2>블로그에 오신 것을 환영합니다</h2>
        <p>복싱의 열정과 손해사정의 전문성이 함께하는 공간입니다.</p>
    </section>

    <div class="split-section">
        <!-- Boxing Section -->
        <section class="category-card boxing">
            <h3>🥊 중흥복싱클럽 이야기</h3>
            <?php
            $boxing_query = new WP_Query(array('post_type' => 'boxing', 'posts_per_page' => 5));
            if ($boxing_query->have_posts()):
                while ($boxing_query->have_posts()):
                    $boxing_query->the_post();
                    ?>
                    <div class="post-item">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                        <div class="post-meta">
                            <?php echo get_the_date(); ?>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                echo '<p>아직 등록된 게시글이 없습니다.</p>';
            endif;
            ?>
        </section>

        <!-- Adjuster Section -->
        <section class="category-card adjuster">
            <h3>📋 손해사정사 업무</h3>
            <?php
            $adjuster_query = new WP_Query(array('post_type' => 'adjuster', 'posts_per_page' => 5));
            if ($adjuster_query->have_posts()):
                while ($adjuster_query->have_posts()):
                    $adjuster_query->the_post();
                    ?>
                    <div class="post-item">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                        <div class="post-meta">
                            <?php echo get_the_date(); ?>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                echo '<p>아직 등록된 게시글이 없습니다.</p>';
            endif;
            ?>
        </section>
    </div>
</main>

<?php get_footer(); ?>