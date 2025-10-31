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

<?php ?>

	<header class="page-header">
        <?php // get_template_part( 'template-parts/page-hero-section' ); ?>
	</header><!-- .page-header -->

    <div class="container">
    <div class="wp-block-columns full-width-off-white">
        <div class="wp-block-column">
            <?php get_template_part('template-parts/blocks/ad-one' ); ?>
        </div> 
    </div>

    <div class="wp-block-columns liv-columns">
            <div class="wp-block-column">
                <div id="crumbs">
                    <?php echo return_breadcrumbs(); ?>
                </div>
                <h1 class="h2">Articles by <?php echo $name; ?></h1>
                <div class="author-info-container">
                    <?php 
                    echo get_avatar( get_the_author_meta( 'ID' ), '300', '', '', array('class' => array('alignright')) );
                    echo $description; ?>
                </div>
          

	<?php if ( have_posts() ) : 
    echo '<ul class="container" style="padding-left: 0;">';
    while ( have_posts() ) : ?>
		<?php the_post(); 
        $cat = get_the_category();
        $heading = $cat[0]->name; ?>
        <li class="one-hundred-list-container">
            <a href="<?php echo get_the_permalink( ); ?>" class="ohl-thumb" style="background-image: url(<?php echo the_post_thumbnail_url(); ?>);"></a>
            <div class="ohl-text">
            <h5 class="green-text uppercase"><?php echo $heading; ?></h5>
            <a href="<?php  echo get_the_permalink( ); ?>">
            <?php _e(the_title('<h2>','</h2>'), 'livability'); 
            the_excerpt();
            ?>
            </div>
            </a>
        </li>
	<?php endwhile; ?>
    </ul>
   

<?php else : ?>
    <h3>no author results</h3>
	
<?php endif; ?>
</div>
            <div class="wp-block-column">
                <?php get_template_part('template-parts/blocks/ad-two' ); ?>
            </div>
        </div>

<?php get_footer(); ?>
