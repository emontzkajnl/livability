<?php 
/**
 * carousel for state cc page template
 * pulls articles from cc options page 
 */

$carousel = get_field('connected_community_slides', 'option');
// print_r($carousel);
if (have_rows('connected_community_slides', 'option')): ?>
<div class="pwl-container">
    <h2 class="green-line">Connected Communities Insights</h2> 
<ul class="pwl-slick">

    <?php while (have_rows('connected_community_slides', 'option')): the_row();
    $cc_post = get_sub_field('slide');
    $cc_ID = $cc_post->ID;
    $bkgrnd = get_the_post_thumbnail_url( $cc_ID, 'rel_article'); ?>
    <li>
        <a href="<?php echo get_the_permalink( $cc_ID); ?>">
        <div>
            <div class="pwl-img" style="background-image: url(<?php echo $bkgrnd; ?>)"></div>
            <h4 style="padding: 20px;"><?php echo $cc_post->post_title; ?></h4>
        </div>
        </a>
    </li>
  <?php endwhile;
    echo '</ul></div>';
endif;