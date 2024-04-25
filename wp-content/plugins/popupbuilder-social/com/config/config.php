<?php
class SGPBSocialConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_SOCIAL_PATH', WP_PLUGIN_DIR.'/'.SGPB_SOCIAL_FOLDER_NAME.'/');
		self::addDefine('SGPB_SOCIAL_DYNAMIC_CLASS_PATH', SGPB_SOCIAL_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_SOCIAL_PUBLIC_URL', plugins_url().'/'.SGPB_SOCIAL_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_SOCIAL_COM_PATH', SGPB_SOCIAL_PATH.'com/');
		self::addDefine('SGPB_SOCIAL_PUBLIC_PATH', SGPB_SOCIAL_PATH.'public/');
		self::addDefine('SGPB_SOCIAL_VIEWS_PATH', SGPB_SOCIAL_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_SOCIAL_CLASSES_PATH', SGPB_SOCIAL_COM_PATH.'classes/');
		self::addDefine('SGPB_SOCIAL_VERSION_DETECTION_PATH', SGPB_SOCIAL_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_SOCIAL_EXTENSION_FILE_NAME', 'SGPBPopupBuilderSocialExtension.php');
		self::addDefine('SGPB_SOCIAL_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderSocialExtension');
		self::addDefine('SGPB_SOCIAL_HELPERS', SGPB_SOCIAL_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_SOCIAL', 'social');
		self::addDefine('SGPB_POPUP_TYPE_SOCIAL_DISPLAY_NAME', 'Social');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_SOCIAL_URL', plugins_url().'/'.SGPB_SOCIAL_FOLDER_NAME.'/');

		self::addDefine('SGPB_SOCIAL_PLUGIN_URL', 'https://wordpress.org/plugins/social/');
		self::addDefine('SGPB_SOCIAL_JS_URL', SGPB_SOCIAL_PUBLIC_URL.'js/');
		self::addDefine('SGPB_SOCIAL_CSS_URL', SGPB_SOCIAL_PUBLIC_URL.'css/');
		self::addDefine('SGPB_SOCIAL_TEXT_DOMAIN', SGPB_SOCIAL_FOLDER_NAME);
		self::addDefine('SGPB_SOCIAL_PLUGIN_MAIN_FILE', 'PopupBuilderSocial.php');
		self::addDefine('SGPB_SOCIAL_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_SOCIAL_ACTION_KEY', 'PopupSocial');
		self::addDefine('SGPB_SOCIAL_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_SOCIAL_ITEM_ID', 106573);
		self::addDefine('SGPB_SOCIAL_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_SOCIAL_KEY', 'POPUP_SOCIAL');
	}
}

SGPBSocialConfig::init();
