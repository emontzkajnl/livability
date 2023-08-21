<?php 

/**
 * Place Topics Block Template
 */


 // Create id attribute allowing for custom "anchor" value.
 $blockId = $block['id'];
$id = 'cc-cta-' . $blockId;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'cc-cta-container';

if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

//fields:
// toggle default text 
// custom text
// article link 
// custom sponsor name
// $has_custom = get_field('use_custom_text');
// $custom_text = get_field('custom_content');
// $article_link = get_field('article_link');
$place_type = get_post_meta(get_the_ID(), 'place_type');
if ($place_type[0] == 'state') {

} else {
    // query for articles with same relationship
    $args = array(
        'post_type'         => 'post',
        'posts_per_page'    => 1,
        'post_status'       => 'publish',
        'category_name'     => 'connected-communities',
        'meta_query'        => array(
            array(
                'key'           => 'place_relationship',
                'value'         => '"' . get_the_ID() . '"',
                'compare'       => 'LIKE'
            ) 
        )
    );
}
$sponsor_name = get_field('sponsor_name');
$cc_page = get_page_by_title( 'Connected Communities', OBJECT );
$parent = get_post_parent();
$place = get_field('place_relationship', get_the_ID());

// print_r($place);
// echo 'place '.$place[0];
// $meta = get_post_meta( get_the_ID() );
// print_r($meta);
// $state_code = get_post_meta(get_the_ID(), 'state_code');
// echo 'state is '.$state_code[0];
// echo '<br />place type  is '.$place_type[0];



?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" >
    <div class="cc-cta__left-section">
       <?php 
       if ($place_type[0] == 'state') {
           echo '<p>';
           echo $cc_page ? get_the_title().' has <a href="'.get_the_permalink( $cc_page->ID).'">Connected Communities</a> with high-speed fiber internet service providers. ' : '';
           echo '<a href="'.get_the_permalink( ).'connected-communities">Read More</a>';
           echo '</p>';
       } else {
            echo $cc_page ? get_the_title().' has <a href="'.get_the_permalink( $cc_page->ID).'">Connected Communities</a> with high-speed fiber internet service providers. ' : '';
            echo '<a href="'.get_the_permalink( ).'connected-communities">Read More</a>';
            echo '</p>';
       }?> 
    </div>
    <div class="cc-cta__right-section">
        <img src="<?php // echo plugin_dir_url(__FILE__); ?>/wp-content/plugins/jci-acf-blocks/blocks/cc-cta/cc-icon-w-bar.svg" alt="">
        <?php echo $sponsor_name ? '<p>By '.$sponsor_name.'</p>' : ''; ?>
    </div>
</div>