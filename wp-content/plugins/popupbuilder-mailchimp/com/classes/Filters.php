<?php
namespace sgpbm;
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

	private function init()
	{
		// popup builder pages
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11, 1);
		}
		$popup = $this->getPopup();
		// edit page
		if ((isset($_GET['sgpb_type']) && $_GET['sgpb_type'] == SGPB_POPUP_TYPE_MAILCHIMP) || (is_object($popup) && $popup->getType() == SGPB_POPUP_TYPE_MAILCHIMP)) {
			add_action('sgpbPopupDefaultOptions', array($this, 'defaultOptions'), 11, 1);
		}
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_MAILCHIMP] = SGPB_MAILCHIMP_AVALIABLE_VERSION;

		return $popupType;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_MAILCHIMP] = SGPB_MAILCHIMP_CLASSES_PATH;

		return $typePaths;
	}

	public function defaultOptions($options)
	{
		$listId = '';
		$status = MailchimpApi::isConnected();

		if ($status) {
			$sgpbMailchimp = MailchimpApi::getInstance();
			$idTitle = $sgpbMailchimp->getListsIdAndTitle();
			if (!empty($idTitle)) {
				$keys = array_keys($idTitle);
				$listId = $keys[0];
			}
		}

		$options[] = array('name' => 'sgpb-mailchimp-lists', 'type' => 'text', 'defaultValue' => $listId);
		$options[] = array('name' => 'sgpb-enable-double-optin', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'sgpb-show-required-fields', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-enable-asterisk-label', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'sgpb-enable-asterisk-title', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'sgpb-mailchimp-form-align', 'type' => 'text', 'defaultValue' => 'center');
		$options[] = array('name' => 'sgpb-mailchimp-label-alignment', 'type' => 'text', 'defaultValue' => 'left');
		$options[] = array('name' => 'sgpb-mailchimp-input-width', 'type' => 'text', 'defaultValue' => '400px');
		$options[] = array('name' => 'sgpb-mailchimp-input-height', 'type' => 'text', 'defaultValue' => '40px');
		$options[] = array('name' => 'sgpb-mailchimp-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'sgpb-mailchimp-border-width', 'type' => 'text', 'defaultValue' => '1px');
		$options[] = array('name' => 'sgpb-mailchimp-asterisk-label', 'type' => 'text', 'defaultValue' => __('indicates required', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-mailchimp-required-message', 'type' => 'text', 'defaultValue' => __('This field is required', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-mailchimp-email-message', 'type' => 'text', 'defaultValue' => __('Please enter valid email', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-mailchimp-email-label', 'type' => 'text', 'defaultValue' => __('Email Address', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-mailchimp-error-message', 'type' => 'text', 'defaultValue' => __('Too many subscribe attempts for this email address', SG_POPUP_TEXT_DOMAIN).'.');
		$options[] = array('name' => 'sgpb-mailchimp-submit-width', 'type' => 'text', 'defaultValue' => '150px');
		$options[] = array('name' => 'sgpb-mailchimp-submit-height', 'type' => 'text', 'defaultValue' => '40px');
		$options[] = array('name' => 'sgpb-mailchimp-submit-title', 'type' => 'text', 'defaultValue' => __('Subscribe', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-mailchimp-submit-border-width', 'type' => 'text', 'defaultValue' => '1px');
		$options[] = array('name' => 'sgpb-mailchimp-submit-border-color', 'type' => 'text', 'defaultValue' => '#2873eb');
		$options[] = array('name' => 'sgpb-mailchimp-submit-background-color', 'type' => 'text', 'defaultValue' => '#2873eb');
		$options[] = array('name' => 'sgpb-mailchimp-submit-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$options[] = array('name' => 'sgpb-mailchimp-submit-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'sgpb-mailchimp-success-behavior', 'type' => 'text', 'defaultValue' => 'showMessage');
		$options[] = array('name' => 'sgpb-mailchimp-success-message', 'type' => 'text', 'defaultValue' => __('You have successfully subscribed to our mail list', SG_POPUP_TEXT_DOMAIN).'.');
		$options[] = array('name' => 'sgpb-mailchimp-success-redirect-new-tab', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-mailchimp-close-popup-already-subscribed', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-mailchimp-show-form-to-top', 'type' => 'checkbox', 'defaultValue' => '');

		return $options;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_MAILCHIMP] = __('Mailchimp', SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}
}
