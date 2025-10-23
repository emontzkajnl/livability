 <?php
//  experiences-adventures food-scenes healthy-places live-here-work-here make-your-move where-to-live-now
 $category = $args['category'];
 $catObj = get_category_by_slug($category);
 $catName = $catObj->name;
//  $blockId = get_the_id();
$blockId = 'topicId_'.$category.get_the_id();



 $topic_args = array(
     'category_name'    => $category,
     'posts_per_page'    => 3,
     'post_status'       => 'publish',
     'post_type'         => 'post',
     'meta_query'        => array(
         array( 
             'key'       => 'place_relationship',
             'value'     => '"' . get_the_ID() . '"',
             'compare'   => 'LIKE'
         ),
         array(
            'key'       => 'sponsored',
            'value'     => 0
        ),
        'relation'      => 'AND'
     ),
     'paged'            => 1
    );
    $topics = new WP_Query( $topic_args );
    if ($topics->have_posts()): ?>
    <div class="place-topics-container">
        <h2 class="green-line"><?php echo $catName; ?></h2><ul>
    <?php while ($topics->have_posts()): $topics->the_post(); ?>
    <li class="one-hundred-list-container">
    <a href="<?php echo get_the_permalink( ); ?>" class="ohl-thumb" style="background-image: url(<?php echo the_post_thumbnail_url(); ?>);"></a>
    <div class="ohl-text">
    <a href="<?php  echo get_the_permalink( ); ?>">
    <?php _e(the_title('<h2>','</h2>'), 'acf_blocks'); 
    the_excerpt();
 ?>
</a>
    </div>
    </li>
    <?php endwhile; 
    wp_reset_postdata(); ?>
    </ul>
    <?php if ($topics->max_num_pages > 1): ?>
    <button class="more-articles load-places" data-topic-block="<?php echo $blockId; ?>">More Articles</button>
    <!-- Add global vars unique to this block using block id -->
    <script>
    window['<?php echo $blockId; ?>'] = {};
    Object.assign(window['<?php echo $blockId; ?>'], {current_page: '<?php echo $topics->query_vars["paged"]; ?>'});
    Object.assign(window['<?php echo $blockId; ?>'], {category: '<?php echo $category; ?>'});
    Object.assign(window['<?php echo $blockId; ?>'], {relationship_id: '<?php echo '"' . get_the_ID() . '"'; ?>'});
    Object.assign(window['<?php echo $blockId; ?>'], {max_page: '<?php echo $topics->max_num_pages; ?>'});
    </script>
     <?php endif; ?>
    </div>
    <?php endif; ?>
 