<?php 

$tnpostargs = array(
    'numberposts'       => 3,
    'post_type'         => 'post',
    'tag'				=> 'fbitn',
    'orderby'           => 'rand',
);
$tnposts = get_posts($tnpostargs);
if ( !empty($tnposts)) {
    echo '<div class="tn-mym__block">';
    echo '<h2 class="wp-block-jci-blocks-section-header">Moving to Tennessee</h2>';
    echo '<p class="brand-stories__sponsor-text" style=" margin-bottom: 30px;">Sponsored by <a href="https://www.fbitn.com/">Farm Bureau Insurance of Tennessee</a></p>';
    echo '<div class="tn-mym">';
    foreach ($tnposts as $key => $value) {
        $ID = $value->ID;
        // $slidebkgrnd = get_the_post_thumbnail_url( $ID, 'rel_article' ); 
        echo '<div class="tn-mym__item"><a class="unstyle-link" href="'.get_the_permalink( $ID ).'">';
        echo '<div class="tn-mym__img-container object-fit-image">'.get_the_post_thumbnail( $ID, 'medium_large', ['style' => 'width: 100%; height: 100%;']).'</div>';
        echo '<h4 class="tn-mym__title">'.get_the_title($ID).'</h4>';
        echo '</a></div>';
    }
    echo '</div></div>'; // pwl-container
}