<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Dashboard helper class
 *
 * @package     AdvancedAds\Pro
 * @author      Advanced Ads <info@wpadvancedads.com>
 */

use AdvancedAds\Utilities\Conditional;
use AdvancedAds\Framework\Utilities\Params;
use GeoIp2\Exception\AddressNotFoundException;

/**
 * WP Admin class for Geo Targeting.
 */
class Advanced_Ads_Geo_Admin {
	/**
	 * Path to view files
	 *
	 * @var string
	 */
	private $views_path;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {
		$this->views_path = plugin_dir_path( __FILE__ ) . '../admin/views';

		add_action( 'advanced-ads-settings-init', [ $this, 'settings_init' ] );

		// ajax request to download the database.
		add_action( 'wp_ajax_advads_download_geolite_database', [ $this, 'download_database' ] );

		// Add assets.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
	}

	/**
	 * Add settings to settings page
	 */
	public function settings_init() {
		// add new section.
		add_settings_section(
			'advanced_ads_geo_setting_section',
			__( 'Geo Targeting', 'advanced-ads-pro' ),
			'__return_empty_string',
			Advanced_Ads_Pro::OPTION_KEY . '-settings'
		);

		// Add license key field.
		add_settings_field(
			'geo-maxmind-license-key',
			__( 'MaxMind license key', 'advanced-ads-pro' ),
			[ $this, 'render_settings_license_key_callback' ],
			Advanced_Ads_Pro::OPTION_KEY . '-settings',
			'advanced_ads_geo_setting_section'
		);

		// Add database field.
		add_settings_field(
			'geo-database-update',
			__( 'MaxMind Database', 'advanced-ads-pro' ),
			[ $this, 'render_settings_database' ],
			Advanced_Ads_Pro::OPTION_KEY . '-settings',
			'advanced_ads_geo_setting_section'
		);

		// add field for the targeting method.
		if ( count( Advanced_Ads_Geo_Plugin::get_instance()->get_targeting_methods() ) > 1 ) {
			add_settings_field(
				'geo-license',
				__( 'Method', 'advanced-ads-pro' ),
				[ $this, 'render_settings_method_callback' ],
				Advanced_Ads_Pro::OPTION_KEY . '-settings',
				'advanced_ads_geo_setting_section'
			);
		}

		// add assistant setting field.
		add_settings_field(
			'geo-locale',
			__( 'Language of names', 'advanced-ads-pro' ),
			[ $this, 'render_settings_locale_option_callback' ],
			Advanced_Ads_Pro::OPTION_KEY . '-settings',
			'advanced_ads_geo_setting_section'
		);
	}

	/**
	 * Render MaxMind license key field.
	 */
	public function render_settings_license_key_callback() {
		$use_filters = $this->is_using_custom_database();
		$license_key = Advanced_Ads_Geo_Plugin::get_instance()->options( 'maxmind-license-key', '' );

		include $this->views_path . '/setting-maxmind-license-key.php';
	}

	/**
	 * Render MaxMind database field.
	 *
	 * @return void
	 */
	public function render_settings_database() {
		$last_update = get_option( ADVADS_SLUG . '-' . Advanced_Ads_Geo_Plugin::OPTIONS_SLUG . '-last-update-geolite2', false );
		$next_update = $this->get_next_first_tuesday_timestamp();

		// Check if the database files exist and do not contain errors.
		$api               = Advanced_Ads_Geo_Api::get_instance();
		$correct_databases = $api->get_geo_ip2_city_reader() && $api->get_geo_ip2_country_reader();
		$use_filters       = $this->is_using_custom_database();

		// Render download of the geo database.
		include $this->views_path . '/setting-download.php';
	}

	/**
	 * Render option for the geo targeting method
	 */
	public function render_settings_method_callback() {
		$method  = Advanced_Ads_Geo_Plugin::get_instance()->options( 'method', 'default' );
		$methods = Advanced_Ads_Geo_Plugin::get_instance()->get_targeting_methods();

		include $this->views_path . '/setting-method.php';
	}

	/**
	 * Render option for language of the geo information
	 */
	public function render_settings_locale_option_callback() {
		$locale = Advanced_Ads_Geo_Plugin::get_instance()->options( 'locale', 'en' );

		include $this->views_path . '/setting-locale.php';
	}

