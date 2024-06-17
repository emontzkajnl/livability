<?php

class Stla_Admin_Fetch_Content_Area {

	/**
	 * instance of class
	 *
	 * @var Stla_Admin_Fetch_Content_Area
	 */
	private static $instance;

	public function __construct() {
		add_action( 'wp_ajax_stla_gravity_form_html', array( $this, 'stla_gravity_form_html' ) );
		add_action( 'wp_ajax_stla_gravity_form_confirmation_html', array( $this, 'stla_gravity_form_confirmation_html' ) );
		add_action( 'wp_ajax_stla_get_page_count', array( $this, 'stla_get_page_count' ) );
		add_action( 'wp_ajax_stla_styler_settings', array( $this, 'stla_styler_settings' ) );
		add_action( 'wp_ajax_stla_get_forms_with_styling', array( $this, 'stla_get_forms_with_styling' ) );
		add_action( 'wp_ajax_stla_delete_forms_styles', array( $this, 'stla_delete_forms_styles' ) );
		add_action( 'wp_ajax_stla_save_styler_settings', array( $this, 'stla_save_styler_settings' ) );
		add_action( 'wp_ajax_stla_save_booster_settings', array( $this, 'stla_save_booster_settings' ) );

		add_action( 'wp_ajax_stla_general_settings', array( $this, 'stla_general_settings' ) );
		add_action( 'wp_ajax_stla_booster_settings', array( $this, 'stla_booster_settings' ) );
		add_action( 'wp_ajax_stla_form_fields_labels', array( $this, 'stla_form_fields_labels' ) );
		add_action( 'wp_ajax_stla_get_all_form_names', array( $this, 'stla_get_all_form_names' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	function init() {
		// delete_option( 'gf_stla_form_id_9' );
		// $general_settings = get_option( 'gf_stla_general_settings9' );
		// print_r( $general_settings );
		// die;

		$data = get_option( 'gf_stla_form_id_tooltips_24' );
		// $data = update_option( 'gf_stla_form_id_tooltips_24', array() );
		$GLOBALS['gf_stla'] = $data;
		// var_dump( $data );
		// die();
	}

	/**
	 * Main Plugin Instance
	 *
	 * Insures that only one instance of a plugin class exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 5.0
	 * @return Stla_Admin_Fetch_Content_Area Highlander Instance
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Stla_Admin_Fetch_Content_Area ) ) {
			self::$instance = new Stla_Admin_Fetch_Content_Area();
		}

		return self::$instance;
	}

	/**
	 * Returns the html for gravity form
	 *
	 * @return string
	 */
	function stla_gravity_form_html() {

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$formId = isset( $_POST['formId'] ) ? $_POST['formId'] : 0;

		gravity_form( $formId );

		// Make sure to exit after outputting the HTML
		wp_die();
	}

	function stla_gravity_form_confirmation_html() {

		require_once GFCommon::get_base_path() . '/form_display.php';

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$formId = isset( $_POST['formId'] ) ? $_POST['formId'] : 0;
		$form   = GFAPI::get_form( $formId );

		// error_log( print_r($form['confirmations'], true) );
		$confirmation = GFFormDisplay::get_confirmation_message( reset( $form['confirmations'] ), $form, array(), array() );

		echo $confirmation;

		// Make sure to exit after outputting the HTML
		wp_die();
	}

	function stla_get_page_count() {
		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$formId = isset( $_POST['formId'] ) ? $_POST['formId'] : 0;

		$form_data = GFAPI::get_form( $formId );

		$page_count = sizeof( GFAPI::get_fields_by_type( $form_data, array( 'page' ) ) );

		wp_send_json_success( $page_count );
	}



	/**
	 * return all the forms to show in header.
	 *
	 * @return void
	 */
	function stla_get_all_form_names() {

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$all_forms_data = GFAPI::get_forms();

		$form_data_with_required_fields = array();

		foreach ( $all_forms_data as $form_data ) {
			$form_data_with_required_fields[] = array(
				'id'    => $form_data['id'],
				'title' => $form_data['title'],
			);
		}

		wp_send_json_success( $form_data_with_required_fields );
	}

	/**
	 * Returns the styler settings
	 *
	 * @return string
	 */
	function stla_styler_settings() {

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$form_id = isset( $_POST['formId'] ) ? $_POST['formId'] : '';

		// GFFormDisplay::enqueue_form_scripts( 24 );
		$settings = get_option( 'gf_stla_form_id_' . $form_id );
		$settings = empty( $settings ) ? array() : $settings;

		wp_send_json_success( $settings );
	}

	/**
	 * Returns all the forms which have styles applied.
	 *
	 * @return void
	 */
	function stla_get_forms_with_styling() {

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$styled_forms = $this->get_forms_with_styles();

		wp_send_json_success( $styled_forms );
	}


	function get_forms_with_styles() {

		$styled_forms = array();
		// get all gravity forms created by user
		if ( class_exists( 'RGFormsModel' ) ) {
			$forms = RGFormsModel::get_forms( null, 'title' );

			$styled_forms[] = array(
				'label' => '---Select form --',
				'value' => '-1',
			);

			foreach ( $forms as $form ) {

				$style_current_form = get_option( 'gf_stla_form_id_' . $form->id );

				if ( ! empty( $style_current_form ) ) {

					$styled_forms[] = array(
						'label' => $form->title,
						'value' => $form->id,
					);

				}
			}
		} else {
			$styled_forms[] = array(
				'label' => 'Gravity Forms not installed',
				'value' => '-1',
			);
		}
		return $styled_forms;
	}

	/**
	 * Deletes Forms styles and return the updated form list.
	 *
	 * @return void
	 */
	function stla_delete_forms_styles() {
		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$formId = isset( $_POST['formId'] ) ? (int) $_POST['formId'] : 0;

		delete_option( 'gf_stla_form_id_' . $formId );

		$styled_forms = $this->get_forms_with_styles();

		wp_send_json_success( $styled_forms );
	}

	/**
	 * Save all the styler settings on save button.
	 *
	 * @return void
	 */
	function stla_save_styler_settings() {
		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$formId          = isset( $_POST['formId'] ) ? (int) $_POST['formId'] : 0;
		$styler_settings = isset( $_POST['stylerSettings'] ) ? $_POST['stylerSettings'] : 0;
		$styler_settings = stripslashes( $styler_settings );
		$styler_settings = json_decode( $styler_settings, true );

		$general_settings = isset( $_POST['generalSettings'] ) ? $_POST['generalSettings'] : 0;
		$general_settings = stripslashes( $general_settings );
		$general_settings = json_decode( $general_settings, true );

		// $styler_settings = serialize( $styler_settings );

		update_option( 'gf_stla_form_id_' . $formId, $styler_settings );
		update_option( 'gf_stla_general_settings' . $formId, $general_settings );

		$styler_options = get_option( 'gf_stla_form_id_' . $formId );

		wp_send_json_success( '' );
	}

	/**
	 * Save all the settings on save button.
	 *
	 * @return void
	 */
	function stla_save_booster_settings() {
		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$booster_settings = isset( $_POST['boosterSettings'] ) ? $_POST['boosterSettings'] : '';
		if ( empty( $booster_settings ) ) {
			wp_send_json_error( 'Booster Settings are empty' );
		}

		$booster_settings = stripslashes( $booster_settings );
		$booster_settings = json_decode( $booster_settings, true );

		update_option( 'gf_stla_booster_settings', $booster_settings );

			wp_send_json_success( '' );
	}


	/**
	 * Sidebar general settings.
	 */
	function stla_general_settings() {
		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$form_id = isset( $_POST['formId'] ) ? $_POST['formId'] : '';

		$settings = get_option( 'gf_stla_general_settings' . $form_id );
		$settings = empty( $settings ) ? array() : $settings;

		wp_send_json_success( $settings );
	}

	/**
	 * Sidebar booster settings.
	 */
	function stla_booster_settings() {
		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$form_id             = isset( $_POST['formId'] ) ? $_POST['formId'] : '';
		$license_status_keys = array( 'custom_themes_addon_license_status', 'ai_addon_license_status', 'field_icons_addon_license_status', 'gravity_forms_tooltips_addon_license_status', 'bootstrap_design_addon_license_status', 'gravity_forms_checkbox_radio_license_status', 'material_design_addon_license_status' );

		$license_status = array();

		foreach ( $license_status_keys as $license_status_key ) {
			$license_status[ $license_status_key ] = get_option( $license_status_key );
		}

		$booster_settings = get_option( 'gf_stla_booster_settings' );
		$stla_licenses    = get_option( 'stla_licenses' );
		$booster_settings = empty( $booster_settings ) ? array() : $booster_settings;
		$licenses         = array(
			'licenses' => array(
				'keys'   => $stla_licenses,
				'status' => $license_status,
			),

		);

		$settings = array_merge( $licenses, $booster_settings );
		wp_send_json_success( $settings );
	}

	/**
	 * Fetch form fields Labels settings.
	 */
	function stla_form_fields_labels() {

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! check_ajax_referer( 'stla_gravity_booster_nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$form_id = isset( $_POST['formId'] ) ? $_POST['formId'] : '';

		if ( empty( $form_id ) ) {
			wp_send_json_error( 'Form id not selected' );
		}

		$form           = GFAPI::get_form( $form_id );
		$field_labels   = array();
		$complex_fields = array( 'name', 'address', 'email' );
		$form_fields    = $form['fields'];
		foreach ( $form_fields as $field ) {

			$field_labels[] = array(
				'id'    => $field->id,
				'label' => $field->label,
			);

			if ( in_array( $field->type, $complex_fields ) ) {
				$state_field_id   = false;
				$country_field_id = false;
				$name_prefix_id   = false;

				if ( $field->type === 'address' ) {
					$country_field_id = floatval( $field['id'] . '.6' );
					$address_type     = $field['addressType'];
					if ( $address_type !== 'international' ) {
						$state_field_id = floatval( $field['id'] . '.4' );
					}
				}

				if ( $field->type === 'name' ) {
					$name_prefix_id = floatval( $field['id'] . '.2' );

				}

				if ( ! empty( $field['inputs'] ) ) {
					foreach ( $field['inputs'] as  $sub_field ) {

						$is_hidden    = ! empty( $sub_field['isHidden'] ) ? $sub_field['isHidden'] : false;
						$sub_field_id = floatval( $sub_field['id'] );

						// state field id is only set if its not international ( then it will be dropdown)
						// country field id will always be dropdown.. skip loop in both cases
						// name prefix is always dropdown, so ignored.
						if ( $state_field_id === $sub_field_id || $country_field_id === $sub_field_id || $name_prefix_id === $sub_field_id ) {
							continue;
						}

						if ( ! $is_hidden && ! isset( $child_input['choices'] ) ) {
							$field_labels[] = array(
								'id'    => $sub_field_id,
								'label' => $sub_field['label'],
							);

						}
					}
				}
			}
		}

		wp_send_json_success( $field_labels );
	}
}




/**
 * The main function responsible for returning The Highlander Plugin
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 3.0
 * @return {class} Highlander Instance
 */
function Stla_Admin_Fetch_Content_Area() {
	return Stla_Admin_Fetch_Content_Area::instance();
}

Stla_Admin_Fetch_Content_Area();
