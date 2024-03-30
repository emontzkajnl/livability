<?php 

$city = $args['city']; 
$abbv = $args['abbv']; 
$sun = get_field('city_sun');
$rain = get_field('city_rain');
$snow = get_field('city_snow');

if ($sun && $rain && $snow):

echo '<h3 id="weather">Weather in '.$city.', '.strtoupper($abbv).'</h3>'; ?>

<div class="weather-block-2">
    <div class="weather-block-2__card sun">
        <div class="weather-block-2__icon sun"></div>
        <h4>Average Annual Sun</h4>
        <p><?php echo $sun?> Days</p>
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


