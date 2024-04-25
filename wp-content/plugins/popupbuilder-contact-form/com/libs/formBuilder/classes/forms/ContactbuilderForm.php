<?php
namespace sgpbcontactform;
require_once(SGPB_CF_FORM_CLASSES_FORMS.'Form.php');
use sgpbcontactform\AdminHelper;

class ContactbuilderForm extends form
{
	private $types = array('firstname', 'subject', 'email', 'textarea' , 'submit');

	public function getTypes()
	{
		return $this->types;
	}

	public function __construct($savedObj)
	{
		$this->setSavedObj($savedObj);
		$this->addFields();
	}

	public function __toString()
	{
		$form = $this->renderForm();

		return $form;
	}

	private function addFields()
	{
		$savedObjs = $this->getSavedObj();
		if (empty($savedObjs)) {
			$types = $this->getTypes();
			$this->addCurrentFields($types);
			return true;
		}
		$types = array();

		foreach ($savedObjs as $obj) {
			if (empty($obj)) {
				continue;
			}
			$types[] = $obj['type'];
		}

		$this->addCurrentFields($types);

		return true;
	}

	public function mustNotHaveDelete()
	{
		return array('submit');
	}

	public function allowDeleteField($type)
	{
		$dontDelete = $this->mustNotHaveDelete();

		return !in_array($type, $dontDelete);
	}

	private function getDefaultRequiredOptions()
	{
		$types = array('email', 'textarea');

		return $types;
	}


	public function getFieldsListShortHtml()
	{
		$template = $this->getFieldShortIconTemplate();

		return $template;
	}

	public function getCurrentFieldsAdminHtml()
	{
		$html = $this->getCurrentFieldsAdminTemplate();

		return $html;
	}

	private function getFormMessages()
	{
		$popupObj = $this->getPopupObj();
		$successMessage = $popupObj->getOptionValue('sgpb-contact-success-message');
		$errorMessage = $popupObj->getOptionValue('sgpb-contact-error-message');
		if (empty($errorMessage)) {
			$errorMessage = SGPB_CONTACT_FORM_ERROR_MESSAGE;
		}
		ob_start();
		?>
		<div class="contact-form-messages sgpb-alert sgpb-alert-success sg-hide-element">
			<p><?php echo $successMessage; ?></p>
		</div>
		<div class="contact-form-messages sgpb-alert sgpb-alert-danger sg-hide-element">
			<p><?php echo $errorMessage; ?></p>
		</div>
		<?php
		$messages = ob_get_contents();
		ob_end_clean();

		return $messages;
	}

	public function renderForm()
	{
		$formFields = parent::renderForm();
		$popupObj = $this->getPopupObj();
		$popupId = $popupObj->getId();

		$form = '<div class="sgpb-form-wrapper sgpb-form-'.$popupId.'-wrapper">';
		$form .= apply_filters('sgpbcontactformBefore', '');
		$form .= $this->getFormMessages();
		$form .= $this->getFormOpeningTagHtml();
		$form .= $formFields;
		$form .= $this->getFormFooter();
		$form .= '</div>';
		$form .= $this->getFormStyles();

		return $form;
	}

	private function getFormOpeningTagHtml()
	{
		$popupObj = $this->getPopupObj();
		$popupId = $popupObj->getId();
		$validateJson = $this->getValidateJson();

		$formArgs = apply_filters('sgCFFormArgs', array(
			'action' => '',
			'method' => 'get',
			'data-popup-id' => $popupId,
			'data-validate-json' => $validateJson,
			'class' => array('sgpb-contact-form-form', 'sgpb-contact', 'sgpb-contact-form-'.$popupId)
		));

		$formAttrStr = $this->createAttrStr($formArgs);
		$formTag = '<form '.$formAttrStr.' >';
		$formTag .= apply_filters('sgpbFormPrepend', '');
		$formTag .= '<div class="sgpb-inputs-container">';

		return $formTag;
	}

	private function getFormFooter()
	{
		$formFooter = apply_filters('sgpbFormFooter', '');
		$formFooter .= '</div></form>';
		$formFooter .= apply_filters('sgpbFormAfter', '');

		return $formFooter;
	}

