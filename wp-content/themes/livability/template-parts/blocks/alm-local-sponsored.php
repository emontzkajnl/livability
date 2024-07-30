<?php 
// same as local sponsored, but without sponsored articles being imported so we do the work here. 

$sponsored_args = array(
    'post_type'			=> 'post',
    'posts_per_page'	=> -1,
    'post_status'		=> 'publish',
    'meta_key'		=> 'sponsored',
    'meta_value'		=> true, 
);

$sponsored_query = get_posts($sponsored_args);

print_r($sponsored_args);