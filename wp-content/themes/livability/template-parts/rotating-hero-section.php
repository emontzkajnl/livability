<?php 

if (get_field('hero_images')) {
    $hero_images = get_field('hero_images');
    if (count($hero_images) > 1) {
        $num = rand(0, count($hero_images) - 1);
        $img = $hero_images[$num]['hero_image'];
    } else {
        $img = $hero_images[0]['hero_image'];
    }
// print_r($img); // ID, 
    echo '<div class="home-hero-section">';
    echo wp_get_attachment_image( $img['ID'], '1536x1536' ); 
    echo '<div class="container">';
    if (get_field('hero_text')) {
        echo '<div class="home-hero-text">'.get_field('hero_text').'</div>';
    }
    echo '</div>';
    echo '</div>';
}