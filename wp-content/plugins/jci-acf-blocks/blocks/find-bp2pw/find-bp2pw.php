<?php 
/**
 * Find Your Best Place to Work/Play
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'bp2pw-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'bp2pw';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
$play_or_work = get_field('play_or_work');

$final_list = array();

if ($play_or_work == 'Play') {
    $curated = get_field('curated_play_articles'); 
    $subfield = 'play_article';
    $cats = array('11','16','47','13','14', '18'); 
} else {
    $curated = get_field('curated_work_articles'); 
    $subfield = 'work_article';
    $cats = array('12'); 
}

$number_curated = 0;
if ($curated) {
    foreach ($curated as $key => $value) {
        array_push($final_list,$value[$subfield]);
    }
    $number_curated = count($curated);
}

if ($number_curated < 9) { 
    $ppp = 9 - $number_curated;
    $extra_posts_arr = array(
        'posts_per_page'        => $ppp,
        'category__in'          => $cats,
        'post_type'             => 'post',
    );
    $extra_posts = get_posts($extra_posts_arr);
    // print_r($extra_posts);
    // echo 'id '.$extra_posts[0]->ID;
    foreach ($extra_posts as $key => $value) {
        array_push($final_list, $value->ID);
    }
}


?>

<h2 class="big-thin-text" style="margin-bottom: -15px;">Find Your Best Place To <?php echo  $play_or_work; ?></h2>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<?php
foreach ($final_list as $key => $value) {
    $cat = get_the_category( $value );
    if ($key == 0) {
        echo '<div class="bp2pw__col"><div class="bp2pw__item featured">';
        echo '<a href="'.get_the_permalink( $value).'">'.get_the_post_thumbnail( $value, 'medium' ).'</a>';
        if ($cat) {echo '<h5 class="green-text uppercase ">'.$cat[0]->name.'</h5>';}
        echo '<h3 class="bp2pw__title"><a class="unstyle-link" href="'.get_the_permalink( $value).'">'.get_the_title($value).'</a></h3>';
        echo '<p class="bp2pw__excerpt">'.get_the_excerpt( $value ).'</p>';
        echo '</div></div>';
    } else {
        if ($key == 1 || $key == 5 ) { echo '<div class="bp2pw__col">';}
        echo '<div class="bp2pw__item">';
        if ($cat) {echo '<h5 class="green-text uppercase">'.$cat[0]->name.'</h5>';}
        echo '<h3 class="bp2pw__title"><a href="'.get_the_permalink( $value).'">'.get_the_title($value).'</a></h3>';
        echo '</div>';
        if ($key == 4 || $key == 8 ) { echo '</div>';}
    }
}
?>
</div>