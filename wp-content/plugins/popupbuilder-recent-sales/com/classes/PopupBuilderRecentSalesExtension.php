<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderRecentSalesExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_RECENT_SALES) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$allowPages = array(
			'popupType',
			'editpage',
			'popupspage'
		);

		if (in_array($page, $allowPages)) {
			$jsFiles[] = array('folderUrl' => SGPB_RECENT_SALES_JS_URL, 'filename' => 'RecentSalesAdmin.js');
		}

		$localizeData[] = array(
			'handle' => 'RecentSalesAdmin.js',
			'name' => 'sgpbRecentSalesPublicUrl',
			'data' => SGPB_RECENT_SALES_PUBLIC_URL
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbRecentSalesAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbRecentSalesAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbRecentSalesAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_RECENT_SALES) {
			return false;
		}

		$cssFiles = array();

		$allowPages = array(
			'popupType',
			'editpage',
			'popupspage'
		);

		if (in_array($page, $allowPages)) {
			$cssFiles[] = array('folderUrl' => SGPB_RECENT_SALES_CSS_URL, 'filename' => 'recentSalesAdmin.css');
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbRecentSalesAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	// It's frontend scripts
	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => $jsFiles,
			'localizeData' => $localizeData
		);

		$scriptData = apply_filters('sgpbRecentSalesFrontendJs', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$cssFiles[] = array('folderUrl' => SGPB_RECENT_SALES_CSS_URL, 'filename' => 'recentSalesFrontend.css');
		$cssData = array(
			'cssFiles' => apply_filters('sgpbRecentSalesFrontCssFiles', $cssFiles)
		);

		return $cssData;
	}
}
