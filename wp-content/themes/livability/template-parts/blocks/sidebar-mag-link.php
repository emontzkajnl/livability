<?php 
$args = array(
    'post_type'         => 'liv_magazine',
    'posts_per_page'    => 1,
    'meta_query'        => array(
        array( 
            'key'       => 'place_relationship',
            'value'     => '"' . get_the_ID() . '"',
            'compare'   => 'LIKE'
        )
    ),
);
$mag = get_posts($args);

if ($mag) {
    $ID = $mag[0]->ID;
    $sponsor_title = get_field('mag_sponsored_by_title', $ID); 
    $sponsor_link = get_field('mag_sponsored_by_link', $ID); ?>
    <div class="sidebar-mag-link">
        <div class="sidebar-mag-link__img">
        <a href="<?php echo get_the_permalink($ID); ?>">
        <p class="sidebar-mag-link__cta">Read the Magazine</p>
        <?php echo get_the_post_thumbnail($ID, 'medium_large', array( 'style' => 'height: auto;' )); ?>
        </a>
        </div>
        <?php if ($sponsor_title) { ?>
        <div class="mag-sponsor-sidebar">
            <p class="mag-sponsored-by">Sponsored by</p>
            <p class="mag-sponsor"><a href="<?php echo $sponsor_link; ?>"><?php echo $sponsor_title; ?></a></p>
        </div>
            
        <?php } ?>
    </div> <!--sidebar mag link -->
<?php }