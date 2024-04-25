<?php
namespace sgpbwoo;
use \SgpbPopupExtensionRegister;
use \SGPBWOOConfig;

class WooCommerceMain
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
		require_once(SGPB_WOO_HELPERS.'GetProductsHelper.php');
		require_once(SGPB_WOO_HELPERS.'AdminHelper.php');
		require_once(SGPB_WOO_CLASSES_PATH.'ConditionBuilder.php');
		require_once(SGPB_WOO_CLASSES_PATH.'Actions.php');
		require_once(SGPB_WOO_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_WOO_CLASSES_PATH.'Filters.php');
		require_once(SGPB_WOO_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionWoocommerce.php');

		new Ajax();
	}

	public function wpInit()
	{
		SGPBWOOConfig::addDefine('SG_VERSION_POPUP_WOO', '3.2');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionWoocommerce();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_WOO_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_WOO_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder WooCommerce extension you need to activate Popup Builder plugin', SGPB_AD_BLOCK_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_WOO_FILE_NAME;
		$classPath = SGPB_WOO_DYNAMIC_CLASS_PATH.SGPB_WOO_EXTENSION_FILE_NAME;
		$className = SGPB_WOO_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_WOO_KEY,
				'storeURL' => SGPB_WOO_STORE_URL,
				'file' => SGPB_WOO_FILE_NAME,
				'itemId' => SGPB_WOO_ITEM_ID,
				'itemName' => __('Popup Builder WooCommerce', SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_WOO_AUTHOR,
				'boxLabel' => __('Popup Builder WooCommerce License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_WOO_FILE_NAME;
		// remove WooCommerce extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

WooCommerceMain::getInstance();
