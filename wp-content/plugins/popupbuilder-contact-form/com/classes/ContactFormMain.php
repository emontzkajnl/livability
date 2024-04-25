<?php
namespace sgpbcontactform;
use \SgpbPopupExtensionRegister;
use \SGPBContactFormConfig;

class ContactFormMain
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
		require_once(SGPB_CONTACT_FORM_HELPERS.'ConfigDataHelper.php');
		require_once(SGPB_CONTACT_FORM_HELPERS.'AdminHelper.php');
		require_once(SGPB_CONTACT_FORM_CLASSES_PATH.'Actions.php');
		require_once(SGPB_CONTACT_FORM_CLASSES_PATH.'Installer.php');
		require_once(SGPB_CONTACT_FORM_CLASSES_PATH.'Filters.php');
		require_once(SGPB_CONTACT_FORM_CLASSES_PATH.'ContactformForm.php');
		require_once(SGPB_CONTACT_FORM_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_CONTACT_FORM_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionContactForm.php');
	}

	public function wpInit()
	{
		SGPBContactFormConfig::addDefine('SG_VERSION_POPUP_CONTACT_FORM', '3.3');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionContactForm();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_CONTACT_FORM_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_CONTACT_FORM_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder '.SGPB_POPUP_TYPE_CONTACT_FORM_DISPLAY_NAME.' extension you need to activate Popup Builder plugin', SG_POPUP_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}
		Installer::install();
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_CONTACT_FORM_FILE_NAME;
		$classPath = SGPB_CONTACT_FORM_DYNAMIC_CLASS_PATH.SGPB_CONTACT_FORM_EXTENSION_FILE_NAME;
		$className = SGPB_CONTACT_FORM_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_CONTACT_FORM_KEY,
				'storeURL' => SGPB_CONTACT_FORM_STORE_URL,
				'file' => SGPB_CONTACT_FORM_FILE_NAME,
				'itemId' => SGPB_CONTACT_FORM_ITEM_ID,
				'itemName' => __('Popup Builder '.SGPB_POPUP_TYPE_CONTACT_FORM_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_CONTACT_FORM_AUTHOR,
				'boxLabel' => __('Popup Builder '.SGPB_POPUP_TYPE_CONTACT_FORM_DISPLAY_NAME.' License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_CONTACT_FORM_FILE_NAME;
		// remove Popup Builder Contact Form extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

ContactFormMain::getInstance();
