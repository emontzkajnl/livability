<?php
/**
 * Assets manager handles the registration of stylesheets and scripts required for plugin functionality.
 *
 * @package AdvancedAds
 * @author  Advanced Ads <info@wpadvancedads.com>
 */

namespace AdvancedAds\Pro;

use AdvancedAds\Framework\Assets_Registry;

/**
 * Pro's assets manager
 */
class Assets_Manager extends Assets_Registry {
	/**
	 * Base URL for plugin local assets.
	 *
	 * @return string
	 */
	public function get_base_url(): string {
		return AA_PRO_BASE_URL;
	}

	/**
	 * Prefix to use in handle to make it unique.
	 *
	 * @return string
	 */
	public function get_prefix(): string {
		return AA_PRO_SLUG;
	}

	/**
	 * Version for plugin local assets.
	 *
	 * @return string
	 */
	public function get_version(): string {
		return AAP_VERSION;
	}

	/**
	 * Register assets
	 *
	 * @return void
	 */
	public function register_assets(): void {
	}
}
