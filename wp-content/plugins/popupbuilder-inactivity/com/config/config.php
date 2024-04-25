<?php

class SGPBInactivityConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_INACTIVITY_PATH', WP_PLUGIN_DIR.'/'.SGPB_INACTIVITY_FOLDER_NAME.'/');
		self::addDefine('SGPB_INACTIVITY_PUBLIC_URL', plugins_url().'/'.SGPB_INACTIVITY_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_INACTIVITY_DYNAMIC_CLASS_PATH', SGPB_INACTIVITY_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_INACTIVITY_COM_PATH', SGPB_INACTIVITY_PATH.'com/');
		self::addDefine('SGPB_INACTIVITY_PUBLIC_PATH', SGPB_INACTIVITY_PATH.'public/');
		self::addDefine('SGPB_INACTIVITY_VIEWS_PATH', SGPB_INACTIVITY_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_INACTIVITY_CLASSES_PATH', SGPB_INACTIVITY_COM_PATH.'classes/');
		self::addDefine('SGPB_INACTIVITY_VERSION_DETECTION_PATH', SGPB_INACTIVITY_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_INACTIVITY_EXTENSION_FILE_NAME', 'PopupBuilderInactivityExtension.php');
		self::addDefine('SGPB_INACTIVITY_EXTENSION_CLASS_NAME', 'PopupBuilderInactivityExtension');
		self::addDefine('SGPB_INACTIVITY_HELPERS', SGPB_INACTIVITY_COM_PATH.'helpers/');
		self::addDefine('SGPB_INACTIVITY_EVENT_KEY', 'inactivity');
		self::addDefine('SGPB_POPUP_TYPE_INACTIVITY_DISPLAY_NAME', 'Inactivity');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SGPB_POPUP_TYPE_INACTIVITY', 'inactivity');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_INACTIVITY_URL', plugins_url().'/'.SGPB_INACTIVITY_FOLDER_NAME.'/');

		self::addDefine('SGPB_INACTIVITY_JS_URL', SGPB_INACTIVITY_PUBLIC_URL.'js/');
		self::addDefine('SGPB_INACTIVITY_CSS_URL', SGPB_INACTIVITY_PUBLIC_URL.'css/');
		self::addDefine('SGPB_INACTIVITY_TEXT_DOMAIN', SGPB_INACTIVITY_FOLDER_NAME);
		self::addDefine('SGPB_INACTIVITY_PLUGIN_MAIN_FILE', 'PopupBuilderInactivity.php');
		self::addDefine('SGPB_INACTIVITY_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_INACTIVITY_ACTION_KEY', 'PopupInactivity');
		self::addDefine('SGPB_INACTIVITY_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_INACTIVITY_ITEM_ID', 106597);
		self::addDefine('SGPB_INACTIVITY_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_INACTIVITY_KEY', 'POPUP_INACTIVITY');
	}
}

SGPBInactivityConfig::init();
