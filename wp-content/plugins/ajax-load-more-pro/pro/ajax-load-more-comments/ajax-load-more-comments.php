<?php
/**
 * Plugin Name: Ajax Load More: Comments
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/comments/
 * Description: Ajax Load More add-on for infinite scrolling blog comments.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: https://connekthq.com
 * Version: 1.2.4
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 * Requires Plugins: ajax-load-more
 *
 * @package ALM_Comments
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALM_COMMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_COMMENTS_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_COMMENTS_VERSION', '1.2.4' );
define( 'ALM_COMMENTS_RELEASE', 'June 9, 2025' );

$GLOBALS['alm_comment_repeater']      = '';
$GLOBALS['alm_comment_repeater_type'] = '';

if ( ! class_exists( 'ALM_Comments' ) ) :

	/**
	 * Initiate the class.
	 */
	class ALM_Comments {

		/**
		 * Set up contructors.
		 */
		public function __construct() {
			add_action( 'alm_comments_installed', [ &$this, 'alm_comments_installed' ] );
			add_action( 'wp_ajax_alm_comments', [ &$this, 'alm_comments_query' ] );
			add_action( 'wp_ajax_nopriv_alm_comments', [ &$this, 'alm_comments_query' ] );
			add_filter( 'alm_comments_shortcode', [ &$this, 'alm_comments_shortcode' ], 10, 7 );
			add_filter( 'alm_comments_preloaded', [ &$this, 'alm_comments_preloaded' ], 10, 1 );
			add_action( 'alm_comments_settings', [ &$this, 'alm_comments_settings' ] );
		}

		/**
		 * Preload comments if preloaded is true in alm shortcode.
		 *
		 * @param  array $args The query arguments.
		 * @return string
		 * @since 1.1
		 */
		public function alm_comments_preloaded( $args ) {
			global $post;

			$comments_callback                    = isset( $args['comments_callback'] ) ? $args['comments_callback'] : '';
			$comments_template                    = isset( $args['comments_template'] ) ? $args['comments_template'] : 'none';
			$GLOBALS['alm_comment_repeater']      = $comments_template;
			$comments_template_type               = preg_split( '/(?=\d)/', $comments_template, 2 );
			$GLOBALS['alm_comment_repeater_type'] = $comments_template_type[0];

			$comments_post_id  = isset( $args['comments_post_id'] ) && $args['comments_post_id'] ? $args['comments_post_id'] : $post->ID;
			$comments_per_page = isset( $args['comments_per_page'] ) ? $args['comments_per_page'] : '5';
			$comments_type     = isset( $args['comments_type'] ) ? $args['comments_type'] : 'comment';
			$comments_style    = isset( $args['comments_style'] ) ? $args['comments_style'] : 'ul';
			$orderby           = isset( $args['orderby'] ) ? $args['orderby'] : 'date';
			$order             = isset( $args['order'] ) ? $args['order'] : 'DESC';
			$offset            = isset( $args['offset'] ) ? $args['offset'] : 0;

			// If callback is empty, look for a template selected.
			if ( $comments_callback === '' ) {
				$comments_callback = $comments_template_type !== 'none' ? 'alm_comment' : $comments_callback;
			}

			$alm_comments_args  = [
				'status'  => 'approve',
				'post_id' => $comments_post_id,
				'number'  => 999,
				'offset'  => $offset,
				/**
				 * Offset does not work unless we limit the amount using 'number'.
				 * Also, offsetting this query results in the newest comments (nested or top level) being removed and not necessarily the correct one
				 */
				'orderby' => $orderby,
				'order'   => $order,
			];
			$alm_comments_query = get_comments( $alm_comments_args );
			ob_start();
			wp_list_comments(
				[
					'style'             => $comments_style,
					'page'              => 1,
					'per_page'          => intval( $comments_per_page ),
					'callback'          => $comments_callback,
					'type'              => $comments_type,
					'reverse_top_level' => false,
				],
				$alm_comments_query
			);
			$data = ob_get_clean();
			return $data;
		}

		/**
		 * Query comments, send results via ajax as JSON.
		 *
		 * @since 1.0
		 */
		public function alm_comments_query() {
			$form_data = filter_input_array( INPUT_GET );
			if ( ! isset( $form_data ) ) {
				return;
			}

			$query_type    = isset( $form_data['query_type'] ) ? $form_data['query_type'] : 'standard';
			$data          = isset( $form_data['comments'] ) ? $form_data['comments'] : '';
			$offset        = isset( $form_data['offset'] ) ? $form_data['offset'] : 0;
			$orderby       = isset( $form_data['orderby'] ) ? $form_data['orderby'] : 'date';
			$order         = isset( $form_data['order'] ) ? $form_data['order'] : 'DESC';
			$canonical_url = isset( $form_data['canonical_url'] ) ? $form_data['canonical_url'] : $_SERVER['HTTP_REFERER'];
			$preloaded     = isset( $form_data['preloaded'] ) ? $form_data['preloaded'] : false;

			// Cache Add-on.
			$cache_id        = isset( $form_data['cache_id'] ) ? $form_data['cache_id'] : '';
			$cache_slug      = isset( $form_data['cache_slug'] ) && $form_data['cache_slug'] ? $form_data['cache_slug'] : '';
			$cache_logged_in = isset( $form_data['cache_logged_in'] ) ? $form_data['cache_logged_in'] : false;
			$do_create_cache = $cache_logged_in === 'true' && is_user_logged_in() ? false : true;

			if ( $data ) {
				$comments                             = isset( $data['comments'] ) ? $data['comments'] : false;
				$comments_callback                    = isset( $data['callback'] ) ? $data['callback'] : '';
				$comments_template                    = isset( $data['template'] ) ? $data['template'] : 'none';
				$GLOBALS['alm_comment_repeater']      = $comments_template;
				$comments_template_type               = preg_split( '/(?=\d)/', $comments_template, 2 );
				$comments_template_type               = $comments_template_type[0];
				$GLOBALS['alm_comment_repeater_type'] = $comments_template_type;
				$page                                 = isset( $form_data['page'] ) ? $form_data['page'] : 0;

				$comments_post_id  = isset( $data['post_id'] ) ? $data['post_id'] : 'null';
				$comments_per_page = isset( $data['per_page'] ) ? $data['per_page'] : '5';
				$comments_type     = isset( $data['type'] ) ? $data['type'] : 'comment';
				$comments_style    = isset( $data['style'] ) ? $data['style'] : 'ul';

				// Paging Add-on.
				$paging = isset( $form_data['paging'] ) ? $form_data['paging'] : 'false';

				$page = $paging === 'true' && $preloaded === 'true' ? $page = $page - 1 : $page;

				// If callback is empty, look for a template selected.
				if ( $comments_callback === '' ) {
					$comments_callback = $comments_template_type !== 'none' ? 'alm_comment' : $comments_callback;
				}

				// Preloaded - add a page.
				$page = $preloaded === 'true' ? $page + 1 : $page;

				if ( $comments === 'true' ) {
					$comment_count = 0; // Count the comments.

					$alm_comments_args = [
						'status'  => 'approve',
						'post_id' => $comments_post_id,
						'number'  => 999,
						'offset'  => $offset,
						/**
						 * Offset does not work unless we limit the amount using 'number'.
						 * Also, offsetting this query results in the newest comments (nested or top level) being removed and not necessarily the correct one
						*/
						'orderby' => $orderby,
						'order'   => $order,
					];

					$post_comments = get_comments( $alm_comments_args );

					foreach ( $post_comments as $comment ) {
						// If the comment has no parent, count it!
						// This is becasue wp_list_comments does not count replies.
						$comment_parent = $comment->comment_parent ? $comment->comment_parent : 0;
						if ( $comment_parent === 0 ) {
							++$comment_count;
						}
					}

					if ( $query_type === 'totalposts' ) {
						// Paging add-on.
						wp_send_json( [ 'totalposts' => $comment_count ] );

					} else {
						// Standard ALM.
						ob_start();
						wp_list_comments(
							[
								'style'             => $comments_style,
								'page'              => $page + 1,
								'per_page'          => intval( $comments_per_page ),
								'callback'          => $comments_callback,
								'type'              => $comments_type,
								'reverse_top_level' => false,
							],
							$post_comments
						);
						$comment_data = ob_get_clean();

						if ( $comment_data ) {
							/**
							 * Cache Add-on.
							 * Create the cache file.
							 */
							if ( $cache_id && method_exists( 'ALMCache', 'create_cache_file' ) && $do_create_cache ) {
								ALMCache::create_cache_file( $cache_id, $cache_slug, $canonical_url, $comment_data, intval( $comments_per_page ), $comment_count );
							}

							$return = [
								'html' => $comment_data,
								'meta' => [
									'postcount'  => intval( $comments_per_page ),
									'totalposts' => $comment_count,
								],
							];

						} else {
							$return = [
								'html' => '',
								'meta' => [
									'postcount'  => null,
									'totalposts' => null,
								],
							];
						}
						wp_send_json( $return );
					}
				}
			}
			wp_die();
		}

		/**
		 * Build Comments shortcode params and send back to core ALM.
		 *
		 * @param  string $comments          The value for Comments (true/false).
		 * @param  string $comments_per_page Per page.
		 * @param  string $comments_type     The comment type..
		 * @param  string $comments_style    The list style.
		 * @param  string $comments_template The repeater template.
		 * @param  string $comments_callback The callback function name.
		 * @param  string $comments_post_id  Post ID.
		 * @return string                    The generated HTML data attributes.
		 * @since 1.0
		 */
		public function alm_comments_shortcode( $comments, $comments_per_page, $comments_type, $comments_style, $comments_template, $comments_callback, $comments_post_id ) {
			global $post;

			// Set default post ID.
			$comments_post_id = isset( $comments_post_id ) && $comments_post_id ? $comments_post_id : $post->ID;

			$return  = ' data-comments="' . $comments . '"';
			$return .= ' data-comments_per_page="' . $comments_per_page . '"';
			$return .= ' data-comments_type="' . $comments_type . '"';
			$return .= ' data-comments_style="' . $comments_style . '"';
			$return .= ' data-comments_template="' . $comments_template . '"';
			$return .= ' data-comments_callback="' . $comments_callback . '"';
			$return .= ' data-comments_post_id="' . $comments_post_id . '"';
			return $return;
		}

		/**
		 * An empty function to determine if comments is true.
		 *
		 * @since 1.0
		 */
		public function alm_comments_installed() {
			// Empty function return.
		}

		/**
		 * Create the Comments settings panel.
		 *
		 * @since 1.0
		 */
		public function alm_comments_settings() {
			register_setting(
				'alm_comments_license',
				'alm_comments_license_key',
				'alm_comments_sanitize_license'
			);
		}
	}

	/**
	 * Custom comment styling callback (wp_list_comments()).
	 *
	 * @since 1.0
	 */
	function alm_comment( $comment, $args, $depth ) {
		global $comment;
		$template      = $GLOBALS['alm_comment_repeater'];
		$template_type = $GLOBALS['alm_comment_repeater_type'];

		if ( strpos( $template, '.php' ) || strpos( $template, '.html' ) && has_filter( 'alm_get_theme_repeater' ) ) {
			// Theme Repeater.
			do_action( 'alm_get_theme_repeater', $template, 1, 1, 1, 1, $args );
		} else {
			// Standard Repeater.
			include alm_get_current_repeater( $template, $template_type );
		}
	}

	/**
	 * Sanitize our license activation
	 *
	 * @param string $new The API Key.
	 * @return string The API key as a string.
	 * @since 1.0.0
	 */
	function alm_comments_sanitize_license( $new ) {
		$old = get_option( 'alm_comments_license_key' );
		if ( $old && $old !== $new ) {
			delete_option( 'alm_comments_license_status' );
		}
		return $new;
	}

	/**
	 * The main function responsible for returning Ajax Load More Comments.
	 *
	 * @since 1.0
	 */
	function alm_comments() {
		global $alm_comments;
		if ( ! isset( $alm_comments ) ) {
			$alm_comments = new ALM_Comments();
		}
		return $alm_comments;
	}
	alm_comments();

endif;

/**
 * Software Licensing
 */
function alm_comments_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_comments_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			[
				'version' => ALM_COMMENTS_VERSION,
				'license' => $license_key,
				'item_id' => ALM_COMMENTS_ITEM_NAME,
				'author'  => 'Darren Cooney',
			]
		);
	}
}
add_action( 'admin_init', 'alm_comments_plugin_updater', 0 );
