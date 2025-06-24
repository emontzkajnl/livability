<?php
/**
 * ALm Facet class.
 *
 * @package ALMFilters
 */


class ALM_Facets {

	/**
	 * @var ALM_Facet_Process
	 */
	protected $process_facet;

	/**
	 * ALM Admin Notices.
	 *
	 * @var array
	 */
	public $notices = [];

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'rebuild' ] );
		add_action( 'admin_init', [ $this, 'cancelled' ] );
		add_action( 'alm_facets_build_index', [ $this, 'index' ] );

		// Start the background process.
		$this->process_facet = new ALM_Facet_Process();

		// Notices.
		if ( class_exists( 'ALM_Filters_Notices' ) && is_admin() ) {
			$this->notices = new ALM_Filters_Notices();
		}
	}

	/**
	 * Indexing cancelled via querystring.
	 *
	 * @see wp-admin/admin.php?page=ajax-load-more-filters&status=cancel
	 */
	public function cancelled() {
		$params = filter_input_array( INPUT_GET ); // phpcs:ignore
		if ( $this->process_facet->is_processing() && isset( $params['status'] ) && $params['status'] === 'cancel' ) {
			$this->process_facet->cancel();

			$queued = self::get_all_facets( 'queued' ) ?? []; // Get all queued options.
			foreach ( $queued as $item ) {
				delete_option( $item ); // Delete the queued option.
			}

			// Get all status options.
			$status = self::get_all_facets( 'status' ) ?? [];
			foreach ( $status as $item ) {
				$option = unserialize( get_option( $item ) );
				if ( $option && isset( $option['status'] ) && $option['status'] !== 'completed' ) {
					$option['status'] = 'complete'; // Update the status to complete.
					update_option( $item, serialize( $option ), false ); // Update the option.
				}
			}

			$msg = __( 'Facet indexing has been cancelled. Some indices may be paritally built and will require a rebuild.', 'ajax-load-more-filters' );

			$filter_id = isset( $params['id'] ) ? $params['id'] : false;
			if ( $filter_id ) {
				/* translators: %s: Filter Admin link */
				$msg .= ' ' . sprintf( __( '<a href="%1$s">Rebuild Cancelled Index</a>', 'ajax-load-more-filters' ), ALM_FILTERS_BASE_URL . '&filter=' . $filter_id . '&rebuild_index' );
			}

			// Display admin notice.
			$this->notices->alm_add_admin_notice( $msg, 'warning', '', false );
		}
	}

	/**
	 * Rebuild a facet via querystring.
	 *
	 * @see wp-admin/admin.php?page=ajax-load-more-filters&rebuild_facet=my_facet
	 */
	public function rebuild() {
		$params = filter_input_array( INPUT_GET ); // phpcs:ignore
		if ( ! isset( $params['rebuild_facet_index'] ) ) {
			return; // Exit if missing querystring.
		}
		$filter_id = $params['rebuild_facet_index'];
		$filter    = ALMFIlters::alm_filters_get_filter_by_id( $filter_id, true );

		// Confirm filter exist.
		if ( $filter ) {
			// Rebuild facet index.
			do_action( 'alm_facets_build_index', $filter );

			/* translators: %s: Filter ID & Filter Admin link */
			$msg = sprintf( __( 'A rebuild of the <a href="%2$s">%1$s</a> index has been triggered successfully.', 'ajax-load-more-filters' ), $filter_id, ALM_FILTERS_BASE_URL . '&filter=' . $filter_id ); // phpcs:ignore
			$this->notices->alm_add_admin_notice( $msg, 'success' );

		} else {
			$msg = __( 'Filter not found and facets could not be rebuilt.', 'ajax-load-more-filters' );
			$this->notices->alm_add_admin_notice( $msg, 'error' );
		}
	}

	/**
	 * Initiate the facet index build process.
	 *
	 * @param array $filter The filter array.
	 * @return void
	 */
	public function index( $filter = [] ) {
		if ( ! $filter ) {
			return; // Exit if filter is missing.
		}

		// Check if background indexing is enabled.
		if ( ! $this::do_background_index() ) {
			$this->save_index( $filter );
			return;
		}

		$filter_id = isset( $filter['id'] ) ? trim( $filter['id'] ) : false;

		if ( ! $filter_id ) {
			// Exit if missing Filter ID.
			$this->notices->alm_add_admin_notice( __( 'Missing Ajax Load More Filter ID.', 'ajax-load-more-filters' ), 'error' );
			return false;
		}

		// Run an initial query to get the total number of pages.
		$data = $this->get_index( $filter, 1, true );

		// Get the total number of found posts.
		$found_posts = $data && isset( $data['found_posts'] ) ? $data['found_posts'] : 0;

		// Skip background processing if the total number of posts is less than the posts per page.
		if ( (int) $found_posts <= (int) apply_filters( 'alm_filters_facets_posts_per_page', 100 ) ) {
			$this->save_index( $filter );
			return;
		}

		// Build the data queue.
		$pages = $this->build_queue( $data, $filter_id, $filter );

		// Check if background processing is already running.
		if ( $this->process_facet->is_processing() ) {

			/* translators: %s: Filter ID & Filter Admin link */
			$msg = sprintf( __( 'Indexer is already running! The <a href="%2$s">%1$s</a> index be built once the previous processes have completed.', 'ajax-load-more-filters' ), $filter_id, ALM_FILTERS_BASE_URL . '&filter=' . $filter_id );
			$this->notices->alm_add_admin_notice( $msg, 'warning', 'indexer-queued' );

			// Pause the process.
			$this->process_facet->pause();

			// Get the batch data in the queue.
			$batches = $this->process_facet->get_batches();

			// Set facet queued status.
			update_option( self::get_facet_option_name( $filter_id, 'queued' ), 'true', false );

			if ( $batches ) {
				foreach ( $batches as $batch ) {
					// Check if filter ID is already in the queue. If so, delete it and add it to the end.
					if ( $filter_id === $batch->filter_id ) {
						$this->process_facet->delete( $batch->key );

						// Update the facet status.
						update_option(
							self::get_facet_option_name( $filter_id, 'status' ),
							serialize(
								[
									'status' => 'queued',
								]
							),
							false
						);

						break; // Exit loop.
					}
				}
			}

			// Resume the process.
			$this->process_facet->resume();
		}

		// Add pages to the indexing queue.
		foreach ( $pages as $page ) {
			$this->process_facet->push_to_queue( $page );
		}

		// Delete facet cache or transients.
		self::delete_cache( $filter_id );

		// Start the background process.
		$this->process_facet->save()->dispatch();
	}

	/**
	 * Build the data queue for the facet index process.
	 *
	 * @param array  $data   The data/index as array.
	 * @param string $id     The filter ID.
	 * @param array  $filter The filter array.
	 * @return array         Array of pages.
	 */
	public function build_queue( $data, $id, $filter ) {
		$items = [];
		if ( $data && isset( $data['index'] ) && isset( $data['pages'] ) ) {
			$pages = (int) $data['pages'];
			for ( $i = 1; $i <= $pages; $i++ ) {
				$items[] = [
					'id'     => $id,
					'filter' => $filter,
					'paged'  => $i,
					'pages'  => $pages,
				];
			}
		}
		return $items;
	}

	/**
	 * Get and return facet index data via WP Query.
	 *
	 * Note: The index will contain an array of post IDs and arrays of taxonomy and meta values.
	 *       The index is then compared to the facets when they are returned from the database.
	 *       The facet index is stored in the options tables. e.g. `alm_facet_{id}`
	 *
	 * @param  array $filter Array containing the filter options.
	 * @param  int   $paged  The current page number.
	 * @param  bool  $test   Is this a test run. Test run is used to return the total page count.
	 * @param  bool  $all    Run a query for all posts.
	 * @return array         An array containing facet index information.
	 */
	public static function get_index( $filter, $paged = 1, $test = false, $all = false ) {
		$index          = [];
		$post_types     = isset( $filter['facets_post_types'] ) ? $filter['facets_post_types'] : [ 'post' ];
		$posts_per_page = ! $all ? apply_filters( 'alm_filters_facets_posts_per_page', 100 ) : -1;

		$args = [
			'post_type'              => $post_types,
			'posts_per_page'         => $posts_per_page,
			'post_status'            => [ 'publish' ],
			'paged'                  => $paged,
			'ignore_sticky_posts'    => true, // Ignore sticky posts.
			'update_post_meta_cache' => false, // Don't update post meta cache.
			'update_post_term_cache' => false, // Don't update post term cache.
			'cache_results'          => false, // Don't cache results.
			'fields'                 => 'ids', // Only return post IDs.
		];

		if ( in_array( 'attachment', $post_types, true ) ) {
			$args['post_status'] = [ 'publish', 'inherit' ]; // Set `inherit` post_status for attachments.
		}

		/**
		 * Facets Hook to modify index query args.
		 */
		$args = apply_filters( 'alm_filters_facets_index_' . $filter['id'] . '_args', $args );

		// Query posts.
		$query = new WP_Query( $args );

		while ( $query->have_posts() ) :
			$query->the_post();
			$index[] = alm_filters_build_post_index( get_the_ID(), alm_filters_pluck_facet_keys( $filter ) );
		endwhile;
		wp_reset_postdata();

		$max_pages = $query->max_num_pages;

		// Log error if no posts are found.
		if ( $test && $max_pages === 0 ) {
			error_log( __( 'Ajax Load More: Filters -> No posts found, the facet index contains zero indexable posts.', 'ajax-load-more-filters' ) );
		}

		// Return the index and total number of pages.
		return [
			'index'       => $index,
			'pages'       => $max_pages === 0 ? 1 : $max_pages,
			'found_posts' => $query->found_posts,
		];
	}

	/**
	 * Save the entire facet index directly without background processing functionality.
	 *
	 * Note: This function is called when background processing is diasbled via hook.
	 *       Query ALL posts and save the index in a single query.
	 *
	 * @param  array $filter An array containing the filter options.
	 * @return void
	 */
	public function save_index( $filter ) {
		if ( ! $filter || ! isset( $filter['id'] ) ) {
			return;
		}

		$filter_id = trim( $filter['id'] );

		// Get the full post index.
		$data  = $this->get_index( $filter, 1, false, true );
		$index = $data && isset( $data['index'] ) ? $data['index'] : [];

		// Update the facet index.
		update_option( self::get_facet_option_name( $filter_id ), serialize( $index ) );

		// Update the facet status.
		update_option(
			self::get_facet_option_name( $filter_id, 'status' ),
			serialize(
				[
					'status'      => 'complete',
					'paged'       => 1,
					'pages'       => 1,
					'found_posts' => count( $index ),
				]
			),
			false
		);

		// Delete queued status.
		self::delete_queued_status( $filter_id );

		// Delete related transients.
		self::delete_cache( $filter_id );
	}

	/**
	 * Update an existing facet index after a post save.
	 *
	 * @param array  $filter      The filter options.
	 * @param string $post_id     The post ID.
	 * @param string $post_status The post status.
	 * @return void
	 */
	public static function update_index( $filter, $post_id, $post_status ) {
		$filter_id = $filter['id'];
		$index     = ALMFilters::get_facet_index_by_id( $filter_id );
		if ( ! $index ) {
			// No facet index found, create one.
			do_action( 'alm_facets_build_index', $filter );
			return;
		}

		// Delete facet cache or transients.
		self::delete_cache( $filter_id );

		$ids         = wp_list_pluck( $index, 'id' ); // Pluck IDs from the facet.
		$key         = array_search( $post_id, $ids ); // Find the post ID in thr array.
		$option_name = self::get_facet_option_name( $filter_id );

		if ( $key !== false ) {
			// Remove the post from the index if in trash.
			if ( $post_status === 'trash' ) {
				unset( $index[ $key ] );
				update_option( $option_name, serialize( $index ) );
				return;
			}

			$post_types    = isset( $filter['facets_post_types'] ) ? $filter['facets_post_types'] : [ 'post' ];
			$is_attachment = in_array( 'attachment', $post_types, true );

			// Update the facet index by the array key.
			if ( $post_status === 'publish' || ( $is_attachment && $post_status === 'inherit' ) ) {
				$index[ $key ] = alm_filters_build_post_index( $post_id, alm_filters_pluck_facet_keys( $filter ) );
				update_option( $option_name, serialize( $index ), false, false );
				return;
			}
		} else {
			// Post not found, add item to facet index.

			// Confirm index is an array.
			if ( is_array( $index ) ) {
				// Get individual post index.
				$data = alm_filters_build_post_index( $post_id, alm_filters_pluck_facet_keys( $filter ) );

				// Add post to index array.
				$index[] = $data;

				// Update the facet index.
				update_option( $option_name, serialize( $index ), false );
			}
		}
	}

	/**
	 * Delete facet cache/transients by filter ID.
	 *
	 * @param string $filter_id The filter ID.
	 * @return void
	 */
	public static function delete_cache( $filter_id ) {
		if ( ! $filter_id ) {
			return;
		}

		if ( apply_filters( 'alm_filters_facets_transient_cache', true ) ) {
			// Transient Cache.
			global $wpdb;
			$default = '_transient_';
			$prefix  = esc_sql( $default . ALM_FILTERS_FACET_PREFIX . $filter_id ); // e.g. _transient_alm_facet_filter_actors.
			$options = $wpdb->options;
			$t       = esc_sql( "$prefix%" );

			// Get all transients that match.
			$sql        = $wpdb->prepare( "SELECT option_name FROM $options WHERE option_name LIKE '%s'", $t ); // phpcs:ignore
			$transients = $wpdb->get_col( $sql ); // phpcs:ignore

			if ( $transients ) {
				foreach ( $transients as $transient ) {
					$name = str_replace( $default, '', $transient ); // Replace `_transient_` from returned transient.
					delete_transient( $name ); // Delete the transient.
				}
			}
		} else {
			// WP Cache.
			$group_name = ALM_FACETS_CACHE_PREFIX . $filter_id;
			wp_cache_flush_group( $group_name );
			return;

		}
	}

	/**
	 * Check if background processing for facet indexing is enabled.
	 *
	 * @return bool
	 */
	public static function do_background_index() {
		$options = get_option( 'alm_settings' ) ?? [];
		$name    = '_alm_filters_background_indexing';

		// Return false if background indexing is disabled via hook.
		if ( ! apply_filters( 'alm_filters_facets_background_indexing', true ) ) {
			return false;
		}

		// Return false if admin option is unchecked.
		$background = isset( $options[ $name ] ) && $options[ $name ] === '1' ? true : false;
		if ( ! $background ) {
			return false;
		}

		return true; // Return true, background indexing is enabled.
	}

	/**
	 * Check is basic authentication is enabled.
	 *
	 * @return int Status code.
	 */
	public static function check_basic_auth() {
		$request = wp_safe_remote_get(
			get_site_url(),
			[
				'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
			]
		);
		return wp_remote_retrieve_response_code( $request );
	}

	/**
	 * Get the facet option names for the options table.
	 *
	 * @param string $filter_id The filter ID.
	 * @return array            The facet status.
	 */
	public static function get_facet_option_name( $filter_id, $append = '' ) {
		if ( ! $filter_id ) {
			return '';
		}
		$name = $append ? ALM_FILTERS_FACET_PREFIX . $append . '_' : ALM_FILTERS_FACET_PREFIX;
		return $name . $filter_id;
	}

	/**
	 * Delete the facet index and facet status from WP options table.
	 *
	 * @param string $filter_id The filter ID.
	 */
	public static function delete_facet_options( $filter_id ) {
		delete_option( self::get_facet_option_name( $filter_id ) );
		delete_option( self::get_facet_option_name( $filter_id, 'status' ) );
		delete_option( self::get_facet_option_name( $filter_id, 'queued' ) );
	}

	/**
	 * Delete queued status option from options table.
	 *
	 * @param string $filter_id The filter ID.
	 */
	public static function delete_queued_status( $filter_id ) {
		delete_option( self::get_facet_option_name( $filter_id, 'queued' ) );
	}

	/**
	 * Set the default facet status to complete for facets that don't currently have a status.
	 *
	 * @param string $filter_id The filter ID.
	 */
	public static function set_default_facet_status( $filter_id = '' ) {
		if ( ! $filter_id ) {
			return; // Exit if missing filter ID.
		}

		$status = unserialize( get_option( self::get_facet_option_name( $filter_id, 'status' ) ) );
		if ( ! $status ) {
			// Update the facet status.
			update_option(
				self::get_facet_option_name( $filter_id, 'status' ),
				serialize(
					[
						'status' => 'complete',
					]
				),
				false
			);
		}
	}

	/**
	 * Get all facet filters/options from the wp_options table.
	 *
	 * @param string $pluck Optional. The filter name to pluck.
	 * @return array        An array of option names.
	 */
	public static function get_all_facets( $pluck = '' ) {
		global $wpdb;
		$pluck   = $pluck ? $pluck . '_' : ''; // Add underscore to pluck.
		$prefix  = esc_sql( ALM_FILTERS_FACET_PREFIX . $pluck );
		$options = $wpdb->options;
		$t       = esc_sql( "$prefix%" );
		$sql     = $wpdb->prepare( "SELECT option_name FROM $options WHERE option_name LIKE '%s'", $t ); //phpcs:ignore
		$filters  = $wpdb->get_col( $sql ); //phpcs:ignore

		if ( ! $filters ) {
			return []; // Return empty array if no filters found.
		}

		if ( ! $pluck ) {
			$modified = [];
			foreach ( $filters as $facet ) {
				// Remove all `status_` and `queued_` facets.
				if ( strpos( $facet, $prefix . 'status_' ) === false && strpos( $facet, $prefix . 'queued_' ) === false ) {
					$modified[] = $facet;
				}
			}
			$filters = $modified;
		}

		return $filters;
	}
}

new ALM_Facets();
