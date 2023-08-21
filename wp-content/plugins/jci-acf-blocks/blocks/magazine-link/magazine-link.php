<?php

/**
 * Magazine Link Template
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'magazine-link-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'acf-magazine-link';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$magID = get_field('magazine_link');

if (!$magID) {
    $ID = get_the_ID();
    $place_type = get_field('place_type', $ID);
    if ($place_type == 'metro') {
        $place_type = 'Region';
    }
    $args = array(
        'post_type'     => 'liv_magazine',
        'posts_per_page'=> 1,
        'post_status'   => 'publish',
        'meta_query'    => array(  
            'relation'  => 'AND',
            array( 
                'key'   => 'place_relationship',
                'value' => '"'.$ID.'"',
                'compare'=> 'LIKE'
            ),
            array( 
                'key'   => 'mag_place_type',
                'value' => $place_type,
                'compare'=> 'LIKE'
            )
        )
    );
    $mag_query = new WP_Query($args);
    $magID = count($mag_query->posts) == 1 ? $mag_query->posts[0]->ID : '';
}

if ($magID) {
    $src = get_the_post_thumbnail_url( $magID, 'rel_article');
    $sponsor = get_field('mag_sponsored_by_title', $magID);
    $sponsor_link = get_field('mag_sponsored_by_link', $magID); ?>

    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <img src="<?php echo $src; ?>" />
    <h4><?php echo get_the_title($magID); ?></h4>
    <?php if ($sponsor){
        echo '<p>This digital edition of the <span class="italic">'.get_the_title($magID).'</span> is sponsored by the <a href="'.$sponsor_link.'">'.$sponsor.'</a>.</p>';
    } ?>
    <a href="<?php echo  get_the_permalink($magID); ?>"><button>Read the Magazine</button></a>
    </div>

<?php } 

?>

