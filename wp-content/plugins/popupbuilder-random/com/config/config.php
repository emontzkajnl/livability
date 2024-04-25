<?php
class SGPBRandomConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_RANDOM_PATH', WP_PLUGIN_DIR.'/'.SGPB_RANDOM_FOLDER_NAME.'/');
		self::addDefine('SGPB_RANDOM_PUBLIC_URL', plugins_url().'/'.SGPB_RANDOM_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_RANDOM_DYNAMIC_CLASS_PATH', SGPB_RANDOM_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_RANDOM_COM_PATH', SGPB_RANDOM_PATH.'com/');
		self::addDefine('SGPB_RANDOM_PUBLIC_PATH', SGPB_RANDOM_PATH.'public/');
		self::addDefine('SGPB_RANDOM_VIEWS_PATH', SGPB_RANDOM_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_RANDOM_CLASSES_PATH', SGPB_RANDOM_COM_PATH.'classes/');
		self::addDefine('SGPB_RANDOM_VERSION_DETECTION_PATH', SGPB_RANDOM_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_RANDOM_EXTENSION_FILE_NAME', 'PopupBuilderRandomExtension.php');
		self::addDefine('SGPB_RANDOM_EXTENSION_CLASS_NAME', 'PopupBuilderRandomExtension');
		self::addDefine('SGPB_RANDOM_HELPERS', SGPB_RANDOM_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_RANDOM', 'random');
		self::addDefine('SGPB_POPUP_TYPE_RANDOM_DISPLAY_NAME', 'Random');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SG_RANDOM_TAXONOMY_SLUG', 'randompopupslug');
		self::addDefine('SG_POPUP_CATEGORY_TAXONOMY', 'popup-categories');

		self::addDefine('SGPB_RANDOM_URL', plugins_url().'/'.SGPB_RANDOM_FOLDER_NAME.'/');

		self::addDefine('SGPB_RANDOM_JS_URL', SGPB_RANDOM_PUBLIC_URL.'js/');
		self::addDefine('SGPB_RANDOM_CSS_URL', SGPB_RANDOM_PUBLIC_URL.'css/');
		self::addDefine('SGPB_RANDOM_TEXT_DOMAIN', SGPB_RANDOM_FOLDER_NAME);
		self::addDefine('SGPB_RANDOM_PLUGIN_MAIN_FILE', 'PopupBuilderRandom.php');
		self::addDefine('SGPB_RANDOM_AVAILABLE_VERSION', 1);

		self::addDefine('SGPB_RANDOM_ACTION_KEY', 'PopupRandom');
		self::addDefine('SGPB_RANDOM_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_RANDOM_ITEM_ID', 106666);
		self::addDefine('SGPB_RANDOM_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_RANDOM_KEY', 'POPUP_RANDOM');
	}
}

SGPBRandomConfig::init();
