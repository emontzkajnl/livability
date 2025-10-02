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

	get_template_part( 'template-parts/content/content-sponsored-page' );

endwhile; // End of the loop.
// dynamic_sidebar( 'sidebar-2' );

get_footer();