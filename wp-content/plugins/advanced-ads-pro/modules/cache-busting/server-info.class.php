<?php // phpcs:ignoreFile

use AdvancedAds\Abstracts\Ad;
use AdvancedAds\Framework\Utilities\Params;

/**
 * Reduce the number of AJAX calls: during the first AJAX call, save the data (in cookies),
 * that cannot be checked using only JS. Later, the passive cache-busting can check that data.
 *
 * There are 2 ways to update the data array:
 * 1. Define the 'ADVANCED_ADS_PRO_USER_COOKIE_MAX_AGE' constant (in seconds).
 * An ajax requests will be initiated from time to time to update the expired conditions of the ads on the page.
 * 2. Use the "Update visitor conditions cache in the user's browsers" option (Settings > Pro > Cache Busting ) and update the page cache.
 * An ajax request will be initiated to update all the conditions of the ads on the page.
 */
class Advanced_Ads_Pro_Cache_Busting_Server_Info {

	/**
	 * Holds the Cache Busting class.
	 *
	 * @var Advanced_Ads_Pro_Module_Cache_Busting
	 */
	public $cache_busting;

	/**
	 * The options array.
	 *
	 * @var array
	 */
	public $options;

	/**
	 * How long the server info cookie will be stored maximum.
	 *
	 * @var int
	 */
	public $server_info_duration;

	/**
	 * How long the server info cookie will be stored maximum.
	 *
	 * @var int
	 */
	public $vc_cache_reset;

	/**
	 * If we are in a current AJAX call.
	 *
	 * @var bool
	 */
	public $is_ajax;

	/**
	 * The constructor.
	 *
	 * @param Advanced_Ads_Pro_Module_Cache_Busting $cache_busting Cache Busting instance.
	 * @param array                                 $options Option array.
	 */
	public function __construct( $cache_busting, $options ) {
		$this->cache_busting = $cache_busting;
		$this->options = $options;

		$this->server_info_duration = defined( 'ADVANCED_ADS_PRO_USER_COOKIE_MAX_AGE' ) ? absint( ADVANCED_ADS_PRO_USER_COOKIE_MAX_AGE ) : MONTH_IN_SECONDS;
		$this->vc_cache_reset = ! empty( $this->options['vc_cache_reset'] ) ? absint( $this->options['vc_cache_reset'] ) : 0;

		$this->is_ajax = ! empty( $cache_busting->is_ajax );
		new Advanced_Ads_Pro_Cache_Busting_Visitor_Info_Cookie( $this );
	}

	/**
	 * Get ajax request that will be used in case required cookies do not exist.
	 *
	 * @param array $ads  An array of Ad objects.
	 * @param array $args Ad arguments.
	 *
	 * @return void|array
	 */
	public function get_ajax_for_passive_placement( $ads, $args, $elementid ) {
		if ( ! $this->server_info_duration ) {
			return;
		}

		if ( ! is_array( $ads ) ) {
			$ads = [ $ads ];
		}

		$server_c = [];
		foreach ( $ads as $ad ) {
			$ad_server_c = $this->get_server_conditions( $ad );
			if ( $ad_server_c ) {
				$server_c = array_merge( $server_c, $ad_server_c );
			}
		}

		if ( ! $server_c ) { return; }

		$query = Advanced_Ads_Pro_Module_Cache_Busting::build_js_query( $args);

		return [
			'ajax_query' => Advanced_Ads_Pro_Module_Cache_Busting::get_instance()->get_ajax_query( array_merge( $query, [
				'elementid' => $elementid,
				'server_conditions' => $server_c
			] ) ),
			'server_info_duration' => $this->server_info_duration,
			'server_conditions' => $server_c,
		];

	}

