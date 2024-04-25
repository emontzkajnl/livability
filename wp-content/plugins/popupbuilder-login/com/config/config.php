<?php
class SGPBLoginConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_LOGIN_PATH', WP_PLUGIN_DIR.'/'.SGPB_LOGIN_FOLDER_NAME.'/');
		self::addDefine('SGPB_LOGIN_PUBLIC_URL', plugins_url().'/'.SGPB_LOGIN_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_LOGIN_DYNAMIC_CLASS_PATH', SGPB_LOGIN_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_LOGIN_COM_PATH', SGPB_LOGIN_PATH.'com/');
		self::addDefine('SGPB_LOGIN_PUBLIC_PATH', SGPB_LOGIN_PATH.'public/');
		self::addDefine('SGPB_LOGIN_VIEWS_PATH', SGPB_LOGIN_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_LOGIN_CLASSES_PATH', SGPB_LOGIN_COM_PATH.'classes/');
		self::addDefine('SGPB_LOGIN_VERSION_DETECTION_PATH', SGPB_LOGIN_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_LOGIN_EXTENSION_FILE_NAME', 'PopupBuilderLoginExtension.php');
		self::addDefine('SGPB_LOGIN_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderLoginExtension');
		self::addDefine('SGPB_LOGIN_HELPERS', SGPB_LOGIN_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_LOGIN', 'login');
		self::addDefine('SGPB_POPUP_TYPE_LOGIN_DISPLAY_NAME', 'LOGIN');
		self::addDefine('SGPB_LOGIN_CONDITION_KEY', 'loginConditions');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_LOGIN_URL', plugins_url().'/'.SGPB_LOGIN_FOLDER_NAME.'/');

		self::addDefine('SGPB_LOGIN_JS_URL', SGPB_LOGIN_PUBLIC_URL.'js/');
		self::addDefine('SGPB_LOGIN_CSS_URL', SGPB_LOGIN_PUBLIC_URL.'css/');
		self::addDefine('SGPB_LOGIN_TEXT_DOMAIN', SGPB_LOGIN_FOLDER_NAME);
		self::addDefine('SGPB_LOGIN_PLUGIN_MAIN_FILE', 'PopupBuilderLogin.php');
		self::addDefine('SGPB_LOGIN_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_LOGIN_ACTION_KEY', 'PopupLogin');
		self::addDefine('SGPB_LOGIN_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_LOGIN_ITEM_ID', 106673);
		self::addDefine('SGPB_LOGIN_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_LOGIN_KEY', 'POPUP_LOGIN');
	}
}

SGPBLoginConfig::init();
