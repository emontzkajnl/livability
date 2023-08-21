<?php

/**
 * Full Width Magazine Link Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'magazine-link-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = ' full-width-mag ';
if( !empty($block['className']) ) {
    $className .=  $block['className'];
} 

$magID = get_field('magazine_link'); 
$src = get_the_post_thumbnail_url( $magID, 'three_hundred_wide');
$sponsor = get_field('mag_sponsored_by_title', $magID);
$sponsor_link = get_field('mag_sponsored_by_link', $magID); 
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="fw-mag-img"><a href="<?php echo  get_the_permalink($magID); ?>"><img src="<?php echo $src; ?>" /></a></div>
<div class="fw-mag-text">
    
    <h2><a href="<?php echo  get_the_permalink($magID); ?>"><?php echo get_the_title($magID); ?></a></h2>
    <?php if ($sponsor){
        echo '<p>This digital edition of the <span class="italic">'.get_the_title($magID).'</span> is sponsored by <a href="'.$sponsor_link.'" target="_blank">'.$sponsor.'</a>.</p>';
    } ?>
    <button><a href="<?php echo  get_the_permalink($magID); ?>">Read the Magazine</a></button>
</div>

</div>