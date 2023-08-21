<?php
/**
 * Listicle Template
 */

$id = 'listicle-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'listicle';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <h1>Hello world. </h1>
</div>