	/**
	 * Get server conditions of the ad.
	 *
	 * @param $ad Ad
	 * @return array
	 */
	private function get_server_conditions( Ad $ad ) {
		$visitors = $ad->get_visitor_conditions();
		$visitors =  ! empty( $visitors ) && is_array( $visitors ) ? array_values( $visitors ) : [];
		$result = [];
		foreach ( $visitors as $k => $visitor ) {
			if ( $info = $this->get_server_condition_info( $visitor ) ) {
				$visitor_to_add = array_intersect_key( $visitor, [ 'type' => true, $info['hash_fields'] => true ] );
				$result[ $info['hash'] ] = $visitor_to_add;
			}

		}
		return $result;
	}

	/**
	 * Get info about the server condition.
	 *
	 * @param array $visitor Visitor condition.
	 * @return array|void info about server condition.
	 */
	public function get_server_condition_info( $visitor ) {
		if ( ! isset( $visitor['type'] ) ) {
			return;
		}

		$conditions = $this->get_all_server_conditions();
		if ( ! isset( $conditions[ $visitor['type'] ]['passive_info']['function'] ) ) {
			// It's not a server condition.
			return;
		}
		$info = $conditions[ $visitor['type'] ]['passive_info'];

		$hash = $visitor['type'];

		// Add unique fields set on the Ad edit page.
		// This allows us to to have several conditions of the same type.
		if ( isset( $info['hash_fields'] ) && isset( $visitor[ $info['hash_fields'] ] ) ) {
			$hash .= '_' . $visitor[ $info['hash_fields'] ];
		}
		// Allow the administrator to remove all cookies in the user's browsers.
		$hash .= '_' . $this->vc_cache_reset;

		$hash = substr( md5( $hash ), 0, 10 );
		return [ 'hash' => $hash, 'function' => $info['function'], 'hash_fields' => $info['hash_fields'] ];
	}

	/**
	 * Get all server conditions.
	 */
	public function get_all_server_conditions() {
		if ( ! $this->server_info_duration ) {
			return [];
		}
		if ( ! did_action( 'init' ) ) {
			// All conditions should be ready.
			trigger_error( sprintf( '%1$s was called incorrectly', 'Advanced_Ads_Pro_Cache_Busting_Server_Info::get_all_server_conditions' ) );
		}
		$r = [];
		foreach ( Advanced_Ads_Visitor_Conditions::get_instance()->conditions as $name => $condition ) {
			if ( isset( $condition['passive_info'] ) ) {
				$r[ $name ] = $condition;
			}
		}
		return $r;
	}

}

/**
 * Cache Bust: Visitor info cookie
 */
class Advanced_Ads_Pro_Cache_Busting_Visitor_Info_Cookie {
	// Note: hard-coded in JS.
	const VISITOR_INFO_COOKIE_NAME = 'advanced_ads_visitor';

	/**
	 * Holds the server info class.
	 *
	 * @var Advanced_Ads_Pro_Cache_Busting_Server_Info
	 */
	private $server_info;

	public function __construct( $server_info ) {
		$this->server_info = $server_info;

		if ( ! $this->can_set_cookie() ) {
			// Remove cookie.
			if ( $this->parse_existing_cookies() ) {
				$this->set_cookie( false );
			}
			return;
		}

		if ( $this->server_info->is_ajax ) {
			add_action( 'init', [ $this, 'add_server_info' ], 50 );
		}

		if ( ! empty( $this->server_info->options['vc_cache_reset_actions']['login'] ) ) {
			add_action( 'wp_logout', [ $this, 'log_in_out' ] );
			add_action( 'set_auth_cookie', [ $this, 'log_in_out' ] );
		}
	}

