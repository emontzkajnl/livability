<?php
/**
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
	
	if (get_field('place_type') != 'state') {
		// if 2024 top 100, show bp hero whether client or not
		$is_2024 = has_term('2024', 'best_places_years');
		$is_2025 = has_term('2025', 'best_places_years');
		$heading = get_field('override_place_title') ? 'h2' : 'h1' ; 
		if ($is_2024 || $is_2025) { 
			$parent_id = wp_get_post_parent_id();
 			$byline =  get_field('img_byline',get_post_thumbnail_id());
			$hero  = get_field('hero_section');
			
			?>
			<div class="wp-block-columns">
			
            <div class="wp-column container" style="width: 100%; margin-top: 50px;">
                <div class="bp23-heading-section">
                    <div class="bp23-thumb" >

 					<?php if ($hero && $hero['hero_video']) {
 					echo do_shortcode('[nk_awb awb_type="yt_vm_video" awb_video="https://youtu.be/'.$hero['hero_video'].'" awb_video_start_time="0" awb_video_end_time="0" awb_video_always_play="true" awb_video_mobile="true"]' );
					} else {
						echo get_the_post_thumbnail( get_the_ID(), 'medium-large' );  
					  echo $byline ? '<div class="livability-image-meta">'.$byline.'</div>' : ''; 
					} ?>
					 
                    </div>
					<div class="bp23-title-section">
						<!-- TODO replace badge and maybe title for 2025 -->
						<?php if ($is_2025) { ?>
							<a href="<?php echo site_url( '/best-places/top-100-best-places-to-live-in-the-us/' ); ?>">
						<img class="bp23-badge-single" src="<?php echo get_stylesheet_directory_uri(  ); ?>/assets/images/livability-top-100-best-places-badge-2025.svg"/>
						</a>
						<?php echo '<'.$heading.' class="bp23-title">'.get_the_title().'<span><a href="'.site_url( '/best-places/top-100-best-places-to-live-in-the-us/' ).'">Best Places to Live in the U.S. 2025</a></span></'.$heading.'>'; 
						} else { ?>
						<a href="<?php echo site_url( '/best-places/2024-top-100-best-places-to-live-in-the-us/' ); ?>">
						<img class="bp23-badge-single" src="<?php echo get_stylesheet_directory_uri(  ); ?>/assets/images/2024Top100_badge_final.svg"/>
						</a>
						<?php 
							echo '<'.$heading.' class="bp23-title">'.get_the_title().'<span><a href="'.site_url( '/best-places/2024-top-100-best-places-to-live-in-the-us/' ).'">Best Places to Live in the U.S. 2024</a></span></'.$heading.'>'; 
						}?>
						
						<?php echo has_excerpt( ) ? '<p class="bp23-excerpt">'.get_the_excerpt().'</p>' : ''; 
						// echo 'parent: '.wp_get_post_parent_id(  ).' '.get_the_title(150065 ); ?>
					</div>
                </div>
            </div>
        </div> <!--wp-block-columns-->
		<?php } elseif (get_field('client_place'))  {
			get_template_part( 'template-parts/hero-section' );
		}
		// content part for both client and non-client: 
		get_template_part( 'template-parts/content/content-places-v2' );
	} else {
		// state places retain old templates
		get_template_part( 'template-parts/hero-section' );
		get_template_part( 'template-parts/content/content-single-places' );
	}


endwhile; // End of the loop.

get_footer();
