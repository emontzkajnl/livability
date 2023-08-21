<?php
/**
 * Template Name: Best places 2023 Landing Page
 * Template Post Type: best_places
 **/
// include_once( get_stylesheet_directory() .'/assets/lib/bp_2023_cache_obj.php');
get_header();

while ( have_posts() ) :
	the_post(); ?>
    

    <header class="entry-header alignwide">
        <?php //echo get_the_post_thumbnail(  ); ?>
    </header>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content ">
    <div class="wp-block-columns">
        <div class="wp-block-column">
            <?php the_content(); ?>
        </div>
    </div>
    <div class="wp-block-columns">
        <div class="wp-block-column">
        <?php get_template_part( 'template-parts/blocks/bp23-top-100-list' ); ?>
        </div>
    </div>
    
    
    </div> <!--entry content-->
</article>
<?php endwhile;

get_footer();