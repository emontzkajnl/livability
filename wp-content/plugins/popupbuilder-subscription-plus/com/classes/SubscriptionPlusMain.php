<?php
namespace sgpbsubscriptionplus;
use \SgpbPopupExtensionRegister;
use \SGPBSubscriptionPlusConfig;

class SubscriptionPlusMain
{
	private static $instance = null;
	private $actions;
	private $filters;
	private $ajax;

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
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'SubscriptionPlusRegisterPostType.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'EmailTemplate.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_LIBS.'TinyMce.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_HELPERS.'AdminHelper.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_HELPERS.'ConfigDataHelper.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'Installer.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'Actions.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'Filters.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'SubscriptionPlusForm.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'EmailIntegrations.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionSubscriptionPlus.php');
	}

	public function wpInit()
	{
		SGPBSubscriptionPlusConfig::addDefine('SG_VERSION_POPUP_SUBSCRIPTION_PLUS', '4.8');
		$this->actions = new Actions();
		$this->filters = new Filters();
		$this->autoresponderInit();
		$versionDetection = new SgpbPopupVersionDetectionSubscriptionPlus();
		$versionDetection->compareVersions();
	}

	public function autoresponderInit()
	{
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'autoresponder/AutoresponderRegisterPostType.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'autoresponder/Autoresponder.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'autoresponder/Actions.php');
		require_once(SGPB_SUBSCRIPTION_PLUS_CLASSES_PATH.'autoresponder/Filters.php');
		$this->actions = new AutoresponderActions();
		$this->filters = new AutoresponderFilters();
		$this->ajax = new Ajax();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_SUBSCRIPTION_PLUS_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_SUBSCRIPTION_PLUS_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder '.SGPB_POPUP_TYPE_SUBSCRIPTION_PLUS_DISPLAY_NAME.' extension you need to activate Popup Builder plugin', SG_POPUP_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}

		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_SUBSCRIPTION_PLUS_FILE_NAME;
		$classPath = SGPB_SUBSCRIPTION_PLUS_DYNAMIC_CLASS_PATH.SGPB_SUBSCRIPTION_PLUS_EXTENSION_FILE_NAME;
		$className = SGPB_SUBSCRIPTION_PLUS_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_SUBSCRIPTION_PLUS_KEY,
				'storeURL' => SGPB_SUBSCRIPTION_PLUS_STORE_URL,
				'file' => SGPB_SUBSCRIPTION_PLUS_FILE_NAME,
				'itemId' => SGPB_SUBSCRIPTION_PLUS_ITEM_ID,
				'itemName' => __('Popup Builder '.SGPB_POPUP_TYPE_SUBSCRIPTION_PLUS_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_SUBSCRIPTION_PLUS_AUTHOR,
				'boxLabel' => __('Popup Builder '.SGPB_POPUP_TYPE_SUBSCRIPTION_PLUS_DISPLAY_NAME.' License', SG_POPUP_TEXT_DOMAIN)
			)
		);

		Installer::install();
		SgpbPopupExtensionRegister::register($pluginName, $classPath, $className, $options);
	}

	public function deactivate()
	{
		if (!file_exists(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php')) {
			return false;
		}

		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_SUBSCRIPTION_PLUS_FILE_NAME;
		// remove Popup Builder Subscription Plus extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

SubscriptionPlusMain::getInstance();
