<?php

/**
 * Embedded Article Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'embedded-article-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'embedded-article';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$article = get_field('embedded_article'); 
$cat = get_the_category($article); ?>

<div class="one-hundred-list-container">
<div class="ohl-thumb" style="background-image:url(<?php echo get_the_post_thumbnail_url($article, 'medium_large'); ?>);">
<?php echo get_field('sponsored', $article) ? '<p class="sponsored-label small">Sponsored</p>' : ""; ?>
</div>
<div class="ohl-text">
<h5 class="green-text uppercase"><?php echo $cat[0]->name; ?></h5>
<a href="<?php echo get_the_permalink($article); ?>">
    <h4><?php echo get_the_title($article); ?></h4>
    <p><?php echo get_the_excerpt($article); ?></p>
</a>
</div>
</div>


