<?php

/**
 * Category/Curated template
 */

if ($is_preview && $block['data']['preview_img']) {
	echo '<img src="'.$block['data']['preview_img'].'" style="width: 100%; height: auto;"/>';
    return;
}

// Create id attribute allowing for custom "anchor" value.
$id = 'category-curated-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'category-curated';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if (get_field('use_curated_posts')): 
$row_one = get_field('row_article_one');
$row_two = get_field('row_article_two');
$column_one = get_field('column_article_one');
$column_two = get_field('column_article_two');
$column_three = get_field('column_article_three');
else: 
$cat = get_field('category');
// print_r($cat);
$args = array(
    'post_type'     => 'post',
    'posts_per_page'=> 5,
    'category_name' => $cat[0]->slug,
);
// if (!empty($cat)) {
    // $args['category_name'] = $cat[0]->name;
// }

$the_query = new WP_Query( $args );
// print_r($the_query->posts);
// $queryArray = $the_query->posts;
// echo 'name '.$cat[0]->name;

// print_r($the_query->posts);
shuffle($the_query->posts);
$row_one = $the_query->posts[0];
$row_two = $the_query->posts[1];
$column_one = $the_query->posts[2];
$column_two = $the_query->posts[3];
$column_three = $the_query->posts[4];

// if ($the_query->have_posts()): 
//     while ($the_query->have_posts()): $the_query->the_post();
//     echo '<h4>the post '.get_the_title().'</h4>';
// endwhile;
// endif;
// wp_reset_query();
endif; ?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="small-articles">
    <?php foreach(array($row_one, $row_two) as $row):
        // print_r($row);
        $ID = $row->ID; ?>
        <div class="small-article-card">
        <?php echo get_field('sponsored', $row->ID) ? '<p class="sponsored-label ">Sponsored</p>' : ""; ?>
            <a href="<?php echo get_the_permalink( $ID ); ?>" class="sma-img" >
            <?php echo get_the_post_thumbnail( $ID, 'three_hundred_wide' ); ?>
            </a>
            <!-- <img src="<?php //echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>" alt=""> -->
            <div class="sma-title">
            <h4><a href="<?php echo get_the_permalink( $ID ); ?>"><?php echo get_the_title( $ID );  ?></a></h4>
            </div>
        </div>
        
    <?php endforeach; ?>
    </div>

    <div class="coc-articles">
    <?php foreach(array($column_one, $column_two, $column_three) as $column): 
    $ID = $column->ID; 
    // $img = get_the_post_thumbnail_url($ID, "three_hundred_wide");?>
       <div class="article-card">
       
        <a href="<?php echo the_permalink($ID); ?>" class="article-img">
        <!-- <div style="background-image: url(<?php //echo $img; ?>);"></div> -->
        <?php echo get_the_post_thumbnail( $ID, 'three_hundred_wide' ); ?>
        <?php echo get_field('sponsored', $column->ID) ? '<p class="sponsored-label small">Sponsored</p>' : ""; ?>
        
        </a>
        <div class="article-text">
        <a href="<?php echo the_permalink($ID); ?>">
            <?php echo '<h4>'.get_the_title( $ID ).'</h4>';  ?>
        </a>
        </div>

    </div>
    <?php endforeach; ?>
    </div>
    <?php if (get_field('button_text')): ?>
    <button class="uppercase"><a href="<?php echo get_field('button_link'); ?>"><?php echo get_field('button_text'); ?></a></button>
    <?php elseif(!get_field('use_curated_posts')): 
       $cat_page_obj = get_field('category_page', 'category_'.$cat[0]->term_id); ?>
    <button class="uppercase"><a href="<?php echo get_permalink( $cat_page_obj->ID); ?>">More <?php echo $cat[0]->name ?> Articles</a></button>
    <?php wp_reset_postdata(); 
    endif;
    ?>

    
</div>

