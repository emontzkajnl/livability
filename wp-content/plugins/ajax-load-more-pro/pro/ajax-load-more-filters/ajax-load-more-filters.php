<?php
/**
 * Plugin Name: Ajax Load More: Filters
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/filters/
 * Description: Ajax Load More add-on to build and manage Ajaxed filters.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: https://connekthq.com
 * Version: 3.0.2
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALMFilters
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALM_FILTERS_VERSION', '3.0.2' );
define( 'ALM_FILTERS_RELEASE', 'June 10, 2025' );
define( 'ALM_FILTERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_FILTERS_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_FILTERS_ADMIN_URL', plugins_url( 'admin/', __FILE__ ) );
define( 'ALM_FILTERS_SLUG', 'ajax-load-more-filters' );
define( 'ALM_FILTERS_BASE_URL', get_admin_url() . 'admin.php?page=' . ALM_FILTERS_SLUG );
define( 'ALM_FILTERS_PREFIX', 'alm_filter_' );
define( 'ALM_FILTERS_FACET_PREFIX', 'alm_facet_filter_' );
define( 'ALM_FACETS_CACHE_PREFIX', 'alm_facet_cache_' );

if ( ! class_exists( 'ALMFilters' ) ) :

	/**
	 * Initiate the class.
	 */
	class ALMFilters {

		/**
		 * ALM Admin Notices.
		 *
		 * @var array
		 */
		public $notices = [];

		/**
		 * Count filters.
		 *
		 * @var int
		 */
		public static $counter = 0;

		/**
		 * An array of filter operators used with each filter.
		 * Store tax operator, meta operator and type in an array as they are not passed in the querystring.
		 *
		 * @var array
		 */
		public static $alm_filters_key_operators = [];

		/**
		 * Facets.
		 *
		 * @var array|bool
		 */
		public static $facets = false;

		/**
		 * Facets Count.
		 *
		 * @var int|bool
		 */
		public static $facets_count = false;

		/**
		 * Facets Post Types.
		 *
		 * @var array
		 */
		public static $facets_post_types = [];

		/**
		 * Construct class.
		 */
		public function __construct() {
			add_action( 'alm_filters_installed', [ $this, 'alm_filters_installed' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'alm_filters_enqueue_scripts' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'alm_filters_admin_enqueue_scripts' ] );
			add_action( 'ajax_load_more_filters_', [ $this, 'ajax_load_more_filters_' ] );
			add_action( 'admin_init', [ $this, 'alm_filters_duplicate_filter' ] );
			add_action( 'admin_init', [ $this, 'alm_filters_deleted' ] );
			add_action( 'admin_init', [ $this, 'alm_filters_updated' ] );
			add_action( 'init', [ $this, 'alm_filters_facet_publish_actions' ] );
			add_action( 'init', [ $this, 'alm_filters_textdomain' ] );

			add_filter( 'alm_filters_shortcode_params', [ $this, 'alm_filters_shortcode_params' ], 10, 8 );
			add_filter( 'redirect_canonical', [ $this, 'alm_filters_frontpage_canonical_redirect' ] );

			add_shortcode( 'ajax_load_more_filters', [ $this, 'alm_filters_shortcode' ] );

			$this->includes();
		}

		/**
		 * Loads plugin translations.
		 *
		 * @since  2.4
		 * @return void
		 */
		public function alm_filters_textdomain() {
			load_plugin_textdomain( 'ajax-load-more-filters', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Include these files.
		 *
		 * @since 1.0
		 */
		public function includes() {
			// Admin only.
			if ( is_admin() ) {
				if ( ! class_exists( 'ALM_Filters_Notices' ) ) {
					require_once 'functions/classes/class-notices.php';
					$this->notices = new ALM_Filters_Notices();
				}

				require_once 'functions/classes/class-tools.php';
				require_once 'functions/classes/class-settings.php';

				if ( ! function_exists( 'alm_list_all_filters' ) ) {
					require_once 'admin/functions.php';
				}
			}

			require_once 'lib/wp-background-processing/wp-background-processing.php';
			require_once 'functions/facets/class-cache.php';
			require_once 'functions/facets/class-sidebar.php';
			require_once 'functions/facets/class-process.php';
			require_once 'functions/facets/class-facets.php';
			require_once 'functions/helpers.php';
			require_once 'functions/dynamic-filter-vars.php';
			require_once 'functions/checkbox-limit.php';
			require_once 'functions/display-custom-values.php';
			require_once 'functions/display-terms.php';
			require_once 'functions/display-textfield.php';
			require_once 'functions/facets.php';
			require_once 'functions/preview.php';

			// API Endpoints.
			require_once 'admin/api/save.php';
			require_once 'admin/api/renderfilter.php';
			require_once 'admin/api/facets/status.php';
		}

		/**
		 * Create the publish actions for indexing facets.
		 *
		 * @return void
		 */
		public function alm_filters_facet_publish_actions() {
			if ( is_user_logged_in() && current_user_can( apply_filters( 'alm_user_role', 'edit_theme_options' ) ) && apply_filters( 'alm_filters_facet_publish_actions', true ) ) {
				$options = get_option( 'alm_settings' );

				// Prevent reindexing if ALM setting is false.
				$update_on_save = isset( $options['_alm_filters_facet_index'] ) ? $options['_alm_filters_facet_index'] : '1';
				if ( $update_on_save === '0' ) {
					return;
				}

				$facets     = ALM_Facets::get_all_facets(); // Get all facets.
				$post_types = alm_filters_facet_get_post_types( $facets ); // Get all post types.

				if ( $post_types ) {
					self::$facets_post_types = $post_types;
					$types                   = wp_list_pluck( $post_types, 'post_type' );
					if ( $types ) {
						$types = array_unique( $types ); // Remove duplicates.
						foreach ( $types as $type ) {
							add_action( 'save_post_' . $type . '', [ $this, 'alm_filters_update_facets_on_save' ] );
						}
					}
				}
			}
		}

		/**
		 * Save post hook for updating facets.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/save_post/
		 * @param string $id      The Post ID.
		 * @return void
		 */
		public function alm_filters_update_facets_on_save( $id ) {
			$post_type   = get_post_type( $id );
			$post_status = get_post_status( $id );
			$facets      = self::$facets_post_types; // Get all facets from global variables.

			if ( $post_status === 'auto-draft' || ! $facets ) {
				return;
			}

			// Pull out facets that match the current post type.
			$filtered = array_filter(
				$facets,
				function ( $facet ) use ( $post_type ) {
					return $facet['post_type'] === $post_type;
				}
			);

			if ( ! $filtered ) {
				return;
			}

			foreach ( $filtered as $filter ) {
				if ( $filter['id'] ) {
					$filter = self::alm_filters_get_filter_by_id( $filter['id'], true );
					if ( $filter ) {
						ALM_Facets::update_index( $filter, $id, get_post_status( $id ) );
					}
				}
			}
		}

		/**
		 * Duplicate an existing filter.
		 *
		 * @see wp-admin/admin.php?page=ajax-load-more-filters&duplicate_filter=my_facet
		 */
		public function alm_filters_duplicate_filter() {
			$params = filter_input_array( INPUT_GET ); // phpcs:ignore
			if ( isset( $params['duplicate_filter'] ) && isset( $params['filter_id'] ) ) {
				$id         = $params['duplicate_filter'];
				$new_id     = $params['filter_id'];
				$filter     = self::alm_filters_get_filter_by_id( $id, true );
				$new_filter = self::alm_filters_get_filter_by_id( $new_id, true );

				// New filter name exists. exit before overwrite.
				if ( $new_filter ) {
					/* translators: %s: Filter ID & admin link */
					$msg = sprintf( __( 'Filter <a href="%1$s">%2$s</a> already exists - you need to use a unique ID for each filter.', 'ajax-load-more-filters' ), ALM_FILTERS_BASE_URL . '&filter=' . $new_id, $new_id );
					$this->notices->alm_add_admin_notice( $msg, 'error' );
					return;
				}

				// Confirm filter exist to duplicate.
				if ( $filter ) {
					$filter['id']            = $new_id;
					$time                    = time();
					$filter['date_created']  = $time;
					$filter['date_modified'] = $time;

					update_option( ALM_FILTERS_PREFIX . $filter['id'], serialize( $filter ), false );

					$has_facets = isset( $filter['facets'] ) && $filter['facets']; // Locate matching facet.
					if ( $has_facets ) {
						// Build the facet index.
						do_action( 'alm_facets_build_index', $filter );
					}

					/* translators: %s: Filter ID */
					$msg = sprintf( __( 'Filter <a href="%1$s">%2$s</a> created sucessfully.', 'ajax-load-more-filters' ), ALM_FILTERS_BASE_URL . '&filter=' . $new_id, $new_id );
					$this->notices->alm_add_admin_notice( $msg, 'success' );

				} else {
					/* translators: %s: Filter ID */
					$msg = sprintf( __( 'Filter <strong>%s</strong> does not exist and could not be duplicated.', 'ajax-load-more-filters' ), $id );
					$this->notices->alm_add_admin_notice( $msg, 'error' );

				}
			}
		}

		/**
		 * Delete a filter and facets from the options table.
		 *
		 * @see wp-admin/admin.php?page=ajax-load-more-filters&delete_filter=my_filter
		 */
		public function alm_filters_deleted() {
			$params = filter_input_array( INPUT_GET ); // phpcs:ignore
			if ( isset( $params['delete_filter'] ) ) {
				$filter_id = $params['delete_filter'];

				// Confirm options exist.
				$filter = self::alm_filters_get_filter_by_id( $filter_id, true );

				if ( $filter ) {
					// Delete related Facet option and transients.
					$has_facets = isset( $filter['facets'] ) && $filter['facets']; // Locate matching facet.
					if ( $has_facets ) {
						// Delete facet related WP options and transients.
						ALM_Facets::delete_facet_options( $filter_id );
						ALM_Facets::delete_cache( $filter_id );
					}

					// Delete filter.
					delete_option( ALM_FILTERS_PREFIX . $filter_id );

					if ( $has_facets ) {
						$msg = '<strong>' . $filter_id . '</strong> ' . __( 'filter and facet index was deleted successfully.', 'ajax-load-more-filters' );
					} else {
						$msg = '<strong>' . $filter_id . '</strong> ' . __( 'filter was deleted successfully.', 'ajax-load-more-filters' );
					}
					$this->notices->alm_add_admin_notice( $msg, 'success' );
				} else {
					$msg = '<strong>' . $filter_id . '</strong> ' . __( 'filter not found.', 'ajax-load-more-filters' );
					$this->notices->alm_add_admin_notice( $msg, 'error' );
				}
			}
		}

		/**
		 * Filter has been updated or a facet reindex triggered.
		 *
		 * @since 1.6
		 */
		public function alm_filters_updated() {
			$params = filter_input_array( INPUT_GET ); // phpcs:ignore
			if ( isset( $params['filter'] ) && ( isset( $params['filter_updated'] ) || isset( $params['rebuild_index'] ) || isset( $params['auto_rebuild_index'] ) ) ) {
				$filter_id          = trim( $params['filter'] );
				$filter             = self::alm_filters_get_filter_by_id( $filter_id, true );
				$rebuild_index      = isset( $params['rebuild_index'] );
				$auto_rebuild_index = isset( $params['auto_rebuild_index'] );

				if ( ! $filter ) {
					$this->notices->alm_add_admin_notice( __( 'Filter does not exist.', 'ajax-load-more-filters' ), 'error' );
					return;
				}

				if ( ! isset( $filter['filters'] ) ) {
					$this->notices->alm_add_admin_notice( __( 'No active filters found.', 'ajax-load-more-filters' ), 'error' );
					return;
				}

				// Update Facet index and transients.
				$has_facets = isset( $filter['facets'] ) && $filter['facets']; // Locate matching facet.
				if ( $has_facets ) {
					// Confirm post types are selected.
					$has_facets_post_types = isset( $filter['facets_post_types'] ) && is_array( $filter['facets_post_types'] );
					if ( ! $has_facets_post_types ) {
						$this->notices->alm_add_admin_notice( __( 'Error: You must select at least 1 post type when using facets.', 'ajax-load-more-filters' ), 'error' );
						return;
					}

					$facet_index = $this::get_facet_index_by_id( $filter_id );

					// Build facet index if it doesn't exist or a reindex was triggered.
					if ( ! $facet_index || $rebuild_index || $auto_rebuild_index ) {
						do_action( 'alm_facets_build_index', $filter );
					}

					if ( $rebuild_index ) {
						// Rebuild facet index via `rebuild_index` querystring.
						$this->notices->alm_add_admin_notice( __( 'Facet reindexing triggered successfully.', 'ajax-load-more-filters' ), 'success', 'indexer-rebuild' );
					} elseif ( $auto_rebuild_index ) {
						// Rebuild facet index via `auto_rebuild_index` querystring.
						$this->notices->alm_add_admin_notice( __( 'Filter saved and facet reindexing has been triggered automatically.', 'ajax-load-more-filters' ), 'success', 'indexer-rebuild' );
					} else {
						$url = ALM_FILTERS_BASE_URL . '&filter=' . $filter_id . '&rebuild_index';

						/* translators: %1$s is the rebuild URL */
						$msg = sprintf( wp_kses_post( __( 'Filter saved successfully. <a href="%1$s" id="alm-rebuild-facet-index-notice">Rebuild Index</a>', 'ajax-load-more-filters' ) ), $url );
						$this->notices->alm_add_admin_notice( $msg, 'success' );
					}
				} else {
					$this->notices->alm_add_admin_notice( __( 'Filter saved successfully.', 'ajax-load-more-filters' ), 'success' );
				}
			}
		}

		/**
		 * Enqueue filter JS and CSS
		 *
		 * @since 1.0
		 */
		public function alm_filters_enqueue_scripts() {
			// JS and Localization.
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min'; // Use minified libraries if SCRIPT_DEBUG is turned off.
			wp_register_script( 'ajax-load-more-filters', plugins_url( '/dist/js/filters' . $suffix . '.js', __FILE__ ), 'ajax-load-more', ALM_FILTERS_VERSION, true );
			wp_localize_script(
				'ajax-load-more-filters',
				'alm_filters_localize',
				[
					'remove_active_filter' => __( 'Remove filter ', 'ajax-load-more-filters' ),
					'hide_inactive_group'  => apply_filters( 'alm_filters_hide_inactive_group', true ),
				]
			);

			// Enqueue CSS.
			if ( ! alm_do_inline_css( '_alm_inline_css' ) && ! alm_css_disabled( '_alm_filters_disable_css' ) ) {
				// Not inline or disabled.
				if ( class_exists( 'ALM_ENQUEUE' ) ) {
					ALM_ENQUEUE::alm_enqueue_css( ALM_FILTERS_SLUG, ALM_FILTERS_URL . '/dist/css/styles.css' );
				}
			}

			// Datepickr Themes.
			wp_register_style( 'alm-flatpickr-default', ALM_FILTERS_URL . '/dist/vendor/flatpickr/flatpickr.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-airbnb', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/airbnb.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-confetti', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/confetti.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-dark', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/dark.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-light', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/light.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-material_blue', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/material_blue.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-material_green', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/material_green.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-material_orange', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/material_orange.css', '', ALM_FILTERS_VERSION );
			wp_register_style( 'alm-flatpickr-material_red', ALM_FILTERS_URL . '/dist/vendor/flatpickr/themes/material_red.css', '', ALM_FILTERS_VERSION );
		}

		/**
		 * Enqueue our fitlers frontend scripts in the admin.
		 *
		 * @since 1.0
		 */
		public function alm_filters_admin_enqueue_scripts() {
			$screen = get_current_screen();
			// Only load on settings page.
			if ( $screen->base === 'toplevel_page_ajax-load-more' ) {
				wp_enqueue_style( 'alm-filters-frontend', ALM_FILTERS_URL . '/dist/css/styles.css', '', ALM_FILTERS_VERSION );
			}
		}

		/**
		 * The Ajax Load More Filter Shortcode.
		 *
		 * @param array $atts Shortcode params.
		 * @since 1.0
		 */
		public static function alm_filters_shortcode( $atts ) {
			$args     = shortcode_atts(
				[
					'id'       => '',
					'target'   => '',
					'preview'  => false,
					'redirect' => false,
				],
				$atts
			);
			$id       = sanitize_key( $args['id'] );
			$target   = esc_attr( str_replace( ' ', '', $args['target'] ) );
			$filter   = self::alm_filters_get_filter_by_id( $id, true ); // Get the option.
			$preview  = esc_attr( $args['preview'] );
			$redirect = esc_attr( $args['redirect'] );

			if ( $filter && $target ) {
				return self::init( $filter, $target, $preview, $redirect );
			}
		}

		/**
		 * Function to start the filter build process.
		 *
		 * @param array   $filters  Array of filters.
		 * @param string  $target   Target ALM ID.
		 * @param boolean $preview  Preview Mode.
		 * @param string  $redirect Redirect user to new URL after filter.
		 * @since 1.0
		 */
		public static function init( $filters = [], $target = '', $preview = false, $redirect = false ) {
			if ( empty( $filters ) ) {
				return;
			}

			$options = get_option( 'alm_settings' );
			++self::$counter;

			// Enqueue JavaScript.
			wp_enqueue_script( 'ajax-load-more-filters' );

			// Inline CSS.
			if ( class_exists( 'ALM_ENQUEUE' ) ) {
				if ( ! is_admin() && alm_do_inline_css( '_alm_inline_css' ) && ! alm_css_disabled( '_alm_filters_disable_css' ) && self::$counter === 1 ) {
					$file = ALM_FILTERS_PATH . '/dist/css/styles.css';
					echo ALM_ENQUEUE::alm_inline_css( ALM_FILTERS_SLUG, $file, ALM_FILTERS_URL ); // phpcs:ignore
				}
			}

			// Set up variables.
			$querystring       = self::alm_filters_parse_url();
			$output            = '';
			$filter_count      = 0;
			$has_datepicker    = false;
			$has_rangeslider   = false;
			$container_element = 'div';
			$redirect          = $redirect ? 'data-redirect="' . esc_attr( $redirect ) . '"' : '';

			if ( $filters['filters'] ) {
				$options_obj = [
					'target'             => isset( $target ) ? strtolower( esc_attr( $target ) ) : '',
					'id'                 => isset( $filters['id'] ) ? esc_attr( $filters['id'] ) : '',
					'style'              => isset( $filters['style'] ) ? esc_attr( $filters['style'] ) : 'change',
					'facets'             => isset( $filters['facets'] ) && $filters['facets'] === true ? true : false,
					'button_text'        => isset( $filters['button_text'] ) && ! empty( $filters['button_text'] ) ? $filters['button_text'] : apply_filters( 'alm_filters_button_text', __( 'Submit', 'ajax-load-more-filters' ) ),
					'reset_button'       => isset( $filters['reset_button'] ) ? $filters['reset_button'] : false,
					'reset_button_label' => isset( $filters['reset_button_label'] ) && ! empty( $filters['reset_button_label'] ) ? $filters['reset_button_label'] : apply_filters( 'alm_filters_reset_button_label', __( 'Reset Filters', 'ajax-load-more-filters' ) ),
				];

				self::$facets       = isset( $filters['facets'] ) && $filters['facets'] === true ? true : false;
				self::$facets_count = isset( $filters['facets_count'] ) && $filters['facets_count'] === true ? true : true;

				$color_class = isset( $options['_alm_filters_color'] ) ? ' filters-' . $options['_alm_filters_color'] : ''; // Get colors.

				// Facet classses.
				$facets_class  = self::$facets ? ' alm-filters-facets' : '';
				$facets_class .= self::$facets && isset( $filters['facets_hide_inactive'] ) && $filters['facets_hide_inactive'] === true ? ' alm-filters-facets-hide-inactive' : '';

				$aria_roles = self::$facets ? ' aria-live="polite" aria-atomic="true"' : ''; // Add aria roles to container when facets enabled.

				$output .= '<' . $container_element . $aria_roles . ' class="alm-filters alm-filters-container' . $facets_class . $color_class . '" id="alm-filters-' . $options_obj['id'] . '" data-target="' . $options_obj['target'] . '" data-style="' . $options_obj['style'] . '" data-id="' . $options_obj['id'] . '" ' . $redirect . '>';

				foreach ( $filters['filters'] as $f ) {
					++$filter_count;

					$obj = [
						'index'                      => $filter_count,
						'base_url'                   => function_exists( 'alm_get_canonical_url' ) ? alm_get_canonical_url() : '',
						'key'                        => isset( $f['key'] ) ? self::alm_filters_replace_underscore( esc_attr( $f['key'] ) ) : '',
						'field_type'                 => isset( $f['field_type'] ) ? esc_attr( $f['field_type'] ) : '',
						'taxonomy'                   => isset( $f['taxonomy'] ) ? esc_attr( $f['taxonomy'] ) : '',
						'taxonomy_operator'          => isset( $f['taxonomy_operator'] ) ? self::alm_filters_replace_underscore( esc_attr( $f['taxonomy_operator'] ) ) : 'IN',
						'taxonomy_include_children'  => isset( $f['taxonomy_include_children'] ) ? $f['taxonomy_include_children'] : 'true',
						'meta_key'                   => isset( $f['meta_key'] ) ? esc_attr( $f['meta_key'] ) : '',
						'meta_operator'              => isset( $f['meta_operator'] ) ? self::alm_filters_replace_underscore( esc_attr( $f['meta_operator'] ) ) : 'IN',
						'meta_type'                  => isset( $f['meta_type'] ) ? self::alm_filters_replace_underscore( esc_attr( $f['meta_type'] ) ) : 'CHAR',
						'exclude'                    => isset( $f['exclude'] ) ? esc_attr( $f['exclude'] ) : '',
						'author_role'                => isset( $f['author_role'] ) ? esc_attr( $f['author_role'] ) : '',
						'values'                     => isset( $f['values'] ) ? $f['values'] : '',
						'show_count'                 => isset( $f['show_count'] ) && $f['show_count'] ? true : false,
						'checkbox_limit'             => isset( $f['checkbox_limit'] ) ? $f['checkbox_limit'] : false,
						'checkbox_limit_label_open'  => isset( $f['checkbox_limit_label_open'] ) ? $f['checkbox_limit_label_open'] : apply_filters( 'alm_filters_checkbox_limit_label_open', __( 'Show More', 'ajax-load-more-filters' ) ),
						'checkbox_limit_label_close' => isset( $f['checkbox_limit_label_close'] ) ? $f['checkbox_limit_label_close'] : apply_filters( 'alm_filters_checkbox_limit_label_close', __( 'Show Less', 'ajax-load-more-filters' ) ),
						'title'                      => isset( $f['title'] ) ? esc_attr( $f['title'] ) : '',
						'description'                => isset( $f['description'] ) ? esc_attr( $f['description'] ) : '',
						'label'                      => isset( $f['label'] ) ? esc_attr( $f['label'] ) : '',
						'button_label'               => isset( $f['button_label'] ) ? $f['button_label'] : apply_filters( 'alm_filters_textfield_submit_label', __( 'Submit', 'ajax-load-more-filters' ) ),
						'placeholder'                => isset( $f['placeholder'] ) ? esc_attr( $f['placeholder'] ) : apply_filters( 'alm_filters_textfield_placeholder', '' ),
						'default_select_option'      => isset( $f['default_select_option'] ) ? esc_attr( $f['default_select_option'] ) : '',
						'classes'                    => isset( $f['classes'] ) ? ' ' . esc_attr( $f['classes'] ) : apply_filters( 'alm_filters_css_classes', '' ),
						'section_toggle'             => isset( $f['section_toggle'] ) ? $f['section_toggle'] : false,
						'section_toggle_status'      => isset( $f['section_toggle_status'] ) ? esc_attr( $f['section_toggle_status'] ) : '',
						'star_rating_min'            => isset( $f['star_rating_min'] ) ? esc_attr( $f['star_rating_min'] ) : '',
						'star_rating_max'            => isset( $f['star_rating_max'] ) ? esc_attr( $f['star_rating_max'] ) : '',
						'datepicker_mode'            => isset( $f['datepicker_mode'] ) ? esc_attr( $f['datepicker_mode'] ) : '',
						'datepicker_format'          => isset( $f['datepicker_format'] ) ? esc_attr( $f['datepicker_format'] ) : '',
						'datepicker_locale'          => isset( $f['datepicker_locale'] ) ? esc_attr( $f['datepicker_locale'] ) : '',
						'rangeslider_min'            => isset( $f['rangeslider_min'] ) ? esc_attr( $f['rangeslider_min'] ) : '',
						'rangeslider_max'            => isset( $f['rangeslider_max'] ) ? esc_attr( $f['rangeslider_max'] ) : '',
						'rangeslider_start'          => isset( $f['rangeslider_start'] ) ? esc_attr( $f['rangeslider_start'] ) : '',
						'rangeslider_end'            => isset( $f['rangeslider_end'] ) ? esc_attr( $f['rangeslider_end'] ) : '',
						'rangeslider_steps'          => isset( $f['rangeslider_steps'] ) ? esc_attr( $f['rangeslider_steps'] ) : '',
						'rangeslider_label'          => isset( $f['rangeslider_label'] ) ? esc_attr( $f['rangeslider_label'] ) : '',
						'rangeslider_orientation'    => isset( $f['rangeslider_orientation'] ) ? esc_attr( $f['rangeslider_orientation'] ) : '',
						'rangeslider_decimals'       => isset( $f['rangeslider_decimals'] ) ? esc_attr( $f['rangeslider_decimals'] ) : '',
						'rangeslider_reset'          => isset( $f['rangeslider_reset'] ) ? esc_attr( $f['rangeslider_reset'] ) : '',
						'checkbox_toggle'            => isset( $f['checkbox_toggle'] ) ? esc_attr( $f['checkbox_toggle'] ) : '',
						'checkbox_toggle_label'      => isset( $f['checkbox_toggle_label'] ) ? esc_attr( $f['checkbox_toggle_label'] ) : apply_filters( 'alm_filters_toggle_label', __( 'Select All', 'ajax-load-more-filters' ) ),
						'count'                      => $filter_count,
					];

					$field_type      = $obj['field_type'];
					$obj['field_id'] = $obj['key'] . '-' . $field_type; // Add custom `field_id` option.
					$filter_key      = alm_filters_get_filter_key( $obj ); // Get filter key value.

					/**
					 * Set Pre-selected value of element - Core Filter hook
					 *
					 * @since 1.1.1
					 */
					$obj['selected_value'] = isset( $f['selected_value'] ) ? esc_attr( $f['selected_value'] ) : '';
					$obj['selected_value'] = alm_filters_parse_dynamic_vars( $obj['key'], $obj['selected_value'] );
					if ( has_filter( 'alm_filters_' . $options_obj['id'] . '_' . $filter_key . '_selected' ) ) {
						$obj['selected_value'] = apply_filters( 'alm_filters_' . $options_obj['id'] . '_' . $filter_key . '_selected', $obj['selected_value'] );
					}
					$default_selected_value = $obj['selected_value'] ? ' data-selected-value="' . $obj['selected_value'] . '"' : ' data-selected-value=""';

					/**
					 * Set a default/fallback value of element - Core Filter hook.
					 *
					 * @since 1.1.1
					 */
					$obj['default_value'] = isset( $f['default_value'] ) && trim( $f['default_value'] !== '' ) ? esc_attr( $f['default_value'] ) : '';
					$obj['default_value'] = alm_filters_parse_dynamic_vars( $obj['key'], $obj['default_value'] );

					if ( has_filter( 'alm_filters_' . $options_obj['id'] . '_' . $filter_key . '_default' ) ) {
						$obj['default_value'] = apply_filters( 'alm_filters_' . $options_obj['id'] . '_' . $filter_key . '_default', $obj['default_value'] );
					}
					$default_value = ( $obj['default_value'] ) ? ' data-default-value="' . $obj['default_value'] . '"' : ' data-default-value=""';

					// Get Taxonomy Values.
					$taxonomy_value            = '';
					$taxonomy_operator         = '';
					$taxonomy_include_children = '';
					if ( $obj['taxonomy'] && $obj['taxonomy_operator'] ) {
						$taxonomy_value            = ' data-taxonomy="' . alm_filters_add_underscore( $options_obj['id'] ) . '' . $obj['taxonomy'] . '"';
						$taxonomy_operator         = ' data-taxonomy-operator="' . $obj['taxonomy_operator'] . '"';
						$taxonomy_include_children = ' data-taxonomy-include-children="' . $obj['taxonomy_include_children'] . '"';
					}

					// Get Meta Values.
					$meta_value    = '';
					$meta_operator = '';
					$meta_type     = '';
					if ( $obj['meta_key'] && $obj['meta_operator'] && $obj['meta_type'] ) {
						$meta_value    = ' data-meta-key="' . $obj['meta_key'] . '"';
						$meta_operator = ' data-meta-compare="' . $obj['meta_operator'] . '"';
						$meta_type     = ' data-meta-type="' . $obj['meta_type'] . '"';
					}

					// Convert Search Key for use on WP search page ?s={term}.
					if ( $obj['key'] === 'search' && is_search() ) {
						$obj['key'] = 's';
					}

					// Convert tag to _tag for front and archive pages.
					if ( $obj['key'] === 'tag' && alm_filters_is_archive() ) {
						$obj['key'] = alm_filters_add_underscore( $options_obj['id'] ) . 'tag';
					}

					// Set Author Role.
					$author_role = $obj['author_role'] ? ' data-author-role="' . $obj['author_role'] . '"' : '';

					// Preselected Value Classname.
					$selected_value_class = ! empty( $obj['selected_value'] ) ? ' alm-filter--preselected' : '';

					// Archive/Front page.
					$is_archive = alm_filters_is_archive() ? ' data-is-archive="true"' : '';

					// Checkbox/Radio/Star Rating Role. and Aria Labelledby.
					$role       = '';
					$labelledby = '';
					if ( $field_type === 'radio' || $field_type === 'checkbox' || $field_type === 'star_rating' ) {
						$labelledby = isset( $obj ) && isset( $obj['title'] ) ? ' aria-labelledby="alm-filter-' . $filter_key . '-title"' : '';
						if ( $field_type === 'radio' || $field_type === 'star_rating' ) {
							$role = ' role="radiogroup"';
						} else {
							$role = ' role="group"';
						}
					}

					// Build output.
					$preview_class = $preview ? ' is-preview' : '';
					$output       .= '<div class="alm-filter alm-filter--group alm-filter--' . str_replace( '_', '', $obj['key'] ) . $selected_value_class . $obj['classes'] . $preview_class . '" id="alm-filter-' . $filter_count . '" data-key="' . $obj['key'] . '" data-fieldtype="' . $field_type . '"' . $taxonomy_value . $taxonomy_operator . $taxonomy_include_children . $meta_value . $meta_operator . $meta_type . $author_role . $default_selected_value . '' . $default_value . '' . $is_archive . '' . $role . $labelledby . '>';

					$section_toggle        = $obj['section_toggle'] === true ? true : false;
					$section_toggle_status = $obj['section_toggle_status'] === 'collapsed' ? 'collapsed' : 'expanded';

					$output .= alm_filters_display_title( $options_obj['id'], $obj, $section_toggle, $section_toggle_status );
					$output .= alm_filters_open_filter_container( $obj, $section_toggle, $section_toggle_status );
					$output .= alm_filters_display_description( $options_obj['id'], $obj );

					// Determine which $key to implement.
					$key = $obj['key'];
					$key = $key === 'taxonomy' ? $obj['taxonomy'] : $key; // Convert $key to $taxonomy value.
					$key = $key === 'meta' ? $obj['meta_key'] : $key; // Convert $key to $meta_key value.

					// Check to see if custom filter exists.
					$has_custom_values_filter = has_filter( 'alm_filters_' . $options_obj['id'] . '_' . self::alm_filters_revert_underscore( $key ) );

					// Custom Values / Custom Value Hook and NOT Textfield.
					if ( ( $obj['values'] || $has_custom_values_filter ) && $field_type !== 'text' ) {
						// Custom Value filter hook.
						$values = apply_filters(
							'alm_filters_' . $options_obj['id'] . '_' . self::alm_filters_revert_underscore( $key ),
							$obj['values'],
							$obj['values']
						);

						// Pass Custom Values to function.
						$output .= alm_filters_list_custom_values( $options_obj['id'], $values, $obj, $querystring );

					} elseif ( $field_type === 'text' || $field_type === 'date_picker' || $field_type === 'range_slider' ) {
							// Textfield / Date Picker / Range Slider.
							$output .= alm_filters_display_textfield( $options_obj['id'], $obj, $querystring );

						if ( $field_type === 'date_picker' ) {
							$has_datepicker = true;
						}
						if ( $field_type === 'range_slider' ) {
							$has_rangeslider = true;
						}
					} elseif ( $field_type === 'star_rating' ) {
						// Star Rating.
						$output .= self::alm_filters_display_star_rating( $options_obj['id'], $obj, $querystring );
					} else {
						// Custom Value filter hook.
						if ( has_filter( 'alm_filters_' . $options_obj['id'] . '_' . $key ) ) {
							$values  = apply_filters( 'alm_filters_' . $options_obj['id'] . '_' . self::alm_filters_revert_underscore( $key ), '' );
							$output .= alm_filters_list_custom_values( $options_obj['id'], $values, $obj, $querystring );
						} else {
							$output .= alm_filters_list_terms( $obj, $querystring, $options_obj['id'] );
						}
					}

					$output .= alm_filters_close_filter_container();
					$output .= '</div>';
				}

				// Reset/Clear Filters Button.
				$output .= alm_filters_render_controls( $options_obj, $obj );

				// Disable filter edit links while logged in.
				$is_filter_option = get_option( ALM_FILTERS_PREFIX . $options_obj['id'] );
				if ( is_user_logged_in() && current_user_can( apply_filters( 'alm_user_role', 'edit_theme_options' ) ) && apply_filters( 'alm_filters_edit', true ) && ! empty( $is_filter_option ) ) {
					$output .= '<a href="' . get_admin_url() . 'admin.php?page=ajax-load-more-filters&filter=' . $filters['id'] . '" class="alm-filters-edit" title="' . __( 'Filter', 'ajax-load-more-filters' ) . ': ' . $filters['id'] . '">' . __( 'Edit Filter', 'ajax-load-more-filters' ) . '</a>';
				}

				$output .= '<div class="alm-filters--loading"></div>';
				$output .= '</' . $container_element . '>';

				// Enqueue Datepicker CSS.
				if ( $has_datepicker ) {
					$datepicker_style = isset( $options['_alm_filters_flatpickr_theme'] ) ? $options['_alm_filters_flatpickr_theme'] : 'default';
					wp_enqueue_style( 'alm-flatpickr-' . $datepicker_style );
				}

				// Enqueue Range Slider CSS.
				if ( $has_rangeslider ) {
					wp_enqueue_style( 'alm-nouislider', ALM_FILTERS_URL . '/dist/vendor/nouislider/nouislider.min.css', '', ALM_FILTERS_VERSION );
				}

				return $output; // Print the HTML.
			}
		}

		/**
		 * Return the key operators to core alm.
		 *
		 * @deprecated 1.1
		 */
		public static function alm_filters_return_key_operators() {
			return self::$alm_filters_key_operators;
		}

		/**
		 * Parse the querystring using $_SERVER.
		 *
		 * @see https://www.php.net/manual/en/function.parse-str.php
		 *
		 * @return array URL array.
		 * @since 1.0
		 */
		public static function alm_filters_parse_url() {
			$url   = $_SERVER['QUERY_STRING'];
			$query = [];

			if ( ! $url ) {
				return ''; // Bail early if no querystring.
			}

			// Replace `%20` with space.
			$url = str_replace( '%20', ' ', $url );

			// Split URL at `&`.
			$filters = explode( '&', $url );

			// Loop all filters.
			foreach ( $filters as $filter ) {
				// Split filter at `=`.
				$filter_array = explode( '=', $filter );
				if ( ! $filter_array || count( $filter_array ) !== 2 ) {
					continue; // Bail early if not able to split value or invalid URL.
				}
				$key           = $filter_array[0]; // Get key filter.
				$query[ $key ] = $filter_array[1]; // Add to querystring array.
			}
			return $query;
		}

		/**
		 * Get all meta_key or taxonomy parameters from filter so we only parse the keys that matter in the URL.
		 *
		 * @param  array  $target Filter target IDs as an array.
		 * @param  string $key    The array key to access.
		 * @return array          An array of meta keys.
		 * @since 1.0
		 */
		public static function alm_filters_get_query_keys( $target = [], $key = '' ) {
			if ( ! is_array( $target ) || empty( $target ) ) {
				// Target is set in core ALM shortcode.
				return [];
			}

			$array = [];

			// Loop all filters.
			foreach ( $target as $id ) {
				$options = self::alm_filters_get_filter_by_id( $id ); // Get filter option from PHP GLOBALS or the options table.

				if ( $options ) {
					$options = $options['filters']; // Get filter options only.
					if ( ! $options ) {
						return [];
					}
					// Loop all filter groups.
					foreach ( $options as $filter ) {
						if ( isset( $filter[ $key ] ) ) {
							// If taxonomy or meta key, add items to array.
							if ( $key === 'taxonomy' && ( is_archive() || is_front_page() || is_home() ) ) {
								// If archive or frontpage, prepend `_` to taxonomy slug.
								$array[] = '_' . $filter[ $key ];
							} else {
								$array[] = $filter[ $key ];
							}
						}
					}
				}
			}
			return $array;
		}

		/**
		 * Build array of all filters.
		 * Taxonomy, Meta operator values are not stored in the querystring so we need to connect via $filters.
		 *
		 * @param array $target Array of Filter IDs.
		 * @return array        Array of filters.
		 * @since 1.12.0
		 */
		public static function alm_filters_get_all_filters( $target = [] ) {
			if ( ! $target ) {
				// Bail early if target is empty.
				return;
			}

			$array = [];

			// Loop all filter IDs.
			foreach ( $target as $id ) {
				$options = self::alm_filters_get_filter_by_id( $id );  // Get filter option from PHP GLOBALS or the options table.
				if ( $options ) {
					// Loop all filters in current filter.
					if ( isset( $options['filters'] ) ) {
						foreach ( $options['filters'] as $filter ) {
							$array[] = $filter;
						}
					}
				}
			}
			return $array;
		}

		/**
		 * Get filter from PHP Global variable or WP options table.
		 * Note: This used when using the PHP method alm_filters() to initiate a filter via PHP.
		 *
		 * @param string  $id          Filter ID.
		 * @param boolean $use_options Get via WP options.
		 * @return array|boolean       The filter as array.
		 */
		public static function alm_filters_get_filter_by_id( $id, $use_options = false ) {
			if ( ! $id ) {
				return false;
			}

			if ( ! $use_options && isset( $GLOBALS ) && isset( $GLOBALS[ ALM_FILTERS_PREFIX . $id ] ) ) {
				return $GLOBALS[ ALM_FILTERS_PREFIX . $id ]; // Get from PHP Globals.
			} else {
				return unserialize( get_option( ALM_FILTERS_PREFIX . $id ) ); // Get filter as WP option.
			}
		}

		/**
		 * Get facet index by ID.
		 *
		 * @param string $id     The Filter ID.
		 * @return array|boolean The filter as array.
		 */
		public static function get_facet_index_by_id( $id ) {
			return $id ? unserialize( get_option( ALM_FILTERS_FACET_PREFIX . $id ) ) : false;
		}

		/**
		 * Convert key (shortcode params) to camelCase. e.g. post_type => postType.
		 *
		 * @param string $value Value to replace underscores.
		 * @return string The updated value.
		 * @since 1.0
		 */
		public static function alm_filters_replace_underscore( $value ) {
			$underscore = strpos( $value, '_' );
			if ( $underscore ) {
				$char_to_replace = substr( $value, $underscore + 1, 1 );
				$value           = str_replace( '_' . $char_to_replace, strToUpper( $char_to_replace ), $value );
			}

			// If value is year, month or day add '_' before to prevent 404s. e.g. _year.
			$value = ( $value === 'year' || $value === 'month' || $value === 'day' || $value === 'author' ) ? '_' . $value : $value;
			return $value;
		}

		/**
		 * Remove the leading _ from certain key values.
		 *
		 * @param string $key The key value to replace.
		 * @return string The updated key value.
		 * @since 1.0
		 */
		public static function alm_filters_revert_underscore( $key ) {
			// If value is _year, _month, _day or _author remove the '_'.
			$key = ( $key === '_year' || $key === '_month' || $key === '_day' || $key === '_author' ) ? str_replace( '_', '', $key ) : $key;
			return $key;
		}

		/**
		 * Renders a toggle checkbox filter.
		 *
		 * @param array  $obj      Filter object array.
		 * @param string $position The checkbox position (before/after).
		 * @return string          Raw HTML output.
		 * @since 1.0
		 */
		public static function alm_filters_display_toggle( $obj, $position = 'before' ) {
			$return = '';
			if ( isset( $obj['field_type'] ) && $obj['field_type'] === 'checkbox' && isset( $obj['checkbox_toggle'] ) && isset( $obj['checkbox_toggle_label'] ) ) {
				if ( ( $obj['checkbox_toggle'] === 'before' && $position === 'before' ) || ( $obj['checkbox_toggle'] === 'after' && $position === 'after' ) ) {
					$return .= '<li class="alm-filter--checkbox field-select-all">';
					$return .= '<div class="alm-filter--link field-checkbox field-toggle"
						data-all="true"
						data-value=""
						data-type="all"
						role="checkbox"
						tabindex="0"
						aria-checked="false"
						>';
					$return .= $obj['checkbox_toggle_label'];
					$return .= '</div>';
					$return .= '</li>';
				}
			}
			return $return;
		}

		/**
		 * Renders a Star Rating filter.
		 *
		 * @param string $id          Filter ID.
		 * @param array  $obj         Filter object array.
		 * @param string $querystring The querystring.
		 * @return string             Raw HTML output.
		 * @since 1.0
		 */
		public static function alm_filters_display_star_rating( $id, $obj, $querystring ) {
			$current = '';
			if ( isset( $querystring ) && isset( $obj['meta_key'] ) && isset( $querystring[ $obj['meta_key'] ] ) ) {
				$current = $querystring[ $obj['meta_key'] ];
			}

			$start = isset( $obj['star_rating_min'] ) && is_numeric( $obj['star_rating_min'] ) ? (int) $obj['star_rating_min'] : 1;
			$end   = isset( $obj['star_rating_max'] ) && is_numeric( $obj['star_rating_max'] ) ? (int) $obj['star_rating_max'] : 5;
			$end   = $end <= $start ? $start + 1 : $end;

			$output        = '';
			$init_feedback = '';
			$output       .= '<ul class="alm-filter--align-items">';

			for ( $i = $start; $i <= $end; $i++ ) {
				$active       = $current == $i ? ' active' : ''; // phpcs:ignore
				$highlight    = $i <= $current ? ' highlight' : '';
				$aria_checked = $current == $i ? 'aria-checked="true"' : 'aria-checked="false"'; // phpcs:ignore
				$label        = $i > 1 ? apply_filters( 'alm_filters_stars_label', __( 'stars and up', 'ajax-load-more-filters' ) ) : apply_filters( 'alm_filters_stars_label_singular', __( 'star and up', 'ajax-load-more-filters' ) );
				$label        = $i === $end ? apply_filters( 'alm_filters_stars_label_last', __( 'stars', 'ajax-load-more-filters' ) ) : $label;

				$init_feedback = $current == $i ? $current . ' ' . $label : $init_feedback; // phpcs:ignore

				$output .= '<li class="alm-filter--radio">';
				$output .= '<div
							class="alm-filter--link field-radio field-starrating' . $active . $highlight . '"
							role="radio"
							data-type="radio"
							data-value="' . $i . '"
							data-text="' . $i . ' ' . $label . '"
							id="star-' . $i . '"
							' . $aria_checked . '
							title="' . $i . ' ' . $label . '"
							tabindex="0"
							>';
				$output .= '<div><i class="alm-star" aria-hidden="true"></i></div>';
				$output .= '<span class="offscreen">' . $i . ' ' . $label . '</span>';
				$output .= '</div>';
				$output .= '</li>';
			}

				$output .= '<span class="alm-star--feedback" aria-live="polite" aria-atomic="true">';
				$output .= ! empty( $init_feedback ) ? $init_feedback : '';
				$output .= '</span>';

			$output .= '</ul>';

			return $output;
		}

		/**
		 * Set the filter container element.
		 *
		 * @param string $id       The ID of the filter.
		 * @param array  $obj      Filter object array.
		 * @param string $location The location either open or close.
		 * @return string          Raw HTML output.
		 */
		public static function alm_filters_get_container( $id = '', $obj = [], $location = 'open' ) {
			if ( ! $obj ) {
				return false;
			}

			$field_type = $obj['field_type'];

			// Radio/Checkboxes.
			if ( $field_type === 'checkbox' || $field_type === 'radio' ) {
				return '<' . ( ( $location === 'close' ) ? '/' : '' ) . 'ul>';
			}

			// Select/Multi-Select.
			if ( $field_type === 'select' || $field_type === 'select_multiple' ) {
				$data = '';

				if ( $location === 'open' ) {
					// Open.
					$data .= '<div class="alm-filter--select ' . apply_filters( 'alm_filters_select_class', '' ) . '">';

					// Display field label for select inputs.
					if ( $obj['label'] ) {
						$data .= alm_filters_render_label( $id, $obj, $obj['field_id'] . '-' . $obj['index'] );
					}

					$data_count          = self::$facets && $obj['show_count'] ? ' data-count="true"' : ''; // Data attribute for facets.
					$data_count_template = self::$facets && $obj['show_count'] ? ' data-count-template="' . apply_filters( 'alm_filters_show_count_select_display', '(%count%)' ) . '"' : ''; // Data attribute for facets.

					$multiple = $field_type === 'select_multiple' ? ' multiple' : '';
					$data    .= '<select id="' . $obj['field_id'] . '-' . $obj['index'] . '" class="alm-filter--item"' . $multiple . $data_count . $data_count_template . '>';

				} else {
					// Close.
					$data .= '</select>';
					$data .= '</div>';

				}
				return $data;
			}
		}

		/**
		 * Enqueue filters admin js and css.
		 *
		 * @since 1.0
		 */
		public static function alm_enqueue_filters_admin_scripts() {
			wp_enqueue_style( 'alm-filters-admin', ALM_FILTERS_URL . '/dist/css/admin_styles.css', '', ALM_FILTERS_VERSION );
			wp_enqueue_script( 'alm-filters-admin', ALM_FILTERS_URL . '/dist/js/admin.js', '', ALM_FILTERS_VERSION, true );

			wp_localize_script(
				'alm-filters-admin',
				'alm_filters_localize',
				[
					'root'                => esc_url_raw( rest_url() ),
					'nonce'               => wp_create_nonce( 'wp_rest' ),
					'base_url'            => get_admin_url() . 'admin.php?page=ajax-load-more-filters',
					'cancel_index'        => __( 'Are you sure you want to cancel facet indexing? This will immediately cancel indexing for all facets in the queue.', 'ajax-load-more-filters' ),
					'rebuild_index'       => __( 'Are you sure you want to rebuild the facet index for this filter?', 'ajax-load-more-filters' ),
					'delete_filter'       => __( 'Are you sure you want to permanently delete', 'ajax-load-more-filters' ),
					'ordering_parameters' => __( 'Ordering Parameters', 'ajax-load-more-filters' ),
					'date_parameters'     => __( 'Date Parameters', 'ajax-load-more-filters' ),
					'true_parameter'      => __( 'True', 'ajax-load-more-filters' ),
					'false_parameter'     => __( 'False', 'ajax-load-more-filters' ),
					'category_parameters' => __( 'Category Parameters', 'ajax-load-more-filters' ),
					'field_type_beta'     => __( 'Beta', 'ajax-load-more-filters' ),
					'field_type_basic'    => __( 'Basic Form Fields', 'ajax-load-more-filters' ),
					'field_type_adv'      => __( 'Advanced Form Fields', 'ajax-load-more-filters' ),
					'tag_parameters'      => __( 'Tag Parameters', 'ajax-load-more-filters' ),
					'create_filter'       => __( 'Create Filter', 'ajax-load-more-filters' ),
					'update_filter'       => __( 'Save Changes', 'ajax-load-more-filters' ),
					'saving_filter'       => __( 'Saving...', 'ajax-load-more-filters' ),
					'saved_filter'        => __( 'Filter Saved', 'ajax-load-more-filters' ),
					'expand_filter'       => __( 'Expand Filter', 'ajax-load-more-filters' ),
					'close_filter'        => __( 'Close Filter', 'ajax-load-more-filters' ),
				]
			);
		}

		/**
		 * An empty function to determine if users is true.
		 *
		 * @since 1.0
		 */
		public function alm_filters_installed() {
			 // phpcs:ignore
			// Empty return.
		}

		/**
		 * Build the Filters shortcode params and send back to core ALM.
		 *
		 * Note: $target is converted to filters-target for data atts.
		 *
		 * @param string $filters   The value of filters in the shortcode.
		 * @param string $target    The value of filters in the shortcode.
		 * @param string $url       The value of url in the shortcode.
		 * @param string $paging    The value of paging in the shortcode.
		 * @param string $scroll    The value of scroll in the shortcode.
		 * @param string $scrolltop The value of scrolltop in the shortcode.
		 * @param string $debug     The value of debug in the shortcode.
		 * @param string $options   ALM global settings.
		 * @return string           The generated shortcode attributes.
		 * @since 1.0
		 */
		public function alm_filters_shortcode_params( $filters, $target, $url, $paging, $scroll, $scrolltop, $debug, $options ) {
			$data  = ' data-filters="true"';
			$data .= ' data-filters-target="' . strtolower( $target ) . '"';
			$data .= ' data-filters-url="' . $url . '"';
			$data .= ' data-filters-paging="' . $paging . '"';
			$data .= ' data-filters-scroll="' . $scroll . '"';
			$data .= ' data-filters-scrolltop="' . $scrolltop . '"';
			$data .= $debug === 'true' ? ' data-filters-debug="true"' : '';

			if ( $target && alm_filters_has_facets( $target ) ) {
				// Dynamically set `facets="true"` if set in filter.
				$data .= ' data-facets="true"';
			}

			return $data;
		}

		/**
		 *  Return the current page number via querystring.
		 *
		 *  @since 1.0
		 */
		public static function alm_filters_get_page_num() {
			$params = filter_input_array( INPUT_GET ); // phpcs:ignore
			$pg     = isset( $params['pg'] ) ? $params['pg'] : 1;
			return $pg;
		}

		/**
		 * Is the field a radio or select?
		 *
		 * @param string $field Field Type.
		 * @return boolean
		 * @since 1.2
		 */
		public static function alm_filters_radio_select( $field ) {
			$return = false;
			if ( $field === 'radio' || $field === 'select' || $field === 'select_multiple' ) {
				$return = true;
			}
			return $return;
		}

		/**
		 * Get all filters from the wp_options table.
		 *
		 * @param  string $prefix The prefixed string to use for comparision.
		 * @return array          An array of filters.
		 * @since 1.1
		 */
		public static function alm_get_all_filters( $prefix = ALM_FILTERS_PREFIX ) {
			global $wpdb;
			$prefix  = esc_sql( $prefix );
			$options = $wpdb->options;
			$t       = esc_sql( "$prefix%" );
			$sql     = $wpdb->prepare( "SELECT option_name FROM $options WHERE option_name LIKE '%s'", $t ); //phpcs:ignore
			$filters = $wpdb->get_col( $sql ); //phpcs:ignore

			return self::alm_remove_filter_license_options( $filters );
		}

		/**
		 * Remove the following from the filter listing as they are used for plugin options.
		 * - alm_filters_license_key
		 * - alm_filters_license_status
		 *
		 * @param  array $filters The current filters.
		 * @return array          An array of active filters
		 * @since 1.5
		 */
		public static function alm_remove_filter_license_options( $filters = [] ) {
			if ( $filters ) {
				$new_filters = [];
				foreach ( $filters as $filter ) {
					if ( $filter !== 'alm_filters_license_status' && $filter !== 'alm_filters_license_key' ) {
						$new_filters[] = $filter;
					}
				}
				return $new_filters;
			}
		}

		/**
		 * Replace `alm_filter_` from option name.
		 *
		 * @param strign $string The filter name to replace.
		 * @return string        Updated filter name.
		 * @since 1.5
		 */
		public static function alm_filters_replace_string( $string = '' ) {
			return $string ? str_replace( 'alm_filter_', '', $string ) : '';
		}


		/**
		 * Prevent URL redirects on frontpage with filters.
		 * Note: This is to prevent `+` in querystring parameters from being encoded.
		 * website.com?=category=design+development was redirecting to website.com?=category=design%20development
		 *
		 * @see https://developer.wordpress.org/reference/hooks/redirect_canonical/
		 * @param boolean $redirect Should redirect.
		 * @return boolean
		 */
		public function alm_filters_frontpage_canonical_redirect( $redirect ) {
			$query_params = filter_input_array( INPUT_GET ); // phpcs:ignore
			if ( is_front_page() && $query_params ) {
				$redirect = false;
			}
			return $redirect;
		}
	}

	/**
	 * Sanitize the license activation.
	 *
	 * @param string $new License key as a string.
	 * @since 1.0.0
	 */
	function alm_filters_sanitize_license( $new ) {
		$old = get_option( 'alm_filters_license_key' );
		if ( $old && $old !== $new ) {
			delete_option( 'alm_filters_license_status' );
		}
		return $new;
	}

	/**
	 * The main function responsible for returning Ajax Load More Filters.
	 *
	 * @since 1.0
	 */
	function alm_filters_init() {
		global $alm_filters_init;
		if ( ! isset( $alm_filters_init ) ) {
			$alm_filters_init = new ALMFilters();
		}
		return $alm_filters_init;
	}
	alm_filters_init();

endif;

/**
 * The public function responsible for building the filters.
 *
 * @param array|string $data     Array of filter data or filter ID.
 * @param string       $target   The target ID.
 * @param string       $redirect Redirect user to new URL after filter.
 * @since 1.0
 */
function alm_filters( $data, $target, $redirect = false ) {
	if ( is_array( $data ) ) {
		// Parse the filter array.
		$id = isset( $data['id'] ) ? $data['id'] : false;
		if ( ! $id ) {
			return;
		}
		// Set array as global variable.
		$GLOBALS[ ALM_FILTERS_PREFIX . $id ] = $data; // phpcs:ignore

	} else {
		// Parse as filter ID.
		$filter = unserialize( get_option( ALM_FILTERS_PREFIX . $data ) );
		$data   = is_array( $filter ) ? $filter : [];

	}

	return ALMFilters::init( $data, $target, false, $redirect );
}

/**
 * Software Licensing.
 *
 * @return void
 */
function alm_filters_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_filters_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			[
				'version' => ALM_FILTERS_VERSION,
				'license' => $license_key,
				'item_id' => ALM_FILTERS_ITEM_NAME,
				'author'  => 'Darren Cooney',
			]
		);
	}
}
add_action( 'admin_init', 'alm_filters_plugin_updater', 0 );
/* End Software Licensing */