	public function getFormStyles($designStyles = '', $popupId = 0, $savedPopup = true)
	{
		$styles = '<style>';
		$popupObj = $this->getPopupObj();
		if (is_object($popupObj)) {
			$popupId = $popupObj->getId();
		}

		if (!$savedPopup) {
			$popupId = 0;
 		}

		if (empty($designStyles)) {
			$popupObj = $this->getPopupObj();
			$designStyles = $this->getFieldsDesignJson($popupObj);
		}
		// front side
		if (empty($designStyles)) {
			$designStyles = $popupObj->getOptionValue('sgpb-contact-fields-design-json');
		}

		$designStyles = json_decode($designStyles, true);

		$inputStyles = @$designStyles['inputStyles'];
		$messageStyles = @$designStyles['messageStyles'];
		$submitStyles = @$designStyles['submitStyles'];
		$formStyles = @$designStyles['formStyles'];

		if (!empty($inputStyles)) {
			if (isset($inputStyles['active-border-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .js-contact-text-inputs:active,';
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .js-contact-text-inputs:focus {border-color: '.$inputStyles['active-border-color'].' !important;}';
			}
			if (isset($inputStyles['label-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-label-wrapper {color: '.$inputStyles['label-color'].';}';
			}
			$placeholder = $inputStyles['placeholder'];
			unset($inputStyles['placeholder']);

			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-inputs::-webkit-input-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-inputs::-moz-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-inputs:-ms-input-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-inputs:-moz-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';

			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper,';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper select,';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-multiselect-main-wrapper select,';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-field-dropdown-wrapper select,';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' input[type="text"],';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' input[type="email"],';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' input[type="number"] {';

			foreach($inputStyles as $styleKey => $styleValue) {
				$attr = '';
				if (is_numeric($styleValue)) {
					$attr = 'px';
				}
				if ($styleKey == 'border-radius') {
					$styles .= $styleKey.': '.$styleValue.$attr.';';
				}
				else {
					$styles .= $styleKey.': '.$styleValue.$attr.' !important;';
				}
			}
			$styles .= '}';

			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-field-checkbox-wrapper label,';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-contact-text-checkbox-gdpr,';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-field-radiobutton-wrapper label {';
			foreach($inputStyles as $styleKey => $styleValue) {
				if ($styleKey != 'color') {
					continue;
				}

				$styles .= $styleKey.': '.$styleValue.';';
			}
			$styles .= '}';

			// checkbox field extra styles
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' input[type="checkbox"] {';
			$styles .= '-webkit-appearance: checkbox;';
			$styles .= '}';

			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' input[type="checkbox"]:before {';
			$styles .= 'content: none;';
			$styles .= '}';
			// advanced phone field extra styles
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper {';
			$styles .= 'display: flex;';
			$styles .= 'border-width: 0px !important;';
			$styles .= 'background-color: transparent !important;';
			$styles .= '}';

			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper select {';
			$styles .= 'width: 35% !important;';
			$styles .= 'float: left;';
			$styles .= 'font-size: 12px !important;';
			$styles .= 'margin-top: 5px !important;';
			$styles .= '}';

			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper input {';
			$styles .= 'width: 64% !important;';
			$styles .= 'float: right;';
			$styles .= 'margin-top: 5px !important;';
			$styles .= '}';
		}

		if (!empty($messageStyles)) {
			$placeholder = '';

			if (!empty($messageStyles['placeholder'])) {
				$placeholder = $messageStyles['placeholder'];
			}
			unset($messageStyles['placeholder']);

			if (isset($messageStyles['message-active-border-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .js-contact-field-textarea:active,';
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .js-contact-field-textarea:focus {border-color: '.$messageStyles['message-active-border-color'].';}';
			}
			if (isset($messageStyles['message-label-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-label-textarea-wrapper {color: '.$messageStyles['message-label-color'].';}';
			}

			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-textareas::-webkit-input-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-textareas::-moz-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-textareas:-ms-input-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-simple-textareas:-moz-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';

			$styles .= '.sgpb-contact-form-'.$popupId.' textarea {';
			foreach($messageStyles as $styleKey => $styleValue) {
				$attr = '';
				if (is_numeric($styleValue)) {
					$attr = 'px';
				}
				switch($styleKey){
					case 'message-active-border-color':
					case 'message-label-color':
						break;
					case 'message-border-color':
					case 'border-color':
					case 'width':
					case 'height':
						$styles .= $styleKey.': '.$styleValue.';';
						break;
					case 'border-radius':
						$styles .= $styleKey.': '.$styleValue.$attr.';';
						break;
					default:
						$styles .= $styleKey.': '.$styleValue.$attr.' !important;';
				}
			}
			$styles .= '}';
		}
		if (!empty($submitStyles)) {
			if (isset($submitStyles['hover-background-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .js-contact-submit-btn:hover {background-color: '.$submitStyles['hover-background-color'].' !important;}';
			}
			if (isset($submitStyles['font-size'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .js-contact-submit-btn {font-size: '.$submitStyles['font-size'].' !important;}';
			}
			$styles .= '.sgpb-contact-form-'.$popupId.' .js-contact-submit-btn {';
			foreach($submitStyles as $styleKey => $styleValue) {
				$attr = '';
					if (is_numeric($styleValue)) {
						$attr = 'px';
				}

				$styles .= $styleKey.': '.$styleValue.$attr.' !important;';
			}
			$styles .= '}';
		}
		if (!empty($formStyles)) {
			if ($popupObj->getOptionValue('sgpb-contact-field-horizontally')) {
				$fieldsHorizontally = $popupObj->getOptionValue('sgpb-contact-field-horizontally');
				if ($popupObj->getOptionValue('sgpb-contact-except-button')) {
					$exceptButton = $popupObj->getOptionValue('sgpb-contact-except-button');
				}
			}

			if (isset($fieldsHorizontally)) {
				$styles .= '.sgpb-form-'.$popupId.'-wrapper form .sgpb-inputs-container {display: flex !important; overflow-x: scroll;}';

				if ($inputStyles['margin-right'] == 0 && $inputStyles['margin-left'] == 0) {
					$styles .= '.sgpb-contact-form-'.$popupId.' .sgpb-inputs-container input, textarea {
							border-radius: 0px !important;
							border-left: none !important;
						}';
					$styles .= '.sgpb-contact-form-'.$popupId.' .sgpb-inputs-container .sgpb-each-field-main-wrapper:first-of-type input {
							border-top-left-radius: '.$inputStyles['border-radius'].' !important;
							border-bottom-left-radius: '.$inputStyles['border-radius'].' !important;
							border-left: '.$inputStyles['border-width'].' solid '.$inputStyles['border-color'].' !important;
						}';
				}
				if (!isset($exceptButton)) {
					$styles .= '.sgpb-form-'.$popupId.'-wrapper form {
						display: flex !important;
						justify-content: center;
						overflow-x: hidden;
					}';
					if ($submitStyles['margin-left'] == 0) {
						$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .js-contact-submit-btn {
							border-radius: unset !important;
							border-top-right-radius: '.$submitStyles['border-radius'].' !important;
							border-bottom-right-radius: '.$submitStyles['border-radius'].' !important;
						}';
					}
				}
				if (isset($exceptButton) || $submitStyles['margin-left'] != 0) {
					$styles .= '.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-inputs-container .sgpb-each-field-main-wrapper:last-of-type input,.sgpb-form-wrapper .sgpb-contact-form-'.$popupId.' .sgpb-inputs-container .sgpb-each-field-main-wrapper:last-of-type textarea {
						border-top-right-radius: '.$inputStyles['border-radius'].' !important;
						border-bottom-right-radius: '.$inputStyles['border-radius'].' !important;
					}';
				}
			}

			$styles .= '.sgpb-contact-form-'.$popupId.' {';
			$styles .= 'background-color: '.$formStyles['background-color'].';';
			$styles .= 'padding-top: '.@$formStyles['padding-top'].'px;';
			$styles .= 'padding-right: '.@$formStyles['padding-right'].'px;';
			$styles .= 'padding-bottom: '.@$formStyles['padding-bottom'].'px;';
			$styles .= 'padding-left: '.@$formStyles['padding-left'].'px;';
			$styles .= '}';
		}
		$styles .= '</style>';

		return $styles;
	}

	public function getFieldsJson()
	{
		$allSettings = $this->getFieldsSettingsObj();

		$json = json_encode($allSettings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);

		return $json;
	}

	public function getFieldsDesignJson($popupObj)
	{
		$designSettings = array();
		$inputStyles = array();
		$submitStyles = array();
		$formStyles = array();
		$messageStyles = array();

		// input styles
		$inputWidth = $popupObj->getOptionValue('sgpb-contact-inputs-width');
		$inputStyles['width'] = $this->getCSSSafeSize($inputWidth);

		$inputHeight = $popupObj->getOptionValue('sgpb-contact-inputs-height');
		$inputStyles['height'] = $this->getCSSSafeSize($inputHeight);

		$inputBorderWidth = $popupObj->getOptionValue('sgpb-contact-inputs-border-width');
		$inputStyles['border-width'] = $this->getCSSSafeSize($inputBorderWidth);

		$inputStyles['border-color'] = $popupObj->getOptionValue('sgpb-contact-inputs-border-color');
		$inputStyles['background-color'] = $popupObj->getOptionValue('sgpb-contact-inputs-bg-color');
		$inputStyles['placeholder'] = $popupObj->getOptionValue('sgpb-contact-inputs-placeholder-color');
		$inputStyles['color'] = $popupObj->getOptionValue('sgpb-contact-inputs-text-color');
		$inputStyles['border-radius'] = $this->getCSSSafeSize($popupObj->getOptionValue('sgpb-contact-text-border-radius'));
		$inputStyles['active-border-color'] = $popupObj->getOptionValue('sgpb-contact-text-active-border-color');
		$inputStyles['label-color'] = $popupObj->getOptionValue('sgpb-contact-label-color');
		$inputStyles['border-style'] = 'solid';
		$inputStyles['margin-top'] = $popupObj->getOptionValue('sgpb-contact-input-margin-top');
		$inputStyles['margin-right'] = $popupObj->getOptionValue('sgpb-contact-input-margin-right');
		$inputStyles['margin-bottom'] = $popupObj->getOptionValue('sgpb-contact-input-margin-bottom');
		$inputStyles['margin-left'] = $popupObj->getOptionValue('sgpb-contact-contact-margin-left');

		$formBgOpacity = $popupObj->getOptionValue('sgpb-contact-form-bg-opacity');
		$formBgColor = $popupObj->getOptionValue('sgpb-contact-form-bg-color');

		$formStyles['background-color'] = AdminHelper::hex2rgb($formBgColor, $formBgOpacity).';';
		$formStyles['padding-top'] = $popupObj->getOptionValue('sgpb-contact-form-padding-top');
		$formStyles['padding-right'] = $popupObj->getOptionValue('sgpb-contact-form-padding-right');
		$formStyles['padding-bottom'] = $popupObj->getOptionValue('sgpb-contact-form-padding-bottom');
		$formStyles['padding-left'] = $popupObj->getOptionValue('sgpb-contact-form-padding-left');
		if ($popupObj->getOptionValue('sgpb-contact-form-padding')) {
			$formStyles['padding'] = $popupObj->getOptionValue('sgpb-contact-form-padding');
		}

		// submit styles
		$submitFontSize = $popupObj->getOptionValue('sgpb-contact-btn-font-size');
		if (!empty($submitFontSize)) {
			$submitStyles['font-size'] = $this->getCSSSafeSize($submitFontSize);
		}

		$submitWidth = $popupObj->getOptionValue('sgpb-contact-submit-width');
		$submitStyles['width'] = $this->getCSSSafeSize($submitWidth);

		$submitHeight = $popupObj->getOptionValue('sgpb-contact-submit-height');
		$submitStyles['height'] = $this->getCSSSafeSize($submitHeight);

		$submitStyles['background-color'] = $popupObj->getOptionValue('sgpb-contact-submit-bg-color');
		$submitStyles['color'] =  $popupObj->getOptionValue('sgpb-contact-submit-text-color');
		$submitStyles['border-width'] = $this->getCSSSafeSize($popupObj->getOptionValue('sgpb-contact-submit-border-width'));
		$submitStyles['border-radius'] = $this->getCSSSafeSize($popupObj->getOptionValue('sgpb-contact-submit-border-radius'));
		$submitStyles['border-color'] = $popupObj->getOptionValue('sgpb-contact-submit-border-color');
		$submitStyles['text-transform'] = 'none !important';
		$submitStyles['border-style'] = 'solid';
		$submitStyles['hover-background-color'] = $popupObj->getOptionValue('sgpb-contact-btn-bg-hover-color');
		$submitStyles['margin-top'] = $popupObj->getOptionValue('sgpb-contact-button-margin-top');
		$submitStyles['margin-right'] = $popupObj->getOptionValue('sgpb-contact-button-margin-right');
		$submitStyles['margin-bottom'] = $popupObj->getOptionValue('sgpb-contact-button-margin-bottom');
		$submitStyles['margin-left'] = $popupObj->getOptionValue('sgpb-contact-button-margin-left');

		// mesage styles
		$messageWidth = $popupObj->getOptionValue('sgpb-contact-message-width');
		$messageStyles['width'] = $this->getCSSSafeSize($messageWidth);

		$messageHeight = $popupObj->getOptionValue('sgpb-contact-message-height');
		$messageStyles['height'] = $this->getCSSSafeSize($messageHeight);

		$messageStyles['background-color'] = $popupObj->getOptionValue('sgpb-contact-message-bg-color');
		$messageStyles['border-width'] = $popupObj->getOptionValue('sgpb-contact-message-border-width');
		$messageStyles['border-color'] = $popupObj->getOptionValue('sgpb-contact-message-border-color');
		$messageStyles['placeholder'] = $popupObj->getOptionValue('sgpb-contact-message-placeholder-color');
		$messageStyles['color'] =  $popupObj->getOptionValue('sgpb-contact-message-text-color');
		$messageStyles['text-transform'] = 'none !important';
		$messageStyles['border-radius'] = $this->getCSSSafeSize($popupObj->getOptionValue('sgpb-contact-message-border-radius'));
		$messageStyles['message-active-border-color'] = $popupObj->getOptionValue('sgpb-contact-message-active-border-color');
		$messageStyles['message-label-color'] = $popupObj->getOptionValue('sgpb-contact-message-label-color');
		$messageStyles['margin-top'] = $popupObj->getOptionValue('sgpb-contact-message-margin-top');
		$messageStyles['margin-right'] = $popupObj->getOptionValue('sgpb-contact-message-margin-right');
		$messageStyles['margin-bottom'] = $popupObj->getOptionValue('sgpb-contact-message-margin-bottom');
		$messageStyles['margin-left'] = $popupObj->getOptionValue('sgpb-contact-message-margin-left');
		$messageStyles['resize'] = $popupObj->getOptionValue('sgpb-contact-message-resize');

		$designSettings['inputStyles'] = $inputStyles;
		$designSettings['messageStyles'] = $messageStyles;
		$designSettings['submitStyles'] = $submitStyles;
		$designSettings['formStyles'] = $formStyles;

		return json_encode($designSettings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	public function filterSettings($settings, $fieldObj)
	{
		if (!$this->getId()) {
			$type = $fieldObj->getType();
			$defaultRequiredOptions = $this->getDefaultRequiredOptions();
			if (in_array($type, $defaultRequiredOptions)) {
				$settings['required'] = true;
			}
		}
		$settings['type'] = $type;

		return $settings;
	}

	public function getValidateJson()
	{
		$savedObj = $this->getSavedObj();
		$popup = $this->getPopupObj();
		$requiredMessage = $popup->getOptionValue('sgpb-contact-required-message');
		$emailMessage = $popup->getOptionValue('sgpb-contact-invalid-email-message');

		if (empty($requiredMessage)) {
			$requiredMessage = SGPB_CONTACT_FORM_REQUIRED_MESSAGE;
		}

		if (empty($emailMessage)) {
			$emailMessage = SGPB_CONTACT_FORM_EMAIL_MESSAGE;
		}

		$validateObj = '{ ';
		$rules = '"rules": { ';
		$messages = '"messages": { ';

		foreach ($savedObj as $key => $obj) {
			if (empty($obj['required']) || empty($obj)) {
				continue;
			}
			$type = $obj['type'];
			$fieldObj = $this->createFieldObjByType($type);
			$fieldObj->setFieldSettings($obj);
			$settings = $this->getFieldSettings($key, $fieldObj);

			$name = $settings['attrs']['name'];
			if ($type == 'advancedphone') {
				$name .= '[number]';
			}

			if ($type == 'email') {
				$rules .= '"'.$name.'": {
					"required": true,
					"email": true
				},';
				$messages .= '"'.$name.'": {
					"required": "'.$requiredMessage.'",
					"email": "'.$emailMessage.'"
				},';
				continue;
			}
			if ($type == 'number') {
				$rules .= '"'.$name.'": {
					"required": true,
					"number": true
				},';
				$messages .= '"'.$name.'": "'.$requiredMessage.'",';
				continue;
			}
			if ($type == 'phone' || $type == 'advancedphone') {
				$rules .= '"'.$name.'": {
					"required": true,
					"minlength": "6",
					"number": true
				},';
				$messages .= '"'.$name.'": {
					"required": "'.$requiredMessage.'"
				},';
				continue;
			}

			$messages .= '"'.$name.'": "'.$requiredMessage.'",';
			$rules .= '"'.$name.'": "required"'.',';
		}
		$rules = rtrim($rules, ',');
		$messages = rtrim($messages, ',');

		$rules .= '},';
		$messages .= '}';

		$validateObj .= $rules;
		$validateObj .= $messages;

		$validateObj .= '}';

		return htmlspecialchars($validateObj);
	}

	public function getFieldNameAndValues($savedData)
	{
		$savedObj = $this->getSavedObj();
		$fieldNameAndValues = array();

		foreach ($savedObj as $key => $obj) {
			$type = $obj['type'];
			$fieldObj = $this->createFieldObjByType($type);
			$settings = $this->getFieldSettings($key, $fieldObj);
			$name = @$settings['attrs']['name'];
			$fieldName = @$settings['fieldName'];

			if ($type == 'gdpr') {
				$fieldNameAndValues[$fieldName] = 'Checked';
				continue;
			}
			foreach ($savedData as $optionName => $optionValue) {
				if (is_array($optionValue)) {
					$optionValue = $this->implodeValues($optionValue, $fieldObj);
				}
				if ($optionName == $name) {
					$fieldNameAndValues[$fieldName] = $optionValue;
				}
				else if ($optionName.'[]' == $name) {
					$fieldNameAndValues[$fieldName] = @implode (", ", $optionValue);
				}
			}
		}

		return $fieldNameAndValues;
	}

	public function getFieldNameAndValuesFromSavedData($savedData, $settingsJson)
	{
		if (empty($savedData) || empty($settingsJson)) {
			return array();
		}
		$fieldNameAndValues = array();
		foreach ($savedData as $optionName => $optionValue) {
			foreach ($settingsJson as $key => $setting) {
				$type = $setting['type'];

				$fieldObj = $this->createFieldObjByType($type);
				$settings = $this->getFieldSettings($key, $fieldObj);

				$name = @$settings['attrs']['name'];
				$fieldName = @$setting['fieldName'];

				if ($optionName == $name) {
					if (is_array($optionValue)) {
						$optionValue = $this->implodeValues($optionValue, $fieldObj);
					}
					if (empty($fieldNameAndValues[$fieldName])) {
						$fieldNameAndValues[$fieldName] = $optionValue;
					}
					else {
						$fieldNameAndValues[$fieldName.' '.$key] = $optionValue;
					}
				}
				else if ($optionName.'[]' == $name) {
					$value = @implode (", ", $optionValue);
					if (empty($fieldNameAndValues[$fieldName])) {
						$fieldNameAndValues[$fieldName] = $value;
					}
					else {
						$fieldNameAndValues[$fieldName.' '.$key] = $optionValue;
					}
				}
			}
		}

		return $fieldNameAndValues;
	}

	private function implodeValues($values, $typeObj)
	{
		$separator = ', ';
		$separator = apply_filters('sgpbContactNameValuesSeparator', $separator, $typeObj);

		return implode($separator, $values);

	}
}
