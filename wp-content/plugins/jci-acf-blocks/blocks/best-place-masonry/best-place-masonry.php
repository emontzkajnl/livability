<?php

/**
 * Best Place Masonry Template
 */

// Create id attribute allowing for custom "anchor" value.
$blockId = $block['id'];
$id = 'bpm-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'bpm';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$args = array(
    'post_type'         => 'best_places',
    'posts_per_page'    => 18,
    'post_status'       => 'publish',
    'post_parent'       => 0,
    'paged'             => 1, 
    // 'post__not_in'      => array(159696, 150065)

);
$counter = 1;
$bpm_query = new WP_Query( $args);
if ($bpm_query->have_posts()): ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="masonry-container">
    <?php while ($bpm_query->have_posts()): $bpm_query->the_post(); 
    switch ($counter) {
        case 1: 
        case 4:
            $count_class = 'fb-sixty';
            break;
        case 2: 
        case 3: 
            $count_class = 'fb-forty';
            break;
        default:
            $count_class = '';
            break;
    } ?>
    <?php if ($counter % 2 == 1) {echo '<div class="curated-posts-2">';} ?>
    <div class="cp <?php echo $count_class; ?>" style="background-image: url(<?php //echo get_the_post_thumbnail_url('medium_large'); ?>);" >
    <?php echo get_the_post_thumbnail( get_the_ID(), 'medium_large' ); ?>
    <a href="<?php echo get_the_permalink(); ?>">
    <div class="cp-container">
    <h3><?php echo get_the_title(); ?></h3>
    </div>
    </a>
    </div><!-- cp -->
    <?php if ($counter % 2 == 0) {echo '</div>';} ?>
    <?php echo $counter === 6 ? '<InnerBlocks />' : ''; ?>
    <?php $counter < 6 ? $counter++ : $counter = 1; ?>
    <?php endwhile; ?>
    </div><!-- masonry-container -->
    <?php  if($bpm_query->max_num_pages > 1): ?>
    <button class="more-articles centered load-bpm" data-bpm-block="<?php echo $blockId; ?>">More Articles</button>

    <script>
    window['<?php echo $blockId; ?>'] = {};
    Object.assign(window['<?php echo $blockId; ?>'], {current_page: 2});
    </script>
    <?php endif; ?>

    </div>
<?php endif; ?>