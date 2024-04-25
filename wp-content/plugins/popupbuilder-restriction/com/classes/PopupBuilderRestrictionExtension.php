<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderRestrictionExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_RESTRICTION) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbRestrictionAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbRestrictionAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbRestrictionAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_RESTRICTION || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbRestrictionAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasRestrictionPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasRestrictionPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbRestrictionJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbRestrictionJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbRestrictionJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasRestrictionPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasRestrictionPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbRestrictionCssFiles', $cssFiles)
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
