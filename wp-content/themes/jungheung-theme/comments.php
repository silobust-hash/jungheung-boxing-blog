<?php
if (post_password_required()) {
    return;
}
?>
<section id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $count = get_comments_number();
            if ($count === '1') {
                echo '댓글 1개';
            } else {
                printf('댓글 %s개', number_format_i18n($count));
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' => 40,
            ));
            ?>
        </ol>

        <?php
        the_comments_pagination(array(
            'prev_text' => '← 이전',
            'next_text' => '다음 →',
        ));

        if (!comments_open()) : ?>
            <p class="no-comments">댓글이 마감되었습니다.</p>
        <?php endif;
    endif;

    comment_form(array(
        'title_reply' => '댓글 남기기',
        'label_submit' => '등록',
    ));
    ?>
</section>
