<?php
/**
 * Template part for displaying places, version 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

 // get all posts related to place and sort into categories
$topic_args = array(
    'posts_per_page'    => -1,
    'post_status'       => 'publish',
    'post_type'         => 'post',
    'meta_query'        => array(
        array( 
            'key'       => 'place_relationship',
            'value'     => '"' . get_the_ID() . '"',
            'compare'   => 'LIKE'
        ),
        array(
           'key'       => 'sponsored',
           'value'     => 0
       ),
       'relation'      => 'AND'
    )
   );
   $topics = new WP_Query( $topic_args );
   $topics_array = array();
   foreach($topics->posts as $topic) {
        $ID = $topic->ID;
       $cat = get_the_category( $ID );
       $slug = $cat[0]->slug;
       
        if (! array_key_exists($slug, $topics_array)) {
        $topics_array[$slug] = array($ID); 
       } else {
           array_push($topics_array[$slug], $ID );
       }
   }

   $is_2024_bp = has_term('2024', 'best_places_years');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
        <div class="wp-block-columns full-width-white">
            <div class="wp-block-column">
                <?php get_template_part( 'template-parts/blocks/ad-one' ); ?>
            </div>
        </div>

<div class="place-column__parent">
    <div class="place-column__nav">
    <ul class="place-side-nav">
        
        <li><a href="#top-100" >2024 Top 100</a></li>
        <li><a href="#things-to-do">Things to Do</a></li>
        <li><a href="#economy">Economy</a></li>
        <li><a href="#brands-to-know">Brands to Know</a></li>
        <li><a href="#quick-facts">Quick Facts</a></li>
        <li><a href="#weather">Weather</a></li>
        <li><a href="#map">Map</a></li>
        <?php 
        echo array_key_exists('experiences-adventures', $topics_array) ? '<li><a href="#experiences-and-adventures">Experiences & Adventures</a></li>' : '';
        echo array_key_exists('food-scenes', $topics_array) ? '<li><a href="#food-scenes">Food Scenes</a></li>': '';
        echo array_key_exists('healthy-places', $topics_array) ? '<li><a href="#healthy-places">Healthy Places</a></li>': '';
        echo array_key_exists('make-your-move', $topics_array) ? '<li><a href="#make-your-move">Make Your Move</a></li>': '';
        echo array_key_exists('where-to-live-now', $topics_array) ? '<li><a href="#where-to-live-now">Where to Live Now</a></li>': '';
        echo array_key_exists('education-careers-opportunity', $topics_array) ? '<li><a href="#education-careers-and-opportunities">Education, Careers & Opportunities</a></li>': '';
        echo array_key_exists('love-where-you-live', $topics_array) ? '<li><a href="#love-where-you-live">Love Where You Live</a></li>': '';
        echo array_key_exists('where-to-live-now', $topics_array) ? '<li><a href="#more-about-living-in">More About Living in </a></li>': '';
        ?>

    </ul>
    </div>
    <div class="place-column__content">
    
        <div class="wp-block-columns liv-columns">
            <div class="wp-block-column">
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column"></div>
                    <div class="wp-block-column">
                        <div id="crumbs">
                            <?php echo return_breadcrumbs(); ?> 
                        </div>
                        <?php if (has_term('2024', 'best_places_years')) {echo 'has term';} else {echo 'not term';} 
                        // print_r(get_terms()); 
                        echo get_the_term_list(get_the_ID(),'best_places_years' );?>
                     
                    </div>
                </div>
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column">

                    <?php 
                    //get_template_part( 'template-parts/blocks/top-one-hundred-link'); ?>
                    
                    </div>
                    <div class="wp-block-column">

                    
                     
                     <?php 
                     if ($is_2024_bp) {
                         echo '<h2 class="h1">'.get_the_title().'</h2>';
                     } else {
                        echo '<h1 class="h1">'.get_the_title().'</h1>';
                     }
                     
                     //If there is and article connected to this place with a 
                     // topic of connected community, show block
                     echo do_shortcode('[addtoany]'); 
                     get_template_part( 'template-parts/blocks/cc-cta-block' );
                     
                     if (get_field('non-client_city_with_content')) {
                        the_content( );
                    } else {
                        get_template_part( 'template-parts/blocks/madlib' ); 
                    }
                    get_template_part( 'template-parts/blocks/embedded-cc-article' );
                    get_template_part( 'template-parts/blocks/link-to-2023-best-place' );
                     ?>
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
            <?php get_template_part( 'template-parts/blocks/ad-two' ); ?>
            </div>
        </div>
        <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php get_template_part( 'template-parts/blocks/quick-facts' ); ?>
        </div></div>
        
        <?php get_template_part( 'template-parts/blocks/city-map' ); ?>
        
        <div class="wp-block-colomns">
            <div class="wp-block-column">
      
            <?php get_template_part( 'template-parts/blocks/place-brand-story' ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'experiences-adventures') ); ?>
            <?php get_template_part( 'template-parts/blocks/ad-three' ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'food-scenes') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'healthy-places') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'affordable-places-to-live') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'make-your-move') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'where-to-live-now') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'education-careers-opportunity') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'love-where-you-live') ); ?>
            </div>
        </div>

        </div><!-- place-column__content -->

</div><!-- place-column__parent --> 
		
	</div><!-- .entry-content -->




</article><!-- #post-<?php the_ID(); ?> -->
