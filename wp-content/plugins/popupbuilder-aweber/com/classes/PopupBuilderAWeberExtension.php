<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderAWeberExtension implements SgpbIPopupExtension
{
	public function getAWeberSettingsPageKey()
	{
		return SG_POPUP_POST_TYPE.'_page_'.SGPB_POPUP_TYPE_AWEBER;
	}

	public function getScripts($page, $data)
	{
		if ((!empty($data['popupType']) && @$data['popupType'] != SGPB_POPUP_TYPE_AWEBER) || (empty($data['popupType']) && $page != SGPB_AWEBER_PAGE)) {
			return false;
		}

		$aweberSettingsKey = $this->getAWeberSettingsPageKey();
		$jsFiles = array();
		$localizeData = array();

		$allowPages = array(
			'popupType',
			'editpage',
			'popupspage',
			$aweberSettingsKey
		);

		if (in_array($page, $allowPages)) {
			$jsFiles[] = array('folderUrl' => SGPB_AWEBER_JS_URL, 'filename' => 'AWeber.js');
		}

		$localizeData[] = array(
			'handle' => 'AWeber.js',
			'name' => 'SGPB_AWEBER_PARAMS',
			'data' => array(
				'confirmText' => __('Are you sure you want to disconnect your AWeber account?', SG_POPUP_TEXT_DOMAIN),
				'nonce' => wp_create_nonce(SG_AJAX_NONCE)
			)
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAWeberAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAWeberAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAWeberAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		if ((!empty($data['popupType']) && @$data['popupType'] != SGPB_POPUP_TYPE_AWEBER) || (empty($data['popupType']) && $page != SGPB_AWEBER_PAGE)) {
			return false;
		}

		$aweberSettingsKey = $this->getAWeberSettingsPageKey();
		$cssFiles = array();

		$allowPages = array(
			'popupType',
			'editpage',
			'popupspage',
			$aweberSettingsKey
		);

		if (in_array($page, $allowPages)) {
			$cssFiles[] = array('folderUrl' => SGPB_AWEBER_CSS_URL, 'filename' => 'aweberAdmin.css');
		}

		$cssData = array(
			'cssFiles' => $cssFiles
		);

		return $cssData;
	}

	/*It's frontend scripts*/
	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => $jsFiles,
			'localizeData' => $localizeData
		);

		$scriptData = apply_filters('sgpbAWeberFrontendJs', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		if (empty($data['popups'])) {
			return false;
		}

		$hasAWeberPopup = $this->hasTypeFromLoadedPopups($data['popups']);

		if (!$hasAWeberPopup) {
			return false;
		}

		$cssFiles = array();

		$cssFiles[] = array('folderUrl' => SGPB_AWEBER_CSS_URL, 'filename' => 'aweberFrontend.css');
		$cssData = array(
			'cssFiles' => apply_filters('sgpbAWeberAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	private function hasTypeFromLoadedPopups($popups)
	{
		$hasType = false;

		foreach ($popups as $popup) {
			if (!is_object($popup)) {
				continue;
			}

			if ($popup->getType() == SGPB_POPUP_TYPE_AWEBER) {
				$hasType = true;
				break;
			}
		}

		return $hasType;
	}
}
