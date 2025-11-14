<?php  
if (get_post_type() == 'liv_place') {
    $meta_value = get_the_ID();
    $place_title = get_field('place_type') == 'city' ? substr(get_the_title($meta_value), 0,) : get_the_title($meta_value); 
} elseif (get_post_type() == 'place_category_page') {
    $pr = get_field('place_relationship');
    $meta_value = $pr[0];
    $place_title = get_field('place_type', $meta_value) == 'city' ? substr(get_the_title($meta_value), 0,) : get_the_title($meta_value); 
} else {
    $meta_value = null;
} 

// lop off last four characters to remove state from city pages


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
    $html = '<div class="category-nav-top">';
    $html .= '<div class="category-nav-menu">';
    $html .= '<img  src="'.get_stylesheet_directory_uri().'/assets/images/livability-icon.svg" style="height: 26px; padding: 0 24px; margin-top: -7px; display: inline;"/>';
    $html .= '<a href="'.get_the_permalink($meta_value).'">'.$place_title.'</a>';
    foreach ($catorder as $co) {
        foreach ($catnavholder as $index => $cnh) {
            if ($co ==  $index) {
                $html .= $cnh;
                break;
            }
        }
    }
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<style>
         .site-content {padding-top:0px;}
         .single-place_category_page .site-content {padding-top:25px;}
         .category-nav {display:none;}
         .category-nav-top {width:100%; background:#eee; position: sticky; top: 0; z-index:9;}
         .category-nav-menu {max-width:1360px; margin:0 auto; padding:12px 0px; text-align:center;}
         .category-nav-menu a {padding:12px 16px; color:#111;}
         .site-header.headroom--unpinned {height:0;}
         .site-header {position:relative;}
         </style>';
    echo $html;

}
wp_reset_postdata(  );