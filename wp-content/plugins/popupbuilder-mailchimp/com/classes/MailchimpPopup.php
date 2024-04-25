<?php
namespace sgpb;
use sgpbm\MailchimpApi;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class MailchimpPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'));
		add_filter('sgpbHasSubPopups', array($this, 'subPopups'), 10, 1);
		add_filter('sgpbPopupRenderOptions', array($this, 'renderOptions'), 10, 1);
	}

	private function frontendFilters()
	{
		add_filter('sgpbMailchimpJsFilter', array($this, 'jsFilter'));
	}

	public function renderOptions($options)
	{
		// for old popups
		if (isset($options['sgpb-mailchimp-success-popup']) && function_exists('sgpb\sgpGetCorrectPopupId')) {
			$options['sgpb-mailchimp-success-popup'] = sgpGetCorrectPopupId($options['sgpb-mailchimp-success-popup']);
		}

		return $options;
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$defaultOptions[] = array('name' => 'sgpb-mailchimp-gdpr-status', 'type' => 'checkbox', 'defaultValue' =>  '');
		$defaultOptions[] = array('name' => 'sgpb-mailchimp-gdpr-label', 'type' => 'text', 'defaultValue' =>  __('Accept Terms', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-mailchimp-gdpr-text', 'type' => 'text', 'defaultValue' =>  __(get_bloginfo().' will use the information you provide on this form to be in touch with you and to provide updates and marketing.', SG_POPUP_TEXT_DOMAIN));

		return $defaultOptions;
	}

	public function subPopups($subPopups)
    {
        $subPopups[] = 'mailchimp';

        return $subPopups;
    }

	public function jsFilter($jsFiles)
	{
		$popupId = $this->getId();

		$jsFiles['localizeData'][] = array(
			'handle' => 'Validate.js',
			'name' => 'SGPB_MAILCHIMP_PARAMS_'.$popupId,
			'data' => array(
				'nonce' => wp_create_nonce(SG_AJAX_NONCE),
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'validateScript' => $this->getValidateScript(),
				'pleaseWait' => __('Please wait', SG_POPUP_TEXT_DOMAIN)
			)
		);

		return $jsFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeOptionsView()
	{
		return array();
	}

	public function getPopupTypeMainView()
	{
		return array(
			'filePath' => SGPB_MAILCHIMP_VIEWS_PATH.'mainView.php',
			'metaboxTitle' => __('Mailchimp Settings', SG_POPUP_TEXT_DOMAIN),
			'short_description' => 'Connect your Mailchimp account, subscribers will be automatically added to your account'
		);
	}

	private function getValidateScript()
	{
		$sgpbMailchimp = MailchimpApi::getInstance();
		$currentPopupId = $this->getId();

		$params = array(
			'listId' => $this->getOptionValue('sgpb-mailchimp-lists'),
			'requiredMessage' => $this->getOptionValue('sgpb-mailchimp-required-message'),
			'emailMessage' => $this->getOptionValue('sgpb-mailchimp-email-message'),
		);
		$validateScript = $sgpbMailchimp->getValidateScript($params, $currentPopupId);

		return $validateScript;
	}

	public function getPopupTypeContent()
	{
		if (!MailchimpApi::isConnected()) {
			$content = '<b>'.__('Popup Builder Mailchimp extension requires authentication.', SG_POPUP_TEXT_DOMAIN).'</b>';
			return $formHtml = $content;
		}
		$this->frontendFilters();

		$showBeforeContent = $this->getOptionValue('sgpb-mailchimp-show-form-to-top');
		$content = $this->getContent();
		$form = $this->renderForm();
		$messages = $this->getFormMessages();
		$form = $messages.$form;
		$popupContent = $content.$form;

		if ($showBeforeContent) {
			$popupContent = $form.$content;
		}

		return $popupContent;
	}

	public function renderCss()
	{
		$content = '';
		$borderWidth = $this->getOptionValue('sgpb-mailchimp-submit-border-width');
		$borderWidth = AdminHelper::getCSSSafeSize($borderWidth);
		$borderRadius = $this->getOptionValue('sgpb-mailchimp-submit-border-radius');
		$borderRadius = AdminHelper::getCSSSafeSize($borderRadius);
		$submitWidth = $this->getOptionValue('sgpb-mailchimp-submit-width');
		$submitWidth = AdminHelper::getCSSSafeSize($submitWidth);
		$submitHeight = $this->getOptionValue('sgpb-mailchimp-submit-height');
		$submitHeight = AdminHelper::getCSSSafeSize($submitHeight);
		$inputBorderWidth = $this->getOptionValue('sgpb-mailchimp-border-width');
		$inputBorderWidth = AdminHelper::getCSSSafeSize($inputBorderWidth);
		$inputBorderRadius = $this->getOptionValue('sgpb-mailchimp-border-radius');
		$inputBorderRadius = AdminHelper::getCSSSafeSize($inputBorderRadius);
		$inputWidth = $this->getOptionValue('sgpb-mailchimp-input-width');
		$inputWidth = AdminHelper::getCSSSafeSize($inputWidth);
		$inputHeight = $this->getOptionValue('sgpb-mailchimp-input-height');
		$inputHeight = AdminHelper::getCSSSafeSize($inputHeight);
		$formAlignment = $this->getOptionValue('sgpb-mailchimp-form-align');
		$asteriskLabelStatus = $this->getOptionValue('sgpb-enable-asterisk-label');
		$asteriskTitleStatus = $this->getOptionValue('sgpb-enable-asterisk-title');
		$submitBorderColor = $this->getOptionValue('sgpb-mailchimp-submit-border-color');
		$submitBackgroundColor = $this->getOptionValue('sgpb-mailchimp-submit-background-color');
		$submitTextColor = $this->getOptionValue('sgpb-mailchimp-submit-color');
		$mailchimpBorderColor = $this->getOptionValue('sgpb-mailchimp-border-color');
		$mailchimpInputColor = $this->getOptionValue('sgpb-mailchimp-input-color');
		$mailchimpBackgroundColor = $this->getOptionValue('sgpb-mailchimp-background-color');
		$mailchimpLabelColor = $this->getOptionValue('sgpb-mailchimp-label-color');
		$mailchimpLabelAlignment = $this->getOptionValue('sgpb-mailchimp-label-alignment');
		$important = '!important';

		// for admin live preview
		if (is_admin()) {
			$important = '';
		}
		$id = $this->getId();
		$content .= '<style type="text/css">';
		$content .= '.sgpb-mailchimp-'.$id.' {';
		$content .= 'overflow: hidden;';
		$content .= '}';

		$content .= '.sgpb-mailchimp-'.$id.' input[type="submit"].sgpb-embedded-subscribe {';
		$content .= 'width: '.$submitWidth.' '.$important.';';
		$content .= 'height: '.$submitHeight.' '.$important.';';
		$content .= 'border-radius: '.$borderRadius.' '.$important.';';
		$content .= 'border: '.$borderWidth.' '.$important.';';
		$content .= 'border: '.$borderWidth.' solid '.$submitBorderColor.' '.$important.';';
		$content .= 'background-color: '.$submitBackgroundColor.' '.$important.';';
		$content .= 'color: '.$submitTextColor.' '.$important.';';
		$content .= '}';

		$content .= '.sgpb-mailchimp-'.$id.'.sgpb-mailchimp-'.$id.' .sgpb-mailchimp-input {';
		$content .= 'width: '.$inputWidth.' '.$important.';';
		$content .= 'height: '.$inputHeight.' '.$important.';';
		$content .= 'border-radius: '.$inputBorderRadius.' '.$important.';';
		$content .= 'border: '.$inputBorderWidth.' solid '.$mailchimpBorderColor.' '.$important.';';
		$content .= 'color: '.$mailchimpInputColor.' '.$important.';';
		$content .= 'background-color: '.$mailchimpBackgroundColor.' '.$important.';';
		$content .= 'margin: 0;';
		$content .= '}';
		$content .= '.sgpb-mailchimp-'.$id.' .sgpb-label {';
		$content .= 'color: '.$mailchimpLabelColor.' '.$important.';';
		$content .= 'text-align: '.$mailchimpLabelAlignment.' '.$important.';';
		$content .= '}';
		$content .= '.sgpb-mailchimp-'.$id.',';
		$content .= '.sgpb-mailchimp-'.$id.' .sg-submit-wrapper {';
		$content .= 'text-align: '.$formAlignment.';';
		$content .= '}';
		$content .= '.sgpb-live-preview-wrapper .mc-field-group.sgpb-field-group {';
		$content .= 'text-align: '.$formAlignment.';';
		$content .= '}';
		$content .= '.sgpb-mailchimp-'.$id.'.sgpb-mailchimp-'.$id.' .sgpb-validate-message {';
		$content .= 'width: '.$inputWidth.' '.$important.';';
		$content .= 'margin: 0 auto;';
		$content .= '}';
		$content .= '.sgpb-mailchimp-'.$id.' .sgpbm-subfield .sgpbm-datepart,.sgpb-mailchimp-'.$id.' .phonefield.phonefield-us .phonePart {width: auto !important;min-width: 40px; }';
		$content .= '.sgpb-mailchimp-'.$id.' .phonefield-us .phonearea input,.sgpbmMailchimpForm .phonefield-us .phonedetail1 input{width:40px; !important;}';
		$content .= '.sgpb-mailchimp-'.$id.' .phonefield-us .phonePart input {width:50px; !important;}';
		// gdpr
		$content .= '.sgpb-mailchimp-'.$id.' .sgpb-gdpr-wrapper {max-width:'.$inputWidth.'; !important;}';
		$content .= '.sgpb-mailchimp-admin-form-wrapper {max-width:'.$inputWidth.'; !important;margin: 0 auto;}';

		if (!$asteriskLabelStatus) {
			$content .= '.sgpb-mailchimp-'.$id.' .sgpb-asterisk,';
			$content .= '.sgpb-mailchimp-'.$id.' .sgpb-indicates-required {';
			$content .= 'display: none;';
			$content .= '}';
		}
		if (!$asteriskTitleStatus) {
			$content .= '.sgpb-mailchimp-'.$id.' .sgpb-indicates-required {';
			$content .= 'display: none;';
			$content .= '}';
		}
		$content .= '</style>';

		return $content;
	}

	public function getSubPopupObj()
	{
		$options = $this->getOptions();
		$subPopups = parent::getSubPopupObj();

		if ($options['sgpb-mailchimp-success-behavior'] == 'openPopup') {
			$subPopupId = (!empty($options['sgpb-mailchimp-success-popup'])) ? (int)$options['sgpb-mailchimp-success-popup']: null;
			if (empty($subPopupId)) {
				return $subPopups;
			}

			// for old popups
			if (function_exists('sgpb\sgpGetCorrectPopupId')) {
				$subPopupId = sgpGetCorrectPopupId($subPopupId);
			}

			$subPopupObj = SGPopup::find($subPopupId);
			if (!empty($subPopupObj) && ($subPopupObj instanceof SGPopup)) {
				// We remove all events because this popup will be open after successful contacted
				$subPopupObj->setEvents(array('param' => 'click', 'value' => ''));
				$subPopups[] = $subPopupObj;
			}
		}

		return $subPopups;
	}

	private function getFormMessages()
	{
		$successMessage = $this->getOptionValue('sgpb-mailchimp-success-message');
		$errorMessage = $this->getOptionValue('sgpb-mailchimp-error-message');

		$messages = '<div class="mailchimp-form-messages sgpb-alert sgpb-alert-success sg-hide-element">';
		$messages .= '<p>'.$successMessage.'</p>';
		$messages .= '</div>';
		$messages .= '<div class="mailchimp-form-messages sgpb-alert sgpb-alert-danger sgpb-alert-error sg-hide-element">';
		$messages .= '<p>'.$errorMessage.'</p>';
		$messages .= '</div>';

		return $messages;
	}

	public function renderForm()
	{
		$css = $this->renderCss();
		$id = $this->getId();
		$selectedFormId = $this->getOptionValue('sgpb-mailchimp-lists');
		$sgpbMailchimp = MailchimpApi::getInstance();
		$params = array(
			'popupId' => $id,
			'listId' => $selectedFormId,
			'emailLabel' => $this->getOptionValue('sgpb-mailchimp-email-label'),
			'asteriskLabel' => $this->getOptionValue('sgpb-mailchimp-asterisk-label'),
			'showRequiredFields' => $this->getOptionValue('sgpb-show-required-fields') ? true : false,
			'submitTitle' => $this->getOptionValue('sgpb-mailchimp-submit-title'),
			'gdprStatus' => $this->getOptionValue('sgpb-mailchimp-gdpr-status'),
			'gdprLabel' => $this->getOptionValue('sgpb-mailchimp-gdpr-label'),
			'gdprConfirmationText' => $this->getOptionValue('sgpb-mailchimp-gdpr-text')
		);
		$form = $sgpbMailchimp->getListFormHtml($params);
		$form .= $css;

		return $form;
	}

	public function getExtraRenderOptions()
	{
		return array();
	}
}

