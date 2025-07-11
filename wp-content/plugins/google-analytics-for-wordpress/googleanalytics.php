<?php

/**
 * Plugin Name:         Google Analytics for WordPress by MonsterInsights
 * Plugin URI:          https://www.monsterinsights.com/?utm_source=liteplugin&utm_medium=pluginheader&utm_campaign=pluginurl&utm_content=7%2E0%2E0
 * Description:         The best Google Analytics plugin for WordPress. See how visitors find and use your website, so you can keep them coming back.
 * Author:              MonsterInsights
 * Author URI:          https://www.monsterinsights.com/lite/?utm_source=liteplugin&utm_medium=pluginheader&utm_campaign=authoruri&utm_content=7%2E0%2E0
 *
 * Version:             9.6.0
 * Requires at least:   5.6.0
 * Requires PHP:        7.2
 *
 * License:             GPL v3
 *
 * Text Domain:         google-analytics-for-wordpress
 * Domain Path:         /languages
 *
 * MonsterInsights Lite
 * Copyright (C) 2008-2024, MonsterInsights, support@monsterinsights.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category            Plugin
 * @copyright           Copyright © 2018 Chris Christoff
 * @author              Chris Christoff
 * @package             MonsterInsights
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @author  Chris Christoff
 * @access public
 */
final class MonsterInsights_Lite {


	/**
	 * Holds the class object.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var object Instance of instantiated MonsterInsights class.
	 */
	public static $instance;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $version Plugin version.
	 */
	public $version = '9.6.0';
	/**
	 * Plugin file.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $file PHP File constant for main file.
	 */
	public $file;

	/**
	 * The name of the plugin.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $plugin_name Plugin name.
	 */
	public $plugin_name = 'MonsterInsights Lite';

	/**
	 * Unique plugin slug identifier.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $plugin_slug Plugin slug.
	 */
	public $plugin_slug = 'monsterinsights-lite';

	/**
	 * Holds instance of MonsterInsights License class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_License $license Instance of License class.
	 */
	protected $license;

	/**
	 * Holds instance of MonsterInsights Admin Notice class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_Admin_Notice $notices Instance of Admin Notice class.
	 */
	public $notices;

	/**
	 * Holds instance of MonsterInsights Reporting class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_Reporting $reporting Instance of Reporting class.
	 */
	public $reporting;

	/**
	 * Holds instance of MonsterInsights Notifications class.
	 *
	 * @since 7.11
	 * @access public
	 * @var MonsterInsights_Notifications $notifications Instance of Notifications class.
	 */
	public $notifications;

	/**
	 * Holds instance of MonsterInsights Notification Events
	 *
	 * @since 7.12.3
	 * @access public
	 * @var MonsterInsights_Notification_Event $notification_event Instance of MonsterInsights_Notification_Event class.
	 */
	public $notification_event;

	/**
	 * Holds instance of MonsterInsights Auth class.
	 *
	 * @since 7.0.0
	 * @access public
	 * @var MonsterInsights_Auth $auth Instance of Auth class.
	 */
	protected $auth;

	/**
	 * Holds instance of MonsterInsights API Auth class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_Auth $api_auth Instance of APIAuth class.
	 */
	public $api_auth;

	/**
	 * Holds instance of MonsterInsights API Rest Routes class.
	 *
	 * @since 7.4.0
	 * @access public
	 * @var MonsterInsights_Rest_Routes $routes Instance of rest routes.
	 */
	public $routes;

	/**
	 * The tracking mode used in the frontend.
	 *
	 * @since 7.15.0
	 * @accces public
	 * @var string
	 * @deprecated Since 8.3 with the removal of ga compatibility
	 */
	public $tracking_mode;

	/**
	 * Setup checklist class property.
	 *
	 * @var MonsterInsights_Setup_Checklist
	 */
	public $setup_checklist;

