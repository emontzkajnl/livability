<?php
/**
 *
 * The template for displaying with the blocks single posts
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
	the_post(); ?>
	<script src="https://static.addtoany.com/menu/page.js"></script>
	<?php
	$sponsored_args = array(
		'post_type'			=> 'post',
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish',
		'meta_key'		=> 'sponsored',
		'meta_value'		=> true, 
	);
	$sponsored_query = new WP_Query($sponsored_args);
	$sponsored_ids = [];
	$sponsored_global_ids = [];
	$sponsored_state_ids = [];
	if ($sponsored_query->have_posts()):
		while ($sponsored_query->have_posts()): $sponsored_query->the_post();
		// echo get_the_title().'<br />';
		$type = get_field('sponsor_type');
		// echo 'sponsor_type is '.get_field('sponsor_type').'<br />';
		if ($type == 'global') {
			// echo 'global post is '.get_the_title().'<br />';
			$sponsored_global_ids[] = get_the_id();
		} elseif ($type == 'state') {
			// echo 'state post is '.get_the_title().'<br />';
			$sponsored_state_ids[] = get_the_id();
		} else {
			// echo 'city post is '.get_the_title().'<br />';
			$sponsored_ids[] = get_the_id();
		}
		
		endwhile;
	endif;
	wp_reset_query();
	// echo 'here are sponsored ids';
	// print_r($sponsored_ids);
	?>

	<div id="article-wrapper">
	<?php get_template_part( 'template-parts/content/content-single-no-blocks', null, array('city' => $sponsored_ids, 'global' =>$sponsored_global_ids, 'state' => $sponsored_state_ids )); ?>
	</div> 


		<!-- place_relationship -->
		<?php 

		// CODE MOVED TO FUNCTIONS.PHP
		// $places = get_field('place_relationship'); 
		// if ($places) {
		// 	$implode_places = implode(',', $places); // franklin id: 54186
		// 	$place_rel_query = 'meta_key="place_relationship" meta_compare="IN" meta_value_num="'.$implode_places.'"';
		// } else {
		// 	$place_rel_query = '';
		// }
?>
	<?php echo do_shortcode( '[ajax_load_more id="custom_single_query" single_post_taxonomy="category" single_post="true" single_post_id="'.get_the_ID().'"  single_post_order="previous" cache="false" single_post_target="#article-wrapper"  post_type="post" pause_override="true" loading_style="infinite classic" scroll_distance="100"]' );

endwhile; // End of the loop.


get_footer();