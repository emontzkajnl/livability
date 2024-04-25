<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderPushNotificationExtension implements SgpbIPopupExtension
{
	private function allowedPages()
	{
		$pages = array('popupbuilder_page_sgpbPush');

		return $pages;
	}

	public function getScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();
		if ((!empty($data['popupType']) && @$data['popupType'] != SGPB_POPUP_TYPE_PUSH_NOTIFICATION) || (empty($data['popupType']) && $page != SG_POPUP_POST_TYPE.'_page_'.SGPB_PUSH_NOTIFICATION_PAGE_KEY)) {
			return false;
		}

		$jsFiles[] = array('folderUrl'=> SGPB_PUSH_NOTIFICATION_JS_URL, 'filename' => 'PushNotificationAdmin.js');
		$localizeData[] = array(
			'handle' => 'PushNotificationAdmin.js',
			'name' => 'SGPB_NOTIFICATION_LOCALIZATION',
			'data' => array(
				'areYouSure' => __('Are you sure?', SG_POPUP_TEXT_DOMAIN),
			)
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbPushNotificationAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbPushNotificationAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbPushNotificationAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		if ((!empty($data['popupType']) && @$data['popupType'] != SGPB_POPUP_TYPE_PUSH_NOTIFICATION) || (empty($data['popupType']) && $page != SG_POPUP_POST_TYPE.'_page_'.SGPB_PUSH_NOTIFICATION_PAGE_KEY)) {
			return false;
		}
		wp_enqueue_media();

		$cssFiles[] = array('folderUrl' => SGPB_PUSH_NOTIFICATION_CSS_URL, 'filename' => 'NotificationAdmin.css');
		$cssData = array(
			'cssFiles' => apply_filters('sgpbPushNotificationAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasPushNotificationPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasPushNotificationPopup) {
			return false;
		}
		$jsFiles[] = array('folderUrl' => SGPB_PUSH_NOTIFICATION_JS_URL, 'filename' => 'PushNotification.js');
		$scriptData = array(
			'jsFiles' => apply_filters('sgpbPushNotificationJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbPushNotificationJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbPushNotificationJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasPushNotificationPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasPushNotificationPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbPushNotificationCssFiles', $cssFiles)
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
