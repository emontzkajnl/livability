<?php
/**
 * The template for displaying front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

 // If we have this page, we will output it's static content as homepage
//  $homepage_obj = get_page_by_title( 'Homepage Output');
//  if ($homepage_obj && file_exists(WP_CONTENT_DIR.'/uploads/homepage-content/homepage-output.html')):
//     include_once(WP_CONTENT_DIR.'/uploads/homepage-content/homepage-output.html');
//  else:

get_header();
$hero_section = get_field('display_hero');
if ($hero_section) {
	get_template_part( 'template-parts/page-hero-section' );
}

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content/content-page' );

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile; // End of the loop.
// dynamic_sidebar( 'sidebar-2' );

get_footer();
// endif;