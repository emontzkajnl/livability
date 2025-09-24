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
    <?php $ID = get_the_ID(); ?>



	<div class="entry-content">
   
        <div class="wp-block-columns full-width-off-white">
            <div class="wp-block-column">
                <?php //get_template_part('template-parts/blocks/ad-one' ); ?>
                <div class="wp-block-jci-ad-area-one" style="display: flex; justify-content: center;" >
                <?php //echo the_ad_group(698); ?>
                </div>
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
                        <?php get_template_part( 'template-parts/blocks/place-categories'); ?>
                     
                    </div>
                </div>
                    <!-- <div class="wp-block-columns liv-columns-2"> -->

                    <!-- </div> -->
                <!-- </div> -->
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column">
                        <?php echo do_shortcode( '[addtoany]' ); ?> 
                        <!-- <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                            <a class="a2a_button_copy_link"></a>
                            <a class="a2a_button_linkedin"></a>
                            <a class="a2a_button_facebook"></a>
                            <a class="a2a_button_x"></a>
                            <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                        </div>
                        <script>
                            a2a_config.linkurl = '<?php //echo get_the_permalink(); ?>';
                             a2a.init('page');
                        </script> -->
                       <?php get_template_part( 'template-parts/blocks/more-like-this'); ?>
                       
                    </div>
                    <div class="wp-block-column">
                     
                        
                        <?php if (has_post_thumbnail() && !get_field('hide_featured_image')): ?>
                        <figure class="wp-block-image size-full">
                        <div class="img-container">
                            <?php
                            $caption = get_the_post_thumbnail_caption();
                            $f_post_image_id = get_post_thumbnail_id();
                            $f_img_byline = get_field('img_byline', $f_post_image_id);
                            $f_img_place_name = get_field('img_place_name', $f_post_image_id);
                            if ($f_img_byline || $f_img_place_name) {
                                echo get_the_post_thumbnail($post->ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                                echo '<div class="livability-image-meta">';
                                echo $f_img_place_name ? $f_img_place_name : '' ;
                                echo $f_img_place_name && $f_img_byline ? ' / ' : '';
                                echo $f_img_byline ?  strip_tags($f_img_byline, "<a>") : '' ;
                                echo '</div>';
                            } else {
                                echo get_the_post_thumbnail($post->ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            }
                             ?>
                            
                        </div>
                        <?php if ($caption) {
                            echo '<figcaption>'.$caption.'</figcaption>';
                        } ?>
                        </figure>
                        <?php endif; ?>
                        <?php the_content(); ?>
                       
                    
                     
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
            <?php //get_template_part('template-parts/blocks/ad-two' ); ?>
            <div class="wp-block-jci-ad-area-two">
                <!-- empty so aa can just target div  -->
            </div>
            </div> 
            </div> <!-- wp-block-columns -->
            </div> <!-- .entry-content? -->
        

     
	

    <div class="entry-content">
        
    <div class="wp-block-columns">
            <div class="wp-block-column">
     

            
          
            </div>
        </div> 
    </div><!-- .entry-content -->
	
   
   
</div>

</article><!-- #post-<?php //the_ID(); ?> -->
 