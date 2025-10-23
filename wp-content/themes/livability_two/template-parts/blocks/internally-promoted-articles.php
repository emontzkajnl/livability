<?php $high_traffic_articles = get_field('high_traffic_articles', 'options'); 
    if ($high_traffic_articles) {
        foreach($high_traffic_articles as $hta) {
            if (get_the_ID() == $hta['ht_article']) {
            //    echo 'id matches';
                $promoted = get_posts(array(
                    'post_type'      => array('liv_place', 'post'),
                    'numberposts'    => 1,
                    'orderby'        => 'rand',
                    'meta_key'       => 'promote',
                    'meta_value'     => 1
                ));
            //    print_r($promoted);
            if ($promoted) {
                $p = $promoted[0];
                echo '<div class="promoted-block">';
                echo '<h4 class="promoted-block__title">Also Check Out:</h3>';
                echo '<a href="'.get_the_permalink($p->ID).'">'.get_the_post_thumbnail( $p->ID, 'medium' ).'</a>';
                echo '<p><a href="'.get_the_permalink($p->ID).'">'.get_the_title($p->ID).'</a></p>';
                echo '</div>';

            }
            break;
            }
        }
    } ?>