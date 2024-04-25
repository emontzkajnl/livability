<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class PopupBuilderGeoTargetingExtension implements SgpbIPopupExtension
{
	public function getScripts($pageName, $data)
	{
		return array();
	}

	public function getStyles($page, $data)
	{
		return array();
	}

	public function getFrontendScripts($page, $popupData)
	{
		return array();
	}

	public function getFrontendStyles($page, $popupData)
	{
		return array();
	}
}
