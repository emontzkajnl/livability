<?php 
if (array_key_exists('city', $args)) {
    $id = $args['city'];
    // echo 'has city';
} else {
    $id = get_the_ID();
    // echo 'has no city';
}

if (!get_field('hide_facts', $id)):

if ( get_post_type() == 'best_places' ) {
    $add_title = 'About '.get_the_title();
} else {
    $add_title = '';
}

 $commute = get_field('city_commute', $id);
 $cityHomeValue = str_replace(',','',get_field('city_home_value', $id)); 
 $cityHomeValue ? intval($cityHomeValue) : ''; 
 $cityHouseholdIncome = str_replace(',','', get_field('city_household_income', $id));
 $cityHouseholdIncome = intval($cityHouseholdIncome);
 $cityPopulation = str_replace(',','', get_field('city_population', $id));
 $cityPopulation = intval($cityPopulation);
 $cityWalkScore = get_field('city_walk_score', $id);
 $cityPropTax = str_replace(',','', get_field('city_property_tax', $id));
 $cityPropTax = intval($cityPropTax);
 
echo '<div class="quick-facts-block"><h2 class="qf-title h3">Quick Facts '.$add_title.'</h2><dl>';
echo $commute ? '<div><dt>Average Commute</dt><dd>'.$commute.'</dd></div>' : '';
echo $cityHomeValue ? '<div><dt>Median Home Value</dt><dd>$'.number_format($cityHomeValue).'</dd></div>' : '';
echo $cityHouseholdIncome ? '<div><dt>Med. Household Income</dt><dd>$'.number_format($cityHouseholdIncome).'</dd></div>' : '';
echo $cityPopulation ? '<div><dt>Total Population</dt><dd>'.number_format($cityPopulation).'</dd></div>' : '';
echo $cityWalkScore ? '<div><dt>Walk Score</dt><dd>'.number_format($cityWalkScore).'</dd></div>' : '';
echo $cityPropTax ? '<div><dt>Median Property Tax</dt><dd>$'.number_format($cityPropTax).'</dd></div>' : '';
 //echo '</dl><p class="widget-link"><a href="'.site_url( 'city-data-widget?cityid=' ).$id.'">Display these facts on your site.</a></p></div>';
 echo '</dl></div>';
endif; // if !hide_facts