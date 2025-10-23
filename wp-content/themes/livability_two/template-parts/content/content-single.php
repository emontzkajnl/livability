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

	<!-- <header class="entry-header alignwide"> -->
		<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php //twenty_twenty_one_post_thumbnail(); ?>
	<!-- </header> -->

	<div class="entry-content">
		<?php
		if (get_field('number_of_columns')) { ?>
		 <style>
            div#ez-toc-container nav ul {
                columns: <?php echo get_field('number_of_columns'); ?>; 
            }
        </style>
		<?php }

		the_content();

		// wp_link_pages(
		// 	array(
		// 		'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
		// 		'after'    => '</nav>',
		// 		/* translators: %: page number. */
		// 		'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
		// 	)
		// );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">
		<?php //twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->


</article><!-- #post-<?php //the_ID(); ?> -->
