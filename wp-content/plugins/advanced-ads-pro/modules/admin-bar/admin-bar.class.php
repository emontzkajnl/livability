<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Admin bar class
 *
 * @package     AdvancedAds\Pro
 * @author      Advanced Ads <info@wpadvancedads.com>
 */

use AdvancedAds\Utilities\Conditional;

/**
 * Admin bar functionality.
 */
class Advanced_Ads_Pro_Module_Admin_Bar {
	/**
	 * Constructor
	 */
	public function __construct() {
		if ( defined( 'ADVANCED_ADS_PRO_DISABLE_ADS_TOOLBAR_ITEM' ) && ADVANCED_ADS_PRO_DISABLE_ADS_TOOLBAR_ITEM ) {
			return;
		}

		// TODO: load options
		// Add admin bar item with current ads.
		if ( ! is_admin() ) {
			add_action( 'admin_bar_menu', [ $this, 'admin_bar_current_ads' ], 999 );
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 11 );
		add_action( 'wp_footer', [ $this, 'output_items' ], 21 );
	}

	/**
	 * Add admin bar menu with current displayed ads and ad groups.
	 *
	 * @since 1.0.0
	 * @param WP_Admin_Bar $wp_admin_bar Admin bar class.
	 */
	public function admin_bar_current_ads( $wp_admin_bar ) {
		// Early bail!!
		if ( ! Conditional::user_can( 'advanced_ads_edit_ads' ) || ! Advanced_Ads_Ad_Health_Notices::notices_enabled() ) {
			return;
		}

		// Add main menu item.
		$args = [
			'id'    => 'advads_current_ads',
			'title' => __( 'Ads', 'advanced-ads-pro' ),
			'href'  => false,
		];
		$wp_admin_bar->add_node( $args );

		$args = [
			'parent' => 'advads_current_ads',
			'id'     => 'advads_no_ads_found',
			'title'  => __( 'No Ads found', 'advanced-ads-pro' ),
			'href'   => false,
		];
		$wp_admin_bar->add_node( $args );
	}

	/**
	 * Enqueue the admin bar script.
	 */
	public function enqueue_scripts() {
		if ( ! is_admin_bar_showing() ) {
			return;
		}

		$uri_rel_path = AAP_BASE_URL . 'assets/js/';

		$deps = [ 'jquery' ];
		if ( wp_script_is( 'advanced-ads-pro/cache_busting' ) ) {
			$deps[] = 'advanced-ads-pro/cache_busting';
		}

		wp_enqueue_script( 'advanced-ads-pro/cache_busting_admin_bar', $uri_rel_path . 'admin_bar.js', $deps, AAP_VERSION, true );

		// Scrollable ads listing when ads long then windows height.
		$custom_inline_style = '#wp-admin-bar-advads_current_ads-default { overflow-y: auto; max-height:calc(100vh - 50px); } ';
		wp_add_inline_style( 'admin-bar', $custom_inline_style );
	}

	/**
	 * Output items that do not use cache-busting.
	 */
	public function output_items() {
		// Add item for each ad.
		$ads   = \AdvancedAds\Frontend\Stats::get()->entities ?? [];
		$nodes = [];

		foreach ( $ads as $_ad ) {
			// TODO: $type not used .
			// TODO: types are extendable through Advanced_Ads_Select.
			$type = '';
			switch ( $_ad['type'] ) {
				case 'ad':
					$type = esc_html__( 'ad', 'advanced-ads-pro' );
					break;
				case 'group':
					$type = esc_html__( 'group', 'advanced-ads-pro' );
					break;
				case 'placement':
					$type = esc_html__( 'placement', 'advanced-ads-pro' );
					break;
			}

			$nodes[] = [
				'title' => esc_html( $_ad['title'] ),
				'type'  => $type,
				'count' => $_ad['count'],
			];
		}

		$content = sprintf( '<script>window.advads_admin_bar_items = %s;</script>', wp_json_encode( $nodes ) );

		if ( class_exists( 'Advanced_Ads_Utils' ) && method_exists( 'Advanced_Ads_Utils', 'get_inline_asset' ) ) {
			$content = Advanced_Ads_Utils::get_inline_asset( $content );
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the output is already escaped, we can't escape it again without breaking the HTML.
		echo $content;
	}
}
