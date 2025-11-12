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
$user_meta = get_user_meta($author_id);
$company = '';
$title = '';
if ($user_meta['wpseo_user_schema']) {
    $schema = unserialize($user_meta['wpseo_user_schema'][0]);
    $company = $schema['worksFor'] ? $schema['worksFor'] : '';
    $title = $schema['jobTitle'] ? $schema['jobTitle'] : '';
} 

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
<?php
// echo '<pre>';
// print_r(get_user_meta($author_id));
// print_r(unserialize($user_meta['wpseo_user_schema'][0]));

// echo '</pre>'; 

// foreach (unserialize($user_meta['wpseo_user_schema'][0]) as $key => $value) {
//     echo 'key '.$key.' value '.$value.'<br />';
//     # code...
// }
// $test = unserialize($user_meta['wpseo_user_schema'][0]);

// echo 'test: '.$test['worksFor'];
?>

    <div class="wp-block-columns liv-columns">
            <div class="wp-block-column">
                <div id="crumbs">
                    <?php echo return_breadcrumbs(); ?>
                </div>
                <h1 class="h2"><?php echo $name; ?></h1>
                <h4><?php echo  $title.' '.$company; ?></h4>
                <div class="author-info-container clearfix">
                    <?php 
                    // print_r($author_image['ID'] );
                    if ($author_image) {
                        echo wp_get_attachment_image( $author_image['ID'], 'medium', '', array("class" => "alignright ")  );
                        // echo wp_get_attachment_image( $author_image['ID'], 'medium');
                    } else {
                        echo get_avatar( get_the_author_meta( 'ID' ), '300', '', '', array('class' => array('alignright')) );
                    }
                    
                    
                    echo $description;
                    // echo limitWordsAndAddEllipsis($description, 40); ?>
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
