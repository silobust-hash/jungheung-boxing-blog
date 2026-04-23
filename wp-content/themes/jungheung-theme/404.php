<?php get_header(); ?>

<main class="error-wrap">
    <div class="error-box">
        <h1 class="error-code">404</h1>
        <p class="error-message">찾으시는 페이지가 없거나 이동되었습니다.</p>
        <?php get_search_form(); ?>
        <p><a class="btn-home" href="<?php echo esc_url(home_url('/')); ?>">홈으로 돌아가기</a></p>
    </div>
</main>

<?php get_footer(); ?>
