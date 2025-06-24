<?php
/**
 * Save REST API endpoint for saving filters.
 *
 * @package ALMFilters
 */

/**
 * Init Endpoint.
 */
add_action(
	'rest_api_init',
	function () {
		$my_namespace = 'alm-filters';
		$my_endpoint  = '/facets/status';
		register_rest_route(
			$my_namespace,
			$my_endpoint,
			[
				'methods'             => 'POST',
				'callback'            => 'alm_filters_facet_status',
				'permission_callback' => '__return_true',
			]
		);
	}
);

/**
 * Get the facet status.
 *
 * @param WP_REST_Request $request The HTTP request object.
 * @return void
 * @since 1.0
 */
function alm_filters_facet_status( WP_REST_Request $request ) {
	$data      = json_decode( $request->get_body(), true );
	$filter_id = isset( $data['filter_id'] ) ? $data['filter_id'] : 0;

	if ( ! $filter_id ) {
		wp_send_json(
			[
				'success' => false,
			]
		);
	}

	// Get the status of the facet.
	$status_option = unserialize( get_option( ALM_Facets::get_facet_option_name( $filter_id, 'status' ) ) );

	// Check the queued status of the filter.
	$is_queued = get_option( ALM_Facets::get_facet_option_name( $filter_id, 'queued' ) );

	// Set the default status
	$status = $status_option['status'] ?? 'complete';

	if ( $is_queued === 'true' ) {
		$status = 'queued'; // Queued option found, set the status.
	}

	$data = [
		'success'     => true,
		'status'      => $status,
		'paged'       => $status_option['paged'] ?? 0,
		'pages'       => $status_option['pages'] ?? 0,
		'found_posts' => $status_option['found_posts'] ?? 0,
	];

	switch ( $status ) {
		case 'queued':
			$data['html'] = ALM_Facet_Sidebar::facet_index_queued();
			break;
		case 'processing':
			$data['html'] = ALM_Facet_Sidebar::facet_index_processing( $data );
			break;
		default:
			$data['html'] = ALM_Facet_Sidebar::get_facet_index_stats( $filter_id );
			break;
	}

	// Send the response.
	wp_send_json( $data );
}
