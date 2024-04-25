<?php
namespace sgpbadvancedclosing;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbOptionAvailable', array($this, 'filterOption'), 10, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'defaultOptions'), 10, 1);
	}

	public function filterOption($filterOption)
	{
		if (isset($filterOption['name'])) {
			$name = $filterOption['name'];

			if ($name == 'sgpb-auto-close') {
				$filterOption['status'] = true;
			}
			if ($name == 'sgpb-enable-popup-overlay') {
				$filterOption['status'] = true;
			}
			if ($name == 'sgpb-disable-popup-closing') {
				$filterOption['status'] = true;
			}
			if ($name == 'sgpb-close-after-page-scroll') {
				$filterOption['status'] = true;
			}
			
			return $filterOption;
		}

		return $filterOption;
	}

	public function defaultOptions($options)
	{
		$options[] = array('name' => 'sgpb-close-after-page-scroll', 'type' => 'checkbox', 'defaultValue' => '', 'min-version' => SGPB_POPUP_PRO_MIN_VERSION, 'min-pkg' => SGPB_POPUP_PKG_SILVER);
		$options[] = array('name' => 'sgpb-add-closing-countdown', 'type' => 'checkbox', 'defaultValue' => '', 'min-version' => SGPB_POPUP_PRO_MIN_VERSION, 'min-pkg' => SGPB_POPUP_PKG_SILVER);
		$options[] = array('name' => 'sgpb-closing-countdown-position-top', 'type' => 'number', 'defaultValue' => 3);
		$options[] = array('name' => 'sgpb-closing-countdown-position-right', 'type' => 'number', 'defaultValue' => 3);
		$options[] = array('name' => 'sgpb-closing-countdown-digits-color', 'type' => 'text', 'defaultValue' => '#2873EB');
		$options[] = array('name' => 'sgpb-closing-countdown-bg-color', 'type' => 'text', 'defaultValue' => '');

		return $options;
	}
}