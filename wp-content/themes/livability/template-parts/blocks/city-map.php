<?php 
if ($post->post_parent > 0){
    if (get_field('city_map_options')) {
    $place_query = str_replace(',','',get_the_title());
    $place_query = str_replace(' ','+', $place_query); 
    $cmo = get_field('city_map_options');
    $option = $cmo['options']; // title, coordinates, proxi, hide
    $zoom = $cmo['set_zoom_level'] ? '&zoom='.$cmo['zoom_level']: '';

    ?>
    <div class="city-map-block">
        <iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&q=<?php echo $place_query; ?>+USA<?php echo $zoom; ?>" allowfullscreen=""></iframe>
    </div>
    <?php } else { ?>
         
    <div class="city-map-block">
        <iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&q=<?php echo $place_query; ?>+USA" allowfullscreen=""></iframe>
    </div>

    <?php }
}
?>