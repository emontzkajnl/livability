<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

if ($_GET) {
    $cityId = htmlspecialchars($_GET['cityid']);
    // commute, homevalue, income, pop, property tax
    $commute = get_field('city_commute', $cityId ); 
    $cityHomeValue = str_replace(',','',get_field('city_home_value', $cityId)); 
    $cityHomeValue ? intval($cityHomeValue) : ''; 
    $cityHouseholdIncome = str_replace(',','', get_field('city_household_income', $cityId)); 
    $cityHouseholdIncome = intval($cityHouseholdIncome); 
    $cityPopulation = str_replace(',','', get_field('city_population', $cityId)); 
    $cityPopulation = intval($cityPopulation);
    $cityPropTax = str_replace(',','', get_field('city_property_tax', $cityId)); 
    $cityPropTax = intval($cityPropTax);
    ?>

    <div class="city-widget">
        <div class="city-widget-image" style="background-image: url('<?php echo get_the_post_thumbnail_url($cityId, 'rel_article'  ) ?>');">
        <div class="city-widget-textbox">
            <span>Livability.com</span>
            <h3><?php echo get_the_title($cityId); ?></h3>
        </div>
    </div>
        <table  class="livscore-table">
            <thead>
                <tr>
                    <th colspan="2"><span class="livscore" style="color: white;">Livability.com</span><br><span class="livscore-number">Quick Facts</span></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($commute) { echo '<tr><td>Average Commute</td><td>'.$commute.' Min</td></tr>'; } ?>
                <?php if ($cityHomeValue) { echo '<tr><td>Median Home Value</td><td>$'.number_format($cityHomeValue).'</td></tr>'; } ?>
                <?php if ($cityHouseholdIncome) { echo '<tr><td>Median Household Income</td><td>$'.number_format($cityHouseholdIncome).'</td></tr>'; } ?>
                <?php if ($cityPopulation) { echo '<tr><td>Total Population</td><td>'.number_format($cityPopulation).'</td></tr>'; } ?>
                <?php if ($cityPropTax) { echo '<tr><td>Median Property Tax</td><td>$'.number_format($cityPropTax).'</td></tr>'; } ?>
                <tr style="background-color: #9fd274;">
                    <td  style="text-align: center; color: white; font-weight: bold; " colspan="2"><?php echo '<a style="color: white; text-decoration: none;" href="'.get_the_permalink( $cityId ).'" target="_blank">Discover '.get_the_title($cityId).' on Livability.com</a>'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

<?php } else {
    echo 'there is no param here';
}

get_footer();