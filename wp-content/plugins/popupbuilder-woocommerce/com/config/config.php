<?php
class SGPBWOOConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_WOO_PATH', WP_PLUGIN_DIR.'/'.SGPB_WOO_FOLDER_NAME.'/');
		self::addDefine('SGPB_WOO_DYNAMIC_CLASS_PATH', SGPB_WOO_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_WOO_PUBLIC_URL', plugins_url().'/'.SGPB_WOO_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_WOO_COM_PATH', SGPB_WOO_PATH.'com/');
		self::addDefine('SGPB_WOO_PUBLIC_PATH', SGPB_WOO_PATH.'public/');
		self::addDefine('SGPB_WOO_VIEWS_PATH', SGPB_WOO_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_WOO_CLASSES_PATH', SGPB_WOO_COM_PATH.'classes/');
		self::addDefine('SGPB_WOO_VERSION_DETECTION_PATH', SGPB_WOO_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_WOO_EXTENSION_FILE_NAME', 'PopupBuilderWooExtension.php');
		self::addDefine('SGPB_WOO_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderWooExtension');
		self::addDefine('SGPB_WOO_HELPERS', SGPB_WOO_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_WOO', 'Woo');
		self::addDefine('SGPB_POPUP_TYPE_WOO_DISPLAY_NAME', 'WooCommerce');
		self::addDefine('SGPB_WOO_CONDITION_KEY', 'wooConditions');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SGPB_WOO_PRODUCT_POST_TYPE', 'product');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_WOO_URL', plugins_url().'/'.SGPB_WOO_FOLDER_NAME.'/');
		self::addDefine('SGPB_WOO_PLUGIN_URL', 'https://wordpress.org/plugins/woocommerce/');
		self::addDefine('SGPB_WOO_JS_URL', SGPB_WOO_PUBLIC_URL.'js/');
		self::addDefine('SGPB_WOO_CSS_URL', SGPB_WOO_PUBLIC_URL.'css/');
		self::addDefine('SGPB_WOO_TEXT_DOMAIN', SGPB_WOO_FOLDER_NAME);
		self::addDefine('SGPB_WOO_PLUGIN_MAIN_FILE', 'PopupBuilderWOO.php');
		//
		self::addDefine('SGPB_WOO_ACTION_KEY', 'sgpbWOO');
		self::addDefine('SGPB_WOO_PLUGIN_KEY', 'woocommerce/woocommerce.php');
		self::addDefine('SGPB_ADD_TO_CART_KEY', 'addToCart');
		self::addDefine('SGPB_WOO_ADD_TO_CART', 'add');
		self::addDefine('SGPB_WOO_REMOVE_FROM_CART', 'remove');
		self::addDefine('SGPB_WOO_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_WOO_ITEM_ID', 102942);
		self::addDefine('SGPB_WOO_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_WOO_KEY', 'POPUP_WOO');
	}
}

SGPBWOOConfig::init();
