<?php 

/**
 * ACF Info Box Template
 */


 // Create id attribute allowing for custom "anchor" value.
 $blockId = $block['id'];
$id = 'acf-info-box-' . $blockId;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'wp-block-jci-blocks-info-box';

if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
$icon = get_field('icon');
$quote = get_field('quote');
$name = get_field('name');
$position = get_field('position');

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className).' '.$icon; ?>">
<div class="info-box-quote">
    <?php echo $quote; ?>
</div>
<p class="info-box-name">
    <?php echo $name; ?>
</p>
<div class="info-box-position">
    <?php echo $position; ?>
</div>
<?php
    if (get_field('add_button')) {
        echo '<a class="info-box-button" href="'.get_field('button_link').'">'.get_field('button_text').'</a>';
    }
?>

</div>
