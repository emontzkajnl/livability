<?php 
/**
 * Curated Four Column
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'c4l';
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'c4l';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} ?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php 
$count = 1;
// post_object_id, curated_posts
if (have_rows('curated_posts')):
    echo '<div class="bp2l__row">';
    while(have_rows('curated_posts')): the_row();
    $obj = get_sub_field('post_object_id'); ?>
    <div class="bp2l__bpl">
    <div class="bp2l__img-container">
    <a href="<?php echo get_the_permalink($obj); ?>"><?php echo get_the_post_thumbnail($obj, 'medium'); ?></a>
    </div>
    <h4 class="bp2l__bpl-title"><a class="unstyle-link" href="<?php echo get_the_permalink($obj); ?>"><?php echo get_the_title($obj); ?></a></h4>
</div>
    <?php if ($count == 8): ?>
        </div> <!-- bp2l__row -->
        <div class="c4l__load-more-container"><button class="c4l__load-more-btn">Load More</button></div>
        <div class="bp2l__row bp2l__hidden" style="display: none;">
    <?php endif;
    $count++;
    endwhile;
    echo '</div>'; // bp2l__row
endif;
?>
</div>