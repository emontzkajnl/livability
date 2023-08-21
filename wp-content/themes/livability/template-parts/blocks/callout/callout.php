<?php 
// Create id attribute allowing for custom "anchor" value.
$id = 'callout-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'callout';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$text = get_field('callout') ? : 'Add callout here...';
$title = get_field('title');

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<p><?php if ($title) {echo $title;} ?></p>
<p><?php echo $text;  ?></p>
<?php if (have_rows('repeater')): 
echo '<ul>';
while (have_rows('repeater')): the_row();
echo '<li>' . get_sub_field('repeat_text') . '</li>';
endwhile;
echo '</ul>';
endif; 
?>

</div>