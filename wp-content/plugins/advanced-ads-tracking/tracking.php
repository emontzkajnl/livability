<?php
/**
 * Advanced Ads – Tracking
 *
 * Plugin Name:       Advanced Ads – Tracking
 * Plugin URI:        https://wpadvancedads.com/add-ons/tracking/
 * Description:       Track ad impressions and clicks.
 * Version:           2.8.2
 * Author:            Advanced Ads GmbH
 * Author URI:        https://wpadvancedads.com
 * Text Domain:       advanced-ads-tracking
 * Domain Path:       /languages
 */

// return if we already have a version running, instead of showing error.
if ( defined( 'AAT_IMP_SHORTCODE' ) ) {
	return;
}

// load basic path and url to the plugin
require_once __DIR__ . '/bootstrap.php';

/**
 * Halt code remove with new release.
 *
 * @return void
 */
function wp_advads_tracking_halt_code() {
	global $advads_halt_notices;

	if ( version_compare( ADVADS_VERSION, '2.0.0', '>=' ) ) {
		if ( ! isset( $advads_halt_notices ) ) {
			$advads_halt_notices = [];
		}
		$advads_halt_notices[] = __( 'Advanced Ads – Tracking', 'advanced-ads-tracking' );

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
					<h2><?php esc_html_e( 'Important Notice', 'advanced-ads-tracking' ); ?></h2>
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
					<h3><?php esc_html_e( 'The following addons are affected:', 'advanced-ads-tracking' ); ?></h3>
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
						__( 'Your version of <strong>Advanced Ads – Tracking</strong> is incompatible with <strong>Advanced Ads 2.0</strong> and has been deactivated. Please update the plugin to the latest version. If you cannot update the plugin, e.g., due to an expired license, you can <a href="%1$s">roll back to a compatible version of the Advanced Ads plugin</a> at any time or <a href="%2$s">renew your license</a>.', 'advanced-ads-pro' ),
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
	require_once __DIR__ . '/addon-init.php';
}

add_action( 'plugins_loaded', 'wp_advads_tracking_halt_code', 5 );

