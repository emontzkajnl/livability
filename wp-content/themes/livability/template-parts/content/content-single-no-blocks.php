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
<script>
        var cmWrapper = cmWrapper || {};
        cmWrapper.que = cmWrapper.que || [];
</script>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php $ID = get_the_ID(); ?>

    <script>
        cmWrapper.que.push(function() {
            cmWrapper.ads.define("ROS_ATF_300x600", "<?php echo $ID; ?>-3");
            cmWrapper.ads.define("ROS_ATF_970x250","<?php echo $ID; ?>-1" );
            // cmWrapper.ads.define("ROS_BTF_300x600", "<?php //echo $ID; ?>-2");
            cmWrapper.ads.requestUnits();
        });
    </script>


	<div class="entry-content">
    <?php
		if (get_field('number_of_columns')) { ?>
	
        <style>
            div#ez-toc-container nav ul {
                columns: <?php echo get_field('number_of_columns'); ?>; 
            }
        </style>
		<?php }
         if (get_field('enable_mega_hero', $ID)):
        require(get_stylesheet_directory(  ).'/assets/lib/state_abbv.php');
        $override = get_field('override_featured_image'); 
        $megahero_height = get_field('megahero_height');
        $article_thumb_url = $override ? wp_get_attachment_image_url( $override['id'], 'post_thumbnail' ) :  get_the_post_thumbnail_url( $ID, 'post_thumbnail');
        $mega_title = get_field('custom_title_override') ? get_field('custom_title_override') : get_the_title(); 
        $megacat = get_the_category( ); 
        $uriSegments = explode("/", parse_url(get_the_permalink( ), PHP_URL_PATH)); 
        $uriSegments = array_filter($uriSegments);
        $numSegments = count($uriSegments);
        $state_seg = strtoupper($uriSegments[1]);
        $subtitlePlace = '';
        if (array_key_exists($state_seg, $us_state_abbrevs_names)) {
            $subtitlePlace =  $us_state_abbrevs_names[$state_seg].' '; 
        } else {
            $subtitlePlace = $uriSegments[2].' ';
        }
        ?>
        <div class="mega-hero alignfull" style="background-image: url('<?php echo $article_thumb_url; ?>'); height: <?php echo $megahero_height; ?>vh">
            <div class="mega-hero-text-area">
                <h6><?php // echo $subtitlePlace.$megacat[0]->name;  ?><?php echo $megacat[0]->name; ?></h6>
                <?php echo '<h2>'.$mega_title.'</h2>'; ?>
            </div>
        </div>

        <?php endif; 
        // $uriSegments = explode("/", parse_url(get_the_permalink( ), PHP_URL_PATH)); 
        // $uriSegments = array_filter($uriSegments);
        // $numSegments = count($uriSegments);
        // $subtitlePlace = '';
        // // print_r($uriSegments);
        // if ($numSegments > 3 ) {
        //     $subtitlePlace = $uriSegments[2].' ';
        // } else if ($numSegments == 3) {
        //     require(get_stylesheet_directory(  ).'/assets/lib/state_abbv.php');
        //     $state_seg = strtoupper($uriSegments[1]);
        //     $subtitlePlace = $us_state_abbrevs_names[$state_seg].' ';
        // }
        // echo 'subtitle: '.$subtitlePlace;
         ?>
        <div class="wp-block-columns full-width-off-white">
            <div class="wp-block-column">
                <?php //get_template_part('template-parts/blocks/ad-one' ); ?>
                <div class="wp-block-jci-ad-area-one" id="<?php echo get_the_ID(); ?>-1"></div>
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
                        <?php if (get_field('article_announcement', 'option')){ ?>
                        <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"right":"35px","bottom":"15px","left":"35px","top":"15px"}}},"backgroundColor":"green"} -->
                        <div class="bp23-announcement has-green-background-color has-background" style="display: none; padding-top:15px;padding-right:35px;padding-bottom:15px;padding-left:35px;line-height:2"><?php echo get_field('article_announcement', 'option'); ?></div>
                        <!-- /wp:paragraph -->
                        <?php } ?>
                        <h1 class="h2"><?php echo the_title(); ?></h1>
                        <?php $cat = get_the_category(  );
                        // print_r($cat);
                        // echo $cat[0]->slug;
                        if ($cat[0]->slug == 'connected-communities') {
                            get_template_part( 'template-parts/blocks/cta-block' );
                        }
                          ?>
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
                       <?php get_template_part( 'template-parts/blocks/more-like-this'); ?>
                        <?php get_template_part( 'template-parts/blocks/internally-promoted-articles' ); ?>
                    </div>
                    <div class="wp-block-column">
                     
                        <?php if (has_excerpt(  )): ?>
                            <p class="article-excerpt"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                      
                        <p class="author">By <?php echo esc_html__( get_the_author(), 'livibility' ).' on '.esc_html( get_the_date() ); ?></p>
                        <?php if (get_field('sponsored')): 
                            $sponsor_text = get_field('sponsor_text') ? get_field('sponsor_text') : 'Sponsored by';
                            $name = get_field('sponsor_name');
                            $url = get_field('sponsor_url'); ?>
                        <div class="sponsored-by">
                            <p>Sponsored by: <a href="<?php echo esc_url( $url ); ?>"><?php _e($name, 'livability'); ?></a></p>
                        </div>
                        <?php endif; ?>
                        <?php if (has_post_thumbnail() && !get_field('hide_featured_image')): ?>
                        <div class="img-container">
                            <?php
                            $post_image_id = get_post_thumbnail_id();
                            $img_byline = get_field('img_byline', $post_image_id);
                            $img_place_name = get_field('img_place_name', $post_image_id);
                            if ($img_byline || $img_place_name) {
                                echo get_the_post_thumbnail($post->ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                                echo '<div class="livability-image-meta">';
                                echo $img_place_name ? $img_place_name : '' ;
                                echo $img_place_name && $img_byline ? ' / ' : '';
                                echo $img_byline ?  strip_tags($img_byline, "<a>") : '' ;
                                echo '</div>';
                            } else {
                                echo get_the_post_thumbnail($post->ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            }
                             ?>
                            
                        </div>
                        <?php endif; ?>
                        <?php the_content(); ?>
                        <div class="cm-text-links-<?php echo $ID; ?>"></div>
                    
                        <?php //get_template_part('template-parts/content/content-author-section'); ?>
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
            <?php //get_template_part('template-parts/blocks/ad-two' ); ?>
            <div class="wp-block-jci-ad-area-two" id="<?php echo get_the_ID(); ?>-3"></div>
            </div> 
            </div> <!-- wp-block-columns -->
            </div> <!-- .entry-content? -->
        

     
	
    <?php 
     if (get_field('make_horizontal')) {
        get_template_part( 'template-parts/content/content-horizontal-scroll' );
    }
    ?>
    <div class="entry-content">
        
    <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php if ($cat[0]->slug == 'connected-communities') {
                get_template_part( 'template-parts/blocks/cc-global-carousel' ); 
            }
            get_template_part( 'template-parts/blocks/local-sponsored', null, array('city' => $args['city'], 'global' => $args['global'], 'state' => $args['state'])); ?>
            </div>
        </div> 
    </div><!-- .entry-content -->
	
   
   
</div>

</article><!-- #post-<?php //the_ID(); ?> -->
