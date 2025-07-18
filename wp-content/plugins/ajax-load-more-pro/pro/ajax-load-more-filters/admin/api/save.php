<?php
/**
 * Save REST API endpoint for saving filters.
 *
 * @package ALMFilters
 */

/**
 * Init Endpoint.
 */
add_action(
	'rest_api_init',
	function () {
		$my_namespace = 'alm-filters';
		$my_endpoint  = '/save';
		register_rest_route(
			$my_namespace,
			$my_endpoint,
			[
				'methods'             => 'POST',
				'callback'            => 'alm_filters_save_filter',
				'permission_callback' => '__return_true',
			]
		);
	}
);

/**
 * Save the filter data.
 *
 * @param WP_REST_Request $request The HTTP request object.
 * @return void
 * @since 1.0
 */
function alm_filters_save_filter( WP_REST_Request $request ) {
	// Get contents of request and convert to array.
	$data    = json_decode( $request->get_body(), true );
	$options = json_decode( $data['options'] );

	$filters = json_decode( $data['filters'] );

	$filter_array       = [];
	$filter_array['id'] = '';

	if ( $filters ) {

		// Loop options and build options array.
		foreach ( $options as $key => $value ) {

			// Get the ID.
			$id = isset( $options[ $key ]->id ) ? sanitize_key( $options[ $key ]->id ) : '';

			// Get option from DB.
			$filter_option = unserialize( get_option( ALM_FILTERS_PREFIX . $id ) );

			// Set Options from `$options` data.
			$filter_array['id']                   = $id;
			$filter_array['style']                = isset( $options[ $key ]->style ) ? $options[ $key ]->style : '';
			$filter_array['facets']               = isset( $options[ $key ]->facets ) ? $options[ $key ]->facets : false;
			$filter_array['facets_post_types']    = isset( $options[ $key ]->facets_post_types ) ? $options[ $key ]->facets_post_types : '';
			$filter_array['facets_hide_inactive'] = isset( $options[ $key ]->facets_hide_inactive ) ? $options[ $key ]->facets_hide_inactive : false;
			$filter_array['reset_button']         = isset( $options[ $key ]->reset_button ) ? $options[ $key ]->reset_button : false;
			$filter_array['reset_button_label']   = isset( $options[ $key ]->reset_button_label ) ? $options[ $key ]->reset_button_label : '';

			// Only set button_text if style === change.
			if ( $filter_array['style'] === 'button' ) {
				$filter_array['button_text'] = isset( $options[ $key ]->button_text ) ? $options[ $key ]->button_text : '';
			}

			// Facets - boolean variables.
			$has_facets            = $filter_array['facets'];
			$has_facets_post_types = $filter_array['facets_post_types'] && is_array( $filter_array['facets_post_types'] );

			// Facets - Remove all facet parameters if facets is false.
			if ( ! $has_facets ) {
				unset( $filter_array['facets'] );
				unset( $filter_array['facets_post_types'] );
				unset( $filter_array['facets_hide_inactive'] );
			}

			// Reset Button.
			if ( ! $filter_array['reset_button'] ) {
				unset( $filter_array['reset_button'] );
				unset( $filter_array['reset_button_label'] );
			}

			// Get current time.
			$timestamp = current_time( 'timestamp' );

			// Created Date.
			if ( ! $filter_option ) { // if filter doesn't yet exist.
				$filter_array['date_created'] = $timestamp;
			} else {
				// Get current filter for date created attribute.
				if ( ! isset( $filter_option['date_created'] ) ) {
					// If it doesn't exist, created it.
					$filter_array['date_created'] = $timestamp;
				} else {
					// Set it back to original value.
					$filter_array['date_created'] = $filter_option['date_created'];
				}
			}

			// Update modified date.
			$filter_array['date_modified'] = $timestamp;
		}

		$filter_array['filters'] = [];

		// Convert $filters to array from stdClass Object.
		$filters = json_decode( wp_json_encode( $filters ), true );

		// Show Count option.
		$show_count_array = [ 'category', 'category__and', 'tag', 'tag__and', 'taxonomy' ];
		if ( $has_facets ) {
			// If facets.
			$show_count_array[] = 'meta'; // Add meta support.
			$show_count_array[] = 'author'; // Add author support.
			$show_count_array[] = 'year'; // Add year support.
			$show_count_array[] = 'month'; // Add month support.
			$show_count_array[] = 'day'; // Add day support.
			$show_count_array[] = 'post_type'; // Add post_type support.
		}

		// Fields that allow for selection.
		$selection_type_array = [ 'checkbox', 'radio', 'select', 'select_multiple' ];

		// Loop each item in array as a $filter.
		foreach ( $filters as $filter ) {

			// Confirm atleast a key and field_type are set before pushing into array.
			if ( $filter['key'] && $filter['field_type'] ) {

				// Convert $filter to array from stdClass Object.
				$array = json_decode( wp_json_encode( $filter ), true );

				// Remove items from the array if empty.

				/**
				 * Taxonomy Parameters.
				 */
				if ( isset( $array['taxonomy'] ) && $array['taxonomy'] === '' ) {
					unset( $array['taxonomy'] );
					unset( $array['taxonomy_operator'] );
					unset( $array['taxonomy_include_children'] );
				}
				// Remove include children if true as thats the default.
				if ( isset( $array['taxonomy_include_children'] ) && $array['taxonomy_include_children'] === 'true' ) {
					unset( $array['taxonomy_include_children'] );
				}
				// Remove Taxonomy parameters when key !== 'taxonomy'.
				if ( $filter['key'] !== 'taxonomy' ) {
					unset( $array['taxonomy'] );
					unset( $array['taxonomy_operator'] );
					unset( $array['taxonomy_include_children'] );
				}

				/**
				 * Custom Field Parameters.
				 */
				if ( isset( $array['meta_key'] ) && $array['meta_key'] === '' ) {
					unset( $array['meta_key'] );
					unset( $array['meta_operator'] );
					unset( $array['meta_type'] );
				}
				// Remove Custom Field parameters when key !== 'meta'.
				if ( $filter['key'] !== 'meta' ) {
					unset( $array['meta_key'] );
					unset( $array['meta_operator'] );
					unset( $array['meta_type'] );
				}

				// Author Role.
				if ( isset( $array['author_role'] ) && $array['author_role'] === '' ) {
					unset( $array['author_role'] );
				}

				// Exclude.
				if ( isset( $array['exclude'] ) && $array['exclude'] === '' ) {
					unset( $array['exclude'] );
				}

				// Show Count.
				if ( ! in_array( $filter['key'], $show_count_array, true ) || ! in_array( $filter['field_type'], $selection_type_array, true ) ) {
					$array['show_count'] = false;
				}

				// Selected Value.
				if ( ( isset( $array['selected_value'] ) && $array['selected_value'] === '' ) || $filter['field_type'] === 'text' ) {
					unset( $array['selected_value'] );
				}

				// Default Value.
				if ( ( isset( $array['default_value'] ) && $array['default_value'] === '' ) ) {
					unset( $array['default_value'] );
				}

				// Custom Values.
				if ( ( isset( $array['values'] ) && $array['values'] === '' ) || empty( $array['values'] ) || $filter['field_type'] === 'text' || $filter['field_type'] === 'star_rating' ) {
					unset( $array['values'] );
				}

				// Label.
				if ( isset( $array['label'] ) && $array['label'] === '' ) {
					unset( $array['label'] );
				}

				// Default Select Value.
				if ( $filter['field_type'] !== 'select' || isset( $array['default_select_option'] ) && $array['default_select_option'] === '' ) {
					unset( $array['default_select_option'] );
				}

				// Title.
				if ( isset( $array['title'] ) && empty( $array['title'] === '' ) ) {
					$array['title'] === ''; // If empty title, Set to empty string to prevent other issues.
				}

				// Description.
				if ( isset( $array['description'] ) && empty( $array['description'] ) ) {
					unset( $array['description'] );
				}

				// Button Label.
				if ( ( ( isset( $array['button_label'] ) && $array['button_label'] === '' ) || ( $filter['field_type'] !== 'text' ) && $filter['field_type'] !== 'date_picker' ) ) {
					unset( $array['button_label'] );
				}

				// Placeholder.
				if ( isset( $array['placeholder'] ) && $array['placeholder'] === '' ) {
					unset( $array['placeholder'] );
				}

				// Classes.
				if ( isset( $array['classes'] ) && $array['classes'] === '' ) {
					unset( $array['classes'] );
				}

				/**
				 * Star Rating.
				 */
				if ( $filter['field_type'] !== 'star_rating' ) {
					unset( $array['star_rating_min'], $array['star_rating_max'] );
				}

				/**
				 * Checkbox Limit.
				 */
				if ( ! in_array( $filter['field_type'], [ 'checkbox' ], true ) || isset( $array['checkbox_limit'] ) && $array['checkbox_limit'] === '' ) {
					unset( $array['checkbox_limit'] );
				}
				// Limit Label Open.
				if ( isset( $array['checkbox_limit_label_open'] ) && $array['checkbox_limit_label_open'] === '' || ! isset( $array['checkbox_limit'] ) ) {
					// Remove if empty or parent `checkbox_limit` is not set.
					unset( $array['checkbox_limit_label_open'] );
				}
				// Limit Label Close.
				if ( isset( $array['checkbox_limit_label_close'] ) && $array['checkbox_limit_label_close'] === '' || ! isset( $array['checkbox_limit'] ) ) {
					// Remove if empty or parent `checkbox_limit` is not set.
					unset( $array['checkbox_limit_label_close'] );
				}

				/**
				 * Section Toggle.
				 */
				if ( isset( $array['section_toggle'] ) && $array['section_toggle'] === '' && $array['title'] !== '' ) {
					unset( $array['section_toggle'] );
					unset( $array['section_toggle_status'] );
				}
				// Section Toggle Status.
				if ( ! $array['section_toggle'] || $array['title'] === '' ) {
					unset( $array['section_toggle'] );
					unset( $array['section_toggle_status'] );
				}

				/**
				 * Checkbox Toggle.
				 */
				if ( $filter['field_type'] !== 'checkbox' || isset( $array['checkbox_toggle'] ) && $array['checkbox_toggle'] === '' ) {
					unset( $array['checkbox_toggle'] );
				}

				// Facets: Don't allow checkbox toggle.
				if ( $has_facets ) {
					unset( $array['checkbox_toggle'] );
					unset( $array['checkbox_toggle_label'] );
				}
				// Toggle Label.
				if ( $filter['field_type'] !== 'checkbox' || isset( $array['checkbox_toggle'] ) && $array['checkbox_toggle'] === '' || isset( $array['checkbox_toggle_label'] ) && $array['checkbox_toggle_label'] === '' ) {
					unset( $array['checkbox_toggle_label'] );
				}

				/**
				 * Datepicker.
				 */

				// Mode.
				if ( $filter['field_type'] !== 'date_picker' || isset( $array['datepicker_mode'] ) && $array['datepicker_mode'] === '' ) {
					unset( $array['datepicker_mode'] );
				}

				// Display Format.
				if ( $filter['field_type'] !== 'date_picker' || isset( $array['datepicker_format'] ) && $array['datepicker_format'] === '' ) {
					unset( $array['datepicker_format'] );
				}

				// Locale.
				if ( $filter['field_type'] !== 'date_picker' || isset( $array['datepicker_locale'] ) && $array['datepicker_locale'] === '' ) {
					unset( $array['datepicker_locale'] );
				}

				// Range Slider.

				// Min.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_min'] ) && $array['rangeslider_min'] === '' ) {
					unset( $array['rangeslider_min'] );
				}
				// Max.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_max'] ) && $array['rangeslider_max'] === '' ) {
					unset( $array['rangeslider_max'] );
				}
				// Start.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_start'] ) && $array['rangeslider_start'] === '' ) {
					unset( $array['rangeslider_start'] );
				}
				// End.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_end'] ) && $array['rangeslider_end'] === '' ) {
					unset( $array['rangeslider_end'] );
				}
				// Steps.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_steps'] ) && $array['rangeslider_steps'] === '' ) {
					unset( $array['rangeslider_steps'] );
				}
				// Steps.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_label'] ) && $array['rangeslider_label'] === '' ) {
					unset( $array['rangeslider_label'] );
				}
				// Orientation.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_orientation'] ) && $array['rangeslider_orientation'] === '' ) {
					unset( $array['rangeslider_orientation'] );
				}
				// Decimals.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_decimals'] ) && $array['rangeslider_decimals'] === '' ) {
					unset( $array['rangeslider_decimals'] );
				}
				// Reset Button.
				if ( $filter['field_type'] !== 'range_slider' || isset( $array['rangeslider_reset'] ) && $array['rangeslider_reset'] === '' ) {
					unset( $array['rangeslider_reset'] );
				}

				unset( $array['order'] );
				unset( $array['uniqueid'] );
				unset( $array['hookname'] );

				array_push( $filter_array['filters'], $array );
			}
		}
	}

	// Create the response obj.
	if ( count( $filter_array['filters'] ) > 0 && $filter_array['id'] !== '' ) {
		// If array is larger than just $options and ID is set.

		// Facets with empty post types.
		if ( $has_facets && ! $has_facets_post_types ) {
			$response = [
				'success' => false,
				'msg'     => __( 'Error: You must select at least 1 post type when using facets.', 'ajax-load-more-filters' ),
				'code'    => wp_json_encode( $filter_array, JSON_PRETTY_PRINT ),
			];
			wp_send_json( $response );
		}

		/**
		 * Rebuild facet index if post types have changed.
		 */
		$rebuild_facet_index = false;
		if ( $filter_option && $has_facets ) {
			// Get old and new post type selection.
			$old_facets_post_types = isset( $filter_option['facets_post_types'] ) ? $filter_option['facets_post_types'] : [];
			$new_facets_post_types = $filter_array['facets_post_types'];
			$rebuild_facet_index   = $old_facets_post_types !== $new_facets_post_types;
		}

		// Create/Update option on success.
		update_option( ALM_FILTERS_PREFIX . $filter_array['id'], serialize( $filter_array ), false );

		// Create response.
		$response = [
			'success' => true,
			'msg'     => __( 'Filter saved successfully.', 'ajax-load-more-filters' ),
			'code'    => wp_json_encode( $filter_array, JSON_PRETTY_PRINT ),
			'rebuild' => $rebuild_facet_index,
		];

	} else {
		// Create response.
		$response = [
			'success' => false,
			'msg'     => __( 'Error - You are missing some important filter criteria. Please fill out all required fields.', 'ajax-load-more-filters' ),
			'code'    => '',
		];
	}

	wp_send_json( $response );
}
