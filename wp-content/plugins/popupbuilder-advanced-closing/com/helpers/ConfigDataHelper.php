<?php
namespace sgpbadvancedclosing;

class ConfigDataHelper
{
	public static function defaultData()
	{
		$data = array();

		$data['countdownPositions'] = array(
			'topRight' => __('top-right', SG_POPUP_TEXT_DOMAIN),
			'topLeft' => __('top-left', SG_POPUP_TEXT_DOMAIN),
			'bottomRight' => __('bottom-right', SG_POPUP_TEXT_DOMAIN),
			'bottomLeft' => __('bottom-left', SG_POPUP_TEXT_DOMAIN)
		);

		return $data;
	}
}