	/**
	 * Allow static method call as visitor condition callback.
	 *
	 * @param string $name      method name.
	 * @param mixed  $arguments functions arguments.
	 */
	public static function __callStatic( $name, $arguments ) {
		if ( 'metabox_geo' === $name ) {
			( new self() )->metabox_geo( ...$arguments );
		}
		// else fail silently.
	}

	/**
	 * Add visitor condition box
	 *
	 * @param array  $options   ad options.
	 * @param int    $index     current index in conditions list.
	 * @param string $form_name form name of current condition.
	 *
	 * @return void
	 * @throws \MaxMind\Db\Reader\InvalidDatabaseException Corrupted DB files.
	 */
	private function metabox_geo( $options, $index = 0, $form_name = '' ) {
		if ( empty( $options['type'] ) ) {
			return;
		}

		$type_options = Advanced_Ads_Visitor_Conditions::get_instance()->conditions;

		if ( ! isset( $type_options[ $options['type'] ] ) ) {
			return;
		}

		$method    = Advanced_Ads_Geo_Plugin::get_current_targeting_method();
		$countries = Advanced_Ads_Geo_Api::get_countries();

		// set defaults for all variables.
		$my_country          = '';
		$my_country_iso_code = '';
		$my_city             = '';
		$my_region           = '';
		$my_lat              = 0.0;
		$my_lon              = 0.0;

		switch ( $method ) {
			case 'sucuri':
				$my_country_iso_code = Advanced_Ads_Geo_Plugin::get_sucuri_country();
				$my_country          = $countries[ $my_country_iso_code ] ?? ' – ';
				$current_location    = sprintf( '%s (%s)', $my_country, $my_country_iso_code );
				break;
			default:
				// get information from the current user to help him debugging issues.
				$api   = Advanced_Ads_Geo_Api::get_instance();
				$ip    = $api->get_real_ip_address();
				$error = false;

				// get locale.
				$locale = Advanced_Ads_Geo_Plugin::get_instance()->options( 'locale', 'en' );

				if ( $ip ) {
					try {
						$reader = $api->get_geo_ip2_city_reader();

						if ( $reader ) {
							// Look up the IP address.
							$record = $reader->city( $ip );
							if ( ! empty( $record ) ) {
								$my_city = ( $record->city->name ) ? $record->city->name : __( '(unknown city)', 'advanced-ads-pro' );
								if ( isset( $record->city->names[ $locale ] ) && $record->city->names[ $locale ] ) {
									$my_city = $record->city->names[ $locale ];
								}

								$my_country          = ( isset( $record->country->names[ $locale ] ) && $record->country->names[ $locale ] )
									? $record->country->names[ $locale ]
									: $record->country->names['en'];
								$my_country_iso_code = $record->country->isoCode;

								// get first subdivision (region/state).
								$my_region = ( isset( $record->subdivisions[0] ) && $record->subdivisions[0]->name ) ? $record->subdivisions[0]->name : __( '(unknown region)', 'advanced-ads-pro' );
								if ( isset( $record->subdivisions[0] ) && isset( $record->subdivisions[0]->names[ $locale ] ) && $record->subdivisions[0]->names[ $locale ] ) {
									$my_region = $record->subdivisions[0]->names[ $locale ];
								}
								if ( isset( $record->location ) && isset( $record->location->latitude ) && isset( $record->location->longitude ) ) {
									$my_lat = $record->location->latitude;
									$my_lon = $record->location->longitude;
								}
							}
						} else {
							$error = sprintf(
								'%1$s %2$s',
								__( 'Geo Databases not found.', 'advanced-ads-pro' ),
								sprintf(
									/* translators: 1: opening <a>-tag to Advanced Ads manual, 2: closing <a>-tag */
									__( 'Please read the %1$sinstallation instructions%2$s.', 'advanced-ads-pro' ),
									'<a href="https://wpadvancedads.com/manual/geo-targeting-condition/#Enabling_Geo-Targeting" target="_blank">',
									'</a>'
								)
							);
						}
					} catch ( AddressNotFoundException $e ) {
						$error = $e->getMessage() . ' ' . __( 'Maybe you are working on a local or secured environment.', 'advanced-ads-pro' );
					}
				} else {
					$raw_ip = $api->get_raw_ip_address();
					$error  = '<span class="advads-notice-inline advads-error">' . __( 'Your IP address format is incorrect', 'advanced-ads-pro' ) . ' (' . $raw_ip . ')</span>';
				}

				if ( $error ) {
					$current_location = '<span class="advads-notice-inline advads-error">' . $error . '</span>';
				} else {
					$current_location  = $ip . ', ' . $my_country . ', ' . $my_region . ', ' . $my_city;
					$current_location .= '<br/>' . __( 'Coordinates', 'advanced-ads-pro' ) . ': (' . $my_lat . ' / ' . $my_lon . ')';
				}
		}

		// form name basis.
		if ( method_exists( 'Advanced_Ads_Visitor_Conditions', 'get_form_name_with_index' ) ) {
			$name = Advanced_Ads_Visitor_Conditions::get_form_name_with_index( $form_name, $index );
		} else {
			$name = Advanced_Ads_Visitor_Conditions::FORM_NAME . '[' . $index . ']';
		}

		$operator = isset( $options['operator'] ) ? $options['operator'] : 'is';
		$geo_mode = isset( $options['geo_mode'] ) ? $options['geo_mode'] : 'classic';
		?>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>[type]" value="<?php echo esc_attr( $options['type'] ); ?>"/>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>[value]" value="1"/>

		<style type="text/css">
			span.geomode {
				display: inline-block;
				clear: both;
				margin: 0px 0px 5px 0px;
			}

			span.geomode input[type=radio] {
				display: block;
				float: left;
				margin: 0px 5px 0px 5px;
			}
		</style>

		<div style="margin-bottom: 10pt;">
			<span class="geomode">
				<?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<input id="radio_classic_<?php echo esc_attr( $index ); ?>" type="radio" name="<?php echo esc_attr( $name ); ?>[geo_mode]" <?php checked( $geo_mode, 'classic' ); ?> value="classic" onclick="advads_geo_admin.set_mode(<?php echo $index; ?>, this.value);">
				<?php // phpcs:enable ?>
				<label for="radio_classic_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'by specific location', 'advanced-ads-pro' ); ?></label>
			</span>
			<span class="geomode">
				<?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<input class="geomode" id="radio_latlon_<?php echo esc_attr( $index ); ?>" type="radio" name="<?php echo esc_attr( $name ); ?>[geo_mode]" <?php checked( $geo_mode, 'latlon' ); ?> value="latlon" onclick="advads_geo_admin.set_mode(<?php echo $index; ?>, this.value);">
				<?php // phpcs:enable ?>
				<label for="radio_latlon_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'by radius', 'advanced-ads-pro' ); ?></label>
			</span>
		</div>


		<div class="geomode" id="advads_geo_classic_<?php echo esc_attr( $index ); ?>"
			<?php
			if ( 'classic' !== $geo_mode ) {
				echo ' style="display:none;"';
			}
			?>
		>
			<select name="<?php echo esc_attr( $name ); ?>[operator]">
				<option value="is" <?php selected( 'is', $operator ); ?>><?php esc_html_e( 'is', 'advanced-ads-pro' ); ?></option>
				<option value="is_not" <?php selected( 'is_not', $operator ); ?>><?php esc_html_e( 'is not', 'advanced-ads-pro' ); ?></option>
			</select>
			<?php

			switch ( $method ) :
				case 'sucuri':
					$country = isset( $options['country'] ) ? $options['country'] : $my_country_iso_code;
					?>
					<div class="advads-conditions-select-wrap"><select name="<?php echo esc_attr( $name ); ?>[country]"><?php foreach ( $countries as $_code => $_title ) : ?>
								<option value="<?php echo esc_attr( $_code ); ?>" <?php selected( $_code, $country ); ?>><?php echo esc_html( $_title ); ?></option>
							<?php
							endforeach;
																				?>
						</select></div>
					<?php
					break;
				default:
					$country = isset( $options['country'] ) ? $options['country'] : $my_country_iso_code;
					$region  = isset( $options['region'] ) ? $options['region'] : '';
					$city    = isset( $options['city'] ) ? $options['city'] : '';
					?>
					<div class="advads-conditions-select-wrap"><select name="<?php echo esc_attr( $name ); ?>[country]"><?php foreach ( $countries as $_code => $_title ) : ?>
								<option value="<?php echo esc_attr( $_code ); ?>" <?php selected( $_code, $country ); ?>><?php echo esc_html( $_title ); ?></option>
							<?php endforeach; ?>
						</select></div> <?php esc_html_e( 'or', 'advanced-ads-pro' ); ?>
					<input type="text" name="<?php echo esc_attr( $name ); ?>[region]" value="<?php echo esc_attr( $region ); ?>" placeholder="<?php esc_html_e( 'State/Region', 'advanced-ads-pro' ); ?>"/>
					<?php esc_html_e( 'or', 'advanced-ads-pro' ); ?>
					<input type="text" name="<?php echo esc_attr( $name ); ?>[city]" value="<?php echo esc_attr( $city ); ?>" placeholder="<?php esc_html_e( 'City', 'advanced-ads-pro' ); ?>"/>
					<?php
					break;
			endswitch;
			?>
		</div>
		<div id="advads_geo_latlon_<?php echo esc_attr( $index ); ?>"
			<?php
			if ( 'latlon' !== $geo_mode ) {
				echo ' style="display:none;"';
			}
			?>
		>
			<?php
			switch ( $method ) :
				case 'sucuri':
					esc_html_e( 'This option can’t be used if your site is using sucuri.net.', 'advanced-ads-pro' );
					break;
				default:
					$lat                = isset( $options['lat'] ) ? $options['lat'] : '';
					$lon                = isset( $options['lon'] ) ? $options['lon'] : '';
					$distance           = isset( $options['distance'] ) ? $options['distance'] : '';
					$distance_condition = isset( $options['distance_condition'] ) ? $options['distance_condition'] : '';
					$distance_unit      = isset( $options['distance_unit'] ) ? $options['distance_unit'] : '';
					?>
					<select name="<?php echo esc_attr( $name ); ?>[distance_condition]">
						<option value="lte" <?php selected( $distance_condition, 'lte' ); ?>><?php esc_html_e( 'Distance is less than or equal to', 'advanced-ads-pro' ); ?></option>
						<option value="gt" <?php selected( $distance_condition, 'gt' ); ?>><?php esc_html_e( 'Distance is greater than', 'advanced-ads-pro' ); ?></option>
					</select>
					<input type="number" name="<?php echo esc_attr( $name ); ?>[distance]" maxlength="8" style="width:80pt;" value="<?php echo esc_attr( $distance ); ?>" placeholder="<?php esc_attr_e( 'Distance', 'advanced-ads-pro' ); ?>"/>
					<select name="<?php echo esc_attr( $name ); ?>[distance_unit]">
						<option value="km" <?php selected( $distance_unit, 'km' ); ?>><?php esc_html_e( 'kilometers (km)', 'advanced-ads-pro' ); ?></option>
						<option value="mi" <?php selected( $distance_unit, 'mi' ); ?>><?php esc_html_e( 'miles (mi)', 'advanced-ads-pro' ); ?></option>
					</select><br/><?php esc_html_e( 'from', 'advanced-ads-pro' ); ?><br/>
					<?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<button type="button" class="button" onclick="advads_geo_admin.click_locname(<?php echo $index; ?>)"><?php esc_html_e( 'get coordinates', 'advanced-ads-pro' ); ?></button>
					<?php // phpcs:enable ?>
					<input type="number" step="any" id="advads_geo_input_lat_<?php echo esc_attr( $index ); ?>" style="width:80pt; text-align:right;" name="<?php echo esc_attr( $name ); ?>[lat]" value="<?php echo esc_attr( $lat ); ?>" placeholder="<?php esc_attr_e( 'Latitude', 'advanced-ads-pro' ); ?>" title="<?php esc_attr_e( 'Latitude', 'advanced-ads-pro' ); ?>"/> /
					<input type="number" step="any" id="advads_geo_input_lon_<?php echo esc_attr( $index ); ?>" style="width:80pt; text-align:right;" name="<?php echo esc_attr( $name ); ?>[lon]" value="<?php echo esc_attr( $lon ); ?>" placeholder="<?php esc_attr_e( 'Longitude', 'advanced-ads-pro' ); ?>" title="<?php esc_attr_e( 'Longitude', 'advanced-ads-pro' ); ?>"/>
					<br>
					<?php
					if ( '' !== $lat && '' !== $lon && $my_lat && $my_lon ) {
						$distance          = Advanced_Ads_Geo::calculate_distance( $my_lat, $my_lon, $lat, $lon, $distance_unit );
						$current_location .= '<br/>' . esc_html__( 'Distance to center', 'advanced-ads-pro' ) . ' ( ' . $lat . ' / ' . $lon . ' ): ' . round( $distance, 1 ) . ' ' . $distance_unit;
					}
					break;
			endswitch;
			?>
		</div>
		<?php
		$latlon_city = isset( $options['latlon_city'] ) ? $options['latlon_city'] : '';
		?>
		<div id="advads_geo_latlon_by_city_<?php echo esc_attr( $index ); ?>" style="display: none; margin:3pt; padding:3pt;">
			<input id="advads_geo_input_search_city_<?php echo esc_attr( $index ); ?>" type="text" name="<?php echo esc_attr( $name ); ?>[latlon_city]" value="<?php echo esc_attr( $latlon_city ); ?>" placeholder="<?php esc_attr_e( 'City', 'advanced-ads-pro' ); ?>">
			<?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<button type="button" class="button button-primary" onclick="advads_geo_admin.search_loc(<?php echo $index; ?>)"><?php esc_html_e( 'Search', 'advanced-ads-pro' ); ?></button>
			<button type="button" class="button" onclick="advads_geo_admin.search_loc_close(<?php echo $index; ?>)"><?php esc_html_e( 'Cancel', 'advanced-ads-pro' ); ?></button>
			<?php // phpcs:enable ?>
			<p class="description"><?php esc_html_e( 'Enter the name of the city, click the search button and pick one of the results to set the coordinates of the center.', 'advanced-ads-pro' ); ?></p>
			<div id="advads_geo_latlon_loading_<?php echo esc_attr( $index ); ?>" class="spinner is-active" style="display:none; float: none;"></div>
			<div id="advads_geo_latlon_results_<?php echo esc_attr( $index ); ?>"></div>
		</div>
		<p class="description">
			<?php echo esc_html( $type_options[ $options['type'] ]['description'] ); ?>
			<a href="https://wpadvancedads.com/manual/geo-targeting-condition/?utm_source=advanced-ads&utm_medium=link&utm_campaign=condition-geo-location" class="advads-manual-link" target="_blank">
				<?php esc_html_e( 'Manual', 'advanced-ads-pro' ); ?>
			</a>
		</p>
		<div class="description">
			<p>
				<strong><?php esc_html_e( 'Location based on your IP address', 'advanced-ads-pro' ); ?>:</strong>
				<br>
				<?php
				echo wp_kses(
					$current_location,
					[
						'br'   => [],
						'span' => [ 'class' => true ],
						'a'    => [
							'href'   => true,
							'target' => true,
						],
					]
				);
				?>
			</p>
			<?php $this->render_visitor_profile_information( $my_lat, $my_lon ); ?>
		</div>
		<?php
	}

