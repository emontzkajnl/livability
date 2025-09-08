<?php
/**
 * Plugin Name:       Jci Blocks V2
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       jci-blocks-v2
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_jci_blocks_v2_block_init() {
	register_block_type( __DIR__ . '/build/ad-area-one', array('render_callback' => 'jci_blocks_render_ad_one'));
	register_block_type( __DIR__ . '/build/ad-area-two', array('render_callback' => 'jci_blocks_render_ad_two'));
	register_block_type( __DIR__ . '/build/ad-area-three', array('render_callback' => 'jci_blocks_render_ad_three'));
	register_block_type( __DIR__ . '/build/quick-facts', array('render_callback' => 'jci_blocks_render_quick_facts'));
	register_block_type( __DIR__ . '/build/magazine-articles', array('render_callback' => 'jci_blocks_render_magazine_articles'));
	register_block_type( __DIR__ . '/build/best-place-data', array('render_callback' => 'jci_blocks_render_bp_data'));
	register_block_type( __DIR__ . '/build/post-title', array('render_callback' => 'jci_blocks_render_post_title'));
	register_block_type( __DIR__ . '/build/madlib', array('render_callback' => 'jci_blocks_render_madlib'));
	register_block_type( __DIR__ . '/build/onehundred-list', array('render_callback' => 'jci_blocks_render_onehundred_list'));
	register_block_type( __DIR__ . '/build/breadcrumbs', array('render_callback' => 'jci_blocks_render_breadcrumbs'));
	register_block_type( __DIR__ . '/build/featured-image', array('render_callback' => 'jci_blocks_render_featured_image'));
	register_block_type( __DIR__ . '/build/editable-post-title');
	register_block_type( __DIR__ . '/build/section-header');
	register_block_type( __DIR__ . '/build/sponsored-by', array('render_callback' => 'jci_blocks_render_sponsored_by'));
	register_block_type( __DIR__ . '/build/excerpt-and-post-author', array('render_callback' => 'jci_blocks_render_excerpt_and_post_author'));
	register_block_type( __DIR__ . '/build/magazine', array('render_callback' => 'jci_blocks_render_magazine'));
	register_block_type( __DIR__ . '/build/onehundredslider', array('render_callback' => 'jci_blocks_render_posts_block'));
	register_block_type( __DIR__ . '/build/info-box');
    // register_block_type( __DIR__ . '/build/info-box-2');
	// register_block_type( __DIR__ . '/build/info-box-with-button');
	register_block_type( __DIR__ . '/build/suggested-posts-404', array('render_callback' => 'jci_blocks_render_suggested_posts'));
	register_block_type( __DIR__ . '/build/bp-sponsor', array('render_callback' => 'jci_blocks_render_bp_sponsor'));
	register_block_type( __DIR__ . '/build/city-list', array('render_callback' => 'jci_blocks_render_city_list'));
	register_block_type( __DIR__ . '/build/city-301', array('render_callback' => 'jci_blocks_render_city_301'));
	register_block_type( __DIR__ . '/build/city-map', array('render_callback' => 'jci_blocks_render_city_map'));
	register_block_type( __DIR__ . '/build/mag-sponsor', array('render_callback' => 'jci_blocks_render_mag_sponsor'));
	register_block_type( __DIR__ . '/build/link-place-to-top-100', array('render_callback' => 'jci_blocks_link_place_to_top_100'));
    register_block_type( __DIR__ . '/build/weather-block', array('render_callback' => 'jci_blocks_weather_block'));
    register_block_type( __DIR__ . '/build/content-weather-block', array('render_callback' => 'jci_blocks_content_weather_block'));
    register_block_type( __DIR__ . '/build/livscore-block', array('render_callback' => 'jci_blocks_livscore_block'));
    register_block_type( __DIR__ . '/build/link-to-2023-best-place', array('render_callback' => 'jci_blocks_link_to_2023_best_place'));
    register_block_type( __DIR__ . '/build/internally-promoted', array('render_callback' => 'jci_blocks_internally_promoted'));
    register_block_type( __DIR__ . '/build/bp-301', array('render_callback' => 'jci_blocks_bp_301'));
    register_block_type( __DIR__ . '/build/magazine-brand-stories', array('render_callback' => 'jci_blocks_magazine_brand_stories'));
    register_block_type( __DIR__ . '/build/largest-cities', array('render_callback' => 'jci_blocks_largest_cities'));
    register_block_type( __DIR__ . '/build/industries', array('render_callback' => 'jci_blocks_industries'));
    register_block_type( __DIR__ . '/build/occupations', array('render_callback' => 'jci_blocks_occupations'));
    register_block_type( __DIR__ . '/build/schools', array('render_callback' => 'jci_blocks_schools'));
}
add_action( 'init', 'create_block_jci_blocks_v2_block_init' );

function jci_blocks_categories( $categories, $post) {
    return array_merge(
        $categories,
        array( 
            array( 
                'slug' => 'jci-category',
                'title' => __('Livability', 'jci_blocks'),
            )
        )
            );
}
 add_filter('block_categories_all', 'jci_blocks_categories', 10, 2);

function return_breadcrumbs() {
    global $post;
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    // $delimiter = '&raquo;'; // delimiter between crumbs
    $delimiter = '&gt;'; // delimiter between crumbs
    $home = 'Home'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb
    $homeLink = get_bloginfo('url');
    $output = ''; //returned output
    $currentID = get_the_ID();
    // $post = get_post();
    // $post_type = $post->post_type;
    $post_type = get_post_type();
    $cat = get_the_category( $currentID );
    // print_r($post);
    if (!function_exists('getCategoryPageUrl')) {
    function getCategoryPageUrl($cat) {
        $url = '';
        switch ($cat[0]->term_id) { 
            case 32: //affordable places
                $url = 70439;
                break;
            case 12: // education, carreers, oppotunity
                $url = 70441;
                break;
            case 11: // experiences and adventures
                $url = 70443;
                break;
            case 16: // food scenes 
                $url = 70445;
                break;
            case 13: // healthy places 
                $url = 70447;
                break;
            case 14: // love where u live
                $url = 70451;
                break;                                
            case 18: // make your move
                $url = 70453;
                break;
            case 536: // connected communities 
                $url = 158133;
                break;     
            default: //TODO: ADD CONNECTED COMMUNITIES TOPIC PAGE
                $url = '';               
        }
            return get_the_permalink($url);
        }
    }

    $output .=  '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ' ;
    if ( is_author()) {
        $author = get_queried_object();
        $name = get_user_meta($author->ID);
        $output .= ' Authors ' . $delimiter . ' '  . get_the_author_meta('display_name') . ' ';
    } elseif ( !has_post_parent()) {
        if ( $post_type == 'post') { 
            $parsed = wp_parse_url( get_permalink());
            $split = explode('/',$parsed['path']);
            if (count($split) > 4) { // has state
                $state_segment = '/'.$split[1].'/';
                $ss_obj = get_page_by_path($state_segment, OBJECT, 'liv_place');
                if ($ss_obj) {
                    $output .= ' <a href="' . get_permalink( $ss_obj->ID) . '"> ' . get_field('state_code', $ss_obj->ID) . '</a> ' . $delimiter . ' ';
                }
                
            }
            if (count($split) > 5) { // has city
                $city_rel = get_field('place_relationship');
                $city_title = ucwords(str_replace('-', ' ', $split[2]));
                if (count($city_rel) == 1) {
                    $city_link = get_the_permalink( $city_rel[0]);
                } elseif (count($city_rel) > 1) {
                    $rc_array = get_posts(array(
                        'post_type'	=> 'liv_place',
                        'orderby'   => 'post__in',
                        'post__in' => $city_rel,
                        'posts_per_page' => 1
                    ));
                    $city_link = get_the_permalink($rc_array[0]->ID );
                }
                $output .= ' <a href="' . $city_link . '"> ' . $city_title . '</a> ' . $delimiter . ' ';
            }

            $output .= ' <a href="' . getCategoryPageUrl($cat) . '">'  . $cat[0]->name . '</a> ' . $delimiter . ' ';
        }
        $output .=  $before . get_the_title() . $after;
    } elseif (has_post_parent($currentID)) {
        $parent_id  = wp_get_post_parent_id($currentID);
        $breadcrumbs = array();
        
        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
            $output .=  $breadcrumbs[$i];
            if ($i != count($breadcrumbs)-1) {
                $output .=  ' ' . $delimiter . ' ';
            }
        }
        $output .=  ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
    }
    $output .= '</div>';
 return $output;
 die();
}

 function jci_blocks_render_posts_block(  ) {
    $currentID = get_the_ID();
    $parent = get_post_parent($currentID);
    $parentLink = get_the_permalink( $parent->ID);
    $args = array(  
        'posts_per_page' => -1,
        'post_type'     => 'best_places',
        'post_status'   => 'publish',
        'orderby'    => 'meta_value_num',
        'meta_key'  => 'bp_rank',
        'order'     => 'ASC',
        'post_parent'   => $parent->ID,
    );
    $query = new WP_Query($args);
    $current_rank = get_field('bp_rank', $currentID);
    // SPLIT THE QUERY ARRAY SO CURRENT BP IS FIRST 
    $queryArray = $query->posts;
    $current_rank = $current_rank - 1;
    $row2 = array_splice($queryArray, $current_rank);
    $row1 = array_splice($queryArray, 0, $current_rank );
    $merged = array_merge($row2, $row1);
    $query->posts = $merged;
    $posts = '';
    if ($query->have_posts()) {
        $posts .= '<div class="list-carousel-container custom-block"><ul class="wp-block-jci_blocks-blocks list-carousel">';
        while ( $query->have_posts()):  
            $query->the_post();
            $ID = get_the_ID(  );
            $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url($ID, 'rel_article') : '';
            $title = __(get_the_title(), 'jci_blocks');
            $rank = get_field('bp_rank');
            // $posts .= '<li><a href="' . esc_url( get_the_permalink()) . '" >' . get_the_title() . '</a></li>';
            $posts .= '<li class="lc-slide">';
            $posts .= '<a href="'.get_the_permalink().'">';
            $posts .= '<div class="lc-slide-inner"><div class="lc-slide-content">';
            $posts .= '<div class="lc-slide-img" style="background-image: url(' . $thumb_url . ')">';
            $posts .= '<p class="slide-count">' . $rank . '</p>';
            $posts .= '</div>';
            $posts .= '<div><h4 class="city-state">' . $title . '</h4></div>';
            $posts .= '</div></div>';
            $posts .= '</a></li>';
        endwhile;
        $posts .= '</ul>';
        $posts .= '<button class="list-carousel-button"><a href="'.$parentLink.'">Go Back To List</a></button>';
        $posts .= '</div>';
        return $posts;
        die();
        // wp_reset_postdata();
    } else {
        return '<div>' . __("No Posts Found", "jci_blocks") . '</div>';
    }
    // echo '<button>Here is a button</button>';
 }



 function jci_blocks_render_quick_facts() {
    global $wpdb;
     $ID = get_the_id();
     $post_type = get_post_type( );
    //  if (get_field('hide_facts', $ID) == true) {
    //     return null;
    //  }
     if ($post_type == 'liv_place'): // used on places only
     $is_child = wp_get_post_parent_id($ID) > 0;
    $data = get_field('state_data');
    // $test = 'is this a block?';
    $html = '';
    // if ($data) {
        // $householdIncome = intval(get_field('state_household_income')); 
        if ($is_child) { // city page
            $results = $wpdb->get_results( "SELECT * FROM 2025_city_data  WHERE place_id = $ID", OBJECT );
            if ($results) {
                $cityHomeValue = $results[0]->avg_hom_val;
                $cityPropTax = $results[0]->avg_pro_tax;
                $cityPopulation = $results[0]->city_pop;
                $commute = $results[0]->avg_com;
                $cityHouseholdIncome = $results[0]->avg_hou_inc;
                $avg_rent = $results[0]->avg_rent;
            } else {
                $commute = get_field('city_commute'); 
                $cityHomeValue = str_replace(',','',get_field('city_home_value')); 
                $cityHomeValue = intval($cityHomeValue); 
                $cityHouseholdIncome = str_replace(',','',get_field('city_household_income'));
                $cityHouseholdIncome = intval($cityHouseholdIncome); 
                $cityPopulation = str_replace(',','',get_field('city_population'));
                $cityPopulation = intval($cityPopulation);
                $cityWalkScore = get_field('city_walk_score');  
                $cityPropTax = str_replace(',','',get_field('city_property_tax')); 
                $cityPropTax = intval($cityPropTax);
            }
        } else { // state page
            $results = $wpdb->get_results( "SELECT * FROM 2025_state_data  WHERE place_id = $ID", OBJECT );
            if ($results) {
                $householdIncome = $results[0]->avg_hou_inc;
                $propTax = $results[0]->avg_pro_tax;
                $statePop = $results[0]->state_pop;
                $salesTax = $results[0]->sales_tax;
                $incomeTax = $results[0]->state_inc_tax;
                $stateRent = $results[0]->avg_rent;
            } else { // fallback to custom fields
                $householdIncome = str_replace(',','',get_field('state_household_income')); 
                $householdIncome = intval($householdIncome);
                $propTax = get_field('state_property_tax');
                $propTax = number_format($propTax);
                $statePop = str_replace(',','',get_field('state_population'));
                $statePop = intval($statePop);
                $stateSun = get_field('state_sunshine');
                $salesTax = get_field('state_sales_tax'); // doesn't work with 0
                $incomeTax = get_field('state_income_tax'); // does work with 0
            }
        }
       

        // if (!$diversityIndex && !$householdIncome && !$propTax && !$homeValue) { return NULL;}
        $html .= '<div class="quick-facts-2-block"><h3 class="qf-title">Quick Facts about '.get_the_title().'</h3><dl>';
        if (!$is_child) {
        $html .= $householdIncome ? '<div class="avg-inc"><dt>Average Household Income</dt><dd>$'.number_format($householdIncome).'</dd></div>' : '';
        $html .= $propTax ? '<div class="prop-tax"><dt>Average Property Tax</dt><dd>$'.$propTax.'</dd></div>' : '';
        $html .= $statePop ? '<div class="city-pop"><dt>State Population</dt><dd>'.number_format($statePop).'</dd></div>' : '';
        // $html .= $stateSun ? '<div><dt>Average Property Tax</dt><dd>$'.$stateSun.'</dd></div>' : '';
        if (isset($salesTax) && $salesTax != '') {
           $html .=  '<div class="state-sales"><dt>Sales Tax</dt><dd>'.$salesTax.'%</dd></div>';
        }
        if (isset($incomeTax) && $incomeTax != '') {
            $html .= '<div class="state-inc"><dt>State Income Tax</dt><dd>'.$incomeTax.'%</dd></div>';
        }
        // $html .= $salesTax ? '<div class="state-sales"><dt>Sales Tax</dt><dd>'.$salesTax.'%</dd></div>' : '';
        // $html .= $incomeTax ? '<div class="state-inc"><dt>State Income Tax</dt><dd>'.$incomeTax.'</dd></div>' : '';
        $html .= $stateRent ? '<div class="avg-rent"><dt>Median Monthly Rent</dt><dd>'.$stateRent.'</dd></div>' : '';
        } else {
        $html .= $commute ? '<div class="avg-com"><dt>Average Commute</dt><dd>'.$commute.' minutes</dd></div>' : '';
        $html .= $cityHomeValue ? '<div class="prop-tax"><dt>Median Home Value</dt><dd>$'.number_format($cityHomeValue).'</dd></div>' : '';
        $html .= $cityHouseholdIncome ? '<div class="avg-inc"><dt>Median Household Income</dt><dd>$'.number_format($cityHouseholdIncome).'</dd></div>' : '';
        $html .= $cityPopulation ? '<div class="city-pop"><dt>Total Population</dt><dd>'.number_format($cityPopulation).'</dd></div>' : '';
        $html .= $cityWalkScore ? '<div><dt>Walk Score</dt><dd>'.$cityWalkScore.'</dd></div>' : '';
        $html .= $cityPropTax ? '<div class="avg-inc"><dt>Median Property Tax</dt><dd>$'.number_format($cityPropTax).'</dd></div>' : '';
        $html .= $avg_rent ? '<div class="avg-rent"><dt>Median Monthly Rent</dt><dd>$'.number_format($avg_rent).'</dd></div>' : '';
        }
        $html .= '</dl></div>';
        $html .= $is_child ? '<p class="widget-link"><a href="'.site_url( 'city-data-widget?cityid=' ).$ID.'">Display these facts on your site.</a></p>' : '';
    else: // used for 2023 best places only
        $place = get_field('place_relationship');
        foreach ($place as $p) {
            if (get_field('place_type', $p) == 'city') {
                $city_id= $p;
            }
        }
        $cityHouseholdIncome = str_replace(',','',get_field('city_household_income', $city_id));
        $cityHouseholdIncome = intval($cityHouseholdIncome); 
        $cityHomeValue = str_replace(',','',get_field('city_home_value', $city_id)); 
        $cityHomeValue = intval($cityHomeValue); 
        $cityPropTax = str_replace(',','',get_field('city_property_tax', $city_id)); 
        $cityPropTax = intval($cityPropTax);
        $cityPopulation = str_replace(',','',get_field('city_population', $city_id));
        $cityPopulation = intval($cityPopulation);
        $cityRent = str_replace(',','',get_field('city_rent', $city_id));
        $cityRent = intval($cityRent);
        $commute = get_field('city_commute', $city_id); 

        $html .= '<h3>Quick Facts About '.get_the_title($city_id).'</h3>';
        $html .= '<dl class="bp23qf">';
        $html .= '<div class="bp23qf__col1">';
            $html .= '<div class="bp23qf__income">';
            $html .= '<div class="bp23qf__text"><dt>Median Household Income</dt>';
            $html .= '<dd>$'.number_format($cityHouseholdIncome).'</dd></div>'; 
            $html .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/bp23-qf-icons/quick-facts-income.svg" />';
            $html .= '</div>';
            $html .= '<div class="bp23qf__homevalue">';
            $html .= '<dt>Median Home Value</dt>';
            $html .= '<dd>$'.number_format($cityHomeValue).'</dd>'; 
            $html .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/bp23-qf-icons/quick-facts-home-value.svg" />';
            $html .= '</div>';
            $html .= '<div class="bp23qf__proptax">';
            $html .= '<dt>Average State Property Tax</dt>';
            $html .= '<dd>$'.number_format($cityPropTax).'</dd>'; 
            $html .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/bp23-qf-icons/quick-facts-prop-tax.svg" />';
            $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="bp23qf__col2">';
            $html .= '<div class="bp23qf__pop">';
            $html .= '<dt>Total Population</dt>';
            $html .= '<dd>'.number_format($cityPopulation).'</dd>'; 
            $html .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/bp23-qf-icons/quick-facts-population.svg" />';
            $html .= '</div>';
            $html .= '<div class="bp23qf__rent">';
            $html .= '<dt>Median Rent/Mo.</dt>';
            $html .= '<dd>$'.number_format($cityRent).'</dd>'; 
            $html .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/bp23-qf-icons/quick-facts-rent.svg" />';
            $html .= '</div>';
            $html .= '<div class="bp23qf__commute">';
            $html .= '<dt>Average Commute</dt>';
            $html .= '<dd>'.$commute.' minutes</dd>'; 
            $html .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/bp23-qf-icons/quick-facts-transportation.svg" />';
            $html .= '</div>';      
        $html .= '</div>';
        $html .= '</dl>';
        
    endif;
     return $html;
 }

 function jci_blocks_render_state_quick_facts() {
    $results = $wpdb->get_results( "SELECT * FROM 2024_state_data  ", OBJECT );
 }

 function jci_blocks_render_content_card_block(  $attributes ) {
    // var_dump($attributes);
    // return 'Id is ' . $attributes['postId'];
    
    if ($attributes && $attributes['postId']) {
        $id = $attributes['postId'];
        $title = get_the_title($id); 

        $html = '<a href="'.get_the_permalink( $id ).'">';
        $html .= '<div class="jci-content-card-block" style="background-image: url('.get_the_post_thumbnail_url($id, 'rel_article').');">';
        $html .= '<h2>'.$title.'</h2>';
        $html .= '</div></a>';

        return $html;
       
     } else {
        return 'No ID is set';
    }
 }

 function jci_blocks_render_bp_data () {
     $livscore = get_field('ls_livscore');
     $civic = get_field('ls_civic');
     $demographics = get_field('ls_demographics');
     $economy = get_field('ls_economy');
     $education = get_field('ls_education');
     $health = get_field('ls_health');
     $housing = get_field('ls_housing');
     $infrastructure = get_field('ls_infrastructure');
    $amenities = get_field('amenities');
    $remote_ready = get_field('remote_ready');
     $the_ID = get_the_ID();
     $thumb = get_the_post_thumbnail_url($the_ID, 'medium_large');
     $how_we_calculate_link = get_the_permalink( '92097' ); 
    //  $how_we_calculate_link = get_the_permalink( '88506' );
     ?>
     <style>
     .bp-data-image {
         background-image: url("<?php echo $thumb; ?>");
     }
     </style>
     <?php $html = <<< EOF
     <div class="bp-data-container">
     <div class="bp-data-image"></div>
     <table class="livscore-table">
        <thead>
            <tr>
                <th colspan=2><span class="livscore">liv score</span><br /><span class="livscore-number">$livscore</span><br /><a href="'.$how_we_calculate_link.'" class="livscore-link">How We Calculate Our Data</a></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Civics</td>
                <td>$civic</td>
            </tr>
            <tr>
                <td>Demographics</td>
                <td>$demographics</td>
            </tr>
            <tr>
                <td>Economy</td>
                <td>$economy</td>
            </tr>
            <tr>
                <td>Education</td>
                <td>$education</td>
            </tr>
            <tr>
                <td>Health</td>
                <td>$health</td>
            </tr>
            <tr>
                <td>Housing</td>
                <td>$housing</td>
            </tr>
            <tr>
                <td>Infrastructure</td>
                <td>$infrastructure</td>
            </tr>    
            <tr>
                <td>Amenities</td>
                <td>$amenities</td>
            </tr>   
            <tr>
                <td>Remote Ready </td>
                <td>$remote_ready</td>
            </tr>                                                         
        </tbody>
    </table>
    </div>
EOF;
     return $html;
     die();
    // return 'hello world';
    
 }

 function jci_blocks_render_post_title($attributes) {
     $ID = get_the_ID();
    //  print_r($attributes);
    //  print_r($secondarg);
    if ($attributes &&  $attributes["content"] && strlen($attributes["content"]) > 2) {
        $text = $attributes["content"];
    } else {
        $text = get_the_title( $ID);
    }
    //  $text = $attributes["content"];
    //  echo 'string length '.strlen($text);
    //  if (  strlen($text) < 2) {
    //      return '<h1 class="h2">'.get_the_title( $ID).'</h1>';
    //  } else {
         return '<h1 class="h1">'.$text.'</h1>';
    //  }
     die();
 }

 function jci_blocks_render_madlib() {
    $ID = get_the_ID();
    $title = get_the_title( $ID );
    // $county?
    $population = get_field('city_population', $ID);
    $income = get_field('city_household_income', $ID);
    $homeValue = get_field('city_home_value', $ID);
    $startOverLink = get_permalink( 18680);
    $gtkLink = get_permalink(19038);
    $mymLink = get_permalink( 70453 );

    
    $html = '<p>Looking to move to '.$title.'? You’ve come to the right place. Livability helps people find their perfect places to live, and we’ve got everything you need to know to decide if moving to '.$title.' is right for you.</p>';

    $html .= '<p>Let’s start with the basics: '.$title.' has a population of '.$population.'. What about cost of living in '.$title.'? The median income in '.$title.' is $'.$income.' and the median home value is $'.$homeValue.'.</p>';
    
    $html .= '<p>Read on to learn more about '.$title.', and if you’d like some tips and advice for making your big move, check out our <a href="'.$mymLink.'">Make Your Move</a> page, where you’ll find all kinds of stories and insights including <a href="'.$startOverLink.'">How to Start Over in a New City</a>, <a href="'.$gtkLink.'">Tips for Getting to Know a New City Before You Move</a> and so much more.</p>';

return $html;
die();
    
 }

 function jci_blocks_render_onehundred_list() {
    $currentID = get_the_ID();
    // $child_array = array();
    // $children = get_posts(array(
    //     'post_type'     => 'best_places',
    //     'post_parent'   => $currentID,
    //     'post_status'   => 'publish',
    //     'posts_per_page' => 100
    // ));
    // This was just done to accomodate ALM plugin limitation
    // foreach($children as $child) {
    //     $child_array[] = $child->ID;
    // }
    // print_r($child_array);
    // $list = implode(', ', $child_array);
    $args = array( 
        'post_type'     => 'best_places',
        'posts_per_page'=> 10,
        'post_status'   => 'publish',
        'orderby'    => 'meta_value_num',
        'meta_key'  => 'bp_rank',
        'order'     => 'ASC',
        // 'post__in'  => $child_array,
        'post_parent'   => $currentID,
        'paged'     => 1
    ); ?>
    <script>

    window.ohlObj = {};
    Object.assign(window.ohlObj, {current_page: '2'});
    Object.assign(window.ohlObj, {parent: <?php echo $currentID; ?>});
    // Object.assign(window.ohlObj, {children: <?php //echo json_encode($child_array); ?>});
    </script>


    <?php $ohl_query = new WP_Query($args);

    $html = '';
    if ($ohl_query->have_posts()): 
        $html .= '<ul class="onehundred-container">';
        while ($ohl_query->have_posts()): $ohl_query->the_post();
        $ID = get_the_ID();
        $place = get_field('place_relationship');
        $population = '';
        if ($place) {
            $population = get_field('city_population', $place[0]);
        }
        $html .= '<li class="one-hundred-list-item"><div class="one-hundred-list-container">';
        // $html .= '<a  class="ohl-thumb" href="'.get_the_permalink().'" ><div style="background-image: url('.get_the_post_thumbnail_url($ID, 'three_hundred_wide').');">';
        $html .= '<a  class="ohl-thumb" href="'.get_the_permalink().'" >'.get_the_post_thumbnail($ID, 'rel_article');
        $html .= '<p class="green-circle with-border">'.get_field('bp_rank').'</p></a>';
        $html .= '<div class="ohl-text">';
        $html .= '<a href="'.get_the_permalink().'"><h2>'.get_the_title().'</h2>';
        $html .= '<h3 class="uppercase">Livscore: '.get_field('ls_livscore');
        if ($population) {
            $html .= ' | Population: '.$population;
        }
        $html .= '</h3>';
        $html .= '<p>'.get_the_excerpt().'</p></a>';
        $html .= '</div></div></li>';
        endwhile;
        wp_reset_postdata();
        // inner block here

        $html .= '</ul>';
        
    endif;
    



    // $html = '<div class="onhundred-container">';
    // if(function_exists('alm_render')){
        // $html .= alm_render($args);
    // }
    // $html .= do_shortcode( '[ajax_load_more container_type="div" posts_per_page="10"  css_classes="onehundred-container" loading_style="infinite fading-circles" post_type="best_places" meta_key="bp_rank"  meta_compare="IN" post__in="'.$list.'" order="ASC" orderby="meta_value_num" scroll_container=".onehundred-container"]');
    // $html .= '</div>';

    
    return $html;
    die();
 } 

function jci_blocks_render_breadcrumbs() {
    return return_breadcrumbs();
}

function jci_blocks_render_bp_title() {
    $rank = get_field('bp_rank');

    $html = '<h3>#'.$rank.'. '.get_the_title().'</h3>';
    $html .= do_shortcode( '[addtoany]' );
    return $html;
    die();
} 

function jci_blocks_render_quote() {
    $html = '';
    if (have_rows('bp_quote')): 
        while(have_rows('bp_quote')): the_row();
        $text = get_sub_field('bp_quote_text');
        $source = get_sub_field('bp_quote_source');
        $html .= '<div class="quote-block">';
        $html .= $text ? '<p>'.$text.'</p>' : '';
        $html .= $source ? '<p class="bold">'.$source.'</p>' : '';
        $html .= '</div>';
        endwhile;
    endif;
    return $html;
    die();
}

function jci_blocks_render_magazine() {
    $iframe = get_field('calameo_id');
    $html = '';
    if ($iframe) {
        $html = '<div class="magazine-container">';
        $html .= '<iframe src=" //v.calameo.com/?bkcode='.$iframe.'&mode=viewer" width="100%" frameborder="0" scrolling="no" allowtransparency allowfullscreen></iframe>';
        $html .= '</div>';
    }
    
    return $html;
    die();
}

function jci_blocks_render_magazine_link() {
    $html = '';
    $ID = get_the_ID();
    $place_type = get_field('place_type', $ID);
    if ($place_type == 'metro') {
        $place_type = 'Region';
    }
    $args = array(
        'post_type'     => 'liv_magazine',
        'posts_per_page'=> 1,
        'post_status'   => 'publish',
        'meta_query'    => array(  
            'relation'  => 'AND',
            array( 
                'key'   => 'place_relationship',
                'value' => '"'.$ID.'"',
                'compare'=> 'LIKE'
            ),
            array( 
                'key'   => 'mag_place_type',
                'value' => $place_type,
                'compare'=> 'LIKE'
            )
        )
    );
    $mag_query = new WP_Query($args);
    if (count($mag_query->posts) == 1):
        $magID = $mag_query->posts[0]->ID;
        $src = get_the_post_thumbnail_url( $magID, 'rel_article');
        $sponsor = get_field('mag_sponsored_by_title', $magID);
        $sponsor_link = get_field('mag_sponsored_by_title', $magID);
        $html .= '<div class="magazine-link">';
        $html .= '<img src="'.$src.'" />';
        $html .= '<h4>'.get_the_title($magID).'</h4>';
        // $html .= '<p>Place type is '.$place_type.'</p>';
        if ($sponsor) {
            $html .= '<p>This digital edition of the <span class="italic">'.get_the_title($magID).'</span> is sponsored by the <a href="'.$sponsor_link.'">'.$sponsor.'</a>.</p>';
        }
        $html .= '<a style="color: #fff;" href="'.get_the_permalink($magID).'"><button>Read the Magazine</button></a>';
        $html .= '</div>';
    endif;
    return $html;
}

function jci_blocks_render_magazine_articles() {
    $html = '';
    // $ID = get_the_ID();
    if (have_rows('articles')):
        $html .= '<h2 class="green-line">In This Issue</h2>';
        $html .= '<div class="mag-article-list">';
        while (have_rows('articles')): the_row();
        $article = get_sub_field('article');
        if ($article && get_post_status($article ) == 'publish'):
        $art_id = $article->ID;
        $img = get_the_post_thumbnail_url( $art_id, 'rel_article' );
        $cat = get_the_category($art_id);
        $html .= '<a  class="mag-article" href="'.get_the_permalink( $art_id ).'" >';
        $html .= '<div style="background-image:linear-gradient(
            180deg,
            rgba(0, 0, 0, 0) 50%,
            rgba(0, 0, 0, 1) 100%
          ), url('.$img.'); " >';
        $html .= get_field('sponsored', $art_id) ? '<p class="sponsored-label">Sponsored</p>' : "";
        
        if ($cat) {$html .= '<h5 class="green-text uppercase">'.$cat[0]->name.'</h5>';}
        $html .= '<h3>'.get_the_title($art_id).'</h3>';
        $html .= '</div></a>';
        endif; 
        endwhile;
        $html .= '</div>';
    endif;
    
    return $html;
    die();
}

function jci_blocks_render_scroll_load_posts() {
    // $postdate = get_the_date($post->ID);
    // $ID = $post->ID;

    $html = '<h2 class="green-line">More Articles</h2>';
    $html .= '<div class="alm-container">';
    $ID = get_the_ID();
    // $current_date = get_the_date();
    // $args = array( 
    //     'post_type'         => 'post',
    //     'repeater'          => 'template_1',
    //     'posts_per_page'    => 5,
    // );
    // if(function_exists('alm_render')){
        // return alm_render($args);
        // die();
        $html .= do_shortcode( '[ajax_load_more id="my_id" repeater="template_1"]');
    // }
    $html .= '</div>';
    return $html;
    die();
}

function jci_blocks_render_sponsored_by() {
    // sponsored, sponsor_name, sponsor_text, sponsor_url, sponsor_logo
    $html = '';
    if (get_field('sponsored')): 
        $sponsor_text = get_field('sponsor_text') ? get_field('sponsor_text') : 'Sponsored by';
        $name = get_field('sponsor_name');
        $url = get_field('sponsor_url');
        $html .= '<div class="sponsored-by"><p>'.$sponsor_text.' <a href="'.$url.'">'.$name.'</a></p></div>';
    endif;
    return $html;
    die();
}

function jci_blocks_render_excerpt_and_post_author() {
    $html = '';
    if (has_excerpt( )) {
        $html .= '<p class="article-excerpt">'.get_the_excerpt().'</p>';
    }
    $html .= '<p class="author">By '.esc_html__( get_the_author(), 'livibility' ) .' on '.esc_html( get_the_date() ).'</p>';
    return $html;
}

function jci_blocks_render_author_block() {
    $ID = get_the_ID();
    $html = '<div class=author-bio>';
    $html .= get_avatar( get_the_author_meta( 'ID' ), '130' );
    $html .= '<div class="author-bio-content">';
    $html .= '<h2 class="author-title">About the Author</h2>';
    $html .= '<p class="author-description">'.get_the_author_meta( 'description' ).'</p>';
    $html .= '<p><a href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'"><button>More</button></a></p>';
    $html .= '</div></div>';
    return $html;
}

function jci_blocks_render_featured_image() {
    $html = '';
    if (has_post_thumbnail( ) ) {
        $html .= '<div>'.get_the_post_thumbnail('medium_large', array('style'=> 'height: auto; max-width: none;')).'</div>';
    }
    return $html;
}

function jci_blocks_render_ad_one() {
    // $ID = get_the_ID();
    // $all_ads = get_field( 'all_ads', $ID);
//     if ($aa) {
//         return the_ad_group(698); 
//     } else {
    $html = '';
    $html .= '<div class="wp-block-jci-blocks-ad-area-one wp-block-jci-ad-area-one">';
    // AD NOW INJECTED SO DIV ONLY
    //$html .= do_shortcode('[the_ad_placement id="top-leaderboard"]');
    // $html .= get_ad_group(698);
    $html .= '</div>';
//     // if ($all_ads) {
        return $html;
//     // }
//     }
}

function jci_blocks_render_ad_two() {
    // $ID = get_the_ID();
    // $all_ads = get_field( 'all_ads', $ID);
    
    // $html .= '<div class="wp-block-jci-blocks-ad-area-two" id="'.$ID.'-3"></div>';
    $html = '<div class="wp-block-jci-ad-area-two">';
    // $html .= '<script>
    // googletag.cmd.push(function() { googletag.display("div-gpt-ad-1568929535248-0"); });
    // </script>';
    // $html .= do_shortcode('[the_ad_group id="699"]');
    //$html .= do_shortcode('[the_ad_placement id="manual-right-rail"]');
    $html .= '</div>';
    // if ($all_ads) {
        return $html;
    // }
}

function jci_blocks_render_ad_three() {
    $ID = get_the_ID();
    $all_ads = get_field( 'all_ads', $ID);
    $html = '';
    $in_content = get_field( 'in_content_ads', $ID);
    // $html .= '<div class="wp-block-jci-blocks-ad-area-three" id="'.$ID.'-2"></div>';
    $html .= '<div class="wp-block-jci-ad-area-three" id="div-gpt-ad-1568929556599-0">';
    // $html .= '<script>
    // googletag.cmd.push(function() { googletag.display("div-gpt-ad-1568929556599-0"); });
    // </script>';
    $html .= '</div>';
    // if ($all_ads || $in_content) {
        return $html;
    // }
}

function jci_blocks_suggested_posts() {
    $html = '';
    // get last segment of url
    global $wp;
    $request = $wp->request;
    $pos = strrpos($request, '/');
    $basename = $pos === false ? $request : substr($request, $pos + 1);
    $basename = str_replace('-', ' ',$basename);
    $args = array(
        's'                 => '"'.$basename.'"',
        'posts_per_page'    => 20,
        'post_status'       => 'published'
    );
    $posts = get_posts($args);
    if ($posts) {
        $html .= '<p>Sorry, we couldn\'t find the page your were looking for. Here are some other results for '.ucwords($basename).'.  </p>';
        $html .= '<ul style="padding-left: 0;">';
        // foreach($posts as $p) {
        //     $pid = $p->ID;
        //     $html .= '<li class="one-hundred-list-container">';
        //     $html .= '<a href="'.get_the_permalink( $pid).'"  class="ohl-thumb" style="background-image: url('.get_the_post_thumbnail_url( $pid ).');"></a>';
        //     $html .= '<div class="ohl-text">';
        //     $html .= '<a href="'.get_the_permalink( $pid).'"><h3>'.get_the_title($pid).'</h3>'.get_the_excerpt($pid).'</a>';
        //     $html .= '</li>';
        // }
        $html .= do_shortcode('[ajax_load_more loading_style="infinite classic" repeater="template_3" post_type="any" posts_per_page="20" search="'.$basename.'" scroll_distance="-200"]');
        $html .= '</ul>';
    } else {
        $html .= '<p>Sorry, we couldn\'t find the page your were looking for. Here are our latest posts.</p>';
        $html .= do_shortcode('[ajax_load_more loading_style="infinite classic" repeater="template_3" post_type="post" posts_per_page="20" scroll_distance="-200"]');
    }
    return $html;
}

function jci_blocks_bp_sponsor() {
    $html = '';
    $sponsor = get_field('sponsor_name');
    if ($sponsor) {
        $url = get_field('sponsor_url');
        $html .= '<div class="bp-sponsor-container">';
        $html .= $url ? '<p>Sponsored by <a href="'.$url.'" target="_blank">'.$sponsor.'</a></p>' : '<p>Sponsored by '.$sponsor.'</p>';
        $html .= '</div>';
    }
    return $html;
}

function jci_blocks_render_city_list() {
    $ID = get_the_ID( );
    $args = array(
        'post_type' => 'liv_place',
        'child_of' => $ID,
        'echo' => false,
        'title_li' => ''
    );

    $html = '<div class="city-list-block" id="city-list">';
    $html .= '<h3>Cities in '.get_the_title($ID).' on Livability.com</h3>';
    $html .= '<ul>';
    $html .= wp_list_pages($args);
    $html .= '</ul>';
    $html .= '<div class="city-list-bkgrnd">';
    $html .= '<button class="show-city-list">See Full List</button>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function jci_blocks_render_city_301() {
    if (isset($_GET['city'])) {
        $city = $_GET['city'];
        $ID = get_the_ID( );
        $title = get_the_title($ID);
        $html = '';
        $html .= '<div class="wp-block-jci-blocks-info-box city">';
        $html .= '<p>We\'re sorry, '.ucfirst(htmlspecialchars($city)).', '.$title.' isn\'t on our site.</p>';
        $html .= '<a href="#city-list" class="components-button info-box-button">See Other '.$title.' Cities</a>';
        $html .= '</div>';
        return $html;
    } else {
        return;
    }
}

function jci_blocks_city_map() {
    $place_query = str_replace(',','',get_the_title());
    $place_query = str_replace(' ','+', $place_query);
    $html = '<div class="city-map-block">';
    $html .= '<iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&amp;q='.$place_query.'+USA" allowfullscreen=""></iframe>';
    $html .= '</div>';
    return $html;

}

function jci_blocks_mag_sponsor() {
    $sponsorname = get_field('mag_sponsored_by_title');
    $sponsorlink = get_field('mag_sponsored_by_link');
    $sponsorimg = get_field('mag_sponsored_by_logo');
    $html = '';
    if ($sponsorname ) {
        $html .= '<div class="magazine-sponsor-block"><p><em>This digital edition of the '.esc_html__(get_the_title(), 'livability').' is sponsored by ';
        $html .= $sponsorlink ? '<a href="'.esc_attr__( $sponsorlink, 'livability' ).'">': '';
        $html .= $sponsorname;
        $html .= $sponsorlink ? '</a>' : '';
        $html .= '.</em></p>';
        $html .= $sponsorimg ? wp_get_attachment_image($sponsorimg['ID'], 'full', false, array('height' => 'auto')) : '';
        $html .= '</div>';
    }
    
    return $html;
}

function jci_blocks_onehundred_paginated() {
    $currentID = get_the_ID();
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $args = array( 
        'post_type'     => 'best_places',
        'posts_per_page'=> 10,
        'post_status'   => 'publish',
        'orderby'    => 'meta_value_num',
        'meta_key'  => 'bp_rank',
        'order'     => 'ASC',
        // 'post__in'  => $child_array,
        'post_parent'   => $currentID,
        'paged'     => $paged
    ); 
    $ohl_query = new WP_Query($args);

        $html = '';
    if ($ohl_query->have_posts()): 
        $html .= '<ul class="onehundred-container">';
        while ($ohl_query->have_posts()): $ohl_query->the_post();
        $ID = get_the_ID();
        $place = get_field('place_relationship');
        $population = '';
        if ($place) {
            $population = get_field('city_population', $place[0]);
        }
        $html .= '<li class="one-hundred-list-item"><div class="one-hundred-list-container">';
        $html .= '<a  class="ohl-thumb" href="'.get_the_permalink().'" ><div style="background-image: url('.get_the_post_thumbnail_url($ID, 'three_hundred_wide').');">';
        $html .= '<p class="green-circle with-border">'.get_field('bp_rank').'</p></div></a>';
        $html .= '<div class="ohl-text">';
        $html .= '<a href="'.get_the_permalink().'"><h2>'.get_the_title().'</h2>';
        $html .= '<h3 class="uppercase">Livscore: '.get_field('ls_livscore');
        if ($population) {
            $html .= ' | Population: '.$population;
        }
        $html .= '</h3>';
        $html .= '<p>'.get_the_excerpt().'</p></a>';
        $html .= '</div></div></li>';
        endwhile;
        wp_reset_postdata();

        $html .= '</ul>';
        
    endif;
    
    return $html;
}

function jci_blocks_link_place_to_top_100() {
    $html = "";
    $args = array(
        'post_type'     => 'best_places',
        'post_status'   => 'publish',
        'posts_per_page' => 10,
        'meta_query'        => array(
            'relation'      => 'AND',
            array( 
                'key'       => 'place_relationship',
                'value'     => '"' . get_the_ID() . '"',
                'compare'   => 'LIKE'
            ),
            array(
                'key'       => 'is_top_one_hundred_page',
                'value'     => 1
            ),
        ),
        'tax_query'         => array(
            array(
                'taxonomy'  => 'best_places_years',
                'field'     => 'slug',
                'terms'     => range('2020','2030'),
            ),
        ),
    );
    $bp_query = new WP_Query($args);
    if ( $bp_query->have_posts() && get_field('place_type', get_the_ID()) == 'city' ):
        $recent_year = $recent_id = 1; // save id of post from most recent year
        $title = get_the_title();
        while ( $bp_query->have_posts() ): $bp_query->the_post();
        $years = get_the_terms( get_the_ID(), 'best_places_years' );
        $year = $years[0]->name;
        if ($year > $recent_year) {
            $recent_year = $year;
            $recent_id = get_the_ID();
        }
        endwhile;
        wp_reset_postdata();
        $rank = get_post_meta($recent_id, 'bp_rank', true);
        $parent = wp_get_post_parent_id( $recent_id );
        $badge = get_post_meta( $parent, 'badge', true );
        $html .= '<div class="link-place-to-top-100">';
        $html .= '<a href="'.get_the_permalink($parent).'" title="'.get_the_title($parent).'" >';
        $html .= wp_get_attachment_image($badge, 'three_hundred_wide').'</a>';
        $html .= '<p><a href="'.get_the_permalink($recent_id ).'" title="'.$title.' Best Place to Live" >';
        $html .= $title.' Ranks Among the Best Places to Live in the U.S. '.$recent_year.'</a></p>';
        $html .= '</div>';
    endif;
    return $html;
}

function jci_blocks_render_infobox($attributes, $content, $block ) {
    $html =  '<div class="wp-block-jci-info-box '.$attributes['icon'].'">';
    // $html .= '<p class="info-box-quote">'.$attributes['text'].'</p>';
    // $html .= '<p class="info-box-name">'.$attributes['name'].'</p>';
    // $html .= '<p class="info-box-position">'.$attributes['position'].'</p>';
    // if ($attributes['buttonText']):
        // $html .= '<button class="info-box-button" href="'.$attributes['buttonLink'].'">'.$attributes['buttonText'].'</button>';
    // endif;
    $html .= implode(",",$attributes);
    $html .= implode(",",$content);
    $html .= implode(",",$block);
    $html .= '</div>';
    return $html;
    // return print_r($content).'<br />'.print_r($block);
    
}

function jci_blocks_livscore_block() {
    if (get_post_type( ) == 'best_places'):
    $place = get_field('place_relationship');
    foreach ($place as $p) {
        if (get_field('place_type', $p) == 'city') {
            $city_title = get_the_title($p);
        }
    }
    $title = $city_title;
    $livscore = get_field('ls_livscore');
    $amenities = get_field('amenities');
    $environment = get_field('ls_environment');
    $economy = get_field('ls_economy');
    $education = get_field('ls_education');
    $transportation = get_field('ls_transportation');
    $health = get_field('ls_health');
    $housing = get_field('ls_housing');
    $safety = get_field('ls_safety');
    elseif (get_post_type( ) == 'liv_place'): // top 100 cities are no longer best places as of 2024
        global $wpdb;
        $id = get_the_ID();
        $title = get_the_title();
        $city_title = $title;
        $is_2024 = has_term('2024', 'best_places_years');
		$is_2025 = has_term('2025', 'best_places_years');
        $results = null;
     
     if ($is_2025) {
        $results = $wpdb->get_results( "SELECT * FROM 2025_top_100 WHERE place_id = $id", OBJECT );
     } elseif ($is_2024) {
        $results = $wpdb->get_results( "SELECT * FROM 2024_top_100 WHERE place_id = $id", OBJECT );
     } else {
        return '';
     }
     $livscore = $results[0]->livscore;
     $amenities = $results[0]->amenities;
     $environment = $results[0]->environment;
     $economy = $results[0]->economy;
     $education = $results[0]->education;
     $transportation = $results[0]->transportation;
     $health = $results[0]->healthcare;
     $housing = $results[0]->housing;
     $safety = $results[0]->safety;
    endif; // if place type is place
    $siteurl = site_url( );
    $cat_array= array(
        'amenities'         => $amenities,
        'economy'           => $economy,
        'education'         => $education,
        'environment'       => $environment,
        'health'            => $health,
        'housing'           => $housing,
        'safety'            => $safety,
        'transportation'    => $transportation,
    );
    
    $top_array = $cat_array;
    arsort($top_array);
    $top_array = array_slice($top_array, 0, 3);
    $top_array_keys = array_keys($top_array);
    $post_type = get_post_type(  );
    $html = <<< EOF
    <div class="livscore-block">
    <div class="livscore-block__arc" >
    <h3 class="h4" style="text-align: center;"><span style="display: block;">$title</span> Quality of Life LivScore</h3>
    <svg viewBox="0 55 100 60" xmlns="http://www.w3.org/2000/svg" >
        <defs>
        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="0%"   stop-color="#cce8b7"/>
        <stop offset="100%" stop-color="#40852c"/>
        </linearGradient>
        </defs>
        <path id="thepath" fill="none" stroke="url(#gradient)" stroke-width="4" style="stroke-linecap: round;" d="M 10 100 A10 10 0 0 1 90, 100"></path>
    
        <g id="Shape" transform="translate(-5,-5)" >
            <circle transform="translate(32,32)"  r=30 fill="#fff" />
            <path d="M36.2183406,0 C16.209607,0 0,16.1103448 0,35.9965517 C0,55.8827586 16.209607,71.9931034 36.2183406,71.9931034 C56.2270742,71.9931034 72.4366812,55.8827586 72.4366812,35.9965517 C72.4366812,16.1103448 56.2270742,0 36.2183406,0 Z M56.6069869,45.4362069 L56.6069869,23.662069 C56.6069869,23.0327586 56.1004367,22.4034483 55.3406114,22.4034483 L49.8951965,22.4034483 C49.1353712,22.4034483 48.628821,22.9068966 48.628821,23.662069 L48.628821,52.3586207 L46.0960699,52.3586207 L46.0960699,26.6827586 L42.930131,26.6827586 C42.2969432,26.6827586 41.790393,27.0603448 41.790393,27.6896552 L41.790393,50.4706897 L31.6593886,50.4706897 L31.6593886,39.3948276 L27.860262,35.8706897 L24.1877729,39.5206897 L24.1877729,50.5965517 L15.1965066,50.5965517 L15.1965066,39.5206897 L14.6899563,39.5206897 C14.0567686,39.5206897 12.6637555,39.2689655 11.3973799,38.1362069 C10.7641921,37.5068966 10.3842795,37.1293103 10.1310044,36.2482759 C9.49781659,34.4862069 10.1310044,33.2275862 10.510917,32.5982759 C10.510917,32.5982759 9.62445415,31.8431034 9.62445415,29.7034483 C9.62445415,27.437931 11.3973799,25.1724138 13.930131,24.9206897 C14.9432314,24.7948276 15.7030568,24.9206897 16.4628821,25.2982759 C16.4628821,25.2982759 17.0960699,23.9137931 19.5021834,23.662069 C23.9344978,23.2844828 26.8471616,27.5637931 25.7074236,30.2068966 C25.7074236,30.2068966 25.9606987,30.4586207 26.4672489,30.962069 L24.5676856,32.85 C22.9213974,31.2137931 22.7947598,31.087931 22.7947598,31.087931 C23.4279476,30.2068966 23.5545852,29.4517241 23.5545852,29.4517241 C23.6812227,27.6896552 21.7816594,26.0534483 20.0087336,26.1793103 C18.7423581,26.3051724 18.3624454,26.5568966 17.9825328,26.9344828 C17.349345,27.437931 17.0960699,28.0672414 17.0960699,28.0672414 C16.5895197,27.5637931 15.3231441,27.1862069 14.5633188,27.312069 C13.1703057,27.5637931 12.1572052,28.5706897 12.1572052,30.0810345 C12.1572052,31.7172414 13.8034934,32.4724138 13.8034934,32.4724138 C13.0436681,32.85 12.5371179,33.7310345 12.5371179,34.612069 C12.5371179,35.9965517 13.6768559,37.0034483 14.9432314,37.0034483 L15.3231441,37.0034483 L17.8558952,37.0034483 L17.8558952,48.0793103 L21.6550218,48.0793103 L21.6550218,38.5137931 L27.9868996,32.3465517 L31.5327511,35.9965517 L31.5327511,32.7241379 L34.1921397,32.7241379 L34.1921397,48.2051724 L39.1310044,48.2051724 L39.1310044,28.0672414 C39.1310044,25.9275862 40.3973799,24.5431034 42.8034934,24.5431034 L45.9694323,24.5431034 L45.9694323,23.9137931 C45.9694323,23.9137931 45.8427948,20.137931 49.768559,20.137931 L55.3406114,20.137931 C57.7467249,20.137931 59.139738,21.2706897 59.139738,23.9137931 L59.139738,42.037931 C59.139738,42.5413793 59.5196507,43.0448276 60.1528384,43.0448276 L62.8122271,43.0448276 L62.8122271,45.687931 L56.6069869,45.687931 L56.6069869,45.4362069 Z"  fill="#569747"/>
        </g>
        <text x="9" y="108" style="font-size: 5px; font-family: Jost, Arial, Helvetica, sans-serif; font-weight: bold;">0</text>
        <text x="83" y="108" style="font-size: 5px; font-family: Jost, Arial, Helvetica, sans-serif; font-weight: bold;">1000</text>
        <text x="83" y="108" style="font-size: 5px; font-family: Jost, Arial, Helvetica, sans-serif; font-weight: bold;">1000</text>
        <text x="28" y="100" style="font-size: 23px; font-family: Jost, Arial, Helvetica, sans-serif; font-weight: bold;">$livscore</text>
        
    </svg>
    
    <p class="livscore-block__calculate-link"><a href="$siteurl/methodology-ranking-criteria">How We Calculate Our Data</a></p>
    </div>
    <script>
    const thepath = document.getElementById('thepath');
    const pathLength = thepath.getTotalLength();
    const livscore = $livscore;
    const livscorepoint = (livscore * pathLength) / 1000;
    const pt = thepath.getPointAtLength(livscorepoint);
    const endpoint = document.getElementById('Shape');
    const translate = 'translate(' + pt.x + ', ' + pt.y + ') scale(0.15)';
    endpoint.setAttribute("transform", translate);
    </script>
    
EOF;
$html .= '<div class="livscore-block__top-cat">';
$html .= '<h3 class="h4" style="text-align: center;"><span style="display: block;">'.$title.'</span> Top Categories</h3>';
$html .= '<ul class="livscore-block__cat-list">';
foreach($top_array as $key => $value) {
    $key == "housing" ? $mod_key = "Housing & Cost of Living" : $mod_key = ucfirst($key);
    $html .= '<li><div class="livscore-block__cat-bkgrnd" style="background-image: url('.get_stylesheet_directory_uri(  ).'/assets/images/livability-category-icons/green/'.$key.'.svg);"></div><p>'.$mod_key.'</p></li>';
}
$html .= '</ul>';
$html .= '<p>'.substr($city_title,0, -4).' ranks highest for '.$top_array_keys[0].', '.$top_array_keys[1].' and '.$top_array_keys[2].'.</p>';
$html .= '</div></div>';

$html .= '<div style="text-align: center;"><button class="lcd-btn"><span>See</span> Livscore Category Details <i class="fas fa-angle-down"></i><i class="fas fa-angle-up"></i></button></div>';
$html .= '<div class="livscore-category-details hidden">';
foreach ($cat_array as $key => $cat) {
    $percent = $cat;
    $key == "housing" ? $mod_key = "Housing & Cost of Living" : $mod_key = ucfirst($key);
    $description = get_field($key, 'option');
    $html .= '<div class="cat-detail">';
    $html .= '<p class="cat-detail-name '.$key.'">'.$mod_key.'<span>'.$cat.'<i class="fa fa-question-circle"></i></span></p>';
    $html .= '<div class="lcd-status-bar"><div style="width: '.$percent.'%;" class="lcd-status"><span class="lcd-circle"></span></div></div>';
    if ($description) {
        $html .= '<div class="cat-detail-description">'.$description.'</div>';
    } 
    $html .= '</div>';
}

$html .= '</div>';
    return $html; 
    // return var_dump($results);

}

function jci_blocks_weather_block() {
    $place = get_field('place_relationship');
    foreach ($place as $p) {
        if (get_field('place_type', $p) == 'city') {
            $city_id = $p;
        }
    }
    $sun = get_field('city_sun', $city_id);
    $rain = get_field('city_rain', $city_id);
    $snow = get_field('city_snow', $city_id);
    if ($sun && $rain && $snow):
    $weather_array = array(
        'sun'       => $sun,
        'rain'      => $rain,
        'snow'      => $snow
    );
    $html = '<h3 class="weather-block-title">'.substr(get_the_title($city_id), 0, -4).' Weather</h3>';
    $html .= '<div class="weather-block">';
    foreach ($weather_array as $key => $value) {
        // $unit = $key == 'sun' ? 'days' : 'inches';
        if ($key == 'sun') {
            $unit = 'days';
        } elseif($value == 1) {
            $unit = 'inch';
        } else {
            $unit = 'inches';
        }
        $html .= '<div class="'.$key.'"><div class="weather-icon"></div><h3>'.$value.' '.$unit.'</h3><p>Average Annual '.ucfirst($key).'</p></div>';
    }
    $html .= '</div>';
    return $html;
    endif;
}

function jci_blocks_link_to_2023_best_place() {
    $bp_args = array(
        'posts_per_page'       => 1,
        'meta_query'        => array(
            'relation'      => 'AND',
            array( 
                'key'       => 'place_relationship',
                'value'     => '"' . get_the_ID() . '"',
                'compare'   => 'LIKE'
            ),
            array(
                'key'       => 'is_top_one_hundred_page',
                'value'     => 1
            ),
        ),
        'tax_query'         => array(
            array(
                'taxonomy'  => 'best_places_years',
                'field'     => 'slug',
                'terms'     => array('2023'),
            ),
        ),
    );
    $bp_query = new WP_Query($bp_args);
    if ($bp_query->have_posts()):
        $html = '';
        $city_title = get_the_title();
        while ($bp_query->have_posts()): $bp_query->the_post();
        $ID = get_the_ID();
        $yoast_opengraph_image_id = get_post_meta( $ID, '_yoast_wpseo_opengraph-image-id', true );
        $livscore = get_field('ls_livscore');
        $amenities = get_field('amenities');
        $environment = get_field('ls_environment');
        $economy = get_field('ls_economy');
        $education = get_field('ls_education');
        $transportation = get_field('ls_transportation');
        $health = get_field('ls_health');
        $housing = get_field('ls_housing');
        $safety = get_field('ls_safety');
        $cat_array= array(
            'amenities'         => $amenities,
            'economy'           => $economy,
            'education'         => $education,
            'environment'       => $environment,
            'health'            => $health,
            'housing'           => $housing,
            'safety'            => $safety,
            'transportation'    => $transportation,
        );
        arsort($cat_array);
        $cat_array = array_slice($cat_array, 0, 3);
        $cat_array = array_keys($cat_array);
        if (preg_match("/^[A-G]/i", $city_title)) {
        $txt = '<p><strong>Is '.$city_title.' a good place to live? </strong>Thanks to high scores for its '.$cat_array[0].', '.$cat_array[1].' and '.$cat_array[2].', '.substr($city_title, 0, -4).' ranked among Livability\'s <a href="'.get_the_permalink().'">Best Places to Live in the U.S. </a>for 2023.</p>';
        } elseif (preg_match("/^[H-O]/i", $city_title)) {
            $txt = '<p><strong>'.$city_title.' is one of Livability\'s Top 100 best cities in America,</strong> 
            scoring high marks for its '.$cat_array[0].', '.$cat_array[1].' and '.$cat_array[2].'. Read more about <a href="'.get_the_permalink().'">why '.substr($city_title, 0, -4).' is a good place to live.</a></p>';
        } else {
            $txt = '<p><strong>'.$city_title.' is one of the best places to live in America,</strong>
            thanks to high scores for its '.$cat_array[0].', '.$cat_array[1].' and '.$cat_array[2].'. Read more about the <a href="'.get_the_permalink().'">quality of life in '.substr($city_title, 0, -4).'.</a></p> ';
        } 
        $html .= '<div class="link-to-2023-bp">';
        $html .= $yoast_opengraph_image_id  ? '<div class="l2-23-bp-container"><a href="'.get_the_permalink().'">'.wp_get_attachment_image( $yoast_opengraph_image_id, 'large').'</a></div>' : '';
        $html .= $txt;
        $html .= '</div>';
        return $html; 


        endwhile;

    endif;
    
}

function jci_blocks_internally_promoted() {
    $high_traffic_articles = get_field('high_traffic_articles', 'options'); 
    $html = ''; 
    if ($high_traffic_articles) {
        foreach($high_traffic_articles as $hta) {
            if (get_the_ID() == $hta['ht_article']) {
            //    echo 'id matches';
                $promoted = get_posts(array(
                    'post_type'      => array('liv_place', 'post'),
                    'numberposts'    => 1,
                    'orderby'        => 'rand',
                    'meta_key'       => 'promote',
                    'meta_value'     => 1
                ));
            //    print_r($promoted);
            if ($promoted) {
                $p = $promoted[0];
                $html =  '<div class="promoted-block">';
                $html .= '<h4 class="promoted-block__title">Also Check Out:</h3>';
                $html .= '<a href="'.get_the_permalink($p->ID).'">'.get_the_post_thumbnail( $p->ID, 'medium' ).'</a>';
                $html .= '<p><a href="'.get_the_permalink($p->ID).'">'.get_the_title($p->ID).'</a></p>';
                $html .= '</div>';
            }
            break;
            }
        }
    } 
    return $html;
}

function jci_blocks_bp_301() {
    if (isset($_GET['bpredirect'])) {
        $html = '';
        $html .= '<div class="wp-block-jci-blocks-info-box city">';
        $html .= '<p>We\'re sorry, that content has expired. Please see our other best places.</p>';
        $html .= '</div>';
        return $html;
    } else {
        return;
    }
}

function jci_blocks_magazine_brand_stories() {
    $places = get_field('place_relationship');
    if (count($places) == 1) {
        $place_args = array(
            'key'       => 'place_relationship',
            'value'     => $places[0],
            'compare'   => 'LIKE'
        );
    } else {
        $place_args = array('relation'      => 'OR');
        foreach ($places as $p) {
            $place_args[] = array(
                'key'       => 'place_relationship',
                'value'     => $p,
                'compare'   => 'LIKE'
            );
        }
    }
    $args = array(
        'post_type'         => 'post',
        'post_status'       => 'publish',
        'posts_per_page'    => -1,
        'meta_query'        => array(
            'relation'      => 'AND',
            array(
                'key'       => 'sponsored',
                'value'     => true,
            ),
            $place_args
        )
    );
    $brand_query = new WP_Query($args);

    if ($brand_query->have_posts()):
        $html .= '<div class="brand-stories">';
        $html .= '<h2 class="wp-block-jci-blocks-section-header green-line">Businesses & Brands to Know</h2>';
        $html .= '<div class="pwl-slick">';
        while ($brand_query->have_posts()): $brand_query->the_post();
        $ID = get_the_ID();
        $sponsor_name = get_field('sponsor_name'); 
        $sponsor_url = get_field('sponsor_url');  
        $html .= '<div class="brand-stories__card">';
        $html .= '<a href="'.get_the_permalink().'" class="brand-stories__img">'.get_the_post_thumbnail( $ID,'rel_article' ).'</a>';
        //$html .= '<div style="background-image: url('.get_the_post_thumbnail( $ID,'rel_article' ).'); display: block; height: 180px;"></div></a>';
        $html .= '<div class="brand-stories__title">';
        $html .= '<h4><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4></div>';
        $html .= '<p class="brand-stories__sponsor-text"><a href="'.$sponsor_url.'" target="_blank">Sponsored by '.$sponsor_name.'</a></p>';
        $html .= '</div>'; // title, card
        endwhile;
        $html .= "</div>"; //pwl slick
        $html .= "</div>";
    endif;
    // $html = '<pre>';
    // $html = print_r($place_args);
    // $html = '</pre>';
    // foreach ($places as $place) {
    //     $html .= 'place is '.$place.'<br />';
    // }
    return $html;
}

function jci_blocks_content_weather_block() {
    $id = get_the_ID();
    global $wpdb; 
    if (has_post_parent( $id )) {
        $results = $wpdb->get_results( "SELECT * FROM 2024_city_data WHERE place_id = $id", OBJECT );
    } else {
        $results = $wpdb->get_results( "SELECT * FROM 2025_state_data WHERE place_id = $id", OBJECT );
    }
    

    $high = $results[0]->avg_high_temp;
    $low = $results[0]->avg_low_temp;
    $rain = $results[0]->avg_rain;
    $snow = $results[0]->avg_snow;

   if ($high != null && $low != null && $rain != null && $snow != null):
    $html = '<div class="weather-block-2">';
    $html .= '<div class="weather-block-2__card temp"><div class="weather-block-2__icon temp"></div><h4>Average Temperatures</h4><p>'.$high.' high / '.$low.' low</p></div>';
    $html .= '<div class="weather-block-2__card rain"><div class="weather-block-2__icon rain"></div><h4>Average Annual Rainfall</h4><p>'.$rain.' in</p></div>';
    $html .= '<div class="weather-block-2__card snow"><div class="weather-block-2__icon snow"></div><h4>Average Annual Snowfall</h4><p>'.$snow.' in</p></div>';
    $html .= '</div>';
    return $html;
    endif; 
}

function jci_blocks_largest_cities() {
    global $wpdb;
    $state = get_the_title();
    $html = '';
    
    $results = $wpdb->get_results( "SELECT city, city_pop, state FROM 2025_city_data WHERE state='$state' ORDER BY city_pop DESC", ARRAY_A);
    if ($results):
    $results = array_slice($results, 0, 12);
    $html .= '<ul class="liv-table">';
    foreach ($results as $r) {
        $html .= '<li><span class="table-key">'.substr($r['city'],0, -4).'</span><span class="table-value">'.number_format($r['city_pop']).'</span></li>';
    }
    $html .= '</ul>';
    return $html;
    endif;
}

function jci_blocks_industries() {
    global $wpdb;
    $stateId = get_the_ID();
    $html = '';
    $results = $wpdb->get_results("SELECT INDEXAGRI, INDEXMINE, INDEXCONS, INDEXMMFG, INDEXWTRA, INDEXRTRA, INDEXTRAN, INDEXUTIL, INDEXINFO, INDEXFIN, INDEXREAL, INDEXPSRV, INDEXMGMT, INDEXADS, INDEXEDUC, INDEXHLTH,  INDEXARTS, INDEXFOOD, INDEXOTHR, INDEXPUBA FROM 2025_state_data WHERE place_id = $stateId", ARRAY_A);
    if ($results):
    if (!function_exists('mapIndustry')){
        function mapIndustry($ind) {
            switch ($ind) {
                case 'INDEXAGRI':
                    return 'Agriculture/Forestry';
                    break;
                case 'INDEXMINE':
                    return 'Mining/Quarrying/Oil/Gas';
                    break;
                case 'INDEXCONS':
                    return 'Construction';
                    break;              
                case 'INDEXMMFG':
                    return 'Manufacturing';
                    break;
                case 'INDEXWTRA':
                    return 'Wholesale Trade';
                    break;
                case 'INDEXRTRA':
                    return 'Retail Trade';
                    break;
                case 'INDEXTRAN':
                    return 'Transportation/Warehousing';
                    break;
                case 'INDEXUTIL':
                    return 'Utilities';
                    break; 
                case 'INDEXINFO':
                    return 'Information';
                    break;
                case 'INDEXFIN':
                    return 'Finance/Insurance';
                    break;
                case 'INDEXREAL':
                    return 'Real Estate';
                    break;
                case 'INDEXPSRV':
                    return 'Professional/Scientific/Tech';
                    break;
                case 'INDEXMGMT':
                    return 'Management of Companies';
                    break;
                case 'INDEXADS':
                    return 'Administrative/Waste Management';
                    break;
                case 'INDEXEDUC':
                    return 'Educational';
                    break;    
                case 'INDEXHLTH':
                    return 'Health Care and Social';
                    break;
                case 'INDEXARTS':
                    return 'Arts/Entertainment/Recreation';
                    break;
                case 'INDEXFOOD':
                    return 'Accommodation/Food Services';
                    break;
                case 'INDEXOTHR':
                    return 'Other Services';
                    break; 
                case 'INDEXPUBA':
                    return 'Public Administration';
                    break;                                 
                default:
                    return '';
                    break;
            }
        }
    }
    $results = $results[0];
    arsort($results);
    $results = array_slice($results, 0, 12);
    $html .= '<ul class="liv-table">';
    foreach ($results as $key=>$value) {
        $html .= '<li><span class="table-key">'.mapIndustry($key).'</span><span class="table-value">'.number_format($value).'</span></li>';
    }
    $html .= '</ul>';
    endif;
    return $html;
}

function jci_blocks_occupations() {
    global $wpdb;
    $stateId = get_the_ID();
    $results = $wpdb->get_results("SELECT OCCEXMGMT, OCCEXBUSF, OCCEXCOMP, OCCEXARCH,
     OCCEXSCI, OCCEXCOMM, OCCEXLEG, OCCEXEDUC, OCCEXARTS, OCCEXHLT1, OCCEXHLT2, 
     OCCEXHLT3, OCCEXFIRE, OCCEXCOPS, OCCEXFPRP, OCCEXCLNG,  OCCEXPCAR, OCCEXSALE, 
     OCCEXOFF, OCCEXFARM, OCCEXCNEX, OCCEXINST, OCCEXPROD, OCCEXTRAN, OCCEXMATM FROM 2025_state_data WHERE place_id = $stateId", ARRAY_A);
        if ($results):
    if (!function_exists('mapOccupation')) {
    function mapOccupation($occ) {
    switch ($occ) {
        case 'OCCEXMGMT' :
            return 'Management';
            break;
        case 'OCCEXBUSF' :
            return 'Business/Financial Operations';
            break;
        case 'OCCEXCOMP' :
            return 'Computer/Mathematical';
            break;
        case 'OCCEXARCH' :
            return 'Architecture/Engineering';
            break;
        case 'OCCEXSCI' :
            return 'Life/Physical/Social Science';
            break;
        case 'OCCEXCOMM' :
            return 'Community/Social Service';
            break;
        case 'OCCEXLEG' :
            return 'Legal';
            break;
        case 'OCCEXEDUC' :
            return 'Education/TrainingLibrary';
            break;
        case 'OCCEXARTS' :
            return 'Arts/Design/Ent./Sports/Media';
            break;
        case 'OCCEXHLT1' :
            return 'Health Diagnosing/Treating/Technical';
            break;
        case 'OCCEXHLT2' :
            return 'Health Technologists/Technicians';
            break;
        case 'OCCEXHLT3' :
            return 'Healthcare Support';
            break;
        case 'OCCEXFIRE' :
            return 'Fire Fighting/Prevention/Protective';
            break;
        case 'OCCEXCOPS' :
            return 'Law Enforcement';
            break;
        case 'OCCEXFPRP' :
            return 'Food Preparation/Serving';
            break;
        case 'OCCEXCLNG' :
            return 'Cleaning/Maintenance';
            break;
        case 'OCCEXPCAR' :
            return 'Personal Care/Service';
            break;
        case 'OCCEXSALE' :
            return 'Sales';
            break;
        case 'OCCEXOFF' :
            return 'Office/Administrative Support';
            break;
        case 'OCCEXFARM' :
            return 'Farming/Fishing/Forestry';
            break;
        case 'OCCEXCNEX' :
            return 'Construction/Extraction';
            break;
        case 'OCCEXINST' :
            return 'Installation/Maintenance/Repair';
            break;
        case 'OCCEXPROD' :
            return 'Production';
            break;
        case 'OCCEXTRAN' :
            return 'Transportation';
            break;
        case 'OCCEXMATM' :
            return 'Material Moving';
            break;
        default : 
            return '';
            break;
        }
    }
}
    $results = $results[0];
    arsort($results);
    $results = array_slice($results, 0, 12);
    $html .= '<ul class="liv-table">';
    foreach ($results as $key=>$value) {
        $html .= '<li><span class="table-key">'.mapOccupation($key).'</span><span class="table-value">'.number_format($value).'</span></li>';
    }
    $html .= '</ul>';
    endif;
    return $html;
}

function jci_blocks_schools() {
    global $wpdb;
    $stateId = get_the_ID();
    $html = '';
    $results = $wpdb->get_results( "SELECT college, enrollment FROM largest_colleges WHERE place_id='$stateId' ORDER BY enrollment DESC", ARRAY_A);
    $results = array_slice($results, 0, 12);
    if ($results):
        $html .= '<ul class="liv-table">';
        foreach ($results as $s) {
            $html .= '<li><span class="table-key">'.$s['college'].'</span><span class="table-value">'.number_format($s['enrollment']).'</span></li>';
        }
        $html .= '</ul>';
    endif;
    return $html;
    // print_r($results);
}