	/**
	 * Create cookies during AJAX requests.
	 */
	public function add_server_info() {
		$request = Params::request( 'deferedAds', [], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( empty( $request ) ) {
			return;
		}

		$e_cookie = $n_cookie = $this->parse_existing_cookies();

		// Parse ajax request.
		foreach ( $request as $query ) {
			if ( ! isset( $query['ad_method'] ) || $query['ad_method'] !== 'placement' || empty( $query['server_conditions'] ) ) {
				// The query does not have server conditions.
				continue;
			}

			// Prepare new cookies to save.
			$n_cookie = $this->prepare_new_cookies( $query['server_conditions'], $n_cookie );
		}

		$n_cookie['vc_cache_reset'] = $this->server_info->vc_cache_reset;

		if ( $n_cookie !== $e_cookie ) {
			$this->set_cookie( $n_cookie );
		}
	}

	/**
	 * Get correct and not obsolete conditions.
	 */
	private function parse_existing_cookies() {
		$n_cookie = [];

		if ( Params::cookie( self::VISITOR_INFO_COOKIE_NAME ) ) {
			$n_cookie['vc_cache_reset'] = $this->server_info->vc_cache_reset;
			$e_cookie                   = Params::cookie( self::VISITOR_INFO_COOKIE_NAME );
			$e_cookie                   = wp_unslash( $e_cookie );
			$e_cookie                   = json_decode( $e_cookie, true );

			if ( isset( $e_cookie['browser_width'] ) ) {
				$n_cookie['browser_width'] = $e_cookie['browser_width'];
			}

			if ( isset( $e_cookie['vc_cache_reset'] ) && absint( $e_cookie['vc_cache_reset'] ) < $this->server_info->vc_cache_reset ) {
				// The cookie has been reset on the Settings page.
				return $n_cookie;
			}
			if ( empty( $e_cookie['conditions'] ) || ! is_array( $e_cookie['conditions'] ) ) {
				return $n_cookie;
			}

			foreach ( $e_cookie['conditions'] as $cond_name => $hashes ) {
				foreach ( (array) $hashes as $hash => $item ) {
					// Do not add outdated conditions.
					if ( isset( $item['time'] ) && ( absint( $item['time'] ) + $this->server_info->server_info_duration ) > time() ) {
						$n_cookie['conditions'][ $cond_name ][ $hash ] = $item;
					}
				}
			}
		}
		return $n_cookie;
	}

	/**
	 * Prepare new conditions to save.
	 *
	 * @param array $visitors New visitor conditions to add to cookie.
	 * @param array $n_cookie Existing visitor conditions from cookie.
	 *
	 * @return array $n_cookie New cookie.
	 */
	public function prepare_new_cookies( $visitors, $n_cookie = [] ) {
		foreach ( (array) $visitors as $visitor ) {
			$info = $this->server_info->get_server_condition_info( $visitor );
			if ( ! $info ) { continue; }
			if ( isset( $n_cookie['conditions'][ $visitor['type'] ][ $info['hash'] ] ) ) { continue; }

			$n_cookie['conditions'][ $visitor['type'] ][ $info['hash'] ] = [
				'data' => call_user_func( $info['function'], $visitor ),
				'time' => time(),
			];
		}
		return $n_cookie;
	}

	/**
	 * Check if the cookie can be set.
	 */
	public function can_set_cookie() {
		return $this->server_info->server_info_duration;
	}

	/**
	 * Set cookie.
	 *
	 * @param array|bool $cookie Cookie.
	 */
	public function set_cookie( $cookie ) {
		if ( ! $cookie ) {
			setrawcookie( self::VISITOR_INFO_COOKIE_NAME, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
			return;
		}

		$cookie = json_encode( $cookie );
		$cookie = rawurlencode( $cookie );

		if ( strlen( $cookie ) > 4096 ) {
			Advanced_Ads::log( 'The cookie size is too large' );
			return;
		}

		// Prevent spaces from being converted to '+'
		setrawcookie( self::VISITOR_INFO_COOKIE_NAME, $cookie, time() + $this->server_info->server_info_duration, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Remove server info on log in/out.
	 */
	public function log_in_out() {
		$server_conditions = $this->server_info->get_all_server_conditions();

		$n_cookie = $this->parse_existing_cookies();
		if ( isset( $n_cookie['conditions'] ) ) {
			foreach ( (array) $n_cookie['conditions'] as $cond_name => $cond ) {
				if ( isset( $server_conditions[ $cond_name ]['passive_info']['remove'] )
					&& $server_conditions[ $cond_name ]['passive_info']['remove'] === 'login' ) {
					unset ( $n_cookie['conditions'][ $cond_name ] );
				}
			}
		}

		$this->set_cookie( $n_cookie );
	}
}
