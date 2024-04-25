<?php
class SGPBPushNotificationConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_PUSH_NOTIFICATION_PATH', WP_PLUGIN_DIR.'/'.SGPB_PUSH_NOTIFICATION_FOLDER_NAME.'/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_CLASS_PATH', SGPB_PUSH_NOTIFICATION_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_PUBLIC_URL', plugins_url().'/'.SGPB_PUSH_NOTIFICATION_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_COM_PATH', SGPB_PUSH_NOTIFICATION_PATH.'com/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_PUBLIC_PATH', SGPB_PUSH_NOTIFICATION_PATH.'public/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_VIEWS_PATH', SGPB_PUSH_NOTIFICATION_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_CLASSES_PATH', SGPB_PUSH_NOTIFICATION_COM_PATH.'classes/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_VERSION_DETECTION_PATH', SGPB_PUSH_NOTIFICATION_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_LIBS_PATH', SGPB_PUSH_NOTIFICATION_COM_PATH.'libs/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_DATA_TABLE_PATH', SGPB_PUSH_NOTIFICATION_CLASSES_PATH.'dataTable/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_EXTENSION_FILE_NAME', 'PopupBuilderPushNotificationExtension.php');
		self::addDefine('SGPB_PUSH_NOTIFICATION_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderPushNotificationExtension');
		self::addDefine('SGPB_PUSH_NOTIFICATION_HELPERS', SGPB_PUSH_NOTIFICATION_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_PUSH_NOTIFICATION', 'pushNotification');
		self::addDefine('SGPB_POPUP_TYPE_PUSH_NOTIFICATION_DISPLAY_NAME', 'Push Notification');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_PUSH_NOTIFICATION_URL', plugins_url().'/'.SGPB_PUSH_NOTIFICATION_FOLDER_NAME.'/');

		self::addDefine('SGPB_PUSH_NOTIFICATION_JS_URL', SGPB_PUSH_NOTIFICATION_PUBLIC_URL.'js/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_CSS_URL', SGPB_PUSH_NOTIFICATION_PUBLIC_URL.'css/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_IMAGE_URL', SGPB_PUSH_NOTIFICATION_PUBLIC_URL.'img/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_TEXT_DOMAIN', SGPB_PUSH_NOTIFICATION_FOLDER_NAME);
		self::addDefine('SGPB_PUSH_NOTIFICATION_DEFAULT_IMAGE_NAME', 'icon.png');
		self::addDefine('SGPB_PUSH_NOTIFICATION_CHROME_IMAGE_NAME', 'chrome-icon.svg');
		self::addDefine('SGPB_PUSH_NOTIFICATION_PLUGIN_MAIN_FILE', 'PopupBuilderPushNotification.php');
		self::addDefine('SGPB_PUSH_NOTIFICATION_AVAILABLE_VERSION', 1);

		self::addDefine('SGPB_PUSH_NOTIFICATION_ACTION_KEY', 'PopupPushNotification');
		self::addDefine('SGPB_PUSH_NOTIFICATION_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_PUSH_NOTIFICATION_PAGE_KEY', 'sgpbPush');
		self::addDefine('SGPB_PUSH_NOTIFICATION_TABLE_NAME', 'sgpb_push_notification');
		self::addDefine('SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME', 'sgpb_push_notification_campaigns');

		self::addDefine('SGPB_PUSH_NOTIFICATION_ITEM_ID', 215009);
		self::addDefine('SGPB_PUSH_NOTIFICATION_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_PUSH_NOTIFICATION_KEY', 'POPUP_PUSH_NOTIFICATION');
	}
}

SGPBPushNotificationConfig::init();
