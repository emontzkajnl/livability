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
<?php if ( ! is_handheld() ) { ?>
<ul class="bp2l__tab-nav">
<li  data-tab="tab-one" class="active" >Top 100 Best Places to Live in US</li>
<li data-tab="tab-two" >Best Places in Region</li>
<li  data-tab="tab-three">Best Places in State</li>
<li  data-tab="tab-four">More Best Places</li>
</ul>
<?php } ?>

<?php if ( is_handheld()) {echo '<div class="bp2l__mobile-tab active" data-tab="tab-one"><h3>Top 100 Best Places to Live in US</h3></div>';} ?>
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
<?php if ( is_handheld()) {echo '<div class="bp2l__mobile-tab" data-tab="tab-two"><h3>Best Places in Region</h3></div>';} ?>
<div id="tab-two" class="tab-content" style="display: none;">
    <div class="bp2l__one-map-container">
        <iframe src="https://map.proxi.co/r/0fSzOxRtmpiyf0FVupe-" allow="geolocation; clipboard-write" width="100%" height="625px" style="border-width: 0px;" allowfullscreen name="proxi-region-map"></iframe> <div style="font-family: Sans-Serif; font-size:12px;color:#000000;opacity:0.5; padding-top: 5px;"> powered by <a href="https://www.proxi.co/?utm_source=poweredbyproxi" style="color:#000000" target="_blank">Proxi</a> </div>
    </div>
    <div class="bp2l__one-text-container">
        <?php echo $region; ?>
        <ul class="bp2l__region-list">
            <li data-map-id="0fSzOxRtmpiyf0FVupe-"><a href="https://map.proxi.co/r/0fSzOxRtmpiyf0FVupe-" target="proxi-region-map">Northwest</a></li>
            <li data-map-id="-zh_7WnxLgQ-CDqhL4O_"><a href="https://map.proxi.co/r/-zh_7WnxLgQ-CDqhL4O_" target="proxi-region-map">Southwest</a></li>
            <li>Midwest</li>
            <li>Northeast</li>
            <li>Southeast</li>
        </ul>
    </div>
</div>
<?php if ( is_handheld()) {echo '<div class="bp2l__mobile-tab" data-tab="tab-three"><h3>Best Places in State</h3></div>';} ?>
<div id="tab-three" class="tab-content" style="display: none;">
    <div class="bp2l__one-map-container">
        <iframe src="https://map.proxi.co/r/c-OsWkiWgcodPdEN1I2I" allow="geolocation; clipboard-write" width="100%" height="625px" style="border-width: 0px;" name="proxi-state-map" allowfullscreen></iframe> <div style="font-family: Sans-Serif; font-size:12px;color:#000000;opacity:0.5; padding-top: 5px;"> powered by <a href="https://www.proxi.co/?utm_source=poweredbyproxi" style="color:#000000" target="_blank">Proxi</a> </div>
    </div>
    <div class="bp2l__one-text-container">
            <select name="States" id=""  size="12">
                <optgroup label="Select a State">
                <a href="https://map.proxi.co/r/0fSzOxRtmpiyf0FVupe-" target="proxi-state-map"><option value="Alabama">Alabama</option></a>
                <option value="Alaska"><a href="https://map.proxi.co/r/-zh_7WnxLgQ-CDqhL4O_" target="proxi-state-map">Alaska</a></option>
                <option value="Arizonaxxx">Arizona</option>
                <option value="Arkansas">Arkansas</option>
                <option value="California">California</option>
                <option value="Colorado">Colorado</option>
                <option value="Connecticut">Connecticut</option>
                <option value="Delaware">Delaware</option>
                <option value="Florida">Florida</option>
                <option value="Georgia">Georgia</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Idaho">Idaho</option>
                <option value="Illinois">Illinois</option>
                <option value="Indiana">Indiana</option>
                <option value="Iowa">Iowa</option>
                <option value="Kansas">Kansas</option>
                <option value="Kentucky">Kentucky</option>
                <option value="Louisiana">Louisiana</option>
                <option value="Maine">Maine</option>
                <option value="Maryland">Maryland</option>
                <option value="Massachusetts">Massachusetts</option>
                <option value="Michigan">Michigan</option>
                <option value="Minnesota">Minnesota</option>
                <option value="Mississippi">Mississippi</option>
                <option value="Missouri">Missouri</option>
                <option value="Montana">Montana</option>
                <option value="Nebraska">Nebraska</option>
                <option value="Nevada">Nevada</option>
                <option value="New Hampshire">New Hampshire</option>
                <option value="New Jersey">New Jersey</option>
                <option value="New Mexico">New Mexico</option>
                <option value="New York">New York</option>
                <option value="North Carolina">North Carolina</option>
                <option value="North Dakota">North Dakota</option>
                <option value="Ohio">Ohio</option>
                <option value="Oklahoma">Oklahoma</option>
                <option value="Oregon">Oregon</option>
                <option value="Pennsylvania">Pennsylvania</option>
                <option value="Rhode Island">Rhode Island</option>
                <option value="South Carolina">South Carolina</option>
                <option value="South Dakota">South Dakota</option>
                <option value="Tennessee">Tennessee</option>
                <option value="Texas">Texas</option>
                <option value="Utah">Utah</option>
                <option value="Vermont">Vermont</option>
                <option value="Virginia">Virginia</option>
                <option value="Washington">Washington</option>
                <option value="West Virginia">West Virginia</option>
                <option value="Wisconsin">Wisconsin</option>
                <option value="Wyoming">Wyoming</option>
            </optgroup>
            </select>

    </div>
</div>

<?php if ( is_handheld()) {echo '<div class="bp2l__mobile-tab" data-tab="tab-four"><h3>More Best Places</h3></div>';} ?>
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
