<?php
/**
 * The template for displaying author archive pages
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
$user_meta = get_user_meta($author_id);
$company = '';
$title = '';
$expertise = '';
if ($user_meta['wpseo_user_schema']) {
    $schema = unserialize($user_meta['wpseo_user_schema'][0]);
    $company = $schema['worksFor'] ? $schema['worksFor'] : '';
    $title = $schema['jobTitle'] ? $schema['jobTitle'] : '';
    $expertise = $schema['knowsAbout'] ? '<p style="font-weight: bold;">Expertise: '.implode(', ', $schema['knowsAbout']).'</p>' : '';
} 
$seperator = $company && $title ? '<br />' : '';
$facebook = $user_meta['facebook'];
$isntagram = $user_meta['isntagram'];
$pinterest = $user_meta['pinterest'];
$linkedin = $user_meta['linkedin'];
$youtube = $user_meta['youtube'];

?>





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
               <?php 
           
               ?>
                <h1 class="h2" style="margin-bottom: 10px;"><?php echo $name; ?></h1>
                <h4 style="margin-top: 10px;"><?php echo  $title.$seperator.$company; ?></h4>
                
                <ul class="author-social">
                    <?php 
                    echo $facebook ? '<li><a href="'.esc_url($facebook[0]).'"><img src="'.get_stylesheet_directory_uri().'/assets/images/social-icons/facebook.svg"/></a></li>' : '';
                    echo $instagram ? '<li><a href="'.esc_url($instagram[0]).'"><img src="'.get_stylesheet_directory_uri().'/assets/images/social-icons/instagram.svg"/></a></li>' : '';
                    echo $pinterest ? '<li><a href="'.esc_url($pinterest[0]).'"><img src="'.get_stylesheet_directory_uri().'/assets/images/social-icons/pinterest.svg"/></a></li>' : '';
                    echo $linkedin ? '<li><a href="'.esc_url($linkedin[0]).'"><img src="'.get_stylesheet_directory_uri().'/assets/images/social-icons/linkedin.svg"/></a></li>' : '';
                    echo $youtube ? '<li><a href="'.esc_url($youtube[0]).'"><img src="'.get_stylesheet_directory_uri().'/assets/images/social-icons/youtube.svg"/></a></li>' : '';
                    ?>
                </ul>
                <div class="author-info-container clearfix">
                <h2 class="author-title">About <?php echo get_author_name( ); ?></h2>
                    <?php 
                    echo $expertise;
                    // print_r($author_image['ID'] );
                    if ($author_image) {
                        echo wp_get_attachment_image( $author_image['ID'], 'medium', '', array("class" => "alignright ")  );
                        // echo wp_get_attachment_image( $author_image['ID'], 'medium');
                    } else {
                        echo get_avatar( get_the_author_meta( 'ID' ), '300', '', '', array('class' => array('alignright')) );
                    }
                    
                    
                    echo $description; ?>
                </div>
          

	<?php if ( have_posts() ) : 
    echo '<h2>Articles by '.get_author_name( ).'</h2>';
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
