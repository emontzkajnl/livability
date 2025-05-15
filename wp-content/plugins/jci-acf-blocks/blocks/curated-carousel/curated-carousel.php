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

$c_or_t = get_field('curated_or_tag');
if ($c_or_t == 'curated' ) {
    $the_slides = get_field('slides');
} else {
    $term = get_field('taxonomy');
    $args = array(
        'post_type'		=> 'post',
        'post_status'	=> 'publish', 
        'tag_id'        => $term[0]
    );
    $the_slides = get_posts( $args );
}


// print_r($the_slides);
if ($the_slides): 
shuffle($the_slides);
if (get_the_ID() == '274') {
    echo '<p class="brand-stories__sponsor-text" style="margin-top: -30px !important;">Sponsored by <a href="https://www.fbitn.com/" target="_blank">Farm Bureau Insurance of Tennessee</a></p>';
}
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<?php 
echo '<ul class="pwl-slick">';
foreach($the_slides as $s){
$c_or_t == 'curated' ? $slide = $s['slide'] : $slide = $s ;
$slideId = $slide->ID;
$slidebkgrnd = get_the_post_thumbnail_url( $slideId, 'rel_article' ); ?>
<li>
<a href="<?php echo get_the_permalink($slideId); ?>">
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


