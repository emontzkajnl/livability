<?php
/**
 * Admin function and hooks.
 *
 * @package ALMFilters
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function alm_filters_render_whats_this_button() {
	?>
	<a class="alm-instructions--toggle" href="javascript: void(0);" v-on:click="toggleInstructions($event)">
		<?php _e( 'What\'s This?', 'ajax-load-more-filters' ); ?>
	</a>
	<?php
}

/**
 * This function will list all filters.
 *
 * @since 1.5
 * @param string $pos The position of the listing.
 */
function alm_list_all_filters( $pos = 'sidebar' ) {
	$filters = ALMFilters::alm_get_all_filters();
	$params  = filter_input_array( INPUT_GET );
	$order   = isset( $params['order'] ) ? $params['order'] : 'desc';
	$orderby = isset( $params['orderby'] ) ? $params['orderby'] : 'id';
	$results = [];

	if ( $filters ) :
		// Loop each filter.
		foreach ( $filters as $filter ) {
			$filter = preg_replace( '/' . esc_sql( ALM_FILTERS_PREFIX ) . '/', '', $filter, 1 );
			$data   = unserialize( get_option( ALM_FILTERS_PREFIX . $filter ) );

			if ( ! $data ) {
				continue;
			}

			$results[] = [
				'id'     => $data['id'],
				'date'   => $data['date_created'],
				'count'  => isset( $data['filters'] ) ? count( $data['filters'] ) : 0,
				'facets' => isset( $data['facets'] ) ? $data['facets'] : false,
			];

			// Order the results.
			if ( $orderby && version_compare( PHP_VERSION, '5.6', '>=' ) ) {
				if ( $order === 'asc' ) {
					usort(
						$results,
						function ( $a, $b ) use ( $orderby ) {
							return $b[ $orderby ] <=> $a[ $orderby ]; // phpcs:ignore
						}
					);
				} else {
					usort(
						$results,
						function ( $a, $b ) use ( $orderby ) {
							return $a[ $orderby ] <=> $b[ $orderby ]; // phpcs:ignore
						}
					);
				}
			}
		}
		?>

		<table class="wp-list-table widefat fixed striped table-view-list">
			<thead>
				<tr>
					<th class="title column-title">
						<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ); ?>&orderby=id&order=<?php echo strtolower( $order ) === 'asc' ? 'desc' : 'asc'; ?>">
							<?php esc_attr_e( 'Filter ID', 'ajax-load-more-filters' ); ?>
							<?php alm_filters_list_arrow( 'id', $orderby, $order ); ?>
						</a>
					</th>
					<th class="text-center hide-mobile">
						<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ); ?>&orderby=count&order=<?php echo strtolower( $order ) === 'asc' ? 'desc' : 'asc'; ?>">
							<?php esc_attr_e( 'Count', 'ajax-load-more-filters' ); ?>
							<?php alm_filters_list_arrow( 'count', $orderby, $order ); ?>
						</a>
					</th>
					<th class="text-center hide-mobile">
						<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ); ?>&orderby=facets&order=<?php echo strtolower( $order ) === 'asc' ? 'desc' : 'asc'; ?>">
							<?php esc_attr_e( 'Facets', 'ajax-load-more-filters' ); ?>
							<?php alm_filters_list_arrow( 'facets', $orderby, $order ); ?>
						</a>
					</th>
					<th class="text-center hide-mobile">
						<?php esc_attr_e( 'Index', 'ajax-load-more-filters' ); ?>
					</th>
					<th>
						<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ); ?>&orderby=date&order=<?php echo strtolower( $order ) === 'asc' ? 'desc' : 'asc'; ?>">
							<?php esc_attr_e( 'Date', 'ajax-load-more-filters' ); ?>
							<?php alm_filters_list_arrow( 'date', $orderby, $order ); ?>
						</a>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $results as $filter ) :
					$id = $filter['id'];
					if ( $id ) {
						$count = (int) $filter['count'];
						?>
					<tr>
						<td class="title column-title">
							<strong>
								<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ) . '&filter=' . esc_attr( $id ); ?>" aria-label="<?php echo esc_attr( $id ); ?>">
									<?php echo esc_attr( $id ); ?>
								</a>
							</strong>
							<div class="row-actions">
								<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ) . '&filter=' . esc_attr( $id ); ?>" aria-label="Edit <?php echo esc_attr( $id ); ?>">
									<?php esc_attr_e( 'Edit', 'ajax-load-more-filters' ); ?>
								</a>
								<span>|</span>
								<a href="<?php echo esc_attr( get_home_url() ) . '?alm_filters_preview=' . esc_attr( $id ); ?>" target="_blank" aria-label="Preview <?php echo esc_attr( $id ); ?>">
									<?php esc_attr_e( 'Preview', 'ajax-load-more-filters' ); ?>
								</a>
								<span>|</span>
								<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ) . '&duplicate_filter=' . esc_attr( $id ); ?>" aria-label="Duplicate <?php echo esc_attr( $id ); ?>" class="duplicate-filter" data-id="<?php echo esc_attr( $id ); ?>">
									<?php esc_attr_e( 'Duplicate', 'ajax-load-more-filters' ); ?>
								</a>
								<span>|</span>
								<?php if ( $filter['facets'] ) { ?>
								<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ); ?>&rebuild_facet_index=<?php echo esc_attr( $id ); ?>"><?php esc_attr_e( 'Rebuild Index', 'ajax-load-more-filters' ); ?></a> |
								<?php } ?>
								<a href="<?php echo esc_attr( ALM_FILTERS_BASE_URL ); ?>&delete_filter=<?php echo esc_attr( $id ); ?>" data-name="<?php echo esc_attr( $id ); ?>" class="delete-filter"><?php esc_attr_e( 'Delete', 'ajax-load-more-filters' ); ?></a>
							</div>
						</td>
						<td class="text-center hide-mobile">
							<abbr title="<?php printf( __( 'This filter contains %1$s filter block(s)', 'ajax-load-more-filters' ), $count ); ?>" style="cursor: help;">
								<?php echo esc_attr( $count ); ?>
							</abbr>
						</td>
						<td class="text-center hide-mobile">
							<?php if ( $filter['facets'] ) { ?>
								<svg class="is-active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M384 32c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l320 0zM342 145.7c-10.7-7.8-25.7-5.4-33.5 5.3L189.1 315.2 137 263.1c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l72 72c5 5 11.9 7.5 18.8 7s13.4-4.1 17.5-9.8L347.3 179.2c7.8-10.7 5.4-25.7-5.3-33.5z"/></svg>
							<?php } else { ?>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M384 80c8.8 0 16 7.2 16 16l0 320c0 8.8-7.2 16-16 16L64 432c-8.8 0-16-7.2-16-16L48 96c0-8.8 7.2-16 16-16l320 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32z"/></svg>
							<?php } ?>
						</td>
						<td class="text-center hide-mobile">
							<?php
							if ( array_key_exists( 'facets', $filter ) && $filter['facets'] ) {
								$facet_count = count( ALMFilters::get_facet_index_by_id( $id ) );
								?>
							<abbr title="<?php printf( __( 'This filter contains %1$s indexed posts', 'ajax-load-more-filters' ), $facet_count ); ?>" style="cursor: help;">
								<?php echo esc_attr( $facet_count ); ?>
							</abbr>
								<?php
							} else {
								echo ' -- ';
							}
							?>
						</td>
						<td>
							<?php esc_attr_e( 'Published', 'ajax-load-more-filters' ); ?>:<br/>
							<?php
							if ( isset( $filter['date'] ) ) {
								echo '<abbr title="' . esc_attr( gmdate( 'Y/m/d h:i:s a', $filter['date'] ) ) . '" style="cursor: help;">' . esc_attr( gmdate( 'Y/m/d', $filter['date'] ) ) . '</abbr>';
							}
							?>
						</td>
					</tr>
						<?php
					}
			endforeach;
				?>
			</tbody>
		</table>

		<div id="alm-filter-pop-up">
			<div class="inner-wrap small">
				<h3><?php esc_attr_e( 'Duplicate Filter', 'ajax-load-more-filters' ); ?></h3>
				<p><?php esc_attr_e( 'Enter a unique filter ID and click the Duplicate button.', 'ajax-load-more-filters' ); ?></p>
				<form action="" method="GET" class="dup-form">
					<input type="hidden" value="ajax-load-more-filters" name="page">
					<input type="hidden" value="" name="duplicate_filter">
					<input type="text" name="filter_id" onkeypress="window.restrictIDChars(event);">
					<div>
						<button type="submit" class="button button-primary"><?php esc_attr_e( 'Duplicate', 'ajax-load-more-filters' ); ?></button>
						<button type="button" class="button dup-form-close"><?php esc_attr_e( 'Cancel', 'ajax-load-more-filters' ); ?></button>
					</div>
				</form>
			</div>
		</div>

		<?php else : ?>
			<?php echo wp_kses_post( alm_filters_empty_filters( $pos ) ); ?>
		<?php endif; ?>
	<?php
}

