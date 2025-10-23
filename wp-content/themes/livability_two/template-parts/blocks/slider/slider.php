<?php 

/**
 * Slider Block Template
 * 
 * 
 */


// Create id attribute allowing for custom "anchor" value.
$id = 'slider-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'myslider';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
// if (!empty($block['placeholder'])) {
//     error_log('had placeholder');
//     $block['placeholder'] = 'here is a placeholder';
// }

// error_log(print_r($block['placeholder'], true));
$placeholder = 'add a slider here';
// $text = get_field('text2');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" style="min-height: 50px;">
<!-- <p><?php //echo $text ? $text : 'Please enter text...'; ?></p> -->
<?php if (have_rows('sliders2')): ?>
<ul class="sliders">
<?php while(have_rows('sliders2')): the_row(); 
$img = get_sub_field('slider2'); 
$sub = get_sub_field('subtitle'); 
$cs = get_sub_field('city_state'); 
$credit = get_sub_field('photo_credit'); ?>

<li>
    <div class="slide-container">
        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
        <div class="text-area">
            <div class="center-text">
                <?php echo $sub ? '<h2 class="subtitle">'.$sub.'</h2>' : ''; ?>
                <?php echo $cs ? '<p class="city-state">'.$cs.'</p>' : ''; ?>
            </div>
            <?php echo $credit ? '<p class="credit">Photo Credit: '.$credit.'</p>' : ''; ?>

        </div>
    </div>
</li>
<?php endwhile; ?>
</ul>
<?php else: echo '<p>Enter slider here...</p>' ?>
<?php endif; ?>
</div><!--slider-container--> 