	/**
	 * Download database
	 *
	 * @since 1.0.0
	 */
	public function download_database() {
		check_ajax_referer( 'advanced-ads-admin-ajax-nonce', 'nonce' );

		if ( ! Conditional::user_can( 'advanced_ads_manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this.', 'advanced-ads-pro' ), 400 );
		}

		$upload_full = Advanced_Ads_Geo_Plugin::get_instance()->get_upload_full();

		if ( ! $upload_full ) {
			wp_send_json_error( __( 'The upload dir is not available', 'advanced-ads-pro' ), 400 );
		}

		$license_key = sanitize_text_field( wp_unslash( Params::request( 'license_key', '' ) ) );

		if ( empty( $license_key ) ) {
			wp_send_json_error( __( 'Please provide a MaxMind license key', 'advanced-ads-pro' ), 400 );
		}

		$file_prefix = Advanced_Ads_Geo_Plugin::get_maxmind_file_prefix();

		if ( '' === $file_prefix ) {
			$file_prefix = $this->create_new_prefix();
			$this->remove_obsolete_files();
		}

		// download source.
		$download_urls = [
			'city'    => 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&suffix=tar.gz&license_key=' . $license_key,
			'country' => 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&suffix=tar.gz&license_key=' . $license_key,
		];

		// Paths where we will save files.
		$filepaths = [
			'city'    => $upload_full . $file_prefix . '-GeoLite2-City.mmdb',
			'country' => $upload_full . $file_prefix . '-GeoLite2-Country.mmdb',
		];

