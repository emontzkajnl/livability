<?php
namespace sgpbaw;
use \SgpbPopupExtensionRegister;
use \SGPBAWeberConfig;

class AWeberMain
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
		require_once(SGPB_AWEBER_HELPERS.'DefaultOptionsData.php');
		require_once(SGPB_AWEBER_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_AWEBER_CLASSES_PATH.'AWeberApi.php');
		require_once(SGPB_AWEBER_CLASSES_PATH.'Actions.php');
		require_once(SGPB_AWEBER_CLASSES_PATH.'Filters.php');
		require_once(SGPB_AWEBER_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionAweber.php');
	}

	public function wpInit()
	{
		SGPBAWeberConfig::addDefine('SG_VERSION_POPUP_AWEBER', '3.2');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionAweber();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_AWEBER_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_AWEBER_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder AWeber extension you need to activate Popup Builder plugin', SGPB_AWEBER_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_AWEBER_FILE_NAME;
		$classPath = SGPB_AWEBER_DYNAMIC_CLASS_PATH.SGPB_AWEBER_EXTENSION_FILE_NAME;
		$className = SGPB_AWEBER_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_AWEBER_KEY,
				'storeURL' => SGPB_AWEBER_STORE_URL,
				'file' => SGPB_AWEBER_FILE_NAME,
				'itemId' => SGPB_AWEBER_ITEM_ID,
				'itemName' => __('Popup Builder AWeber', SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_AWEBER_AUTHOR,
				'boxLabel' => __('Popup Builder AWeber License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_AWEBER_FILE_NAME;
		// remove AWeber extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

AWeberMain::getInstance();
