<?php 
$args = array(
    'posts_per_page'        => 9,
    'post_status'           => 'publish',
    'post_type'             => 'post',
);
$rp_query = new WP_Query($args);
if ($rp_query->have_posts()): ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
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
    echo '</div></div>';
endif;