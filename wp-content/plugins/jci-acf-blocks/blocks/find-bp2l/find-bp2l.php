<?php 
/**
 * Find Your BP2L
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'bp2l';
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'bp2l';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$top_onehundred = get_field('top_100_text');
$region = get_field('region_text');
// $bp_args = array(
//     'post_type'         => 'best_places',
//     'posts_per_page'    => 8,
//     'post_status'       => 'publish',
//     'post_parent'       => 0
// );
// $bp_query = new WP_Query($bp_args);
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<h2 class="bp2l__title">Find Your Best Place to Live</h2>
<ul class="bp2l__tab-nav">
<li  data-tab="tab-one"class="active" >Top 100 Best Places to Live in US</li>
<li data-tab="tab-two" >Best Places in Region</li>
<li  data-tab="tab-three">Best Places in State</li>
<li  data-tab="tab-four">More Best Places</li>
</ul>
<div id="tab-one" class="tab-content">
    <div class="bp2l__one-map-container">
        <iframe src="https://map.proxi.co/r/0fSzOxRtmpiyf0FVupe-" allow="geolocation; clipboard-write" width="100%" height="625px" style="border-width: 0px;" allowfullscreen></iframe> 
    </div>
    <div class="bp2l__one-text-container">
        <div class="bp2l__top-100-img">
            <img src="https://livability.com/wp-content/themes/livability/assets/images/2023-Livability-Top-100-best-places-badge.svg" alt="">
        </div>
        <?php echo $top_onehundred; ?>

        <a href=""><button style="background-color: #7dc244;">See our top 100 list</button></a>
        <a href=""><button >Previous Years List</button></a>
    </div>
</div>
<div id="tab-two" class="tab-content" style="display: none;">
    <div class="bp2l__one-map-container">
        <iframe src="https://map.proxi.co/r/0fSzOxRtmpiyf0FVupe-" allow="geolocation; clipboard-write" width="100%" height="625px" style="border-width: 0px;" allowfullscreen></iframe> <div style="font-family: Sans-Serif; font-size:12px;color:#000000;opacity:0.5; padding-top: 5px;"> powered by <a href="https://www.proxi.co/?utm_source=poweredbyproxi" style="color:#000000" target="_blank">Proxi</a> </div>
    </div>
    <div class="bp2l__one-text-container">
        <?php echo $region; ?>
    </div>
</div>
<div id="tab-three" class="tab-content" style="display: none;">
    <div class="bp2l__one-map-container">
        <iframe src="https://map.proxi.co/r/0fSzOxRtmpiyf0FVupe-" allow="geolocation; clipboard-write" width="100%" height="625px" style="border-width: 0px;" allowfullscreen></iframe> <div style="font-family: Sans-Serif; font-size:12px;color:#000000;opacity:0.5; padding-top: 5px;"> powered by <a href="https://www.proxi.co/?utm_source=poweredbyproxi" style="color:#000000" target="_blank">Proxi</a> </div>
    </div>
    <div class="bp2l__one-text-container">

    </div>
</div>
<div id="tab-four" class="tab-content" style="display: none;">

<?php 
    if (have_rows('best_places_list')):
        echo '<div class="bp2l__row">';
        while (have_rows('best_places_list')): the_row();
        $bp = get_sub_field('best_place'); ?>
        <div class="bp2l__bpl">
            <div class="bp2l__img-container">
            <a href="<?php echo get_the_permalink($bp); ?>"><?php echo get_the_post_thumbnail($bp, 'medium'); ?></a>
            </div>
            <h4 class="bp2l__bpl-title"><a class="unstyle-link" href="<?php echo get_the_permalink($bp); ?>"><?php echo get_the_title($bp); ?></a></h4>
        </div>

        
        <?php endwhile;
        echo '</div>';
    endif;
    wp_reset_query(  );
?>
</div>

</div>
