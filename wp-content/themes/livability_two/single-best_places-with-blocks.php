<?php
/**
 * Template Name: Best places with blocks
 * Template Post Type: best_places
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();



/* Start the Loop */
// echo '<div class="container">';
while ( have_posts() ) :
	the_post();
		get_template_part( 'template-parts/bp-hero-section' );
		get_template_part( 'template-parts/content/content-single-best-places' );

	wp_link_pages(
		array(
			'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
			'after'    => '</nav>',
			/* translators: %: page number. */
			'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
		)
	);


	if ( is_attachment() ) {
		// Parent post navigation.
		the_post_navigation(
			array(
				/* translators: %s: parent post link. */
				'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentytwentyone' ), '%title' ),
			)
		);
	}

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}


endwhile; // End of the loop.


get_footer();
