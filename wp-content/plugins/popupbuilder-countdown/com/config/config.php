<?php

class SGPBCountdownConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_COUNTDOWN_PATH', WP_PLUGIN_DIR.'/'.SGPB_COUNTDOWN_FOLDER_NAME.'/');
		self::addDefine('SGPB_COUNTDOWN_PUBLIC_URL', plugins_url().'/'.SGPB_COUNTDOWN_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_COUNTDOWN_DYNAMIC_CLASS_PATH', SGPB_COUNTDOWN_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_COUNTDOWN_COM_PATH', SGPB_COUNTDOWN_PATH.'com/');
		self::addDefine('SGPB_COUNTDOWN_PUBLIC_PATH', SGPB_COUNTDOWN_PATH.'public/');
		self::addDefine('SGPB_COUNTDOWN_VIEWS_PATH', SGPB_COUNTDOWN_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_COUNTDOWN_CLASSES_PATH', SGPB_COUNTDOWN_COM_PATH.'classes/');
		self::addDefine('SGPB_COUNTDOWN_VERSION_DETECTION_PATH', SGPB_COUNTDOWN_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_COUNTDOWN_EXTENSION_FILE_NAME', 'PopupBuilderCountdownExtension.php');
		self::addDefine('SGPB_COUNTDOWN_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderCountdownExtension');
		self::addDefine('SGPB_COUNTDOWN_HELPERS', SGPB_COUNTDOWN_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_COUNTDOWN', 'countdown');
		self::addDefine('SGPB_POPUP_TYPE_COUNTDOWN_DISPLAY_NAME', 'Countdown');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SG_COUNTDOWN_COUNTER_SECONDS_SHOW', 1);
		self::addDefine('SG_COUNTDOWN_COUNTER_SECONDS_HIDE', 2);

		self::addDefine('SG_COUNTDOWN_COUNTER_LOCATION_TOP_LEFT', 1);
		self::addDefine('SG_COUNTDOWN_COUNTER_LOCATION_TOP', 2);
		self::addDefine('SG_COUNTDOWN_COUNTER_LOCATION_TOP_RIGHT', 3);
		self::addDefine('SG_COUNTDOWN_COUNTER_LOCATION_BOTTOM', 4);

		self::addDefine('SGPB_COUNTDOWN_URL', plugins_url().'/'.SGPB_COUNTDOWN_FOLDER_NAME.'/');

		self::addDefine('SGPB_COUNTDOWN_JS_URL', SGPB_COUNTDOWN_PUBLIC_URL.'js/');
		self::addDefine('SGPB_COUNTDOWN_CSS_URL', SGPB_COUNTDOWN_PUBLIC_URL.'css/');
		self::addDefine('SGPB_COUNTDOWN_TEXT_DOMAIN', SGPB_COUNTDOWN_FOLDER_NAME);
		self::addDefine('SGPB_COUNTDOWN_PLUGIN_MAIN_FILE', 'PopupBuilderCountdown.php');
		self::addDefine('SGPB_COUNTDOWN_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_COUNTDOWN_ACTION_KEY', 'PopupCountdown');
		self::addDefine('SGPB_COUNTDOWN_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_COUNTDOWN_ITEM_ID', 106623);
		self::addDefine('SGPB_COUNTDOWN_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_COUNTDOWN_KEY', 'POPUP_COUNTDOWN');
	}
}

SGPBCountdownConfig::init();
