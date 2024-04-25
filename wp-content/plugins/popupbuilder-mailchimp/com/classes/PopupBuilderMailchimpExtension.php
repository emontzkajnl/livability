<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderMailchimpExtension implements SgpbIPopupExtension
{
	public function getMailchimpSettingsPageKey()
	{
		return SG_POPUP_POST_TYPE.'_page_'.SGPB_POPUP_TYPE_MAILCHIMP;
	}

	public function getScripts($page, $data)
	{
		if ((!empty($data['popupType']) && @$data['popupType'] != SGPB_POPUP_TYPE_MAILCHIMP) || (empty($data['popupType']) && $page != SGPB_MAILCHIMP_PAGE)) {
			return false;
		}

		$mailchimpSettingsKey = $this->getMailchimpSettingsPageKey();
		$jsFiles = array();
		$localizeData = array();

		$allowPages = array(
			'popupType',
			'editpage',
			'popupspage',
			$mailchimpSettingsKey
		);

		if (in_array($page, $allowPages)) {
			$jsFiles[] = array('folderUrl' => SGPB_MAILCHIMP_JS_URL, 'filename' => 'MailchimpBackend.js');
		}

		$localizeData[] = array(
			'handle' => 'MailchimpBackend.js',
			'name' => 'SGPB_MAILCHIMP_PARAMS',
			'data' => array(
				'nonce' => wp_create_nonce(SG_AJAX_NONCE)
			)
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbMailchimpAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbMailchimpAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbMailchimpAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		if ((!empty($data['popupType']) && @$data['popupType'] != SGPB_POPUP_TYPE_MAILCHIMP) || (empty($data['popupType']) && $page != SGPB_MAILCHIMP_PAGE)) {
			return false;
		}

		$mailchimpSettingsKey = $this->getMailchimpSettingsPageKey();
		$cssFiles = array();

		$allowPages = array(
			'popupType',
			'editpage',
			'popupspage',
			$mailchimpSettingsKey
		);

		if (in_array($page, $allowPages)) {
			$cssFiles[] = array('folderUrl' => SGPB_MAILCHIMP_CSS_URL, 'filename' => 'mailchimp.css');
			$cssFiles[] = array('folderUrl' => SGPB_MAILCHIMP_CSS_URL, 'filename' => 'MailchimpDefaults.css');
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbMailchimpAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	// It's frontend scripts
	public function getFrontendScripts($page, $data)
	{
		if (empty($data['popups'])) {
			return false;
		}

		$hasMailChimpPopup = $this->hasTypeFromLoadedPopups($data['popups']);

		if (!$hasMailChimpPopup) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$jsFiles[] = array('folderUrl' => SGPB_MAILCHIMP_JS_URL, 'filename' => 'Mailchimp.js', 'inFooter' => true);
		$jsFiles[] = array('folderUrl' => SGPB_MAILCHIMP_JS_URL, 'filename' => 'Validate.js', 'dep' => array('jquery'), 'inFooter' => true);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbMailchimpJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbMailchimpJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbMailchimpJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		if (empty($data['popups'])) {
			return false;
		}

		$hasMailChimpPopup = $this->hasTypeFromLoadedPopups($data['popups']);

		if (!$hasMailChimpPopup) {
			return false;
		}

		$cssFiles = array();

		$cssFiles[] = array('folderUrl' => SGPB_MAILCHIMP_CSS_URL, 'filename' => 'mailchimp.css');
		$cssFiles[] = array('folderUrl' => SGPB_MAILCHIMP_CSS_URL, 'filename' => 'MailchimpDefaults.css');

		$cssData = array(
			'cssFiles' => apply_filters('sgpbMailchimpCssFiles', $cssFiles)
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
			if ($popup->getType() == SGPB_POPUP_TYPE_MAILCHIMP) {
				$hasType = true;
				break;
			}
		}

		return $hasType;
	}
}
