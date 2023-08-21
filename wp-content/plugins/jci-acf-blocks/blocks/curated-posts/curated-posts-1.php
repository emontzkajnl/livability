<?php

/**
 * 1 Curated Post Template
 */

if ($is_preview && $block['data']['preview_img']) {
	echo '<img src="'.$block['data']['preview_img'].'" style="width: 100%; height: auto;"/>';
	return;
}

// Create id attribute allowing for custom "anchor" value.
$id = 'curated-posts-1-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'curated-posts-1';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$article_1 = get_field('curated_posts_1') ?: 'Curated Post';
// $test = get_post_meta(7 );

// $img1 = get_the_post_thumbnail_url($article_1->ID, "medium_large");
$cat1 = get_the_category( $article_1->ID );
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php //echo '<pre>block data <br />';
//   print_r($test); 
// print_r($block['data']);
//  echo '</pre>'; ?>
<div class="post-1-1 cp" style="background-image: url(<?php //echo $img1; ?>);">
<?php echo get_the_post_thumbnail( $article_1->ID ); ?>
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
</div>