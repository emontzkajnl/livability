<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

use AdvancedAds\Abstracts\Ad;
use AdvancedAds\Framework\Utilities\Params;

/**
 * Allow serving ads on external URLs.
 */
class Advanced_Ads_Pro_Module_Ad_Server {
	/**
	 * Advanced_Ads_Pro_Module_Ad_Server constructor.
	 */
	public function __construct() {
		// Register frontend AJAX calls.
		add_action( 'wp_ajax_aa-server-select', [ $this, 'get_placement' ] );
		add_action( 'wp_ajax_nopriv_aa-server-select', [ $this, 'get_placement' ] );
		add_filter( 'advanced-ads-set-wrapper', [ $this, 'ad_wrapper' ], 10, 2 );

		// Add allowed HTTP origins.
		if ( wp_doing_ajax() ) {
			add_filter( 'allowed_http_origins', [ $this, 'add_allowed_origins' ] );
		}
	}

	/**
	 * Add a wrapper to served top level ads
	 *
	 * @param array $wrapper existing wrapper data.
	 * @param Ad    $ad      the ad.
	 *
	 * @return array
	 */
	public function ad_wrapper( $wrapper, $ad ) {
		$placement = $ad->get_root_placement();

		if ( ! $placement || ! $placement->is_type( 'server' ) ) {
			return $wrapper;
		}
		if ( ! $ad->is_top_level() ) {
			return $wrapper;
		}
		if ( ! is_array( $wrapper ) || ! isset( $wrapper['id'] ) ) {
			$wrapper['id'] = $ad->create_wrapper_id();
		}

		return $wrapper;
	}

	/**
	 * Load placement content
	 *
	 * Based on Advanced_Ads_Ajax::advads_ajax_ad_select()
	 */
	public function get_placement() {
		$options           = Advanced_Ads_Pro::get_instance()->get_options();
		$block_no_referrer = ! empty( $options['ad-server']['block-no-referrer'] ); // True if option is set.

		// Prevent direct access through the URL.
		if ( $block_no_referrer && ! Params::server( 'HTTP_REFERER' ) ) {
			die( 'direct access forbidden' );
		}

		// Set correct frontend headers.
		header( 'X-Robots-Tag: noindex,nofollow' );
		header( 'Content-Type: text/html; charset=UTF-8' );

		$embedding_urls = $this->get_embedding_urls();

		// Cross Origin Resource Sharing.
		if ( ! empty( $embedding_urls ) ) {
			$embedding_urls_string = implode( ' ', $embedding_urls );
			header( 'Content-Security-Policy: frame-ancestors ' . $embedding_urls_string );
			foreach ( $embedding_urls as $url ) {
				$parsed_url = wp_parse_url( $url );
				$scheme     = isset( $parsed_url['scheme'] ) ? $parsed_url['scheme'] . '://' : 'https://';
				header( 'Access-Control-Allow-Origin: ' . $scheme . $parsed_url['host'] );
			}
		}

		$public_slug = Params::request( 'p', null );
		if ( empty( $public_slug || ! is_string( $public_slug ) ) ) {
			die( 'missing p parameter' );
		}

		// Get placement output by public slug.
		$placement_content = $this->get_placement_output_by_public_slug( $public_slug );
		include __DIR__ . '/views/frontend-template.php';

		die();
	}

	/**
	 * Modify the ad object before serving
	 *
	 * @param false|string $override overridden ad output.
	 * @param Ad           $ad       the ad.
	 *
	 * @return false
	 */
	public function override_ad_object( $override, $ad ) {
		/**
		 * We need to force the ad to open in a new window when the link is created through Advanced Ads. Otherwise,
		 * clicking the ad in an iframe would load the target page in the iframe, too.
		 *
		 * 1. The Tracking add-on has a dedicated option on the ad edit page for this.
		 * We are setting it to open in a new window here and ignore the options the user might have set.
		 */
		$ad->set_prop_temp( 'tracking.target', 'new' );

		// Ignore consent settings for ad-server ads.
		$ad->set_prop_temp( 'privacy.ignore-consent', 'on' );

		/**
		 * 2. The Advanced Ads plugin adds target="_blank" based on a global option
		 * We change force that option to open ads in a new window by hooking into the advanced-ads-options filter below.
		 */
		add_filter(
			'advanced-ads-options',
			function ( $options ) {
				$options['target-blank'] = 1;

				return $options;
			}
		);

		return false;
	}

	/**
	 * Get the content of a placement based on the public slug.
	 *
	 * @param string $public_slug placement ID or public slug.
	 */
	private function get_placement_output_by_public_slug( $public_slug = '' ) {
		if ( '' === $public_slug ) {
			return '';
		}

		$placement = wp_advads_get_placement( $public_slug );

		// Return placement if there is one with public_slug being the placement ID.
		if ( $placement ) {
			add_filter( 'advanced-ads-ad-select-override-by-ad', [ $this, 'override_ad_object' ], 10, 2 );
			return $placement->output();
		}

		// Load all placements.
		$placements = wp_advads_get_placements();

		// Iterate through "ad-server" placements and look for the one with the public slug.
		foreach ( $placements as $placement ) {
			if ( $placement->is_type( 'server' ) && $public_slug === $placement->get_prop( 'ad-server-slug' ) ) {
				add_filter( 'advanced-ads-ad-select-override-by-ad', [ $this, 'override_ad_object' ], 10, 3 );

				return $placement->output();
			}
		}
	}

	/**
	 * Add allowed HTTP origins.
	 * Needed for the JavaScript-based implementation of the placement.
	 *
	 * @param array $origins Allowed HTTP origins.
	 * @return array $origins Allowed HTTP origins.
	 */
	public function add_allowed_origins( $origins ) {

		$embedding_urls = $this->get_embedding_urls();

		if ( is_array( $embedding_urls ) && count( $embedding_urls ) ) {
			$origins = array_merge( $origins, $embedding_urls );
		}
		return $origins;
	}

	/**
	 * Get the embedding URL array
	 *
	 * @return array $embedding_urls.
	 */
	public function get_embedding_urls() {
		$options              = Advanced_Ads_Pro::get_instance()->get_options();
		$embedding_url_option = isset( $options['ad-server']['embedding-url'] ) ? $options['ad-server']['embedding-url'] : false;

		$embedding_urls_raw = explode( ',', $embedding_url_option );

		$embedding_urls = [];
		foreach ( $embedding_urls_raw as $_url ) {
			$embedding_urls[] = esc_url_raw( $_url );
		}

		return $embedding_urls;
	}
}
