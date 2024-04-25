<?php
namespace sgpbcontactform;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11, 1);
		}

		$this->init();
	}

	public function init()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'contactFormDefaultOptions'), 10, 1);
	}

	public function contactFormDefaultOptions($options)
	{
		$options[] = array('name' => 'sgpb-contact-fields-json', 'type' => 'sgpb', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-contact-fields-design-json', 'type' => 'sgpb', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-contact-to-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$options[] = array('name' => 'sgpb-contact-register-user', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-contact-show-form-to-top', 'type' => 'checkbox', 'defaultValue' => '');

		$options[] = array('name' => 'sgpb-contact-input-margin-top', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-input-margin-right', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-input-margin-bottom', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-input-margin-left', 'type' => 'text', 'defaultValue' => '2');

		$options[] = array('name' => 'sgpb-contact-button-margin-top', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-button-margin-right', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-button-margin-bottom', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-button-margin-left', 'type' => 'text', 'defaultValue' => '2');

		$options[] = array('name' => 'sgpb-contact-message-margin-top', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-message-margin-right', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-message-margin-bottom', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-contact-message-margin-left', 'type' => 'text', 'defaultValue' => '2');

		$options[] = array('name' => 'sgpb-contact-field-horizontally', 'type' => 'checkbox', 'defaultData' => '');
		$options[] = array('name' => 'sgpb-contact-except-button', 'type' => 'checkbox', 'defaultData' => '');

		return $options;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_CONTACT_FORM] = SGPB_CONTACT_FORM_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_CONTACT_FORM] = __(SGPB_POPUP_TYPE_CONTACT_FORM_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_CONTACT_FORM] = SGPB_CONTACT_FORM_AVALIABLE_VERSION;


		return $popupType;
	}
}
