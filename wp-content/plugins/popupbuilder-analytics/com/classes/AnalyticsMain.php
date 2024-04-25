<?php
namespace sgpban;
use \SgpbPopupExtensionRegister;
use \SGPBAnalyticsConfig;

class AnalyticsMain
{
	private static $instance = null;
	private $actions;
	private $filters;

	private function __construct()
	{
		$this->init();
	}

	private function __clone()
	{

	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init()
	{
		$this->includeFiles();
		add_action('init', array($this, 'wpInit'), 1);
		if (method_exists('sgpb\AdminHelper', 'updatesInit') && !has_action('admin_init', array( 'sgpb\AdminHelper', 'updatesInit'))){
			add_action('admin_init', array( 'sgpb\AdminHelper', 'updatesInit'), 9999);
		}
		$this->registerHooks();
	}

	public function includeFiles()
	{
		require_once(SGPB_ANALYTICS_HELPERS.'DefaultOptionsData.php');
		require_once(SGPB_ANALYTICS_HELPERS.'AnalyticsParameters.php');
		require_once(SGPB_ANALYTICS_HELPERS.'AnalyticsFunctions.php');
		require_once(SGPB_ANALYTICS_CLASSES_PATH.'AnalyticsInstall.php');
		require_once(SGPB_ANALYTICS_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_ANALYTICS_CLASSES_PATH.'Actions.php');
		require_once(SGPB_ANALYTICS_CLASSES_PATH.'Filters.php');
		require_once(SGPB_ANALYTICS_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionAnalytics.php');
	}

	public function wpInit()
	{
		SGPBAnalyticsConfig::addDefine('SG_VERSION_POPUP_ANALYTICS', '4.4');
		$this->actions = new Actions();
		new SgpbPopupVersionDetectionAnalytics();
	}

	public function actions()
	{
		$this->actions = new Actions();
		$this->filters = new Filters();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_ANALYTICS_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_ANALYTICS_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder Analytics extension you need to activate Popup Builder plugin', SG_POPUP_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}
		AnalyticsInstall::install();
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_ANALYTICS_FILE_NAME;
		$classPath = SGPB_ANALYTICS_DYNAMIC_CLASS_PATH.SGPB_ANALYTICS_EXTENSION_FILE_NAME;
		$className = SGPB_ANALYTICS_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_ANALYTICS_KEY,
				'storeURL' => SGPB_ANALYTICS_STORE_URL,
				'file' => SGPB_ANALYTICS_FILE_NAME,
				'itemId' => SGPB_ANALYTICS_ITEM_ID,
				'itemName' => __('Popup Builder Analytics', SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_ANALYTICS_AUTHOR,
				'boxLabel' => __('Popup Builder Analytics License', SG_POPUP_TEXT_DOMAIN)
			)
		);

		SgpbPopupExtensionRegister::register($pluginName, $classPath, $className, $options);
	}

	public function deactivate()
	{
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_ANALYTICS_FILE_NAME;
		// remove Analytics extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);
	}
}

AnalyticsMain::getInstance();
