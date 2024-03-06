<?php
/**
 * Template Name: Place v2
 * Template Post Type: liv_place
 *
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
while ( have_posts() ) :
	the_post();
	
	
	// !get_field('place_type') == 'state' && 
	if ((!get_field('client_place')) && (get_field('place_type') != 'state')) {
		get_template_part( 'template-parts/content/content-places-v2' );
	} else {
		get_template_part( 'template-parts/hero-section' );
		get_template_part( 'template-parts/content/content-places-v2' );
	}


endwhile; // End of the loop.

get_footer();
