<?php 
include_once( get_stylesheet_directory() .'/assets/lib/regions.php');
include_once( get_stylesheet_directory() .'/assets/lib/meta_key_display.php');
if (isset($_COOKIE['bp23_cat'])) {
    $meta_key = $_COOKIE['bp23_cat'];
} else {
    $meta_key = 'ls_livscore';
}


    $bp_args = array(
        'post_type'         => 'best_places',
        'post_status'       => 'publish',
        'tax_query'			=> array(
            array(
                'taxonomy'	=> 'best_places_years',
                'field'		=> 'slug',
                'terms'		=> '2023'
            )
        ),
        'orderby'           => 'meta_value_num',
        'meta_key'          => $meta_key,
        'order'             => 'DESC',
        'posts_per_page'    => 20,
        'paged'             => 1
    );
    $bp_posts = get_posts($bp_args);
?>
<h3 id="top-one-hundred-list">Explore Our Best Places to Live in the U.S.</h3>
<h5 style="margin-bottom: 10px;">Sort the list by these categories.</h5>
<ul class="bp23-category-btns" >
    <li class="bp23-category-btn" class="liv-score" ><div data-cat="ls_livscore" class="livscore  <?php echo $meta_key == 'ls_livscore' ? 'active' : ''; ?>">Liv Score</div></li>
    <li class="bp23-category-btn" class="amenities" ><div data-cat="amenities" class="amenities <?php echo $meta_key == 'amenities' ? 'active' : ''; ?>">Amenities</div></li>
    <li class="bp23-category-btn" class="economy" ><div data-cat="ls_economy" class="economy <?php echo $meta_key == 'ls_economy' ? 'active' : ''; ?>">Economy</div></li>
    <li class="bp23-category-btn" class="education"><div data-cat="ls_education"  class="education <?php echo $meta_key == 'ls_education' ? 'active' : ''; ?>">Education</div></li>
    <li class="bp23-category-btn" class="environment"><div data-cat="ls_environment"  class="environment <?php echo $meta_key == 'ls_environment' ? 'active' : ''; ?>">Environment</div></li>
    <li class="bp23-category-btn" class="health-care"><div  data-cat="ls_health" class="health-care <?php echo $meta_key == 'ls_health' ? 'active' : ''; ?>">Health</div></li>
    <li class="bp23-category-btn" class="housing"><div  data-cat="ls_housing" class="housing <?php echo $meta_key == 'ls_housing' ? 'active' : ''; ?>">Housing & Cost of Living</div></li>
    <li class="bp23-category-btn" class="safety"><div  data-cat="ls_safety" class="safety <?php echo $meta_key == 'ls_safety' ? 'active' : ''; ?>">Safety</div></li>
    <li class="bp23-category-btn" class="transportation"><div  data-cat="ls_transportation" class="transportation <?php echo $meta_key == 'ls_transportation' ? 'active' : ''; ?>">Transportation</div></li>
</ul>
<h5 style="margin-bottom: 10px;">Filter the list by region, population or median home value.</h5>

<label style="display: none;" for="region">Geographical Region:</label>
<select class="bp23-filter" name="region" id="region">
    <option value="">Geographical Region:</option>
    <option value="northeast">Northeast</option>
    <option value="northwest">Northwest</option>
    <option value="midwest">Midwest</option>
    <option value="southwest">Southwest</option>
    <option value="southeast">Southeast</option>
</select>
<label style="display: none;" f for="population">Population:</label>
<select class="bp23-filter" name="population" id="population">
    <option value="">Population:</option>
    <option value="0-100000">Population: 75,000-100,000</option>
    <option value="100000-150000">Population: 100,000-150,000</option>
    <option value="150000-200000">Population: 150,000-200,000</option>
    <option value="200000-300000">Population: 200,000-300,000</option>
    <option value="300000-400000">Population: 300,000-400,000</option>
    <option value="400000-999999">Population: 400,000-500,000</option>
    
