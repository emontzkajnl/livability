<?php 

/**
 * Slider Block Template
 */


 // Create id attribute allowing for custom "anchor" value.
$id = 'slider-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'list-carousel-container';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
// $initial_slide = get_field('initial_slide') ? get_field('initial_slide') : 1; 
//GET CURRENT RANK
// GET LIST TAXONOMY AND QUERY ALL: best_places_taxonomy
// ORDER ALL BY RANK
$currentID = get_the_ID();
$parent = get_post_parent($currentID);
// $terms = get_the_terms($currentID, 'best_places_taxonomy');
// echo 'terms';
// print_r($terms);
// echo 'id is '.$terms[0]->term_id;
// $termID = $terms[0]->term_id;
$args = array( 
    'post_type'     => 'best_places',
    'posts_per_page'=> -1,
    'post_status'   => 'publish',
    'orderby'    => 'meta_value_num',
    'meta_key'  => 'bp_rank',
    'order'     => 'ASC',
    'post_parent'   => $parent->ID,
    // 'tax_query'     => array(
    //     array( 
    //         'taxonomy'  => 'best_places_taxonomy',
    //         'field'     => 'term_id',
    //         'terms'     => $termID,
    //     )
    // ),
);


$query = new WP_Query( $args );
$current_rank = get_field('bp_rank', $currentID);

$queryArray = $query->posts;
// echo '<pre>';
// var_dump($queryArray);
// echo '</pre>';
// echo "current rank is ".$current_rank;
$current_rank = $current_rank - 1;
$row2 = array_splice($queryArray, $current_rank);
$row1 = array_splice($queryArray, 0, $current_rank );
// print_r($queryArray);
$merged = array_merge($row2, $row1);
$query->posts = $merged;
// echo '<pre>';
// var_dump($query->posts);
// echo '</pre>';


if ($query->have_posts() && $parent > 0): 
    echo '<div id="'.esc_attr($id).'" class="'.esc_attr($className).'">';
    echo '<ul class="list-carousel">';
    while ($query->have_posts()): $query->the_post();
    $ID = get_the_id();
    $rank = get_field('bp_rank', $ID);
    $title = get_the_title();
    $meta = get_post_meta( $ID, 'bp_rank'); ?>
    <!-- // print_r($meta); -->
    <!-- //echo '<li><a href="'.get_the_permalink( ).'">'.get_the_title().'  '.$rank.'</a></li>'; -->
    <!-- // echo get_the_post_thumbnail( $ID); -->
    <li class="lc-slide">
    <a href="<?php echo get_the_permalink( ); ?>">
    <div class="lc-slide">
    <div class="lc-slide-inner">
    <div class="lc-slide-content">
    <div class="lc-slide-img" style="background-image: url('<?php echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>')">
    <p class="slide-count"><?php echo $rank; ?></p>
    </div>
    <h4 class="city-state"><?php echo $title; ?></h4>
    </div>
    
    </div>
    </div>
    </a>
    </li>

<?php endwhile;
echo '</ul></div>';
endif;
wp_reset_postdata();
?>


