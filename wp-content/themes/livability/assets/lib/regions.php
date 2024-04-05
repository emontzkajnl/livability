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
        "Northeast" => array('Virginia', 'West Virginia', 'Vermont', 'Pennsylvania', 'Rhode Island', 'Ohio', 'New York', 'New Hampshire', 'New Jersey', 'Maine', 'Maryland', 'Massachusetts', 'Connecticut', 'Delaware'),
        "Midwest"   => array('Wisconsin', 'South Dakota', 'North Dakota', 'Missouri', 'Nebraska', 'Michigan', 'Minnesota', 'Kansas', 'Illinois', 'Indiana', 'Iowa') ,
        "Southwest"     => array('Washington, DC', 'Texas', 'Utah', 'Oklahoma', 'New Mexico', 'Nevada', 'Kentucky', 'Louisiana', 'Arizona', 'Arkansas', 'California', 'Colorado'),
        "Southeast"     => array('South Carolina', 'Tennessee', 'North Carolina', 'Mississippi', 'Florida', 'Georgia', 'Alabama'),
        "Northwest"      => array('Wyoming', 'Washington', 'Oregon', 'Montana', 'Hawaii', 'Idaho', 'Alaska')
    );
    foreach($us_regions as $key => $region) {
        if (in_array($state, $region)) {
            return $key;
        }
    }
}