	/**
	 * Primary class constructor.
	 *
	 * @since 6.0.0
	 * @access public
	 */
	public function __construct() {
		// We don't use this
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @access public
	 * @return object The MonsterInsights_Lite object.
	 * @since 6.0.0
	 *
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof MonsterInsights_Lite ) ) {
			self::$instance       = new MonsterInsights_Lite();
			self::$instance->file = __FILE__;

			// Detect Pro version and return early
			if ( defined( 'MONSTERINSIGHTS_PRO_VERSION' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'monsterinsights_pro_notice' ) );

				return self::$instance;
			}

			// Define constants
			self::$instance->define_globals();

			// Load in settings
			self::$instance->load_settings();

			// Compatibility check
			if ( ! self::$instance->check_compatibility() ) {
				return self::$instance;
			}

			// Load in Licensing
			self::$instance->load_licensing();

			// Load in Auth
			self::$instance->load_auth();

			// Load files
			self::$instance->require_files();

			// This does the version to version background upgrade routines and initial install
			$mi_version = get_option( 'monsterinsights_current_version', '5.5.3' );
			if ( version_compare( $mi_version, '8.13.0', '<' ) ) {
				monsterinsights_lite_call_install_and_upgrade();
			}

			// Load admin only components.
			if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
				self::$instance->notices            = new MonsterInsights_Notice_Admin();
				self::$instance->reporting          = new MonsterInsights_Reporting();
				self::$instance->api_auth           = new MonsterInsights_API_Auth();
				self::$instance->routes             = new MonsterInsights_Rest_Routes();
				self::$instance->notifications      = new MonsterInsights_Notifications();
				self::$instance->notification_event = new MonsterInsights_Notification_Event();
				self::$instance->setup_checklist    = new MonsterInsights_Setup_Checklist();
			}

			if ( monsterinsights_is_pro_version() ) {
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'pro/includes/load.php';
			} else {
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/load.php';
			}
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @return void
	 * @since 6.0.0
	 * @access public
	 *
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'google-analytics-for-wordpress' ), '6.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * Attempting to wakeup an MonsterInsights instance will throw a doing it wrong notice.
	 *
	 * @return void
	 * @since 6.0.0
	 * @access public
	 *
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'google-analytics-for-wordpress' ), '6.0.0' );
	}

	/**
	 * Magic get function.
	 *
	 * We use this to lazy load certain functionality. Right now used to lazyload
	 * the API & Auth frontend, so it's only loaded if user is using a plugin
	 * that requires it.
	 *
	 * @return void
	 * @since 7.0.0
	 * @access public
	 *
	 */
	public function __get( $key ) {
		if ( $key === 'auth' ) {
			if ( empty( self::$instance->auth ) ) {
				// LazyLoad Auth for Frontend
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/auth.php';
				self::$instance->auth = new MonsterInsights_Auth();
			}

			return self::$instance->$key;
		} else {
			return self::$instance->$key;
		}
	}

	/**
	 * Check compatibility with PHP and WP, and display notices if necessary
	 *
	 * @return bool
	 * @since 8.0.0
	 */
	private function check_compatibility() {
		if ( defined( 'MONSTERINSIGHTS_FORCE_ACTIVATION' ) && MONSTERINSIGHTS_FORCE_ACTIVATION ) {
			return true;
		}

		require_once plugin_dir_path( __FILE__ ) . 'includes/compatibility-check.php';
		$compatibility = MonsterInsights_Compatibility_Check::get_instance();
		$compatibility->maybe_display_notice();

		return $compatibility->is_php_compatible() && $compatibility->is_wp_compatible();
	}

	/**
	 * Define MonsterInsights constants.
	 *
	 * This function defines all of the MonsterInsights PHP constants.
	 *
	 * @return void
	 * @since 6.0.0
	 * @access public
	 *
	 */
	public function define_globals() {

		if ( ! defined( 'MONSTERINSIGHTS_VERSION' ) ) {
			define( 'MONSTERINSIGHTS_VERSION', $this->version );
		}

		if ( ! defined( 'MONSTERINSIGHTS_LITE_VERSION' ) ) {
			define( 'MONSTERINSIGHTS_LITE_VERSION', MONSTERINSIGHTS_VERSION );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_NAME' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_NAME', $this->plugin_name );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_SLUG' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_SLUG', $this->plugin_slug );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_FILE' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_FILE', $this->file );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_DIR' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_DIR', plugin_dir_path( $this->file ) );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_URL' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_URL', plugin_dir_url( $this->file ) );
		}

	}

	/**
	 * Output a nag notice if the user has both Lite and Pro activated
	 *
	 * @access public
	 * @return    void
	 * @since 6.0.0
	 *
	 */
	public function monsterinsights_pro_notice() {
		$url = admin_url( 'plugins.php' );
		// Check for MS dashboard
		if ( is_network_admin() ) {
			$url = network_admin_url( 'plugins.php' );
		}
		?>
		<div class="error">
			<p><?php echo sprintf(esc_html__('Please %1$suninstall%2$s the MonsterInsights Lite Plugin. Your Pro version of MonsterInsights may not work as expected until the Lite version is uninstalled.', 'google-analytics-for-wordpress'), '<a href="' . $url . '">', '</a>'); // phpcs:ignore ?></p>
		</div>
		<?php
	}

	/**
	 * Loads MonsterInsights settings
	 *
	 * Adds the items to the base object, and adds the helper functions.
	 *
	 * @return void
	 * @since 6.0.0
	 * @access public
	 *
	 */
	public function load_settings() {
		global $monsterinsights_settings;
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/options.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/helpers.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/deprecated.php';
		$monsterinsights_settings = monsterinsights_get_options();
	}


	/**
	 * Loads MonsterInsights License
	 *
	 * Loads license class used by MonsterInsights
	 *
	 * @return void
	 * @since 7.0.0
	 * @access public
	 *
	 */
	public function load_licensing() {
		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/license-compat.php';
			self::$instance->license = new MonsterInsights_License_Compat();
		}
	}

	/**
	 * Loads MonsterInsights Auth
	 *
	 * Loads auth used by MonsterInsights
	 *
	 * @return void
	 * @since 7.0.0
	 * @access public
	 *
	 */
	public function load_auth() {
		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/auth.php';
			self::$instance->auth = new MonsterInsights_Auth();
		}
	}

	/**
	 * Loads all files into scope.
	 *
	 * @access public
	 * @return    void
	 * @since 6.0.0
	 *
	 */
	public function require_files() {

		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/capabilities.php';

		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {

			// Lite and Pro files
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/ajax.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/admin.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/common.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/notice.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/licensing/autoupdate.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/review.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/setup-checklist.php';

			// Pages
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/settings.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/tools.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/reports.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/addons.php';

			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/api-auth.php';

			// Reports
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reports/abstract-report.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reports/overview.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reports/site-summary.php';

			// Reporting Functionality
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reporting.php';

			// Routes used by Vue
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/routes.php';

			// Load gutenberg editor functions
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/gutenberg/gutenberg.php';

			// Emails
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/emails/class-emails.php';

			// Notifications class.
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/notifications.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/notification-event.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/notification-event-runner.php';
			// Add notification manual events for lite version.
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/notifications/notification-events.php';
		}

		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/exclude-page-metabox.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/frontend/verified-badge/Controller.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/site-notes/Controller.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/api-request.php';

		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			// Late loading classes (self instantiating)
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/tracking.php';
		}

		if (is_admin()) {
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/class-monsterinsights-am-deactivation-survey.php';
			add_action('admin_menu', function () {

				new \MonsterInsights_AM_Deactivation_Survey(
					apply_filters(
						'monsterinsights_deactivation_survey_url',
						'https://monsterinsights.com/wp-json/am-deactivate-survey/v1/deactivation-data'
					),
					'MonsterInsights Lite',
					'google-analytics-for-wordpress'
				);
			}, 100);
		}

		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/frontend/frontend.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/frontend/seedprod.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/measurement-protocol-v4.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/feature-feedback/class-monsterInsights-feature-feedback.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/class-monsterinsights-onboarding.php';
	}

	/**
	 * Get the tracking mode for the frontend scripts.
	 *
	 * @return string
	 * @deprecated Since 8.3 with the removal of ga compatibility
	 */
	public function get_tracking_mode() {

		if ( ! isset( $this->tracking_mode ) ) {
			// This will already be set to 'analytics' to anybody already using the plugin before 7.15.0.
			$this->tracking_mode = monsterinsights_get_option( 'tracking_mode', 'gtag' );
		}

		return $this->tracking_mode;
	}
}

