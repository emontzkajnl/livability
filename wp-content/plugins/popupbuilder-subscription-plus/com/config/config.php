<?php
class SGPBSubscriptionPlusConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_PATH', WP_PLUGIN_DIR.'/'.SGPB_SUBSCRIPTION_PLUS_FOLDER_NAME.'/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_URL', plugins_url().'/'.SGPB_SUBSCRIPTION_PLUS_FOLDER_NAME.'/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_DYNAMIC_CLASS_PATH', SGPB_SUBSCRIPTION_PLUS_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_PUBLIC_URL',SGPB_SUBSCRIPTION_PLUS_URL.'public/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_COM_PATH', SGPB_SUBSCRIPTION_PLUS_PATH.'com/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_PUBLIC_PATH', SGPB_SUBSCRIPTION_PLUS_PATH.'public/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH', SGPB_SUBSCRIPTION_PLUS_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_TEMPLATES_PATH', SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'emailTemplates/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH', SGPB_SUBSCRIPTION_PLUS_COM_PATH.'classes/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_VERSION_DETECTION_PATH', SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_EXTENSION_FILE_NAME', 'PopupBuilderSubscriptionPlusExtension.php');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderSubscriptionPlusExtension');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_HELPERS', SGPB_SUBSCRIPTION_PLUS_COM_PATH.'helpers/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_LIBS', SGPB_SUBSCRIPTION_PLUS_COM_PATH.'libs/');
		self::addDefine('SG_SUBSCRIPTION_PLUS_URL', plugins_url().'/'.SGPB_SUBSCRIPTION_PLUS_FOLDER_NAME.'/');
		self::addDefine('SG_SUBSCRIPTION_PLUS_PUBLIC_URL', SG_SUBSCRIPTION_PLUS_URL.'public/');
		self::addDefine('SG_SUBSCRIPTION_PLUS_VIEWS_URL', SG_SUBSCRIPTION_PLUS_PUBLIC_URL.'views/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_TEMPLATES_URL', SG_SUBSCRIPTION_PLUS_VIEWS_URL.'emailTemplates/');
		self::addDefine('SGPB_POPUP_TYPE_SUBSCRIPTION_PLUS', 'subscription');
		self::addDefine('SGPB_SUBSCRIBERS_TABLE_NAME', 'sgpb_subscribers');
		self::addDefine('SGPB_POPUP_TYPE_SUBSCRIPTION_PLUS_DISPLAY_NAME', 'Subscription Plus');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEMPLATE_POST_TYPE', 'sgpbtemplate');
		self::addDefine('SG_POPUP_AUTORESPONDER_POST_TYPE', 'sgpbautoresponder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SG_POPUP_SUBSCRIBERS_PAGE', 'sgpbSubscribers');
		self::addDefine('SG_SUBSCRIPTION_PLUS_PAGE', SG_POPUP_POST_TYPE.'_page_'.SG_POPUP_TEMPLATE_POST_TYPE);
		self::addDefine('SG_SUBSCRIPTION_PLUS_EMAIL_CREATION_PAGE_URL', admin_url().'edit.php?post_type='.SG_POPUP_POST_TYPE.'&page='.SG_POPUP_TEMPLATE_POST_TYPE);
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_URL', plugins_url().'/'.SGPB_SUBSCRIPTION_PLUS_FOLDER_NAME.'/');

		// autoresponder events
		self::addDefine('SGPB_AUTORESPONDER_EVENT_PAGE_CREATION', 'page_creation');
		self::addDefine('SGPB_AUTORESPONDER_EVENT_ALL_POSTS_CREATION', 'all_posts');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_PLUGIN_URL', 'https://wordpress.org/plugins/subscriptionplus/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_JS_URL', SGPB_SUBSCRIPTION_PLUS_PUBLIC_URL.'js/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_MCE_JS_URL', SGPB_SUBSCRIPTION_PLUS_JS_URL.'mce/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_CSS_URL', SGPB_SUBSCRIPTION_PLUS_PUBLIC_URL.'css/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_IMG_URL', SGPB_SUBSCRIPTION_PLUS_PUBLIC_URL.'img/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_TEMPLATE_IMG_URL', SGPB_SUBSCRIPTION_PLUS_IMG_URL.'templates/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_TEXT_DOMAIN', SGPB_SUBSCRIPTION_PLUS_FOLDER_NAME);
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_PLUGIN_MAIN_FILE', 'PopupBuilderSubscriptionPlus.php');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_SUBSCRIPTION_PLUS_ACTION_KEY', 'PopupSubscriptionPlus');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_SUBSCRIPTION_PLUS_ITEM_ID', 130653);
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_KEY', 'POPUP_SUBSCRIPTION_PLUS');

		// form builder config
		self::addDefine('SGPB_SUBSCRIPTION_FORM_BUILDER', SGPB_SUBSCRIPTION_PLUS_COM_PATH.'/libs/formBuilder/');
		self::addDefine('SGPB_SUBSCRIPTION_FORM_BUILDER_URL', SGPB_SUBSCRIPTION_PLUS_URL.'com/libs/formBuilder/');
		self::addDefine('SGPB_SUBSCRIPTION_FORM_BUILDER_VIEWS', SGPB_SUBSCRIPTION_FORM_BUILDER.'views/');
		self::addDefine('SGPB_DEFAULT_EMAIL_TEMPLATES_HEADER', 'sgpb-default-email-templates-header');
		self::addDefine('SGPB_DEFAULT_EMAIL_TEMPLATES_FOOTER', 'sgpb-default-email-templates-footer');
		self::addDefine('SG_POPUP_EMAIL_INTEGRATIONS_PAGE', SG_POPUP_POST_TYPE.'_page_'.'sgpbEmailIntegrations');
		self::addDefine('SG_POPUP_EMAIL_INTEGRATIONS_SCREEN', 'sgpbEmailIntegrations');
		self::addDefine('SG_POPUP_EMAIL_INTEGRATIONS_PATH', SGPB_SUBSCRIPTION_PLUS_LIBS.'emailIntegrations/');
		self::addDefine('SG_POPUP_EMAIL_INTEGRATIONS_URL', SGPB_SUBSCRIPTION_PLUS_URL.'com/libs/emailIntegrations/');
		self::addDefine('SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS', 'sgpb-email-integrations-settings');
	}
}

SGPBSubscriptionPlusConfig::init();
