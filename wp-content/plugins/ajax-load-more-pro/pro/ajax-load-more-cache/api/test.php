<?php
/**
 * Custom /test route to text REST API access.
 *
 * @package ajax-load-more-cache
 * @deprecated 3.0
 * @version 2.0
 */

add_action(
	'rest_api_init',
	function () {
		$my_namespace = 'ajax-load-more/cache';
		$my_endpoint  = '/test';
		register_rest_route(
			$my_namespace,
			$my_endpoint,
			[
				'methods'             => 'POST',
				'callback'            => 'alm_cache_test',
				'permission_callback' => function () {
					return true;
				},
			]
		);
	}
);

/**
 * Test REST API access
 *
 * @return mixed JSON response.
 */
function alm_cache_test() {
	// Access is enable, send the response.
	$response = [ 'success' => true ];

	// Send response as JSON.
	wp_send_json( $response );
}
