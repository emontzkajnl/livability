<?php 
/**
 * Liv Place Section Heading
 */

// Create class attribute allowing for custom "className" and "align" values.
$className = 'place-section-heading';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$heading = get_field('heading');
$icon = get_field('icon');
?>
<div class="place-section-heading">
<h2 class="<?php echo esc_attr($icon); ?>"><?php echo $heading; ?></h2>
<span class="place-section-heading__line"></span>
</div>
