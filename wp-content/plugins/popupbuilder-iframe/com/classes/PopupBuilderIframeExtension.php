<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderIframeExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_IFRAME) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbIframeAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbIframeAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbIframeAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_IFRAME || $page == 'popupType') {
			// here we will include current popup type custom styles
		}
		
		$cssData = array(
			'cssFiles' => apply_filters('sgpbIframeAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasIframePopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasIframePopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbIframeJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbIframeJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbIframeJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasIframePopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasIframePopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbIframeCssFiles', $cssFiles)
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