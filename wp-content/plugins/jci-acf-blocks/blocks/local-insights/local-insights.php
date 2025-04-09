<?php 
/**
 * For city pages. Adds local insights if any
 */

$blockId = $block['id'];
$id = 'local-insights-' . $blockId;
$className = 'local-inights';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

 $ID = get_the_ID();
 $city_state_name = get_the_title();
 $city_name = substr($city_state_name, 0, -4);
 $args = array(
     'post_type'        => 'local_insights',
     'posts_per_page'   => 3,
     'post_status'      => 'publish',
     'orderby'          => 'rand',
     'meta_query'        => array(
        array(
            'key'           => 'place',
            'value'         => '"' . $ID . '"',
            'compare'       => 'LIKE'
        ) 
    )
 );

 $li_query = new WP_Query($args);
 $total_posts = $li_query->found_posts;
 $count = 1;
 if ($li_query->have_posts()): ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="place-section-heading"><h2 class="location lpsh" style="text-align:center;">Live Like a Local</h2><span class="place-section-heading__line"></span></div>
    <span class="insights-title" style="display: block; margin-top: 30px;">Local residents give their insights into <?php echo  $city_state_name; ?></span>
    <?php while ($li_query->have_posts()): $li_query->the_post();
    $hide_class = $total_posts > 1 && $count < $total_posts ? ' insight-hidden' : '';
    $ID = get_the_ID();
    $f_name = get_field('first_name', $ID);
    $l_name = get_field('last_name', $ID);
    $company = get_field('company', $ID);
    $title = get_field('title', $ID);
    $slash = $company && $title ? ' / ' : '';
    $email = get_field('email', $ID);
    $contact_link = get_field('contact_link', $ID) ? '<a href="'.get_field('contact_link', $ID).'" class="insights-link" target="_blank">Website</a>' : '';
    $phone = get_field('phone', $ID) ? '<a class="insights-phone" href="tel:'.get_field('phone', $ID).'">'.get_field('phone', $ID).'</a>' : '' ;
    $q_opportunities = get_field('q_opportunities', $ID);
    $a_opportunities = get_field('a_opportunities', $ID);
    $q_area = get_field('q_area', $ID);
    $a_area = get_field('a_area', $ID);
    $q_local_vibe = get_field('q_local_vibe', $ID);
    $a_local_vibe = get_field('a_local_vibe', $ID);
     ?>
    <div class="insights <?php echo $hide_class; ?>">
        <!-- <div class="insights-heading clearfix"> -->
            <!-- <div> -->
                <div class="insights-img-container">
            <?php echo get_the_post_thumbnail( $ID, 'thumbnail', array('class' => 'insights-img')); ?>
            </div>
            <div class="insights-text-container">
              <h2 class="insights-title"><?php echo $f_name.' '.$l_name; ?></h2>  
              <span class="insights-name"><?php echo $title.$slash.$company; ?></span>
            <!-- </div> -->
            
        <!-- </div> -->
        <?php if ($a_opportunities): 
            // $q_opportun/ities = str_replace('CITY STATE', $city_state_name, $q_opportunities); ?>
            <h2 class="insights-q">Q. <?php echo $q_opportunities; ?></h2>
            <p class="insights-a"><span class="insights-q">A.</span> <?php echo $a_opportunities; ?></p>
        <?php endif; ?>
        <?php if ($a_area): 
            // $q_area = str_replace('CITY STATE', $city_state_name, $q_area); ?>
            <h2 class="insights-q">Q. <?php echo $q_area; ?></h2>
            <p class="insights-a"><span class="insights-q">A.</span> <?php echo $a_area; ?></p>
        <?php endif; ?>
        <?php if ($a_local_vibe): 
            // $q_local_vibe = str_replace('CITY STATE', $city_state_name, $q_local_vibe); ?>
            <h2 class="insights-q">Q. <?php echo $q_local_vibe; ?></h2>
            <p class="insights-a"><span class="insights-q">A.</span> <?php echo $a_local_vibe; ?></p>
        <?php endif; ?>
        <p class="insights-connect-with"><strong>Connect With <?php echo $f_name.':</strong> '.$phone.' '.$contact_link; ?> </p>
        </div>
    </div>
    <?php $count++;
    endwhile; ?>
    <p style="text-align: center;">See <a href="" alt="See content advertising opportunities">content advertising opportunities</a> for this page.</p>
    <?php echo '</div>';
endif;
wp_reset_postdata(  );
//echo 'city '.$_GET['city'];
?>
