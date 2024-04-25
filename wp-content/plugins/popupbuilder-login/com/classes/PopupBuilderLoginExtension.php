<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderLoginExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_LOGIN) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbLoginAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbLoginAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbLoginAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_LOGIN || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbLoginAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasLoginPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasLoginPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbLoginJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbLoginJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbLoginJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasLoginPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasLoginPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbLoginCssFiles', $cssFiles)
		);

		return $cssData;
	}

	protected function hasConditionFromLoadedPopups($popups)
	{
		$hasType = false;

		foreach ($popups as $popup) {
			if (!is_object($popup)) {
				continue;
			}

		}

		return $hasType;
	}
}
