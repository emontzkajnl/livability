<?php
/**
 * 2023 top 100 coming soon hero section
 */

 $title = get_field('title');
 $timer_shortcode_id = get_field('shortcode_id');
 $img = get_field('image');


?>
<div class="t-100-landing-23" style="background-image: url(<?php echo wp_get_attachment_url( $img); ?>); background-size: cover; background-position-y: center; ">
<div class="t-100-23-text-area">
    <?php echo $title;

    echo do_shortcode( '[wpcdt-countdown id="150089"]');
    // echo $timer_shortcode_id ? do_shortcode( '[wpcdt-countdown id="'.$timer_shortcode_id.'"]' ) : '' ; ?>
</div>
</div>