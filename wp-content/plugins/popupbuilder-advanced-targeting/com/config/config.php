<?php
class SGPBAdvancedTargetingConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_ADVANCED_TARGETING_PATH', WP_PLUGIN_DIR.'/'.SGPB_ADVANCED_TARGETING_FOLDER_NAME.'/');
		self::addDefine('SGPB_ADVANCED_TARGETING_PUBLIC_URL', plugins_url().'/'.SGPB_ADVANCED_TARGETING_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_ADVANCED_TARGETING_DYNAMIC_CLASS_PATH', SGPB_ADVANCED_TARGETING_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_ADVANCED_TARGETING_COM_PATH', SGPB_ADVANCED_TARGETING_PATH.'com/');
		self::addDefine('SGPB_ADVANCED_TARGETING_PUBLIC_PATH', SGPB_ADVANCED_TARGETING_PATH.'public/');
		self::addDefine('SGPB_ADVANCED_TARGETING_VIEWS_PATH', SGPB_ADVANCED_TARGETING_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_ADVANCED_TARGETING_CLASSES_PATH', SGPB_ADVANCED_TARGETING_COM_PATH.'classes/');
		self::addDefine('SGPB_ADVANCED_TARGETING_VERSION_DETECTION_PATH', SGPB_ADVANCED_TARGETING_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_ADVANCED_TARGETING_EXTENSION_FILE_NAME', 'PopupBuilderAdvancedTargetingExtension.php');
		self::addDefine('SGPB_ADVANCED_TARGETING_EXTENSION_CLASS_NAME', 'PopupBuilderAdvancedTargetingExtension');
		self::addDefine('SGPB_ADVANCED_TARGETING_HELPERS', SGPB_ADVANCED_TARGETING_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_ADVANCED_TARGETING', 'advancedTargeting');
		self::addDefine('SGPB_POPUP_TYPE_ADVANCED_TARGETING_DISPLAY_NAME', 'Advanced Targeting');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_POPUP_ADVANCED_TARGETING_EXTENSION_KEY', 'popupbuilder-advanced-targeting/PopupBuilderAdvancedTargeting.php');
		self::addDefine('SGPB_POPUP_GEO_TARGETING_EXTENSION_KEY', 'popupbuilder-geo-targeting/PopupBuilderGeoTargeting.php');
		self::addDefine('SGPB_POPUP_GROUPS_DEVICES_KEY', 'groups_devices');
		self::addDefine('SGPB_POPUP_GROUPS_USER_ROLE_KEY', 'groups_user_role');
		self::addDefine('SGPB_POPUP_AFTER_X_PAGE_KEY', 'after_x_page');
		self::addDefine('SGPB_POPUP_GROUPS_USER_STATUS_KEY', 'groups_user_status');
		self::addDefine('SGPB_POPUP_URL_DETECTION_KEY', 'url_detection');
		self::addDefine('SGPB_POPUP_QUERY_STRING_DETECTION_KEY', 'query_string_detection');
		self::addDefine('SGPB_POPUP_COOKIE_DETECTION_KEY', 'cookie_detection');
		self::addDefine('SGPB_POPUP_GROUPS_OS_KEY', 'operation_system');
		self::addDefine('SGPB_POPUP_GROUPS_BROWSER_KEY', 'browser_detection');
		self::addDefine('SGPB_ADVANCED_TARGETING_URL', plugins_url().'/'.SGPB_ADVANCED_TARGETING_FOLDER_NAME.'/');
		self::addDefine('SGPB_ADVANCED_TARGETING_JS_URL', SGPB_ADVANCED_TARGETING_PUBLIC_URL.'js/');
		self::addDefine('SGPB_ADVANCED_TARGETING_CSS_URL', SGPB_ADVANCED_TARGETING_PUBLIC_URL.'css/');
		self::addDefine('SGPB_ADVANCED_TARGETING_TEXT_DOMAIN', SGPB_ADVANCED_TARGETING_FOLDER_NAME);
		self::addDefine('SGPB_ADVANCED_TARGETING_PLUGIN_MAIN_FILE', 'PopupBuilderAdvancedTargeting.php');
		self::addDefine('SGPB_ADVANCED_TARGETING_AVAILABLE_VERSION', 1);

		self::addDefine('SGPB_ADVANCED_TARGETING_ACTION_KEY', 'PopupAdvancedTargeing');
		self::addDefine('SGPB_ADVANCED_TARGETING_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_ADVANCED_TARGETING_ITEM_ID', 106639);
		self::addDefine('SGPB_ADVANCED_TARGETING_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_ADVANCED_TARGETING_KEY', 'POPUP_ADVANCED_TARGETING');
	}
}

SGPBAdvancedTargetingConfig::init();
