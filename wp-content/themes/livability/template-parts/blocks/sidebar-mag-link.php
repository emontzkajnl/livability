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
    $sponsor = get_field('sponsor', $ID); ?>
    <div class="sidebar-mag-link">
        <div class="sidebar-mag-link__img">
        <a href="<?php echo get_the_permalink($ID); ?>">
        <p class="sidebar-mag-link__cta">Read the Magazine</p>
        <?php echo get_the_post_thumbnail($ID, 'medium_large', array( 'style' => 'height: auto;' )); ?>
        </a>
        </div>
    </div>
<?php }