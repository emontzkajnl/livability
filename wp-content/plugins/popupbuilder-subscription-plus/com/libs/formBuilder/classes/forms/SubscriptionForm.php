<?php
namespace sgpbform;
require_once(SGPB_FORM_CLASSES_FORMS.'Form.php');
use sgpbsubscriptionplus\SubscriptionPlusAdminHelper;

class SubscriptionForm extends form
{
	private $types = array('email', 'firstname', 'lastname', 'submit');

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
		return array('email', 'submit');
	}

	public function allowDeleteField($type)
	{
		$dontDelete = $this->mustNotHaveDelete();

		return !in_array($type, $dontDelete);
	}

	private function getDefaultRequiredOptions()
	{
		$types = array('email');

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
		$successMessage = $popupObj->getOptionValue('sgpb-subs-success-message');
		$errorMessage = $popupObj->getOptionValue('sgpb-subs-error-message');
		if (empty($errorMessage)) {
			$errorMessage = SGPB_SUBSCRIPTION_ERROR_MESSAGE;
		}
		ob_start();
		?>
		<div class="subs-form-messages sgpb-alert sgpb-alert-success sg-hide-element">
			<p><?php echo $successMessage; ?></p>
		</div>
		<div class="subs-form-messages sgpb-alert sgpb-alert-danger sg-hide-element">
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
		$form .= apply_filters('sgpbSubscriptionFormBefore', '');
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

		$formArgs = apply_filters('sgSubscriptionFormArgs', array(
			'action' => '',
			'method' => 'get',
			'data-popup-id' => $popupId,
			'data-validate-json' => $validateJson,
			'class' => array('sgpb-subscription-plus-form', 'sgpb-subscription', 'sgpb-subscription-plus-form-'.$popupId)
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
		$formFooter .= '</form>';
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
			$designStyles = $popupObj->getOptionValue('sgpb-subscription-fields-design-json');
		}

		$designStyles = json_decode($designStyles, true);

		$inputStyles = @$designStyles['inputStyles'];
		$submitStyles = @$designStyles['submitStyles'];
		$formStyles = @$designStyles['formStyles'];

		if (!empty($inputStyles)) {
			if (isset($inputStyles['active-border-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .js-subs-text-inputs:active,';
				$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .js-subs-text-inputs:focus {border-color: '.$inputStyles['active-border-color'].' !important;}';
			}
			if (isset($inputStyles['label-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-label-wrapper {color: '.$inputStyles['label-color'].' !important;}';
			}
			$placeholder = $inputStyles['placeholder'];
			unset($inputStyles['placeholder']);

			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-simple-inputs::-webkit-input-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';

			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-simple-inputs::-webkit-input-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-simple-inputs::-moz-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-simple-inputs:-ms-input-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-simple-inputs:-moz-placeholder {color: '.$placeholder.' !important; font-weight: lighter;}';

			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper,';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper select,';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' input[type="text"],';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' textarea,';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' input[type="email"],';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' input[type="number"] {';
			foreach($inputStyles as $styleKey => $styleValue) {
				$attr = '';
				if (is_numeric($styleValue)) {
					$attr = 'px';
				}

				if ($styleKey == 'border-color') {
					$styles .= $styleKey.': '.$styleValue.' !important;';
				}
				else if ($styleKey == 'active-border-color' || $styleKey == 'label-color') {
					continue;
				}
				else if ($styleKey == 'border-radius') {
					$styles .= $styleKey.': '.$styleValue.$attr.';';
				}
				else {
					if ($styleKey == 'height') {
						$styles .= $styleKey.': '.$styleValue.$attr.';';
					}
					else {
						$styles .= $styleKey.': '.$styleValue.$attr.' !important;';
					}
				}
			}
			$styles .= '}';

			// checkbox field extra styles
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' input[type="checkbox"] {';
			$styles .= '-webkit-appearance: checkbox;!important';
			$styles .= '}';
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' input[type="checkbox"]:before {';
			$styles .= 'content: none;';
			$styles .= '}';

			// advanced phone field extra styles
			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper {';
			$styles .= 'display: flex;';
			$styles .= 'background-color: transparent !important;';
			$styles .= '}';

			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper select {';
			$styles .= 'width: 35% !important;';
			$styles .= 'float: left;';
			$styles .= 'font-size: 12px !important;';
			$styles .= 'margin-top: 5px !important;';
			$styles .= '}';

			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-advanced-phone-field-wrapper input {';
			$styles .= 'width: 64% !important;';
			$styles .= 'float: right;';
			$styles .= 'margin-top: 5px !important;';
			$styles .= '}';

			$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' textarea {';
			$styles .= 'padding: 5px !important;';
			$styles .= '}';
		}
		if (!empty($submitStyles)) {
			if (isset($submitStyles['hover-background-color'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .js-subs-submit-btn:hover {background-color: '.$submitStyles['hover-background-color'].'!important;}';
			}
			if (isset($submitStyles['font-size'])) {
				$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .js-subs-submit-btn {font-size: '.$submitStyles['font-size'].'!important;}';
			}

			$styles .= '.sgpb-subscription-plus-form-'.$popupId.' .js-subs-submit-btn {';
			foreach($submitStyles as $styleKey => $styleValue) {
				$attr = '';
				if (is_numeric($styleValue)) {
					$attr = 'px';
				}

				if ($styleKey == 'background-color') {
					$styles .= $styleKey.': '.$styleValue.'!important;';
					$styles .= 'background: linear-gradient('.$styleValue.', '.$styleValue.');';
				}
				else if ($styleKey == 'hover-background-color' || $styleKey == 'font-size') {
					continue;
				}
				else {
					$styles .= $styleKey.': '.$styleValue.$attr.' !important;';
				}
			}
			$styles .= '}';
		}

		if (!empty($formStyles)) {
			if (is_object($popupObj)) {
				$formBackgroundColor = $popupObj->getOptionValue('sgpb-subs-form-bg-color');
				$formBackgroundOpacity = $popupObj->getOptionValue('sgpb-subs-form-bg-opacity');

				$formStyles['background-color'] = SubscriptionPlusAdminHelper::hex2rgb($formBackgroundColor, $formBackgroundOpacity).';';
			}
			if (isset($formStyles['padding']) && !isset($formStyles['padding-top'])) {
				$formStyles['padding-top'] = $formStyles['padding'];
				$formStyles['padding-right'] = $formStyles['padding'];
				$formStyles['padding-bottom'] = $formStyles['padding'];
				$formStyles['padding-left'] = $formStyles['padding'];
			}

			if ($popupObj->getOptionValue('sgpb-subs-field-horizontally')) {
				$fieldsHorizontally = $popupObj->getOptionValue('sgpb-subs-field-horizontally');
				if ($popupObj->getOptionValue('sgpb-subs-except-button')) {
					$exceptButton = $popupObj->getOptionValue('sgpb-subs-except-button');
				}
			}

			if (isset($fieldsHorizontally)) {
				$styles .= '.sgpb-form-'.$popupId.'-wrapper form .sgpb-inputs-container {display: flex !important;}';

				if ($inputStyles['margin-right'] == 0 && $inputStyles['margin-left'] == 0) {
					$styles .= '.sgpb-subscription-plus-form-'.$popupId.' .sgpb-inputs-container input {
							border-radius: 0px !important;
							border-left: none !important;
						}';
					$styles .= '.sgpb-subscription-plus-form-'.$popupId.' .sgpb-inputs-container .sgpb-each-field-main-wrapper:first-of-type input {
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
						$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .js-subs-submit-btn {
							border-radius: unset !important;
							border-top-right-radius: '.$submitStyles['border-radius'].' !important;
							border-bottom-right-radius: '.$submitStyles['border-radius'].' !important;
						}';
					}
				}
				if (isset($exceptButton) || $submitStyles['margin-left'] != 0) {
					$styles .= '.sgpb-form-wrapper .sgpb-subscription-plus-form-'.$popupId.' .sgpb-inputs-container .sgpb-each-field-main-wrapper:last-of-type input {
						border-top-right-radius: '.$inputStyles['border-radius'].' !important;
						border-bottom-right-radius: '.$inputStyles['border-radius'].' !important;
					}';
				}
			}

			$styles .= '.sgpb-subscription-plus-form-'.$popupId.' {';
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

		// input styles
		$inputWidth = $popupObj->getOptionValue('sgpb-subs-text-width');
		$inputStyles['width'] = $this->getCSSSafeSize($inputWidth);

		$inputHeight = $popupObj->getOptionValue('sgpb-subs-text-height');
		$inputStyles['height'] = $this->getCSSSafeSize($inputHeight);

		$inputBorderWidth = $popupObj->getOptionValue('sgpb-subs-text-border-width');
		$inputStyles['border-width'] = $this->getCSSSafeSize($inputBorderWidth);

		$inputStyles['border-color'] = $popupObj->getOptionValue('sgpb-subs-text-border-color');
		$inputStyles['background-color'] = $popupObj->getOptionValue('sgpb-subs-text-bg-color');
		$inputStyles['placeholder'] = $popupObj->getOptionValue('sgpb-subs-text-placeholder-color');
		$inputStyles['color'] = $popupObj->getOptionValue('sgpb-subs-text-color');
		$inputStyles['border-radius'] = $this->getCSSSafeSize($popupObj->getOptionValue('sgpb-subs-text-border-radius'));
		$inputStyles['active-border-color'] = $popupObj->getOptionValue('sgpb-subs-text-active-border-color');
		$inputStyles['label-color'] = $popupObj->getOptionValue('sgpb-subs-label-color');

		$inputStyles['margin-top'] = $popupObj->getOptionValue('sgpb-subs-input-margin-top');
		$inputStyles['margin-right'] = $popupObj->getOptionValue('sgpb-subs-input-margin-right');
		$inputStyles['margin-bottom'] = $popupObj->getOptionValue('sgpb-subs-input-margin-bottom');
		$inputStyles['margin-left'] = $popupObj->getOptionValue('sgpb-subs-input-margin-left');

		$formBgOpacity = $popupObj->getOptionValue('sgpb-subs-form-bg-opacity');
		$formBgColor = $popupObj->getOptionValue('sgpb-subs-form-bg-color');

		$formStyles['background-color'] = SubscriptionPlusAdminHelper::hex2rgb($formBgColor, $formBgOpacity).';';
		$formStyles['padding-top'] = $popupObj->getOptionValue('sgpb-subs-form-padding-top');
		$formStyles['padding-right'] = $popupObj->getOptionValue('sgpb-subs-form-padding-right');
		$formStyles['padding-bottom'] = $popupObj->getOptionValue('sgpb-subs-form-padding-bottom');
		$formStyles['padding-left'] = $popupObj->getOptionValue('sgpb-subs-form-padding-left');
		if ($popupObj->getOptionValue('sgpb-subs-form-padding')) {
			$formStyles['padding'] = $popupObj->getOptionValue('sgpb-subs-form-padding');
		}

		// submit styles
		$submitWidth = $popupObj->getOptionValue('sgpb-subs-btn-width');
		$submitStyles['width'] = $this->getCSSSafeSize($submitWidth);

		$submitFontSize = $popupObj->getOptionValue('sgpb-subs-btn-font-size');
		if (!empty($submitFontSize)) {
			$submitStyles['font-size'] = $this->getCSSSafeSize($submitFontSize);
		}

		$submitHeight = $popupObj->getOptionValue('sgpb-subs-btn-height');
		$submitStyles['height'] = $this->getCSSSafeSize($submitHeight);

		$submitStyles['background-color'] = $popupObj->getOptionValue('sgpb-subs-btn-bg-color');
		$submitStyles['color'] =  $popupObj->getOptionValue('sgpb-subs-btn-text-color');
		$submitStyles['border-width'] = $this->getCSSSafeSize($popupObj->getOptionValue('sgpb-subs-btn-border-width'));
		$submitStyles['border-radius'] = $this->getCSSSafeSize($popupObj->getOptionValue('sgpb-subs-btn-border-radius'));
		$submitStyles['border-color'] = $popupObj->getOptionValue('sgpb-subs-btn-border-color');
		$submitStyles['text-transform'] = 'none !important';
		$submitStyles['border-style'] = 'solid';
		$submitStyles['hover-background-color'] = $popupObj->getOptionValue('sgpb-subs-btn-bg-hover-color');

		$submitStyles['margin-top'] = $popupObj->getOptionValue('sgpb-subs-button-margin-top');
		$submitStyles['margin-right'] = $popupObj->getOptionValue('sgpb-subs-button-margin-right');
		$submitStyles['margin-bottom'] = $popupObj->getOptionValue('sgpb-subs-button-margin-bottom');
		$submitStyles['margin-left'] = $popupObj->getOptionValue('sgpb-subs-button-margin-left');

		$designSettings['inputStyles'] = $inputStyles;
		$designSettings['submitStyles'] = $submitStyles;
		$designSettings['formStyles'] = $formStyles;
		/* TODO fix or remove next 2 lines */
		// $designSettings['inputSettings'] = @$inputSettings;
		// $designSettings['buttonSettings'] = @$submitSettings;

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
		$requiredMessage = $popup->getOptionValue('sgpb-subs-validation-message');
		$emailMessage = $popup->getOptionValue('sgpb-subs-invalid-message');

		if (empty($emailMessage)) {
			$emailMessage = SGPB_SUBSCRIPTION_EMAIL_MESSAGE;
		}

		if (empty($requiredMessage)) {
			$requiredMessage = SGPB_SUBSCRIPTION_VALIDATION_MESSAGE;
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
			$fieldName = strtolower(@$obj['fieldName']);

			if ($type == 'gdpr') {
				$fieldNameAndValues[$fieldName] = 'Checked';
				continue;
			}

			foreach ($savedData as $optionName => $optionValue) {

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
						$fieldNameAndValues[$fieldName.' '.$key] = $value;
					}
				}
			}
		}

		return $fieldNameAndValues;
	}

	private function implodeValues($values, $typeObj)
	{
		$separator = ', ';
		$separator = apply_filters('sgpbNameValuesSeparator', $separator, $typeObj);

		return implode($separator, $values);

	}

	public function getFieldKeyAndLabel($settingsJson, $excludedTypes = array())
	{
		if (empty($settingsJson)) {
			return array();
		}
		$data = array();
		foreach ($settingsJson as $key => $setting) {
			$type = $setting['type'];
			if (in_array($type, $excludedTypes)) {
				continue;
			}
			$fieldObj = $this->createFieldObjByType($type);
			$settings = $this->getFieldSettings($key, $fieldObj);

			$name = @$settings['attrs']['name'];
			$fieldName = @$setting['fieldName'];
			$data[$name] = $fieldName;
		}

		return $data;
	}
}
