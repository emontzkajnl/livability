<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

if ( have_posts() ) {
	?>



	<div class="entry-content site-content">
		<div class="wp-block-columns">
			<div class="wp-block-column">
			<h1 class="h2">
				<?php
				printf(
					/* translators: %s: search term. */
					esc_html__( 'Results for "%s"', 'twentytwentyone' ),
					'<span class="page-description search-term">' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
			<div class="search-result-count container"><p>
		<?php
		printf(
			esc_html(
				/* translators: %d: the number of search results. */
				_n(
					'We found %d result for your search.',
					'We found %d results for your search.',
					(int) $wp_query->found_posts,
					'livability'
				)
			),
			(int) $wp_query->found_posts
		);
		// print_r($wp_query);
		?>
	</p></div><!-- .search-result-count -->
			</div>
		</div>
	<div class="wp-block-columns liv-columns">
		<div class="wp-block-column">


	<ul class="container" style="padding-left: 0;">
	<?php
	// Start the Loop.
	// while ( have_posts() ) {
		// the_post();
		// get_template_part( 'template-parts/content/content-search');
	
	//  get_template_part( 'template-parts/content/content-search-test');
	//  get_template_part( 'template-parts/content/content-excerpt', get_post_format() );
	echo do_shortcode('[ajax_load_more loading_style="infinite classic" repeater="template_3" post_type="any" posts_per_page="20" search="'.esc_html( get_search_query() ).'" scroll_distance="-200"]');
	// }
	?>
	</ul>
	</div> <!-- column -->
		<div class="wp-block-column">
		<?php get_template_part('template-parts/blocks/ad-two' ); ?>
		</div>
	</div>

	<?php // Previous/next page navigation.
	//  twenty_twenty_one_the_posts_navigation();

	// If no content, include the "No posts found" template.
	
} else {
	get_template_part( 'template-parts/content/content-none' );
}
echo '</div>'; //entry-content


get_footer();
