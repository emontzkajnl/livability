<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

$description = get_the_archive_description();
$name = get_the_author_meta('display_name');
$author = get_queried_object();
$author_id = $author->ID;
$author_image = get_field('author_image', 'user_'.$author_id);

?>

<?php if ( have_posts() ) : ?>

	<header class="page-header">
        <?php get_template_part( 'template-parts/page-hero-section' ); ?>
	</header><!-- .page-header -->

    <div class="container">
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
                            <?php echo return_breadcrumbs(); ?>
                        </div>
                        <h1 class="h2"><?php echo $name; ?></h1>
                        <div class="author-info-container">
                            <?php //echo 'id is '.$author_id; 
                            //print_r($author_image);
                            // echo 'author id '.$author_id;
                            // echo wp_get_attachment_image($author_image->ID);
                            // print_r($author_image);
                            echo get_avatar( get_the_author_meta( 'ID' ), '300', '', '', array('class' => array('alignright')) );
                            echo $description; ?>
                        </div>
                    </div>
                </div>
                 
           
            </div>
            <div class="wp-block-column">
                <?php get_template_part('template-parts/blocks/ad-two' ); ?>
            </div>
        </div>

        
        

	<?php while ( have_posts() ) : ?>
		<?php the_post(); 

        echo do_shortcode( '[ajax_load_more archive="true" repeater="template_1"  post_type="post" button_label="More Articles" posts_per_page="6"]'); ?>
	<?php endwhile; ?>
    </div> <!--container-->

<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>
