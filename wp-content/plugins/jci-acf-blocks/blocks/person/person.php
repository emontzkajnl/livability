<?php 

/**
 * Person Block Template
 */

$blockId = $block['id'];
$id = 'person-' . $blockId;

$headshot = get_field('headshot');

$layout = get_field('layout');
$name = get_field('name');
$position = get_field('position');
$company = get_field('company');
$bio = get_field('bio');
$button = get_field('button');

?>

<div id="<?php echo $id; ?>" class="person-container <?php echo $layout; ?>">
<?php //var_dump($headshot); ?>
<?php echo wp_get_attachment_image($headshot['ID'], 'three_hundred_wide' ); ?>
<div class="person-info">
<?php echo '<p><span class="bold">'.$name.'</span><br/>';
if ($position) {echo $position.'<br/>';}
if ($company) {echo $company.'<br/>';}
echo '</p>';
if ($bio) {echo '<p class="person-bio">'.$bio.'</p>';}
if ($button) {
    echo '<button><a style="color: white;" href="'.esc_url($button['url']).'" target="'.esc_attr($button['target'] ? $button['target'] : '_self').'">'.$button['title'].'</a></button>';
}
?>
</div>

</div>