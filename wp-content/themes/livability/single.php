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
session_start();
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

		$places = get_field('place_relationship'); 
		$cat = get_the_category();
		$alm_posts = array();
		$today = getdate();
		// CREATE MANUAL ARRAY TO CONTROL ALM
		$alm_args = array(
			'numberposts' 			=> 100,
			'post_type'				=> 'post',
			'post_status'			=> 'publish', 
			'date_query'        => array(
				array(
					'after'     => $today[ 'month' ] . ' 1st, ' . ($today[ 'year' ] - 3)
				)
			)
		);
		if ($places) {
			if (count($places) == 1) {
				$alm_args['meta_query'] = array(
					array(
						'key'		=> 'place_relationship',
						'value'		=>  $places[0],
						'compare'	=> 'LIKE'
					)
				);
			} else { //mulitple places
				$alm_args['meta_query'] = array('relation' => 'OR');
				foreach ($places as $p) {
					$alm_args['meta_query'][] = array(
						'key'       => 'place_relationship',
						'value'     => $p,
						'compare'   => 'LIKE'
					);
				}
			}
		
		} else {
			$alm_args['meta_query'] = array(
				array(
					'key'		=> 'global_article',
					'value'		=>  true,
					'compare'	=> 'LIKE'
				)
			);
		}

		if ( ! empty( $cat ) ) {
			$alm_args['cat'] = $cat[0]->term_id;
		}
		// print_r($alm_args);
		$alm_query = get_posts($alm_args);
		foreach ($alm_query as $aq) {
			$alm_posts[] = $aq->ID;
		}
		// print_r($alm_query);
		$alm_post_string = implode(',',$alm_posts);
?>
	<?php echo do_shortcode( '[ajax_load_more single_post="true" single_post_id="'.get_the_ID().'"  single_post_order="'.$alm_post_string.'" post__in="'.$alm_post_string.'" cache="false" single_post_target="#article-wrapper"  post_type="post" pause_override="true"  loading_style="infinite classic" scroll_distance="100"]' );

endwhile; // End of the loop.


get_footer();