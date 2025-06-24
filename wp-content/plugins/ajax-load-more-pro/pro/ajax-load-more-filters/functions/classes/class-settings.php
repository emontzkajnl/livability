<?php
/**
 * ALM Filters Settings Class.
 *
 * @package ALMFilters
 */

/**
 * Initiate the class.
 */
class ALM_Filters_Settings {

	/**
	 * Construct class.
	 */
	public function __construct() {
		add_action( 'alm_filters_settings', [ $this, 'alm_filters_settings' ] );
	}

	/**
	 * Create the Comments settings panel.
	 *
	 * @since 1.0
	 */
	public function alm_filters_settings() {
		register_setting(
			'alm_filters_license',
			'alm_filters_license_key',
			'alm_filters_sanitize_license'
		);
		add_settings_section(
			'alm_filters_settings',
			'Filter Settings',
			'alm_filters_settings_callback',
			'ajax-load-more'
		);
		add_settings_field(
			'_alm_filters_facet_index',
			__( 'Facet Indexing', 'ajax-load-more-filters' ),
			'alm_filters_facet_index_callback',
			'ajax-load-more',
			'alm_filters_settings'
		);
		add_settings_field(
			'_alm_filters_background_indexing',
			__( 'Enable Background Processing', 'ajax-load-more-filters' ),
			'alm_filters_background_indexing_callback',
			'ajax-load-more',
			'alm_filters_settings'
		);
		add_settings_field(
			'_alm_filters_disable_css',
			__( 'Disable Filters CSS', 'ajax-load-more-filters' ),
			'alm_filters_disable_css_callback',
			'ajax-load-more',
			'alm_filters_settings'
		);
		add_settings_field(
			'_alm_filters_color',
			__( 'Theme Color', 'ajax-load-more-filters' ),
			'alm_filters_color_callback',
			'ajax-load-more',
			'alm_filters_settings'
		);
		add_settings_field(
			'_alm_filters_flatpickr_theme',
			__( 'Datepicker Theme', 'ajax-load-more-filters' ),
			'alm_filters_flatpickr_theme_callback',
			'ajax-load-more',
			'alm_filters_settings'
		);
	}
}

/**
 * Setting: Section Heading
 *
 * @since 1.0
 */
function alm_filters_settings_callback() {
	$html = '<p>' . __( 'Customize your installation of the <a href="http://connekthq.com/plugins/ajax-load-more/filters/">Filters</a> add-on.', 'ajax-load-more-filters' ) . '</p>';
	echo wp_kses_post( $html );
}

/**
 * Setting: Disable CSS.
 *
 *  @since 1.0
 */
function alm_filters_disable_css_callback() {
	$options = get_option( 'alm_settings' );
	if ( ! isset( $options['_alm_filters_disable_css'] ) ) {
		$options['_alm_filters_disable_css'] = '0';
	}

	$html  = '<input type="hidden" name="alm_settings[_alm_filters_disable_css]" value="0" />';
	$html .= '<input type="checkbox" id="alm_filters_disable_css_input" name="alm_settings[_alm_filters_disable_css]" value="1"' . ( ( $options['_alm_filters_disable_css'] ) ? ' checked="checked"' : '' ) . ' />';
	$html .= '<label for="alm_filters_disable_css_input">' . __( 'I want to use my own CSS styles', 'ajax-load-more-filters' ) . '<br/><span style="display:block;"><i class="fa fa-file-text-o"></i> &nbsp;<a href="' . ALM_FILTERS_URL . '/dist/css/styles.css" target="blank">' . __( 'View Filter CSS', 'ajax-load-more-filters' ) . '</a></span></label>';

	echo $html; // phpcs:ignore
}

/**
 * Setting: Update facet indicies on post update.
 *
 *  @since 2.0
 */
function alm_filters_facet_index_callback() {
	$options = get_option( 'alm_settings' );
	$name    = '_alm_filters_facet_index';
	if ( ! isset( $options[ $name ] ) ) {
		$options[ $name ] = '1';
	}

	$html  = '<input type="hidden" name="alm_settings[' . $name . ']" value="0" />';
	$html .= '<input type="checkbox" id="' . $name . '" name="alm_settings[' . $name . ']" value="1"' . ( ( $options[ $name ] ) ? ' checked="checked"' : '' ) . ' />';
	$html .= '<label for="' . $name . '">' . __( 'Update facet indices when a post or custom post type is updated', 'ajax-load-more-filters' ) . '</label>';
	echo $html; // phpcs:ignore
}

