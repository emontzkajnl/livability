<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class PopupBuilderAdvancedTargetingExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_ADVANCED_TARGETING) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAdvancedTargetingAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAdvancedTargetingAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAdvancedTargetingAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_ADVANCED_TARGETING || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbAdvancedTargetingAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasAdvancedTargetingPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasAdvancedTargetingPopup) {
			return false;
		}
		$jsFiles[] = array('folderUrl'=> SGPB_ADVANCED_TARGETING_JS_URL, 'filename' => 'AdvancedTarget.js', 'dep' => array('PopupBuilder.js'));

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAdvancedTargetingJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAdvancedTargetingJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAdvancedTargetingJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasAdvancedTargetingPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasAdvancedTargetingPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbAdvancedTargetingCssFiles', $cssFiles)
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
			$conditions = $popup->getConditions();
			if (!empty($conditions)) {
				foreach ($conditions as $condition) {
					if ($condition['param'] == SGPB_POPUP_AFTER_X_PAGE_KEY) {
						$hasType = true;
						break;
					}
				}
			}
		}

		return $hasType;
	}
}