		// Names in the `tar.gz` archives.
		$filenames_in_archive = [
			'city'    => 'GeoLite2-City.mmdb',
			'country' => 'GeoLite2-Country.mmdb',
		];

		// create upload directory if not exists yet.
		if ( ! file_exists( $upload_full ) ) {
			// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
			mkdir( $upload_full );
			// phpcs:enable
		}

		// Prevent directory listing.
		if ( ! file_exists( $upload_full . 'index.html' ) ) {
			// phpcs:disable WordPress.WP.AlternativeFunctions,WordPress.PHP.NoSilencedErrors.Discouraged
			$handle = @fopen( $upload_full . 'index.html', 'w' );
			fwrite( $handle, '' );
			fclose( $handle );
			// phpcs:enable
		}

		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			wp_raise_memory_limit();
		}

		$this->maybe_replace_tmp_dir();

		foreach ( $download_urls as $key => $download_url ) {
			// variable with the name of the database file to download.
			$db_file  = $filepaths[ $key ];
			$filename = $filenames_in_archive[ $key ];
			$result   = $this->download_geolite2_database( $download_url, $db_file, $filename );
			if ( ! isset( $result['state'] ) || ! $result['state'] ) {
				wp_send_json_error( $result['message'], 400 );
			}
		}

		// save geo options on db update.
		$pro_plugin  = Advanced_Ads_Pro::get_instance();
		$pro_options = $pro_plugin->get_options();
		if ( ! array_key_exists( Advanced_Ads_Geo_Plugin::OPTIONS_SLUG, $pro_options ) ) {
			$pro_options[ Advanced_Ads_Geo_Plugin::OPTIONS_SLUG ] = [];
		}
		$pro_options[ Advanced_Ads_Geo_Plugin::OPTIONS_SLUG ]['maxmind-license-key'] = $license_key;
		$pro_options[ Advanced_Ads_Geo_Plugin::OPTIONS_SLUG ]['locale']              = sanitize_text_field( wp_unslash( Params::request( 'locale', '' ) ) );
		$pro_plugin->update_options( $pro_options );

		update_option( ADVADS_SLUG . '-' . Advanced_Ads_Geo_Plugin::OPTIONS_SLUG . '-last-update-geolite2', time() );
		wp_send_json_success( __( 'Database updated successfully!', 'advanced-ads-pro' ) );
	}

	/**
	 * Download GeoLite2 databases
	 *
	 * @param string $download_url The download url.
	 * @param string $db_file      The target file.
	 * @param string $filename     The target filename in the archive.
	 *
	 * @return array
	 */
	private function download_geolite2_database( $download_url, $db_file, $filename ) {

		// download the file from MaxMind, this places into temporary location.
		$temp_file = download_url( $download_url );

		$result['state']   = false;
		$result['message'] = __( 'Database update failed', 'advanced-ads-pro' );

		// If we failed, through a message, otherwise proceed.
		if ( is_wp_error( $temp_file ) ) {
			$error_code = $temp_file->get_error_data()['code'] ?? '';
			$error_body = $temp_file->get_error_data()['body'] ?? '';

			$result['message'] = sprintf(
				/* translators: 1: The download URL, 2: The HTTP error code, 3: The HTTP header title, 4: The response body */
				__( 'Error downloading database from: %1$s - %2$s %3$s - %4$s', 'advanced-ads-pro' ),
				wp_parse_url( $download_url, PHP_URL_HOST ), // Do not expose the license key from query string.
				$error_code,
				$temp_file->get_error_message(),
				$error_body
			);

			error_log( 'Advanced Ads Geo: ' . $result['message'] ); // phpcs:ignore
		} else {
			// The dir where the archive was downloaded.
			$temp_file_dir = dirname( $temp_file );

			try {
				$archive = new PharData( $temp_file );

				// The dir extracted from the archive.
				$extracted_dir = trailingslashit( $archive->current()->getFilename() );
				// Full path to the dir extracted from the archive.
				$extracted_dir_full = trailingslashit( $temp_file_dir ) . $extracted_dir;

				$archive->extractTo(
					$temp_file_dir,
					$extracted_dir . $filename,
					true
				);
			} catch ( Exception $e ) {
				$result['message'] = sprintf(
					/* translators: an error mesage. */
					esc_html__( 'Could not open downloaded database for reading: %s', 'advanced-ads-pro' ),
					$temp_file . ', ' . $e->getMessage()
				);

				error_log( 'Advanced Ads Geo: ' . $result['message'] ); // phpcs:ignore

				return $result;
			} finally {
				wp_delete_file( $temp_file );
			}

			add_filter( 'filesystem_method', [ $this, 'set_direct_filesystem_method' ] );
			WP_Filesystem();
			global $wp_filesystem;

			if ( ! isset( $wp_filesystem->method ) || 'direct' !== $wp_filesystem->method ) {
				$result['message'] = __( 'Could not access filesystem', 'advanced-ads-pro' );

				error_log( 'Advanced Ads Geo: ' . $result['message'] ); // phpcs:ignore

				return $result;
			}

			// Move the file.
			$renamed = $wp_filesystem->move( $extracted_dir_full . $filename, $db_file, true );
			$wp_filesystem->delete( $extracted_dir_full, false, 'd' );

			if ( ! $renamed ) {
				/* translators: MaxMind database file name. */
				$result['message'] = sprintf( __( 'Could not open database for writing %s', 'advanced-ads-pro' ), $db_file );
				// Remove corrupted file.
				$wp_filesystem->delete( $db_file, false, 'f' );
			} else {
				$result['message'] = '';
				$result['state']   = true;
			}
			remove_filter( 'filesystem_method', [ $this, 'set_direct_filesystem_method' ] );
		}

		return $result;
	}

	/**
	 * Get timestamp of the next first Tuesday of a month (either this month or the next)
	 *
	 * @param string $time timestamp from which to get the next available Tuesday.
	 *
	 * @return int of the next Tuesday (midnight GMT)
	 * @since 1.0.0
	 */
	public function get_next_first_tuesday_timestamp( $time = 0 ) {

		// we actually use Wednesday since it returns midnight.
		if ( ! $time ) {
			$time = time();
		}

		// current month.
		// phpcs:disable WordPress.DateTime.RestrictedFunctions.date_date
		$month          = date( 'F', $time );
		$year           = date( 'Y', $time );
		$next_tuesday_t = strtotime( "first Wednesday of $month $year" );

		// if this is the past, get first Tuesday of next month.
		if ( $next_tuesday_t < time() ) {
			$next_month_t   = strtotime( 'next month' );
			$month          = date( 'F', $next_month_t );
			$year           = date( 'Y', $next_month_t );
			$next_tuesday_t = strtotime( "first Tuesday of $month $year" );
		}
		// phpcs:enable

		return $next_tuesday_t;
	}

	/**
	 * Check if the databases are working
	 */
	public function check_database() {
		$api = Advanced_Ads_Geo_Api::get_instance();

		return $api->get_geo_lite_country_filename() && $api->get_geo_lite_city_filename();
	}

	/**
	 * Check if an updated version of the database might already be available
	 *  we use the timestamp of the last update that actually happened and check,
	 * if the next first Tuesday of a month that followed is already in the past
	 *
	 * @return bool true, if update should be available
	 * @since 1.0.0
	 */
	public function is_update_available() {
		// check if database is available.
		if ( ! $this->check_database() ) {
			return true;
		}

		// current time.
		$now_t = time();

		// get last update from the database.
		$last_update_t = get_option( ADVADS_SLUG . '-' . Advanced_Ads_Geo_Plugin::OPTIONS_SLUG . '-last-update-geolite2', $now_t );
		// return true, if last update is more than 31 days ago.
		$month_in_seconds = 31 * DAY_IN_SECONDS;
		if ( $month_in_seconds <= $now_t - $last_update_t ) {
			return true;
		}

		// get next Tuesday following the update.
		$next_tuesday_t = $this->get_next_first_tuesday_timestamp( $last_update_t );

		if ( $next_tuesday_t < $now_t ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if license is valid
	 *
	 * @return bool true if license is valid
	 * @since 1.0.0
	 */
	public function license_valid() {
		$status = Advanced_Ads_Admin_Licenses::get_instance()->get_license_status( 'advanced-ads-geo' );
		if ( 'valid' === $status ) {
			return true;
		}

		return false;
	}

	/**
	 * Register and enqueue admin-specific scripts.
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {
		if ( ! Conditional::is_screen_advanced_ads() ) {
			return;
		}

		$handle = ADVADS_SLUG . '-' . Advanced_Ads_Geo_Plugin::OPTIONS_SLUG . '-admin-script';
		wp_enqueue_script( $handle, plugin_dir_url( __FILE__ ) . '../admin/assets/admin.js', [], AAP_VERSION, true );
		wp_localize_script(
			$handle,
			'advads_geo_translation',
			[
				/* translators: 1: The number of search results. */
				'found_results'           => __( 'Found %1$d results. Please pick the one, you want to use.', 'advanced-ads-pro' ),
				'no_results'              => __( 'Your search did not return any results.', 'advanced-ads-pro' ),
				'could_not_retrieve_city' => __( 'There was an error connecting to the search service.', 'advanced-ads-pro' ),
				/* translators: 1: A link to the geo location service. */
				'manual_geo_search'       => sprintf( __( 'You can search for the geo coordinates manually at %1$s.', 'advanced-ads-pro' ), '<a href="https://nominatim.openstreetmap.org/">nominatim.openstreetmap.org</a>' ),
				'COOKIEPATH'              => COOKIEPATH,
				'COOKIE_DOMAIN'           => COOKIE_DOMAIN,
				'nonce'                   => wp_create_nonce( 'advanced-ads-admin-ajax-nonce' ),
			]
		);
	}

	/**
	 * As per MaxMind TOS, databases should not be puclicly accessible. Create a random prefix for that.
	 */
	private function create_new_prefix() {
		$new_prefix = wp_generate_password( 32, false );

		update_option( 'advanced-ads-geo-maxmind-file-prefix', $new_prefix );

		return $new_prefix;
	}

	/**
	 * Remove files prefixed with an obsolete prefix.
	 */
	public function remove_obsolete_files() {
		require_once ABSPATH . 'wp-admin/includes/file.php';

		add_filter( 'filesystem_method', [ $this, 'set_direct_filesystem_method' ] );
		WP_Filesystem();
		global $wp_filesystem;

		if ( ! isset( $wp_filesystem->method ) || 'direct' !== $wp_filesystem->method ) {
			return;
		}

		$upload_full = Advanced_Ads_Geo_Plugin::get_instance()->get_upload_full();

		if ( ! $upload_full ) {
			return;
		}

		$files = $wp_filesystem->dirlist( $upload_full );
		if ( ! $files ) {
			return;
		}

		foreach ( $files as $file ) {
			if ( preg_match( '/GeoLite2-.*?\.mmdb$/', $file['name'] )
				|| 'tmp-' === substr( $file['name'], 0, 4 )
			) {
				$wp_filesystem->delete( $upload_full . $file['name'] );
			}
		}

		remove_filter( 'filesystem_method', [ $this, 'set_direct_filesystem_method' ] );
	}

	/**
	 * Set direct filesystem method.
	 */
	public function set_direct_filesystem_method() {
		return 'direct';
	}

	/**
	 * Create a new tmp dir inside the upload dir if the existing one does not contain enought space.
	 */
	private function maybe_replace_tmp_dir() {
		if ( defined( 'WP_TEMP_DIR' ) ) {
			return;
		}

		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$available_tmp_space = @disk_free_space( get_temp_dir() );

		// We need > 200 MB.
		if ( ! $available_tmp_space || ( $available_tmp_space / MB_IN_BYTES ) < 200 ) {
			$tmp_dir = Advanced_Ads_Geo_Plugin::get_instance()->get_upload_full()
						. 'tmp-'
						. Advanced_Ads_Geo_Plugin::get_maxmind_file_prefix();

			if ( ! file_exists( $tmp_dir ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir,WordPress.PHP.NoSilencedErrors.Discouraged
				@mkdir( $tmp_dir );
			}

			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			if ( @is_dir( $tmp_dir ) && wp_is_writable( $tmp_dir ) ) {
				define( 'WP_TEMP_DIR', $tmp_dir );
			}
		}
	}

	/**
	 * Render a table with the current visitor profile cookie information if available.
	 *
	 * @param float $lat User's latitude.
	 * @param float $lon User's longitude.
	 *
	 * @return void
	 */
	private function render_visitor_profile_information( $lat, $lon ) {
		$visitor_profile = new Advanced_Ads_Geo_Visitor_Profile();
		if ( $visitor_profile->has_visitor_profile && $visitor_profile->is_different_from_current_location( $lat, $lon ) ) {
			require $this->views_path . '/visitor-profile.php';
		}
	}

	/**
	 * Check if custom database files are used via filters
	 *
	 * @return bool
	 */
	private function is_using_custom_database() {
		$api = Advanced_Ads_Geo_Api::get_instance();

		return has_filter( 'advanced-ads-geo-maxmind-geolite2-country-db-filepath' )
			&& has_filter( 'advanced-ads-geo-maxmind-geolite2-city-db-filepath' )
			&& $api->get_geo_ip2_city_reader()
			&& $api->get_geo_ip2_country_reader();
	}
}
