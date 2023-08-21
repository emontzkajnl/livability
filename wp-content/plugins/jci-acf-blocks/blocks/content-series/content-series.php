<?php

/**
 * Content Series Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'content-series-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'place-with-rp';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$content_series = get_field('content_series');


$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => 4,
    'post_status'       => 'publish',
    'order'             => 'DESC',
    'orderby'           => 'date',
    'tax_query'         => array(
        array( 
            'taxonomy'  => 'content_series',
            'field'     => 'id',
            'terms'     => $content_series
        )
    ),
);

$content_query = new WP_Query( $args );
$posts = $content_query->posts;
if (count($posts) > 1):
$main_article = $posts[0];
$article_array = array_slice($posts, 1);
$term_obj = get_term($content_series);

$main_article_img = get_the_post_thumbnail_url($main_article->ID, 'medium_large');
?>
<h2 class="wp-block-jci-blocks-section-header green-line"><?php echo $term_obj->name; ?></h2>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    
    <div class="place-container" style="background-image: url(<?php  echo $main_article_img; ?>);">
    <a href="<?php echo the_permalink($main_article->ID); ?>">
    <?php echo get_the_post_thumbnail( $main_article->ID, 'medium_large' ); ?>
    <div class="cp-container"><h3><?php echo get_the_title($main_article->ID); ?></h3></div>
    </a>
    </div>
    <div class="pwrp-posts">
    <?php foreach($article_array as $article):?>
    <?php
    $ID = $article->ID;
    $img = get_the_post_thumbnail_url($ID, "medium");
    ?>
    <div class="article-card">
        <a  class="article-img" href="<?php echo the_permalink($ID); ?>">
        <!-- <div style="background-image: url(<?php //echo $img; ?>);"></div> -->
        <?php echo get_the_post_thumbnail( $ID, 'medium' ); ?>
        </a>
        <div class="article-text">
        <a href="<?php echo the_permalink($ID); ?>">
            <?php echo '<h4>'.get_the_title( $ID ).'</h4>';  ?> 
        </a>
        </div>

    </div>
    <?php endforeach; ?>
    
    </div> 
</div>
<?php endif; ?>

