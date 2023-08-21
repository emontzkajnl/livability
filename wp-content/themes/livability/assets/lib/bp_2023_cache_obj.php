<?php

if (!$_SESSION['cached_bp_posts']):
$bp_args = array(
    'post_type'			=> 'best_places',
    'posts_per_page'	=> 100,
    'post_status'		=> array( 'publish', 'draft', 'future' ),
    'tax_query'			=> array(
        array(
            'taxonomy'	=> 'best_places_years',
            'field'		=> 'slug',
            'terms'		=> '2023'
        )
    ),
);
$bp_posts = get_posts($bp_args);
foreach ($bp_posts as $key => $bp) {
    // echo 'key '.$key.' title '.$bp->post_title.'<br />';
    $meta = get_post_meta( $bp->ID );
    // print_r($meta);
    $places_array = $meta['place_relationship'][0];
    $places_array = unserialize($places_array);
    foreach($places_array as $p) {
        if (get_field('place_type', $p) == 'city'){
            $city_meta = get_post_meta($p);
            // print_r($city_meta);
            $bp->latitude = $city_meta['jci-latitude'][0];
            $bp->longitude = $city_meta['jci-longitude'][0];
        } 
    }
}
$_SESSION['cached_bp_posts'] = $bp_posts;
endif;