/**
 * Setting: Enable facet background processing.
 *
 *  @since 3.0
 */
function alm_filters_background_indexing_callback() {
	$options = get_option( 'alm_settings' );
	$name    = '_alm_filters_background_indexing';
	if ( ! isset( $options[ $name ] ) ) {
		$options[ $name ] = '0';
	}

	$enabled = ALM_Facets::check_basic_auth() === 200 ? true : false;
	$html    = '<span style="display: block; margin-bottom: 15px;">' . __( 'Allow facet indices to be built with background processing.', 'ajax-load-more-filters' ) . '</span>';

	if ( $enabled ) {
		$html .= '<input type="hidden" name="alm_settings[' . $name . ']" value="0" />';
		$html .= '<input type="checkbox" id="' . $name . '" name="alm_settings[' . $name . ']" value="1"' . ( ( $options[ $name ] ) ? ' checked="checked"' : '' ) . ' />';
		$html .= '<label for="' . $name . '">' . __( 'Enable background processing', 'ajax-load-more-filters' ) . '</label>';

	} else {
		$html .= '<div class="alm-form-notice">';
		$html .= '<p><strong>' . __( 'Not Available', 'ajax-load-more-filters' ) . '</strong><br/>';
		$html .= __( 'It appears your site may be using basic authentication. Background processing relies on the WordPress HTTP API which which requires you to attach a username and password to the requests.', 'ajax-load-more-filters' );
		$html .= '</p>';

		$auth_url = 'https://connekthq.com/plugins/ajax-load-more/docs/add-ons/filters/facets/#background-processing';
		$html    .= '<p><a href="' . $auth_url . '" class="button button-primary" target="_blank">' . __( 'Learn More', 'ajax-load-more-filters' ) . '</a></p>';
		$html    .= '</div>';
	}

	echo $html; // phpcs:ignore
}

/**
 * Setting: Get the color of the paging element
 *
 * @since 1.0
 */
