<?php
class SGPBIframeConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_IFRAME_PATH', WP_PLUGIN_DIR.'/'.SGPB_IFRAME_FOLDER_NAME.'/');
		self::addDefine('SGPB_IFRAME_PUBLIC_URL', plugins_url().'/'.SGPB_IFRAME_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_IFRAME_DYNAMIC_CLASS_PATH', SGPB_IFRAME_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_IFRAME_COM_PATH', SGPB_IFRAME_PATH.'com/');
		self::addDefine('SGPB_IFRAME_PUBLIC_PATH', SGPB_IFRAME_PATH.'public/');
		self::addDefine('SGPB_IFRAME_VIEWS_PATH', SGPB_IFRAME_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_IFRAME_CLASSES_PATH', SGPB_IFRAME_COM_PATH.'classes/');
		self::addDefine('SGPB_IFRAME_VERSION_DETECTIONS_PATH', SGPB_IFRAME_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_IFRAME_EXTENSION_FILE_NAME', 'SGPBPopupBuilderIframeExtension.php');
		self::addDefine('SGPB_IFRAME_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderIframeExtension');
		self::addDefine('SGPB_IFRAME_HELPERS', SGPB_IFRAME_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_IFRAME', 'iframe');
		self::addDefine('SGPB_POPUP_TYPE_IFRAME_DISPLAY_NAME', 'Iframe');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_IFRAME_URL', plugins_url().'/'.SGPB_IFRAME_FOLDER_NAME.'/');

		self::addDefine('SGPB_IFRAME_JS_URL', SGPB_IFRAME_PUBLIC_URL.'js/');
		self::addDefine('SGPB_IFRAME_CSS_URL', SGPB_IFRAME_PUBLIC_URL.'css/');
		self::addDefine('SGPB_IFRAME_TEXT_DOMAIN', SGPB_IFRAME_FOLDER_NAME);
		self::addDefine('SGPB_IFRAME_PLUGIN_MAIN_FILE', 'PopupBuilderIframe.php');
		self::addDefine('SGPB_IFRAME_AVAILABLE_VERSION', 1);

		self::addDefine('SGPB_IFRAME_ACTION_KEY', 'PopupIframe');
		self::addDefine('SGPB_IFRAME_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_IFRAME_ITEM_ID', 106631);
		self::addDefine('SGPB_IFRAME_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_IFRAME_KEY', 'POPUP_IFRAME');
	}
}

SGPBIframeConfig::init();
