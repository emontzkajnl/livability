<?php 

// OLD CODE FOR MANUAL AJAX
if ($topics->have_posts()): ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<h2 class="green-line"><?php echo get_cat_name($cat[0]); ?></h2 >
<!-- <p><?php //echo $topics->max_num_pages; ?></p> -->
<ul>
<?php while ($topics->have_posts()): $topics->the_post(); ?>

<li>
<?php $id = acf_maybe_get_POST('post_id'); ?>

<div class="tp-container">
<img src="<?php echo get_the_post_thumbnail_url($id, 'three_hundred_wide'); ?>" alt=""> 
<div class="tp-text">
<a href="<?php  echo get_the_permalink( ); ?>">
<?php _e(the_title('<h3>','</h3>'), 'acf_blocks'); 

the_excerpt(); ?>
</a>
</div>
</div>

</li>
<?php endwhile; ?>

</ul>
<?php //print_r($topics->query_vars); ?>
<?php //if ($topics->max_num_pages > 1): ?>
<button class="more-articles" data-topic-block="<?php echo $blockId; ?>">More Articles</button>
</div>
<!-- Add global vars unique to this block using block id -->
<script>
window['<?php echo $blockId; ?>'] = {};
Object.assign(window['<?php echo $blockId; ?>'], {posts: '<?php echo json_encode($topics->query_vars ); ?>'});
Object.assign(window['<?php echo $blockId; ?>'], {current_page: '<?php echo $topics->query_vars["paged"]; ?>'});
Object.assign(window['<?php echo $blockId; ?>'], {max_page: '<?php echo $topics->max_num_pages; ?>'});
</script>
 <?php //endif;  
wp_reset_postdata(); ?>
<?php else: return; ?>
</div>
<?php endif; 