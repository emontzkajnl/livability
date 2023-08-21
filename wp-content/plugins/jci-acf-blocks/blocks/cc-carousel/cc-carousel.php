<?php
/**
 * connected communities carousel
 * used on state place that has cc. 
 * add all non-global articles of that state to carousel
 */

if ($is_preview && $block['data']['preview_img']) {
	echo '<img src="'.$block['data']['preview_img'].'" style="width: 100%; height: auto;"/>';
}

$id = 'cc-carousel-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'pwl-container cc-carousel-container';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// query to get connected communities by state
$args = array(
    'post_type'     => 'post',
    'posts_per_page'=> -1,
    'post_status'   => 'publish',
    'category_name' => 'connected-communities',
    'meta_query'    => array(
        array(
            'key'       => 'global_article',
            'value'     => '0',
            'compare'   => 'LIKE'
        )
    )
);

// The id is the place relationship of the article or the parent

$cc_query = new WP_Query($args);

//loop through articles to find place 
$cc_array = array();
$cc_posts = $cc_query->posts;
$ID = get_the_ID();
// print_r($cc_posts);
foreach ($cc_posts as $cc_post) {
    $place = get_post_meta($cc_post->ID, 'place_relationship', true);
    $s = wp_get_post_parent_id( $place[0] );
    if ($s == $ID  || $place[0] == $ID) {
        array_push($cc_array, $cc_post);
    }
} 
$cc_query->posts = $cc_array;
$cc_query->found_posts = count($cc_array);
$cc_query->post_count = count($cc_array);

?>

<?php if ($cc_query->have_posts(  )): ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php echo '<h2 style="margin-bottom: 50px;">'.get_the_title().' Connected Communities</h2>'; 

echo '<ul class="pwl-slick">';
while ($cc_query->have_posts()): $cc_query->the_post(); 
$ID = get_the_ID();
$proceed = false;
if (!$parent_place) {
    $proceed = true;
} else {
    $places = get_field('place_relationship', $ID);
    // print_r($places);
    foreach ((array)$places as $place) {
        $this_parent = wp_get_post_parent_id($place);
       if ($place == $parent_place || $place == $this_parent) {
           $proceed = true;
       }
    }
}
if ($proceed):
$slidebkgrnd = get_the_post_thumbnail_url( $ID, 'rel_article' ); ?> 
<li>
<a href="<?php echo get_the_permalink($ID); ?>">
<div>
<div class="pwl-img" style="background-image: url(<?php echo $slidebkgrnd; ?>);"></div>
<!-- <img src="<?php //echo get_the_post_thumbnail_url($slide->ID, 'rel_article'); ?>" /> -->
<h3><?php echo get_the_title(); ?></h3>
</div>
</a>
</li>

<?php endif; //proceed
 endwhile; 
echo '</ul></div>'; ?>
<?php endif; 
wp_reset_postdata(  ); ?>
