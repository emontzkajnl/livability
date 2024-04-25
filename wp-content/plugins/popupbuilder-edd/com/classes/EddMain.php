<?php
namespace sgpbedd;
use \SgpbPopupExtensionRegister;
use \SGPBEDDConfig;

class EDDMain
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
		require_once(SGPB_EDD_HELPERS.'GetProductsHelper.php');
		require_once(SGPB_EDD_HELPERS.'AdminHelper.php');
		require_once(SGPB_EDD_CLASSES_PATH.'ConditionBuilder.php');
		require_once(SGPB_EDD_CLASSES_PATH.'Actions.php');
		require_once(SGPB_EDD_CLASSES_PATH.'Filters.php');
		require_once(SGPB_EDD_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionEdd.php');
	}

	public function wpInit()
	{
		SGPBEDDConfig::addDefine('SG_VERSION_POPUP_EDD', '2.1');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionEdd();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_EDD_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_EDD_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder Easy Digital Downloads extension you need to activate Popup Builder plugin', SG_POPUP_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_EDD_FILE_NAME;
		$classPath = SGPB_EDD_DYNAMIC_CLASS_PATH.SGPB_EDD_EXTENSION_FILE_NAME;
		$className = SGPB_EDD_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_EDD_KEY,
				'storeURL' => SGPB_EDD_STORE_URL,
				'file' => SGPB_EDD_FILE_NAME,
				'itemId' => SGPB_EDD_ITEM_ID,
				'itemName' => __('Popup Builder Easy Digital Downloads', SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_EDD_AUTHOR,
				'boxLabel' => __('Popup Builder Easy Digital Downloads License', SG_POPUP_TEXT_DOMAIN)
			)
		);
		SgpbPopupExtensionRegister::register($pluginName, $classPath, $className, $options);
	}

	public function deactivate()
	{
		if (!file_exists(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php')) {
			return false;
		}

		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_EDD_FILE_NAME;
		// remove Easy Digital Downloads extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

EDDMain::getInstance();
