<?php 
/**
 * For Connected Communities page. Query all cc articles to get array of states and list them. 
 */

 include_once(get_stylesheet_directory().'/assets/lib/state_abbv.php');

$args = array(
    'post_type'         => 'post',
    'category_name'     => 'connected-communities',
    'posts_per_page'    => -1,
    'post_status'       => 'publish',
    'meta_query'        => array(
        array(
            'key'       => 'global_article',
            'value'     => '0',
            'compare'   => 'LIKE'
        )
    )
);
$cities = get_posts($args);
$state_array = [];
// print_r($cities[0]);

if ($cities) {
    $state_array = [];
    foreach ($cities as $city) {
        // echo '<br />city is '.$city->post_title;
        $pr = get_post_meta($city->ID, 'place_relationship');
        $pr_id = $pr[0][0];
        if ($pr_id) {
            // echo '<br />place type: '.get_post_meta($pr_id, 'place_type')[0].' state code: '.get_post_meta($pr_id, 'state_code')[0];
            $state = get_post_meta($pr_id, 'state_code')[0];
            // $state_array[] = $state;
            if (!in_array($state, $state_array)) {
                $state_array[] = $state;
            }
        } 
    }
    // echo 'states: ';
    // print_r($state_array);
    // $state_array = sort($state_array);
    sort($state_array);
    $site_url =  get_site_url(); ?>
    <div class="state-cc-list">
        <h2 class="green-line">Connected Communities by State</h2>
        <ul class="cc-list">
        <?php foreach($state_array as $key => $st) {
            // echo 'st is '.$st;
            echo '<li><a href="'.$site_url.'/'.$st.'/connected-communities">'.ucwords(strtolower($us_state_abbrevs_names[$st])).'</a></li>';
        } ?>
        </ul>
    </div>
    
    
<?php } 