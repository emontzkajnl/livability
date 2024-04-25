<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class PopupBuilderAdvancedClosingExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_ADVANCED_CLOSING) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAdvancedClosingAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAdvancedClosingAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAdvancedClosingAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_ADVANCED_CLOSING || $page == 'popupType') {
			// here we will include current popup type custom styles
		}
		
		$cssData = array(
			'cssFiles' => apply_filters('sgpbAdvancedClosingAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasAdvancedClosingPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasAdvancedClosingPopup) {
			return false;
		}

		$jsFiles[] = array('folderUrl'=> SGPB_ADVANCED_CLOSING_JS_URL, 'filename' => 'AdvancedClosing.js', 'dep' => array('jquery'));

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAdvancedClosingJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAdvancedClosingJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAdvancedClosingJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasAdvancedClosingPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasAdvancedClosingPopup) {
			return false;
		}

		$cssFiles[] = array('folderUrl'=> SGPB_ADVANCED_CLOSING_CSS_URL, 'filename' => 'AdvancedClosing.css');

		$cssData = array(
			'cssFiles' => apply_filters('sgpbAdvancedClosingCssFiles', $cssFiles)
		);

		return $cssData;
	}

	protected function hasConditionFromLoadedPopups($popups)
	{
		$hasType = false;
		$allowidOption = array('sgpb-auto-close', 'sgpb-close-after-page-scroll');
		foreach ($popups as $popup) {
			if (!is_object($popup)) {
				continue;
			}
			$options = $popup->getOptions();
			foreach ($allowidOption as $optionName) {
				if (isset($options[$optionName])) {
					$hasType = true;
					break;
				}
			}

			if ($hasType == true) {
				break;
			}
		}

		return $hasType;
	}
}