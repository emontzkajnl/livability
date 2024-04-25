<?php
class SGPBAWeberConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_AWEBER_PATH', WP_PLUGIN_DIR.'/'.SGPB_AWEBER_FOLDER_NAME.'/');
		self::addDefine('SGPB_AWEBER_PUBLIC_URL', plugins_url().'/'.SGPB_AWEBER_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_AWEBER_DYNAMIC_CLASS_PATH', SGPB_AWEBER_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_AWEBER_COM_PATH', SGPB_AWEBER_PATH.'com/');
		self::addDefine('SGPB_AWEBER_PUBLIC_PATH', SGPB_AWEBER_PATH.'public/');
		self::addDefine('SGPB_AWEBER_VIEWS_PATH', SGPB_AWEBER_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_AWEBER_CLASSES_PATH', SGPB_AWEBER_COM_PATH.'classes/');
		self::addDefine('SGPB_AWEBER_VERSION_DETECTION_PATH', SGPB_AWEBER_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_AWEBER_LIB_PATH', SGPB_AWEBER_COM_PATH.'lib/');
		self::addDefine('SGPB_AWEBER_EXTENSION_FILE_NAME', 'PopupBuilderAWeberExtension.php');
		self::addDefine('SGPB_AWEBER_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderAWeberExtension');
		self::addDefine('SGPB_AWEBER_HELPERS', SGPB_AWEBER_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_AWEBER', 'aweber');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SGPB_AWEBER_PAGE', SG_POPUP_POST_TYPE.'_page_'.SGPB_POPUP_TYPE_AWEBER);

		self::addDefine('SGPB_AWEBER_URL', plugins_url().'/'.SGPB_AWEBER_FOLDER_NAME.'/');
		self::addDefine('SGPB_AWEBER_JS_URL', SGPB_AWEBER_PUBLIC_URL.'js/');
		self::addDefine('SGPB_AWEBER_CSS_URL', SGPB_AWEBER_PUBLIC_URL.'css/');
		self::addDefine('SGPB_AWEBER_IMG_URL', SGPB_AWEBER_PUBLIC_URL.'img/');
		self::addDefine('SGPB_AWEBER_TEXT_DOMAIN', SGPB_AWEBER_FOLDER_NAME);
		self::addDefine('SGPB_AWEBER_PLUGIN_MAIN_FILE', 'PopupBuilderAWeber.php');
		self::addDefine('SGPB_AWEBER_ACTION_KEY', 'AWeber');
		self::addDefine('SGPB_AWEBEBER_AVALIABLE_VERSION', 1);
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPBAW_CUSTOMER_KEY', 'AkIR8H2FJejLI73Rfo2tIFqd');
		self::addDefine('SGPBAW_CUSTOMER_SECRET_KEY', 'smFRe5NOKBtnfxTPdbac5uXoDzqWol5qflbevOKE');

		self::addDefine('SGPB_AWEBER_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_AWEBER_ITEM_ID', 8145);
		self::addDefine('SGPB_AWEBER_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_AWEBER_KEY', 'POPUP_AWEBER');
	}
}

SGPBAWeberConfig::init();
