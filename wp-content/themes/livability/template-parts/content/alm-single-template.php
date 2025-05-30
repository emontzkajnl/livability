<?php 
// backup for single content template in alm 7/30
global $post;
$ID = get_the_ID();?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content">
<?php if (get_field('number_of_columns')) { ?>
	
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
        $megahero_vertical = get_field('vertical_position') ? get_field('vertical_position') : 'center';
        if ( is_mobile() ) {
            $size = 'portrait';
        } else {
            $size = 'full';
        }
        $article_thumb_url = $override ? wp_get_attachment_image_url( $override['id'], $size ) :  get_the_post_thumbnail_url( $ID, $size);
        $article_thumb_id = $override ? $override['id'] : get_post_thumbnail_id();
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
        $hero_img_byline = get_field('img_byline', $article_thumb_id);
        $hero_img_place_name = get_field('img_place_name', $article_thumb_id);
?>
            <div class="mega-hero alignfull" style="background-image: url('<?php echo $article_thumb_url; ?>'); height: <?php echo $megahero_height; ?>vh; background-position-y: <?php echo $megahero_vertical; ?>;">
            <div class="mega-hero-text-area">
                <p class="mega-hero__subheader"><?php // echo $subtitlePlace.$megacat[0]->name;  ?><?php echo $megacat[0]->name; ?></p>
                <?php echo '<p class="mega-hero__header">'.$mega_title.'</p>'; ?>
            </div>
         
        
        <?php if ($hero_img_byline || $hero_img_place_name) {
             echo '<div class="absolute-container"><div class="livability-image-meta">';
             echo $hero_img_place_name ? $hero_img_place_name : '' ;
             echo $hero_img_place_name && $hero_img_byline ? ' / ' : '';
             echo $hero_img_byline ?  strip_tags($hero_img_byline, "<a>") : '' ;
             echo '</div></div>';
        } ?>
        </div>
	<?php endif; ?>
         <div class="wp-block-columns full-width-off-white">
            <div class="wp-block-column">
                <div class="wp-block-jci-ad-area-one" style="display: flex; justify-content: center;" >
                <?php echo the_ad_group(698); ?>
                </div>
            </div>
        </div>
    	<div class="wp-block-columns liv-columns">
        	<div class="wp-block-column"><!-- main column-->
            	<div class="wp-block-columns liv-columns-2">
                	<div class="wp-block-column"></div>
                	<div class="wp-block-column">
                     <?php if (function_exists('return_breadcrumbs')) {
                                echo return_breadcrumbs(); 
                            } ?>
                    <?php if (get_field('article_announcement', 'option')){ ?>
                        <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"right":"35px","bottom":"15px","left":"35px","top":"15px"}}},"backgroundColor":"green"} -->
                        <div class="bp23-announcement has-green-background-color has-background" style="display: none; padding-top:15px;padding-right:35px;padding-bottom:15px;padding-left:35px;line-height:2"><?php echo get_field('article_announcement', 'option'); ?></div>
                        <!-- /wp:paragraph -->
                        <?php } ?>
                    <h1 class="h2"><?php echo the_title(); ?></h1>
                     <?php $cat = get_the_category(  );
                        if ($cat[0]->slug == 'connected-communities') {
                            get_template_part( 'template-parts/blocks/cta-block' );
                        }
                          ?>
                		
              </div> <!-- breadcrumb title column-->
            </div> <!--liv columns 2-->
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
                        <figure class="wp-block-image size-full">
                        <div class="img-container">
                            <?php
                            $caption = get_the_post_thumbnail_caption();
                            $f_post_image_id = get_post_thumbnail_id();
                            $f_img_byline = get_field('img_byline', $f_post_image_id);
                            $f_img_place_name = get_field('img_place_name', $f_post_image_id);
                            if ($f_img_byline || $f_img_place_name) {
                                echo get_the_post_thumbnail($ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                                echo '<div class="livability-image-meta">';
                                echo $f_img_place_name ? $f_img_place_name : '' ;
                                echo $f_img_place_name && $f_img_byline ? ' / ' : '';
                                echo $f_img_byline ?  strip_tags($f_img_byline, "<a>") : '' ;
                                echo '</div>';
                            } else {
                                echo get_the_post_thumbnail($ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            }
                             ?>
                            
                        </div>
                        <?php if ($caption) {
                            echo '<figcaption>'.$caption.'</figcaption>';
                        } ?>
                        </figure>
                        <?php endif; ?>
                        <?php the_content(); ?>
                        <div class="cm-text-links-<?php echo $ID; ?>"></div>
                    
                    </div>
            </div> <!-- second lc2 --> 
            
                </div> <!-- lc first column-->
            	<div class="wp-block-column"><!-- sidebar column-->
             	<div class="wp-block-jci-ad-area-two">
                    <?php //echo the_ad_group(699); ?>
                </div>
        	</div><!-- sidebar column-->
            </div> <!-- liv-columns -->
</div> <!-- entry-content --> 
<?php 
     if (get_field('make_horizontal')) {
        get_template_part( 'template-parts/content/content-horizontal-scroll' );
    }
    ?>
  <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php if ($cat[0]->slug == 'connected-communities') {
                get_template_part( 'template-parts/blocks/cc-global-carousel' ); 
            }
            // get_template_part( 'template-parts/blocks/local-sponsored', null, array('city' => $args['city'], 'global' => $args['global'], 'state' => $args['state'])); ?>
            </div>
        
</div><!-- entry content -->
<?php 
     if (get_field('make_horizontal')) {
        get_template_part( 'template-parts/content/content-horizontal-scroll' );
    }
    ?>
    <div class="entry-content">
   <div class="wp-block-columns">
            <div class="wp-block-column">
           <?php
            // get_template_part( 'template-parts/blocks/local-sponsored', null, array('city' => $args['city'], 'global' => $args['global'], 'state' => $args['state'])); ?>
            </div>
        </div> 
    </div><!-- .entry-content -->

</article>
