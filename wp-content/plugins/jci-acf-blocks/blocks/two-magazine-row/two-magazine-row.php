<?php

/**
 * Two Magazine Row Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'magazine-link-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = ' two-mag-row ';
if( !empty($block['className']) ) {
    $className .=  $block['className'];
} 

$magOne = get_field('magazine_one'); 
$magTwo = get_field('magazine_two'); 
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php foreach (array($magOne, $magTwo) as $magID) { 
    $src = get_the_post_thumbnail_url( $magID, 'three_hundred_wide'); 
    $sponsor = get_field('mag_sponsored_by_title', $magID);
    $sponsor_link = get_field('mag_sponsored_by_link', $magID); ?>
    <div class="two-mag-col">
    <div class="two-mag-float">
        
    
    <!-- <div class="two-mag-img"> -->
        <a class="two-mag-img" href="<?php echo  get_the_permalink($magID); ?>"><img src="<?php echo $src; ?>" /></a>
    <!-- </div> -->
    <div class="two-mag-text">
    
        <h2><a href="<?php echo  get_the_permalink($magID); ?>"><?php echo get_the_title($magID); ?></a></h2>
        <?php if ($sponsor){
            echo '<p>This digital edition of the <span class="italic">'.get_the_title($magID).'</span> is sponsored by <a href="'.$sponsor_link.'" target="_blank">'.$sponsor.'</a>.</p>';
        } ?>
        <button><a href="<?php echo  get_the_permalink($magID); ?>">Read the Magazine</a></button>
        </div><!--float-->
        <!-- <button><a href="<?php //echo  get_the_permalink($magID); ?>">Read the Magazine</a></button> -->
    </div>
    
    </div>
<?php } ?>

</div>