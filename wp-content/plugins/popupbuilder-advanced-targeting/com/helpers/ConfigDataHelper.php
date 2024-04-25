<?php
namespace sgpbadvancedtargeting;

class ConfigDataHelper
{
	public static function defaultData()
	{
		$data = array();
		$data['conditionsGeoTargeting'] = array(
			'countries' => __('Countries', SG_POPUP_TEXT_DOMAIN),
			'cities' => __('Cities', SG_POPUP_TEXT_DOMAIN),
			'states' => __('States', SG_POPUP_TEXT_DOMAIN),
			'visitor-ip' => __('Visitor IP', SG_POPUP_TEXT_DOMAIN)
		);

		return $data;
	}
}
