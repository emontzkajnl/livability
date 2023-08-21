<?php

/**
 * 3 Curated Post Template
 */

if ($is_preview && $block['data']['preview_img']) {
	echo '<img src="'.$block['data']['preview_img'].'" style="width: 100%; height: auto;"/>';
	return;
}

// Create id attribute allowing for custom "anchor" value.
$id = 'curated-posts-3-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'curated-posts-3';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$article_1 = get_field('curated_posts_3_1') ?: 'Curated Post';
$article_2 = get_field('curated_posts_3_2') ?: 'Curated Post';
$article_3 = get_field('curated_posts_3_3') ?: 'Curated Post';
// $img1 = get_the_post_thumbnail_url($article_1->ID, "medium_large");
// $img2 = get_the_post_thumbnail_url($article_2->ID, "medium_large");
// $img3 = get_the_post_thumbnail_url($article_3->ID, "medium_large");
$cat1 = get_the_category( $article_1->ID );
$cat2 = get_the_category( $article_2->ID );
$cat3 = get_the_category( $article_3->ID );
// print_r($cat1);
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="post-3-1 cp" style="background-image: url(<?php //echo $img1; ?>);">
	<?php echo get_the_post_thumbnail( $article_1->ID , 'medium_large' ); ?>
	<a href="<?php echo get_the_permalink( $article_1->ID); ?>">
	<div class="cp-container">
	<?php echo get_field('sponsored', $article_1->ID) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
	<?php if ($cat1): ?>
		<h5 class="green-text uppercase"><?php echo $cat1[0]->name; ?></h5>
		<?php endif; ?>
    	<h3 class="post-1-title"><?php echo $article_1->post_title; ?></h3>
		<!-- <p><?php //echo get_the_excerpt( $article_1->ID ); ?></p> -->
		</div>
		</a>
    </div>
    <div class="post-3-2 cp" style="background-image: url(<?php //echo $img2; ?>);">
	<?php echo get_the_post_thumbnail( $article_2->ID , 'medium_large' ); ?>
	<a href="<?php echo get_the_permalink( $article_2->ID); ?>">
	<div class="cp-container">
	<?php echo get_field('sponsored', $article_2->ID) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
		<?php if ($cat2): ?>
		<h5 class="green-text uppercase"><?php echo $cat2[0]->name; ?></h5>
		<?php endif; ?>
    	<h3 class="post-1-title"><?php echo $article_2->post_title; ?></h3>
		<!-- <p><?php //echo get_the_excerpt( $article_2->ID ); ?></p> -->
		</div>
		</a>
    </div>
    <div class="post-3-3 cp" style="background-image: url(<?php //echo $img3; ?>);">
	<?php echo get_the_post_thumbnail( $article_3->ID , 'medium_large' ); ?>
	<a href="<?php echo get_the_permalink( $article_3->ID); ?>">
	<div class="cp-container">
	<?php echo get_field('sponsored', $article_3->ID) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
	<?php if ($cat3): ?>
	<h5 class="green-text uppercase"><?php echo $cat3[0]->name; ?></h5>
	<?php endif; ?>
    	<h3 class="post-1-title"><?php echo $article_3->post_title; ?></h3>
		<!-- <p><?php //echo get_the_excerpt( $article_3->ID ); ?></p> -->
		</div>
		</a>
    </div>
</div>