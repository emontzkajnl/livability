<?php
/**
 * Plugin Name: Ajax Load More: Cache
 * Plugin URI: http://connekthq.com/plugins/ajax-load-more/cache/
 * Description: Ajax Load More add-on that creates and serves static files from ajax requests.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: http://connekthq.com
 * Version: 3.1.1
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALMCache
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ALM_CACHE_VERSION', '3.1.1' );
define( 'ALM_CACHE_RELEASE', 'December 11, 2025' );

if ( ! class_exists( ' ALMCache' ) ) :

	/**
	 * Initiate the class.
	 */
	class ALMCache {

		/**
		 * ALM Admin Notices.
		 *
		 * @var array
		 */
		public $notices = [];

		/**
		 * Constructor function.
		 */
		public function __construct() {
			$this->init();

			add_action( 'alm_cache_installed', [ $this, 'alm_cache_installed' ] );
			add_action( 'alm_clear_cache', [ $this, 'alm_cache_delete' ] );

			add_action( 'admin_bar_menu', [ $this, 'alm_add_toolbar_items' ], 100 );
			add_action( 'wp_head', [ $this, 'alm_cache_adminbar_styles' ] );
			add_action( 'admin_head', [ $this, 'alm_cache_adminbar_styles' ] );

			add_action( 'alm_cache_settings', [ $this, 'alm_cache_settings' ] );
			add_action( 'admin_init', [ $this, 'alm_cache_3_1_upgrader' ] );
			add_action( 'current_screen', [ $this, 'handle_delete_action' ] );

			add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
			add_action( 'init', [ $this, 'alm_cache_create_publish_actions' ] );

			add_filter( 'alm_get_cache_array', [ $this, 'alm_get_cache_array' ], 10, 2 );
			add_filter( 'alm_cache_shortcode', [ $this, 'alm_cache_shortcode' ] );
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @return void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'ajax-load-more-cache', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Include these files in the admin.
		 *
		 * @return void
		 */
		public function init() {
			define( 'ALM_CACHE_ADMIN_PATH', plugin_dir_path( __FILE__ ) );
			define( 'ALM_CACHE_ADMIN_URL', plugins_url( '', __FILE__ ) );
			require_once 'api/create.php';
			require_once 'api/get.php';
		}

		/**
		 * Check if the current user has access to cache functions.
		 *
		 * @return bool
		 */
		public static function has_permissions() {
			return current_user_can( apply_filters( 'alm_user_role', 'edit_theme_options' ) );
		}

		/**
		 * Admin functions to handle cache deletion notice and action.
		 *
		 * @return void
		 */
		public function handle_delete_action() {
			if ( class_exists( 'ALM_Notices' ) ) {
				$this->notices = new ALM_Notices();
			}

			$params = filter_input_array( INPUT_GET, FILTER_FLAG_EMPTY_STRING_NULL );
			if ( isset( $params['action'] ) && $params['action'] === 'delete-cache' ) {
				$message = __( 'Ajax Load More Cache has been deleted successfully.', 'ajax-load-more-cache' );
				$this->notices->add_admin_notice( $message, 'success' );
				do_action( 'alm_clear_cache' );
				return;
			}
		}

		/**
		 * V3 Upgrade routine to delete the legacy/existing ALM Cache.
		 *
		 * @return void
		 */
		public function alm_cache_3_1_upgrader() {
			delete_option( 'alm_cache_v2_upgrade' ); // Clean up old v2 upgrade option.
			delete_option( 'alm_cache_v3_upgrade' ); // Clean up old v3 upgrade option.
		}
		/**
		 * Create the cache directory by id and store data about cache in .txt file
		 *
		 * @return string The cache directory path.
		 */
		public static function alm_cache_create_directory() {
			$path = self::alm_get_cache_path(); // Test directory before creating files.
			if ( ! is_dir( $path ) ) {
				wp_mkdir_p( $path ); // Create directory if it doesn't exist.
			}
			return $path;
		}

		/**
		 * Get array of cache items to prebuild.
		 *
		 * @return array
		 */
		public static function alm_get_cache_array() {
			$array = apply_filters( 'alm_cache_array', [] );
			if ( ! $array ) {
				return [];
			}

			$filtered = [];
			foreach ( $array as $item ) {
				if ( ! isset( $item['id'] ) || ! isset( $item['url'] ) ) {
					continue;
				}
				$filtered[] = $item;
			}

			return is_array( $filtered ) ? $filtered : null;
		}

		/**
		 * Get absolute path to cache directory path
		 *
		 * @return string
		 */
		public static function alm_get_cache_path() {
			$upload_dir = wp_upload_dir();
			$path       = apply_filters( 'alm_cache_path', $upload_dir['basedir'] . '/alm-cache/' );
			if ( substr( $path, -1 ) !== '/' ) {
				$path .= '/'; // Ensure trailing slash.
			}
			return $path;
		}

		/**
		 * Get the rest api URL for a cache item.
		 *
		 * @param string $id The cache ID.
		 * @param string $name The cache name.
		 * @return string
		 */
		public static function alm_get_cache_rest_url( $id, $name ) {
			if ( ! $id || ! $name ) {
				return;
			}
			$base = esc_url_raw( rest_url() ) . 'ajax-load-more/cache/get?';
			return $base . 'id=' . $id . '&name=' . $name;
		}

		/**
		 * Get cached file from the filesystem.
		 *
		 * @param string $cache_id The cache ID.
		 * @return mixed
		 */
		public static function get_cache( $cache_id = '' ) {
			if ( ! $cache_id ) {
				return; // Exit if no cache ID.
			}

			$file = self::alm_get_cache_path() . $cache_id . '.json';
			if ( ! file_exists( $file ) ) {
				return false; // Exit if file not found.
			}

			// Load WP Filesystem.
			global $wp_filesystem;
			include_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();

			// Get cache file contents.
			$json = $wp_filesystem->get_contents( $file );
			if ( ! $json ) {
				return false; // Missing content.
			}

			header( 'X-ALM-Cache: ' . $cache_id ); // Set cache hit header.
			return $json;
		}

		/**
		 * Create cache file in the filesystem.
		 *
		 * @param string $cache_id The cache ID.
		 * @param array  $data     The data.
		 * @return void
		 */
		public static function create_cache( $cache_id, $data ) {
			if ( ! $cache_id || ! $data ) {
				return; // Exit if no cache ID or data.
			}

			// Create the cache directory.
			$path = self::alm_cache_create_directory();
			if ( ! $path ) {
				return;
			}

			// Load WP Filesystem.
			global $wp_filesystem;
			include_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();

			$file = $wp_filesystem->put_contents( $path . $cache_id . '.json', wp_json_encode( $data ), FS_CHMOD_FILE );

			if ( ! $file ) {
				wp_die( esc_attr__( 'Unable to create cache file. Please contact your hosting administrator.', 'ajax-load-more-cache' ) );
			}

			// Dispatch action after cache created.
			do_action( 'alm_cache_created', $cache_id, $data );
		}

		/**
		 * Call this function when posts are published to determine if we should flush the cache
		 *
		 * @return void
		 */
		public function alm_cache_post_published() {
			$options = get_option( 'alm_settings' ); // Get plugin options.
			if ( isset( $options['_alm_cache_publish'] ) && $options['_alm_cache_publish'] === '1' ) {
				self::alm_cache_delete();
			}
		}

		/**
		 * An empty function to determine if cache is activated.
		 *
		 * @return void
		 */
		public function alm_cache_installed() {
			// Empty return.
		}

		/**
		 * Get count of ALM cache items.
		 *
		 * @return int
		 */
		public function alm_cache_get_count() {
			$path = self::alm_get_cache_path();
			if ( substr( $path, -1 ) !== '/' ) {
				$path .= '/'; // Ensure trailing slash.
			}

			$files = glob( $path . '*.json' );
			if ( ! $files ) {
				return 0;
			}
			return count( $files );
		}

		/**
		 * Delete entire ALM cache or specific file by ID.
		 *
		 * @param string $id Optional ID/hash to delete.
		 * @return void
		 */
		public static function alm_cache_delete( $id = '' ) {
			$path = self::alm_get_cache_path();
			if ( ! is_dir( $path ) || ! self::has_permissions() ) {
				return;
			}

			if ( $id ) {
				// Delete specific cache file.
				$file = $path . $id . '.json';
				if ( file_exists( $file ) ) {
					unlink( $file ); // phpcs:ignore
				}
			} else {
				// Delete all cache files.
				$files = glob( $path . '*.json' );
				if ( ! $files ) {
					return 0;
				}
				foreach ( $files as $file ) {
					unlink( $file ); // phpcs:ignore
				}
			}

			// Clean up routine for legacy cache.
			$legacy = get_option( 'alm_cache_v3_1_upgrade' );
			if ( ! $legacy ) {
				foreach ( new DirectoryIterator( $path ) as $directory ) {
					// Delete nested directories.
					if ( $directory->isDot() ) {
						continue;
					}
					if ( $directory->isDir() ) {
						$dir = $path . $directory;
						self::alm_cache_rmdir( $dir );
					}
				}
				update_option( 'alm_cache_v3_1_upgrade', true );
			}

			// Dispatch action after cache deleted.
			do_action( 'alm_cache_deleted', $id );
		}

		/**
		 *  Recurrsively delete a cache directory and files.
		 *
		 *  @param string $dir The directory.
		 *  @return void
		 */
		public static function alm_cache_rmdir( $dir ) {
			if ( ! self::has_permissions() ) {
				return;
			}

			$path = self::alm_get_cache_path();

			// Confirm is directory & directory is found in the `alm_get_cache_path`.
			if ( ! is_dir( $dir ) || strpos( $dir, $path ) === false ) {
				return;
			}

			// Recurrsively remove nested directories.
			if ( is_dir( $dir ) ) {
				$objects = scandir( $dir );
				foreach ( $objects as $object ) {
					if ( $object !== '.' && $object !== '..' ) {
						if ( filetype( $dir . '/' . $object ) === 'dir' ) {
							self::alm_cache_rmdir( $dir . '/' . $object );
						} else {
							unlink( $dir . '/' . $object );  // phpcs:ignore
						}
					}
				}
				reset( $objects );
				rmdir( $dir ); // phpcs:ignore
			}
		}

		/**
		 * Enqueue Cache admin js and css.
		 *
		 * @return void
		 */
		public static function alm_enqueue_cache_admin_scripts() {
			wp_enqueue_style( 'alm-cache-css', ALM_CACHE_ADMIN_URL . '/build/index.css', '', ALM_CACHE_VERSION );
			wp_enqueue_script( 'alm-cache-admin', ALM_CACHE_ADMIN_URL . '/build/index.js', [ 'jquery' ], ALM_CACHE_VERSION, false );

			// Localized JS variables.
			wp_localize_script(
				'alm-cache-admin',
				'alm_cache_localize',
				[
					'root'              => esc_url_raw( rest_url() ),
					'nonce'             => wp_create_nonce( 'wp_rest' ),
					'ajax_admin_url'    => admin_url( 'admin-ajax.php' ),
					'alm_cache_nonce'   => wp_create_nonce( 'alm_cache_nonce' ),
					'are_you_sure'      => __( 'Are you sure you want to delete the following Ajax Load More Cache and all of it\'s contents?', 'ajax-load-more-cache' ),
					'are_you_sure_full' => __( 'Are you sure you want to delete the entire Ajax Load More Cache?', 'ajax-load-more-cache' ),
				]
			);
		}

		/**
		 * Build Cache shortcode params and send back to core ALM.
		 *
		 * @param array $options  Plugin settings/options.
		 * @return string
		 */
		public function alm_cache_shortcode( $options = [] ) {
			/**
			 * Check for ALM version 7.7 or greater.
			 *
			 * @since 3.0
			 */
			$alm_core_version_check = defined( 'ALM_VERSION' ) && version_compare( ALM_VERSION, '7.7', '>=' );
			if ( ! $alm_core_version_check ) {
				error_log( __( 'Ajax Load More Cache requires Ajax Load More version 7.7 or greater. Please update Ajax Load More to the latest version to use Ajax Load More Cache.', 'ajax-load-more-cache' ) );
				return;
			}

			$data = ' data-cache="true"';

			// Cache auto-generate. Turn off cache for known users when auto-generating cache.
			$auto_generate = isset( $_GET['alm_auto_cache'] );

			if ( is_user_logged_in() && isset( $options['_alm_cache_known_users'] ) && $options['_alm_cache_known_users'] === '1' && ! $auto_generate ) {
				$data .= ' data-cache-logged-in="true"'; // Check for known users.
			}
			return $data;
		}

		/**
		 * Create the Cache settings panel.
		 *
		 * @return void
		 */
		public function alm_cache_settings() {
			register_setting(
				'alm_cache_license',
				'alm_cache_license_key',
				[ $this, 'alm_cache_sanitize_license' ]
			);
			add_settings_section(
				'alm_cache_settings',
				__( 'Cache Settings', 'ajax-load-more-cache' ),
				[ $this, 'alm_cache_settings_callback' ],
				'ajax-load-more'
			);
			add_settings_field(
				'_alm_cache_delete_cache_button',
				__( 'Delete Cache', 'ajax-load-more-cache' ),
				[ $this, 'alm_cache_delete_cache_callback' ],
				'ajax-load-more',
				'alm_cache_settings'
			);
			$generate_cache = ALMCACHE::alm_get_cache_array();
			if ( ! empty( $generate_cache ) ) {
				add_settings_field(
					'_alm_cache_generate_cache_button',
					__( 'Generate Cache', 'ajax-load-more-cache' ),
					[ $this, 'alm_cache_generate_cache_callback' ],
					'ajax-load-more',
					'alm_cache_settings'
				);
			}
			add_settings_field(
				'_alm_cache_publish',
				__( 'Published Posts', 'ajax-load-more-cache' ),
				[ $this, 'alm_cache_publish_callback' ],
				'ajax-load-more',
				'alm_cache_settings'
			);
			add_settings_field(
				'_alm_cache_known_users',
				__( 'Known Users', 'ajax-load-more-cache' ),
				[ $this, 'alm_cache_known_users_callback' ],
				'ajax-load-more',
				'alm_cache_settings'
			);
		}

		/**
		 * Create the publish actions for when new posts are added
		 *
		 * @return void
		 */
		public function alm_cache_create_publish_actions() {
			if ( self::has_permissions() ) {
				$pt_args = [ 'public' => true ];
				$types   = get_post_types( $pt_args );
				if ( $types ) {
					foreach ( $types as $type ) {
						$typeobj = get_post_type_object( $type );
						$name    = $typeobj->name;
						if ( $name !== 'revision' && $name !== 'attachment' && $name !== 'nav_menu_item' && $name !== 'acf' ) {
							add_action( 'publish_' . $name . '', [ $this, 'alm_cache_post_published' ] );
						}
					}
				}
				add_action( 'future_to_publish', [ $this, 'alm_cache_post_published' ] );
			}
		}

		/**
		 * Create admin bar menu.
		 *
		 * @param object $admin_bar The admin bar object.
		 * @since 1.0
		 */
		public function alm_add_toolbar_items( $admin_bar ) {
			if ( ! is_admin() || ! self::has_permissions() || ! is_admin_bar_showing() ) {
				return;
			}

			$admin_bar->add_menu(
				[
					'id'    => 'alm-cache',
					'title' => '<div class="alm-ab-icon"></div>Cache',
					'href'  => admin_url( 'admin.php?page=ajax-load-more#cache_settings' ),
					'meta'  => [
						'title' => __( 'Ajax Load More Cache', 'ajax-load-more-cache' ),
						'class' => 'alm-adminbar-icon',
					],
				]
			);

			$admin_bar->add_menu(
				[
					'id'     => 'alm-cache-delete',
					'parent' => 'alm-cache',
					'title'  => __( 'Delete Cache', 'ajax-load-more-cache' ) . ' (<span id="alm-admin-bar-cache-total">' . $this->alm_cache_get_count() . '</span>)',
					'href'   => add_query_arg( [ 'action' => 'delete-cache' ], admin_url( 'admin.php?page=ajax-load-more' ) ),
					'meta'   => [
						'title'  => __( 'Delete Cache', 'ajax-load-more-cache' ),
						'target' => '_self',
					],
				]
			);

			$generate_cache = ALMCACHE::alm_get_cache_array();
			if ( ! empty( $generate_cache ) ) {
				$link = add_query_arg(
					[
						'action' => 'cache_build#cache_settings',
					],
					admin_url( 'admin.php?page=ajax-load-more' )
				);
				$admin_bar->add_menu(
					[
						'id'     => 'alm-cache-build',
						'parent' => 'alm-cache',
						'title'  => __( 'Generate Cache', 'ajax-load-more-cache' ),
						'href'   => $link,
						'meta'   => [
							'title'  => __( 'Generate Cache', 'ajax-load-more-cache' ),
							'target' => '_self',
						],
					]
				);
			}
		}

		/**
		 * Admin bar styles.
		 *
		 * @return void
		 */
		public function alm_cache_adminbar_styles() {
			if ( ! is_admin_bar_showing() ) {
				return;
			}
			?>
			<style>
			#wpadminbar .alm-ab-icon {
				background-image: url("data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iNDhweCIKCSBoZWlnaHQ9IjQ4cHgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgNDggNDgiIHhtbDpzcGFjZT0icHJlc2VydmUiPgoJPGc+CgkJPHBhdGggZmlsbD0iIzk5YTBhNSIgZD0iTTQ0LDQ4SDRjLTIuMiwwLTQtMS44LTQtNFY0YzAtMi4yLDEuOC00LDQtNGg0MGMyLjIsMCw0LDEuOCw0LDR2NDBDNDgsNDYuMiw0Ni4yLDQ4LDQ0LDQ4eiIvPgoJPC9nPgoJPGc+CgkJPGc+CgkJCTxwYXRoIGZpbGw9IiMxZDIzMjciIGQ9Ik0xOC45LDI3LjdMMTgsMzAuNWMtMC4xLDAuMy0wLjIsMC42LTAuMywwLjljLTAuMSwwLjMtMC4xLDAuNy0wLjEsMC45YzAsMC4yLDAsMC40LDAuMSwwLjUKCQkJCWMwLjEsMC4yLDAuMiwwLjMsMC4zLDAuNGMwLjEsMC4xLDAuMywwLjIsMC41LDAuMmMwLjIsMC4xLDAuNCwwLjEsMC41LDAuMWgxLjN2MS43aC04LjN2LTEuN2gwLjRjMC4zLDAsMC41LDAsMC44LTAuMQoJCQkJczAuNS0wLjIsMC43LTAuNHMwLjQtMC40LDAuNi0wLjdjMC4yLTAuMywwLjQtMC43LDAuNi0xLjJMMjIsMTIuNGg0LjRMMzMsMzEuMmMwLjIsMC40LDAuMywwLjgsMC41LDEuMXMwLjQsMC41LDAuNiwwLjcKCQkJCXMwLjQsMC4zLDAuNiwwLjRjMC4yLDAuMSwwLjUsMC4xLDAuNywwLjFIMzZ2MS43SDI1LjV2LTEuN2gxLjJjMC4yLDAsMC4zLDAsMC41LTAuMWMwLjIsMCwwLjMtMC4xLDAuNC0wLjIKCQkJCWMwLjEtMC4xLDAuMi0wLjIsMC4zLTAuNGMwLjEtMC4yLDAuMS0wLjMsMC4xLTAuNmMwLTAuMywwLTAuNS0wLjEtMC43Yy0wLjEtMC4yLTAuMS0wLjQtMC4yLTAuNmwtMS4xLTMuMkgxOC45eiBNMjQuNCwyMC41CgkJCQljLTAuMS0wLjQtMC4yLTAuNy0wLjQtMS4xYy0wLjEtMC40LTAuMi0wLjgtMC40LTEuMmMtMC4xLTAuNC0wLjItMC44LTAuMy0xLjJjLTAuMS0wLjQtMC4yLTAuOC0wLjMtMS4yYy0wLjEsMC4zLTAuMiwwLjctMC4zLDEuMQoJCQkJYy0wLjEsMC40LTAuMywwLjgtMC40LDEuMnMtMC4zLDAuOC0wLjQsMS4ycy0wLjMsMC44LTAuNCwxLjJsLTEuOSw1LjRoNi41TDI0LjQsMjAuNXoiLz4KCQk8L2c+Cgk8L2c+Cjwvc3ZnPgo=");
				background-position: center;
				background-repeat: no-repeat;
				background-size: 20px 20px;
				float: left;
				height: 30px;
				width: 26px;
				margin-right: 4px;
				margin-top: 1px;
			}
			</style>
			<?php
		}


		/**
		 * Cache Setting Heading.
		 *
		 * @return void
		 */
		public function alm_cache_settings_callback() {
			// Translators: 1: opening anchor tag, 2: closing anchor tag.
			$html = '<p>' . sprintf( __( 'Customize your installation of the %1$sCache%2$s add-on.', 'ajax-load-more-cache' ), '<a href="http://connekthq.com/plugins/ajax-load-more/cache/">', '</a>' ) . '</p>';
			echo $html; //phpcs:ignore
		}

		/**
		 * Clear cache when a new post is published.
		 *
		 * @since 2.6.0
		 */
		public function alm_cache_publish_callback() {
			$options = get_option( 'alm_settings' );
			if ( ! isset( $options['_alm_cache_publish'] ) ) {
				$options['_alm_cache_publish'] = '0';
			}
			$html  = '<input type="hidden" name="alm_settings[_alm_cache_publish]" value="0" /><input type="checkbox" id="alm_cache_publish" name="alm_settings[_alm_cache_publish]" value="1"' . ( ( $options['_alm_cache_publish'] ) ? ' checked="checked"' : '' ) . ' />';
			$html .= '<label for="alm_cache_publish">' . esc_html__( 'Delete Cache When Posts are Published.', 'ajax-load-more-cache' );
			$html .= '<span style="display:block">' . esc_html__( 'The Ajax Load More Cache will be fully cleared when a post, page or custom post type is published or modified.', 'ajax-load-more-cache' ) . '</span>';
			$html .= ' </label>';
			echo $html; //phpcs:ignore
		}

		/**
		 * Don't cache files for known users.
		 *
		 * @return void
		 */
		public function alm_cache_known_users_callback() {
			$options = get_option( 'alm_settings' );
			if ( ! isset( $options['_alm_cache_known_users'] ) ) {
				$options['_alm_cache_known_users'] = '0';
			}
			$html  = '<input type="hidden" name="alm_settings[_alm_cache_known_users]" value="0" /><input type="checkbox" id="alm_cache_known_users" name="alm_settings[_alm_cache_known_users]" value="1"' . ( ( $options['_alm_cache_known_users'] ) ? ' checked="checked"' : '' ) . ' />';
			$html .= '<label for="alm_cache_known_users">' . esc_html__( 'Disable Cache for Logged In Users', 'ajax-load-more-cache' );
			$html .= '<span style="display:block">' . esc_html__( 'Logged in users will retrieve content from the database and will not view any cached Ajax content.', 'ajax-load-more-cache' ) . '</span>';
			$html .= ' </label>';
			echo $html; //phpcs:ignore
		}

		/**
		 * Delete Cache button.
		 *
		 * @return void
		 */
		public function alm_cache_delete_cache_callback() {
			$count  = $this->alm_cache_get_count();
			$action = filter_input( INPUT_GET, 'action' );

			if ( $count === 0 && ( ! isset( $action ) || $action !== 'cache_build' ) ) {
				echo '<p>' . esc_html__( 'The Ajax Load More Cache is empty.', 'ajax-load-more-cache' ) . '</p>';
				return;
			}

			// Building cache, clear existing cache first.
			$url_params = filter_input_array( INPUT_GET ); // Get URL params.
			if ( isset( $url_params['action'] ) && $url_params['action'] === 'cache_build' ) {
				do_action( 'alm_clear_cache' );
				$count = 0; // Reset count after clearing cache.
			}

			$link = add_query_arg(
				[
					'action' => 'delete-cache#cache_settings',
				],
				admin_url( 'admin.php?page=ajax-load-more' )
			);

			// Translators: 1: number of cached results, 2: results/result.
			$html  = '<p style="margin-bottom:10px;">' . sprintf( __( 'The Ajax Load More Cache contains %1$s cached %2$s.', 'ajax-load-more-cache' ), '<strong id="alm-cache-total">' . $count . '</strong>', $count !== 1 ? __( 'results', 'ajax-load-more-cache' ) : __( 'result', 'ajax-load-more-cache' ) ) . '</p>';
			$html .= '<p><a href="' . esc_url( $link ) . '" class="button button-secondary">';
			$html .= esc_html__( 'Delete Cache', 'ajax-load-more-cache' );
			$html .= '</a>';
			echo $html; //phpcs:ignore
		}

		/**
		 * Generate Cache button.
		 *
		 * @return void
		 */
		public function alm_cache_generate_cache_callback() {
			$link       = add_query_arg(
				[
					'action' => 'cache_build#cache_settings',
				],
				admin_url( 'admin.php?page=ajax-load-more' )
			);
			$url_params = filter_input_array( INPUT_GET ); // Get URL params.
			if ( isset( $url_params['action'] ) && $url_params['action'] === 'cache_build' ) {
				require_once 'admin/includes/auto-generate.php';
			} else {
				$html  = '<p style="margin-bottom:10px;">' . esc_html__( 'You have enabled auto-generation of the Ajax Load More Cache. Click the Build Cache below to start the process.', 'ajax-load-more-cache' ) . '</p>';
				$html .= '<p>';
				$html .= '<a href="' . esc_url( $link ) . '" class="button button-primary" style="margin-right: 5px;">' . esc_html__( 'Build Cache', 'ajax-load-more-cache' ) . '</a>';
				$html .= '<a href="https://connekthq.com/plugins/ajax-load-more/docs/add-ons/cache/#auto-generate" class="button button-secondary" target="_blank">' . esc_html__( 'Documentation', 'ajax-load-more-cache' ) . '</a>';
				$html .= '</p>';
				echo $html; //phpcs:ignore
			}
		}

			/**
			 * Sanitize our license activation.
			 *
			 * @param string $key The API Key.
			 * @return string The API key as a string.
			 */
		public function alm_cache_sanitize_license( $key ) {
			$old = get_option( 'alm_cache_license_key' );
			if ( $old && $old !== $key ) {
				delete_option( 'alm_cache_license_status' );
			}
			return $key;
		}
	}

	/**
	 * The main function responsible for returning Ajax Load More Cache.
	 *
	 * @return void
	 */
	function alm_cache_init() {
		global $alm_cache;
		if ( ! isset( $alm_cache ) ) {
			$alm_cache = new ALMCache();
		}
		return $alm_cache;
	}
		alm_cache_init();
endif;

/**
 * Software Licensing
 *
 * @return void
 */
function alm_cache_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) { // Don't check for updates if Pro is activated.
		$license_key = trim( get_option( 'alm_cache_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			[
				'version' => ALM_CACHE_VERSION,
				'license' => $license_key,
				'item_id' => ALM_CACHE_ITEM_NAME,
				'author'  => 'Darren Cooney',
			]
		);
	}
}
add_action( 'admin_init', 'alm_cache_plugin_updater', 0 );
