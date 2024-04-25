<?php
namespace sgpbm;
use \SGPBMailchimpConfig;
use \SgpbPopupExtensionRegister;

class MailchimpMain
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
		add_action('init', array($this, 'actions'));
		if (method_exists('sgpb\AdminHelper', 'updatesInit') && !has_action('admin_init', array( 'sgpb\AdminHelper', 'updatesInit'))){
			add_action('admin_init', array( 'sgpb\AdminHelper', 'updatesInit'), 9999);
		}
		$this->registerHooks();
	}

	public function includeFiles()
	{
		require_once(SGPB_MAILCHIMP_HELPERS.'DefaultOptionsData.php');
		require_once(SGPB_MAILCHIMP_API_PATH.'Mailchimp.php');
		require_once(SGPB_MAILCHIMP_API_PATH.'MailchimpSingleton.php');
		require_once(SGPB_MAILCHIMP_API_PATH.'MailchimpApi.php');
		require_once(SGPB_MAILCHIMP_CLASSES_PATH.'Ajax.php');
		require_once(SGPB_MAILCHIMP_CLASSES_PATH.'Actions.php');
		require_once(SGPB_MAILCHIMP_CLASSES_PATH.'Filters.php');
		require_once(SGPB_MAILCHIMP_VERSION_DETECTION_PATH.'SgpbPopupVersionDetectionMailchimp.php');
	}

	public function actions()
	{
		SGPBMailchimpConfig::addDefine('SG_VERSION_POPUP_MAILCHIMP', '4.4');
		$this->actions = new Actions();
		$this->filters = new Filters();
		new SgpbPopupVersionDetectionMailchimp();
	}

	private function registerHooks()
	{
		register_activation_hook(SGPB_MAILCHIMP_FILE_NAME, array($this, 'activate'));
		register_deactivation_hook(SGPB_MAILCHIMP_FILE_NAME, array($this, 'deactivate'));
	}

	public function activate()
	{
		if (!defined('SG_POPUP_EXTENSION_PATH')) {
			$message = __('To enable Popup Builder Ad Block extension you need to activate Popup Builder plugin', SGPB_AD_BLOCK_TEXT_DOMAIN).'.';
			echo $message;
			wp_die();
		}
		require_once(SG_POPUP_EXTENSION_PATH.'SgpbPopupExtensionRegister.php');
		$pluginName = SGPB_MAILCHIMP_FILE_NAME;
		$classPath = SGPB_MAILCHIMP_DYNAMIC_CLASS_PATH.SGPB_MAILCHIMP_EXTENSION_FILE_NAME;
		$className = SGPB_MAILCHIMP_EXTENSION_CLASS_NAME;

		$options = array(
			'licence' => array(
				'key' => SGPB_MAILCHIMP_KEY,
				'storeURL' => SGPB_MAILCHIMP_STORE_URL,
				'file' => SGPB_MAILCHIMP_FILE_NAME,
				'itemId' => SGPB_MAILCHIMP_ITEM_ID,
				'itemName' => __('Popup Builder Mailchimp', SG_POPUP_TEXT_DOMAIN),
				'autor' => SGPB_MAILCHIMP_AUTHOR,
				'boxLabel' => __('Popup Builder Mailchimp License', SG_POPUP_TEXT_DOMAIN)
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
		$pluginName = SGPB_MAILCHIMP_FILE_NAME;
		// remove AWeber extension from registered extensions
		SgpbPopupExtensionRegister::remove($pluginName);

		return true;
	}
}

MailchimpMain::getInstance();
