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
$link = get_field('menu_link');
//echo 'label is '.$link['label'];
$link_id = $link_title = '';
// $link = get_field('menu_link') ? 'id="'.get_field('menu_link').'"' : ''; 
if ($link) {
    $icon .= ' lpsh';
    $link_id = 'id="'.$link['value'].'"';
    $link_title = 'data-title="'.$link['label'].'"';
}

?>
<div class="place-section-heading">
<?php echo '<h2 class="'.esc_attr($icon).'" '.$link_id.' '.$link_title.'>'.$heading.'</h2>'; ?>
<span class="place-section-heading__line"></span>
</div>
