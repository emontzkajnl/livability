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
   $parent_obj = get_post_parent();
   $state_name = $parent_obj->post_title;
   $state_abbv = $parent_obj->post_name;
   $full_city_name = get_the_title($place[0]);
   $city_name = substr(get_the_title($place[0]), 0, -4); 
//    print_r($parent_obj);

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
        <li class="active"><a href="#overview">Overview</a></li>
        <?php if ($is_2024_bp && get_field('turn_on_top_100_blocks', 'options')): ?>
        <li><a href="#top-100" >2024 Top 100</a></li>
        <li><a href="#things-to-do">Things to Do</a></li>
        <li><a href="#economy">Economy</a></li>
        <?php endif; ?>
        <li><a href="#brands-to-know">Brands to Know</a></li>
        <li><a href="#weather">Weather</a></li>
        <li><a href="#quick-facts">Quick Facts</a></li>
        <li><a href="#map">Map</a></li>
        <?php 
        echo array_key_exists('experiences-adventures', $topics_array) ? '<li><a href="#experiences-adventures">Experiences & Adventures</a></li>' : '';
        echo array_key_exists('food-scenes', $topics_array) ? '<li><a href="#food-scenes">Food Scenes</a></li>': '';
        echo array_key_exists('healthy-places', $topics_array) ? '<li><a href="#healthy-places">Healthy Places</a></li>': '';
        echo array_key_exists('make-your-move', $topics_array) ? '<li><a href="#make-your-move">Make Your Move</a></li>': '';
        echo array_key_exists('where-to-live-now', $topics_array) ? '<li><a href="#where-to-live-now">Where to Live Now</a></li>': '';
        echo array_key_exists('education-careers-opportunity', $topics_array) ? '<li><a href="#education-careers-and-opportunity">Education, Careers & Opportunities</a></li>': '';
        echo array_key_exists('love-where-you-live', $topics_array) ? '<li><a href="#love-where-you-live">Love Where You Live</a></li>': '';
        ?>
        <li><a href="#more-about-living">More About Living in <?php echo $state_name;   ?></a></li>

    </ul>
    </div>
    <div class="place-column__content">
    
        <div class="wp-block-columns liv-column-p">
            <div class="wp-block-column">
            <div id="crumbs">
                <?php if (function_exists('return_breadcrumbs')) {
                    echo return_breadcrumbs(); 
                } ?>
            </div>
                     <?php 
                     if ($is_2024_bp) {
                         echo '<h2 class="h1" id="overview">'.get_the_title().'</h2>';
                     } else {
                        echo '<h1 class="h1" id="overview">'.get_the_title().'</h1>';
                     }
                     
                     //If there is and article connected to this place with a 
                     // topic of connected community, show block
                     echo do_shortcode('[addtoany]'); 
                     get_template_part( 'template-parts/blocks/cc-cta-block' );
                     
                     if (get_field('client_place') || get_field('non-client_city_with_content')) {
                        the_content( );
                     } else {
                        get_template_part( 'template-parts/blocks/madlib' ); 
                        // non-clients that are 2024 top 100 have content to show
                        if ($is_2024_bp) {
                            the_content();
                        }
                     }
                    get_template_part( 'template-parts/blocks/embedded-cc-article' );
                    // get_template_part( 'template-parts/blocks/link-to-2023-best-place' );
                     ?>
                    </div>
                <!-- </div> -->
            <!-- </div> -->
            <div class="wp-block-column">
            <?php 
            // get_template_part( 'template-parts/blocks/sidebar-mag-link' );
            get_template_part( 'template-parts/blocks/ad-two' ); ?>
            </div>
        </div>
        <div class="wp-block-columns">
            <div class="wp-block-column">
            
            <?php get_template_part( 'template-parts/blocks/top-100-carousel-24'); 
            get_template_part( 'template-parts/blocks/brand-stories', null, array('city' => $city_name, 'state' => $state_name) );
            get_template_part( 'template-parts/blocks/weather', null, array('city' => $city_name, 'abbv' => $state_abbv) );
            get_template_part( 'template-parts/blocks/quick-facts-2', null, array('city' => $city_name, 'state' => $state_name  ) ); 
            ?>
            </div>
        </div>
        
        <?php echo '<h2 id="map">Map of '.$full_city_name.'</h2>'; 
        get_template_part( 'template-parts/blocks/city-map' ); ?>
        
        <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php 
            if (array_key_exists('experiences-adventures', $topics_array)) {
                echo '<h2 id="experiences-adventures">Experiences & Adventures in '.$full_city_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['experiences-adventures'], 'cat' => 'experiences-adventures'));
            }

            if (array_key_exists('food-scenes', $topics_array)) {
                echo '<h2 id="food-scenes">Food Scenes in '.$full_city_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['food-scenes'], 'cat' => 'food-scenes'));
            }  
            
            if (array_key_exists('healthy-places', $topics_array)) {
                echo '<h2 id="healthy-places">Healthy Places in '.$full_city_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['healthy-places'], 'cat' => 'healthy-places' ));
            }   
            
            if (array_key_exists('make-your-move', $topics_array)) {
                echo '<h2 id="make-your-move">Make Your Move to '.$full_city_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['make-your-move'], 'cat' => 'make-your-move'));
            }    
            
            if (array_key_exists('where-to-live-now', $topics_array)) {
                echo '<h2 id="where-to-live-now">'.$full_city_name.': Where to Live Now</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['where-to-live-now'], 'cat' => 'where-to-live-now'));
            }               

            if (array_key_exists('education-careers-opportunity', $topics_array)) {
                echo '<h2 id="education-careers-and-opportunity">Education, Careers and Opportunity in '.$full_city_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['education-careers-opportunity'], 'cat' => 'education-careers-opportunity'));
            }

            if (array_key_exists('love-where-you-live', $topics_array)) {
                echo '<h2 id="love-where-you-live">'.$full_city_name.': Love Where You Live</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['love-where-you-live'], 'cat' => 'love-where-you-live'));
            }            
            ?>
            </div>
        </div>

        </div><!-- place-column__content -->

</div><!-- place-column__parent --> 
		
	</div><!-- .entry-content -->
    <?php get_template_part( 'template-parts/blocks/more-about-living', null, array('state' => $state_name, 'abbv' => $state_abbv) ); ?>
   



</article><!-- #post-<?php the_ID(); ?> -->
