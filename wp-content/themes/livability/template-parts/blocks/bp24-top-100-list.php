<?php 
session_start();
include_once( get_stylesheet_directory() .'/assets/lib/regions.php'); 

?>
<h3 id="top-one-hundred-list">Explore Our Best Places to Live in the U.S.</h3>
<h5 style="margin-bottom: 10px;">Sort by category...</h5>
<ul class="bp23-category-btns" >
    <li class="bp23-category-btn" class="liv-score" ><div data-cat="livscore" class="livscore  <?php echo $meta_key == 'livscore' ? 'active' : ''; ?>">Liv Score</div></li>
    <li class="bp23-category-btn" class="amenities" ><div data-cat="amenities" class="amenities <?php echo $meta_key == 'amenities' ? 'active' : ''; ?>">Amenities</div></li>
    <li class="bp23-category-btn" class="economy" ><div data-cat="economy" class="economy <?php echo $meta_key == 'economy' ? 'active' : ''; ?>">Economy</div></li>
    <li class="bp23-category-btn" class="education"><div data-cat="education"  class="education <?php echo $meta_key == 'education' ? 'active' : ''; ?>">Education</div></li>
    <li class="bp23-category-btn" class="environment"><div data-cat="environment"  class="environment <?php echo $meta_key == 'environment' ? 'active' : ''; ?>">Environment</div></li>
    <li class="bp23-category-btn" class="health-care"><div  data-cat="health" class="health-care <?php echo $meta_key == 'health' ? 'active' : ''; ?>">Health</div></li>
    <li class="bp23-category-btn" class="housing"><div  data-cat="housing" class="housing <?php echo $meta_key == 'housing' ? 'active' : ''; ?>">Housing & Cost of Living</div></li>
    <li class="bp23-category-btn" class="safety"><div  data-cat="safety" class="safety <?php echo $meta_key == 'safety' ? 'active' : ''; ?>">Safety</div></li>
    <li class="bp23-category-btn" class="transportation"><div  data-cat="transportation" class="transportation <?php echo $meta_key == 'transportation' ? 'active' : ''; ?>">Transportation</div></li>
</ul>

<div class="bp24__results-container">
    <div class="bp24__filters-container">
        <h5>Filter by region, population and median home value</h5>
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
            <input type="radio" name="region" id="west" value="west"/>
            <label for="west">West</label>
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
    </div>
<div class="bp24__results">
    <?php global $wpdb;
    $sortBy = 'livscore';
    $score_string = $sortBy == 'livscore' ? '' : ' score';
     $results = $wpdb->get_results( "SELECT * FROM 2024_top_100  ORDER BY ".$sortBy, OBJECT );
     foreach ($results as $key=>$value) {
     $city_data = $wpdb->get_results( "SELECT * FROM 2024_city_data  WHERE place_id = $value->place_id", OBJECT );
     $population = $city_data[0]->city_pop;
     $home_value = $city_data[0]->avg_hom_val;
     ?>
    <div class="bp24__card">
        <div class="bp24__img-container" >
        <?php echo get_the_post_thumbnail( $value->place_id, 'medium'); ?>
        </div>
        <div class="bp24__text-container">
        <h4 class="bp24__city"><?php echo $value->city; ?></h4>
        <p class="bp24__state"><?php echo $value->state; ?></p>
        <p class="bp24__cat-paragraph"><?php echo ucfirst($sortBy).$score_string.': '.json_decode($value->$sortBy, true); ?></p>
        <p>Region: <?php echo get_region_by_state_name($value->state); ?></p>
        <p>Population: <?php echo  number_format($population); ?></p>
        <p>Med. Home Value: $<?php echo number_format($home_value); ?></p>
        </div>
    </div>
    <?php } //end foreach ?>
</div>
</div> <!-- results container -->