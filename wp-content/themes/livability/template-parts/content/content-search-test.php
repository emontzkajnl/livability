<?php 

$state_string_array = [
    'Alabama',
    'Alaska',
    'Arizona',
    'Arkansa',
    'California',
    'Colorado',
    'Connecticut',
    'Delaware',
    'District of Columbia',
    'Florida',
    'Georgia',
    'Hawaii',
    'Idaho',
    'Illinois',
    'Indiana',
    'Iowa',
    'Kansas',
    'Kentucky',
    'Lousiana',
    'Maine',
    'Maryland',
    'Massachusetts',
    'Michigan',
    'Minnesota',
    'Missouri',
    'Montana',
    'Nebraska',
    'Nevada',
    'New Hampshire',
    'New Jersey',
    'New Mexico',
    'New York',
    'North Carolina',
    'North Dakota',
    'Ohio',
    'Oklahoma',
    'Oregon',
    'Pennsylvania',
    'Rhode Island',
    'South Carolina',
    'South Dakota',
    'Tennessee',
    'Texas',
    'Utah',
    'Vermont',
    'Virginia',
    'Washington',
    'West Virginia',
    'Wisconsin',
    'Wyoming'
];

$state_abbv_array = ['AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY'];

$search_term = get_search_query();
$has_state = false;
// check for state name in string
foreach ($state_string_array as $ssa) {
    if (stripos($search_term, $ssa) !== false) {
        $has_state = true;
        break;
    }
}
// check for state abbreviation at end of search term or it equals the search term
if (!$has_state) {
    $substring = substr($search_term, -3);
    foreach ($state_abbv_array as $saa) {
        if ($substring == ' '.$saa || (strlen($search_term) == 2 && $search_term == $saa)) {
            $has_state = true;
            break;
        }
    }
}

if ($has_state == true):
function order_search_by_posttype( $orderby, $wp_query ){

    global $wpdb;
    // global $has_state;
    

    if( ! $wp_query->is_admin && $wp_query->is_search) {

        $orderby =
            "
            CASE WHEN {$wpdb->prefix}posts.post_type = 'liv_place' THEN '1'
                 WHEN {$wpdb->prefix}posts.post_type = 'post' THEN '2' 
                 WHEN {$wpdb->prefix}posts.post_type = 'best_places' THEN '3'
                 WHEN {$wpdb->prefix}posts.post_type = 'liv_magazine' THEN '4' 
            ELSE {$wpdb->prefix}posts.post_type END ASC, 
            {$wpdb->prefix}posts.post_title ASC";
    }

    return $orderby;

}

add_filter( 'posts_orderby', 'order_search_by_posttype', 10, 2 );
endif;
// $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args = [
    'post_type'     => 'any',
    'post_status'   => 'publish',
    'posts_per_page'=> 30,
    's'             => $search_term,
    'order'         => 'ASC',
    // 'paged'         => $paged,
    // 'orderby'       => 'meta_value',
    // 'meta_key'      => 'client_place'
];

$search_query = new WP_Query($args);

if ($search_query->have_posts()):
   // move client city to beginning of results
    foreach ($search_query->posts as $key => $value) {
        if (get_post_type($value) == 'liv_place') {
            if (get_post_meta($value->ID, 'client_place', true)  == 1) {
                $client = $value;
                unset($search_query->posts[$key]);
                array_unshift($search_query->posts, $client);
            }
        }
    }

    while ($search_query->have_posts()): $search_query->the_post();
    $type = get_post_type();
$heading = '';
switch ($type) {
    case 'liv_place':
        $heading = get_field('place_type').' page';
        break;
    case 'post':
        $cat = get_the_category();
        $heading = $cat[0]->name;
        break;  
    case 'liv_magazine':
        $heading = 'Magazine';
        break;     
    case 'best_places':
        $parent = get_post_parent();
        if ($parent) {
            $heading = get_the_title($parent->ID);
        } else {
            $heading = 'Best Places';
        }
        break;   
    default:
        break;
}
$the_title = get_the_title();

?>
<div class="two-row-ohl" style="margin-bottom: 50px;">
    <div class="one-hundred-list-container">
<a href="<?php echo get_the_permalink( ); ?>" class="ohl-thumb" style="background-image: url(<?php echo the_post_thumbnail_url(); ?>);"></a>
<div class="ohl-text">
<h5 class="green-text uppercase"><?php echo $heading; ?></h5>
<a href="<?php  echo get_the_permalink( ); ?>">
<?php _e('<h2>'.$the_title.'</h2>', 'livability'); 
the_excerpt();
 ?>
</a>
</div>
</div>
<?php
    // best place 
    if ($type == 'liv_place'):
        $ID = get_the_ID();
        // echo 'ID is '.$ID;
        global $post;
        $backup = $post;
        $bp_args = [
            'numberposts'    => 20,
            'post_type'         => 'best_places',
            // 'meta_key'          => 'place_relationship',
            // 'meta_value'        => '"'.get_the_ID().'"',
            // 'post_status'       => 'publish',
            'meta_query'        => array(
                array( 
                    'key'   => 'place_relationship',
                    'value' => '"'.$ID.'"',
                    'compare'   => 'LIKE'
                ),
            ),
        ];
        $best_places = new WP_Query($bp_args);
        if ($best_places->have_posts()): ?>
            <div class="ohl-second-row">
            <div class="ohl-second-bp-notice">
                <h4>Livability<br /><span>Best Place</span></h4>
            </div>
            <div class="ohl-second-text">
                <h4><?php echo $the_title.' is also a Livability Best Place' ?></h4>
                <ul>
           <?php  while ($best_places->have_posts()): $best_places->the_post();
            // $innerID = get_the_ID(  );
            $parent = get_post_parent();
            // echo 'the inner title is '.get_the_title();?>
            <li><a href="<?php echo get_the_permalink( $parent->ID ); ?>"><?php echo $parent->post_title; ?></a></li>
            <?php // print_r($parent);
            endwhile;
            echo '</ul></div></div>';
        endif;
        $post = $backup;
        // print_r($best_places);
        // echo '</div>';
    endif;
    ?>
    </div> <!-- two-row-ohl-->
   <?php  endwhile;
//    echo '</div>';
// else: echo 'no results found';
endif;


// echo $has_state ? 'has state' : 'no state'; 