<?php 
// same as local sponsored, but without sponsored articles being imported so we do the work here. 

$is_global = get_field('global_article');

$place_array = get_field('place_relationship');
if ( !is_array($place_array) ) {$place_array = array($place_array);} 
echo 'place array<br />';
print_r($place_array);

$sponsored_args = array(
    'post_type'			=> 'post',
    'posts_per_page'	=> -1,
    'post_status'		=> 'publish',
    'meta_key'		=> 'sponsored',
    'meta_value'		=> true, 
);

$sponsored_query = get_posts($sponsored_args);
$result = [];
foreach ($sponsored_query as $key => $value) {
    // echo 'key: '.$key.'<br />';
    print_r($value->ID);
    // $spnsrd_places = get_field('place_relationship', $value->ID);h
    echo 'spnsrd place<br/>';
    print_r($spnsrd_places, true);
    
    // print_r($place_array, true);
    if (in_array($spnsrd_places, $place_array)) {
        $result[] = $value->ID;
    }
}
echo 'result is ';
print_r($result, true);
