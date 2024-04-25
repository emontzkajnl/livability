<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderGamificationExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_GAMIFICATION) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$jsFiles[] = array('folderUrl'=> SGPB_GAMIFICATION_JS_URL, 'filename' => 'GamificationBackend.js');

		$localizeData[] = array(
			'handle' => 'GamificationBackend.js',
			'name' => 'SGPB_GAMIFICATION_ADMIN_PARAMS',
			'data' => array(
				'nonce' => wp_create_nonce(SG_AJAX_NONCE),
				'defaultImagename' => SGPB_GAMIFICATION_DEFAULT_IMAGE_NAME,
				'chooseImage' => __('Choose Image', SG_POPUP_TEXT_DOMAIN),
				'imgURL' => SGPB_GAMIFICATION_IMG_URL
			)
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbGamificationAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbGamificationAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbGamificationAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_GAMIFICATION) {
			return false;
		}
		$cssFiles[] = array('folderUrl'=> SGPB_GAMIFICATION_CSS_URL, 'filename' => 'gamificationAdmin.css');

		$cssData = array(
			'cssFiles' => apply_filters('sgpbGamificationAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasGamificationPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasGamificationPopup) {
			return false;
		}

		$jsFiles[] = array('folderUrl'=> SGPB_GAMIFICATION_JS_URL, 'filename' => 'Validate.js');
		$jsFiles[] = array('folderUrl'=> SGPB_GAMIFICATION_JS_URL, 'filename' => 'Gamification.js');

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbGamificationJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbGamificationJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbGamificationJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasGamificationPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasGamificationPopup) {
			return false;
		}
		$cssFiles[] = array('folderUrl'=> SGPB_GAMIFICATION_CSS_URL, 'filename' => 'gamification.css');

		$cssData = array(
			'cssFiles' => apply_filters('sgpbGamificationCssFiles', $cssFiles)
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

			if ($popup->getType() == SGPB_POPUP_TYPE_GAMIFICATION) {
				$hasType = true;
				break;
			}
		}

		return $hasType;
	}
}