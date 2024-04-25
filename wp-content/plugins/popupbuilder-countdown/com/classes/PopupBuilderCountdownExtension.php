<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderCountdownExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_COUNTDOWN) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbCountdownAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbCountdownAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbCountdownAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_COUNTDOWN || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbCountdownAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasCountdownPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasCountdownPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbCountdownJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbCountdownJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbCountdownJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasCountdownPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasCountdownPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbCountdownCssFiles', $cssFiles)
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
