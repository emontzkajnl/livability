<?php
class SGPBMailchimpConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_MAILCHIMP_PATH', WP_PLUGIN_DIR.'/'.SGPB_MAILCHIMP_FOLDER_NAME.'/');
		self::addDefine('SGPB_MAILCHIMP_PUBLIC_URL', plugins_url().'/'.SGPB_MAILCHIMP_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_MAILCHIMP_DYNAMIC_CLASS_PATH', SGPB_MAILCHIMP_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_MAILCHIMP_COM_PATH', SGPB_MAILCHIMP_PATH.'com/');
		self::addDefine('SGPB_MAILCHIMP_PUBLIC_PATH', SGPB_MAILCHIMP_PATH.'public/');
		self::addDefine('SGPB_MAILCHIMP_VIEWS_PATH', SGPB_MAILCHIMP_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_MAILCHIMP_CLASSES_PATH', SGPB_MAILCHIMP_COM_PATH.'classes/');
		self::addDefine('SGPB_MAILCHIMP_VERSION_DETECTION_PATH', SGPB_MAILCHIMP_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_MAILCHIMP_API_PATH', SGPB_MAILCHIMP_CLASSES_PATH.'api/');
		self::addDefine('SGPB_MAILCHIMP_LIB_PATH', SGPB_MAILCHIMP_COM_PATH.'lib/');
		self::addDefine('SGPB_MAILCHIMP_EXTENSION_FILE_NAME', 'PopupBuilderMailchimpExtension.php');
		self::addDefine('SGPB_MAILCHIMP_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderMailchimpExtension');
		self::addDefine('SGPB_MAILCHIMP_HELPERS', SGPB_MAILCHIMP_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_MAILCHIMP', 'mailchimp');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SGPB_MAILCHIMP_PAGE', SG_POPUP_POST_TYPE.'_page_'.SGPB_POPUP_TYPE_MAILCHIMP);
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_MAILCHIMP_URL', plugins_url().'/'.SGPB_MAILCHIMP_FOLDER_NAME.'/');
		self::addDefine('SGPB_MAILCHIMP_JS_URL', SGPB_MAILCHIMP_PUBLIC_URL.'js/');
		self::addDefine('SGPB_MAILCHIMP_CSS_URL', SGPB_MAILCHIMP_PUBLIC_URL.'css/');
		self::addDefine('SGPB_MAILCHIMP_TEXT_DOMAIN', SGPB_MAILCHIMP_FOLDER_NAME);
		self::addDefine('SGPB_MAILCHIMP_PLUGIN_MAIN_FILE', 'PopupBuilderMailchimp.php');
		self::addDefine('SGPB_MAILCHIMP_ACTION_KEY', 'sgpbMailchimp');
		self::addDefine('SGPB_MAILCHIMP_AVALIABLE_VERSION', 1);
		self::addDefine('SGPB_MAILCHIMP_LIST_LIMIT', 200);
		self::addDefine('SGPB_MAILCHIMP_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_MAILCHIMP_ITEM_ID', 4271);
		self::addDefine('SGPB_MAILCHIMP_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_MAILCHIMP_KEY', 'POPUP_MAILCHIMP');
	}
}

SGPBMailchimpConfig::init();
