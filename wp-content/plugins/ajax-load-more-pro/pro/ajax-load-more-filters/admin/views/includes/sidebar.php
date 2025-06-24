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
			<h3><?php esc_html_e( 'Shortcode Output', 'ajax-load-more-filters' ); ?> <a title="<?php esc_html_e( 'Use the following shortcode to generate this Ajax Load More Filter instance.', 'ajax-load-more-filters' ); ?> <?php _e( 'Don\'t forget to update the target parameter with the Ajax Load More ID', 'ajax-load-more-filters' ); ?>" href="javascript:void(0)" class="fa fa-question-circle tooltip"></a></h3>
			<div class="cta-inner no-side-padding">
				<div class="output-wrap" style="margin-top: 0;">
					<textarea id="shortcode_output">[ajax_load_more_filters id="{{ data[0].id }}" target="YOUR_ALM_ID"]</textarea>
				</div>
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
			do_action( 'alm_facet_sidebar_status', $filter_id );
		}
	}
	?>
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
