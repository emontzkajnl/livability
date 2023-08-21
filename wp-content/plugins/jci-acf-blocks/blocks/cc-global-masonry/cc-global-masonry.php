<?php

/**
 * Connected Communities Global Masonry
 */

 // Create id attribute allowing for custom "anchor" value.
 $blockId = $block['id'];
$id = 'topics-masonry-' . $blockId;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$className = 'topics-masonry';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}


// $counter = 1; 
$args = array(
    'post_type'         => 'post',
    'category_name'     => 'connected-communities',
    'posts_per_page'    => 10,
    'post_status'       => 'publish',
    // 'meta_query'        => array(
    //     array(
    //         'key'       => 'global_article',
    //         'value'     => '1',
    //         'compare'   => 'LIKE'
    //     )
    // )
);

    $topic_query = new WP_Query( $args ); ?>
<?php if ($topic_query->have_posts() ): ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<?php echo '<h2 class="green-line">Connected Communities Articles</h2>'; ?>
<div class="masonry-container">
<?php while ($topic_query->have_posts() ): $topic_query->the_post();
$ID = get_the_ID(  );
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
    <div class="cp <?php echo $count_class; ?>" style="background-image: url(<?php echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>);" >
    <?php //echo get_field('sponsored', get_the_ID()) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
    <?php echo get_the_post_thumbnail( $ID, 'rel_article' ); ?>
    <a href="<?php echo get_the_permalink(); ?>">
    <div class="cp-container">
    <h3><?php echo get_the_title(); ?></h3>
    </div>
    </a>
    </div><!-- cp -->
    <?php if ($counter % 2 == 0) {echo '</div>';} ?>
    <?php echo $counter === 6 ? '<InnerBlocks />' : ''; ?>
    <?php $counter < 6 ? $counter++ : $counter = 1; ?>
    <?php endwhile; 
     wp_reset_postdata(); ?>
    </div><!-- masonry-container -->
    <?php endif; ?>
    
</div>
<?php //endif; ?>
