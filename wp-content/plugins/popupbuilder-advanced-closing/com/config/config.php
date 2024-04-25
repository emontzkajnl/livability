<?php
class SGPBAdvancedClosingConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_ADVANCED_CLOSING_PATH', WP_PLUGIN_DIR.'/'.SGPB_ADVANCED_CLOSING_FOLDER_NAME.'/');
		self::addDefine('SGPB_ADVANCED_CLOSING_PUBLIC_URL', plugins_url().'/'.SGPB_ADVANCED_CLOSING_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_ADVANCED_CLOSING_DYNAMIC_CLASS_PATH', SGPB_ADVANCED_CLOSING_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_ADVANCED_CLOSING_COM_PATH', SGPB_ADVANCED_CLOSING_PATH.'com/');
		self::addDefine('SGPB_ADVANCED_CLOSING_PUBLIC_PATH', SGPB_ADVANCED_CLOSING_PATH.'public/');
		self::addDefine('SGPB_ADVANCED_CLOSING_VIEWS_PATH', SGPB_ADVANCED_CLOSING_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_ADVANCED_CLOSING_CLASSES_PATH', SGPB_ADVANCED_CLOSING_COM_PATH.'classes/');
		self::addDefine('SGPB_ADVANCED_CLOSING_VERSION_DETECTION_PATH', SGPB_ADVANCED_CLOSING_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_ADVANCED_CLOSING_EXTENSION_FILE_NAME', 'PopupBuilderAdvancedClosingExtension.php');
		self::addDefine('SGPB_ADVANCED_CLOSING_EXTENSION_CLASS_NAME', 'PopupBuilderAdvancedClosingExtension');
		self::addDefine('SGPB_ADVANCED_CLOSING_HELPERS', SGPB_ADVANCED_CLOSING_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_ADVANCED_CLOSING_DISPLAY_NAME', 'Advanced Closing');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_ADVANCED_CLOSING_URL', plugins_url().'/'.SGPB_ADVANCED_CLOSING_FOLDER_NAME.'/');

		/**
			TODO: must be change this URL
		 */
		self::addDefine('SGPB_ADVANCED_CLOSING_PLUGIN_URL', 'https://wordpress.org/plugins/advanced-closing/');
		self::addDefine('SGPB_ADVANCED_CLOSING_JS_URL', SGPB_ADVANCED_CLOSING_PUBLIC_URL.'js/');
		self::addDefine('SGPB_ADVANCED_CLOSING_CSS_URL', SGPB_ADVANCED_CLOSING_PUBLIC_URL.'css/');
		self::addDefine('SGPB_ADVANCED_CLOSING_TEXT_DOMAIN', SGPB_ADVANCED_CLOSING_FOLDER_NAME);
		self::addDefine('SGPB_ADVANCED_CLOSING_PLUGIN_MAIN_FILE', 'PopupBuilderAdvancedClosing.php');
		self::addDefine('SGPB_POPUP_TYPE_ADVANCED_CLOSING', 'advancedClosing');
		self::addDefine('SGPB_ADVANCED_CLOSING_AVALIABLE_VERSION', 1);
		//
		self::addDefine('SGPB_ADVANCED_CLOSING_ACTION_KEY', 'PopupAdvancedClosing');
		self::addDefine('SGPB_ADVANCED_CLOSING_STORE_URL', 'https://popup-builder.com/');
		/**
		TODO: must be change item ID
		 */
		self::addDefine('SGPB_ADVANCED_CLOSING_ITEM_ID', 106652);
		self::addDefine('SGPB_ADVANCED_CLOSING_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_ADVANCED_CLOSING_KEY', 'POPUP_ADVANCED_CLOSING');
	}
}

SGPBAdvancedClosingConfig::init();
