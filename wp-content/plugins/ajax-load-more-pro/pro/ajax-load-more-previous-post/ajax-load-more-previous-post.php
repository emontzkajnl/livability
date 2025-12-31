<?php
/**
 * Plugin Name: Ajax Load More: Single Post
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/single-post/
 * Description: Ajax Load More add-on for infinite scrolling single posts.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: https://connekthq.com
 * Version: 1.8.0
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALMSinglePost
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ALM_PREV_POST_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_PREV_POST_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_PREV_POST_VERSION', '1.8.0' );
define( 'ALM_PREV_POST_RELEASE', 'December 11, 2025' );

if ( ! class_exists( 'ALM_Single_Post' ) ) :
	/**
	 * Initiate the class.
	 */
	class ALM_Single_Post {

		/**
		 * Construct class function.
		 */
		public function __construct() {
			add_action( 'alm_prev_post_installed', [ $this, 'alm_prev_post_installed' ] );
			add_action( 'alm_single_post_installed', [ $this, 'alm_single_post_installed' ] );

			// Admin Ajax and REST API.
			add_action( 'rest_api_init', [ $this, 'alm_single_post_register_route' ] );
			add_action( 'wp_ajax_alm_get_single', [ $this, 'alm_get_single_post' ] ); // Deprecated.
			add_action( 'wp_ajax_nopriv_alm_get_single', [ $this, 'alm_get_single_post' ] ); // Deprecated.

			add_action( 'alm_prev_post_settings', [ $this, 'alm_single_post_settings' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'alm_single_post_enqueue_scripts' ] );
			add_action( 'posts_where', [ $this, 'alm_single_query_where' ], 10, 2 );
			add_action( 'init', [ $this, 'init' ] );

			add_filter( 'alm_single_post_inc', [ $this, 'alm_single_post_inc' ], 10, 5 );
			add_filter( 'alm_single_post_args', [ $this, 'alm_single_post_args' ], 10, 2 );
			add_filter( 'alm_single_post_shortcode', [ $this, 'alm_single_post_shortcode' ], 10, 10 );
		}

		/**
		 * Get the REST API route for single post retrieval.
		 *
		 * @return string
		 */
		public static function alm_single_post_get_route() {
			return esc_url_raw( rest_url( 'ajax-load-more/single-posts/next-post' ) );
		}

		/**
		 * Register REST API route for single post retrieval.
		 *
		 * @return void
		 */
		public function alm_single_post_register_route() {
			register_rest_route(
				'ajax-load-more/single-posts/',
				'next-post',
				[
					'methods'             => 'GET',
					'callback'            => [ $this, 'alm_get_single_rest_request' ],
					'args'                => [],
					'permission_callback' => function () {
						return true;
					},
				]
			);
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @return void
		 */
		public function init() {
			load_plugin_textdomain( 'ajax-load-more-single-post', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Enqueue Single Post scripts.
		 *
		 * @return void
		 */
		public function alm_single_post_enqueue_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_register_script( 'alm-single-posts', plugins_url( '/dist/js/alm-single-posts' . $suffix . '.js', __FILE__ ), [ 'ajax-load-more' ], ALM_PREV_POST_VERSION, true );
		}

		/**
		 * Get single post params from request.
		 *
		 * @param array $params The request params.
		 * @return array
		 * @since 1.0
		 */
		public function alm_get_single_post_params( $params ) {
			$init            = isset( $params['init'] ) ? $params['init'] : false;
			$id              = isset( $params['id'] ) ? $params['id'] : '';
			$exclude_post_id = isset( $params['initial_id'] ) ? $params['initial_id'] : '';
			$tax             = isset( $params['taxonomy'] ) ? $params['taxonomy'] : '';
			$exclude_terms   = isset( $params['excluded_terms'] ) ? $params['excluded_terms'] : '';
			$post_type       = isset( $params['post_type'] ) ? $params['post_type'] : 'post';
			$order           = isset( $params['order'] ) && $params['order'] ? $params['order'] : 'previous';

			// Order - If order is `latest` and first run and ordered by latest, set posts to load in order by date.
			$order = $init === 'false' && $order === 'latest' ? 'previous' : $order;

			$array = [
				'init'            => $init,
				'id'              => $id,
				'exclude_post_id' => $exclude_post_id,
				'tax'             => $tax,
				'exclude_terms'   => $exclude_terms,
				'post_type'       => $post_type,
				'order'           => $order,
			];

			$hash      = md5( wp_json_encode( $array ) ); // Create a hash of the array to use as a transient key.
			$transient = get_transient( 'alm_single_post_' . $hash );
			if ( $transient ) {
				return $transient;
			}

			$data = $this->alm_get_single_posts_data( $array );
			set_transient( 'alm_single_post_' . $hash, $data, apply_filters( 'alm_single_post_transient_expiration', MINUTE_IN_SECONDS ) );
			return $data;
		}

		/**
		 * Fetch next post data via REST API.
		 *
		 * @param WP_REST_Request $request Rest request object.
		 * @return void
		 */
		public function alm_get_single_rest_request( WP_REST_Request $request ) {
			wp_send_json( $this->alm_get_single_post_params( $request->get_params() ) );
		}

		/**
		 * Fetch next post data via admin-ajax.php.
		 *
		 * @deprecated 2.0 Use REST API route instead.
		 * @return void
		 */
		public function alm_get_single_post() {
			$params = filter_input_array( INPUT_GET );
			wp_send_json( $this->alm_get_single_post_params( $params ) );
		}

		/**
		 * Get single post data.
		 *
		 * @param array $data Array containing query data.
		 * @return array
		 */
		public function alm_get_single_posts_data( $data ) {
			if ( $data && $data['id'] ) {

				switch ( $data['order'] ) {
					case 'latest':
						// Get the latest (newest) post.
						return self::alm_get_latest_post( $data['exclude_post_id'], $data['post_type'], $data['tax'], $data['exclude_terms'] );

					case 'next':
						// Get next post ordered by date.
						return self::alm_get_next_post( $data['id'], $data['tax'], $data['exclude_terms'], $data['exclude_post_id'] );

					case 'previous':
						// Get previous post ordered by date.
						return self::alm_get_previous_post( $data['id'], $data['tax'], $data['exclude_terms'], $data['exclude_post_id'] );

					default:
						// Get post ID array (use as default for ease).
						return self::alm_get_post_in_array( $data['id'], $data['order'] );
				}
			}
		}

		/**
		 * Get the next post in the array.
		 *
		 * @param string $id    Post ID.
		 * @param array  $array The array of post IDs.
		 * @return array
		 */
		public static function alm_get_post_in_array( $id, $array ) {
			global $post;

			// Store the existing post object for later so we don't lose it.
			$old_global = $post;

			// Remove whitespace and convert to array.
			$array = explode( ',', str_replace( ' ', '', $array ) );

			$previous_post = '';

			if ( in_array( $id, $array, true ) ) {
				// ID found in array.
				$length = count( $array );
				$index  = array_search( $id, $array, true );
				if ( $index < $length - 1 ) { // Last element.
					$previous_post = get_post( $array[ $index + 1 ] );
				}
			} else {
				// Get first element in array.
				$previous_post = get_post( $array[0] );
			}

			// Reset global $post object.
			$post = $old_global; //phpcs:ignore

			// Build the $data object.
			$data = self::alm_build_data_object( $id, $previous_post );

			return $data;
		}

		/**
		 * Get the previous post by date using `previous_post` method
		 *
		 * @param  string $id              The post ID.
		 * @param  string $tax             The taxonomy slug.
		 * @param  string $exclude_terms   A comma separated list of term IDs.
		 * @param  string $exclude_post_id The post ID to exclude.
		 * @return array
		 */
		public static function alm_get_previous_post( $id, $tax, $exclude_terms, $exclude_post_id ) {
			global $post;

			// Store the existing post object for later so we don't lose it.
			$old_global = $post;

			// Get post object.
			$post = get_post( $id ); // phpcs:ignore

			// Get Previous Post.
			$previous_post = ! empty( $tax ) ? get_previous_post( true, $exclude_terms, $tax ) : get_previous_post( false, $exclude_terms );

			// If Previous Post === Original post.
			if ( $previous_post && $previous_post->ID === $exclude_post_id ) {
				$post          = get_post( $previous_post->ID ); // phpcs:ignore
				$previous_post = ( ! empty( $tax ) ) ? get_previous_post( true, $exclude_terms, $tax ) : get_previous_post( false, $exclude_terms );
			}

			// Reset global $post object.
			$post = $old_global; // phpcs:ignore

			// Build the $data object.
			$data = self::alm_build_data_object( $id, $previous_post );

			return $data;
		}

		/**
		 * Get the next post by date using `next_post` method.
		 *
		 * @param  string $id              The post ID.
		 * @param  string $tax             The taxonomy slug.
		 * @param  string $exclude_terms   A comma separated list of term IDs.
		 * @param  string $exclude_post_id The post ID to exclude.
		 * @return array
		 */
		public static function alm_get_next_post( $id, $tax, $exclude_terms, $exclude_post_id ) {
			global $post;

			// Store the existing post object for later so we don't lose it.
			$old_global = $post;

			// Get post object.
			$post = get_post( $id ); // phpcs:ignore

			// Get next post.
			$next_post = ! empty( $tax ) ? get_next_post( true, $exclude_terms, $tax ) : get_next_post( false, $exclude_terms );

			// If next === original post.
			if ( $next_post && $next_post->ID === $exclude_post_id ) {
				$post      = get_post( $next_post->ID ); //phpcs:ignore
				$next_post = ( ! empty( $tax ) ) ? get_next_post( true, $exclude_terms, $tax ) : get_next_post( false, $exclude_terms );
			}

			// Reset global $post object.
			$post = $old_global; //phpcs:ignore

			// Build the $data object.
			$data = self::alm_build_data_object( $id, $next_post );

			return $data;
		}

		/**
		 * Get the latest (newest) post and return the data.
		 *
		 * @param  string $id            The post ID.
		 * @param  string $post_type     The post type.
		 * @param  string $taxonomy      The taxonomy slug.
		 * @param  string $exclude_terms A comma separated list of term IDs.
		 * @return array
		 */
		public static function alm_get_latest_post( $id, $post_type, $taxonomy, $exclude_terms ) {
			global $post;

			// Store the existing post object for later so we don't lose it.
			$old_global = $post;

			// Get post object.
			$previous_post = get_post( self::alm_query_latest_post_id( $id, $post_type, $taxonomy, $exclude_terms ) );

			// Reset global $post object.
			$post = $old_global; // phpcs:ignore

			// Build the $data object.
			$data = self::alm_build_data_object( $id, $previous_post );

			return $data;
		}

		/**
		 * Run a get_posts function to get the most recent post ID.
		 *
		 * @param  string $id            The post ID.
		 * @param  string $post_type     The post type.
		 * @param  string $taxonomy      The taxonomy slug.
		 * @param  string $exclude_terms A comma separated list of term IDs.
		 * @return string                The post ID.
		 */
		public static function alm_query_latest_post_id( $id, $post_type, $taxonomy, $exclude_terms ) {
			// Get latest post not including the current.
			$args = [
				'post_type'        => $post_type,
				'posts_per_page'   => 1,
				'post__not_in'     => [ $id ],
				'orderby'          => 'date',
				'order'            => 'DESC',
				'fields'           => 'ids',
				'suppress_filters' => false,
			];

			// If $in_same_term, loop all tax terms and query based on the terms.
			if ( $taxonomy ) {
				$terms = get_the_terms( $id, $taxonomy );
				if ( $terms ) {
					$found_terms = [];
					foreach ( $terms as $term ) {
						$found_terms[] = $term->slug;
					}
					$args['tax_query'][] = [
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $found_terms,
						'operator' => 'IN',
					];
				}
			}

			// Exclude certain terms.
			if ( $exclude_terms ) {
				$exclude_terms = explode( ',', $exclude_terms );
				foreach ( $exclude_terms as $id ) {
					$term_data = get_term( $id );
					if ( $term_data ) {
						$args['tax_query'][] = [
							'taxonomy' => $term_data->taxonomy,
							'field'    => 'term_id',
							'terms'    => $id,
							'operator' => 'NOT IN',
						];
					}
				}
			}

			// Get the posts.
			$posts = get_posts( $args );
			if ( $posts ) {
				foreach ( $posts as $post_id ) {
					return $post_id;
				}
			} else {
				return null;
			}
		}

		/**
		 * Build the data object based on the $previous_post object.
		 *
		 * @param string $id The post ID.
		 * @param object $previous_post The post object.
		 * @return array
		 */
		public static function alm_build_data_object( $id, $previous_post ) {
			$data = [];

			if ( $previous_post ) {
				$data['has_previous_post'] = true;
				$data['prev_id']           = $previous_post->ID;
				$data['prev_slug']         = $previous_post->post_name;
				$data['prev_permalink']    = get_permalink( $previous_post->ID );
				$title                     = '';

				// Yoast SEO Title.
				if ( function_exists( 'wpseo_replace_vars' ) ) {
					$title = self::alm_convert_yoast_title( $previous_post );
				}

				if ( empty( $title ) ) {
					$title = strip_tags( html_entity_decode( get_the_title( $previous_post->ID ) ) ); // phpcs:ignore
				}

				$data['prev_title'] = $title;

			} else {
				$data['has_previous_post'] = false;
				return $data;
			}

			$data['current_id'] = (int) $id;
			$data['permalink']  = get_permalink( $id );
			$data['title']      = wp_strip_all_tags( get_the_title( $id ) );

			return $data;
		}

		/**
		 * Get the Yoast page title.
		 *
		 * @param object $post The post object.
		 * @return string The Yoast title.
		 */
		public static function alm_convert_yoast_title( $post ) {
			$yoast_title = get_post_meta( $post->ID, '_yoast_wpseo_title', true );
			if ( empty( $yoast_title ) ) {
				$wpseo_titles = get_option( 'wpseo_titles', [] );
				$yoast_title  = isset( $wpseo_titles[ 'title-' . $post->post_type ] ) ? $wpseo_titles[ 'title-' . $post->post_type ] : get_the_title( $post->ID );
			}
			return wpseo_replace_vars( $yoast_title, $post );
		}

		/**
		 *  Set the `single_post` query args.
		 *
		 *  @param int   $id The current post ID.
		 *  @param array $post_type The post type.
		 *  @return array The post args.
		 */
		public function alm_single_post_args( $id, $post_type ) {
			$args = [
				'post__in'       => [ $id ],
				'post_type'      => $post_type,
				'posts_per_page' => 1,
			];
			return $args;
		}

		/**
		 * Get the content for the first single post include.
		 *
		 * @param string $repeater       The Repeater Template name.
		 * @param string $type           The Repeater type.
		 * @param string $theme_repeater The Theme Repeater name.
		 * @return void
		 */
		public function alm_single_post_inc( $repeater, $type, $theme_repeater ) {
			ob_start();
			if ( $theme_repeater !== 'null' && has_filter( 'alm_get_theme_repeater' ) ) {
				do_action( 'alm_get_theme_repeater', $theme_repeater, 1, 1, 1, 1, '' ); // Theme Repeater.
			} else {
				include alm_get_current_repeater( $repeater, $type ); // Standard Repeater.
			}
			$return = ob_get_contents();
			ob_end_clean();
			return $return;
		}

		/**
		 * Set WP Query params using `posts_where` clause.
		 * Force is_single() and is_singular() to be true in the ajax call.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/posts_where/
		 *
		 * @param string   $where The WHERE clause of the query.
		 * @param WP_Query $query The WP_Query instance (passed by reference).
		 */
		public function alm_single_query_where( $where, $query ) {
			$alm_single_query = $query->get( 'alm_query' );
			if ( $alm_single_query && $alm_single_query === 'single_posts' ) {
				global $wp_query;
				$wp_query->is_single   = true;
				$wp_query->is_feed     = true;
				$wp_query->is_singular = true;
				$wp_query->in_the_loop = true;

				error_reporting( 0 ); // phpcs:ignore
			}
			return $where;
		}

		/**
		 * Build Next Post shortcode params and send back to core ALM.
		 *
		 * @param string $id The post ID.
		 * @param string $order The post order.
		 * @param string $tax The taxonomy.
		 * @param string $excluded The excluded terms.
		 * @param string $progress_bar The progress bar settings.
		 * @param array  $options The ALM options array.
		 * @param string $target The target selector.
		 * @param string $query_order The custom query order.
		 * @param string $query_args The custom query args.
		 * @param string $preview The preview settings.
		 * @return string The shortcode params.
		 */
		public function alm_single_post_shortcode( $id, $order, $tax, $excluded, $progress_bar, $options, $target, $query_order, $query_args, $preview ) {
			$return  = ' data-single-post="true"';
			$return .= ' data-single-post-id="' . $id . '"';

			// Custom Query.
			if ( ! empty( $order ) && $order === 'query' ) {
				$return .= ' data-single-post-query="' . $query_order . '"';
				$return .= ' data-single-post-order="' . self::alm_single_post_custom_query( $id, $query_order, $query_args ) . '"';
			}

			// Post Order.
			if ( ! empty( $order ) && $order !== 'query' ) {
				$return .= ' data-single-post-order="' . $order . '"';
			}

			// Taxonomy.
			if ( ! empty( $tax ) ) {
				$return .= ' data-single-post-taxonomy="' . $tax . '"';
			}

			// Excluded Terms.
			if ( ! empty( $excluded ) ) {
				$return .= ' data-single-post-excluded-terms="' . $excluded . '"';
			}

			// Target.
			if ( ! empty( $target ) ) {
				$return .= ' data-single-post-target="' . $target . '"';
			}

			// Preview.
			if ( ! empty( $preview ) && $preview !== 'false' ) {
				// Convert to array.
				$preview_arr     = explode( ':', $preview );
				$preview_label   = isset( $preview_arr[0] ) ? $preview_arr[0] : apply_filters( 'alm_single_post_preview_button_label', 'Continue Reading' );
				$preview_height  = isset( $preview_arr[1] ) ? intval( $preview_arr[1] ) : apply_filters( 'alm_single_post_preview_height', 700 );
				$preview_element = isset( $preview_arr[2] ) ? $preview_arr[2] : apply_filters( 'alm_single_post_preview_element', 'default' );
				$return         .= ' data-single-post-preview="' . $preview_label . ':' . $preview_height . ':' . $preview_element . '"';
			}

			// Set scrolltop.
			$single_post_scrolltop = '30';
			$single_post_scrolltop = isset( $options['_alm_prev_post_scrolltop'] ) ? $options['_alm_prev_post_scrolltop'] : $single_post_scrolltop;

			$single_post_controls = '1';
			if ( isset( $options['_alm_prev_post_browser_controls'] ) ) {
				$single_post_controls = $options['_alm_prev_post_browser_controls'];
			}

			// Page Title.
			$single_post_title_template = '';
			if ( isset( $options['_alm_prev_post_title'] ) ) {
				$single_post_title_template = $options['_alm_prev_post_title'];
			}

			$return .= ' data-single-post-title-template="' . $single_post_title_template . '"';
			$return .= ' data-single-post-site-title="' . get_bloginfo( 'name' ) . '"';
			$return .= ' data-single-post-site-tagline="' . get_bloginfo( 'description' ) . '"';
			$return .= ' data-single-post-scrolltop="' . $single_post_scrolltop . '"';
			$return .= ' data-single-post-controls="' . $single_post_controls . '"';
			$return .= ' data-single-post-progress-bar="' . $progress_bar . '"';

			return $return;
		}

		/**
		 * A custom taxonomy query.
		 *
		 * @param string $post_id Current Post ID.
		 * @param string $query_order The post type to query.
		 * @param string $query_args The query to build.
		 */
		public static function alm_single_post_custom_query( $post_id = '', $query_order = 'previous', $query_args = null ) {
			$params = filter_input_array( INPUT_GET );

			// Exit if this is an Ajax request as this should run on page load only.
			if ( isset( $params ) && isset( $params['alm_page'] ) ) {
				return false;
			}

			if ( empty( $post_id ) || empty( $query_args ) ) {
				return false;
			}

			$args = ALM_QUERY_ARGS::alm_build_queryargs( $query_args, true );

			$args['fields']         = 'ids';
			$args['orderby']        = 'date';
			$args['order']          = 'DESC';
			$args['post__not_in']   = [ $post_id ];
			$args['posts_per_page'] = apply_filters( 'alm_single_post_posts_per_page_' . $args['alm_id'], '40' ); // phpcs:ignore

			// Custom Query Ordering, used with custom query ordering.
			if ( 'previous' === $query_order ) {
				$args['date_query'] = [
					[
						'before' => get_the_date( 'F d Y g:i a', $post_id ),
					],
					'inclusive' => true,
				];
			}

			/**
			 * Query Hook.
			 *
			 * @return array Updated query args.
			 */
			$args = apply_filters( 'alm_single_post_query_args_' . $args['alm_id'], $args, $post_id );

			// WP Query.
			$alm_custom_query = new WP_Query( $args );

			if ( $alm_custom_query->have_posts() ) {
				return implode( ',', $alm_custom_query->posts );
			} else {
				return '';
			}

			wp_reset_query(); // phpcs:ignore
		}

		/**
		 * An empty function to determine if Previous Post is active.
		 *
		 * @return void
		 */
		public function alm_prev_post_installed() {
			// Empty.
		}

		/**
		 * An empty function to determine if Single Posts is active.
		 *
		 * @return void
		 */
		public function alm_single_post_installed() {
			// Empty.
		}

		/**
		 * Create the Single Post settings panel.
		 *
		 * @return void
		 */
		public function alm_single_post_settings() {
			register_setting(
				'alm_prev_post_license',
				'alm_prev_post_license_key',
				'alm_single_post_sanitize_license'
			);

			add_settings_section(
				'alm_prev_post_settings',
				'Single Post Settings',
				'alm_single_post_callback',
				'ajax-load-more'
			);

			add_settings_field(
				'_alm_prev_post_title',
				__( 'Page Title Template', 'ajax-load-more-single-post' ),
				'alm_single_post_title_callback',
				'ajax-load-more',
				'alm_prev_post_settings'
			);

			add_settings_field(
				'_alm_prev_post_browser_controls',
				__( 'Fwd/Back Buttons', 'ajax-load-more-single-post' ),
				'alm_single_post_browser_controls_callback',
				'ajax-load-more',
				'alm_prev_post_settings'
			);

			add_settings_field(
				'_alm_prev_post_scrolltop',
				__( 'Scroll Top', 'ajax-load-more-single-post' ),
				'alm_single_post_scrolltop_callback',
				'ajax-load-more',
				'alm_prev_post_settings'
			);
		}
	}

	/**
	 * Sanitize license activation.
	 *
	 * @param string $key The new license key.
	 * @return string The license key.
	 */
	function alm_single_post_sanitize_license( $key ) {
		$old = get_option( 'alm_prev_post_license_key' );
		if ( $old && $old !== $key ) {
			delete_option( 'alm_prev_post_license_status' );
		}
		return $key;
	}

	/**
	 * Single Post settings header.
	 *
	 * @return void
	 */
	function alm_single_post_callback() {
		// Translators: 1: opening anchor tag, 2: closing anchor tag.
		echo '<p>' . sprintf( esc_html__( 'Customize your installation of the %1$sSingle Post%2$s add-on.', 'ajax-load-more-single-post' ), '<a href="http://connekthq.com/plugins/ajax-load-more/add-ons/single-post/">', '</a>' ) . '</p>';
	}

	/**
	 * Update the page title
	 *
	 * @return void
	 */
	function alm_single_post_title_callback() {
		$options = get_option( 'alm_settings' );
		if ( ! isset( $options['_alm_prev_post_title'] ) ) {
			$options['_alm_prev_post_title'] = '';
		}

		$html  = '<label for="_alm_prev_post_title">';
		$html .= esc_html__( 'The page title template is used to update the browser title each time a new post is loaded.', 'ajax-load-more-single-post' );
		$html .= '<br/><span>' . esc_html__( 'If empty the page title will NOT be updated.', 'ajax-load-more-single-post' ) . '</span></label>';
		$html .= '<input type="text" class="full" id="_alm_prev_post_title" name="alm_settings[_alm_prev_post_title]" value="' . $options['_alm_prev_post_title'] . '" placeholder="{post-title} - {site-title}" /> ';
		$html .= '<div class="template-tags"><h4>' . esc_html__( 'Template Tags', 'ajax-load-more-single-post' ) . '</h4>';
		$html .= '<ul>';
		$html .= '<li><pre>{post-title}</pre> ' . esc_html__( 'Title of Post', 'ajax-load-more-single-post' ) . '</li>';
		$html .= '<li><pre>{site-title}</pre> ' . esc_html__( 'Site Title', 'ajax-load-more-single-post' ) . '</li>';
		$html .= '<li><pre>{tagline}</pre> ' . esc_html__( 'Site Tagline', 'ajax-load-more-single-post' ) . '</li>';
		$html .= '</ul>';

		echo $html; // phpcs:ignore
	}

	/**
	 * Disable back/fwd button when URLs updated (uses replaceState vs pushState).
	 *
	 * @return void
	 */
	function alm_single_post_browser_controls_callback() {
		$options = get_option( 'alm_settings' );
		if ( ! isset( $options['_alm_prev_post_browser_controls'] ) ) {
			$options['_alm_prev_post_browser_controls'] = '1';
		}

		$html  = '<input type="hidden" name="alm_settings[_alm_prev_post_browser_controls]" value="0" />';
		$html .= '<input type="checkbox" id="_alm_prev_post_browser_controls" name="alm_settings[_alm_prev_post_browser_controls]" value="1"' . ( ( $options['_alm_prev_post_browser_controls'] ) ? ' checked="checked"' : '' ) . ' />';
		$html .= '<label for="_alm_prev_post_browser_controls">' . esc_html__( 'Enable Fwd/Back Browser Buttons.', 'ajax-load-more-single-post' );
		$html .= '<span>' . esc_html__( 'Allow users to navigate Ajax generated content using the back and forward browser buttons.', 'ajax-load-more-single-post' ) . '</span>';
		$html .= '</label>';

		echo $html; // phpcs:ignore
	}

	/**
	 * Set the scrolltop value
	 *
	 * @return void
	 */
	function alm_single_post_scrolltop_callback() {
		$options = get_option( 'alm_settings' );
		if ( ! isset( $options['_alm_prev_post_scrolltop'] ) ) {
			$options['_alm_prev_post_scrolltop'] = '30';
		}

		$html  = '<label for="alm_settings[_alm_prev_post_scrolltop]">';
		$html .= esc_html__( 'The position of the window (in px) when scrolling to a post.', 'ajax-load-more-single-post' );
		$html .= '<br/><span>' . esc_html__( 'This value is used as the trigger for URL updates.', 'ajax-load-more-single-post' ) . '</span>';
		$html .= '</label>';
		$html .= '<input type="number" class="sm" id="alm_settings[_alm_prev_post_scrolltop]" name="alm_settings[_alm_prev_post_scrolltop]" step="1" min="0" value="' . $options['_alm_prev_post_scrolltop'] . '" placeholder="30" /> ';

		echo $html; // phpcs:ignore
	}

	/**
	 * The main function responsible for returning Ajax Load More Single Post.
	 *
	 * @return ALM_Single_Post
	 */
	function alm_single_post() {
		global $alm_single_post;
		if ( ! isset( $alm_single_post ) ) {
			$alm_single_post = new ALM_Single_Post();
		}
		return $alm_single_post;
	}
	alm_single_post();

endif;


/**
 * Software Licensing.
 *
 * @return void
 */
function alm_single_post_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_prev_post_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			[
				'version' => ALM_PREV_POST_VERSION,
				'license' => $license_key,
				'item_id' => ALM_PREV_POST_ITEM_NAME,
				'author'  => 'Darren Cooney',
			]
		);
	}
}
add_action( 'admin_init', 'alm_single_post_updater', 0 );
