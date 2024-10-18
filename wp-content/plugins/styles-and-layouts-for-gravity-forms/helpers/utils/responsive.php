<?php

/**
 * Check if key exit inside settings.
 *
 * @param array  $form_options The saved settings of form.
 * @param string $category The category under settings.
 * @param string $field_names The property of field to check.
 * @return bool
 */
function stla_isset_checker( $form_options, $category, $field_names ) {
	$is_field_set = false;
	if ( ! isset( $form_options[ $category ] ) ) {
		return $is_field_set;
	}
	foreach ( $field_names as $field_name ) {
		if ( ! empty( $form_options[ $category ][ $field_name ] ) ) {
			$is_field_set = true;
		}
	}
	return $is_field_set;
}
