<?php

/**
 * Magazine Link Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'magazine-articles-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'mag-article';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

if (have_rows('magazine_articles')): ?>
<h2 class="green-line">In This Issue</h2>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php while (have_rows('magazine_articles')): ?>
    <?php endwhile; ?>
    </div>
<?php endif; ?>

