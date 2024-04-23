<?php
/**
 * Template Name: Best places 2024 Landing Page
 * Template Post Type: best_places
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

while ( have_posts() ) :
	the_post(); ?>
    

    <header class="entry-header alignwide bp24-landing-hero" style="padding-top: 30px;">
        <?php if ( is_mobile() ) {
            
            $mobileImg = get_field('mobile_image');
            echo '<img src="'.esc_url($mobileImg['url']).'" alt="'.esc_attr($mobileImg['alt']).'" style="max-width: none; height: auto; width: 100%;" />';
        } else {
            echo get_the_post_thumbnail( get_the_ID(), 'full', array('style' => 'max-width: none;', 'width' => '100%', 'height' => 'auto' ) );
        }  ?>
    </header>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content bp24lparticle">
    <div class="wp-block-columns">
        <div class="wp-block-column">
            <?php the_content(); ?>
        </div>
    </div>
    </div> <!--entry content-->
    <div style="background-color: #f8f8f8;">
    <div class="entry-content ">
    <div class="wp-block-columns">
        <div class="wp-block-column">
        <?php get_template_part( 'template-parts/blocks/bp24-top-100-list' ); 
        // $regions = get_posts(array(
        //     'posts_per_page' => -1,
        //     'post_type' => 'liv_place',
        //     'include' => array( 244, 258,  282, 234,  243, 269, 279)
        // ));
        // foreach($regions as $key=>$value) {
        //     echo '\''.$value->post_title.'\', ';
        // }
        ?>
        </div>
    </div>
    
        </div> <!-- off white div -->
        </div> <!--entry content-->
</article>
<?php endwhile;

get_footer();