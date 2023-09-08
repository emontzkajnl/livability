<?php
/**
 * Template Name: Connected Community Page
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
$hero_section = get_field('display_hero');
if ($hero_section) {
	get_template_part( 'template-parts/page-hero-section' );
}

/* Start the Loop */
while ( have_posts() ) :
	the_post(); 
    $state = get_field('place_relationship');?>
    <div class="entry-content">
        <div class="wp-block-columns full-width-off-white">
            <div class="wp-block-column">
                <?php get_template_part('template-parts/blocks/ad-one' ); ?>
            </div>
        </div>

        <div class="wp-block-columns liv-columns">
            <div class="wp-block-column">
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column">
                      
                    </div>
                    <div class="wp-block-column">
                        <div id="crumbs">
                            <?php if (function_exists('return_breadcrumbs')) {
                                echo return_breadcrumbs(); 
                            } ?>
                        </div>
                        <h1 class="h2"><?php echo the_title(); ?></h1>
                    </div>
                </div>
                    <!-- <div class="wp-block-columns liv-columns-2"> -->

                    <!-- </div> -->
                <!-- </div> -->
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column">
                        <?php //echo do_shortcode( '[addtoany]' ); ?>
                        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                            <a class="a2a_button_copy_link"></a>
                            <a class="a2a_button_linkedin"></a>
                            <a class="a2a_button_facebook"></a>
                            <a class="a2a_button_twitter"></a>
                            <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                        </div>
                        <script>
                            a2a_config.linkurl = '<?php echo get_the_permalink(); ?>';
                             a2a.init('page');
                        </script>
                       <?php //get_template_part( 'template-parts/blocks/more-like-this'); ?>
                    </div>
                    <div class="wp-block-column">
                     
                        <?php if (has_post_thumbnail()): ?>
                        <!-- <div class="img-container"> -->
                            <?php
                            // $post_image_id = get_post_thumbnail_id();
                            // $img_byline = get_field('img_byline', $post_image_id);
                            // $img_place_name = get_field('img_place_name', $post_image_id);
                            // if ($img_byline || $img_place_name) {
                            //     echo get_the_post_thumbnail($post->ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            //     echo '<div class="livability-image-meta">';
                            //     echo $img_place_name ? $img_place_name : '' ;
                            //     echo $img_place_name && $img_byline ? ' / ' : '';
                            //     echo $img_byline ?  strip_tags($img_byline, "<a>") : '' ;
                            //     echo '</div>';
                            // } else {
                            //     echo get_the_post_thumbnail($post->ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            // }
                             ?>
                            
                        <!-- </div> -->
                        <?php endif; ?>
                        <?php the_content(); ?>
                        <!-- <div class="cm-text-links-<?php //echo $ID; ?>"></div> -->
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
            <?php //get_template_part('template-parts/blocks/ad-two' ); ?>
            <div class="wp-block-jci-ad-area-two" id="<?php echo get_the_ID(); ?>-3"></div>
            </div> 
            </div> <!-- wp-block-columns -->
            </div> <!-- .entry-content? -->
    <div class="entry-content">
    <div class="wp-block-columns">
            <div class="wp-block-column">
                <?php get_template_part( 'template-parts/blocks/cc-local-list', null, array('state' => $state)); 
                get_template_part( 'template-parts/blocks/cc-global-carousel'); ?>
            </div>
        </div> 
    </div><!-- .entry-content -->

<?php endwhile; // End of the loop.
// dynamic_sidebar( 'sidebar-2' );

get_footer();