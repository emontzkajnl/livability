<?php

class Advanced_Ads_Pro_Module_Ads_For_Adblockers_Admin {
	public function __construct() {
		add_action( 'advanced-ads-settings-init', [ $this, 'settings_init'], 10, 1 );

		$options = Advanced_Ads_Pro::get_instance()->get_options();
		if ( empty( $options['ads-for-adblockers']['enabled'] ) ) {
			return;
		}

		add_action( 'advanced-ads-placement-options-after-advanced', [ $this, 'add_placement_setting' ], 10, 2 );
		add_filter( 'advanced-ads-import-placement', [ $this, 'import_placement' ], 10, 2 );
	}

	public function settings_init($hook) {
		$admin = Advanced_Ads_Admin::get_instance();
		$hook  = $admin->plugin_screen_hook_suffix;

		// add new section
		add_settings_field(
			'module-ads-for-adblockers',
			__( 'Ads for ad blockers', 'advanced-ads-pro' ),
			[ $this, 'render_settings' ],
			Advanced_Ads_Pro::OPTION_KEY . '-settings',
			Advanced_Ads_Pro::OPTION_KEY . '_modules-enable'
		);
	}

	public function render_settings() {
		$options           = Advanced_Ads_Pro::get_instance()->get_options();
		$module_enabled    = isset( $options['ads-for-adblockers']['enabled'] ) && $options['ads-for-adblockers']['enabled'];
		$cb_dashicon_class = ! empty( $options['cache-busting']['enabled'] ) ? 'dashicons-yes advads-color-green' : 'dashicons-no color-red';
		$ab_dashicon_class = ! empty( Advanced_Ads::get_instance()->options()['use-adblocker'] ) ? 'dashicons-yes advads-color-green' : 'dashicons-no color-red';
		include_once dirname( __FILE__ ) . '/views/settings.php';
	}

	/**
	 * Render alternative item option.
	 *
	 * @param string $_placement_slug
	 * @param array $_placement
	 */
	public function add_placement_setting( $_placement_slug, $_placement ) {
		$placement_types = Advanced_Ads_Placements::get_placement_types();
		if ( isset( $placement_types[ $_placement['type'] ]['options']['placement-item-alternative'] ) && ! $placement_types[ $_placement['type'] ]['options']['placement-item-alternative'] ) {
			return;
		}

		$options = Advanced_Ads_Pro::get_instance()->get_options();
		$items = $this->items_for_select();
		$messages = $this->get_messages( $_placement );
		$cb_off = empty( $options['cache-busting']['enabled'] ) || ( isset( $_placement['options']['cache-busting'] ) && $_placement['options']['cache-busting'] === Advanced_Ads_Pro_Module_Cache_Busting::OPTION_OFF );

		ob_start();
		include dirname( __FILE__ ) . '/views/placement-item.php';
		$item_option_content = ob_get_clean();

		if ( ! class_exists( 'Advanced_Ads_Admin_Options' ) ){
			echo esc_html__('Please update to Advanced Ads 1.8', 'advanced-ads-pro');
			return;
		}

		$ad_blocker_description = sprintf(
			'%1s. %2s (<a href="%3s" target="_blank">%4s</a>)',
			__( 'Displayed to visitors with an ad blocker', 'advanced-ads-pro' ),
			__( 'Cache Busting and Ad blocker fix need to be enabled', 'advanced-ads-pro' ),
			esc_url( get_admin_url('/','admin.php?page=advanced-ads-settings#top#pro') ),
			__( 'Settings', 'advanced-ads-pro' )
		);

		Advanced_Ads_Admin_Options::render_option(
			'placement-item-alternative',
			__( 'Ad blocker item', 'advanced-ads-pro' ),
			$item_option_content,
			$ad_blocker_description
		);
	}

	/**
	 * Get items for item select field.
	 *
	 * @return array $select Items for select field.
	 */
	private function items_for_select() {
		static $select = null;

		// Check if result was cached.
		if ( $select !== null ) {
			return $select;
		}

		$select = [];
		$model = Advanced_Ads::get_instance()->get_model();

		// Load all ads.
		$ads = $model->get_ads( [ 'orderby' => 'title', 'order' => 'ASC'] );
		foreach ( $ads as $_ad ) {
			$ad = new Advanced_Ads_Ad( $_ad->ID );
			if ( in_array( $ad->type, [ 'plain', 'content', 'image' ] ) ) {
				$select['ads']['id_' . $_ad->ID] = $_ad->post_title;
			}
		}

		return $select;
	}

	/**
	 * Get messages related to selected alternative item.
	 *
	 * @param array $_placement
	 * @return array $messages Array of strings.
	 */
	private function get_messages( $_placement ) {
		$messages = [];

		if ( ! empty( $_placement['options'] ) ) {
			$ad = Advanced_Ads_Pro_Module_Ads_For_Adblockers::get_ad_for_adblocker( $_placement['options'] );
			if ( $ad ) {
				$content = $ad->prepare_frontend_output();

				if ( preg_match( '/<script[^>]+src=[\'"]/is', $content ) ) {
					$messages[] .= __( 'The chosen ad contains a reference to an external .js file', 'advanced-ads-pro' );
				}
			}
		}

		return $messages;
	}

	/**
	 * Set an ad for adblocker during the import of a placement.
	 *
	 * @param array $placement
	 * @param obj Advanced_Ads_Import
	 * @return array $placement
	 */
	public function import_placement( $placement, Advanced_Ads_Import $import ) {
		if ( ! empty ( $placement['options']['item_adblocker'] ) ) {
			$_item = explode( '_', $placement['options']['item_adblocker'] );
			if ( ! empty( $_item[1] ) ) {
				$found = $import->search_item( $_item[1], 'id' );
				$placement['options']['item_adblocker'] = $found ? 'id_' . $found : '';
			}
		}
		return $placement;
	}
}