function alm_filters_color_callback() {
	$options = get_option( 'alm_settings' );
	if ( ! isset( $options['_alm_filters_color'] ) ) {
		$options['_alm_filters_color'] = '0';
	}

	$color     = $options['_alm_filters_color'];
	$selected0 = '';

	if ( $color === 'default' ) {
		$selected0 = 'selected="selected"';
	}

	$selected1 = '';
	if ( $color === 'blue' ) {
		$selected1 = 'selected="selected"';
	}

	$selected2 = '';
	if ( $color === 'red' ) {
		$selected2 = 'selected="selected"';
	}

	$selected3 = '';
	if ( $color === 'green' ) {
		$selected3 = 'selected="selected"';
	}

	$html  = '<label for="alm_settings_filters_color">' . __( 'Choose the theme color of your Ajax Load More Filter form elements.', 'ajax-load-more-filters' ) . '</label>';
	$html .= '<select id="alm_settings_filters_color" name="alm_settings[_alm_filters_color]">';
	$html .= '<option value="default" ' . $selected0 . '>' . __( 'Default', 'ajax-load-more-filters' ) . '</option>';
	$html .= '<option value="blue" ' . $selected1 . '>' . __( 'Blue', 'ajax-load-more-filters' ) . '</option>';
	$html .= '<option value="red" ' . $selected2 . '>' . __( 'Red', 'ajax-load-more-filters' ) . '</option>';
	$html .= '<option value="green" ' . $selected3 . '>' . __( 'Green', 'ajax-load-more-filters' ) . '</option>';
	$html .= '</select>';

	$html .= '<div class="ajax-load-more-wrap alm-filters alm-filters-container filters-' . $color . '"><span class="pages">' . __( 'Preview', 'ajax-load-more-filters' ) . '</span>';
		// Checkbox.
		$html .= '<div class="alm-filter" style="padding: 5px 0 20px; margin: 0; clear: both;">';
		$html .= '<li class="alm-filter--checkbox"><div class="alm-filter--link field-checkbox active" data-type="checkbox" data-value="design">' . __( 'Checked', 'ajax-load-more-filters' ) . '</div></li>';
		$html .= '<li class="alm-filter--checkbox"><div class="alm-filter--link field-checkbox" data-type="checkbox" data-value="design">' . __( 'Unchecked', 'ajax-load-more-filters' ) . '</div></li>';
		$html .= '</div>';

		// Radio.
		$html .= '<div class="alm-filter" style="padding: 10px 0 0; margin: 0; clear: both;">';
		$html .= '<li class="alm-filter--radio"><div class="alm-filter--link field-radio active" data-type="radio" data-value="design">' . __( 'Checked', 'ajax-load-more-filters' ) . '</div></li>';
		$html .= '<li class="alm-filter--checkbox"><div class="alm-filter--link field-radio" data-type="radio" data-value="design">' . __( 'Unchecked', 'ajax-load-more-filters' ) . '</div></li>';
		$html .= '</div>';

		// Button.
		$html .= '<div class="alm-filters" style="padding: 20px 0 5px; margin: 0; clear: both; min-width: 240px;">';
		$html .= '<div class="alm-filters--submit" style="margin: 0;"><button type="button" class="alm-filters--button" style="margin: 0;">' . apply_filters( 'alm_filters_button_text', __( 'Submit', 'ajax-load-more-filters' ) ) . '</button></div>';
		$html .= '</div>';

	$html .= '</div>';

	echo $html; // phpcs:ignore
	?>

	<script>
		// Filter Preview
		var colorArrayFilters = "filters-default filters-red filters-green filters-blue";
		jQuery("select#alm_settings_filters_color").change(function() {
			var color = jQuery(this).val();
			jQuery('.ajax-load-more-wrap.alm-filters').removeClass(colorArrayFilters);
			jQuery('.ajax-load-more-wrap.alm-filters').addClass('filters-'+color);
		});
		jQuery("select#alm_settings_filters_color").click(function(e){
			e.preventDefault();
		});

		// Check if Disable CSS  === true
		if(jQuery('input#alm_filters_disable_css_input').is(":checked")){
			jQuery('select#alm_settings_filters_color').parent().parent().hide(); // Hide button color
		}

		// On load
		jQuery('input#alm_filters_disable_css_input').change(function() {
			var el = jQuery(this);
			if(el.is(":checked")) {
				el.parent().parent('tr').next('tr').hide(); // Hide color
			}else{
				el.parent().parent('tr').next('tr').show(); // show color
			}
		});

	</script>
	<?php
}

/**
 * Setting: Set the Flatpickr theme.
 * alm_filters_disable_css_callback
 *
 * @since 1.8.0
 */
function alm_filters_flatpickr_theme_callback() {
	$options = get_option( 'alm_settings' );
	if ( ! isset( $options['_alm_filters_flatpickr_theme'] ) ) {
		$options['_alm_filters_flatpickr_theme'] = 'default';
	}
	$selected = $options['_alm_filters_flatpickr_theme'];

	$themes = [
		[
			'name' => 'Default',
			'slug' => 'default',
		],
		[
			'name' => 'AirBnB',
			'slug' => 'airbnb',
		],
		[
			'name' => 'Confetti',
			'slug' => 'confetti',
		],
		[
			'name' => 'Dark',
			'slug' => 'dark',
		],
		[
			'name' => 'Light',
			'slug' => 'light',
		],
		[
			'name' => 'Material Blue',
			'slug' => 'material_blue',
		],
		[
			'name' => 'Material Green',
			'slug' => 'material_green',
		],
		[
			'name' => 'Material Orange',
			'slug' => 'material_orange',
		],
		[
			'name' => 'Material Red',
			'slug' => 'material_red',
		],
	];

	$html  = '<label for="_alm_filters_flatpickr_theme">';
	$html .= __( 'Select a <a href="https://flatpickr.js.org/themes/" target="blank">Theme</a> for the Datepicker Field Type.', 'ajax-load-more-filters' );
	$html .= '</label>';

	$html .= '<select id="_alm_filters_flatpickr_theme" name="alm_settings[_alm_filters_flatpickr_theme]">';
	foreach ( $themes as $theme ) {
		$select_text = ( $selected === $theme['slug'] ) ? ' selected="selected"' : '';
		$html       .= '<option value="' . $theme['slug'] . '"' . $select_text . '>' . $theme['name'] . '</option>';
	}
	$html .= '</select>';

	echo $html; // phpcs:ignore
}

new ALM_Filters_Settings();
