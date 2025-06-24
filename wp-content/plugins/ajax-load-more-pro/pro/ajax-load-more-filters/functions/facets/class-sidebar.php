<?php
/**
 * Facet Sidebar class.
 *
 * @package ALMFilters
 */

class ALM_Facet_Sidebar {
	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'alm_facet_sidebar_status', [ $this, 'alm_facet_sidebar_status' ] );
	}

	/**
	 * Display the facet status in the sidebar.
	 *
	 * @param string $filter_id The filter ID.
	 * @return void
	 */
	public function alm_facet_sidebar_status( $filter_id ) {
		if ( ! $filter_id ) {
			return;
		}
		// Get index status from options table.
		$status       = unserialize( get_option( ALM_Facets::get_facet_option_name( $filter_id, 'status' ) ) );
		$is_completed = $status && isset( $status['status'] ) && $status['status'] === 'complete' ? true : false;
		?>
		<div class="cta" id="alm-facet-status" data-state="loading" data-filter-id="<?php echo esc_attr( $filter_id ); ?>" aria-live="polite" aria-atomic="true">
			<header class="cta--header">
				<h3><?php esc_html_e( 'Facet Index Status', 'ajax-load-more-filters' ); ?></h3>
				<?php
				if ( $is_completed && function_exists( 'alm_status_icon' ) ) {
					// Status icons.
					// echo wp_kses_post( alm_status_icon( 'success', '', __( 'Facet index ready!', 'ajax-load-more-filters' ) ) );
				}
				?>
			</header>
			<div class="cta-inner">
				<div>
					<?php if ( $status ) { ?>
					<div id="alm-facet-status--loader"></div>
					<div id="alm-facet-status--content">
						<div class="alm-facet-building">
							<p><?php esc_attr_e( 'Fetching Index Data...', 'ajax-load-more-filters' ); ?></p>
						</div>
					</div>
						<?php
					} else {
						echo '<div>' . self::get_facet_index_stats( $filter_id ) . '</div>';
					}
					?>
				</div>
			</div>
			<div class="major-publishing-actions space-between">
				<a id="alm-rebuild-facet-index" class="button" href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ); ?>&filter=<?php echo esc_attr( $filter_id ); ?>&rebuild_index">
					<?php esc_attr_e( 'Rebuild Index', 'ajax-load-more-filters' ); ?>
				</a>
				<a id="alm-cancel-indexing" href="<?php echo esc_url( ALM_FILTERS_BASE_URL ); ?>&status=cancel&id=<?php echo esc_attr( $filter_id ); ?>" class="button-link" style="display: none;">
					<?php esc_attr_e( 'Cancel', 'ajax-load-more-filters' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Display queued facet index message.
	 * Note: This action is called from the facets/status API endpoint and displayed in sidebar.
	 *
	 * @return void
	 */
	public static function facet_index_queued() {
		ob_start();
		?>
		<p style="margin-bottom: 5px;"><strong><?php esc_attr_e( 'Indexing Queued', 'ajax-load-mire-filters' ); ?></strong></p>
		<p><?php echo wp_kses_post( __( 'Indexing of this filter will begin once the current process has completed.', 'ajax-load-more-filters' ) ); ?></p>
		<?php
		return ob_get_clean();
	}

	/**
	 * Display facet index loading/processing.
	 * Note: This action is called from the facet-status endpoint and displayed in sidebar.
	 *
	 * @param array $status The facet data array.
	 * @return void
	 */
	public static function facet_index_processing( $data ) {
		ob_start();
		$percentage  = round( $data['paged'] / $data['pages'] * 100 ) . '%';
		$found_posts = $data['found_posts'];
		$indexed     = round( $found_posts / $data['pages'] * $data['paged'] );
		?>
		<div class="alm-facet-building">
			<p>
				<?php
				/* translators: %1$s is the percentage value, %2$s is the total posts indexed, %3$s is the indexed percentage. */
				printf( wp_kses_post( __( '<span>Total Posts:</span> <span class="alm-pre">%2$s/%1$s</span>', 'ajax-load-more-filters' ) ), $found_posts, $indexed, $percentage );
				?>
			</p>
			<p>
				<?php
				/* translators: %1$s is the percentage value. */
				printf( wp_kses_post( __( '<span>Indexing Status:</span> <span class="alm-pre">%1$s</span>', 'ajax-load-more-filters' ) ), $percentage );
				?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Display facet index stats.
	 * Note: This action is called from the facets/status API endpoint and displayed in sidebar.
	 *
	 * @param int $filter_id The filter ID.
	 * @return void
	 */
	public static function get_facet_index_stats( $filter_id ) {
		ob_start();
		$index = ALMFilters::get_facet_index_by_id( $filter_id );
		if ( $index ) {
			$count              = count( $index );
			$indexed_post_types = wp_list_pluck( $index, 'post_type' );
			$post_types         = $indexed_post_types ? array_count_values( $indexed_post_types ) : [];
			if ( $post_types ) {
				arsort( $post_types ); // Sort array by number value.
				/* translators: %1$s is the filter ID, %2$s is the index count */
				echo '<p>' . sprintf( wp_kses_post( __( 'The <span class="alm-pre">%1$s</span> index contains <span class="alm-pre">%2$s</span> posts for the following post types:', 'ajax-load-more-filters' ) ), $filter_id, $count ) . '</p>';
				echo '<div class="alm-stats">';
				foreach ( $post_types as $post_type => $count ) {
					$post_type_object = get_post_type_object( $post_type );
					if ( ! $post_type_object ) {
						continue;
					}

					/* translators: %1$s is count, %2$s is the post type label */
					$title = sprintf( wp_kses_post( __( '%1$s %2$s in index.', 'ajax-load-more-filters' ) ), $count, $post_type_object->label );
					echo '<div title="' . wp_kses_post( $title ) . '">';
					echo '<div class="alm-stats--stat">' . esc_attr( $count ) . '</div>';
					echo '<div>' . esc_attr( $post_type_object->label ) . '</div>';
					echo '</div>';
				}
				echo '</div>';
			}
		} else {
			?>
			<p style="margin-bottom: 10px;">
				<?php esc_html_e( 'There could be a problem. The facet index contains zero indexed posts or is failing to build.', 'ajax-load-more-filters' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Try saving/updating this filter to restart the indexing process.', 'ajax-load-more-filters' ); ?>
			</p>
			<?php
		}
		return ob_get_clean();
	}
}

new ALM_Facet_Sidebar();
