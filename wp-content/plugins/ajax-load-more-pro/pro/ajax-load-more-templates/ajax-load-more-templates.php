<?php
/**
 * Plugin Name: Ajax Load More: Templates
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/templates/
 * Description: Ajax Load More addon for creating and managing Repeater Templates.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: http://connekthq.com
 * Version: 1.0.0
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALMTemplates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALM_TEMPLATES_VERSION', '1.0.0' );
define( 'ALM_TEMPLATES_RELEASE', 'July 23, 2025' );
define( 'ALM_TEMPLATES_TABLE_NAME', 'alm_unlimited' );
define( 'ALM_TEMPLATES_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_TEMPLATES_URL', plugins_url( '', __FILE__ ) );

require_once 'functions.php';

/**
 * Plugin activation hook.
 *
 * @return void
 */
function alm_templates_activation( $network_wide ) {
	if ( is_plugin_active( 'ajax-load-more/ajax-load-more.php' ) ) {
		alm_templates_after_activation( $network_wide ); // Run activation function.
	}
}
register_activation_hook( __FILE__, 'alm_templates_activation' );
add_action( 'wpmu_new_blog', 'alm_templates_activation' );

if ( ! class_exists( 'ALMTemplates' ) ) :

	/**
	 * Templates class.
	 */
	class ALMTemplates {

		/**
		 * Constructor function.
		 */
		public function __construct() {
			add_action( 'admin_init', 'alm_templates_upgrade_routine' );
			add_action( 'alm_templates_installed', [ $this, 'alm_templates_installed' ] );
			add_action( 'alm_templates_settings', [ $this, 'alm_templates_settings' ] );
			add_action( 'init', [ $this, 'alm_templates_textdomain' ] );

			$this->includes();
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @return void
		 */
		public function alm_templates_textdomain() {
			load_plugin_textdomain( 'ajax-load-more-templates', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Load these files before the plugin loads.
		 *
		 * @return void
		 */
		public function includes() {
			require_once ALM_TEMPLATES_PATH . 'custom-repeaters/custom-repeaters.php';
			require_once ALM_TEMPLATES_PATH . 'theme-repeaters/theme-repeaters.php';
		}

		/**
		 * Create the Templates settings panel.
		 *
		 * @return void
		 */
		public function alm_templates_settings() {
			register_setting(
				'alm_templates_license',
				'alm_templates_license_key',
				'alm_templates_sanitize_license'
			);
			add_settings_section(
				'alm_templates_settings',
				__( 'Template Settings', 'ajax-load-more-templates' ),
				[ $this, 'alm_templates_callback' ],
				'ajax-load-more'
			);
			add_settings_field( // Theme Repeater directory.
				'_alm_theme_repeaters_dir',
				__( 'Directory Selection', 'ajax-load-more-templates' ),
				[ $this, 'alm_templates_dir_callback' ],
				'ajax-load-more',
				'alm_templates_settings'
			);
		}

		/**
		 * Template Settings Heading.
		 *
		 * @return void
		 */
		function alm_templates_callback() {
			echo '<p>' . __( 'Customize your installation of the <a href="http://connekthq.com/plugins/ajax-load-more/templates/">Templates</a> add-on.', 'ajax-load-more-templates' ) . '</p>';
		}

		/**
		 * Select directory for theme level repeaters.
		 *
		 * @return void
		 */
		function alm_templates_dir_callback() {
			$options = get_option( 'alm_settings' );
			if ( ! isset( $options['_alm_theme_repeaters_dir'] ) ) {
				$options['_alm_theme_repeaters_dir'] = '/alm_templates';
			}

			$theme = wp_get_theme();

			$html  = '<p>' . __( 'Select the directory that will hold your Theme Templates. All templates must be stored in a top level directory within your current theme folder.', 'ajax-load-more-templates' ) . '</p>';
			$html .= '<p class="notify">' . __( 'If a directory has not been specified, Ajax Load More will attempt to load templates from the <span><i class="fa fa-folder"></i> alm_templates</span> directory.', 'ajax-load-more-templates' ) . '</p>';
			$html .= '<div class="alm-dir-listing theme-repeaters"><ul>';
			$html .= '<p class="theme-title"><i class="fa fa-folder-open"></i> ' . $theme->get( 'Name' ) . '</p>';

			if ( is_child_theme() ) {
				$dir = new DirectoryIterator( get_stylesheet_directory() );
			} else {
				$dir = new DirectoryIterator( get_template_directory() );
			}

			$dir_array = [];
			foreach ( $dir as $fileinfo ) {
				if ( $fileinfo->isDir() && ! $fileinfo->isDot() ) {
					$dir_array[] = $fileinfo->getFilename();
				}
			}

			sort( $dir_array );
			foreach ( $dir_array as $directory ) {
				$html .= '<li>';
				if ( $directory === $options['_alm_theme_repeaters_dir'] ) {
					$html .= '<input type="radio" id="dir_' . $directory . '" name="alm_settings[_alm_theme_repeaters_dir]" value="' . $directory . '" checked="checked">';
				} else {
					$html .= '<input type="radio" id="dir_' . $directory . '" name="alm_settings[_alm_theme_repeaters_dir]" value="' . $directory . '">';
				}
				$html .= '<label for="dir_' . $directory . '"><i class="fa fa-folder"></i> ' . $directory . '</label></li>';
			}
			$html .= '</ul></div>';
			echo $html;
		}

		/**
		 * An empty function to determine if Templates is activated.
		 *
		 * @return void
		 */
		public function alm_templates_installed() {
			// Empty hook.
		}
	}

	/**
	 * Sanitize the license activation.
	 *
	 * @param string $new The license key.
	 * @return string     The license key.
	 */
	function alm_templates_sanitize_license( $new ) {
		$old = get_option( 'alm_templates_license_key' );
		if ( $old && $old !== $new ) {
			delete_option( 'alm_templates_license_status' );
		}
		return $new;
	}

	// Init the class.
	new ALMTemplates();

endif;

/**
 * Software Licensing.
 *
 * @return void
 */
function alm_templates_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) && defined( 'ALM_TEMPLATES_ITEM_NAME' ) && defined( 'ALM_STORE_URL' ) ) {
		$license_key = trim( get_option( 'alm_templates_license_key' ) ); // license key from the DB.
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			[
				'version' => ALM_TEMPLATES_VERSION,
				'license' => $license_key,
				'item_id' => ALM_TEMPLATES_ITEM_NAME,
				'author'  => 'Darren Cooney',
			]
		);
	}
}
add_action( 'admin_init', 'alm_templates_plugin_updater', 0 );
