<?php
/**
 * ALM Filters Tools Class.
 *
 * @package ALMFilters
 */

/**
 * Initiate the class.
 */
class ALM_Filters_Tools {

	/**
	 * ALM Notices.
	 *
	 * @var array
	 */
	public $notices = [];

	/**
	 * Construct class.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'export_filters' ] );
		add_action( 'admin_init', [ $this, 'import_filters' ] );

		if ( class_exists( 'ALM_Filters_Notices' ) && is_admin() ) {
			$this->notices = new ALM_Filters_Notices();
		}
	}

	/**
	 * Export ALM Filter Groups.
	 *
	 * @since 1.5
	 */
	public function export_filters() {
		$params = filter_input_array( INPUT_POST ); // phpcs:ignore
		if ( ! isset( $params['alm_filters_export'] ) ) {
			return; // Exit if not set.
		}

		$filters = $params['filter_keys'] ?? [];
		if ( empty( $filters ) ) {
			$this->notices->alm_add_admin_notice( __( 'No filters selected. You must select at least one filter to export.', 'ajax-load-more-filters' ), 'error', '', false );
			return;
		}

		$filename     = 'alm-filters';
		$export_array = [];
		foreach ( $filters as $filter ) {
			$option         = get_option( $filter );
			$export_array[] = unserialize( $option );
			$filename      .= '[' . ALMFilters::alm_filters_replace_string( $filter ) . ']';
		}

		$filename .= '.json';
		header( 'Content-Description: File Transfer' );
		header( "Content-Disposition: attachment; filename={$filename}" );
		header( 'Content-Type: application/json; charset=utf-8' );

		echo wp_json_encode( $export_array, JSON_PRETTY_PRINT );
		die();
	}

	/**
	 * Import ALM Filter Groups.
	 *
	 * @since 1.5
	 */
	public function import_filters() {
		$params = filter_input_array( INPUT_POST ); // phpcs:ignore
		if ( ! isset( $params['alm_filters_import'] ) ) {
			return; // Exit if not set.
		}

		$file = $_FILES['alm_import_file']; // phpcs:ignore

		if ( $file ) {
			// Validate type.
			if ( pathinfo( $file['name'], PATHINFO_EXTENSION ) !== 'json' ) {
				$this->notices->alm_add_admin_notice( __( 'Incorrect file type! You can only import JSON files.', 'ajax-load-more-filters' ), 'error' );
				return;
			}

			// phpcs:ignore
			$json = file_get_contents( $file['tmp_name'] ); // read file.
			$json = json_decode( $json, true ); // decode json.

			// Validate json.
			if ( empty( $json ) ) {
				$this->notices->alm_add_admin_notice( __( 'Import file empty.', 'ajax-load-more-filters' ), 'error' );
				return;
			}

			// Incorrect JSON format.
			if ( ! is_array( $json ) ) {
				$this->notices->alm_add_admin_notice( __( 'JSON file formatted incorrectly.', 'ajax-load-more-filters' ), 'error' );
				return;
			}

			// Loop all filters.
			$count         = 0;
			$import_string = '';
			foreach ( $json as $filter ) {

				if ( ! isset( $filter['id'] ) ) {
					$this->notices->alm_add_admin_notice( __( 'JSON file formatted incorrectly', 'ajax-load-more-filters' ), 'error' );
					break;
				}
				$id = $filter['id'];

				if ( ! isset( $filter['style'] ) ) {
					$this->notices->alm_add_admin_notice( __( 'JSON file formatted incorrectly', 'ajax-load-more-filters' ), 'error' );
					break;
				}

				if ( ! isset( $filter['filters'] ) ) {
					$this->notices->alm_add_admin_notice( __( 'JSON file formatted incorrectly', 'ajax-load-more-filters' ), 'error' );
					break;
				}

				$style   = $filter['style'];
				$filters = $filter['filters'];

				if ( $filters && $id && $style ) {
					$filter = serialize( $filter );
					update_option( ALM_FILTERS_PREFIX . $id, $filter, false );
					$import_string .= ( $count > 0 ) ? ', ' : '';
					$import_string .= '<a href="' . ALM_FILTERS_BASE_URL . '&filter=' . $id . '"><strong>' . $id . '</strong></a>';

					++$count;
				}
			}

			if ( $count > 0 ) {
				$this->notices->alm_add_admin_notice( $import_string . __( ' successfully imported', 'ajax-load-more-filters' ) );
			}
		} else {
			// Error - file does not exist.
			$this->notices->alm_add_admin_notice( __( 'An error has occurred', 'ajax-load-more-filters' ), 'error' );
		}
	}
}

new ALM_Filters_Tools();
