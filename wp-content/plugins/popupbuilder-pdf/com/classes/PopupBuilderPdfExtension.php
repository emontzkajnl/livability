<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderPdfExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_PDF) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbPdfAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbPdfAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbPdfAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_PDF) {
			// here we will include current popup type custom styles
			return false;
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbPdfAdminCssFiles', $cssFiles)
		);

		$cssData = apply_filters('sgpbPdfAdminCss', $cssData);

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
			'jsFiles' => apply_filters('sgpbPdfJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbPdfJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbPdfJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$cssData = array(
			'cssFiles' => apply_filters('sgpbPdfCssFiles', $cssFiles)
		);

		return apply_filters('sgpbPdfFrontCss', $cssData);
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
