<?php 
$ID = get_the_ID(  );
$livscore = get_post_meta($ID, 'ls_livscore', true);
$civic = get_post_meta($ID, 'ls_civic', true);
$demographics = get_post_meta($ID, 'ls_demographics', true);
$economy = get_post_meta($ID, 'ls_economy', true);
$education = get_post_meta($ID, 'ls_education', true);
$health = get_post_meta($ID, 'ls_health', true);
$housing = get_post_meta($ID, 'ls_housing', true);
$infrastructure = get_post_meta($ID, 'ls_infrastructure', true); 
$amenities = get_post_meta($ID, 'amenities', true);
$how_we_calculate_link = get_the_permalink( '92097' ); ?>


<div class="bp-data-container twenty-two">
    <!-- <div class="bp-data-image"></div> -->
    
    
    <?php
    if (has_post_thumbnail(  )){
        $thumbId = get_post_thumbnail_id(  );
        $img_byline = get_field('img_byline', $thumbId);
        $img_place_name = get_field('img_place_name', $thumbId);
        if ($img_byline || $img_place_name) {
            echo '<div class="img-container">';
            echo get_the_post_thumbnail($ID, 'medium_large');
            echo '<div class="livability-image-meta">';
            $html = $img_place_name ? $img_place_name : '' ;
            $html .= $img_place_name && $img_byline ? ' / ' : '';
            $html .= $img_byline ?  strip_tags($img_byline, "<a>") : '' ;
            echo $html.'</div></div>';
        } else {
            echo get_the_post_thumbnail($ID, 'medium_large');
        }
        
    } 

    
    if ($livscore): ?>

        <table class="livscore-table first">
            <tr>
                <th><span class="livscore"><?php echo get_the_title().'<br />Quality of Life<br />Liv Score'; ?></span><br /><span class="livscore-number"><?php echo $livscore; ?></span><br /><a href="<?php echo  $how_we_calculate_link; ?>" class="livscore-link">How We Calculate Our Data</th>
            </tr>
        </table>

        <table class="livscore-table">
            <tr>
                <td>Civics</td>
                <td><?php echo $civic; ?></td>
            </tr>
            <tr>
                <td>Demographics</td>
                <td><?php echo $demographics; ?></td>
            </tr>
            <tr>
                <td>Economy</td>
                <td><?php echo $economy; ?></td>
            </tr>
            <tr>
                <td>Education</td>
                <td><?php echo $education; ?></td>
            </tr>
        </table>
    
        <table class="livscore-table">
            <tr>
                <td>Health</td>
                <td><?php echo $health; ?></td>
            </tr>
            <tr>
                <td>Housing</td>
                <td><?php echo $housing; ?></td>
            </tr>
            <tr>
                <td>Infrastructure</td>
                <td><?php echo $infrastructure; ?></td>
            </tr> 
            <tr>
                <td>Amenities</td>
                <td><?php echo $amenities; ?></td>
            </tr> 
        </table>
    
    <?php endif; ?>
</div>