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

if ($play_or_work == 'Play') {
    $curated = get_field('curated_play_articles'); 
    $subfield = 'play_article';
    $cats = array('11','16','47','13','14', '18'); 
} else {
    $curated = get_field('curated_work_articles'); 
    $subfield = 'work_article';
    $cats = array('12'); 
}

$number_curated = count($curated);
if ($number_curated < 9) {
    $cats = $play_or_work == 'Play' ? array('11','16','47','13','14', '18') : array('12'); 
    $ppp = 9 - $number_curated;
$extra_posts = array(
    'posts_per_page'        => $ppp,
    'category__in'          => $cats,
    'post_type'             => 'articles',
    
);
}


?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

</div>