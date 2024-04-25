<?php
class SGPBSchedulingConfig
{
	public static function addDefine($name, $value)
	{
		if(!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_SCHEDULING_PATH', WP_PLUGIN_DIR.'/'.SGPB_SCHEDULING_FOLDER_NAME.'/');
		self::addDefine('SGPB_SCHEDULING_DYNAMIC_CLASS_PATH', SGPB_SCHEDULING_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_SCHEDULING_COM_PATH', SGPB_SCHEDULING_PATH.'com/');
		self::addDefine('SGPB_SCHEDULING_PUBLIC_PATH', SGPB_SCHEDULING_PATH.'public/');
		self::addDefine('SGPB_SCHEDULING_VIEWS_PATH', SGPB_SCHEDULING_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_SCHEDULING_CLASSES_PATH', SGPB_SCHEDULING_COM_PATH.'classes/');
		self::addDefine('SGPB_SCHEDULING_VERSION_DETECTION_PATH', SGPB_SCHEDULING_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_SCHEDULING_EXTENSION_FILE_NAME', 'SchedulingExtension.php');
		self::addDefine('SGPB_SCHEDULING_EXTENSION_CLASS_NAME', 'SGPBSchedulingExtension');
		self::addDefine('SGPB_SCHEDULING_HELPERS', SGPB_SCHEDULING_COM_PATH.'helpers/');
		self::addDefine('SGPB_SCHEDULING_PUBLIC_URL', plugins_url().'/'.SGPB_SCHEDULING_FOLDER_NAME.'/public/');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_SCHEDULING_URL', plugins_url().'/'.SGPB_SCHEDULING_FOLDER_NAME.'/');
		self::addDefine('SGPB_SCHEDULING_JS_URL', SGPB_SCHEDULING_PUBLIC_URL.'js/');
		self::addDefine('SGPB_SCHEDULING_CSS_URL', SGPB_SCHEDULING_PUBLIC_URL.'css/');
		self::addDefine('SGPB_SCHEDULING_TEXT_DOMAIN', 'popupBuilderScheduling');
		self::addDefine('SGPB_SCHEDULING_PLUGIN_MAIN_FILE', 'PopupBuilderScheduling.php');
		self::addDefine('SGPB_SCHEDULING_ACTION_KEY', 'scheduling');
		self::addDefine('SGPB_POPUP_TYPE_SCHEDULING_DISPLAY_NAME', 'Scheduling');
		self::addDefine('SG_POPUP_DEFAULT_TIME_ZONE', 'UTC');

		self::addDefine('SGPB_SCHEDULING_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_SCHEDULING_ITEM_ID', 106656);
		self::addDefine('SGPB_SCHEDULING_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_SCHEDULING_KEY', 'POPUP_SCHEDULING');
	}
}

SGPBSchedulingConfig::init();
