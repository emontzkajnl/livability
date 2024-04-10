<?php 

// post, sponsored, place relationship includes id if city, pr 

$city_name = $args['city'];
$state_name = $args['state'];
$ID = get_the_ID();

$args = array(
    'numberposts'         => 'post', 
    'post_status'       => 'publish',
    'posts_per_page'    => -1,
    'orderby'           => 'rand',
    'meta_query'        => array(
        array(
            'key'           => 'place_relationship',
            'value'         => '"' . get_the_ID() . '"',
            'compare'       => 'LIKE'
        ),
        array(
            'key'           => 'sponsored',
            'value'         => 1
        ) 
    )
); 
$sponsors = get_posts($args);

if ($sponsors):
    $count = count($sponsors);
    $heading = $city_name.', '.$state_name.' Businesses & Brands to Know'; 
    echo '<div class="brand-stories"><h2 class="wp-block-jci-blocks-section-header " style="margin-bottom: 30px;">'.$heading.'</h2>';
    if ($count == 1){
        echo '<div class="brand-stories__one">';
        foreach ($sponsors as $s) { 
        $sponsor_name = get_field('sponsor_name', $s); 
        $sponsor_url = get_field('sponsor_url', $s);  ?>
        <div class="brand-stories__card">
            <a href="<?php echo get_the_permalink($s); ?>" class="brand-stories__img">
            <!-- <div style="background-image: url(<?php //echo get_the_post_thumbnail_url( $s,'rel_article' ); ?>); display: block;"></div> -->
            <?php echo get_the_post_thumbnail( $s, 'rel_article', array('style' => 'height: auto;') ); ?>
            </a>
            <div class="brand-stories__one-container">
            <div class="brand-stories__title">
                <h4><a href="<?php echo get_the_permalink($s); ?>"><?php echo get_the_title($s); ?></a></h4>
            </div>
            <p class="brand-stories__sponsor-text"><a href="<?php echo $sponsor_url ?>" target="_blank">Sponsored by <?php echo $sponsor_name ?></a></p>
            </div>
        </div>
        <?php }
        echo '</div>';
   
    } elseif ($count == 2) {
        echo '<div class="brand-stories__two">';
        foreach ($sponsors as $s) { 
        $sponsor_name = get_field('sponsor_name', $s); 
        $sponsor_url = get_field('sponsor_url', $s);  ?>
        <div class="brand-stories__card">
            <a href="<?php echo get_the_permalink($s); ?>" class="brand-stories__img">
            <!-- <div style="background-image: url(<?php //echo get_the_post_thumbnail_url( $s,'rel_article' ); ?>); display: block;"></div> -->
            <?php echo get_the_post_thumbnail( $s, 'rel_article', array('style' => 'height: auto;') ); ?>
            </a>
            <div class="brand-stories__title">
                <h4><a href="<?php echo get_the_permalink($s); ?>"><?php echo get_the_title($s); ?></a></h4>
            </div>
            <p class="brand-stories__sponsor-text"><a href="<?php echo $sponsor_url ?>" target="_blank">Sponsored by <?php echo $sponsor_name ?></a></p>
        </div>
        <?php }
        echo '</div>';
    
    } else {
        echo '<div class="pwl-slick">';
        foreach ($sponsors as $s) { 
        $sponsor_name = get_field('sponsor_name', $s); 
        $sponsor_url = get_field('sponsor_url', $s);  ?>
        <div class="brand-stories__card">
            <a href="<?php echo get_the_permalink($s); ?>" class="brand-stories__img">
            <!-- <div style="background-image: url(<?php //echo get_the_post_thumbnail_url( $s,'rel_article' ); ?>); display: block;"></div> -->
            <?php echo get_the_post_thumbnail( $s, 'rel_article' ); ?>
            </a>
            <div class="brand-stories__title">
                <h4><a href="<?php echo get_the_permalink($s); ?>"><?php echo get_the_title($s); ?></a></h4>
            </div>
            <p class="brand-stories__sponsor-text"><a href="<?php echo $sponsor_url ?>" target="_blank">Sponsored by <?php echo $sponsor_name ?></a></p>
        </div>
        <?php }
        echo '</div>';
    }
    echo '</div>';
endif;