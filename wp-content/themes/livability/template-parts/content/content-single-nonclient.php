<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
        <div class="wp-block-columns full-width-off-white">
            <div class="wp-block-column">
                <?php get_template_part( 'template-parts/blocks/ad-one' ); ?>
            </div>
        </div>
        <div class="wp-block-columns">
     
        </div>
        <div class="wp-block-columns liv-columns">
            <div class="wp-block-column">
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column"></div>
                    <div class="wp-block-column">
                        <div id="crumbs">
                            <?php echo return_breadcrumbs(); ?> 
                        </div>
                        
                        <h1 class="h1"><?php echo get_the_title(); ?></h1>
                        <?php //If there is and article connected to this place with a 
                        // topic of connected community, show block

                        get_template_part( 'template-parts/blocks/cc-cta-block' );
                        ?>
                    </div>
                </div>
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column">
                    <?php echo do_shortcode('[addtoany]'); 
                    get_template_part( 'template-parts/blocks/top-one-hundred-link'); ?>
                    
                    </div>
                    <div class="wp-block-column">

                    <?php 
                     if (get_field('non-client_city_with_content')) {
                        the_content( );
                    } else {
                        get_template_part( 'template-parts/blocks/madlib' ); 
                    }
                    get_template_part( 'template-parts/blocks/embedded-cc-article' );
                    get_template_part( 'template-parts/blocks/link-to-2023-best-place' );
                     ?>
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
            <?php get_template_part( 'template-parts/blocks/ad-two' ); ?>
            </div>
        </div>
        <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php get_template_part( 'template-parts/blocks/quick-facts' ); ?>
        </div></div>
        
        <?php get_template_part( 'template-parts/blocks/city-map' ); ?>
        
        <div class="wp-block-columns">
            <div class="wp-block-column">
      
            <?php get_template_part( 'template-parts/blocks/place-brand-story' ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'experiences-adventures') ); ?>
            <?php get_template_part( 'template-parts/blocks/ad-three' ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'food-scenes') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'healthy-places') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'affordable-places-to-live') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'make-your-move') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'where-to-live-now') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'education-careers-opportunity') ); ?>
            <?php get_template_part( 'template-parts/blocks/place-topics',null, array('category' => 'love-where-you-live') ); ?>
            </div>
        </div>


		
	</div><!-- .entry-content -->




</article><!-- #post-<?php the_ID(); ?> -->
