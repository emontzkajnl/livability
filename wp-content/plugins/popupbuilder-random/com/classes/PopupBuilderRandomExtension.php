<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class PopupBuilderRandomExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if ($page !== 'popupspage') {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbRandomAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbRandomAdminJsLocalizedData', $localizeData)
		);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_RANDOM || $page == 'popupType') {
			// here we will include current popup type custom styles
		}
		
		$cssData = array(
			'cssFiles' => apply_filters('sgpbRandomAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasRandomPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasRandomPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbRandomJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbRandomJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbRandomJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasRandomPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasRandomPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbRandomCssFiles', $cssFiles)
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