/**
 * Fired when the plugin is activated.
 *
 * @access public
 *
 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false otherwise.
 *
 * @return void
 * @global object $wpdb The WordPress database object.
 * @since 6.0.0
 *
 * @global int $wp_version The version of WordPress for this install.
 */
function monsterinsights_lite_activation_hook( $network_wide ) {
	$url = admin_url( 'plugins.php' );
	// Check for MS dashboard
	if ( is_network_admin() ) {
		$url = network_admin_url( 'plugins.php' );
	}

	if ( class_exists( 'MonsterInsights' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die(sprintf(esc_html__('Please uninstall and remove MonsterInsights Pro before activating Google Analytics for WordPress by MonsterInsights. The Lite version has not been activated. %1$sClick here to return to the Dashboard%2$s.', 'google-analytics-by-wordpress'), '<a href="' . $url . '">', '</a>')); // phpcs:ignore
	}

	require_once plugin_dir_path( __FILE__ ) . 'includes/compatibility-check.php';
	$compatibility = MonsterInsights_Compatibility_Check::get_instance();
	$compatibility->maybe_deactivate_plugin( plugin_basename( __FILE__ ) );

	// Add transient to trigger redirect.
	set_transient( '_monsterinsights_activation_redirect', 1, 30 );

	// Hook to trigger when plugin activate.
	do_action( 'monsterinsights_plugin_activated' );
}

register_activation_hook( __FILE__, 'monsterinsights_lite_activation_hook' );

/**
 * Fired when the plugin is uninstalled.
 *
 * @access public
 * @return    void
 * @since 6.0.0
 *
 */
function monsterinsights_lite_uninstall_hook() {
	wp_cache_flush();

	// Note, if both MI Pro and Lite are active, this is an MI Pro instance
	// Therefore MI Lite can only use functions of the instance common to
	// both plugins. If it needs to be pro specific, then include a file that
	// has that method.
	$instance = MonsterInsights();

	$instance->define_globals();
	$instance->load_settings();

	// If uninstalling via wp-cli load admin-specific files only here.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		define( 'WP_ADMIN', true );
		$instance->require_files();
		$instance->load_auth();
		$instance->notices   = new MonsterInsights_Notice_Admin();
		$instance->reporting = new MonsterInsights_Reporting();
		$instance->api_auth  = new MonsterInsights_API_Auth();
	}

	// Don't delete any data if the PRO version is already active.
	if ( monsterinsights_is_pro_version() ) {
		return;
	}

	require_once 'includes/admin/uninstall.php';

	if ( is_multisite() ) {
		$site_list = get_sites();
		foreach ( (array) $site_list as $site ) {
			switch_to_blog( $site->blog_id );

			// Deauthenticate.
			$instance->api_auth->delete_auth();

			// Delete report cache.
			$instance->reporting->delete_aggregate_data();

			// Delete options.
			$instance->api_auth->uninstall_auth();

			restore_current_blog();
		}
		// Delete network auth using a custom function as some variables are not initiated.
		$instance->api_auth->uninstall_network_auth();

		// Delete network data.
		$instance->reporting->delete_aggregate_data( 'network' );
	} else {
		// Delete auth.
		$instance->api_auth->delete_auth();

		// Delete report cache.
		$instance->reporting->delete_aggregate_data();

		// Delete options.
		$instance->api_auth->uninstall_auth();
	}

	// Clear notification cron schedules.
	$schedules = wp_get_schedules();

	if ( is_array( $schedules ) && ! empty( $schedules ) ) {
		foreach ( $schedules as $key => $value ) {
			if ( 0 === strpos( $key, 'monsterinsights_notification_' ) ) {
				$cron_hook = implode( '_', explode( '_', $key, -2 ) ) . '_cron';
				wp_clear_scheduled_hook( $cron_hook );
			}
		}
	}

	// Delete the notifications data.
	$instance->notifications->delete_notifications_data();

	// Popular posts.
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/popular-posts/class-popular-posts-themes.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/popular-posts/class-popular-posts.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/popular-posts/class-popular-posts-helper.php';
	// Lite popular posts specific.
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-inline.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-cache.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-widget.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-widget-sidebar.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-ajax.php';

	// Delete Popular Posts data.
	MonsterInsights_Popular_Posts_Inline()->get_cache()->delete_data();
	MonsterInsights_Popular_Posts_Widget()->get_cache()->delete_data();

	// Delete other options.
	monsterinsights_uninstall_remove_options();
}

