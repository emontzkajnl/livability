<?php



$id = 'plw-carousel-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'pwl-container curated';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
$ID = get_the_ID();
$the_slides = get_field('slides');

// print_r($the_slides);
if ($the_slides): 
shuffle($the_slides);?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php echo '<ul class="pwl-slick">';
foreach($the_slides as $s){
$slide = $s['slide'];
$slideId = $slide->ID;
$slidebkgrnd = get_the_post_thumbnail_url( $slideId, 'rel_article' ); ?>
<li>
<a href="<?php echo get_the_permalink($slide->ID); ?>">
<div>
<div class="pwl-img" style="background-image: url(<?php echo $slidebkgrnd; ?>);"></div>

<h4><?php echo $slide->post_title; ?></h4>
</div> <!--pwl-img -->
</a>
</li>   
<?php }
echo '</ul></div>'; // pwl-container curated
endif;
?>


