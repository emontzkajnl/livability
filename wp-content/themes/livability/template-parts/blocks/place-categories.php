<?php  
if (get_post_type() == 'liv_place') {
    $meta_value = get_the_ID();
} elseif (get_post_type() == 'place_category_page') {
    $pr = get_field('place_relationship');
    $meta_value = $pr[0];
} else {
    $meta_value = null;
} 

$args = array(
    'post_type'         => 'place_category_page', 
    'post_status'       => array('publish', 'draft'),
    'meta_query'        => array(
        array( 
            'key'       => 'place_relationship',
            'value'     => $meta_value,
            'compare'   => 'LIKE'
        ),
    ),
    'posts_per_page'    => 20
);
$place_category_pages = new WP_Query($args);

if ($place_category_pages->have_posts()) {
    $catnavholder = array();
    $catorder = array('live', 'where-to-live', 'neighborhoods', 'homes-for-sale', 'work', 'jobs', 'job-opportunities', 'play', 'things-to-do', 'visit', 'basics','affordable-places-to-live', 'education-careers-opportunity', 'experiences-adventures', 'healthy-places', 'food-scenes', 'love-where-you-live', 'make-your-move', 'where-to-live-now' );

    while ($place_category_pages->have_posts()) {
        $place_category_pages->the_post();
        $cats = get_the_category();
        $cat = $cats[0]; 
       // print_r($cat);
       $catnavholder[$cat->slug] = '<a href="'.get_the_permalink().'">'.$cat->name.'</a>';
       
       ?>
        

    <?php } // end while
    $html = '<div class="category-nav">';
    $html .= '<a href="'.get_the_permalink($meta_value).'">'.get_the_title($meta_value).'</a>';
    foreach ($catorder as $co) {
        foreach ($catnavholder as $index => $cnh) {
            if ($co ==  $index) {
                $html .= $cnh;
                break;
            }
        }
    }
    $html .= '</div>';
    echo $html;

}
wp_reset_postdata(  );