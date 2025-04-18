<?php 

$city = $args['city']; 
$abbv = $args['abbv']; 
$id = get_the_ID();
$results = $wpdb->get_results( "SELECT * FROM 2024_city_data WHERE place_id = $id", OBJECT );
$high = $results[0]->avg_high_temp;
$low = $results[0]->avg_low_temp;
$rain = $results[0]->avg_rain;
$snow = $results[0]->avg_snow;

if ($high != null && $low != null && $rain != null && $snow != null):

echo '<h2 id="weather">Weather in '.$city.', '.strtoupper($abbv).'</h2>'; ?>

<div class="weather-block-2">
    <div class="weather-block-2__card temp">
        <div class="weather-block-2__icon temp"></div>
        <h4>Average Temperatures</h4>
        <p><?php echo $high.' high / '.$low.' low'; ?></p>
    </div>    
    <div class="weather-block-2__card rain">
        <div class="weather-block-2__icon rain"></div>
        <h4>Average Annual Rainfall</h4>
        <p><?php echo $rain?> in</p>
    </div>
    <div class="weather-block-2__card snow">
        <div class="weather-block-2__icon snow"></div>
        <h4>Average Annual Snowfall</h4>
        <p><?php echo $snow?> in</p>
    </div>
</div>

<?php endif; ?>


