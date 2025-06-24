<?php
/**
 * The background processing class to index facets.
 *
 * @see https://github.com/deliciousbrains/wp-background-processing
 * @package ALMFilters
 */

class ALM_Facet_Process extends ALM_Background_Process {
	/**
	 * @var string
	 */
	protected $prefix = 'alm';

	/**
	 * @var string
	 */
	protected $action = 'facet_process';

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $data ) {
		if ( ! $data || ! is_array( $data ) ) {
			return false;
		}

		$filter_id   = isset( $data['id'] ) ? trim( $data['id'] ) : '';
		$filter      = isset( $data['filter'] ) ? $data['filter'] : false;
		$paged       = isset( $data['paged'] ) ? (int) $data['paged'] : 1;
		$pages       = isset( $data['pages'] ) ? (int) $data['pages'] : 1;
		$found_posts = isset( $data['found_posts'] ) ? (int) $data['found_posts'] : 0;

		if ( ! $filter_id || ! $filter ) {
			return false; // Exit if missing.
		}

		if ( $paged === 1 ) {
			ALM_Facets::delete_facet_options( $filter_id ); // Delete the facet WP options if first page.
		}

		// Create the facet index for the current page.
		$new_data    = ALM_Facets::get_index( $filter, $paged ); // Create the facet index.
		$index       = $new_data && isset( $new_data['index'] ) ? $new_data['index'] : [];
		$found_posts = $new_data && isset( $new_data['found_posts'] ) ? $new_data['found_posts'] : 0;
		$option_name = ALM_Facets::get_facet_option_name( $filter_id );

		// Get the current index.
		$option = get_option( $option_name );
		if ( $option ) {
			// Merge the new index with the current index.
			$option = unserialize( $option );
			$index  = array_merge( $option, $index );
		}

		// Update the facet index.
		update_option( $option_name, serialize( $index ), false );

		// Update the facet status.
		update_option(
			ALM_Facets::get_facet_option_name( $filter_id, 'status' ),
			serialize(
				[
					'status'      => $paged === $pages ? 'complete' : 'processing',
					'paged'       => $paged,
					'pages'       => $pages,
					'found_posts' => $found_posts,
				]
			),
			false
		);

		// Delete queued status.
		ALM_Facets::delete_queued_status( $filter_id );

		// Delay for a second to avoid server overload and allow this to be filtered.
		sleep( apply_filters( 'alm_filters_facets_index_delay', 1 ) );
		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		parent::complete();
	}
}
