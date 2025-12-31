<?php
/**
 * Cache API functions.
 *
 * @package ajax-load-more-cache
 * @version 1.0.
 */

/**
 * Custom API route for creating a cached file from params.
 *
 * @author ConnektMedia <support@connekthq.com>
 * @since 2.0
 */

add_action(
	'rest_api_init',
	function () {
		$my_namespace = 'ajax-load-more/cache';
		$my_endpoint  = '/create';
		register_rest_route(
			$my_namespace,
			$my_endpoint,
			[
				'methods'             => 'POST',
				'callback'            => 'alm_cache_create',
				'args'                => [],
				'permission_callback' => function () {
					return true;
				},
			]
		);
	}
);

/**
 * Create Cache from Ajax/HTML data.
 *
 * @param WP_REST_Request $request Rest request object.
 */
function alm_cache_create( WP_REST_Request $request ) {
	$params     = $request->get_params(); // Pluck data from request.
	$cache_id   = isset( $params['cache_id'] ) ? $params['cache_id'] : '';
	$html       = isset( $params['html'] ) ? trim( stripcslashes( $params['html'] ) ) : false;
	$facets     = isset( $params['facets'] ) ? $params['facets'] : [];
	$postcount  = isset( $params['postcount'] ) ? $params['postcount'] : 1;
	$totalposts = isset( $params['totalposts'] ) ? $params['totalposts'] : 1;
	$paging     = isset( $params['paging'] ) ? $params['paging'] : false;

	// Paging cache creation.
	if ( $paging ) {
		ALMCache::create_cache(
			$cache_id,
			$paging
		);

		// Send the response.
		return new WP_REST_Response(
			[
				'success' => true,
				'msg'     => __( 'Cache created successfully for:', 'ajax-load-more-cache' ) . ' ' . $cache_id,
			],
			200
		);
	}

	// Handle missing data.
	if ( ! $cache_id || ! $html ) {
		return new WP_REST_Response(
			[
				'success' => false,
				'msg'     => __( 'An error has occurred while creating the Ajax Load More Cache.', 'ajax-load-more-cache' ),
			],
			401
		);
	}

	$data = [
		'html' => $html,
		'meta' => [
			'postcount'  => $postcount,
			'totalposts' => $totalposts,
		],
	];

	// Include facets if present.
	if ( ! empty( $facets ) ) {
		$data['facets'] = $facets;
	}

	ALMCache::create_cache(
		$cache_id,
		$data
	);

	// Send the response.
	return new WP_REST_Response(
		[
			'success' => true,
			'msg'     => __( 'Cache created successfully for:', 'ajax-load-more-cache' ) . ' ' . $cache_id,
		],
		200
	);
}
