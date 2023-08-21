<?php 

/**
 * Content Card Block Template
 * THIS IS DELETED!
 */

$id = 'card-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

$className = 'jci-content-card-container';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$block_post = get_field('post'); ?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<?php if (get_field('post')): ?>
  <p>The post title is <?php _e($block_post->post_title, 'acf-blocks'); ?></p>  
<?php else: ?>
<p>Select a post from sidebar.</p>
<?php endif; ?>

</div>

 
 