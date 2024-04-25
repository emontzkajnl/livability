<?php
namespace sgpbgeotargeting;

class ConfigDataHelper
{
	public static function defaultData()
	{
		$data = array();
		$data['isActiveAdvancedTargeting'] = defined('SGPB_POPUP_ADVANCED_TARGETING_EXTENSION_KEY') && is_plugin_active(SGPB_POPUP_ADVANCED_TARGETING_EXTENSION_KEY);
		$data['conditionsAdvancedTargeting'] = array(
			'devices' => __('Devices', SG_POPUP_TEXT_DOMAIN),
			'user-status' => __('User Status', SG_POPUP_TEXT_DOMAIN),
			'after-x' => __('After x pages visit', SG_POPUP_TEXT_DOMAIN),
			'user-role' => __('User Role', SG_POPUP_TEXT_DOMAIN),
			'detect-by-url' => __('Referral URL detection', SG_POPUP_TEXT_DOMAIN),
			'cookie-detection' => __('Cookie Detection', SG_POPUP_TEXT_DOMAIN),
			'operation-system' => __('Operating System', SG_POPUP_TEXT_DOMAIN),
			'browser-detection' => __('Web Browser', SG_POPUP_TEXT_DOMAIN),
			'query-string' => __('URL Query String', SG_POPUP_TEXT_DOMAIN)
		);

		return $data;
	}
}
