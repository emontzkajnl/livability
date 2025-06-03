<?php
/**
 * Advanced Ads – Sticky Ads
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Ads – Sticky Ads
 * Plugin URI:        http://wpadvancedads.com/add-ons/sticky-ads/
 * Description:       Advanced ad positioning.
 * Version:           1.8.7
 * Author:            Advanced Ads GmbH
 * Author URI:        http://wpadvancedads.com
 * Text Domain:       advanced-ads-sticky
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'AASADS_FILE', __FILE__ );
define( 'AASADS_VERSION', '1.8.7' );
define( 'AASADS_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AASADS_BASE_DIR', dirname( plugin_basename( __FILE__ ) ) );
define( 'AASADS_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'AASADS_SLUG', 'advanced-ads-sticky-ads' );
define( 'AASADS_PLUGIN_URL', 'https://wpadvancedads.com' );
define( 'AASADS_PLUGIN_NAME', 'Sticky Ads' );

/**
 * Load the plugin.
 *
 * @return void
 */
function load_sticky_ads() {
	include_once plugin_dir_path( __FILE__ ) . 'classes/plugin.php';
	include_once plugin_dir_path( __FILE__ ) . 'public/public.php';

	( new Advanced_Ads_Sticky( is_admin(), wp_doing_ajax() ) );

	// -TODO this basically renders for admin and for ajax (and is not needed for the latter)
	if ( is_admin() ) {
		include_once plugin_dir_path( __FILE__ ) . 'admin/admin.php';
		( new Advanced_Ads_Sticky_Admin() );
	}
}

/**
 * Halt code remove with new release.
 *
 * @return void
 */
function wp_advads_sticky_ads_halt_code() {
	global $advads_halt_notices;

	if ( version_compare( ADVADS_VERSION, '2.0.0', '>=' ) ) {
		if ( ! isset( $advads_halt_notices ) ) {
			$advads_halt_notices = [];
		}
		$advads_halt_notices[] = __( 'Advanced Ads – Sticky Ads', 'advanced-ads-sticky' );

		add_action(
			'all_admin_notices',
			static function () {
				global $advads_halt_notices;

				// Early bail!!
				if ( 'plugins' === get_current_screen()->base || empty( $advads_halt_notices ) ) {
					return;
				}
				?>
				<div class="notice notice-error">
					<h2><?php esc_html_e( 'Important Notice', 'advanced-ads-sticky' ); ?></h2>
					<p>
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %s: Plugin name */
								__( 'Your versions of the Advanced Ads addons listed below are incompatible with <strong>Advanced Ads 2.0</strong> and have been deactivated. Please update them to their latest version. If you cannot update, e.g., due to an expired license, you can <a href="%1$s">roll back to a compatible version of the Advanced Ads plugin</a> at any time or <a href="%2$s">renew your license</a>.', 'advanced-ads-tracking' ),
								esc_url( admin_url( 'admin.php?page=advanced-ads-tools&sub_page=version' ) ),
								'https://wpadvancedads.com/account/#h-licenses'
							)
						)
						?>
					</p>
					<h3><?php esc_html_e( 'The following addons are affected:', 'advanced-ads-sticky' ); ?></h3>
					<ul>
						<?php foreach ( $advads_halt_notices as $notice ) : ?>
							<li><strong><?php echo esc_html( $notice ); ?></strong></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php
				$advads_halt_notices = [];
			}
		);

		add_action(
			'after_plugin_row_' . plugin_basename( __FILE__ ),
			static function () {
				echo '<tr class="active"><td colspan="5" class="plugin-update colspanchange">';
				wp_admin_notice(
					sprintf(
						/* translators: %s: Plugin name */
						__( 'Your version of <strong>Advanced Ads – Sticky Ads</strong> is incompatible with <strong>Advanced Ads 2.0</strong> and has been deactivated. Please update the plugin to the latest version. If you cannot update the plugin, e.g., due to an expired license, you can <a href="%1$s">roll back to a compatible version of the Advanced Ads plugin</a> at any time or <a href="%2$s">renew your license</a>.', 'advanced-ads-pro' ),
						esc_url( admin_url( 'admin.php?page=advanced-ads-tools&sub_page=version' ) ),
						'https://wpadvancedads.com/account/#h-licenses'
					),
					[
						'type'               => 'error',
						'additional_classes' => array( 'notice-alt', 'inline', 'update-message' ),
					]
				);
				echo '</td></tr>';
			}
		);
		return;
	}

	// Autoload and activate.
	load_sticky_ads();
}

add_action( 'plugins_loaded', 'wp_advads_sticky_ads_halt_code', 5 );

