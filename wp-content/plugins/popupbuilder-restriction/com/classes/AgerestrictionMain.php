<?php
namespace sgpbrestriction;
use \SgpbPopupExtensionRegister;
use \SGPBRestrictionConfig;

class Agerestriction
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
		require_once(SGPB_RESTRICTION_HELPERS.'AdminHelper.php');
		require_once(SGPB_RESTRICTION_CLASSES_PATH.'Actions.php');
		require_once(SGPB_RESTRICTION_CLASSES_PATH.'Filters.php');
		require_once(SGPB_RESTRICTION_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionRestriction.php');
	}

	public function wpInit()
	{
		SGPBRestrictionConfig::addDefine('SG_VERSION_POPUP_RESTRICTION', '3.2');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionRestriction();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_RESTRICTION_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_RESTRICTION_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder '.SGPB_POPUP_TYPE_RESTRICTION_DISPLAY_NAME.' extension you need to activate Popup Builder plugin', SGPB_RESTRICTION_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}

		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_RESTRICTION_FILE_NAME;
		$classPath = SGPB_RESTRICTION_DYNAMIC_CLASS_PATH.SGPB_RESTRICTION_EXTENSION_FILE_NAME;
		$className = SGPB_RESTRICTION_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_RESTRICTION_KEY,
				'storeURL' => SGPB_RESTRICTION_STORE_URL,
				'file' => SGPB_RESTRICTION_FILE_NAME,
				'itemId' => SGPB_RESTRICTION_ITEM_ID,
				'itemName' => __('Popup Builder '.SGPB_POPUP_TYPE_RESTRICTION_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_RESTRICTION_AUTHOR,
				'boxLabel' => __('Popup Builder '.SGPB_POPUP_TYPE_RESTRICTION_DISPLAY_NAME.' License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_RESTRICTION_FILE_NAME;
		// remove Popup Builder Restriction extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

Agerestriction::getInstance();
