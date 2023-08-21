<?php

/**
 * Place with Three Posts Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'four-articles-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'place-with-rp';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// one place, three posts
$main_article = get_field('main_article');
$article_one = get_field('article_one');
$article_two = get_field('article_two');
$article_three = get_field('article_three');
$main_article_img = ($main_article->ID, 'large');
$article_array = array($article_one, $article_two, $article_three);
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="place-container" style="background-image: url(<?php echo $main_article_img; ?>);">
    
    <h3><a href="<?php echo the_permalink($main_article->ID); ?>"><?php echo get_the_title($main_article->ID); ?></a></h3>

    </div>
    <div class="pwrp-posts">
    <?php foreach($article_array as $article):?>
    <?php 
    $ID = $article->ID;
    $img = get_the_post_thumbnail_url($ID, "rel_article");
    ?>
    <div class="article-card">
        <div class="article-img" style="background-image: url(<?php echo $img; ?>);">
        <!-- <img src="<?php //echo get_the_post_thumbnail_url($ID, 'rank_card'); ?>" alt=""> -->
        </div>
        <div class="article-text">
        <a href="<?php echo the_permalink($ID); ?>">
            <?php echo '<h4>'.get_the_title( $ID ).'</h4>';  ?> 
        </a>
        </div>

    </div>
    <?php endforeach; ?>
    
    </div>
</div>

