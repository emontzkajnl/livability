<?php 

/**
 * Place Topics Block Template
 */


 // Create id attribute allowing for custom "anchor" value.
 $blockId = $block['id'];
$id = 'place-topics-' . $blockId;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'place-topics-container';

if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$cat = get_category(get_field('topic')[0]);
$sponsor = get_topic_sponsors(); // defined in functions.php
$place = get_field('place');
$title = get_field('title');
$sticky_array = get_option('sticky_posts');
$result_array = [];
$post_count = 1;

// $test = get_field('place_relationship');
// echo 'place is '.$place;
$args = array(  
    'category_name'     => $cat->slug,
    'posts_per_page'    => -1,
    'post_status'       => 'publish',
    'post_type'         => 'post',
    'meta_query'        => array(
        array( 
            'key'       => 'place_relationship',
            'value'     => '"' . get_the_ID() . '"',
            'compare'   => 'LIKE'
        ),
        array(
            'key'       => 'sponsored',
            'value'     => 0
        ),
        'relation'      => 'AND'
    ),
    // 'paged'             => 1,
    'ignore_sticky_posts'   => false
);

$topics = new WP_Query( $args );
 if ($topics->have_posts()): 

    //begin custom sticky code
foreach($topics->posts as $key=>$value ) {
    if (in_array($value->ID, $sticky_array) ) {
        $result_array[] = $value->ID;
        unset($topics->posts[$key]);
    } 
}
if (!empty($result_array)) {
    $sp = get_posts(array('include' => $result_array));
    $topics->posts = array_merge($sp, $topics->posts);
}
$topic_posts = $topics->posts;
$count_posts = count($topic_posts);
if ($count_posts > 3) {
    // echo '<br />count is '.$count_posts;
    $topics->max_num_pages = ceil(($count_posts - 3) / 3) + 1; // custom pagination: first page 3 posts, then 15 posts per page
    // $topics->posts = array_slice($topic_posts, 0, 3);
    // $topics->query['posts_per_page'] = 3;
    // $topics->query_vars['posts_per_page'] = 3;
    // $topics->posts = array_values($topics->posts); 
}
//end custom sticky code

$loop_count = min($count_posts, 3);
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<h2 class="green-line"><?php echo $cat->name; ?></h2>
<?php
if ($sponsor) {
    // print_r($sponsor);
    foreach($sponsor as $S) {
        if ($S['category'] == $cat->term_id) {
        $sponsor_name = $S['sponsor'];
        $sponsor_url = $S['url']; 
        echo $sponsor_url ?
        '<p class="topic-sponsor">Sponsored by <a href="'.$sponsor_url.'" target="_blank">'.$sponsor_name.'</a></p>' : 
        '<p class="topic-sponsor">Sponsored by '.$sponsor_name.'</p>';
        break;
        }
    }
} ?>

<?php //if(function_exists('alm_render')){
    //alm_render($args);
//} 
// echo do_shortcode( '[ajax_load_more  acf="true" acf_field_type="relationship" acf_field_name="place_relationship" orderby=”post__in”  repeater="template_2"   post_type="post" posts_per_page="3" ]');
// category="'.$cat->slug.'"
// acf_field_type="relationship" acf_field_name="place_relationship" orderby=”post__in” acf="true" preloaded="true" preloaded_amount="3"
?>
<ul>
<?php for ($i=0; $i < $loop_count; $i++) { 
    $ID = $topic_posts[$i]->ID;
    $places = get_field('place_relationship', $ID); ?>
    <li class="one-hundred-list-container">
    <a href="<?php echo get_the_permalink( $ID ); ?>" class="ohl-thumb" >
    
    <?php echo  get_the_post_thumbnail($ID, 'rel_article'); ?>
    <?php echo get_field('sponsored', $ID) ? '<p class="sponsored-label ">Sponsored</p>' : ""; ?>
    
    </a>
    <div class="ohl-text">
    <a href="<?php  echo get_the_permalink( $ID ); ?>">
    <?php echo '<h2>'.get_the_title( $ID).'</h2>'; 
    echo '<p>'.get_the_excerpt( $ID).'</p>';
    ?>
    </a>
    </div>
    </li>
<?php }

echo '</ul>'; ?>
<?php if ($topics->max_num_pages > 1): ?>
<button class="more-articles load-places"  data-topic-block="<?php echo $blockId; ?>">More Articles</button>
<!-- Add global vars unique to this block using block id -->
<script>
window['<?php echo $blockId; ?>'] = {};
Object.assign(window['<?php echo $blockId; ?>'], {current_page: '1'});
// Object.assign(window['<?php //echo $blockId; ?>'], {current_page: 2});
Object.assign(window['<?php echo $blockId; ?>'], {category: '<?php echo $cat->slug; ?>'});
Object.assign(window['<?php echo $blockId; ?>'], {relationship_id: '<?php echo '"' . get_the_ID() . '"'; ?>'});
Object.assign(window['<?php echo $blockId; ?>'], {max_page: '<?php echo $topics->max_num_pages; ?>'});
</script>
</div>
 <?php endif;  
?>
<?php else: return; ?>
</div>
<?php endif; 

