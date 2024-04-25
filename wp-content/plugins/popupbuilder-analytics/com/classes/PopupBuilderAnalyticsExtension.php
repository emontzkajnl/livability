<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderAnalyticsExtension implements SgpbIPopupExtension
{
	public function getAnalyticsSettingsPageKey()
	{
		return SG_POPUP_POST_TYPE.'_page_'.SGPB_POPUP_TYPE_ANALYTICS;
	}

	public function getScripts($page, $data)
	{
		$analyticsSettingsKey = $this->getAnalyticsSettingsPageKey();
		$jsFiles = array();
		$localizeData = array();

		$allowPages = array(
			'popupType',
			'editpage',
			'popupspage',
			$analyticsSettingsKey
		);

		if ($page == $analyticsSettingsKey) {
			$jsFiles[] = array('folderUrl' => SGPB_ANALYTICS_JS_URL, 'filename' => 'AnalyticsBackend.js');
		}

		$localizeData[] = array(
			'handle' => 'AnalyticsBackend.js',
			'name' => 'SGPB_ANALYTICS_PARAMS',
			'data' => array(
				'nonce' => wp_create_nonce(SG_AJAX_NONCE)
			)
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAnalyticsAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAnalyticsAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAnalyticsAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$analyticsSettingsKey = $this->getAnalyticsSettingsPageKey();
		$cssFiles = array();

		$allowPages = array(
			'popupType',
			'editpage',
			$analyticsSettingsKey
		);

		if (in_array($page, $allowPages)) {
			$cssFiles[] = array('folderUrl' => SGPB_ANALYTICS_CSS_URL, 'filename' => 'analytics.css');
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbAnalyticsAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	// It's frontend scripts
	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$jsFiles[] = array('folderUrl' => SGPB_ANALYTICS_JS_URL, 'filename' => 'AnalyticsApi.js', 'dep' => array('jquery'), 'inFooter' => true);
		$jsFiles[] = array('folderUrl' => SGPB_ANALYTICS_JS_URL, 'filename' => 'Analytics.js', 'dep' => array('jquery'), 'inFooter' => true);
		$localizeData[] = array(
			'handle' => 'Analytics.js',
			'name' => 'SGPB_ANALYTICS_PARAMS',
			'data' => array(
				'nonce' => wp_create_nonce(SG_AJAX_NONCE),
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'isPreview' => is_preview()
			)
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbAnalyticsJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbAnalyticsJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbAnalyticsAdminJs', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{

	}
}
