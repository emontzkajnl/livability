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
                <div class="insights-header">
                <div class="insights-img-container">
            <?php echo get_the_post_thumbnail( $ID, 'thumbnail', array('class' => 'insights-img')); ?>
            </div> <!--img-container -->
            <div>
              <h2 class="insights-title"><?php echo $f_name.' '.$l_name; ?></h2>  
              <span class="insights-name"><?php echo $title.$slash.$company; ?></span>
            </div>
            </div><!--insights header -->
            
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
        <div class="insights-contact-section">
            <p class="insights-connect-with"><strong>Connect With <?php echo $f_name;?>:</strong></p>
            <div class="insights-contact-lower-section">
            <p style="margin-top: 0"><?php echo $phone.' '.$contact_link; ?></p>
       <?php $rows = get_field('social_profile', $ID); // social_platform, social_url
       if( $rows ) {
       
           echo '<ul class="insights-social">';
           foreach( $rows as $row ) {
               $platform = $row['social_platform'];
               $url = $row['social_url'];
               switch ($platform) {
                case 'facebook':
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#164988" d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"/></svg>';
                    break;
                case 'instagram':
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#164988" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>';
                    break;
                case 'twitter':
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#164988" d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>';
                    break;
                case 'linkedin':
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#164988" d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>';
                    break;               
                default:
                    $svg = null;
                    break;
               }
               echo '<li><a href="'.$url.'">'.$svg.'</a></li>';
           }
           echo '</ul>';
       }
        
        ?> 
        </div> <!--insights-contact-lower-section -->
        </div> <!--insights-contact-section -->
    </div>
    <?php $count++;
    endwhile; ?>
    <p style="text-align: center;">See <a href="" alt="See content advertising opportunities">content advertising opportunities</a> for this page.</p>
    <?php  echo '</div>'; // local-inights
endif;
wp_reset_postdata(  );
//echo 'city '.$_GET['city'];
?>
