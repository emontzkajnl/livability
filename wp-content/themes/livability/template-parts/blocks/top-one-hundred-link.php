<?php 
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
// echo 'top 100 block here. ';
// print_r($bp_query->posts);

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
    echo '<div class="link-place-to-top-100">';
    echo '<a href="'.get_the_permalink($parent).'" title="'.get_the_title($parent).'" >';
    echo wp_get_attachment_image($badge, 'three_hundred_wide').'</a>';
    echo '<p><a href="'.get_the_permalink($recent_id ).'" title="'.$title.' Best Place to Live" >';
    echo $title.' Ranks Among the Best Places to Live in the U.S. '.$recent_year.'</a></p>';
    echo '</div>';
endif;