register_uninstall_hook( __FILE__, 'monsterinsights_lite_uninstall_hook' );

/**
 * The main function responsible for returning the one true MonsterInsights_Lite
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $monsterinsights = MonsterInsights_Lite(); ?>
 *
 * @return MonsterInsights_Lite The singleton MonsterInsights_Lite instance.
 * @uses MonsterInsights_Lite::get_instance() Retrieve MonsterInsights_Lite instance.
 *
 * @since 6.0.0
 *
 */
function MonsterInsights_Lite() {
	return MonsterInsights_Lite::get_instance();
}

/**
 * MonsterInsights Install and Updates.
 *
 * This function is used install and upgrade MonsterInsights. This is used for upgrade routines
 * that can be done automatically, behind the scenes without the need for user interaction
 * (for example pagination or user input required), as well as the initial install.
 *
 * @return void
 * @global string $wp_version WordPress version (provided by WordPress core).
 * @uses MonsterInsights_Lite::load_settings() Loads MonsterInsights settings
 * @uses MonsterInsights_Install::init() Runs upgrade process
 *
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_lite_install_and_upgrade() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/compatibility-check.php';
	$compatibility = MonsterInsights_Compatibility_Check::get_instance();

	// If the WordPress site doesn't meet the correct WP or PHP version requirements, don't activate MonsterInsights
	if ( ! $compatibility->is_php_compatible() || ! $compatibility->is_wp_compatible() ) {
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			return;
		}
	}

	// Don't run if MI Pro is installed
	if ( class_exists( 'MonsterInsights' ) ) {
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			return;
		}
	}

	// Load settings and globals (so we can use/set them during the upgrade process)
	MonsterInsights_Lite()->define_globals();
	MonsterInsights_Lite()->load_settings();

	// Load in Auth
	MonsterInsights()->load_auth();

	// Load upgrade file
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/install.php';

	// Run the MonsterInsights upgrade routines
	$updates = new MonsterInsights_Install();
	$updates->init();
}

/**
 * MonsterInsights check for install and update processes.
 *
 * This function is used to call the MonsterInsights automatic upgrade class, which in turn
 * checks to see if there are any update procedures to be run, and if
 * so runs them. Also installs MonsterInsights for the first time.
 *
 * @return void
 * @uses MonsterInsights_Install() Runs install and upgrade process.
 *
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_lite_call_install_and_upgrade() {
	add_action( 'wp_loaded', 'monsterinsights_lite_install_and_upgrade' );
}

/**
 * Returns the MonsterInsights combined object that you can use for both
 * MonsterInsights Lite and Pro Users. When both plugins active, defers to the
 * more complete Pro object.
 *
 * Warning: Do not use this in Lite or Pro specific code (use the individual objects instead).
 * Also do not use in the MonsterInsights Lite/Pro upgrade and install routines.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Prevents the need to do conditional global object logic when you have code that you want to work with
 * both Pro and Lite.
 *
 * Example: <?php $monsterinsights = MonsterInsights(); ?>
 *
 * @return MonsterInsights The singleton MonsterInsights instance.
 * @uses MonsterInsights::get_instance() Retrieve MonsterInsights Pro instance.
 * @uses MonsterInsights_Lite::get_instance() Retrieve MonsterInsights Lite instance.
 *
 * @since 6.0.0
 *
 */
if ( ! function_exists( 'MonsterInsights' ) ) {
	function MonsterInsights() {
		return ( class_exists( 'MonsterInsights' ) ? MonsterInsights_Pro() : MonsterInsights_Lite() );
	}

	add_action( 'plugins_loaded', 'MonsterInsights' );
}

/**
 * Remove scheduled cron hooks during deactivation.
 */
function monsterinsights_lite_deactivation_hook() {
	wp_clear_scheduled_hook( 'monsterinsights_usage_tracking_cron' );
	wp_clear_scheduled_hook( 'monsterinsights_email_summaries_cron' );

	// Hook to trigger on deactivation.
	do_action( 'monsterinsights_plugin_deactivated' );
}

register_deactivation_hook( __FILE__, 'monsterinsights_lite_deactivation_hook' );
