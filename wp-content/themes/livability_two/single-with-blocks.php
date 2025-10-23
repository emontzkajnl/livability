<?php
/**
 * Template Name: Post with blocks
 * Template Post Type: post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();



/* Start the Loop */
//echo '<div class="container alm-wrapper">';
while ( have_posts() ) :
	the_post(); ?>
	<div id="article-wrapper">
	<!-- <div class="hero-section alignfull"  style="background-image: url('<?php //echo get_the_post_thumbnail_url(); ?>')"> -->
	<!-- </div> -->
	<?php get_template_part( 'template-parts/content/content-single' ); ?>

	<!-- BEGIN INFINITE SCROLL OF PREVIOUS POSTS -->
	<!-- <h2 class="green-line">More Articles</h2> -->
	<!-- <div class="container" id="alm-single-wrapper"> -->
	<!-- <div class="alm-container"> -->
	
	<?php echo do_shortcode( '[ajax_load_more single_post="true" single_post_id="'.get_the_ID().'" single_post_order="previous" single_post_taxonomy="category"  single_post_target="#article-wrapper" post_type="post" pause_override="true" loading_style="infinite classic"]' ); ?>
	<!-- </div> -->
</div>
	


	<?php //if ( is_attachment() ) {
		// Parent post navigation.
		//the_post_navigation(
			//array(
				/* translators: %s: parent post link. */
				//'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentytwentyone' ), '%title' ),
			//)
		//);
	//}


endwhile; // End of the loop.

get_footer();