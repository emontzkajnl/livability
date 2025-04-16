<?php
/**
 * The template for displaying the Filters siderbar.
 *
 * @since 1.0
 * @package ALMFilters
 */

?>
<aside class="cnkt-sidebar" data-sticky>
	<?php if ( $editing ) { ?>
		<div class="cta">
			<h3><?php esc_html_e( 'Shortcode Output', 'ajax-load-more' ); ?> <a title="<?php esc_html_e( 'Use the following shortcode to generate this Ajax Load More filter instance', 'ajax-load-more-filters' ); ?>" href="javascript:void(0)" class="fa fa-question-circle tooltip"></a></h3>
			<div class="cta-inner no-side-padding">
				<div class="output-wrap" style="margin-top: 0;">
					<textarea id="shortcode_output">[ajax_load_more_filters id="{{ data[0].id }}" target="YOUR_ALM_ID"]</textarea>
				</div>
				<p style="font-size: 12px; padding: 12px 7px 0 0; margin: 0;">
				<?php _e( 'Don\'t forget to update the <strong>target</strong> parameter with the Ajax Load More ID', 'ajax-load-more-filters' ); ?>.</p>
			</div>
			<div class="major-publishing-actions">
				<a class="button button-primary copy copy-to-clipboard" data-copied="<?php esc_html_e( 'Copied!', 'ajax-load-more-filters' ); ?>">
					<?php esc_html_e( 'Copy Shortcode', 'ajax-load-more-filters' ); ?>
				</a>
				<a class="button" v-on:click="showOutput($event)">
					<?php esc_html_e( 'Generate PHP', 'ajax-load-more-filters' ); ?>
				</a>
			</div>
		</div>
		<?php
		if ( $has_facets ) {
			// TODO: Add background processing, so we can display the facet index stats here.
			$index = ALMFilters::get_facet_index_by_id( $filter_id );
			?>
			<div class="cta">
				<header class="cta--header">
					<h3><?php esc_html_e( 'Facet Status', 'ajax-load-more-filters' ); ?></h3>
					<?php
					if ( function_exists( 'alm_status_icon' ) ) {
						if ( $index ) {
							echo wp_kses_post( alm_status_icon( 'success', '', __( 'Facet index is ready!', 'ajax-load-more-filters' ) ) );
						} else {
							echo wp_kses_post( alm_status_icon( 'failed', '', '' ) );
						}
					}
					?>
				</header>
				<div class="cta-inner no-side-padding">
					<?php
					if ( $index ) {
						$count              = count( $index );
						$indexed_post_types = wp_list_pluck( $index, 'post_type' );
						$post_types         = $indexed_post_types ? array_count_values( $indexed_post_types ) : [];
						if ( $post_types ) {
							arsort( $post_types ); // Sort array by number value.
							/* translators: %1$s is the filter ID, %2$s is the index count */
							echo '<p>' . sprintf( wp_kses_post( __( 'The <span class="alm-pre">%1$s</span> facet index contains a total of <span class="alm-pre">%2$s</span> posts indexed for the following post types:', 'ajax-load-more' ) ), $filter_id, $count ) . '</p>';
							echo '<div class="alm-stats">';
							foreach ( $post_types as $post_type => $count ) {
								$post_type_object = get_post_type_object( $post_type );
								/* translators: %1$s is count, %2$s is the post type label */
								$title = sprintf( wp_kses_post( __( '%1$s %2$s found in the facet index.', 'ajax-load-more' ) ), $count, $post_type_object->label );
								echo '<div title="' . wp_kses_post( $title ) . '">';
								echo '<div class="alm-stats--stat">' . esc_attr( $count ) . '</div>';
								echo '<div>' . esc_attr( $post_type_object->label ) . '</div>';
								echo '</div>';
							}
							echo '</div>';
						}
					} else {
						?>
					<p><?php esc_html_e( 'The facet index contains zero indexed posts or is failing due to permission issues with the server.', 'ajax-load-more-failing' ); ?></p>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
	<?php
	if ( 'new' === $section ) {
		// require_once ALM_FILTERS_PATH . 'admin/views/cta/filter-list.php';
	}
	if ( 'edit' === $section ) {
		// require_once ALM_FILTERS_PATH . 'admin/views/cta/template-tags.php';
	}
	require_once ALM_FILTERS_PATH . 'admin/views/cta/help.php';
	?>
</aside>
