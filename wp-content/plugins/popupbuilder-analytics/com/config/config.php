<?php
class SGPBAnalyticsConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_ANALYTICS_PATH', WP_PLUGIN_DIR.'/'.SGPB_ANALYTICS_FOLDER_NAME.'/');
		self::addDefine('SGPB_ANALYTICS_PUBLIC_URL', plugins_url().'/'.SGPB_ANALYTICS_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_ANALYTICS_DYNAMIC_CLASS_PATH', SGPB_ANALYTICS_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_ANALYTICS_COM_PATH', SGPB_ANALYTICS_PATH.'com/');
		self::addDefine('SGPB_ANALYTICS_PUBLIC_PATH', SGPB_ANALYTICS_PATH.'public/');
		self::addDefine('SGPB_ANALYTICS_VIEWS_PATH', SGPB_ANALYTICS_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_ANALYTICS_CLASSES_PATH', SGPB_ANALYTICS_COM_PATH.'classes/');
		self::addDefine('SGPB_ANALYTICS_VERSION_DETECTION_PATH', SGPB_ANALYTICS_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_ANALYTICS_LIB_PATH', SGPB_ANALYTICS_COM_PATH.'lib/');
		self::addDefine('SGPB_ANALYTICS_EXTENSION_FILE_NAME', 'PopupBuilderAnalyticsExtension.php');
		self::addDefine('SGPB_ANALYTICS_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderAnalyticsExtension');
		self::addDefine('SGPB_ANALYTICS_HELPERS', SGPB_ANALYTICS_COM_PATH.'helpers/');
		self::addDefine('SGPB_POPUP_TYPE_ANALYTICS', 'analytics');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');

		self::addDefine('SGPB_ANALYTICS_URL', plugins_url().'/'.SGPB_ANALYTICS_FOLDER_NAME.'/');
		self::addDefine('SGPB_ANALYTICS_JS_URL', SGPB_ANALYTICS_PUBLIC_URL.'js/');
		self::addDefine('SGPB_ANALYTICS_CSS_URL', SGPB_ANALYTICS_PUBLIC_URL.'css/');
		self::addDefine('SGPB_ANALYTICS_TEXT_DOMAIN', SGPB_ANALYTICS_FOLDER_NAME);
		self::addDefine('SGPB_ANALYTICS_PLUGIN_MAIN_FILE', 'PopupBuilderAnalytics.php');
		self::addDefine('SGPB_ANALYTICS_ACTION_KEY', 'sgpbAnalytics');
		self::addDefine('SGPB_ANALYTICS_POPULAR_LIMIT', 10);
		self::addDefine('SGPB_ANALYTICS_TABLE_NAME', 'sgpb_analytics');

		self::addDefine('SGPB_ANALYTICS_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_ANALYTICS_ITEM_ID', 4289);
		self::addDefine('SGPB_ANALYTICS_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_ANALYTICS_KEY', 'POPUP_ANALYTICS');
	}

	public static function sgpbEvents()
	{

		$events = array(
			'sgpbLoad'        => array('label' => __('On load', SG_POPUP_TEXT_DOMAIN), 'key' => 2),
			'sgpbOnScroll'    => array('label' => __('On scroll', SG_POPUP_TEXT_DOMAIN), 'key' => 3),
			'Click'       => array('label' => __('On click', SG_POPUP_TEXT_DOMAIN), 'key' => 4),
			'Hover'       => array('label' => __('On hover', SG_POPUP_TEXT_DOMAIN), 'key' => 5),
			'sgpbInactivity'  => array('label' => __('On inactivity', SG_POPUP_TEXT_DOMAIN), 'key' => 6),
			'close'           => array('label' => __('On close', SG_POPUP_TEXT_DOMAIN), 'key' => 7),
			'sgpbInsideclick' => array('label' => __('Inside popup click', SG_POPUP_TEXT_DOMAIN), 'key' => 8),
			'sgpbConfirm'     => array('label' => __('Confirm class', SG_POPUP_TEXT_DOMAIN), 'key' => 9),
			'sgpbIframe'      => array('label' => __('Iframe class', SG_POPUP_TEXT_DOMAIN), 'key' => 10),
			'sgpbAttronload'  => array('label' => __('Shortcode on load', SG_POPUP_TEXT_DOMAIN), 'key' => 11),
			'sgpbSubscriptionSuccess' => array('label' => __('Subscription success', SG_POPUP_TEXT_DOMAIN), 'key' => 12),
			'sgpbContactSuccess' => array('label' => __('Contact success', SG_POPUP_TEXT_DOMAIN), 'key' => 13),
			'sgpbExitIntent' => array('label' => __('Exit intent', SG_POPUP_TEXT_DOMAIN), 'key' => 14),
			'sgpbPopupContentClick' => array('label' => __('Popup content click', SG_POPUP_TEXT_DOMAIN), 'key' => 15),
			'sgpbRecentSales' => array('label' => __('Recent Sales', SG_POPUP_TEXT_DOMAIN), 'key' => 16)
		);

		return apply_filters('sgpbAnalyticsEvents', $events);
	}
}

SGPBAnalyticsConfig::init();
