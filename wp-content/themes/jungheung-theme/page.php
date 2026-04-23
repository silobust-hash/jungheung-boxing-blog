<?php get_header(); ?>

<main class="single-wrap">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('post-single'); ?>>
            <header class="post-header">
                <h1 class="post-title"><?php the_title(); ?></h1>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumb"><?php the_post_thumbnail('large'); ?></div>
            <?php endif; ?>

            <div class="post-content">
                <?php the_content(); ?>
            </div>
        </article>

        <?php if (comments_open() || get_comments_number()) : comments_template(); endif; ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
