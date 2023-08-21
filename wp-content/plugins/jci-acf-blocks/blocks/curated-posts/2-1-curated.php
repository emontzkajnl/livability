<?php

/**
 * 2 And 1 Curated Post Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'curated-posts-2-1' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// $tall_on_left = get_field('tall_on_left') ? 'tall-on-left' : '';
if (get_field('tall_on_left')) {
    $tall_on_left = 'tall-on-left';
} else {
    $tall_on_left = '';
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'curated-posts-2-1' . ' ' . $tall_on_left;
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$tall_article = get_field('tall_post') ?: 'Curated Post';
$upper_article = get_field('upper_post') ?: 'Curated Post';
$lower_article = get_field('lower_post') ?: 'Curated Post';
$tall_img = get_the_post_thumbnail_url($tall_article ->ID, "medium_large");
$upper_img = get_the_post_thumbnail_url($upper_article ->ID, "rel_article");
$lower_img = get_the_post_thumbnail_url($lower_article ->ID, "rel_article");
$tall_cat = get_the_category( $tall_article->ID );
$upper_cat = get_the_category( $upper_article->ID );
$lower_cat = get_the_category( $lower_article->ID );
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="cp tall-cp" style="background-image: url(<?php echo $tall_img; ?>);">
        
        <a href="<?php echo get_the_permalink( $tall_article->ID); ?>">
        <div class="cp-container">
        <?php echo get_field('sponsored', $tall_article->ID) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
        <h5 class="green-text uppercase"><?php echo $tall_cat[0]->name; ?></h5>
        <h3 class="post-1-title"><?php echo $tall_article->post_title; ?></h3>
        <!-- <p><?php //echo get_the_excerpt( $tall_article->ID ); ?></p> -->
        </div>
        </a>
    </div>
    <div class="curated-flex-container">
        <div class="cp" style="background-image: url(<?php echo $upper_img; ?>);">
            <a href="<?php echo get_the_permalink( $upper_article->ID); ?>">
            <div class="cp-container">
            <?php echo get_field('sponsored', $upper_article->ID) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
            <h5 class="green-text uppercase"><?php echo $upper_cat[0]->name; ?></h5>
            <h3 class="post-1-title"><?php echo $upper_article->post_title; ?></h3>
            <!-- <p><?php //echo get_the_excerpt( $upper_article->ID ); ?></p> -->
            </div>
            </a>
        </div>
        <div class="cp" style="background-image: url(<?php echo $lower_img; ?>);">
            <a href="<?php echo get_the_permalink( $lower_article->ID); ?>">
            <div class="cp-container">
            <?php echo get_field('sponsored', $lower_article->ID) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
            <h5 class="green-text uppercase"><?php echo $lower_cat[0]->name; ?></h5>
            <h3 class="post-1-title"><?php echo $lower_article->post_title; ?></h3>
            <!-- <p><?php //echo get_the_excerpt( $lower_article->ID ); ?></p> -->
            </div>
            </a>
        </div>
    </div>
</div>