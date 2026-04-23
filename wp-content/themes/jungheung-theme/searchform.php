<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field"><?php esc_html_e('검색:', 'jungheung'); ?></label>
    <input id="search-field" type="search" class="search-input" placeholder="검색어를 입력하세요"
        value="<?php echo esc_attr(get_search_query()); ?>" name="s">
    <button type="submit" class="search-submit">검색</button>
</form>
