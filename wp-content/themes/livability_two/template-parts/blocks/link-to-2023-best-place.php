<?php 
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
    $txt = '<p><strong>Is '.$city_title.' a good place to live right now? </strong>Thanks to high scores for its '.$cat_array[0].', '.$cat_array[1].' and '.$cat_array[2].', '.substr($city_title, 0, -4).' ranked among Livability\'s <a href="'.get_the_permalink().'">Best Places to Live in the U.S. </a>for 2023.</p>';
    } elseif (preg_match("/^[H-O]/i", $city_title)) {
        $txt = '<p><strong>'.$city_title.' is one of Livability\'s Top 100 best cities in America,</strong> 
        scoring high marks for its '.$cat_array[0].', '.$cat_array[1].' and '.$cat_array[2].'. Read more about <a href="'.get_the_permalink().'">why '.substr($city_title, 0, -4).' is a good place to live.</a></p>';
    } else {
        $txt = '<p><strong>'.$city_title.' is one of the best places to live in America,</strong>
        thanks to high scores for its '.$cat_array[0].', '.$cat_array[1].' and '.$cat_array[2].'. Read more about the <a href="'.get_the_permalink().'">quality of life in '.substr($city_title, 0, -4).'.</a></p> ';
    } ?>
    <div class="link-to-2023-bp">
    <?php echo $yoast_opengraph_image_id ? '<div class="l2-23-bp-container"><a href="'.get_the_permalink().'">'.wp_get_attachment_image( $yoast_opengraph_image_id, 'large').'</a></div>' : '';
        echo $txt; ?>
    </div>
    
   
    <?php endwhile;
endif;


wp_reset_postdata();