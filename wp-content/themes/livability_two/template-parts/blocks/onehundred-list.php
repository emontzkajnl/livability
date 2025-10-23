<?php $currentID = get_the_ID();
            $args = array( 
                'post_type'     => 'best_places',
                'posts_per_page'=> 10,
                'post_status'   => 'publish',
                'orderby'    => 'meta_value_num',
                'meta_key'  => 'bp_rank',
                'order'     => 'ASC',
                'post_parent'   => $currentID, 
                // 'post__in'  => $child_array,
                'paged'     => 1
            ); ?>
             <script>
            window.ohlObj = {};
            Object.assign(window.ohlObj, {current_page: '2'});
            Object.assign(window.ohlObj, {parent: <?php echo $currentID; ?>});
            // Object.assign(window.ohlObj, {children: <?php //echo json_encode($child_array); ?>});
            </script>
            <?php 
            $ohl_query = new WP_Query($args);
            if ($ohl_query->have_posts()): ?>
                <ul class="onehundred-container">
                <?php while ($ohl_query->have_posts()): $ohl_query->the_post();
                $ID = get_the_ID();
                $place = get_field('place_relationship');
                $population = '';
                if ($place) {
                    $population = get_field('city_population', $place[0]);
                } ?>
                <li class="one-hundred-list-item">
                    <div class="one-hundred-list-container">
                       <a href="<?php echo get_the_permalink(); ?>" class="ohl-thumb" style="background-image: url(<?php //echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>);">
                       <p class="green-circle with-border"><?php echo get_field('bp_rank'); ?></p>
                        </a> 
                        <div class="ohl-text">
                            <a href="<?php echo get_the_permalink(); ?>">
                            <h2><?php echo get_the_title(); ?></h2>
                            <h3 class="uppercase">
                                Livscore: <?php echo get_field('ls_livscore'); 
                                if ($population) {
                                    echo ' | Population: '.$population;
                                } ?>
                            </h3>
                            <p><?php echo get_the_excerpt( ); ?></p></a>
                        
                        </div>
                    </div>
                </li>

                <?php endwhile; wp_reset_postdata(); ?>
                </ul>
                <div class="waypoint-target"></div>
            <?php endif; ?>