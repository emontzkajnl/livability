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
    // 'meta_key'          => 'place_relationship',
    // 'meta_value'        => get_the_ID(),
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
    echo '<div class="category-nav">';
    while ($place_category_pages->have_posts()) {
        $place_category_pages->the_post();
        $cats = get_the_category();
        $cat = $cats[0]; ?>
        <a href="<?php echo get_the_permalink(); ?>"><?php echo $cat->name; ?></a>

    <?php }
    echo '</div>';
}
wp_reset_postdata(  );