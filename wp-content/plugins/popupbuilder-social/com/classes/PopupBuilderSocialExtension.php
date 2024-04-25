<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderSocialExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_SOCIAL) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbSocialAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbSocialAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbSocialAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_SOCIAL || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbSocialAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasSocialPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasSocialPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbSocialJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbSocialJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbSocialJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasSocialPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasSocialPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbSocialCssFiles', $cssFiles)
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
