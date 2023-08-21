<?php

/**
 * Place with Three Posts Template
 */

if ($is_preview && $block['data']['preview_img']) {
	echo '<img src="'.$block['data']['preview_img'].'" style="width: 100%; height: auto;"/>';
    return;
}

// Create id attribute allowing for custom "anchor" value.
$id = 'place-with-rp-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'place-with-rp';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// one place, three posts
$place = get_field('place');
$article_one = get_field('article_one');
$article_two = get_field('article_two');
$article_three = get_field('article_three');
$place_img = get_the_post_thumbnail_url($place->ID, 'medium_large');
$article_array = array($article_one, $article_two, $article_three);
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="place-container" style="background-image: url(<?php //echo $place_img; ?>);">
    
    <a href="<?php echo the_permalink($place->ID); ?>">
    <?php echo get_the_post_thumbnail( $place->ID); ?>
    <div class="cp-container">
    <h3><?php echo get_the_title($place->ID); ?></h3>
    </div>
    </a>
    </div>
    <div class="pwrp-posts">
    <?php foreach($article_array as $article):?>
    <?php 
    $ID = $article->ID;
    // $img = get_the_post_thumbnail_url($ID, 'three_hundred_wide');
    $cat = get_the_category( $ID );    
    // print_r($cat);
    ?>
    <div class="article-card">
        <a href="<?php echo the_permalink($ID); ?>" class="article-img" >
        <?php echo get_field('sponsored', $ID) ? '<p class="sponsored-label small">Sponsored</p>' : ""; ?>
        <!-- <div style="background-image: url(<?php //echo $img; ?>);"></div> -->
        <?php echo get_the_post_thumbnail( $ID, 'medium' ); ?>
        </a>
        <div class="article-text">
        <!-- <h5 class="green-text uppercase"><?php //echo $cat[0]->name ?></h5> -->
        <a href="<?php echo the_permalink($ID); ?>">
            <?php echo '<h4>'.get_the_title( $ID ).'</h4>';  ?>
            <!-- <p class="read-more">Read More</p> -->
        </a>
        </div>

    </div>
    <?php endforeach; ?>
    
    </div>
</div>

