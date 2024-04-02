<?php 

$city = $args['city']; 
$abbv = $args['abbv']; 
$id = get_the_ID();
$results = $wpdb->get_results( "SELECT * FROM 2024_city_data WHERE place_id = $id", OBJECT );
$high = $results[0]->avg_high_temp;
$low = $results[0]->avg_low_temp;
$rain = $results[0]->avg_rain;
$snow = $results[0]->avg_snow;

if ($high && $low && $rain && $snow):

echo '<h3 id="weather">Weather in '.$city.', '.strtoupper($abbv).'</h3>'; ?>

<div class="weather-block-2">
    <div class="weather-block-2__card temp">
        <div class="weather-block-2__icon temp"></div>
        <h4>Average Temperatures</h4>
        <p><?php echo $high.' High / '.$low.' Low'; ?></p>
    </div>    <div class="weather-block-2__card rain">
        <div class="weather-block-2__icon rain"></div>
        <h4>Average Annual Rain</h4>
        <p><?php echo $rain?> Inches</p>
    </div>
    <div class="weather-block-2__card snow">
        <div class="weather-block-2__icon snow"></div>
        <h4>Average Annual Snow</h4>
        <p><?php echo $snow?> Inches</p>
    </div>
</div>

<?php endif; ?>


