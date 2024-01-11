<?php 
/**
 * Ultimate Guide Link
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'ug2fyptl-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'ug2fyptl';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$summary = get_field('summary');
$img = get_field('ultimate_guide_image');
$link = get_field('ultimate_guide_link');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="ug2fyptl__img-container" style="background-image: url(<?php echo wp_get_attachment_image_url( $img['ID'], 'medium_large' );  ?>);">
    <?php //echo wp_get_attachment_image( $img['ID'], 'medium_large'); ?>
    </div>
    <div class="ug2fyptl__text-container">
    <h3>The Ultimate Guide To Finding Your Best Place To Live</h3>
    <?php echo '<p class="ug2fyptl__text">'.$summary.'</p>'; ?>
    <a href="<?php  echo $link; ?>"><button>READ GUIDE</button></a>
    </div>
</div>