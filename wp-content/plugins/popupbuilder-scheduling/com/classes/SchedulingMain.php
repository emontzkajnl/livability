<?php
namespace sgpbscheduling;
use \SgpbPopupExtensionRegister;
use \SGPBSchedulingConfig;

class SchedulingMain
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
		if(!isset(self::$instance)) {
			self::$instance = new SchedulingMain();
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
		require_once(SGPB_SCHEDULING_CLASSES_PATH.'AdminHelper.php');
		require_once(SGPB_SCHEDULING_CLASSES_PATH.'Filters.php');
		require_once(SGPB_SCHEDULING_CLASSES_PATH.'Actions.php');
		require_once(SGPB_SCHEDULING_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionScheduling.php');
	}

	public function wpInit()
	{
		SGPBSchedulingConfig::addDefine('SG_VERSION_POPUP_SCHEDULING', '3.1');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionScheduling();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_SCHEDULING_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_SCHEDULING_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder Scheduling extension you need to activate Popup Builder plugin', SGPB_SCHEDULING_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_SCHEDULING_FILE_NAME;
		$classPath = SGPB_SCHEDULING_DYNAMIC_CLASS_PATH.SGPB_SCHEDULING_EXTENSION_FILE_NAME;
		$className = SGPB_SCHEDULING_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_SCHEDULING_KEY,
				'storeURL' => SGPB_SCHEDULING_STORE_URL,
				'file' => SGPB_SCHEDULING_FILE_NAME,
				'itemId' => SGPB_SCHEDULING_ITEM_ID,
				'itemName' => __('Popup Builder '.SGPB_POPUP_TYPE_SCHEDULING_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_SCHEDULING_AUTHOR,
				'boxLabel' => __('Popup Builder '.SGPB_POPUP_TYPE_SCHEDULING_DISPLAY_NAME.' License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_SCHEDULING_FILE_NAME;
		// remove scheduling extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

SchedulingMain::getInstance();
