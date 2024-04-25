<?php
namespace sgpblogin;
use sgpb\SGPopup;

class Filters
{
	private $popup = array();

	public function setPopup($popup)
	{
		$this->popup = $popup;
	}

	public function getPopup()
	{
		return $this->popup;
	}

	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		// by default, it's called inside after register popup builder post type but here we need it to call to get current popup type
		if (class_exists('\SgpbPopupConfig')) {
			\SgpbPopupConfig::popupTypesInit();
		}
		if (isset($_GET['post']) && class_exists('sgpb\SGPopup')) {
			$popup =  @SGPopup::find($_GET['post']);
			$this->setPopup($popup);
		}

		$this->init();
	}

	public function init()
	{
		$popup = $this->getPopup();

		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11);
		}
		if ((isset($_GET['sgpb_type']) && $_GET['sgpb_type'] == SGPB_POPUP_TYPE_LOGIN) || (is_object($popup) && $popup->getType() == SGPB_POPUP_TYPE_LOGIN)) {
			add_action('sgpbPopupDefaultOptions', array($this, 'defaultOptions'), 11);
		}

		add_action('sgPopupConditionsParams', array($this, 'conditionColumns'), 11);
	}

	public function conditionColumns($columns)
	{
		if (!empty($columns['Groups']['groups_user_role'])) {
			unset($columns['Groups']['groups_user_role']);
		}

		return $columns;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_LOGIN] = SGPB_LOGIN_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_LOGIN] = __('Log in', SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_LOGIN] = SGPB_LOGIN_AVALIABLE_VERSION;


		return $popupType;
	}

	public function defaultOptions($options)
	{
		$options[] = array('name' => 'sgpb-login-form-bg-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');
		$options[] = array('name' => 'sgpb-login-form-bg-opacity', 'type' => 'text', 'defaultValue' => 0.8);
		$options[] = array('name' => 'sgpb-login-form-padding', 'type' => 'number', 'defaultValue' => 2);
		$options[] = array('name' => 'sgpb-username-label', 'type' => 'text', 'defaultValue' => __('Username or Email Address'));
		$options[] = array('name' => 'sgpb-password-label', 'type' => 'text', 'defaultValue' => __('Password'));
		$options[] = array('name' => 'sgpb-remember-me-label', 'type' => 'text', 'defaultValue' => __('Remember Me'));
		$options[] = array('name' => 'sgpb-login-button-label', 'type' => 'text', 'defaultValue' => __('Log In'));
		$options[] = array('name' => 'sgpb-login-required-error', 'type' => 'text', 'defaultValue' => __('This field is required.', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-login-text-width', 'type' => 'text', 'defaultValue' => '300px');
		$options[] = array('name' => 'sgpb-login-text-height', 'type' => 'text', 'defaultValue' => '40px');
		$options[] = array('name' => 'sgpb-login-text-border-width', 'type' => 'text', 'defaultValue' => '2px');
		$options[] = array('name' => 'sgpb-login-text-border-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$options[] = array('name' => 'sgpb-login-text-bg-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');
		$options[] = array('name' => 'sgpb-login-text-color', 'type' => 'text', 'defaultValue' => '#000000');
		$options[] = array('name' => 'sgpb-login-text-placeholder-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$options[] = array('name' => 'sgpb-login-btn-width', 'type' => 'text', 'defaultValue' => '300px');
		$options[] = array('name' => 'sgpb-login-btn-height', 'type' => 'text', 'defaultValue' => '40px');
		$options[] = array('name' => 'sgpb-login-btn-title', 'type' => 'text', 'defaultValue' => __('Log In'));
		$options[] = array('name' => 'sgpb-login-btn-progress-title', 'type' => 'text', 'defaultValue' => __('Please wait...', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-login-btn-bg-color', 'type' => 'text', 'defaultValue' => '#007fe1');
		$options[] = array('name' => 'sgpb-login-btn-text-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');
		$options[] = array('name' => 'sgpb-remember-me-status', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'sgpb-login-success-behavior', 'type' => 'text', 'defaultValue' => 'refresh');
		$options[] = array('name' => 'sgpb-login-success-message', 'type' => 'text', 'defaultValue' =>  __('You have successfully logged in', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-login-success-redirect-URL', 'type' => 'text', 'defaultValue' =>  '');
		$options[] = array('name' => 'sgpb-login-success-redirect-new-tab', 'type' => 'checkbox', 'defaultValue' =>  '');
		$options[] = array('name' => 'sgpb-custom-login-error-message', 'type' => 'text', 'defaultValue' => __('Incorrect username or password.', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-already-login-message', 'type' => 'text', 'defaultValue' => __('You are already logged In. Processing after login actions ...please wait', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-custom-error-message', 'type' => 'checkbox', 'defaultValue' => '');

		return $options;
	}
}
