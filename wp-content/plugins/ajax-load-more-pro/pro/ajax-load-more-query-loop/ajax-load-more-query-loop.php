<?php
/**
 * Plugin Name: Ajax Load More: Query Loop
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/query-loop/
 * Description: Ajax Load More add-on for infinite scrolling Query Loop blocks.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: https://connekthq.com
 * Version: 1.0.1
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALMQueryLoop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ALM_QUERY_LOOP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_QUERY_LOOP_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_QUERY_LOOP_VERSION', '1.0.1' );
define( 'ALM_QUERY_LOOP_RELEASE', 'June 9, 2025' );

if ( ! class_exists( 'ALM_QUERY_LOOP' ) ) :
	/**
	 * Initiate the class.
	 */
	class ALM_QUERY_LOOP {

		/**
		 * Construct class function.
		 */
		public function __construct() {
			add_action( 'alm_query_loop_installed', [ &$this, 'alm_query_loop_installed' ] );
			add_action( 'alm_query_loop_settings', [ &$this, 'alm_query_loop_settings' ] );
		}

		/**
		 * Get the query loop next/previous link.
		 *
		 * @param array  $atts  The block attributes array.
		 * @param object $block The block object.
		 * @param string $dir   The direction of the link.
		 * @return string
		 */
		public static function alm_query_loop_get_link( $atts, $block, $dir = 'next' ) {
			$data = '';
			switch ( $dir ) {
				case 'next':
					$data = render_block_core_query_pagination_next( $atts, '', $block );
					break;

				case 'previous':
					$data = render_block_core_query_pagination_previous( $atts, '', $block );
					break;
			}
			if ( ! $data ) {
				return '';
			}

			// Get the page link then use preg_match to extract the href.
			return preg_match( '/href="([^"]*)"/', $data, $matches ) ? $matches[1] : '';
		}

		/**
		 * Render a config object for the Query Loop via `<pre/>` tag.
		 *
		 * @param string $query_id The query ID.
		 * @param array  $atts     The block attributes array.
		 * @param object $block    The block object.
		 * @return void
		 */
		public static function alm_query_loop_config( $query_id, $attributes, $block ) {
			$data = [
				'prev'    => self::alm_query_loop_get_link( $attributes, $block, 'previous' ),
				'next'    => self::alm_query_loop_get_link( $attributes, $block, 'next' ),
				'queryId' => $query_id,
				'paged'   => $_GET[ 'query-' . $query_id . '-page' ] ?? 1,
			];

			echo '<pre data-rel="ajax-load-more" id="ajax-load-more-query-loop-' . esc_attr( $query_id ) . '" style="display: none;">' . esc_js( wp_json_encode( $data ) ) . '</pre>';
		}

		/**
		 * Query Loop setup. Add queryloop="true" to the shortcode.
		 *
		 * @param array $data The data array.
		 * @return string
		 */
		public static function alm_query_loop_setup( $data ) {
			$shortcode = $data['config'];
			$id        = $data['id'];
			$shortcode = str_replace( ']', ' queryloop="true" queryloop_id="' . $id . '"]', $shortcode ); // Add queryloop="true" to the shortcode.
			return $shortcode;
		}

		/**
		 * Create the Previous Post settings panel.
		 *
		 * @since 1.0.0
		 */
		public function alm_query_loop_settings() {
			register_setting(
				'alm_query_loop_license',
				'alm_query_loop_license_key',
				'alm_query_loop_sanitize_license'
			);
		}
	}

	/**
	 * Sanitize license activation.
	 *
	 * @param string $key The new license key.
	 */
	function alm_query_loop_sanitize_license( $key ) {
		$old = get_option( 'alm_query_loop_license_key' );
		if ( $old && $old !== $key ) {
			delete_option( 'alm_query_loop_license_status' );
		}
		return $key;
	}

	/**
	 * The main function responsible for returning Ajax Load More Query Loop.
	 *
	 * @since 1.0
	 */
	function alm_query_loop() {
		global $alm_query_loop;
		if ( ! isset( $alm_query_loop ) ) {
			$alm_query_loop = new ALM_QUERY_LOOP();
		}
		return $alm_query_loop;
	}
	alm_query_loop();

endif;


/**
 * Software Licensing.
 *
 * @since 1.0
 */
function alm_query_loop_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_query_loop_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			[
				'version' => ALM_QUERY_LOOP_VERSION,
				'license' => $license_key,
				'item_id' => defined( 'ALM_QUERY_LOOP_ITEM_NAME' ) ? ALM_QUERY_LOOP_ITEM_NAME : 0,
				'author'  => 'Darren Cooney',
			]
		);
	}
}
add_action( 'admin_init', 'alm_query_loop_updater', 0 );
