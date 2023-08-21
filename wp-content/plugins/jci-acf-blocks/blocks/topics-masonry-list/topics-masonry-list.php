<?php

/**
 * 2 Topics Masonry List Template
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

$topic = get_field('topic_category');
$featured_posts = get_field('featured_posts');

$counter = 1; 
$args = array(
    'post_type'         => 'post',
    'cat'               => $topic,
    'posts_per_page'    => 18,
    'post_status'       => 'publish',
    'paged'             => 1
);

// connected communities is global articles only
if (get_term($topic[0])->slug == 'connected-communities') {
    $args['meta_query'] = array(
        array(
            'key'       => 'global_article',
            'value'     => '1', 
            'compare'   => 'LIKE' 
        )
    );
}
$featured_array = [];
if ($featured_posts) {
    // create array of featured post ids
    foreach($featured_posts as $key => $value) {
        array_unshift($featured_array, $value['featured_post']);
    } 
    // filter them out of main query
    $args['post__not_in'] = $featured_array;
    // get post objects from that array
    $featured_post_objects = get_posts( array('post__in' => $featured_array) );
    // create main query
    $topic_query = new WP_Query( $args ); 
    // merge featured posts with main posts
    $topic_query->posts = array_merge($featured_post_objects,$topic_query->posts );
} else {
    $topic_query = new WP_Query( $args ); 
}?>

<?php if ($topic_query->have_posts() ):
$total_posts = $topic_query->post_count;
// print_r($topic);
    // print_r(get_term($topic[0]));
    // echo get_term($topic[0])->name; ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<?php $title = get_term($topic[0])->slug == 'connected-communities' ? 'More About Fiber & Connected Communities' : get_term($topic[0])->name.' Articles'; 
echo '<h2 class="green-line">'.$title.'</h2>'; ?>
<div class="masonry-container">
<?php while ($topic_query->have_posts() ): $topic_query->the_post();
$ID = get_the_ID(  );
switch ($counter) {
    case 1: $count_class = $total_posts == 1 ? 'fb-hundred' : '';
    case 4:
        $count_class = $total_posts == 1 ? 'fb-hundred' : 'fb-sixty';
        break;
    case 2: 
    case 3: 
        $count_class = $total_posts == 1 ? 'fb-hundred' : 'fb-forty';
        break;
    default:
        $count_class = '';
        break;
} ?>
    <?php if ($counter % 2 == 1) {echo '<div class="curated-posts-2">';} ?>
    <div class="cp <?php echo $count_class; ?>" style="background-image: url(<?php //echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>);" >
    <?php echo get_field('sponsored', get_the_ID()) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
    <?php echo get_the_post_thumbnail( $ID, 'rel_article' ); ?>
    <a href="<?php echo get_the_permalink(); ?>">
    <div class="cp-container">
    <h3><?php echo get_the_title(); ?></h3>
    </div>
    </a>
    </div><!-- cp -->
    <?php if ($counter % 2 == 0) {echo '</div>';} ?>
    <?php echo $counter === 6 ? '<InnerBlocks />' : ''; ?>
    <?php $counter < 6 ? $counter++ : $counter = 1; $total_posts--; ?>
    <?php endwhile; 
    // echo 'found posts: '.$topic_query->post_count;
    if ($topic_query->post_count < 18 && $topic_query->post_count % 2 == 1) {
        echo '</div>';
    }
     wp_reset_postdata(); ?>
    </div><!-- masonry-container -->
    <?php  if($topic_query->max_num_pages > 1): ?>
    <button class="more-articles load-masonry" data-topic-block="<?php echo $blockId; ?>">More Articles</button>

    <script>
    window['<?php echo $blockId; ?>'] = {};
    Object.assign(window['<?php echo $blockId; ?>'], {current_page: 2});
    Object.assign(window['<?php echo $blockId; ?>'], {categoryId: '<?php echo $topic[0]; ?>'});
    Object.assign(window['<?php echo $blockId; ?>'], {offset: '<?php echo count($featured_array); ?>'});
    </script>
    <?php endif; ?>
    
</div>
<?php endif; ?>
