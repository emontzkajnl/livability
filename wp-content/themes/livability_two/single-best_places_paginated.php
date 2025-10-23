<?php
/**
 * Template Name: Best places paginated
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


// while ( have_posts() ) :
	// the_post();
    // get_template_part( 'template-parts/bp-hero-section' );
	// get_template_part( 'template-parts/content/content-single-best-places-paginated' );
	// get_template_part( 'template-parts/content/content-single-best-places' );
	


	// if ( is_attachment() ) {
		// Parent post navigation.
		// the_post_navigation(
			// array(
				/* translators: %s: parent post link. */
				// 'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentytwentyone' ), '%title' ),
			// )
		// );
	// }

	// If comments are open or there is at least one comment, load up the comment template.
	// if ( comments_open() || get_comments_number() ) {
		// comments_template();
	// }

	// Previous/next post navigation.
	// $twentytwentyone_next = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' );
	// $twentytwentyone_prev = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' );

	// $twentytwentyone_next_label     = esc_html__( 'Next post', 'livablity' );
	// $twentytwentyone_previous_label = esc_html__( 'Previous post', 'livablity' );

	// the_post_navigation(
	// 	array(
	// 		'next_text' => '<p class="meta-nav">' . $twentytwentyone_next_label . $twentytwentyone_next . '</p><p class="post-title">%title</p>',
	// 		'prev_text' => '<p class="meta-nav">' . $twentytwentyone_prev . $twentytwentyone_previous_label . '</p><p class="post-title">%title</p>',
	// 	)
	// );
// endwhile; // End of the loop.



$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$ID = the_ID(  );
$args = array( 
    'post_type'     => 'best_places',
    'posts_per_page'=> 10,
    'post_status'   => 'publish',
    'orderby'    => 'meta_value_num',
    'meta_key'  => 'bp_rank',
    'order'     => 'ASC',
    'post_parent'   => $ID, 
    // 'post__in'  => $child_array,
    'paged'     => $paged
); 
$ohl_query = new WP_Query($args);
    if ($ohl_query->have_posts()): ?>
        <ul class="onehundred-container">
        <?php while ($ohl_query->have_posts()): $ohl_query->the_post();
        $ID = get_the_ID();
        $place = get_field('place_relationship');
        $population = '';
        if ($place) {
            $population = get_field('city_population', $place[0]);
        } ?>
        <li class="one-hundred-list-item">
            <div class="one-hundred-list-container">
                <a href="<?php echo get_the_permalink(); ?>" class="ohl-thumb" >
                <div style="background-image: url('<?php echo get_the_post_thumbnail_url($ID,'three_hundred_wide'); ?>');">
                <p class="green-circle with-border"><?php echo get_field('bp_rank'); ?></p>
                </div>
                </a> 
                <div class="ohl-text">
                    <a href="<?php echo get_the_permalink(); ?>">
                    <h3><?php echo get_the_title(); ?></h3>
                    <h4 class="uppercase">
                        Livscore: <?php echo get_field('ls_livscore'); 
                        if ($population) {
                            echo ' | Population: '.$population;
                        } ?>
                    </h4>
                    <p><?php echo get_the_excerpt( ); ?></p></a>
                
                </div>
            </div>
        </li>

        <?php endwhile; 
        // the_post_navigation();
        global $wp_query;
        echo paginate_links();
         ?>
        </ul>
        <!-- <div class="waypoint-target"></div> -->
    <?php endif;
    wp_reset_postdata(); 
get_footer();
