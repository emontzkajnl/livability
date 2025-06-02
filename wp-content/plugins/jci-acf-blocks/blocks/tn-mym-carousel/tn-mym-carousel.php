<?php 
$id = 'tn-mym-carousel-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'pwl-container curated';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
$ID = get_the_ID();

$args = array(
    'post_type'         => 'post',
    'category_name'     => 'make-your-move',
    // 'meta_query'        => array(
    //     array(
    //         'key'           => 'place_relationship',
    //         'value'         => '274',
    //         'compare'       => 'LIKE'
    //     ) 
    //     ),
    'posts_per_page'    => -1,
    'orderby'           => 'rand',
    'post_status'       => 'publish',
);

$tnmym_query = new WP_Query($args);
echo 'tn block';
if ($tnmym_query->have_posts()):
    while ($tnmym_query->have_posts()): $tnmym_query->the_post();
    echo get_the_title().'<br />'; 
    endwhile;
endif;

