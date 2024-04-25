<?php
class SGPBRestrictionConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_RESTRICTION_PATH', WP_PLUGIN_DIR.'/'.SGPB_RESTRICTION_FOLDER_NAME.'/');
		self::addDefine('SGPB_RESTRICTION_PUBLIC_URL', plugins_url().'/'.SGPB_RESTRICTION_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_RESTRICTION_DYNAMIC_CLASS_PATH', SGPB_RESTRICTION_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_RESTRICTION_COM_PATH', SGPB_RESTRICTION_PATH.'com/');
		self::addDefine('SGPB_RESTRICTION_PUBLIC_PATH', SGPB_RESTRICTION_PATH.'public/');
		self::addDefine('SGPB_RESTRICTION_VIEWS_PATH', SGPB_RESTRICTION_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_RESTRICTION_CLASSES_PATH', SGPB_RESTRICTION_COM_PATH.'classes/');
		self::addDefine('SGPB_RESTRICTION_VERSION_DETECTION_PATH', SGPB_RESTRICTION_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_RESTRICTION_EXTENSION_FILE_NAME', 'SGPBPopupBuilderRestrictionExtension.php');
		self::addDefine('SGPB_RESTRICTION_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderRestrictionExtension');
		self::addDefine('SGPB_RESTRICTION_HELPERS', SGPB_RESTRICTION_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_RESTRICTION', 'ageRestriction');
		self::addDefine('SGPB_POPUP_TYPE_RESTRICTION_DISPLAY_NAME', 'Yes/No buttons');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_RESTRICTION_URL', plugins_url().'/'.SGPB_RESTRICTION_FOLDER_NAME.'/');

		self::addDefine('SGPB_RESTRICTION_JS_URL', SGPB_RESTRICTION_PUBLIC_URL.'js/');
		self::addDefine('SGPB_RESTRICTION_CSS_URL', SGPB_RESTRICTION_PUBLIC_URL.'css/');
		self::addDefine('SGPB_RESTRICTION_TEXT_DOMAIN', SGPB_RESTRICTION_FOLDER_NAME);
		self::addDefine('SGPB_RESTRICTION_PLUGIN_MAIN_FILE', 'PopupBuilderRestriction.php');
		self::addDefine('SGPB_RESTRICTION_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_RESTRICTION_ACTION_KEY', 'PopupRestriction');
		self::addDefine('SGPB_RESTRICTION_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_RESTRICTION_ITEM_ID', 106579);
		self::addDefine('SGPB_RESTRICTION_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_RESTRICTION_KEY', 'POPUP_RESTRICTION');
	}
}

SGPBRestrictionConfig::init();
