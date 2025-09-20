<?php 
$args = array(
    'post_type'         => 'place_category_page', 
    'post_status'       => 'publish',
    // 'meta_key'          => 'place_relationship',
    // 'meta_value'        => get_the_ID(),
    'meta_query'        => array(
        array( 
            'key'       => 'place_relationship',
            'value'     => '"' . get_the_ID() . '"',
            'compare'   => 'LIKE'
        ),
    ),
    'posts_per_page'    => 20
);
$place_category_pages = new WP_Query($args);
echo 'place category '.get_the_ID();
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