<?php
/**
 * Page Template: Sponsors Page
 * 
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
$hero_section = get_field('display_hero');
if ($hero_section) {
	get_template_part( 'template-parts/page-hero-section' );
}

// function create_block($city, $state) {
//     $hyphencity = str_replace(' ','-', $city);
//     $uscity = str_replace(' ','_', $city);
//     $block = '<!-- wp:jci-blocks/info-box {"icon":"neighborhood"} --><div class="wp-block-jci-blocks-info-box neighborhood"><p class="info-box-quote"><a href="https://www.movoto.com/'.strtolower($hyphencity).'-'.strtolower($state).'/?utm_source=livability&utm_medium=Partner&utm_campaign=2021_top_100&utm_content=display&utm_term='.strtolower($uscity).'_'.strtolower($state).'" target="_blank" alt="See Find a Home in '.ucwords($city).' '.$state.' for sale on Movoto by OJO" rel="noopener"><strong>Find a Home in '.ucwords($city).', '.$state.'</strong></a><br /><br /><a href="https://www.movoto.com/'.strtolower($hyphencity).'-'.strtolower($state).'/?utm_source=livability&utm_medium=Partner&utm_campaign=2021_top_100&utm_content=display&utm_term='.strtolower($uscity).'_'.$state.'" target="_blank" alt="See Find a Home in '.ucwords($city).' '.$state.' for sale on Movoto by OJO" rel="noopener"><strong>Movoto by OJO </strong></a> is a home search site that provides personalized recommendations and highlights local listings best suited to your needs and preferences.</p><p class="info-box-name"></p><p class="info-box-position"></p><a href="https://www.movoto.com/'.strtolower($hyphencity).'-'.strtolower($state).'/?utm_source=livability&utm_medium=Partner&utm_campaign=2021_top_100&utm_content=display&utm_term='.strtolower($uscity).'_'.strtolower($state).'" target="_blank" class="components-button info-box-button" rel="noopener">See Homes for Sale in '.ucwords($city).'</a></div><!-- /wp:jci-blocks/info-box -->';
//     return $block;  
// }
// error_log(create_block('Annapolis','MD'));
/* Start the Loop */
while ( have_posts() ) :
	the_post();



	get_template_part( 'template-parts/content/content-page' );
	$sponsor_args = array(
		'post_type'			=> 'post',
		'meta_key'			=> 'sponsored',
		'meta_value'		=> true,
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish'
	);
	$sponsor_query = new WP_Query($sponsor_args);

	if ($sponsor_query->have_posts()): ?>
		<div class="container">
			<div style="display: flex; justify-content: center; flex-wrap: wrap;">
		<?php while ($sponsor_query->have_posts()): $sponsor_query->the_post();
		$ID = get_the_ID(  ); ?>
		<div class="sponsor-card" style="width: 300px;">
			<a href="<?php echo get_the_permalink(); ?>">
				<div class="sp-img" style="background-image: url(<?php echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>); height: 200px; width: 100%;"></div>
				<div class="sma-title">
					<?php echo '<h4>'.get_the_title().'</h4>'; ?>
				</div>
			</a>
		</div>
		<?php endwhile; ?>
			</div>
		</div>
	<?php endif;
	wp_reset_query();

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile; // End of the loop.
// dynamic_sidebar( 'sidebar-2' );

get_footer();