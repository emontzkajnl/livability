<?php 
$city_name = $args['city'];
$state_name = $args['state'];
$id = get_the_ID();

//  $commute = get_field('city_commute', $id); 
//  $cityHomeValue = str_replace(',','',get_field('city_home_value', $id)); 
//  $cityHomeValue ? intval($cityHomeValue) : ''; 
//  $cityHouseholdIncome = str_replace(',','', get_field('city_household_income', $id)); 
//  $cityHouseholdIncome = intval($cityHouseholdIncome); 
//  $cityPopulation = str_replace(',','', get_field('city_population', $id)); 
//  $cityPopulation = intval($cityPopulation);
//  $cityWalkScore = get_field('city_walk_score', $id);  
//  $cityPropTax = str_replace(',','', get_field('city_property_tax', $id)); 
//  $cityPropTax = intval($cityPropTax);

 global $wpdb;
 $results = $wpdb->get_results( "SELECT * FROM 2024_city_data WHERE place_id = $id", OBJECT );
 $home_val = $results[0]->avg_hom_val;
 $prop_tax = $results[0]->avg_pro_tax;
 $city_pop = $results[0]->city_pop;
 $avg_com = $results[0]->avg_com;
 $avg_hou_inc = $results[0]->avg_hou_inc;
 $avg_rent = $results[0]->avg_rent;

 
echo '<div class="quick-facts-2-block" id="quick-facts"><h2 class="qf-title h3">Quick Facts about '.$city_name.', '.$state_name.'</h2><dl>';
echo $home_val ? '<div class="home-val"><dt>Median Home Value</dt><dd>$'.number_format($home_val).'</dd></div>' : '';
echo $prop_tax ? '<div class="prop-tax"><dt>Median Property Tax</dt><dd>$'.number_format($prop_tax).'</dd></div>' : '';
echo $city_pop ? '<div class="city-pop"><dt>Total Population</dt><dd>'.number_format($city_pop).'</dd></div>' : '';
echo $avg_com ? '<div class="avg-com"><dt>Average Commute</dt><dd>'.$avg_com.' min</dd></div>' : '';
echo $avg_hou_inc ? '<div class="avg-inc"><dt>Median Household Income</dt><dd>$'.number_format($avg_hou_inc).'</dd></div>' : '';
echo $avg_rent ? '<div class="avg-rent"><dt>Median Rent per Month</dt><dd>$'.number_format($avg_rent).'</dd></div>' : '';

 //echo '</dl><p class="widget-link"><a href="'.site_url( 'city-data-widget?cityid=' ).$id.'">Display these facts on your site.</a></p></div>';
 echo '</dl></div>';