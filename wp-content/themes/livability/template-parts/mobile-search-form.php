<?php
/**
 * The searchform.php template.
 *
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package WordPress
 */

$liv_unique_id = wp_unique_id( 'search-form-' );
?>

<form method="get" class="mobile-search-form" action="<?php echo esc_url( home_url( '/' )); ?>">
    <label for="<?php echo esc_attr( $liv_unique_id ); ?>"><?php _e('Search&hellip;','ag-sites') ?></label>
    <input type="search" placeholder="Search..." class="search-field" name="s" id="<?php echo esc_attr( $liv_unique_id ); ?>">
</form>