<?php 
// function get_place_distance($lat1, $lon1, $lat2, $lon2) {

//     $theta = $lon1 - $lon2;
//     $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
//     $dist = acos($dist);
//     $dist = rad2deg($dist);
//     $miles = $dist * 60 * 1.1515;

//     return $miles;
    
//   }

// echo get_place_distance(34.03, -118.24, 40.71, -74.08);

function make_places_csv() {
    $csvFile = get_template_directory(  ).'/places.csv';
    $args = array(
        'post_type'         => 'liv_place',
        'post_status'       => 'publish',
        'posts_per_page'    => -1
    );
    $places = get_posts($args);
    if ($places->have_posts()):
        while ($places->have_posts()): $places->the_post();
        echo 'id is '.get_the_id().' and the title is '.get_the_title().'<br />';
        endwhile;
    endif;

}
// add_action( 'init', 'make_places_csv');
