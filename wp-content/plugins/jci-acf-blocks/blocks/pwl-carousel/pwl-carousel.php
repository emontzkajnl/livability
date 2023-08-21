<?php

if ($is_preview && $block['data']['preview_img']) {
	echo '<img src="'.$block['data']['preview_img'].'" style="width: 100%; height: auto;"/>';
}

$id = 'plw-carousel-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'pwl-container';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
$ID = get_the_ID();
$the_slides = get_field('slides');

// print_r($the_slides);
if ($the_slides): 
shuffle($the_slides);?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php echo is_front_page() || is_page('homepage-output') ? '<h2 class="green-line">Places We Love</h2>' : '<h2 class="green-line">Places We Love In '. get_the_title($ID).'</h2>';
echo '<ul class="pwl-slick">';
foreach($the_slides as $s){
$slide = $s['slide'];
$slideId = $slide->ID;
$slidebkgrnd = get_the_post_thumbnail_url( $slideId, 'rel_article' ); ?>
<li>
<a href="<?php echo get_the_permalink($slide->ID); ?>">
<div>
<div class="pwl-img" style="background-image: url(<?php echo $slidebkgrnd; ?>);"></div>

<h3><?php echo $slide->post_title; ?></h3>
</div>
</a>
</li>   
<?php }
echo '</ul></div>'; 
endif;
?>