</select>
<label style="display: none;"  for="home_value">Median Home Value:</label>
<select class="bp23-filter" name="home_value" id="home_value">
    <option value="">Median Home Value:</option>
    <option value="0-200000">Median Home Value: 0-$200,000</option>
    <option value="200000-300000">Median Home Value: $200,000-$300,000</option>
    <option value="300000-400000">Median Home Value: $300,000-$400,000</option>
    <option value="400000-500000">Median Home Value: $400,000-$500,000</option>
</select>
<span class="reset-filter">Reset Filters</span>

<div class="bp23-results bp-row">
    <?php foreach($bp_posts as $bp){ 
        $places = get_field('place_relationship',$bp->ID);
         foreach($places as $p) {
            if (get_field('place_type', $p) == 'state') {
                $state = $p;
            } else {
                $city = $p;
            }
        }
        $cityPopulation = intval(str_replace(',','', get_field('city_population', $city))); 
        $cityHomeValue = str_replace(array(',','$'),'',get_field('city_home_value', $city)); 
        $cityTitle = get_the_title($city);
        $score_text = $meta_key == 'ls_livscore' ? '' : ' Score';
        ?>
    <div class="bp23-card">
        <?php echo '<a class="bp23-img" href="'.get_the_permalink( $bp->ID ).'">'.get_the_post_thumbnail( $bp->ID, 'rel_article').'</a>'; ?>
        <div class="bp23-card-text">
        <h3  class="h4"><a href="<?php echo get_the_permalink( $bp->ID ); ?>"><?php echo $cityTitle; ?></a></h3>
        <p class="meta-sort <?php echo strtolower(str_replace('-', '', display_meta_key($meta_key))); ?>"><strong><?php echo ucfirst(display_meta_key($meta_key)).$score_text.': '.get_field($meta_key, $bp->ID); ?></strong></p>
        <p><?php echo 'Region: '.get_region($state); ?></p>
        <p>Population: <?php echo number_format($cityPopulation); ?></p>
        <p>Med. Home Value: $<?php echo number_format($cityHomeValue); ?></p>
        <p><?php //echo 'key is '.$key; ?></p>
    </div>
    <p class="read-more"><a href="<?php echo get_the_permalink( $bp->ID); ?>">Read More</a></p>
    </div>
<?php }
echo '<div style="width: 100%; text-align: center;">';
get_template_part( 'template-parts/blocks/ad-three'); 
echo '</div>';
?>

</div>

<!-- Alabama: 233
Alaska: 234
Arizona: 235
Arkansas: 236
California: 237
Colorado: 238
Connecticut: 239
Delaware: 240
Florida: 241
Georgia: 242
Hawaii: 243
Idaho: 244
Illinois: 245
Indiana: 246
Iowa: 247
Kansas: 248
Kentucky: 249
Louisiana: 250
Maine: 251
Maryland: 252
Massachusetts: 253
Michigan: 254
Minnesota: 255
Mississippi: 256
Missouri: 257
Montana: 258
Nebraska: 259
Nevada: 260
New Hampshire: 261
New Jersey: 262
New Mexico: 263
New York: 264
North Carolina: 265
North Dakota: 266
Ohio: 267
Oklahoma: 268
Oregon: 269
Pennsylvania: 270
Rhode Island: 271
South Carolina: 272
South Dakota: 273
Tennessee: 274
Texas: 275
Utah: 276
Vermont: 277
Virginia: 278
Washington: 279
Wyoming: 282 -->

<!-- Northeast: (239, 251, 253, 261, 271, 277, 262, 264, 270)
Midwest:  (245, 246, 254, 267, 281, 247, 248, 255, 257, 259, 266, 273)
South: (240, 241, 242, 252, 265, 272, 278, 49928, 280, 233, 249, 256, 274, 236, 250, 268, 275)
West: (235, 238, 244, 258, 260, 263, 276, 282, 234, 237, 243, 269, 279) -->