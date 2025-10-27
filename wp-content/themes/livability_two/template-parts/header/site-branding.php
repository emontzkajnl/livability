<?php
/**
 * Displays header site branding
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

$blog_info    = get_bloginfo( 'name' );
$description  = get_bloginfo( 'description', 'display' );
$show_title   = ( true === get_theme_mod( 'display_title_and_tagline', true ) );
$header_class = $show_title ? 'site-title' : 'screen-reader-text';

?>


<div class="site-branding">

	<div class="site-logo">
		<?php if (is_front_page(  )):   ?>
			<img  height="50" width="201" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo3.svg" alt="livability" class="custom-logo">
		<?php else: ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link"><img height="50" width="201" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo3.svg" alt="livability" class="custom-logo"></a>
		<?php endif; ?>
	</div>



	
</div><!-- .site-branding -->
<?php //} ?>
