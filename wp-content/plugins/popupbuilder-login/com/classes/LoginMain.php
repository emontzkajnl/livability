<?php
namespace sgpblogin;
use \SgpbPopupExtensionRegister;
use \SGPBLoginConfig;

class login
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
		add_action('init', array($this, 'wpInit'));
		if (method_exists('sgpb\AdminHelper', 'updatesInit') && !has_action('admin_init', array( 'sgpb\AdminHelper', 'updatesInit'))){
			add_action('admin_init', array( 'sgpb\AdminHelper', 'updatesInit'), 9999);
		}
		$this->registerHooks();
	}

	public function includeFiles()
	{
		require_once(SGPB_LOGIN_HELPERS.'AdminHelper.php');
		require_once(SGPB_LOGIN_CLASSES_PATH.'Actions.php');
		require_once(SGPB_LOGIN_CLASSES_PATH.'Filters.php');
		require_once(SGPB_LOGIN_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_LOGIN_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionLogin.php');
	}

	public function wpInit()
	{
		SGPBLoginConfig::addDefine('SG_VERSION_POPUP_LOGIN', '3.1');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionLogin();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_LOGIN_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_LOGIN_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder Login extension you need to activate Popup Builder plugin', SGPB_AD_BLOCK_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}

		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_LOGIN_FILE_NAME;
		$classPath = SGPB_LOGIN_DYNAMIC_CLASS_PATH.SGPB_LOGIN_EXTENSION_FILE_NAME;
		$className = SGPB_LOGIN_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_LOGIN_KEY,
				'storeURL' => SGPB_LOGIN_STORE_URL,
				'file' => SGPB_LOGIN_FILE_NAME,
				'itemId' => SGPB_LOGIN_ITEM_ID,
				'itemName' => __('Popup Builder Login', SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_LOGIN_AUTHOR,
				'boxLabel' => __('Popup Builder Login License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_LOGIN_FILE_NAME;
		// remove Popup Builder extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

login::getInstance();

