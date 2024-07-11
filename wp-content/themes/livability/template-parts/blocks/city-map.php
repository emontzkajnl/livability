<?php 

$place_query = str_replace(',','',get_the_title());
$place_query = str_replace(' ','+', $place_query); 
if ($post->post_parent > 0):
?>
<div class="city-map-block">
    <iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&q=<?php echo $place_query; ?>+USA" allowfullscreen=""></iframe>
</div>
<?php endif; ?>