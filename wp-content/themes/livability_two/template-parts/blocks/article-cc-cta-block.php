<?php 
require(get_stylesheet_directory(  ).'/assets/lib/state_abbv.php'); 
/**
 * Connected Community CTA for articles
 * if state, use non-client text
 */

$sponsor_name = get_field('sponsor_name');
$place = get_field('place_relationship');
$parent = get_post_parent($place[0]);
$sponsor_name = get_field('sponsor_name');

 
echo '<div class="cc-cta-container"><div class="cc-cta__left-section">';
if ($parent->ID > 0) { 
    //[city], [st] (link to city page) is a certified 
    // Connected Community (link to global article) 
    // which offers high speed fiber internet access. ?>

    <p><a href="<?php echo get_the_permalink($place[0]); ?>"><?php echo get_the_title($place[0]); ?></a> is a certified <a href="<?php echo site_url().'/connected-communities'; ?>">Connected Community</a>
    which offers high speed fiber internet access.</p>
<?php } else {
    $non_liv_city_name = get_field('non-livability_connected_community'); 
    $state = get_the_title($place);
    // [city], [st] (link to state connected communities page) is a 
    // certified Connected Community (link to global article) 
    // which offers high speed fiber internet access.
    //$us_state_abbrevs_names[$stateabbv] ?>
    <p><a href="<?php echo get_the_permalink($place[0]).'/connected-communities'; ?>"><?php echo $non_liv_city_name.', '.$us_state_abbrevs_names[$state]; ?></a> is a certified <a href="">Connected Community</a>
    which offers high speed fiber internet access.</p>
<?php } ?>
</div>
<div class="cc-cta__right-section">
    <img src="<?php echo get_stylesheet_directory_uri(  ).'/assets/images/cc-icon-w-bar.svg'; ?>" alt="">
    <?php echo $sponsor_name ? '<p>By '.$sponsor_name.'</p>' : ''; ?>
</div>

<?php echo '</div>';




