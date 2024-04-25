<?php
namespace sgpbpdf;

class ConfigDataHelper
{
	public static function defaultData()
	{
		$data = array();

		$data['zoomLevel'] = array(
			'automatic' => __('Automatic', SG_POPUP_TEXT_DOMAIN),
			'page-width' => __('Full Width', SG_POPUP_TEXT_DOMAIN),
			'0.25' => '25%',
			'0.5' => '50%',
			'0.75' => '75%',
			'1.25' => '125%',
			'1.5' => '150%',
			'2' => '200%'
		);

		return $data;
	}
}
