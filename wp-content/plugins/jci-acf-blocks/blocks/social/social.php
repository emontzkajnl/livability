<?php 

/**
 * Slider Block Template
 */


 // Create id attribute allowing for custom "anchor" value.
$id = 'social-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'social-sidebar-icons';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
} 

?>

<!-- <p>I'm echoing a social block just to prove it doesn't require any ACF. </p> -->
<?php //echo var_export($GLOBALS['post'], TRUE); 
// echo get_page_template(); ?>