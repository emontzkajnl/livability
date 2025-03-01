<?php 
// if ($post->post_parent > 0){
    $place_query = str_replace(',','',get_the_title());
    $place_query = str_replace(' ','+', $place_query); 
    echo '<div class="city-map-block">';
    if (get_field('city_map_options')) {

    $cmo = get_field('city_map_options');
    $option = $cmo['options']; // title, coordinates, proxi_url, hide
    $zoom = $cmo['set_zoom_level'] ? '&zoom='.$cmo['zoom_level']: '';
    
    switch ($option) {
        case 'title': 
                echo '<iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&q='.$place_query.'+USA'.$zoom.'" allowfullscreen=""></iframe>';
            break;
        case 'coordinates':
            $lat = $cmo['latitude'];
            $long = $cmo['longitude'];
            echo '<iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&q='.$place_query.'&center='.$lat.','.$long.$zoom.'" allowfullscreen=""></iframe>';
            break;
        case 'proxi':
            $proxi = $cmo['proxi_url'];
            // change $zoom according to if url has params or not
            if ($zoom) {
                $components = parse_url($proxi);
                parse_str($components['query'], $params);
                if (count($params) > 0) {
                    $zoom = '&zoom='.$cmo['zoom_level'];
                } else {
                    $zoom = '?zoom='.$cmo['zoom_level'];
                }
            }
            // print_r(parse_url('google.com?key1=one&key2=two', PHP_URL_QUERY));
            echo '<iframe src="'.$proxi.$zoom.'" allow="geolocation; clipboard-write" width="100%" height="625px" style="border-width: 0px;" allowfullscreen></iframe>';
            break;
    
        default:
            // hide map
            break;
    }
    ?>
    <?php } else { // no city map options 
        echo '<iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&q='.$place_query.'+USA'.$zoom.'" allowfullscreen=""></iframe>';
    }
    echo '</div>';
// }
?>