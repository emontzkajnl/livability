<?php 

// Create id attribute allowing for custom "anchor" value.
// $id = 'recent-posts';
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$args = array(
    'posts_per_page'        => 6,
    'post_status'           => 'publish',
    'post_type'             => 'post',
);
$rp_query = new WP_Query($args);
if ($rp_query->have_posts()): 
// Create class attribute allowing for custom "className" and "align" values.
$className = 'recent-posts';
?>
<div id="Recent-Posts" class="<?php echo esc_attr($className); ?>"> ?>
<h2 class="green-line">Recent Posts</h2>
<div class="recent-posts-container">
<?php while ($rp_query->have_posts()):
         $rp_query->the_post(); 
         $ID = get_the_ID(); ?>
    <div class="article-card">
        <a href="<?php echo get_the_permalink( $ID); ?>" class="article-img">
            <?php echo get_the_post_thumbnail( $ID, 'medium' ); ?>
        </a>
        <div class="article-text">
        <a href="<?php echo the_permalink($ID); ?>">
            <?php echo '<h4>'.get_the_title( $ID ).'</h4>';  ?>
        </a>
        </div>
    </div>
    <?php endwhile;
    echo do_shortcode('[ajax_load_more id="test" offset="6" container_type="div" loading_style="green" container_type="div" repeater="template_4" container_type="div" cache="true" post_type="post" posts_per_page="6" post_format="standard" pause="true" images_loaded="true"]');?>
</div>
</div>

<?php endif;