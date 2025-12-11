<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



	<div class="entry-content">

		<?php
		echo '<div style="padding-top: 100px;></div>'; ?>
		<div>
		<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label>
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ); ?></span>
			</label>
			<input type="search" id="post-autocomplete-search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Posts...', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search:', 'label' ); ?>" />

			<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
		</form>
</div>
		<?php 
		the_content();

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				/* translators: %: page number. */
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer default-max-width">
			<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					esc_html__( 'Edit %s', 'twentytwentyone' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
