<?php
class SGPBRecentSalesConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_RECENT_SALES_PATH', WP_PLUGIN_DIR.'/'.SGPB_RECENT_SALES_FOLDER_NAME.'/');
		self::addDefine('SGPB_RECENT_SALES_PUBLIC_URL', plugins_url().'/'.SGPB_RECENT_SALES_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_RECENT_SALES_DYNAMIC_CLASS_PATH', SGPB_RECENT_SALES_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_RECENT_SALES_COM_PATH', SGPB_RECENT_SALES_PATH.'com/');
		self::addDefine('SGPB_RECENT_SALES_PUBLIC_PATH', SGPB_RECENT_SALES_PATH.'public/');
		self::addDefine('SGPB_RECENT_SALES_VIEWS_PATH', SGPB_RECENT_SALES_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_RECENT_SALES_CLASSES_PATH', SGPB_RECENT_SALES_COM_PATH.'classes/');
		self::addDefine('SGPB_RECENT_SALES_VERSION_DETECTION_PATH', SGPB_RECENT_SALES_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_RECENT_SALES_LIB_PATH', SGPB_RECENT_SALES_COM_PATH.'lib/');
		self::addDefine('SGPB_DEFAULT_RECENT_SALES_POPUP_COUNT', 5);
		self::addDefine('SGPB_RECENT_SALES_EXTENSION_FILE_NAME', 'PopupBuilderRecentSalesExtension.php');
		self::addDefine('SGPB_RECENT_SALES_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderRecentSalesExtension');
		self::addDefine('SGPB_RECENT_SALES_HELPERS', SGPB_RECENT_SALES_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_RECENT_SALES', 'recentSales');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');

		self::addDefine('SGPB_RECENT_SALES_URL', plugins_url().'/'.SGPB_RECENT_SALES_FOLDER_NAME.'/');
		self::addDefine('SGPB_RECENT_SALES_JS_URL', SGPB_RECENT_SALES_PUBLIC_URL.'js/');
		self::addDefine('SGPB_RECENT_SALES_CSS_URL', SGPB_RECENT_SALES_PUBLIC_URL.'css/');
		self::addDefine('SGPB_RECENT_SALES_IMG_URL', SGPB_RECENT_SALES_PUBLIC_URL.'img/');
		self::addDefine('SGPB_RECENT_SALES_TEXT_DOMAIN', SGPB_RECENT_SALES_FOLDER_NAME);
		self::addDefine('SGPB_RECENT_SALES_PLUGIN_MAIN_FILE', 'PopupBuilderRecentSales.php');
		self::addDefine('SGPB_RECENT_SALES_ACTION_KEY', 'RecentSales');
		self::addDefine('SGPB_RECENT_SALES_AVALIABLE_VERSION', 1);
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_WOO_DISPLAY_NAME', 'WooCommerce');
		self::addDefine('SGPB_WOO_PLUGIN_SOURCE_KEY', 'woocommerce');
		self::addDefine('SGPB_EDD_DISPLAY_NAME', 'Easy Digital Downloads');
		self::addDefine('SGPB_WOO_PLUGIN_KEY', 'woocommerce/woocommerce.php');
		self::addDefine('SGPB_EDD_PLUGIN_KEY', 'easy-digital-downloads/easy-digital-downloads.php');
		self::addDefine('SGPB_EDD_PLUGIN_SOURCE_KEY', 'edd');

		self::addDefine('SGPB_WOO_PLUGIN_URL', 'https://wordpress.org/plugins/woocommerce/');
		self::addDefine('SGPB_EDD_PLUGIN_URL', 'https://wordpress.org/plugins/easy-digital-downloads/');
		self::addDefine('SGPB_RECENT_SALES_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_RECENT_SALES_ITEM_ID', 103482);
		self::addDefine('SGPB_RECENT_SALES_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_RECENT_SALES_KEY', 'POPUP_RECENT_SALES');
	}
}

SGPBRecentSalesConfig::init();
