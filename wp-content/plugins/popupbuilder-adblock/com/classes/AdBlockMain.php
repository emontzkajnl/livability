<?php
namespace sgpbadb;
use \SgpbPopupExtensionRegister;
use \SGPBAdBlock;

class AdBlockMain
{
	private static $instance = null;
	private $actions;

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
			self::$instance = new AdBlockMain();
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
		require_once(SGPB_AD_BLOCK_HELPERS.'DefaultOptionsData.php');
		require_once(SGPB_AD_BLOCK_CLASSES_PATH.'Actions.php');
		require_once(SGPB_AD_BLOCK_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionAdb.php');
	}

	public function wpInit()
	{
		SGPBAdBlock::addDefine('SG_VERSION_POPUP_AD_BLOCK', '3.1');
		$this->actions = new Actions();
		new SgpbPopupVersionDetectionAdb();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_AD_BLOCK_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_AD_BLOCK_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder Ad Block extension you need to activate Popup Builder plugin', SGPB_AD_BLOCK_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}

		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_AD_BLOCK_FILE_NAME;
		$classPath = SGPB_AD_BLOCK_DYNAMIC_CLASS_PATH.SGPB_AD_BLOCK_EXTENSION_FILE_NAME;
		$className = SGPB_AD_BLOCK_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_AD_BLOCK_KEY,
				'storeURL' => SGPB_AD_BLOCK_STORE_URL,
				'file' => SGPB_AD_BLOCK_FILE_NAME,
				'itemId' => SGPB_AD_BLOCK_ITEM_ID,
				'itemName' => __('Popup Builder AdBlock', SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_AD_BLOCK_AUTHOR,
				'boxLabel' => __('Popup Builder AdBlock License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_AD_BLOCK_FILE_NAME;
		// remove AdBlock extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

AdBlockMain::getInstance();
