<?php
/**
 * The template for displaying all single posts
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
	$year_tax = get_the_terms(get_the_ID(), 'best_places_years' );
	if ($year_tax && $year_tax[0]->name == '2023') {
		get_template_part( 'template-parts/content/content-2023-single-best-places' );
	} else {
		get_template_part( 'template-parts/bp-hero-section' );
		get_template_part( 'template-parts/content/content-single-best-places-no-blocks' );
	}
	


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

	// Previous/next post navigation.
	// $twentytwentyone_next = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' );
	// $twentytwentyone_prev = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' );

	// $twentytwentyone_next_label     = esc_html__( 'Next post', 'livablity' );
	// $twentytwentyone_previous_label = esc_html__( 'Previous post', 'livablity' );


endwhile; // End of the loop.


get_footer();
