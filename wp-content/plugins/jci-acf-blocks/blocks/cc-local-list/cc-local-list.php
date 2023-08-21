<?php 
/**
 * Local Connected Communities List
 */

if ($is_preview && $block['data']['preview_img']) {
	echo '<img src="'.$block['data']['preview_img'].'" style="width: 100%; height: auto;"/>';
}

$id = 'cc-local-list-container-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'cc-local-list-container';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
// $state_ids = get_children( '274', ARRAY_A );
// $city_array = array();
// foreach ($state_ids as $id) {
//     array_push($city_array, $id['ID']);
// }
// print_r($city_array);
$state = get_field('state');
$args = array(
    'post_type'     => 'post',
    'posts_per_page'=> -1,
    // 'meta_query'        => array(
    //     array( 
    //         'key'       => 'place_relationship',
    //         'value'     => '"' . $state . '"',
    //         'compare'   => 'LIKE'
    //     )
    // ),
    'post_status'   => 'publish',
    'category_name' => 'connected-communities'
);

$cc_query = new WP_Query($args);
$cc_posts = $cc_query->posts;
$cc_filtered = array();
// print_r($cc_posts);
foreach ($cc_posts as $key => $cc_post) {
    $place = get_post_meta( $cc_post->ID, 'place_relationship', true);
    $s = wp_get_post_parent_id($place[0]);
    // echo 'place is '.$place[0].' and s is '.$s.' and state is '.$state.'<br />';
    if ($s == $state  || $place[0] == $state) {
        array_push($cc_filtered, $cc_post);
    }
}

$cc_query->posts = $cc_filtered;
$cc_query->found_posts = count($cc_filtered);
$cc_query->post_count = count($cc_filtered);
$remaining = count($cc_filtered);
if ($cc_query->have_posts()): ?>
    <div class="cc-local-list-container">
    <h2 class="green-line">Places in <?php echo get_the_title($state); ?> with fiber internet</h2>
    <?php echo '<ul class="cc-list">';
    foreach ($cc_query->posts as $cc_post) {
        $nonplace = get_field( $cc_post->ID, 'non-livability_connected_community');
        $place = get_post_meta( $cc_post->ID, 'place_relationship', true); 
        if ($nonplace) {
            $city_title = $nonplace;
        } else {
            $city_title = substr(get_the_title($place[0]), 0, -4); // remove state from title
        }
        echo '<li><a href="#'.$cc_post->ID.'">'.$city_title.'</a></li>';
    }
    echo '</ul>';
    $col_per_row;
    $col_count;

    if ($remaining > 2) {
        $col_per_row = 3;
        $col_count = 3;
    } elseif ($remaining == 2) {
        $col_per_row = 2;
        $col_count = 2;
    } else {
        $col_per_row = 1;
        $col_count = 1;
    }

      echo '<div class="curated-posts-'.$col_per_row.'">';
    while ($cc_query->have_posts()): $cc_query->the_post();
    $sectionDiv = '';
    if ($col_count == 0) {
        if ($remaining > 2) {
            $col_per_row > 1 ? $col_per_row-- : $col_per_row = 3;
            $col_count = $col_per_row;
        } else {
            $col_per_row = $remaining;
            $col_count = $remaining;
        }
        echo '</div><div class="curated-posts-'.$col_per_row.'">';
    }
    $ID = get_the_ID(); ?>
        <div class="cp" ID="<?php echo $ID; ?>">
            <?php echo get_the_post_thumbnail( $ID , 'medium_large' ); ?>
            <a href="<?php echo get_the_permalink( $ID) ?>">
            <div class="cp-container">
                <?php echo get_field('sponsored', $ID) ? '<p class="sponsored-label">Sponsored</p>' : ""; ?>
                <h3><?php echo get_the_title(); ?></h3>
            </div>
            </a>
        </div>
        <?php $remaining--;
        $col_count--;
    endwhile;
endif;
wp_reset_postdata(  );
echo '</div>';
// endif;
    // echo '</div>';
    
//}

// echo '<pre>';
// print_r($cc_query);
// echo '</pre>';
// output all title anchor links in columns
// write function to output each article
// parameters: 