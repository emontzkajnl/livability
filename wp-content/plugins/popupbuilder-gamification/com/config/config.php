<?php
class SGPBGamificationConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_GAMIFICATION_PATH', WP_PLUGIN_DIR.'/'.SGPB_GAMIFICATION_FOLDER_NAME.'/');
		self::addDefine('SGPB_GAMIFICATION_PUBLIC_URL', plugins_url().'/'.SGPB_GAMIFICATION_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_GAMIFICATION_DYNAMIC_CLASS_PATH', SGPB_GAMIFICATION_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_GAMIFICATION_COM_PATH', SGPB_GAMIFICATION_PATH.'com/');
		self::addDefine('SGPB_GAMIFICATION_PUBLIC_PATH', SGPB_GAMIFICATION_PATH.'public/');
		self::addDefine('SGPB_GAMIFICATION_VIEWS_PATH', SGPB_GAMIFICATION_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_GAMIFICATION_CLASSES_PATH', SGPB_GAMIFICATION_COM_PATH.'classes/');
		self::addDefine('SGPB_GAMIFICATION_VERSION_DETECTION_PATH', SGPB_GAMIFICATION_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_GAMIFICATION_EXTENSION_FILE_NAME', 'PopupBuilderGamificationExtension.php');
		self::addDefine('SGPB_GAMIFICATION_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderGamificationExtension');
		self::addDefine('SGPB_GAMIFICATION_HELPERS', SGPB_GAMIFICATION_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_GAMIFICATION', 'gamification');
		self::addDefine('SGPB_POPUP_TYPE_GAMIFICATION_DISPLAY_NAME', 'Gamification');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_GAMIFICATION_URL', plugins_url().'/'.SGPB_GAMIFICATION_FOLDER_NAME.'/');

		self::addDefine('SGPB_GAMIFICATION_JS_URL', SGPB_GAMIFICATION_PUBLIC_URL.'js/');
		self::addDefine('SGPB_GAMIFICATION_CSS_URL', SGPB_GAMIFICATION_PUBLIC_URL.'css/');
		self::addDefine('SGPB_GAMIFICATION_IMG_URL', SGPB_GAMIFICATION_PUBLIC_URL.'img/');
		self::addDefine('SGPB_GAMIFICATION_TEXT_DOMAIN', SGPB_GAMIFICATION_FOLDER_NAME);
		self::addDefine('SGPB_GAMIFICATION_PLUGIN_MAIN_FILE', 'PopupBuilderGamification.php');
		self::addDefine('SGPB_GAMIFICATION_DEFAULT_IMAGE_NAME', 'sgpb-gift-icon-1.png');
		self::addDefine('SGPB_GAMIFICATION_DEFAULT_BG_NAME', 'gamification-default-bg.png');
		self::addDefine('SGPB_GAMIFICATION_DEFAULT_CLOSE_BUTTON_NAME', 'close-button.png');
		self::addDefine('SGPB_GAMIFICATION_LOSER_IMG_URL', SGPB_GAMIFICATION_IMG_URL.'loser-smile.png');
		self::addDefine('SGPB_GAMIFICATION_IMAGE_URL', SGPB_GAMIFICATION_IMG_URL.SGPB_GAMIFICATION_DEFAULT_IMAGE_NAME);
		self::addDefine('SGPB_GAMIFICATION_AVAILABLE_VERSION', 1);
		self::addDefine('SGPB_GAMIFICATION_IMAGES_COUNT', 20);

		self::addDefine('SGPB_GAMIFICATION_ACTION_KEY', 'PopupGamification');
		self::addDefine('SGPB_GAMIFICATION_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_GAMIFICATION_ITEM_ID', 215844);
		self::addDefine('SGPB_GAMIFICATION_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_GAMIFICATION_KEY', 'POPUP_GAMIFICATION');
	}
}

SGPBGamificationConfig::init();
