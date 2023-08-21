<?php 
/**
 * Connected Community call to action, for non-client cities
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
$cc_query = new WP_Query($args);
$city_ID = get_the_ID(  );
if ($cc_query->have_posts()):
    while ($cc_query->have_posts()): $cc_query->the_post(); ?>
    <div class="cc-cta-container">
    <div class="cc-cta__left-section">
        <p><?php echo get_the_title($city_ID); ?> is a certified <a href="<?php echo get_site_url(); ?>/connected-communities">Connected Community</a>
         with high-speed fiber internet service providers. <a href="<?php echo get_the_permalink( get_the_ID() ); ?>">Read more.</a></p>
    </div>
    <div class="cc-cta__right-section">
        <img src="<?php // echo get_stylesheet_directory(  ); ?>/wp-content/themes/livability/assets/images/cc-icon-w-bar.svg" alt="">
    </div>
    </div>
    <?php endwhile;
endif;
wp_reset_postdata();