/**
 * Table header column order arrows.
 *
 * @param string $column  The column heading.
 * @param string $orderby The orderby param.
 * @param string $order   The order param.
 * @return void
 */
function alm_filters_list_arrow( $column = '', $orderby = null, $order = 'asc' ) {
	if ( $orderby === $column ) {
		if ( $order === 'asc' ) {
			?>
		<span class="dashicons dashicons-arrow-up" style="color: #555;"></span>
		<?php } else { ?>
		<span class="dashicons dashicons-arrow-down" style="color: #555;"></span>
			<?php
		}
	}
}

/**
 * This function is called when filters do not exist.
 *
 * @since 1.5
 * @param string $pos Position of the filter.
 * @return string     Raw HTML for display.
 */
function alm_filters_empty_filters( $pos ) {
	$response  = '<div class="alm-no-filters ' . $pos . '">';
	$response .= '<div class="alm-no-filters--inner">';
	$response .= '<p class="first-intro">' . __( 'It appears you don\'t have any filters!', 'ajax-load-more-filters' ) . '</p>';
	$response .= '<p>' . __( 'The first step in filtering with Ajax Load More is to create one!', 'ajax-load-more-filters' ) . '</p>';
	if ( $pos !== 'sidebar' ) {
		$response .= '<p class="create-btn"><a href="' . ALM_FILTERS_BASE_URL . '&action=new" class="button button-primary button-large"> ' . __( 'Create Filter', 'ajax-load-more-filters' ) . '</a></p>';
	}
	$response .= '</div>';
	$response .= '</div>';

	return $response;
}
