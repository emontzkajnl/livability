<?php 

$ID = get_the_ID(  ); 
$expire_date = do_shortcode( '[futureaction type=date dateformat="F j, Y"]');?>
<div class="sponsor-grid__card" >
    <a href="<?php echo get_the_permalink(); ?>">
        <div class="sp-img sponsor-grid__img" style="background-image: url(<?php echo get_the_post_thumbnail_url($ID, 'rel_article'); ?>); height: 200px; width: 100%;"></div>
        <div class="sma-title sponsor-grid__text-container">
            <?php echo '<h4 class="sponsor-grid__title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>';
            
            if ($expire_date) {
                echo '<p>Expires '.$expire_date.'</p>';
            } 
            // echo do_shortcode( '[futureaction type=date dateformat="F j, Y"]'); ?>
        </div>
    </a>
</div>