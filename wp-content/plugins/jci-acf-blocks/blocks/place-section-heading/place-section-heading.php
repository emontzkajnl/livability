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
$hide = get_field('hide_in_menu');
// $link = get_field('menu_link');
//echo 'label is '.$link['label'];
// $link_id = $link_title = '';
// $link = get_field('menu_link') ? 'id="'.get_field('menu_link').'"' : ''; 
if ($icon) {
    $icon_class = $icon['value'].' lpsh';
    // $link_id = 'id="'.$link['value'].'"';
    // $link_title = 'data-title="'.$link['label'].'"';
}
$link_id = $hide ? '' : 'id="'.$icon['value'].'"';
$link_title = $hide ? '' : 'data-title="'.$icon['label'].'"';
// echo 'icon label is '.$icon['label'].' and id is '.$link_id;
// echo $show ? '<br />show is true' : '<br />show is false';
?>
<div class="place-section-heading">
<?php echo '<h2 class="'.esc_attr($icon_class).'" '.$link_id.' '.$link_title.'>'.$heading.'</h2>'; ?>
<span class="place-section-heading__line"></span>
</div>
