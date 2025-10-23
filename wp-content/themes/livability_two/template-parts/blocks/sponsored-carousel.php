<?php

// $id = 'plw-carousel-' . $block['id'];
// if( !empty($block['anchor']) ) {
//     $id = $block['anchor'];
// }
// $className = 'pwl-container';
// if( !empty($block['className']) ) {
//     $className .= ' ' . $block['className'];
// }
$ID = get_the_ID();
$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => 30,
    'post_status'       => 'publish',
    'orderby'           => 'rand',
    'meta_key'          => 'sponsored',
    'meta_value'        => true, 
);
$sponsored_query = new WP_Query($args);

?>

<?php if ($sponsored_query->have_posts()): ?>
<div class="pwl-container">
<h2 class="green-line">Sponsored Articles</h2>
<?php 

echo '<ul class="pwl-slick">';
while ($sponsored_query->have_posts()): $sponsored_query->the_post(); 
$slideId = get_the_ID();
$slidebkgrnd = get_the_post_thumbnail_url( $slideId, 'rel_article' );
//var_dump($slide);?> 
<li>
<a href="<?php echo get_the_permalink($slideId); ?>">
<div>
<div class="pwl-img" style="background-image: url(<?php echo $slidebkgrnd; ?>);"></div>
<!-- <img src="<?php //echo get_the_post_thumbnail_url($slide->ID, 'rel_article'); ?>" /> -->
<h4><?php echo get_the_title(); ?></h4>
</div>
</a>
</li>
<?php endwhile; 
echo '</ul></div>'; ?>
<?php endif; ?>