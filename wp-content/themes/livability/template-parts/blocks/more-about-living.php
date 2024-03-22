<?php
$state = $args['state']; 
$abbv = $args['abbv']; ?>
<div class="more-about-living" id="more-about-living">
    <div class="container">
        <h2 class="more-about-living__title">More about Living in <?php echo $state; ?></h2>
        <div class="more-about-living__cards">
            <a href="<?php echo site_url().'/'.$abbv; ?>">
            <div class="more-about-living__card moving-to">
                <h3 class="h4">Moving to <?php echo $state; ?></h3>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/place-icons/hand-and-phone-with-map.svg" alt="">
            </div>
            </a>
            <!-- il/where-to-live-now/best-places-to-live-in-illinois/ -->
            <a href="<?php echo site_url().'/'.$abbv.'/where-to-live-now/best-places-to-live-in-'.$state; ?>">
            <div class="more-about-living__card best-places">
            <h3 class="h4">Best Places to Live in <?php echo $state; ?></h3>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/place-icons/map-with-pins.svg" alt="">
            </div>
            </a>
            <a href="<?php echo get_the_permalink(79896); ?>">
            <div class="more-about-living__card ultimate-guide">
                <h3 class="h4">Ultimate Guide to Finding Your Best Place to Live</h3>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/place-icons/woman-reaching-for-star.svg" alt="">
            </div>
            </a>
        </div>
    </div>
</div>