<?php
namespace sgpb;
use sgpbcontactform\ContactformForm;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class ContactformPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbContactFormAdminJsFiles', array($this, 'adminJsFilter'), 1, 1);
		add_filter('sgpbContactFormAdminCssFiles', array($this, 'adminCssFilter'), 1, 1);
		add_filter('sgpbPopupRenderOptions', array($this, 'renderOptions'), 12, 1);
		add_filter('sgpbContactForm', array($this, 'contactForm'), 1, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'), 1, 100);
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendJsFiles', array($this, 'frontJsFilter'), 1, 1);
		add_filter('sgpbFrontendCssFiles', array($this, 'frontCssFilter'), 1, 1);
	}

	public function renderOptions($options)
	{
		// for old popups
		if (isset($options['sgpb-contact-success-popup']) && function_exists('sgpb\sgpGetCorrectPopupId')) {
			$options['sgpb-contact-success-popup'] = sgpGetCorrectPopupId($options['sgpb-contact-success-popup']);
		}

		return $options;
	}

	public function adminCssFilter($cssFiles)
	{

		$cssFiles[] = array(
			'folderUrl' => SG_POPUP_CSS_URL,
			'filename' => 'ResetFormStyle.css'
		);
		$cssFiles[] = array(
			'folderUrl' => SGPB_CONTACT_FORM_CSS_URL,
			'filename' => 'ContactForm.css'
		);
		$cssFiles[] = array(
			'folderUrl' => SGPB_CF_FORM_BUILDER_URL.'css/',
			'filename' => 'formAdmin.css'
		);

		return $cssFiles;
	}

	public function frontCssFilter($cssFiles)
	{
		$cssFiles[] = array(
			'folderUrl' => SG_POPUP_CSS_URL,
			'filename' => 'ResetFormStyle.css'
		);
		$cssFiles[] = array(
			'folderUrl' => SGPB_CONTACT_FORM_CSS_URL,
			'filename' => 'ContactForm.css'
		);

		return $cssFiles;
	}

	public function adminJsFilter($jsFiles)
	{
		$jsFiles[] = array(
			'folderUrl' => SGPB_CONTACT_FORM_JS_URL,
			'filename' => 'ContactForm.js'
		);

		$jsFiles[] = array(
			'folderUrl' => SGPB_CF_FORM_BUILDER_URL.'js/',
			'filename' => 'formBuilder.js'
		);

		return $jsFiles;
	}

	public function frontJsFilter($jsFiles)
	{
		$jsFiles[] = array(
			'folderUrl' => SG_POPUP_JS_URL,
			'filename' => 'Validate.js'
		);
		$jsFiles[] = array(
			'folderUrl' => SGPB_CONTACT_FORM_JS_URL,
			'filename' => 'ContactForm.js'
		);

		return $jsFiles;
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$defaultOptions[] = array('name' => 'sgpb-contact-form-bg-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');
		$defaultOptions[] = array('name' => 'sgpb-contact-form-bg-opacity', 'type' => 'text', 'defaultValue' => 0.8);
		$defaultOptions[] = array('name' => 'sgpb-contact-form-padding', 'type' => 'number', 'defaultValue' => 2);
		$defaultOptions[] = array('name' => 'sgpb-contact-show-form-to-top', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-contact-field-name', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-contact-name-placeholder', 'type' => 'text', 'defaultValue' => __('Name *', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-name-required', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-contact-field-subject', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-contact-subject-placeholder', 'type' => 'text', 'defaultValue' => __('Subject *', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-subject-required', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-contact-email-placeholder', 'type' => 'text', 'defaultValue' => __('E-mail *', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-message-placeholder', 'type' => 'text', 'defaultValue' => __('Message *', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-receiver-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$defaultOptions[] = array('name' => 'sgpb-contact-error-message', 'type' => 'text', 'defaultValue' => __('Unable to send.', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-required-message', 'type' => 'text', 'defaultValue' => __('This field is required.', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-invalid-email-message', 'type' => 'text', 'defaultValue' => __('Please enter a valid email.', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-inputs-width', 'type' => 'text', 'defaultValue' => '300px');
		$defaultOptions[] = array('name' => 'sgpb-contact-inputs-height', 'type' => 'text', 'defaultValue' => '40px');
		$defaultOptions[] = array('name' => 'sgpb-contact-inputs-border-width', 'type' => 'text', 'defaultValue' => '2px');
		$defaultOptions[] = array('name' => 'sgpb-contact-inputs-bg-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$defaultOptions[] = array('name' => 'sgpb-contact-inputs-border-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$defaultOptions[] = array('name' => 'sgpb-contact-inputs-text-color', 'type' => 'text', 'defaultValue' => '#000000');
		$defaultOptions[] = array('name' => 'sgpb-contact-inputs-placeholder-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-width', 'type' => 'text', 'defaultValue' => '300px');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-height', 'type' => 'text', 'defaultValue' => '50px');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-resize', 'type' => 'text', 'defaultValue' => 'both');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-border-width', 'type' => 'text', 'defaultValue' => '2px');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-border-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-width', 'type' => 'text', 'defaultValue' => '300px');
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-height', 'type' => 'text', 'defaultValue' => '40px');
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-title', 'type' => 'text', 'defaultValue' => __('Submit', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-title-progress', 'type' => 'text', 'defaultValue' => __('Please wait...', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-bg-color', 'type' => 'text', 'defaultValue' => '#007fe1');
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-border-radius', 'type' => 'text', 'defaultValue' => '4px');
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-border-color', 'type' => 'text', 'defaultValue' => '#007fe1');
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-border-width', 'type' => 'text', 'defaultValue' => '0px');
		$defaultOptions[] = array('name' => 'sgpb-contact-submit-text-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-placeholder-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-text-color', 'type' => 'text', 'defaultValue' => '#000000');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-bg-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$defaultOptions[] = array('name' => 'sgpb-contact-success-behavior', 'type' => 'text', 'defaultValue' => 'showMessage');
		$defaultOptions[] = array('name' => 'sgpb-contact-hide-for-contacted-users', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-contact-success-message', 'type' => 'text', 'defaultValue' => __('Your message has been successfully sent', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-success-redirect-URL', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-contact-success-redirect-new-tab', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-contact-gdpr-status', 'type' => 'checkbox', 'defaultValue' =>  '');
		$defaultOptions[] = array('name' => 'sgpb-contact-gdpr-label', 'type' => 'text', 'defaultValue' =>  __('Accept Terms', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-gdpr-text', 'type' => 'text', 'defaultValue' =>  __(get_bloginfo().' will use the information you provide on this form to be in touch with you and to provide updates and marketing.', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-contact-fields', 'type' => 'sgpb', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-contact-label-color', 'type' => 'text', 'defaultValue' => '#000000');
		$defaultOptions[] = array('name' => 'sgpb-contact-text-active-border-color', 'type' => 'text', 'defaultValue' => '#cccccc');
		$defaultOptions[] = array('name' => 'sgpb-contact-text-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$defaultOptions[] = array('name' => 'sgpb-contact-btn-font-size', 'type' => 'text', 'defaultValue' => '18px');
		$defaultOptions[] = array('name' => 'sgpb-contact-form-padding-top', 'type' => 'text', 'defaultValue' => '2');
		$defaultOptions[] = array('name' => 'sgpb-contact-form-padding-right', 'type' => 'text', 'defaultValue' => '2');
		$defaultOptions[] = array('name' => 'sgpb-contact-form-padding-bottom', 'type' => 'text', 'defaultValue' => '2');
		$defaultOptions[] = array('name' => 'sgpb-contact-form-padding-left', 'type' => 'text', 'defaultValue' => '2');
		$defaultOptions[] = array('name' => 'sgpb-contact-btn-bg-hover-color', 'type' => 'text', 'defaultValue' => '#007fe1');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-active-border-color', 'type' => 'text', 'defaultValue' => '#cccccc');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$defaultOptions[] = array('name' => 'sgpb-contact-message-label-color', 'type' => 'text', 'defaultValue' => '#000000');

		return $defaultOptions;
	}

	public function addAdditionalSettings($postData = array(), $obj = null)
	{
		$this->setPostData($postData);
		$postData['sgpb-contact-fields'] = $obj->createFormFieldsData();

		return $postData;
	}

	public function getPopupTypeOptionsView()
	{
		$optionsView = array(
			'filePath' => SGPB_CONTACT_FORM_VIEWS_PATH.'contact.php',
			'metaboxTitle' => 'Contact Form Settings',
			'short_description' => 'Create contact form, customize the fields and styles'
		);

		return $optionsView;
	}

	public function getExtraRenderOptions()
	{
		return array();
	}

	/**
	 * Create form fields data
	 *
	 * @since 1.0.0
	 *
	 * @return array $formData
	 */
	public function createFormFieldsData()
	{
		$formData = array();
		$inputsWidth = $this->getOptionValueFromSavedData('sgpb-contact-inputs-width');
		$inputsWidth = AdminHelper::getCSSSafeSize($inputsWidth);
		$inputsHeight = $this->getOptionValueFromSavedData('sgpb-contact-inputs-height');
		$inputsHeight = AdminHelper::getCSSSafeSize($inputsHeight);
		$inputsBorderWidth = $this->getOptionValueFromSavedData('sgpb-contact-inputs-border-width');
		$inputsBorderWidth = AdminHelper::getCSSSafeSize($inputsBorderWidth);

		$inputStyles = array(
			'width' => $inputsWidth,
			'height' => $inputsHeight,
			'border-width' => $inputsBorderWidth,
			'background-color' => $this->getOptionValueFromSavedData('sgpb-contact-inputs-bg-color'),
			'border-color' => $this->getOptionValueFromSavedData('sgpb-contact-inputs-border-color'),
			'color' => $this->getOptionValueFromSavedData('sgpb-contact-inputs-text-color'),
			'placeholder-color' => $this->getOptionValueFromSavedData('sgpb-contact-inputs-placeholder-color')
		);

		$nameData = array(
			'isShow' => $this->getOptionValueFromSavedData('sgpb-contact-field-name'),
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'text',
				'data-required' => $this->getOptionValueFromSavedData('sgpb-contact-name-required'),
				'name' => 'sgpb-contact-name',
				'placeholder' => $this->getOptionValueFromSavedData('sgpb-contact-name-placeholder'),
				'class' => 'js-contact-field-input js-contact-color-input js-contact-name',
				'data-error-message-class' => 'sgpb-contact-name-error-message'
			),
			'errorMessageBoxStyles' => $inputsWidth
		);

		$subjectData = array(
			'isShow' => $this->getOptionValueFromSavedData('sgpb-contact-field-subject'),
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'text',
				'data-required' => $this->getOptionValueFromSavedData('sgpb-contact-subject-required'),
				'name' => 'sgpb-contact-subject',
				'placeholder' => $this->getOptionValueFromSavedData('sgpb-contact-subject-placeholder'),
				'class' => 'js-contact-field-input js-contact-color-input js-contact-subject',
				'data-error-message-class' => 'sgpb-contact-subject-error-message'
			),
			'errorMessageBoxStyles' => $inputsWidth
		);

		$emailData = array(
			'isShow' => true,
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'email',
				'data-required' => true,
				'name' => 'sgpb-contact-email',
				'placeholder' => $this->getOptionValueFromSavedData('sgpb-contact-email-placeholder'),
				'class' => 'js-contact-field-input js-contact-color-input js-contact-email',
				'data-error-message-class' => 'sgpb-contact-email-error-message'
			),
			'errorMessageBoxStyles' => $inputsWidth
		);

		$messageWidth = $this->getOptionValueFromSavedData('sgpb-contact-message-width');
		$messageWidth = AdminHelper::getCSSSafeSize($messageWidth);
		$messageHeight = $this->getOptionValueFromSavedData('sgpb-contact-message-height');
		$messageHeight = AdminHelper::getCSSSafeSize($messageHeight);
		$messageBorderWidth = $this->getOptionValueFromSavedData('sgpb-contact-message-border-width');
		$messageBorderWidth = AdminHelper::getCSSSafeSize($messageBorderWidth);

		$message = array(
			'isShow' => true,
			'style' => array(
				'width' => $messageWidth,
				'height' => $messageHeight,
				'border-width' => $messageBorderWidth,
				'background-color' => $this->getOptionValueFromSavedData('sgpb-contact-message-bg-color'),
				'border-color' => $this->getOptionValueFromSavedData('sgpb-contact-message-border-color'),
				'color' => $this->getOptionValueFromSavedData('sgpb-contact-message-text-color'),
				'placeholder-color' => $this->getOptionValueFromSavedData('sgpb-contact-message-placeholder-color'),
				'resize' => $this->getOptionValueFromSavedData('sgpb-contact-message-resize')
			),
			'attrs' => array(
				'type' => 'textarea',
				'data-required' => true,
				'name' => 'sgpb-contact-message',
				'placeholder' => $this->getOptionValueFromSavedData('sgpb-contact-message-placeholder'),
				'class' => 'js-contact-field-message js-contact-color-input js-contact-message',
				'data-error-message-class' => 'sgpb-contact-message-error-message'
			)
		);

		/* GDPR checkbox */
		$gdprLabel = $this->getOptionValueFromSavedData('sgpb-contact-gdpr-label');
		$gdprRequired = ($this->getOptionValueFromSavedData('sgpb-contact-gdpr-status')) ? true : false;
		$isShow = ($this->getOptionValueFromSavedData('sgpb-contact-gdpr-status')) ? true : false;

		$gdprCheckbox = array(
			'isShow' => $isShow,
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'customCheckbox',
				'data-required' => $gdprRequired,
				'name' => 'sgpb-contact-gdpr',
				'class' => 'js-contact-gdpr-input js-contact-gdpr-input js-contact-gdpr',
				'id' => 'sgpb-gdpr-field-label',
				'data-error-message-class' => 'sgpb-gdpr-error-message'
			),
			'label' => $gdprLabel,
			'text' => $this->getOptionValueFromSavedData('sgpb-contact-gdpr-text')
		);
		/* GDPR checkbox */

		$submitWidth = $this->getOptionValueFromSavedData('sgpb-contact-submit-width');
		$submitWidth = AdminHelper::getCSSSafeSize($submitWidth);
		$submitHeight = $this->getOptionValueFromSavedData('sgpb-contact-submit-height');
		$submitHeight = AdminHelper::getCSSSafeSize($submitHeight);
		$submitTitle = $this->getOptionValueFromSavedData('sgpb-contact-submit-title');
		$submitBorderRadius = $this->getOptionValueFromSavedData('sgpb-contact-submit-border-radius');
		$submitBorderRadius = AdminHelper::getCSSSafeSize($submitBorderRadius);
		$submitBorderWidth = $this->getOptionValueFromSavedData('sgpb-contact-submit-border-width');
		$submitBorderWidth = AdminHelper::getCSSSafeSize($submitBorderWidth);
		$submitBorderColor = $this->getOptionValueFromSavedData('sgpb-contact-submit-border-color');
		$submitBorderColor = AdminHelper::getCSSSafeSize($submitBorderColor);

		$submit = array(
			'isShow' => true,
			'style' => array(
				'width' => $submitWidth,
				'height' => $submitHeight,
				'background-color' => $this->getOptionValueFromSavedData('sgpb-contact-submit-bg-color'),
				'border-radius' => $submitBorderRadius,
				'border-width' => $submitBorderWidth,
				'border-color' => $submitBorderColor,
				'color' => $this->getOptionValueFromSavedData('sgpb-contact-submit-text-color'),
				'text-transform' => 'none !important'
			),
			'attrs' => array(
				'value' => $submitTitle,
				'type' => 'submit',
				'data-title' => $submitTitle,
				'data-progress-title' => $this->getOptionValueFromSavedData('sgpb-contact-submit-title-progress'),
				'class' => 'js-contact-field-submit js-contact-color-submit js-contact-submit'
			)
		);

		$formData['name'] = $nameData;
		$formData['subject'] = $subjectData;
		$formData['email'] = $emailData;
		$formData['message'] = $message;
		$formData['gdpr'] = $gdprCheckbox;
		$formData['submit'] = $submit;

		return $formData;
	}

	public function getFormCustomStyles($styleData)
	{
		$fieldsPlaceholderColor = $styleData['fieldsPlaceholderColor'];
		$messagePlaceholderColor = $styleData['messageFieldPlaceholderColor'];
		$formBackgroundColor = $this->getOptionValue('sgpb-contact-form-bg-color');
		$formPadding = $this->getOptionValue('sgpb-contact-form-padding');
		$formBackgroundOpacity = $this->getOptionValue('sgpb-contact-form-bg-opacity');
		$popupId = $this->getId();
		if (isset($styleData['formBackgroundOpacity'])) {
			$formBackgroundOpacity = $styleData['formBackgroundOpacity'];
		}
		if (isset($styleData['formColor'])) {
			$formBackgroundColor = $styleData['formColor'];
		}
		if (isset($styleData['formPadding'])) {
			$formPadding = $styleData['formPadding'];
		}
		$formBackgroundColor = AdminHelper::hexToRgba($formBackgroundColor, $formBackgroundOpacity);

		ob_start();
		?>
		<style type="text/css">
			.sgpb-contact-form-<?php echo $popupId; ?> {background-color: <?php echo $formBackgroundColor; ?>;padding: <?php echo $formPadding.'px'; ?>}
			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-text-inputs::-webkit-input-placeholder {color: <?php echo $fieldsPlaceholderColor; ?>;font-weight: lighter;}
			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-text-inputs::-moz-placeholder {color:<?php echo $fieldsPlaceholderColor; ?>;font-weight: lighter;}
			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-text-inputs:-ms-input-placeholder {color:<?php echo $fieldsPlaceholderColor; ?>;font-weight: lighter;} /* ie */
			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-text-inputs:-moz-placeholder {color:<?php echo $fieldsPlaceholderColor; ?>;font-weight: lighter;}

			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-field-textarea::-webkit-input-placeholder {color: <?php echo $messagePlaceholderColor; ?>;font-weight: lighter;}
			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-field-textarea::-moz-placeholder {color:<?php echo $messagePlaceholderColor; ?>;font-weight: lighter;}
			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-field-textarea:-ms-input-placeholder {color:<?php echo $messagePlaceholderColor; ?>;font-weight: lighter;} /* ie */
			.sgpb-contact-form-<?php echo $popupId; ?> .js-contact-field-textarea:-moz-placeholder {color:<?php echo $messagePlaceholderColor; ?>;font-weight: lighter;}
		</style>
		<?php
		$styles = ob_get_contents();
		ob_get_clean();

		return $styles;
	}

	private function getValidationMessages()
	{
		$requiredMessage = $this->getOptionValue('sgpb-contact-required-message');
		$emailMessage = $this->getOptionValue('sgpb-contact-invalid-email-message');

		if (empty($requiredMessage)) {
			$requiredMessage = SGPB_CONTACT_FORM_REQUIRED_MESSAGE;
		}

		if (empty($emailMessage)) {
			$emailMessage = SGPB_CONTACT_FORM_EMAIL_MESSAGE;
		}

		$messageScript = '<script type="text/javascript">';
		$messageScript .= 'jQuery.extend(jQuery.validator.messages, { ';
		$messageScript .= 'required: "'.esc_attr($requiredMessage).'",';
		$messageScript .= 'email: "'.esc_attr($emailMessage).'"';
		$messageScript .= '});';
		$messageScript .= '</script>';

		return $messageScript;
	}

	private function getContactValidationScripts($validateObj)
	{
		$script = '<script type="text/javascript">';
		$script .= $validateObj;
		$script .= '</script>';

		return $script;
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$popupContent = $this->getContent();

		$subscriptionPlusFormObj = new ContactformForm();
		$subscriptionPlusFormObj->setPopupObj($this);
		$formContent = $subscriptionPlusFormObj->render();
		$content = $popupContent.$formContent;

		if ($this->getOptionValue('sgpb-contact-show-form-to-top')) {
			$content = $formContent.$popupContent;
		}

		return $content;
	}

	public function getSubPopupObj()
	{
		$options = $this->getOptions();
		$subPopups = parent::getSubPopupObj();

		if ($options['sgpb-contact-success-behavior'] == 'openPopup') {

			$subPopupId = (!empty($options['sgpb-contact-success-popup'])) ? (int)$options['sgpb-contact-success-popup']: null;
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

	public function contactForm($popupObj)
	{
		if (!is_object($popupObj)) {
			return $popupObj;
		}
		$subscriptionPlusFormObj = new ContactformForm();
		$subscriptionPlusFormObj->setPopupObj($popupObj);
		$formContent = $subscriptionPlusFormObj->render();

		$popupObj->setFormContent($formContent);

		return $popupObj;
	}

	/*
	 * for the old silver/gold/platinum vesion compatibility,
	 * we need copy the function and use with a little changes
	 */
	public function createFormFieldsDataOld()
	{
		$formData = array();
		$inputsWidth = $this->getOptionValue('sgpb-contact-inputs-width');
		$inputsWidth = AdminHelper::getCSSSafeSize($inputsWidth);
		$inputsHeight = $this->getOptionValue('sgpb-contact-inputs-height');
		$inputsHeight = AdminHelper::getCSSSafeSize($inputsHeight);
		$inputsBorderWidth = $this->getOptionValue('sgpb-contact-inputs-border-width');
		$inputsBorderWidth = AdminHelper::getCSSSafeSize($inputsBorderWidth);

		$inputStyles = array(
			'width' => $inputsWidth,
			'height' => $inputsHeight,
			'border-width' => $inputsBorderWidth,
			'background-color' => $this->getOptionValue('sgpb-contact-inputs-bg-color'),
			'border-color' => $this->getOptionValue('sgpb-contact-inputs-border-color'),
			'color' => $this->getOptionValue('sgpb-contact-inputs-text-color'),
			'placeholder-color' => $this->getOptionValue('sgpb-contact-inputs-placeholder-color')
		);

		$nameData = array(
			'isShow' => $this->getOptionValue('sgpb-contact-field-name'),
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'text',
				'data-required' => $this->getOptionValue('sgpb-contact-name-required'),
				'name' => 'sgpb-contact-name',
				'placeholder' => $this->getOptionValue('sgpb-contact-name-placeholder'),
				'class' => 'js-contact-field-input js-contact-color-input js-contact-name',
				'data-error-message-class' => 'sgpb-contact-name-error-message'
			),
			'errorMessageBoxStyles' => $inputsWidth
		);

		$subjectData = array(
			'isShow' => $this->getOptionValue('sgpb-contact-field-subject'),
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'text',
				'data-required' => $this->getOptionValue('sgpb-contact-subject-required'),
				'name' => 'sgpb-contact-subject',
				'placeholder' => $this->getOptionValue('sgpb-contact-subject-placeholder'),
				'class' => 'js-contact-field-input js-contact-color-input js-contact-subject',
				'data-error-message-class' => 'sgpb-contact-subject-error-message'
			),
			'errorMessageBoxStyles' => $inputsWidth
		);

		$emailData = array(
			'isShow' => true,
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'email',
				'data-required' => true,
				'name' => 'sgpb-contact-email',
				'placeholder' => $this->getOptionValue('sgpb-contact-email-placeholder'),
				'class' => 'js-contact-field-input js-contact-color-input js-contact-email',
				'data-error-message-class' => 'sgpb-contact-email-error-message'
			),
			'errorMessageBoxStyles' => $inputsWidth
		);

		$messageWidth = $this->getOptionValue('sgpb-contact-message-width');
		$messageWidth = AdminHelper::getCSSSafeSize($messageWidth);
		$messageHeight = $this->getOptionValue('sgpb-contact-message-height');
		$messageHeight = AdminHelper::getCSSSafeSize($messageHeight);
		$messageBorderWidth = $this->getOptionValue('sgpb-contact-message-border-width');
		$messageBorderWidth = AdminHelper::getCSSSafeSize($messageBorderWidth);

		$message = array(
			'isShow' => true,
			'style' => array(
				'width' => $messageWidth,
				'height' => $messageHeight,
				'border-width' => $messageBorderWidth,
				'background-color' => $this->getOptionValue('sgpb-contact-message-bg-color'),
				'border-color' => $this->getOptionValue('sgpb-contact-message-border-color'),
				'color' => $this->getOptionValue('sgpb-contact-message-text-color'),
				'placeholder-color' => $this->getOptionValue('sgpb-contact-message-placeholder-color'),
				'resize' => $this->getOptionValue('sgpb-contact-message-resize')
			),
			'attrs' => array(
				'type' => 'textarea',
				'data-required' => true,
				'name' => 'sgpb-contact-message',
				'placeholder' => $this->getOptionValue('sgpb-contact-message-placeholder'),
				'class' => 'js-contact-field-message js-contact-color-input js-contact-message',
				'data-error-message-class' => 'sgpb-contact-message-error-message'
			)
		);

		// GDPR checkbox start
		$gdprLabel = $this->getOptionValue('sgpb-contact-gdpr-label');
		$gdprRequired = ($this->getOptionValue('sgpb-contact-gdpr-status')) ? true : false;
		$isShow = ($this->getOptionValue('sgpb-contact-gdpr-status')) ? true : false;

		$gdprCheckbox = array(
			'isShow' => $isShow,
			'style' => $inputStyles,
			'attrs' => array(
				'type' => 'customCheckbox',
				'data-required' => $gdprRequired,
				'name' => 'sgpb-contact-gdpr',
				'class' => 'js-contact-gdpr-input js-contact-gdpr-input js-contact-gdpr',
				'id' => 'sgpb-gdpr-field-label',
				'data-error-message-class' => 'sgpb-gdpr-error-message'
			),
			'label' => $gdprLabel,
			'text' => $this->getOptionValue('sgpb-contact-gdpr-text')
		);
		// GDPR checkbox end

		$submitWidth = $this->getOptionValue('sgpb-contact-submit-width');
		$submitWidth = AdminHelper::getCSSSafeSize($submitWidth);
		$submitHeight = $this->getOptionValue('sgpb-contact-submit-height');
		$submitHeight = AdminHelper::getCSSSafeSize($submitHeight);
		$submitTitle = $this->getOptionValue('sgpb-contact-submit-title');
		$submitBorderRadius = $this->getOptionValue('sgpb-contact-submit-border-radius');
		$submitBorderRadius = AdminHelper::getCSSSafeSize($submitBorderRadius);
		$submitBorderWidth = $this->getOptionValue('sgpb-contact-submit-border-width');
		$submitBorderWidth = AdminHelper::getCSSSafeSize($submitBorderWidth);
		$submitBorderColor = $this->getOptionValue('sgpb-contact-submit-border-color');
		$submitBorderColor = AdminHelper::getCSSSafeSize($submitBorderColor);

		$submit = array(
			'isShow' => true,
			'style' => array(
				'width' => $submitWidth,
				'height' => $submitHeight,
				'background-color' => $this->getOptionValue('sgpb-contact-submit-bg-color'),
				'color' => $this->getOptionValue('sgpb-contact-submit-text-color'),
				'border-radius' => $submitBorderRadius,
				'border-width' => $submitBorderWidth,
				'border-color' => $submitBorderColor,
				'text-transform' => 'none !important'
			),
			'attrs' => array(
				'value' => $submitTitle,
				'type' => 'submit',
				'data-title' => $submitTitle,
				'data-progress-title' => $this->getOptionValue('sgpb-contact-submit-title-progress'),
				'class' => 'js-contact-field-submit js-contact-color-submit js-contact-submit'
			)
		);

		$formData['first-name'] = $nameData;
		$formData['subject'] = $subjectData;
		$formData['email'] = $emailData;
		$formData['textarea'] = $message;
		$formData['gdpr'] = $gdprCheckbox;
		$formData['submit'] = $submit;

		return $formData;
	}
}
