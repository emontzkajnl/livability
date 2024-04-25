<?php
class SGPBPdfConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_PDF_PATH', WP_PLUGIN_DIR.'/'.SGPB_PDF_FOLDER_NAME.'/');
		self::addDefine('SGPB_PDF_PUBLIC_URL', plugins_url().'/'.SGPB_PDF_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_PDF_DYNAMIC_CLASS_PATH', SGPB_PDF_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_PDF_COM_PATH', SGPB_PDF_PATH.'com/');
		self::addDefine('SGPB_PDF_PUBLIC_PATH', SGPB_PDF_PATH.'public/');
		self::addDefine('SGPB_PDF_VIEWS_PATH', SGPB_PDF_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_PDF_CLASSES_PATH', SGPB_PDF_COM_PATH.'classes/');
		self::addDefine('SGPB_PDF_VERSION_DETECTION_PATH', SGPB_PDF_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_PDF_EXTENSION_FILE_NAME', 'PopupBuilderPdfExtension.php');
		self::addDefine('SGPB_PDF_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderPdfExtension');
		self::addDefine('SGPB_PDF_HELPERS', SGPB_PDF_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_PDF', 'pdf');
		self::addDefine('SGPB_POPUP_TYPE_PDF_DISPLAY_NAME', 'PDF');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_PDF_JS_URL', SGPB_PDF_PUBLIC_URL.'js/');
		self::addDefine('SGPB_PDF_VIEWER_JS_URL', SGPB_PDF_JS_URL.'ViewerJS/');
		self::addDefine('SGPB_PDF_CSS_URL', SGPB_PDF_PUBLIC_URL.'css/');
		self::addDefine('SGPB_PDF_AVAILABLE_VERSION', 1);

		self::addDefine('SGPB_PDF_ACTION_KEY', 'PopupPdf');
		self::addDefine('SGPB_PDF_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_PDF_ITEM_ID', 221650);
		self::addDefine('SGPB_PDF_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_PDF_KEY', 'POPUP_PDF');
	}
}

SGPBPdfConfig::init();
