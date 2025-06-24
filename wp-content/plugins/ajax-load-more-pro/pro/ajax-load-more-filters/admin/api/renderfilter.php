<?php
/**
 * Render Filter REST API endpoint for displaying a filter as PHP.
 *
 * @package ALMFilters
 */
add_action(
	'rest_api_init',
	function () {
		$my_namespace = 'alm-filters';
		$my_endpoint  = '/renderfilter';
		register_rest_route(
			$my_namespace,
			$my_endpoint,
			[
				'methods'             => 'POST',
				'callback'            => 'renderfilter',
				'permission_callback' => '__return_true',
			]
		);
	}
);

/**
 * Get the filter data as PHP
 *
 * @param WP_REST_Request $request The HTTP request object.
 * @return void
 * @since 1.0
 */
function renderfilter( WP_REST_Request $request ) {
	// Get contents of request and convert to array
	$data = (array) json_decode( $request->get_body() );

	// Pluck the filter ID from the data.
	$filter_id = $data['id'];

	// Get the option and unserialize.
	$filter = $filter_id ? unserialize( get_option( 'alm_filter_' . $filter_id ) ) : '';

	if ( ! $filter ) {
		wp_send_json(
			[
				'success' => false,
				'msg'     => __( 'Error accessing filter.', 'ajax-load-more-filters ' ),
				'code'    => '',
			]
		);
	}
	wp_send_json(
		[
			'success' => true,
			'msg'     => '',
			'code'    => wp_json_encode( $filter, JSON_PRETTY_PRINT ),
		]
	);
}
