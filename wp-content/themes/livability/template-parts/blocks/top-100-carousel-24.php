<?php 
global $wpdb;
$sortBy = isset( $_SESSION['bp23_cat'] ) ? $_SESSION['bp23_cat'] : 'livscore';
$results = $wpdb->get_results( "SELECT * FROM 2024_top_100 ORDER BY ".$sortBy, OBJECT );
// session_start();
// get array position of current post
$currentPos = '';
foreach ($results as $key => $value) {
    if ($results[$key]->place_id == get_the_ID()) {
        // echo 'post is '.$results[$key]->city;
        $currentPos = $key;
    }
}
if ($currentPos): // if this is a top 100 
// split array and merge
$row2 = array_splice($results, $currentPos);
$row1 = array_splice($results, 0, $currentPos);
$merged = array_merge($row2, $row1);

// loop through array and display posts
?>
<div class="carousel-blocks-container-24">


<div class="carousel-24-left" >
<?php if (isset( $_SESSION['bp23_cat']) { ?>
<h3>Continue Browsing <?php echo ucfirst($sortBy); ?> in the Top 100 Best Places to Live in the U.S.</h3>
<?php } else {
    echo '<h3>Browse the Top 100  Best Places to Live in the U.S.</h3>';
} ?>
<div class="list-carousel-container top-100-24">
    <ul class="wp-block-jci_blocks-blocks list-carousel">
        <?php foreach ($merged as $key => $value) {
            $ID = $merged[$key]->place_id; ?>
        <li class="lc-slide">
            <a href="<?php echo get_the_permalink($ID); ?>">
                <div class="lc-slide-inner">
                    <div class="lc-slide-content">
                        <div class="lc-slide-img" style="background-image: url('<?php echo get_the_post_thumbnail_url($ID, 'thumbnail'); ?>');"></div>
                    <div>
                        <h4 class="city-state"><?php echo get_the_title($ID); ?></h4>
                        <p class="cat-score <?php echo $sortBy; ?>"><?php echo $merged[$key]->$sortBy; ?></p>
                    </div>
                    </div>

                </div>
            </a>
        </li>
        <?php } ?>
    </ul>

</div>
<a href="<?php echo site_url( '/best-places/2024-top-100-best-places-to-live-in-the-us/' ); ?>"><button class="bp2l__green-btn"  >Return to Main List</button></a>
</div>
<div class="carousel-24-right">
    <h3>Explore All Top 100 Best Places Cities </h3>
    <img class="open-top-100-24-map" src="<?php echo get_stylesheet_directory_uri(  ); ?>/assets/images/top-100-map-screenshot.png" alt="">
    <button class="bp2l__green-btn open-top-100-24-map"  >Top 100 Cities Map</button>
</div>
</div>  <!-- .carousel-blocks-container -->
<!-- <script>
    const top100_24btn = document.querySelectorAll('.open-top-100-24-map');
  const top100_24closebtn = document.querySelector('.map-popup-close');
  const top100_24_popup = document.querySelector('.top-100-popup-container');

  
    top100_24btn.forEach(btn => {
      btn.addEventListener("click", () => {
        top100_24_popup.classList.add('open-top-100-popup');
      });
    });
    
  
    top100_24closebtn.addEventListener("click", () => {
      top100_24_popup.classList.remove('open-top-100-popup');
    });
  
</script> -->
<?php   endif; // if this is a top 100 
