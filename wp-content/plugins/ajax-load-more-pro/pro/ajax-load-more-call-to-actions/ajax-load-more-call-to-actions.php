<?php
/**
 * Plugin Name: Ajax Load More: Call to Actions
 * Plugin URI: http://connekthq.com/plugins/ajax-load-more/call-to-actions/
 * Description: Ajax Load More add-on for displaying advertisements and call to actions.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: http://connekthq.com
 * Version: 1.1.1
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALM_CTA
 */

define( 'ALM_CTA_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_CTA_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_CTA_VERSION', '1.1.1' );
define( 'ALM_CTA_RELEASE', 'June 9, 2025' );

if ( ! class_exists( 'ALM_CTA' ) ) :
	/**
	 * ALM Call to Action Class.
	 */
	class ALM_CTA {

		/**
		 * Construct class.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'alm_cta_installed', [ $this, 'alm_cta_installed' ] );
			add_filter( 'alm_cta_shortcode', [ $this, 'alm_cta_shortcode' ], 10, 5 );
			add_action( 'alm_cta_inc', [ $this, 'alm_cta_inc' ], 10, 8 );
			add_filter( 'alm_cta_pos_array', [ $this, 'alm_cta_pos_array' ], 10, 6 );
		}

		/**
		 * Build array of CTA position values.
		 *
		 * @author ConnektMedia
		 * @param string $seo_start_page  Start page number.
		 * @param string $page            Current page number.
		 * @param string $posts_per_page  Posts per Page.
		 * @param string $alm_found_posts Total posts variable.
		 * @param string $cta_val         The value for the CTA.
		 * @param string $paging          True/false.
		 * @return array
		 */
		public function alm_cta_pos_array( $seo_start_page, $page, $posts_per_page, $alm_found_posts, $cta_val, $paging ) {
			if ( 'true' === $paging ) {
				$cta_array[] = $cta_val;

			} elseif ( $seo_start_page > 1 && $page < $seo_start_page ) {
				// If is SEO first load.
				$posts_per_page = floor( $posts_per_page / $seo_start_page ); // Get orginal $posts_per_page value.
				$pages          = $alm_found_posts / $posts_per_page;
				for ( $i = 0; $i < $pages; $i++ ) {
					$cta_array[] = $cta_val + ( $i * $posts_per_page );
				}
			} else {
				$cta_array[] = $cta_val;
			}
			return $cta_array;
		}

		/**
		 * Build shortcode params and send back to core ALM.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 * @param string $cta                Is CTA true/false.
		 * @param string $cta_position       Position of the CTA.
		 * @param string $cta_repeater       Repeater Template.
		 * @param string $cta_theme_repeater Theme Repeater.
		 * @return string
		 */
		public function alm_cta_shortcode( $cta, $cta_position, $repeater, $theme_repeater, $template ) {
			if ( ! $repeater && ! $theme_repeater ) {
				return;
			}
			$shortcode  = ' data-cta="' . $cta . '"';
			$shortcode .= ' data-cta-position="' . $cta_position . '"';
			if ( $repeater ) {
				$shortcode .= ' data-cta-repeater="' . $repeater . '"'; // To be deprecated.
			}
			if ( $theme_repeater ) {
				$shortcode .= ' data-cta-theme-repeater="' . $theme_repeater . '"'; // To be deprecated.
			}
			if ( $template ) {
				$shortcode .= ' data-cta-template="' . $template . '"';
			}
			return $shortcode;
		}

		/**
		 * Get CTA.
		 *
		 * @author ConnektMedia
		 * @param string  $repeater        Repeater Template.
		 * @param string  $theme_repeater  Theme Repeater.
		 * @param int     $alm_found_posts Total posts.
		 * @param int     $alm_page        Current page variable.
		 * @param int     $alm_item        Item in loop vairable.
		 * @param int     $alm_current     The current element variable.
		 * @param boolean $preloaded       Is this Preloaded?.
		 * @param array   $args            Query args.
		 * @return HTMLElement|string
		 */
		public function alm_cta_inc( $repeater, $theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $preloaded = false, $args = [], $template = '' ) {
			if ( $preloaded ) {
				ob_start();
			}
			if ( $theme_repeater && has_filter( 'alm_get_theme_repeater' ) ) {
				// Theme Repeater.
				do_action( 'alm_get_theme_repeater', $theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $args );

			} else {
				// Standard Repeaters.
				$file = alm_get_current_repeater( $repeater, alm_get_repeater_type( $repeater ) );
				include $file;
			}

			if ( $preloaded ) {
				return ob_get_clean();
			}
		}

		/**
		 * An empty function to determine if preload is true.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function alm_cta_installed() {
			// Empty.
		}

		/**
		 * Create the settings panel.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function alm_cta_settings() {
			register_setting(
				'alm_cta_license',
				'alm_cta_license_key',
				'alm_cta_sanitize_license'
			);
		}
	}

	/**
	 * Sanitize our license activation
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 * @param string $new The license key.
	 * @return string
	 */
	function alm_cta_sanitize_license( $new ) {
		$old = get_option( 'alm_cta_license_key' );
		if ( $old && $new !== $old ) {
			// New license has been entered, must reactivate.
			delete_option( 'alm_cta_license_status' );
		}
		return $new;
	}

	/**
	 * The main function responsible for returning Ajax Load More ALternating Templates.
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 * @return class
	 */
	function alm_cta() {
		global $alm_cta;
		if ( ! isset( $alm_cta ) ) {
			$alm_cta = new ALM_CTA();
		}
		return $alm_cta;
	}

	alm_cta();

endif;


/**
 * Software Licensing.
 *
 * @author ConnektMedia
 * @since 1.0
 */
function alm_cta_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_cta_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			[
				'version' => ALM_CTA_VERSION,
				'license' => $license_key,
				'item_id' => ALM_CTA_ITEM_NAME,
				'author'  => 'Darren Cooney',
			]
		);
	}
}
add_action( 'admin_init', 'alm_cta_plugin_updater', 0 );
