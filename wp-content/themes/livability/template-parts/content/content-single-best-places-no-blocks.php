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
// global $post;
?>

<?php 
$places = get_field('place_relationship');
$livscore = get_field('ls_livscore');
$current_rank = get_field('bp_rank');
// $amenities = get_field('amenities');
$remote_ready = get_field('remote_ready');
$how_we_calculate_link = get_the_permalink( '92097' ); 
$is_child = $is_parent = $is_top_onehundred = false;
$year = '';
$this_post = get_post();
$hide_featured = get_post_meta($this_post->ID, 'hide_best_place_featured_image', true);
// print_r($this_post);
$children = get_posts(array('post_parent' => $this_post->ID));
if ($this_post->post_parent) {
    $is_child = true;
} 
if (count($children) > 0) {
    $is_parent = true;
}
$year_tax = get_the_terms(get_the_ID(), 'best_places_years' );
if ($year_tax) {
    $year = $year_tax[0]->name;
}
$is_top_onehundred = get_field('is_top_one_hundred_page');
 
?>
<style>
    .bp-data-image {
        background-image: url("<?php echo get_the_post_thumbnail_url($this_post->ID, 'medium_large'); ?>"); 
    }
</style>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
    <?php if ($year != '2022' || !$is_top_onehundred): ?>
    <div class="wp-block-columns full-width-off-white">
        <div class="wp-block-column">
            <?php get_template_part('template-parts/blocks/ad-one' ); ?>
        </div>
    </div>
        <?php 
        endif;
        if ($is_child): 
            $my_parent = get_post_parent($this_post->ID); 
            $parent_id = $my_parent->ID;
            $title = get_the_title();
            $comma_position = strrpos($title, ',');
            $city_only = substr($title, 0, $comma_position); ?>
        <div id="crumbs">
            <?php echo return_breadcrumbs(); ?> 
        </div>
        <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"right":"35px","bottom":"15px","left":"35px","top":"15px"}}},"backgroundColor":"green"} -->
            <p class="has-green-background-color has-background" style="padding-top:15px;padding-right:35px;padding-bottom:15px;padding-left:35px;line-height:2"><strong>UPDATE</strong>: Check out our new <a href="https://livability.com/best-places/2023-top-100-best-places-to-live-in-the-us/"><strong>2023 Best Cities to Live in the U.S.</strong></a> <strong>list</strong>.</p>   
        <!-- /wp:paragraph -->
        <div class="wp-block-columns liv-columns">
            <div class="wp-block-column">
                <div class="wp-block-columns">
                    <div class="wp-block-column">
                    <?php if ($year != '2022'){ get_template_part( 'template-parts/blocks/best-place-data' );} ?>
                    </div>
                </div>
                
                <div class="wp-block-columns liv-columns-2">
                    <div class="wp-block-column">
                    <?php echo do_shortcode('[addtoany]'); ?>
                    <?php get_template_part( 'template-parts/blocks/more-like-this'); ?>
                   
                    
                        
                    </div>
                    <div class="wp-block-column">
                        <?php if ($year == '2022' && $is_top_onehundred) { ?>
                            <h2 class="h2"><?php echo get_the_title().' is the #'.$current_rank.' Best City to Live in the USA'; ?></h2>
                            <?php } else { ?>
                            <h2 class="h2"><?php echo '#'.$current_rank.'. '.get_the_title(); ?></h2>
                            <?php } ?>
                        <?php if ($year == '2022') {
                            get_template_part( 'template-parts/blocks/best-place-data-2022' );
                            echo '<h2>Why '.$city_only.' is one of the best cities to live in</h2>';
                            } ?>
                        <?php the_content(); 
                        if ($year == '2022' && $is_top_onehundred) {
                            // get_template_part('template-parts/blocks/exp-box' ); 
                            get_template_part( 'template-parts/blocks/quick-facts', null, array( 'city' => $places[0]) );
                            get_template_part( 'template-parts/blocks/city-and-state-links', null, array( 'city-state' => $places) );

                        }
                         ?>
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
            <?php get_template_part('template-parts/blocks/ad-two' ); ?>
           
        </div>
                    </div>
        <!-- <div class="wp-block-columns"> -->
            <!-- <div class="wp-block-column"> -->
        <?php
        // echo 'id is '.$this_post->ID;
      
        
        if ($my_parent):
            $currentID = get_the_ID();
            $parent = get_post_parent();
            // print_r($post);
            $args = array(  
                'posts_per_page' => -1,
                'post_type'     => 'best_places',
                'post_status'   => array('publish', 'private'),
                'orderby'    => 'meta_value_num',
                'meta_key'  => 'bp_rank',
                'order'     => 'ASC',
                // 'post_parent'   => $parent,
                'post_parent'   => $my_parent->ID
            );
            $query = new WP_Query($args);
            // $current_rank = get_field('bp_rank', $currentID);
            // SPLIT THE QUERY ARRAY SO CURRENT BP IS FIRST 
            $queryArray = $query->posts;
            $current_rank = $current_rank - 1;
            $row2 = array_splice($queryArray, $current_rank);
            $row1 = array_splice($queryArray, 0, $current_rank );
            $merged = array_merge($row2, $row1);
            $query->posts = $merged;
            if ($query->have_posts()): ?>
            <?php if ($is_top_onehundred) {echo '<h4 class="aligncenter" style="margin-bottom: -50px; color: #111111;">Continue Reading Best Places To Live in the US in '.$year.'</h4>';} ?>
            <div class="list-carousel-container custom-block">
                <ul class="wp-block-jci_blocks-blocks list-carousel">
                    <?php while ( $query->have_posts()):  $query->the_post();
                    $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_Id(), 'rel_article') : '';
                    $title = __(get_the_title(), 'jci_blocks');
                    $rank = get_field('bp_rank'); ?>
                    <li class="lc-slide">
                    <a href="<?php echo get_the_permalink(); ?>">
                    <div class="lc-slide-inner">
                        <div class="lc-slide-content">
                            <div class="lc-slide-img" style="background-image: url(<?php echo $thumb_url; ?>);">
                                <p class="slide-count"><?php echo $rank; ?></p>
                            </div>
                            <div><h4 class="city-state"><?php echo $title; ?></h4></div>
                        </div>
                    </div>
                    </a>
                    </li>

                    <?php endwhile; ?>
                </ul>
                <button class="list-carousel-button"><a href="<?php echo get_the_permalink( $my_parent->ID ); ?>">Go Back To List</a></button>
            </div>

            <?php endif; ?>
            <?php endif; // if has post parent show carousel ?>
            </div>
            
            
        </div>
		<?php

            else: // begin parent 
                if ($year == '2022' && $is_top_onehundred): 
                get_template_part( 'template-parts/content/content-top-100-2022' ); 
                else: ?>
                <div class="wp-block-columns liv-columns">
                <div class="wp-block-column">
                    <div class="wp-block-columns liv-columns-2">
                        <div class="wp-block-column">
                          
                        </div>
                        <div class="wp-block-column">
                            <div id="crumbs">
                                <?php echo return_breadcrumbs(); ?>
                            </div>
                             <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"right":"35px","bottom":"15px","left":"35px","top":"15px"}}},"backgroundColor":"green"} -->
                            <p class="has-green-background-color has-background" style="padding-top:15px;padding-right:35px;padding-bottom:15px;padding-left:35px;line-height:2"><strong>UPDATE</strong>: Check out our new <a href="https://livability.com/best-places/2023-top-100-best-places-to-live-in-the-us/"><strong>2023 Best Cities to Live in the U.S.</strong></a> <strong>list</strong>.</p>
                            <!-- /wp:paragraph -->
                            <h1 class="h2"><?php echo the_title(); ?></h1>
                            <?php if (get_field('sponsored')): 
                                $sponsor_text = get_field('sponsor_text') ? get_field('sponsor_text') : 'Sponsored by';
                                $name = get_field('sponsor_name');
                                $url = get_field('sponsor_url'); ?>
                            <div class="sponsored-by">
                                <p>Sponsored by: <a href="<?php echo esc_url( $url ); ?>"><?php _e($name, 'livability'); ?></a></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                        <!-- <div class="wp-block-columns liv-columns-2"> -->
    
                        <!-- </div> -->
                    <!-- </div> -->
                    <div class="wp-block-columns liv-columns-2">
                        <div class="wp-block-column">
                            <?php echo do_shortcode( '[addtoany]' ); ?>
                            <?php get_template_part( 'template-parts/blocks/more-like-this'); ?>
                        </div>
                        <div class="wp-block-column">
                         
                            <?php if (has_excerpt(  )): ?>
                                <p class="article-excerpt"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></p>
                            <?php endif; ?>
                          
                            <p class="author">By <?php echo esc_html__( get_the_author(), 'livibility' ).' on '.esc_html( get_the_date() ); ?></p>
                            <div><?php if (!$hide_featured) {
                                echo get_the_post_thumbnail($post->ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            }
                             ?></div>
                            <?php the_content(); ?>
                            
                           
    
                        </div>
                    </div>
                </div>
                <div class="wp-block-column">
                    <?php get_template_part('template-parts/blocks/ad-two' ); ?>
                </div>
            </div> 
            <?php endif; // if not 2022 top 100 ?>
            <!-- Begin onehundred list -->

            <?php $currentID = $this_post->ID;
            $args = array( 
                'post_type'     => 'best_places',
                'posts_per_page'=> 10,
                'post_status'   => array('publish','private'),
                'orderby'    => 'meta_value_num',
                'meta_key'  => 'bp_rank',
                'order'     => 'ASC',
                'post_parent'   => $currentID, 
                // 'post__in'  => $child_array,
                'paged'     => 1
            ); ?>
             <script>
            window.ohlObj = {};
            Object.assign(window.ohlObj, {current_page: '2'});
            Object.assign(window.ohlObj, {parent: <?php echo $currentID; ?>});
            // Object.assign(window.ohlObj, {children: <?php //echo json_encode($child_array); ?>});
            </script>
            <?php // begin onehundred list
            $ohl_query = new WP_Query($args);
            if ($ohl_query->have_posts()): ?>
                <ul class="onehundred-container">
                <?php while ($ohl_query->have_posts()): $ohl_query->the_post();
                $ID = get_the_ID();
                $place = get_field('place_relationship');
                $population = '';
                if ($place) {
                    $population = str_replace(',', '', get_field('city_population', $place[0]));
                    $population = intval($population);
                    $livscore = get_field('ls_livscore', $place[0]);
                } ?>
                <li class="one-hundred-list-item">
                    <div class="one-hundred-list-container">
                       <a href="<?php echo get_the_permalink(); ?>" class="ohl-thumb" >
                       <!-- <div style="background-image: url('<?php //echo get_the_post_thumbnail_url($ID,'three_hundred_wide'); ?>');"> -->
                       <?php echo get_the_post_thumbnail($ID, 'three_hundred_wide'); ?>
                        <p class="green-circle with-border"><?php echo get_field('bp_rank'); ?></p>
                        <!-- </div> -->
                        </a> 
                        <div class="ohl-text">
                            <a href="<?php echo get_the_permalink(); ?>">
                            <h2><?php echo get_the_title(); ?></h2>
                            <h3 class="uppercase">
                                <?php if ($livscore ) {
                                    echo $livscore;
                                }
                                if ($livscore && $population) {
                                    echo ' | ';
                                }
                                if ($population) {
                                    echo ' Population: '.number_format($population);
                                } ?>
                            </h3>
                            <p><?php echo get_the_excerpt( ); ?></p></a>
                        
                        </div>
                    </div>
                </li>

                <?php endwhile; wp_reset_postdata(); ?>
                </ul>
                <div class="waypoint-target"></div>
            <?php endif; ?>


            <?php endif; // end if is child else
		?>
	</div><!-- .entry-content -->
    

	<footer class="entry-footer default-max-width">
		<?php //twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php //the_ID(); ?> -->
