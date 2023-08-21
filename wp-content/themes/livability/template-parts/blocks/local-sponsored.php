<?php 

    // print_r($args['sponsors']);
    // if no locale, use global sponsors, and add to all places

    if (!function_exists('get_pr_state')) {
        function get_pr_state($pr = []) {
            // echo '$pr is ';
            // print_r($pr);
            // echo '<br />';
            if (is_float($pr)) {
                $pr = [$pr];
            }
            $state = '';
            // print_r($pr);
            foreach ($pr as $p) {
                $prnt = get_post_parent($p);
                // echo 'parent: <br />';
                // print_r($prnt);
                if ($prnt && $prnt->ID > 0) {
                    $state = $prnt->ID;
                    break;
                } 
            }
         
            return $state;
        }
    }
    // print_r($args);
    $city_sponsors = $args['city'];
    $state_sponsors = $args['state'];
    $global_sponsors = $args['global'];
    $ID = get_the_ID();
    // echo 'global: ';
    // print_r($global_sponsors);
    // echo 'all_sponsors<br />';
    // print_r($city_sponsors);
    $locale = get_field('place_relationship');

    if ($locale):
        // echo 'begin locale state';
        $locale_state = get_pr_state($locale);
    // get same city sponsors
    // echo '$locale_state is '.get_the_title($locale_state).'<br />';
    $city_result = [];
    foreach ($city_sponsors as $cs) {
        if ($cs == $ID) {continue;}
        $cspr = get_field('place_relationship', $cs);
        if (is_array($cspr)) {
            $intersect = [];
            if (is_array($cspr)) {
                $intersect = array_intersect($cspr, $locale);
            }
            if ( count($intersect) >= 1) {
                $city_result[] = $cs;
            }
        }
    }

    // get same state sponsors
    $state_result = [];
    foreach ($state_sponsors as $ss) {
        if ($ss == $ID) {continue;} // ignore current article
        if ( !is_array($ss) ) {$ss = array($ss);} 
        $ss_state = get_pr_state($ss);
        if ($ss_state == $locale_state) {
            $state_result[] = $ss[0];
        }
    }
    
    echo '<div style="display: none;">';
    print_r($city_result);
    echo '<br />';
    print_r($state_result);
    echo '<br />';
    print_r($global_sponsors);
    echo '<br />';

    $local_sponsors = array_merge($city_result, $state_result, $global_sponsors);

    print_r($local_sponsors);
    echo '</div>';

    else: //end if $locale, is global
        $local_sponsors = $global_sponsors;

        // echo '<div style="display: none;">global: ';
        // print_r($local_sponsors);
        // echo '</div>';
        
    endif;

    //Resulting markup
    if (!empty($local_sponsors)) {
        // remove current post from results
        $self = array_search($ID, $local_sponsors);
        if ($self !== false) {
            unset($local_sponsors[$self]);
        }
        echo '<h2 class="wp-block-jci-blocks-section-header green-line">More To Read</h2>';
        if (count($local_sponsors) == 1): 
        $ls = $local_sponsors[0]; ?>
        <div class="one-hundred-list-container">
            <a href="" class="ohl-thumb">
                <div style="background-image: url(<?php echo get_the_post_thumbnail_url($ls, 'medium_large'); ?>);"></div>
            </a>
            <div class="ohl-text">
                <a href="<?php  echo get_the_permalink($ls ); ?>">
                <?php echo '<h2>'.get_the_title($ls).'</h2>'; 
                    echo get_the_excerpt($ls);
                ?>
                </a>
            </div>
        </div>
        <?php endif;

        if (count($local_sponsors) >= 2):
            if (count($local_sponsors) >= 4) {
                shuffle($local_sponsors);
            } ?>
        <div class="small-articles sponsor-read-more">

        <?php foreach ($local_sponsors as $key => $ls) { ?>
            <div class="small-article-card">
                <a href="<?php echo get_the_permalink($ls); ?>" class="sma-img">
                <!-- <div style="background-image: url(<?php //echo get_the_post_thumbnail_url( $ls,'rel_article' ); ?>); display: block;"></div> -->
                <?php echo get_the_post_thumbnail( $ls, 'rel_article' ); ?>
                </a>
                <div class="sma-title">
                    <h4><a href="<?php echo get_the_permalink($ls); ?>"><?php echo get_the_title($ls); ?></a></h4>
                </div>
        </div>
           
        <?php if ($key == 2) {return;} // limit to one row of three
        }
        echo '</div></div>';
        endif;
    }
    