<?php
class SGPBEDDConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_EDD_PATH', WP_PLUGIN_DIR.'/'.SGPB_EDD_FOLDER_NAME.'/');
		self::addDefine('SGPB_EDD_DYNAMIC_CLASS_PATH', SGPB_EDD_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_EDD_PUBLIC_URL', plugins_url().'/'.SGPB_EDD_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_EDD_COM_PATH', SGPB_EDD_PATH.'com/');
		self::addDefine('SGPB_EDD_PUBLIC_PATH', SGPB_EDD_PATH.'public/');
		self::addDefine('SGPB_EDD_VIEWS_PATH', SGPB_EDD_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_EDD_CLASSES_PATH', SGPB_EDD_COM_PATH.'classes/');
		self::addDefine('SGPB_EDD_VERSION_DETECTION_PATH', SGPB_EDD_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_EDD_EXTENSION_FILE_NAME', 'PopupBuilderEddExtension.php');
		self::addDefine('SGPB_EDD_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderEddExtension');
		self::addDefine('SGPB_EDD_HELPERS', SGPB_EDD_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_EDD', 'Edd');
		self::addDefine('SGPB_POPUP_TYPE_EDD_DISPLAY_NAME', 'Easy Digital Downloads');
		self::addDefine('SGPB_EDD_CONDITION_KEY', 'eddConditions');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SGPB_EDD_PRODUCT_POST_TYPE', 'download');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_EDD_URL', plugins_url().'/'.SGPB_EDD_FOLDER_NAME.'/');
		self::addDefine('SGPB_EDD_JS_URL', SGPB_EDD_PUBLIC_URL.'js/');
		self::addDefine('SGPB_EDD_CSS_URL', SGPB_EDD_PUBLIC_URL.'css/');
		self::addDefine('SGPB_EDD_TEXT_DOMAIN', SGPB_EDD_FOLDER_NAME);
		self::addDefine('SGPB_EDD_PLUGIN_MAIN_FILE', 'PopupBuilderEdd.php');

		self::addDefine('SGPB_EDD_ACTION_KEY', 'sgpbEDD');
		self::addDefine('SGPB_EDD_PLUGIN_KEY', 'easy-digital-downloads/easy-digital-downloads.php');
		self::addDefine('SGPB_EDD_ADD_TO_CART_KEY', 'eddAddToCart');
		self::addDefine('SGPB_EDD_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_EDD_ITEM_ID', 210960);
		self::addDefine('SGPB_EDD_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_EDD_KEY', 'POPUP_EDD');
		self::addDefine('SGPB_MAIN_EDD_PLUGIN_URL', 'https://wordpress.org/plugins/easy-digital-downloads/');
	}
}

SGPBEDDConfig::init();
