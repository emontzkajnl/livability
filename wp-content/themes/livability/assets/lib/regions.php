<?php 

// print_r($us_regions);
function get_region($state) {
    $us_regions = array(
        "Northeast" => array(239, 251, 253, 261, 271, 277, 262, 264, 270, 267, 240, 252, 278, 280),
        "Midwest"   => array(245, 246, 254, 281, 247, 248, 255, 257, 259, 266, 273),
        "Southwest"     => array(49928, 249, 236, 250, 268, 275, 237, 260, 276, 238, 235, 263),
        "Southeast"     => array(241,242,265, 272,274, 256, 233),
        "Northwest"      => array( 244, 258,  282, 234,  243, 269, 279)
    );
    foreach($us_regions as $key => $region) {
        if (in_array($state, $region)) {
            return $key;
        }
    }
}

function get_region_by_state_name($state) {
    $us_regions = array(
        "Northeast" => array( 'Virginia', 'Pennsylvania', 'Rhode Island', 'New York', 'New Hampshire', 'New Jersey', 'Maine', 'Maryland', 'Massachusetts', 'Connecticut', 'Delaware'),
        "Midwest"   => array('Wisconsin', 'South Dakota', 'North Dakota', 'Missouri', 'Ohio', 'Nebraska', 'Michigan', 'Minnesota', 'Kansas', 'Illinois', 'Indiana', 'Iowa') ,
        "Southwest"     => array( 'Texas', 'Oklahoma', 'New Mexico', 'Nevada',  'Arizona',  'California', 'Colorado'),
        "Southeast"     => array('Washington, DC', 'Arkansas', 'West Virginia', 'Vermont', 'South Carolina', 'Tennessee', 'Kentucky', 'Louisiana','North Carolina', 'Mississippi', 'Florida', 'Georgia', 'Alabama'),
        "Northwest"      => array('Wyoming', 'Washington', 'Oregon', 'Montana', 'Utah', 'Hawaii', 'Idaho', 'Alaska')
    );
    foreach($us_regions as $key => $region) {
        if (in_array($state, $region)) {
            return $key;
        }
    }
}


