<?php
class SGPBAgeverificationConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_AGE_VERIFICATION_PATH', WP_PLUGIN_DIR.'/'.SGPB_AGE_VERIFICATION_FOLDER_NAME.'/');
		self::addDefine('SGPB_AGE_VERIFICATION_DYNAMIC_CLASS_PATH', SGPB_AGE_VERIFICATION_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_AGE_VERIFICATION_PUBLIC_URL', plugins_url().'/'.SGPB_AGE_VERIFICATION_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_AGE_VERIFICATION_COM_PATH', SGPB_AGE_VERIFICATION_PATH.'com/');
		self::addDefine('SGPB_AGE_VERIFICATION_PUBLIC_PATH', SGPB_AGE_VERIFICATION_PATH.'public/');
		self::addDefine('SGPB_AGE_VERIFICATION_VIEWS_PATH', SGPB_AGE_VERIFICATION_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_AGE_VERIFICATION_CLASSES_PATH', SGPB_AGE_VERIFICATION_COM_PATH.'classes/');
		self::addDefine('SGPB_AGE_VERIFICATION_VERSION_DETECTION_PATH', SGPB_AGE_VERIFICATION_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_AGE_VERIFICATION_EXTENSION_FILE_NAME', 'PopupBuilderAgeVerificationExtension.php');
		self::addDefine('SGPB_AGE_VERIFICATION_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderAgeverificationExtension');
		self::addDefine('SGPB_AGE_VERIFICATION_HELPERS', SGPB_AGE_VERIFICATION_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_AGE_VERIFICATION', 'ageVerification');
		self::addDefine('SGPB_POPUP_TYPE_AGE_VERIFICATION_DISPLAY_NAME', 'Age Restriction');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_AGE_VERIFICATION_URL', plugins_url().'/'.SGPB_AGE_VERIFICATION_FOLDER_NAME.'/');

		self::addDefine('SGPB_AGE_VERIFICATION_JS_URL', SGPB_AGE_VERIFICATION_PUBLIC_URL.'js/');
		self::addDefine('SGPB_AGE_VERIFICATION_CSS_URL', SGPB_AGE_VERIFICATION_PUBLIC_URL.'css/');
		self::addDefine('SGPB_AGE_VERIFICATION_TEXT_DOMAIN', SGPB_AGE_VERIFICATION_FOLDER_NAME);
		self::addDefine('SGPB_AGE_VERIFICATION_PLUGIN_MAIN_FILE', 'PopupBuilderAgeverification.php');
		self::addDefine('SGPB_AGE_VERIFICATION_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_AGE_VERIFICATION_ACTION_KEY', 'PopupAgeverification');
		self::addDefine('SGPB_AGE_VERIFICATION_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_AGE_VERIFICATION_ITEM_ID', 237863);
		self::addDefine('SGPB_AGE_VERIFICATION_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_AGE_VERIFICATION_KEY', 'POPUP_AGE_VERIFICATION');
	}
}

SGPBAgeverificationConfig::init();
