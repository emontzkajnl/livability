<?php 
session_start();
include_once( get_stylesheet_directory() .'/assets/lib/regions.php'); 
if (isset($_SESSION['bp23_cat'])) {
    $sortBy = $_SESSION['bp23_cat'];
} else {
    $sortBy = 'livscore';
}

?>
<h3 id="top-one-hundred-list">Explore Our Best Places to Live in the U.S.</h3>
<h5 style="margin-bottom: 10px;">Sort by category</h5>
<ul class="bp23-category-btns" >
    <li class="bp23-category-btn" class="liv-score" ><div data-cat="livscore" class="livscore  <?php echo $sortBy == 'livscore' ? 'active' : ''; ?>">LivScore</div></li>
    <li class="bp23-category-btn" class="amenities" ><div data-cat="amenities" class="amenities <?php echo $sortBy == 'amenities' ? 'active' : ''; ?>">Amenities</div></li>
    <li class="bp23-category-btn" class="economy" ><div data-cat="economy" class="economy <?php echo $sortBy == 'economy' ? 'active' : ''; ?>">Economy</div></li>
    <li class="bp23-category-btn" class="education"><div data-cat="education"  class="education <?php echo $sortBy == 'education' ? 'active' : ''; ?>">Education</div></li>
    <li class="bp23-category-btn" class="environment"><div data-cat="environment"  class="environment <?php echo $sortBy == 'environment' ? 'active' : ''; ?>">Environment</div></li>
    <li class="bp23-category-btn" class="health-care"><div  data-cat="healthcare" class="health-care <?php echo $sortBy == 'healthcare' ? 'active' : ''; ?>">Health</div></li>
    <li class="bp23-category-btn" class="housing"><div  data-cat="housing" class="housing <?php echo $sortBy == 'housing' ? 'active' : ''; ?>">Housing & Cost of Living</div></li>
    <li class="bp23-category-btn" class="safety"><div  data-cat="safety" class="safety <?php echo $sortBy == 'safety' ? 'active' : ''; ?>">Safety</div></li>
    <li class="bp23-category-btn" class="transportation"><div  data-cat="transportation" class="transportation <?php echo $sortBy == 'transportation' ? 'active' : ''; ?>">Transportation</div></li>
</ul>

<div class="bp24__results-container">
    <div class="bp24__filters-container">
        <h5>Filter the list</h5>
        <hr>
        <fieldset id="region">
        <legend>U.S. Region</legend>
        <div class="radio-container">
            <input type="radio" name="region" id="allRegions" value="" checked />
            <label for="allRegions">All</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="region" id="northeast" value="northeast" />
            <label for="northeast">Northeast</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="region" id="northwest" value="northwest"/>
            <label for="northwest">Northwest</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="region" id="midwest" value="midwest" />
            <label for="northeast">Midwest</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="region" id="southeast" value="southeast"/>
            <label for="southeast">Southeast</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="region" id="southwest" value="southwest"/>
            <label for="southwest">Southwest</label>
        </div>
        </fieldset>
        <hr>
        <fieldset id="population">
        <legend>Population</legend>
        <div class="radio-container">
            <input type="radio" name="population" id="allPopulation" value="" checked />
            <label for="allPopulation">All</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="population" id="75pop" value="75000-99999" />
            <label for="75pop">75K-99K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="population" id="100pop" value="100000-149999"/>
            <label for="100pop">100K-149K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="population" id="150pop" value="150000-199999" />
            <label for="150pop">150K-199K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="population" id="200pop" value="200000-299999"/>
            <label for="200pop">200K-299K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="population" id="300pop" value="300000-399999"/>
            <label for="300pop">300K-399K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="population" id="400pop" value="400000-500000"/>
            <label for="400pop">400K-500K</label>
        </div>
        </fieldset>
        <hr>
        <fieldset id="home_value">
            <legend>Med. Home Value</legend>
        <div class="radio-container">
            <input type="radio" name="home_value" id="allhv" value="" />
            <label for="allhv">All</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="home_value" id="zerohv" value="0-199999" />
            <label for="zerohv">$0-$199K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="home_value" id="200hv" value="200000-299999" />
            <label for="200hv">$200K-$299K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="home_value" id="300hv" value="300000-399999" />
            <label for="300hv">$300K-$399K</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="home_value" id="400hv" value="400000-500000" />
            <label for="400hv"">$400K-$500K</label>
        </div>
        </fieldset>
        <p class="bp24__reset-btn">Reset All Filters</p>
        <button class="open-top-100-24-map bp2l__green-btn">Top 100 Map</button>
    </div>
<div class="bp24__results">
    <?php global $wpdb;

    $score_string = $sortBy == 'livscore' ? 'LivScore' : $sortBy.' score';
     $results = $wpdb->get_results( "SELECT * FROM 2024_top_100 ORDER BY ".$sortBy." DESC", OBJECT );
     $results = array_splice($results, 0, 20);
     foreach ($results as $key=>$value) {
     $city_data = $wpdb->get_results( "SELECT * FROM 2024_city_data  WHERE place_id = $value->place_id", OBJECT );
     $population = $city_data[0]->city_pop;
     $home_value = $city_data[0]->avg_hom_val;
     ?>
    <div class="bp24__card">
        <div class="bp24__img-container" >
        <a href="<?php echo get_the_permalink( $value->place_id).'?top-100'; ?>">
        <?php echo get_the_post_thumbnail( $value->place_id, 'medium'); ?>k
        </a>
        </div>
        <div class="bp24__text-container">
        <a class="unstyle-link" href="<?php echo get_the_permalink( $value->place_id).'?top-100';  ?>"><h4 class="bp24__city"><?php echo $value->city; ?></h4></a>
        <p class="bp24__state"><?php echo $value->state; ?></p>
        <p class="bp24__cat-paragraph"><?php echo ucfirst($score_string).': '.json_decode($value->$sortBy, true); ?></p>
        <p>Region: <?php echo get_region_by_state_name($value->state); ?></p>
        <p>Population: <?php echo  number_format($population); ?></p>
        <p>Med. Home Value: $<?php echo number_format($home_value); ?></p>
        <p class="bp24__read-more"><a class="unstyle-link" href="<?php echo get_the_permalink( $value->place_id).'?top-100';  ?>">Read More</a></p>
        </div>
    </div>
    <?php } //end foreach ?>
</div>
</div> <!-- results container -->
<!-- <h3>Top 100 Best Places to Live in the U.S. Map</h3> -->
<!-- <iframe src="https://map.proxi.co/r/top-100-best-places-2024_view" allow="geolocation; clipboard-write" width="100%" height="625px" style="border-width: 0px;" allowfullscreen></iframe> -->