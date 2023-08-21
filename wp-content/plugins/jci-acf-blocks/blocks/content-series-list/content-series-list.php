<?php

/**
 * Content Series List Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'content-series-list' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = ' ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$content_series = get_field('content_series');
$article_order = get_field('article_order');


$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => -1,
    'post_status'       => 'publish',
    'order'             => $article_order,
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
// print_r($content_query);

if ($content_query->have_posts()):
    echo '<h2 class="green-line">'.get_term( $content_series[0] )->name.'</h2>';
    echo '<ul class="onehundred-container">';
    while ($content_query->have_posts()): $content_query->the_post(); 
    $ID = get_the_ID(); ?>
    <li class="one-hundred-list-item">
        <div class="one-hundred-list-container">
            <a class="ohl-thumb" href="<?php echo get_the_permalink(); ?>" >
            <div style="background-image: url('<?php echo get_the_post_thumbnail_url($ID, 'three_hundred_wide'); ?>')">
            </div>
            </a>
            <div class="ohl-text">
                <a href="<?php echo get_the_permalink($ID); ?>">
                <h2><?php echo get_the_title(); ?></h2>
                <p><?php echo get_the_excerpt(  ); ?></p>
                </a>
                
            </div>
        </div>
    </li>
    
<?php 
// $term_obj = get_term($content_series);
// print_r($term_obj);
    endwhile;
    echo '</ul>';
endif; 
wp_reset_postdata(  );
?>