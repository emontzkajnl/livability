<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderAgeverificationExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_AGE_VERIFICATION) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAgeverificationAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAgeverificationAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAgeverificationAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_AGE_VERIFICATION || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbAgeverificationAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasAgeverificationPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasAgeverificationPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAgeverificationJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAgeverificationJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAgeverificationJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasAgeverificationPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasAgeverificationPopup) {
			return false;
		}

		$cssFiles[] = array('folderUrl' => SGPB_AGE_VERIFICATION_CSS_URL, 'filename' => 'AgeVerification.css', 'dep' => array(), 'ver' => SG_POPUP_VERSION, 'inFooter' => false);

		$cssData = array(
			'cssFiles' => apply_filters('sgpbAgeverificationCssFiles', $cssFiles)
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

			if ($popup->getType() == SGPB_POPUP_TYPE_AGE_VERIFICATION) {
				$hasType = true;
				break;
			}
		}

		return $hasType;
	}
}
