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
     'posts_per_page'   => 10, // likely more than necessary
     'post_status'      => 'publish',
     'meta_query'        => array(
        array(
            'key'           => 'place',
            'value'         => '"' . $ID . '"',
            'compare'       => 'LIKE'
        ) 
    )
 );

 $li_query = new WP_Query($args);

 if ($li_query->have_posts()): ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <h2 class="insights-title green-line" style="text-align:center;">Local Insights</h2>
    <span class="insights-title" style="text-align:center; font-style:italic; display: block; margin-top: 0;"><?php echo $city_name; ?> insights by local patrons</span>
    <?php while ($li_query->have_posts()): $li_query->the_post();
    $ID = get_the_ID();
    
    $f_name = get_field('first_name', $ID);
    $l_name = get_field('last_name', $ID);
    $company = get_field('company', $ID);
    $title = get_field('title', $ID);
    $email = get_field('email', $ID);
    $contact_link = get_field('contact_link', $ID);
    $phone = get_field('phone', $ID);
    $q_opportunities = get_field('q_opportunities', $ID);
    $a_opportunities = get_field('a_opportunities', $ID);
    $q_area = get_field('q_area', $ID);
    $a_area = get_field('a_area', $ID);
    $q_local_vibe = get_field('q_local_vibe', $ID);
    $a_local_vibe = get_field('a_local_vibe', $ID);
     ?>
    <div class="insights shadow">
        <!-- <div class="insights-heading clearfix"> -->
            <!-- <div> -->
            <?php echo get_the_post_thumbnail( $ID, 'thumbnail', array('class' => 'insights-img')); ?>
              <h2 class="insights-title"><?php echo $f_name.' '.$l_name; ?></h2>  
              <span class="insights-name"><?php echo $title; ?></span>
              <span class="insights-company"><?php echo $company; ?></span>
              <a href="<?php echo $contact_link; ?>" class="insights-link">Website</a>
            <!-- </div> -->
            
        <!-- </div> -->
        <?php if ($a_opportunities): 
            $q_opportunities = str_replace('CITY STATE', $city_state_name, $q_opportunities); ?>
            <span class="insights-cat">Opportunities</span>
            <h2 class="insights-q"><?php echo $q_opportunities; ?></h2>
            <p class="insights-a"><?php echo $a_opportunities; ?></p>
        <?php endif; ?>
        <?php if ($a_area): 
            $q_area = str_replace('CITY STATE', $city_state_name, $q_area); ?>
            <span class="insights-cat">Local Area</span>
            <h2 class="insights-q"><?php echo $q_area; ?></h2>
            <p class="insights-a"><?php echo $a_area; ?></p>
        <?php endif; ?>
        <?php if ($a_local_vibe): 
            $q_local_vibe = str_replace('CITY STATE', $city_state_name, $q_local_vibe); ?>
            <span class="insights-cat">Local Vibe</span>
            <h2 class="insights-q"><?php echo $q_local_vibe; ?></h2>
            <p class="insights-a"><?php echo $a_local_vibe; ?></p>
        <?php endif; ?>
        <a href="<?php echo $contact_link; ?>"><button class="insights-button">Connect With <?php echo $f_name.' '.$l_name; ?></button></a>
    </div>
    <?php endwhile; ?>
    <p style="text-align: center;">See <a href="" alt="See content advertising opportunities">content advertising opportunities</a> for this page.</p>
    <?php echo '</div>';
endif;
wp_reset_postdata(  );
echo 'city '.$_GET['city'];
?>
