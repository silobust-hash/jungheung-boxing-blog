<?php get_header(); ?>

<?php
$character_path = get_template_directory() . '/assets/images/character.png';
$character_url  = get_template_directory_uri() . '/assets/images/character.png';
?>

<main class="error-wrap">
    <div class="error-box">
        <?php if (file_exists($character_path)) : ?>
            <img class="error-mascot" src="<?php echo esc_url($character_url); ?>" alt="" loading="lazy">
        <?php endif; ?>
        <h1 class="error-code">404</h1>
        <p class="error-message">찾으시는 페이지가 KO 당했습니다.</p>
        <p class="error-sub">링크가 오래됐거나 주소를 잘못 입력하셨을 수 있어요.</p>
        <?php get_search_form(); ?>
        <p><a class="btn-home" href="<?php echo esc_url(home_url('/')); ?>">← 홈으로 돌아가기</a></p>
    </div>
</main>

<?php get_footer(); ?>
