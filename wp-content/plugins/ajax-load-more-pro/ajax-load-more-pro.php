<?php
/**
 * Plugin Name: Ajax Load More: Pro
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/pro/
 * Description: All the add-ons for Ajax Load More in a single installation.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: https://connekthq.com
 * Version: 1.4.6
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALMPro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALM_PRO_VERSION', '1.4.6' );
define( 'ALM_PRO_RELEASE', 'December 11, 2025' );

define( 'ALM_PRO_ADMIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_PRO_ADDON_PATH', plugin_dir_path( __FILE__ ) . 'pro/' );
define( 'ALM_PRO_ADMIN_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_PRO_OPTION_PREFIX', 'alm_pro_status_' );

/**
 * Plugin installation hook
 *
 * @return void
 */
function alm_pro_install() {
	global $ajax_load_more;
	if ( ! $ajax_load_more ) {
		return false;
	}

	if ( method_exists( $ajax_load_more, 'alm_return_addons' ) ) {
		$addons  = $ajax_load_more->alm_return_addons(); // Get all addons.
		$plugins = array_merge(
			$addons,
			[
				[
					'path' => 'ajax-load-more-repeaters-v2',
				],
				[
					'path' => 'ajax-load-more-theme-repeaters',
				],
			]
		); // Add legacy addons.

		foreach ( $plugins as $plugin ) {
			// Check if standalone addon is active.
			if ( is_plugin_active( $plugin['path'] . '/' . $plugin['path'] . '.php' ) ) {
				deactivate_plugins( $plugin['path'] . '/' . $plugin['path'] . '.php' ); // deactivate it.
			}

			// Set status option.
			if ( ! get_option( ALM_PRO_OPTION_PREFIX . $plugin['slug'] ) ) {
				update_option( ALM_PRO_OPTION_PREFIX . $plugin['slug'], 'active' );
			}
		}
	}
}
register_activation_hook( __FILE__, 'alm_pro_install' );

if ( ! class_exists( 'ALMPro' ) ) :

	/**
	 * ALM Pro Class.
	 */
	class ALMPro {

		/**
		 * Construct Class.
		 */
		public function __construct() {
			add_action( 'alm_pro_installed', [ $this, 'alm_pro_installed' ] );
			add_action( 'plugins_loaded', [ $this, 'alm_pro_load_addons' ] );
			add_action( 'wp_ajax_alm_pro_toggle_activation', [ $this, 'alm_pro_toggle_activation' ] );
			add_action( 'init', [ $this, 'alm_pro_textdomain' ] );
		}

		/**
		 * Load text domain.
		 *
		 * @return void
		 */
		public function alm_pro_textdomain() {
			load_plugin_textdomain( 'ajax-load-more-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Include these addons at runtime.
		 *
		 * @return void
		 */
		public function alm_pro_load_addons() {
			global $ajax_load_more;
			if ( ! $ajax_load_more || ! method_exists( $ajax_load_more, 'alm_return_addons' ) ) {
				return;
			}
			// Loop through each addon and include it.
			foreach ( $ajax_load_more->alm_return_addons() as $plugin ) {
				if ( ! has_action( $plugin['action'] ) ) {
					$option = get_option( ALM_PRO_OPTION_PREFIX . $plugin['slug'] ) ?? '';

					// Activate the add-on if active or option not yet set.
					if ( ! $option || $option === 'active' ) {
						update_option( $option, 'active' ); // Set option to active.
						require_once ALM_PRO_ADDON_PATH . $plugin['path'] . '/' . $plugin['path'] . '.php';
					}
				}
			}
		}

		/**
		 * Toggle active/inactive add-on states.
		 *
		 * @return void
		 */
		public function alm_pro_toggle_activation() {
			$nonce = $_POST['nonce'];
			$slug  = $_POST['slug'];

			if ( $slug && current_user_can( apply_filters( 'alm_custom_user_role', 'edit_theme_options' ) ) ) {
				// Check the nonce, don't match then bounce!
				if ( ! wp_verify_nonce( $nonce, 'alm_repeater_nonce' ) ) {
					die( esc_html__( 'Error - Unable to verify nonce.', 'ajax-load-more-pro' ) );
				}

				if ( get_option( ALM_PRO_OPTION_PREFIX . $slug ) !== 'active' ) {
					$result = 'active';
					update_option( ALM_PRO_OPTION_PREFIX . $slug, $result );
				} else {
					$result = 'inactive';
					update_option( ALM_PRO_OPTION_PREFIX . $slug, $result );
				}

				$return = [
					'success' => true,
					'slug'    => $slug,
					'result'  => $result,
					'msg'     => esc_html__( 'Add-on status updated', 'ajax-load-more-pro' ),
				];
				wp_send_json( $return );

			} else {
				$return = [
					'success' => false,
					'slug'    => $slug,
					'result'  => 'error',
					'msg'     => esc_html__( 'Add-on status NOT updated', 'ajax-load-more-pro' ),
				];
				wp_send_json( $return );

			}
			wp_die();
		}

		/**
		 * An empty function to determine if pro is true.
		 *
		 * @return bool
		 */
		public function alm_pro_installed() {
			return true;
		}

		/**
		 * Enqueue admin scripts.
		 *
		 * @return void
		 */
		public static function alm_enqueue_pro_admin_scripts() {
			wp_enqueue_script( 'alm-pro-admin', ALM_PRO_ADMIN_URL . '/admin/js/ajax-load-more-pro.js', [ 'jquery' ], ALM_PRO_VERSION, false );
		}

		/**
		 * Create the settings panel.
		 *
		 * @return void
		 */
		public function alm_pro_settings() {
			register_setting(
				'alm_pro_license',
				'alm_pro_license_key',
				'alm_pro_sanitize_license'
			);
			add_settings_section(
				'alm_pro_settings',
				'Pro Settings',
				'alm_pro_settings_callback',
				'ajax-load-more'
			);
		}
	}


	/**
	 * Sanitize our license activation
	 *
	 * @param string $new The license key.
	 * @return string
	 */
	function alm_pro_sanitize_license( $new ) {
		$old = get_option( 'alm_pro_license_key' );
		if ( $old && $old != $new ) {
			delete_option( 'alm_proe_license_status' ); // new license has been entered, so must reactivate.
		}
		return $new;
	}

	/**
	 * The main function responsible for returning Ajax Load More Pro.
	 *
	 * @return void
	 */
	function ALMPro() {
		global $ALMPro;
		if ( ! isset( $ALMPro ) ) {
			$ALMPro = new ALMPro();
		}
		return $ALMPro;
	}
	ALMPro();

endif;

/**
 * Software Licensing.
 *
 * @return void
 */
function alm_pro_plugin_updater() {
	if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) || ! defined( 'ALM_STORE_URL' ) || ! defined( 'ALM_PRO_ITEM_NAME' ) ) {
		return false;
	}

	$edd_updater = new EDD_SL_Plugin_Updater(
		ALM_STORE_URL,
		__FILE__,
		[
			'version' => ALM_PRO_VERSION,
			'license' => trim( get_option( 'alm_pro_license_key' ) ),
			'item_id' => ALM_PRO_ITEM_NAME,
			'author'  => 'Darren Cooney',
		]
	);
}
add_action( 'admin_init', 'alm_pro_plugin_updater', 0 );
