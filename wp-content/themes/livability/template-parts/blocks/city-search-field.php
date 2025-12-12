<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ); ?></span>
    </label>
    <input type="search" id="post-autocomplete-search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Posts...', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search:', 'label' ); ?>" />

    <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
</form>