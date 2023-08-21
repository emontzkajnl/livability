<?php
$id = 'related-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'related-post-list';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
$list_title = get_field('optional_title');
?>
<?php if (have_rows('post_list')): ?>
<div class="more-like-this-block">
<div>
<p><?php echo $list_title ? $list_title : "More Like This"; ?></p>

<ul>
<?php while (have_rows('post_list')): the_row(); 
$this_post = get_sub_field('post');?>
    <li><a href="<?php echo get_the_permalink( $this_post->ID ); ?>"><?php echo $this_post->post_title; ?></a></li>
    <?php endwhile; ?>
</ul>
<?php wp_reset_postdata( ); ?>
</div>
</div>
<?php endif; ?>