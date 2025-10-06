<?php 

$ID = get_the_ID(  ); 
$status = get_post_status();
$place = get_field('place_relationship');
$sponsor_name = get_field('sponsor_name');
$sponsor_url = get_field('sponsor_url') ? get_field('sponsor_url') : '' ;
?>
<div class="sponsor-grid__card" >
    <a href="<?php echo get_the_permalink(); ?>">
        <div class="sp-img sponsor-grid__img" style="background-image: url(<?php echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>); height: 200px; width: 100%;"></div>
        <div class="sma-title sponsor-grid__text-container">
            <?php echo '<h4 class="sponsor-grid__title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>';
            echo '<p>'.get_the_title($place[0]).'</p>';
            $expire_date = do_shortcode( '[futureaction type=date dateformat="F j, Y"]');
            echo '<p>Status: '.$status.'</p>';
            echo '<p>Published '.get_the_date('F j, Y').'</p>';
            if ($expire_date) {
                echo '<p>Expires '.$expire_date.'</p>';
            } 
            if ($sponsor_name) {
                echo '<p>Sponsor: <a href="'.$sponsor_url.'" target="_blank">'.$sponsor_name.'</a></p>';
            }
?>
        </div>
    </a>
</div>