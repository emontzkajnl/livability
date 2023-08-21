<?php 
/**
 * Embedded connected community article
 * add to place related to article
 */

$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => 1,
    'post_status'       => 'publish',
    'category_name'     => 'connected-communities',
    'meta_query'        => array(
        array(
            'key'           => 'place_relationship',
            'value'         => '"' . get_the_ID() . '"',
            'compare'       => 'LIKE'
        ) 
    )
);
$embed_query = new WP_Query($args);
if ($embed_query->have_posts()):
    while ($embed_query->have_posts()): $embed_query->the_post(); ?>
    <!-- <div class="embedded-article"> -->
        <div class="one-hundred-list-container">
        <div class="ohl-thumb" style="background-image:url(<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium_large'); ?>);"></div>
         <div class="ohl-text">
             <h5 class="green-text uppercase">Connected Community</h5>
             <a href="<?php echo get_the_permalink(); ?>">
            <h4><?php echo get_the_title(); ?></h4>
            <p><?php echo get_the_excerpt(); ?></p>
            </a>
         </div>
        </div>

    <?php endwhile;
endif;
wp_reset_postdata();
?>

