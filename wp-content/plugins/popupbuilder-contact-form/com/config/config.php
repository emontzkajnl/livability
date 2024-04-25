<?php
class SGPBContactFormConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_CONTACT_FORM_PATH', WP_PLUGIN_DIR.'/'.SGPB_CONTACT_FORM_FOLDER_NAME.'/');
		self::addDefine('SGPB_CONTACT_FORM_PUBLIC_URL', plugins_url().'/'.SGPB_CONTACT_FORM_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_CONTACT_FORM_DYNAMIC_CLASS_PATH', SGPB_CONTACT_FORM_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_CONTACT_FORM_COM_PATH', SGPB_CONTACT_FORM_PATH.'com/');
		self::addDefine('SGPB_CONTACT_FORM_PUBLIC_PATH', SGPB_CONTACT_FORM_PATH.'public/');
		self::addDefine('SGPB_CONTACT_FORM_VIEWS_PATH', SGPB_CONTACT_FORM_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_CONTACT_FORM_CLASSES_PATH', SGPB_CONTACT_FORM_COM_PATH.'classes/');
		self::addDefine('SGPB_CONTACT_FORM_VERSION_DETECTION_PATH', SGPB_CONTACT_FORM_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_CONTACT_FORM_DATA_TABLE_PATH', SGPB_CONTACT_FORM_CLASSES_PATH.'dataTable/');
		self::addDefine('SGPB_CONTACT_FORM_EXTENSION_FILE_NAME', 'PopupBuilderIContactFormExtension.php');
		self::addDefine('SGPB_CONTACT_FORM_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderContactFormExtension');
		self::addDefine('SGPB_CONTACT_FORM_HELPERS', SGPB_CONTACT_FORM_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_CONTACT_FORM', 'contactForm');
		self::addDefine('SGPB_POPUP_TYPE_CONTACT_FORM_DISPLAY_NAME', 'Contact Form');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_CONTACTED_SUBSCRIBERS_TABLE_NAME', 'sgpb_contacted_subscribers');
		self::addDefine('SGPB_CONTACT_FORM_URL', plugins_url().'/'.SGPB_CONTACT_FORM_FOLDER_NAME.'/');

		self::addDefine('SGPB_CONTACT_FORM_PLUGIN_URL', 'https://wordpress.org/plugins/contactForm/');
		self::addDefine('SGPB_CONTACT_FORM_JS_URL', SGPB_CONTACT_FORM_PUBLIC_URL.'js/');
		self::addDefine('SGPB_CONTACT_FORM_CSS_URL', SGPB_CONTACT_FORM_PUBLIC_URL.'css/');
		self::addDefine('SGPB_CONTACT_FORM_TEXT_DOMAIN', SGPB_CONTACT_FORM_FOLDER_NAME);
		self::addDefine('SGPB_CONTACT_FORM_PLUGIN_MAIN_FILE', 'PopupBuilderContactForm.php');
		self::addDefine('SGPB_CONTACT_FORM_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_CONTACT_CONTACTED_PAGE', 'sgpbContacted');

		self::addDefine('SGPB_CONTACT_FORM_ACTION_KEY', 'PopupContactForm');
		self::addDefine('SGPB_CONTACT_FORM_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_CONTACT_FORM_ITEM_ID', 106563);
		self::addDefine('SGPB_CONTACT_FORM_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_CONTACT_FORM_KEY', 'POPUP_CONTACT_FORM');

		self::addDefine('SGPB_CF_FORM_BUILDER', SGPB_CONTACT_FORM_COM_PATH.'/libs/formBuilder/');
		self::addDefine('SGPB_FORM_CLASSES_FIELDS', SGPB_CF_FORM_BUILDER.'/classes/fields/');
		self::addDefine('SGPB_CF_FORM_BUILDER_URL', SGPB_CONTACT_FORM_URL.'com/libs/formBuilder/');
		self::addDefine('SGPB_CF_FORM_BUILDER_VIEWS', SGPB_CF_FORM_BUILDER.'views/');

		self::addDefine('SGPB_CONTACT_FORM_ERROR_MESSAGE', __('Unable to send.', SG_POPUP_TEXT_DOMAIN));
		self::addDefine('SGPB_CONTACT_FORM_REQUIRED_MESSAGE', __('This field is required.', SG_POPUP_TEXT_DOMAIN));
		self::addDefine('SGPB_CONTACT_FORM_EMAIL_MESSAGE', __('Please enter a valid email.', SG_POPUP_TEXT_DOMAIN));
	}
}

SGPBContactFormConfig::init();
