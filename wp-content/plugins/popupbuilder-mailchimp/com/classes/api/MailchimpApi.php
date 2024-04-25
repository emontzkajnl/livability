<?php
namespace sgpbm;
use sgpb\AdminHelper as PopupBuilderAdminHelper;
use sgpb\Functions;
use sgpb\MultipleChoiceButton;


class MailchimpApi
{
	private static $instance;
	private static $mailchimpObj;
	private $lists = null;
	private $mergeFields = null;
	private $interestFields = null;
	private $showRequiredFields = false;
	private $savedOption = false;

	// The singleton method
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __clone()
	{

	}

	private function __construct()
	{
		$apiKey = get_option('SGPB_MAILCHIMP_API_KEY');
		MailchimpApi::$mailchimpObj = MailchimpSingleton::getInstance($apiKey);
	}

	public function getShowRequiredFields()
	{
		return $this->showRequiredFields;
	}

	public function setShowRequiredFields($showRequiredFields)
	{
		$status = true;

		if (!isset($showRequiredFields) || !$showRequiredFields) {
			$status = false;
		}

		$this->showRequiredFields = $status;
	}

	public function getSavedOptionFields()
	{
		return $this->savedOption;
	}

	public function setSavedOptionFields($savedOption)
	{
		$this->savedOption = $savedOption;
	}

	/**
	 * Get Mailchimp all List data
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function getAllLists()
	{
		if (!isset($this->lists)) {
			$this->lists = MailchimpApi::$mailchimpObj->get('/lists/', array('count' => SGPB_MAILCHIMP_LIST_LIMIT));
		}

		return $this->lists;
	}

	/**
	 * Get Mailchimp account forms count
	 *
	 * @since 1.0.0
	 *
	 * @return int $total
	 */
	public function getTotalCount()
	{
		$lists = $this->getAllLists();
		$total = 0;

		if (isset($lists) && is_array($lists)) {
			$total = @$lists['total_items'];
		}

		return $total;
	}

	/**
	 * Get Mailchimp account all forms id and title
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function getListsIdAndTitle()
	{
		$idAndTitles = array();
		$allLists = $this->getAllLists();

		if (empty($allLists['lists'])) {
			return $idAndTitles;
		}

		foreach ($allLists['lists'] as $list) {
			if (empty($list['id']) ||  empty($list['name'])) {
				continue;
			}
			$listId = $list['id'];

			$idAndTitles[$listId] = $list['name'];
		}

		return $idAndTitles;
	}

	public function getListMergeFields($listId)
	{
		if (!isset($this->mergeFields[$listId])) {
			$this->mergeFields[$listId] = self::$mailchimpObj->get('/lists/'.$listId.'/merge-fields');
		}

		return $this->mergeFields[$listId];
	}

	public function getListInterestData($listId)
	{
		if (isset($this->interestFields[$listId])) {
			return $this->interestFields[$listId];
		}

		$categories = self::$mailchimpObj->get('/lists/'.$listId.'/interest-categories/');

		if (empty($categories['categories'])) {
			return array();
		}
		$interest = array();
		foreach ($categories['categories'] as $category) {
			$categoryData = array(
				'label' => $category['title'],
				'type' => $category['type'],
				'public' => true,
				'required' => false,
				'name' => $category['id']
			);
			$categoryOptions = array();
			$categoriesElements = self::$mailchimpObj->get('/lists/'.$category['list_id'].'/interest-categories/'.$category['id'].'/interests', array('count'=> SGPB_MAILCHIMP_LIST_LIMIT));
			if (!empty($categoriesElements['interests'])) {
				foreach ($categoriesElements['interests'] as $element) {
					$categoryOptions[$element['id']] = $element['name'];
				}
			}
			$categoryData['options']['choices'] = $categoryOptions;
			$categoryData['options']['groupOption'] = true;

			if ($category['type'] != 'dropdown') {
				$categoryData['options']['choices'] = $this->reformatInterestData($categoryOptions, $categoryData);
			}

			$interest[] = $categoryData;
		}

		$this->interestFields[$listId] = $interest;

		return $this->interestFields[$listId];
	}

	private function reformatInterestData($fieldOptions, $data)
	{
		$type = $data['type'];
		$fieldName = $data['name'];
		if ($type == 'checkboxes') {
			$type = 'checkbox';
			$fieldName .= '[]';
		}

		$radioButtonOptions = array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'sgpbm-interest-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'sgpbm-interest-label-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'mc-field-group input-group'
				)
			),
			'buttonPosition' => 'left',
			'nextNewLine' => true,
			'fields' =>  array()
		);

		foreach ($fieldOptions as $optionKey => $optionValue) {
			$value = $optionValue;

			if (isset($data['options']['groupOption'])) {
				$value = $optionKey;
			}

			$radioButtonOptions['fields'][] = array(
				'attr' => array(
					'type' => $type,
					'name' => $fieldName,
					'id' => $data['name'].$optionKey.'-mailchimp-field',
					'value' => $value
				),
				'label' => array(
					'name' => __($optionValue, SG_POPUP_TEXT_DOMAIN)
				)
			);
		}

		$interestFieldsConfigData = $radioButtonOptions;

		return $interestFieldsConfigData;
	}

	/**
	 * reformat form merge fields data
	 *
	 * @since 1.0.0
	 *
	 * @param array $mergeFields
	 *
	 * @return array $data
	 */
	public function reformatMergeFieldsForBuilder($mergeFields)
	{
		$data = array();

		if (empty($mergeFields['merge_fields'])) {
			return $data;
		}
		$options = $this->getSavedOptionFields();

		$mailData['type'] = 'email';
		$mailData['name'] = 'EMAIL';
		$mailData['label'] = @$options['emailLabel'];
		$mailData['public'] = true;
		$mailData['required'] = true;
		$mailData['options'] = array();

		$data[] = $mailData;
		foreach ($mergeFields['merge_fields'] as $fieldData) {
			if (empty($fieldData['type'])) {
				continue;
			}
			$type = $fieldData['type'];
			$currentData['type'] = $type;
			$currentData['name'] = $fieldData['tag'];
			$currentData['label'] = @$fieldData['name'];
			$currentData['public'] = @$fieldData['public'];
			$currentData['required'] = $fieldData['required'];
			if ($type == 'radio') {
				$currentData['options']['choices'] = $this->reformatInterestData($fieldData['options']['choices'], $currentData);
			}
			else {
				$currentData['options'] = @$fieldData['options'];
			}

			$data[] = $currentData;
		}

		return $data;
	}

	public function createSimpleElement($field)
	{
		$reqStar = '';
		$fieldHtml = '';

		// for hide not required field when show required fields was enabled
		if (!$field['required'] && $this->getShowRequiredFields()) {
			return $fieldHtml;
		}
		$optionStatusClassName = 'sgpb-optional-field';
		$hideField = '';

		if ($field['required']) {
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}

		// for do not hide required but hidden element
		if (!$field['required'] && !$field['public']) {
			$hideField = 'sgpb-hide-element';
		}
		$inputArgs = array(
			'type' => $field['type'],
			'name' => $field['name'],
			'id' => 'sgpbm-mce-'.$field['name'],
			'class' => 'sgpb-mailchimp-input',
			'autocomplete' => 'off',
			'data-error-message-class' => $field['name'].'-error-message',
			'value' => ''
		);
		$attrStr = PopupBuilderAdminHelper::createAttrs($inputArgs);

		$fieldHtml = '<div class="mc-field-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'" >';
		$fieldHtml .= '<label class="sgpb-label" for="sgpbm-mce-'.$field['name'].'"><span class="sgpb-label-'.$field['name'].'">'.$field['label'].'</span>'.$reqStar."</label>";
		$fieldHtml .= Functions::createInputElement($attrStr, '');
		$fieldHtml .= '<div id="simple-validate-message" class="'.$field['name'].'-error-message sgpb-validate-message"></div>';
		$fieldHtml .= '</div>';

		return $fieldHtml;
	}

	public function createDateElement($field)
	{
		$reqStar = '';
		$fieldHtml = '';

		// for hide not required field when show required fields was enabled
		if (!$field['required'] && $this->getShowRequiredFields()) {
			return $fieldHtml;
		}
		$optionStatusClassName = 'sgpb-optional-field';
		$hideField = '';

		if ($field['required']) {
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}

		// for hide not required but hidden element
		if (!$field['required'] && !$field['public']) {
			$hideField = 'sgpb-hide-element';
		}

		if (empty($field['options']['date_format'])) {
			return $fieldHtml;
		}

		$formats = explode('/', $field['options']['date_format']);

		if (empty($formats)) {
			return $fieldHtml;
		}
		$autoFocusId = 'sgpbm-mce-'.$field['name'].'-'.$formats[0];

		$fieldHtml = '<div class="mc-field-group sgpb-dates-wrapper sgpb-field-group '.$optionStatusClassName.' '.$hideField.'">';
		$fieldHtml .= '<label class="sgpb-label" for="'.$autoFocusId.'">'.$field['label'].$reqStar.'</label>';

		$inputArgs = array(
			'type' => 'text',
			'class' => $field['required'].'sgpbm-input sgpbm-datepart sgpb-mailchimp-input',
			'data-error-message-class' => $field['name'].'-error-message',
			'data-class-name' => 'sgpbm-datepart',
			'autocomplete' => 'off',
			'value' => ''
		);
		$lastFieldType = end($formats);

		foreach ($formats as $format) {
			$inputArgs['id'] = 'sgpbm-mce-'.$field['name'].'-'.$format;
			$inputArgs['size'] = strlen($format);
			$inputArgs['maxlength'] = strlen($format);
			$inputArgs['placeholder'] = $format;
			$hideSeparator = $lastFieldType == $format;

			switch ($format) {
				case 'DD':
					$inputArgs['name'] = $field['name'].'[day]';
					$attrStr = PopupBuilderAdminHelper::createAttrs($inputArgs);
					$fieldHtml .= '<span class="sgpbm-subfield sgpbm-dayfield sgpbm-subfield">';
						$fieldHtml .= Functions::createInputElement($attrStr);
					$fieldHtml .= '</span>';
					if (!$hideSeparator) {
						$fieldHtml .= '<span class="sgpb-separator">/</span>';
					}
				break;
				case 'MM':
					$inputArgs['name'] = $field['name'].'[month]';
					$attrStr = PopupBuilderAdminHelper::createAttrs($inputArgs);
					$fieldHtml .= '<span class="sgpbm-subfield sgpbm-monthfield sgpbm-subfield">';
						$fieldHtml .= Functions::createInputElement($attrStr);
					$fieldHtml .= '</span>';
					if (!$hideSeparator) {
						$fieldHtml .= '<span class="sgpb-separator">/</span>';
					}
				break;
				case 'YYYY':
					$inputArgs['name'] = $field['name'].'[year]';
					$attrStr = PopupBuilderAdminHelper::createAttrs($inputArgs);
					$fieldHtml .= '<span class="subfield yearfield sgpbm-subfield">';
						$fieldHtml .= Functions::createInputElement($attrStr);
					$fieldHtml .= '</span>';
				break;
			}
		}
		$fieldHtml .= '<span class="sgpbm-formats-from"> ( '.$field['options']['date_format'].' )</span>';
		$fieldHtml .= '<div id="phoneValidateMessage" class="'.$field['name'].'-error-message sgpb-validate-message"></div>';
		$fieldHtml .= '</div>';

		return $fieldHtml;
	}

	private function createRadioElements($field)
	{
		$radioButtonOptions = $field['options']['choices'];
		$asteriskString = '';
		$hideField = '';
		$optionStatusClassName = 'sgpb-optional-field';

		if ($field['required']) {
			$asteriskString = '<span class="asterisk sgpb-asterisk">*</span>';
			$optionStatusClassName = 'sgpb-required-field';
		}
		// for hide not required but hidden element
		if (!$field['required'] && !$field['public']) {
			$hideField = 'sgpb-hide-element';
		}
		$multipleChoiceButton = new MultipleChoiceButton($radioButtonOptions, '');
		$checkboxes = '<div class="mc-field-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'">';
		$checkboxes .= '<span class="sgpb-label" for="sgpbm-mce-EMAIL">'.$field['label'].$asteriskString.'</span>';
		$checkboxes .= $multipleChoiceButton;
		$checkboxes .= '</div>';

		return $checkboxes;
	}

	private function createSelectElement($field)
	{
		$selectBoxOptions = $field['options']['choices'];

		if (!isset($field['options']['groupOption'])) {
			$allOptions = array_values($selectBoxOptions);
			$selectBoxOptions = array_combine($allOptions, $allOptions);
		}

		$asteriskString = '';
		$optionStatusClassName = 'sgpb-optional-field';
		if ($field['required']) {
			$asteriskString = '<span class="asterisk sgpb-asterisk">*</span>';
			$optionStatusClassName = 'sgpb-required-field';
		}
		$hideField = '';

		// When does not required but hidden filed
		if (!$field['required'] && !$field['public']) {
			$hideField = 'sg-hide-element';
		}
		$args = array(
			'name' => $field['name'],
			'class' => 'sgpb-selectbox sgpb-mailchimp-input'
		);
		$checkboxes = '<div class="mc-field-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'">';
		$checkboxes .= '<label class="sgpb-label" for="sgpbm-mce-EMAIL">'.$field['label'].$asteriskString.'</label>';
		$checkboxes .=  PopupBuilderAdminHelper::createSelectBox($selectBoxOptions, '', $args);;
		$checkboxes .= '</div>';

		return $checkboxes;
	}

	private function createPhoneField($field)
	{
		$reqStar = '';
		$fieldHtml = '';

		// for hide not required field when show required fields was enabled
		if (!$field['required'] && $this->getShowRequiredFields()) {
			return $fieldHtml;
		}
		$optionStatusClassName = 'sgpb-optional-field';
		$hideField = '';

		$public = $field['public'];

		// When does not required but hidden filed
		if (!$field['required'] && !$public) {
			$hideField = 'sg-hide-element';
		}

		if ($field['required']) {
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}

		// for hide not required but hidden element
		if (!$field['required'] && !$field['public']) {
			$hideField = 'sgpb-hide-element';
		}
		$name = $field['name'];
		$inputArgs = array(
			'type' => 'number',
			'name' => $name,
			'id' => 'sgpbm-mce-'.$field['name'],
			'class' => $field['required'].'sgpb-mailchimp-input',
			'autocomplete' => 'off',
			'data-error-message-class' => $field['name'].'-error-message',
			'value' => ''
		);
		$attrStr = PopupBuilderAdminHelper::createAttrs($inputArgs);

		$fieldHtml = '<div class="mc-field-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'" >';
		$fieldHtml .= '<label class="sgpb-label" for="sgpbm-mce-'.$field['name'].'">'.$field['label'].$reqStar."</label>";
		if (@$field['options']['phone_format'] == 'US') {
			$fieldHtml .= '<div class="phonefield phonefield-us">';
			$fieldHtml .= '( <span class="phonearea"> <input class="phonePart sgpb-input-style sgpb-mailchimp-input" id="mce-'.$name.'-area" name="'.$name.'[area]"  maxlength="3" size="3" data-class-name="phonePart" data-error-message-class="'.$name.'-error-message"></span> )';
			$fieldHtml .= '<span class="phonedetail1"><input class="phonePart sgpb-input-style sgpb-mailchimp-input" type="text" id="mce-'.$name.'-detail1" name="'.$name.'[detail1]" maxlength="3" size="3" ></span>';
			$fieldHtml .= ' - ';
			$fieldHtml .= '<span class="phonedetail2"><input class="phonePart sgpb-input-style sgpb-mailchimp-input" pattern="[0-9]*" id="mce-'.$name.'-detail2" name="'.$name.'[detail2]" maxlength="4" size="4" value="" type="text"></span>';
			$fieldHtml .= '<span class="small-meta nowrap">(###) ###-####</span>';
			$fieldHtml .= '</div>';
		}
		else {
			$fieldHtml .= Functions::createInputElement($attrStr);
		}
		$fieldHtml .= '<div id="simpleValidateMessage" class="'.$field['name'].'-error-message sgpb-validate-message"></div>';
		$fieldHtml .= '</div>';

		return $fieldHtml;
	}

	public function createAddressField($field)
	{
		$reqStar = '';
		$required = '';
		$hideField = '';
		$optionStatusClassName = 'sgpb-optional-field';
		$name = $field['name'];
		$label = $field['label'];
		$req = $field['required'];

		if (!$req && $this->getShowRequiredFields()) {
			return '';
		}

		$public = $field['public'];

		// When does not required but hidden filed
		if (!$req && !$public) {
			$hideField = 'sg-hide-element';
		}

		if ($req) {
			$required = 'required';
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}

		$output = '<div class="mc-address-group sgpb-address-group '.$optionStatusClassName.' '.$hideField.'" >';
		$output .= '<div class="mc-field-group sgpb-field-group ">';
		$output .= '<label class="sgpb-label" for="mce-'.$name.'-addr1">'.$label.' '.$reqStar.'</label>';
		$output .= '<input type="text" maxlength="70" name="'.$name.'[addr1]" id="mce-'.$name.'-addr1" class="'.$required.' sgpb-mailchimp-input sgpb-address-addr1" data-class-name="sgpb-address-addr1" data-error-message-class="address-addr1-error-message">';
		$output .= '<div class="address-addr1-error-message sgpb-validate-message"></div>';
		$output .= '</div>';
		$output .= '<div class="mc-field-group sgpb-field-group ">';
		$output .= '<label class="sgpb-label" for="mce-'.$name.'-addr2">'.__('Address Line 2', SG_POPUP_TEXT_DOMAIN).'</label>';
		$output .= '<input type="text" maxlength="70" name="'.$name.'[addr2]" id="mce-'.$name.'-addr2" class=" sgpb-mailchimp-input">';
		$output .= '</div>';
		$output .= '<div class="mc-field-group sgpb-field-group ">';
		$output .= '<label class="sgpb-label" for="mce-'.$name.'-city">'.__('City', SG_POPUP_TEXT_DOMAIN).$reqStar.'</label>';
		$output .= '<input type="text" maxlength="40" name="'.$name.'[city]" id="mce-'.$name.'-city" class="'.$required.' sgpb-mailchimp-input sgpb-address-city" data-class-name="sgpb-address-city" data-error-message-class="address-city-error-message">';
		$output .= '<div class="address-city-error-message sgpb-validate-message"></div>';
		$output .= '</div>';
		$output .= '<div class="mc-field-group sgpb-field-group ">';
		$output .= '<label class="sgpb-label" for="mce-'.$name.'-state"> '.__('State/Province/Region', SG_POPUP_TEXT_DOMAIN).$reqStar.'</label>';
		$output .= '<input type="text" maxlength="20" name="'.$name.'[state]" id="mce-'.$name.'-state" class="'.$required.' sgpb-mailchimp-input sgpb-address-state" data-class-name="sgpb-address-state" data-error-message-class="address-state-error-message">';
		$output .= '<div class="address-state-error-message sgpb-validate-message"></div>';
		$output .= '</div>';
		$output .= '<div class="mc-field-group sgpb-field-group ">';
		$output .= '<label class="sgpb-label" for="mce-'.$name.'-zip"> '.__('Postal / Zip Code ', SG_POPUP_TEXT_DOMAIN).$reqStar.'</label>';
		$output .= '<input type="text" maxlength="10" name="'.$name.'[zip]" id="mce-'.$name.'-zip" class="'.$required.' sgpb-mailchimp-input sgpb-address-zip" data-class-name="sgpb-address-zip" data-error-message-class="address-zip-error-message">';
		$output .= '<div class="address-zip-error-message sgpb-validate-message"></div>';
		$output .= '</div>';
		$output .= '<div class="mc-field-group sgpb-field-group ">';
		$output .= '<label class="sgpb-label" for="mce-'.$name.'-country"> '.__('Country', SG_POPUP_TEXT_DOMAIN).$reqStar.'</label>';
		$output .= '<select name="'.$name.'[country]" id="mce-'.$name.'-country" class="'.$required.' sgpb-mailchimp-input" aria-required="true">';
		$output .= DefaultOptionsData::getMailchimpCountryOptions();
		$output .= '</select>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	private function createSubmitButton()
	{
		$options = $this->getSavedOptionFields();
		$inputArgs = array(
			'type' => 'submit',
			'id' => 'sgpbm-mce-subscribe',
			'class' => 'sgpb-embedded-subscribe sgpb-submit',
			'autocomplete' => 'off',
			'value' => $options['submitTitle']
		);
		$attrStr = PopupBuilderAdminHelper::createAttrs($inputArgs);

		$button = '<div class="sgpb-indicates-required"><label><span class="asterisk sgpb-asterisk">*</span><span class="sgpb-indicates-required-title">'.$options['asteriskLabel'].'</span></label></div>';
		$button .= '<div class="mc-field-group sg-clear sg-submit-wrapper">';
		$button .= Functions::createInputElement($attrStr);
		$button .= '</div>';

		return $button;
	}

	private function createGdprFields()
	{
		$options = $this->getSavedOptionFields();
		$hideGdprStyle = '';
		$required = 'required';

		if ($options['gdprStatus'] == 'false' || empty($options['gdprStatus']) || !$options['gdprStatus']) {
			if (!is_admin()) {
				return '';
			}
			$hideGdprStyle = ' style="display: none;"';
		}
		$inputArgs = array(
			'type' => 'checkbox',
			'name' => 'sgpbmGdpr',
			'data-error-message-class' => 'gdpr-error-message',
			'required' => $required,
			'id' => 'sgpbm-gdpr',
			'class' => 'sgpb-mailchimp-gdpr',
			'autocomplete' => 'off',
		);

		if (is_admin()) {
			unset($inputArgs['required']);
		}

		$text = $options['gdprConfirmationText'];
		$label = $options['gdprLabel'];
		$attrStr = PopupBuilderAdminHelper::createAttrs($inputArgs);

		$gdprField = '<div class="mc-field-group sgpb-field-group js-gdpr-wrapper sgpb-required-field" '.$hideGdprStyle.'>';
		$gdprField .= '<div class="mc-field-group sg-clear sgpb-gdpr-wrapper">';
		$inputElement = "<input $attrStr>";
		$inputElement = '<div class="sgpb-gdpr-label-wrapper">'.$inputElement.'<label class="js-gdpr-label" for="sgpbm-gdpr">'.$label.'</label><span class="asterisk sgpb-asterisk">*</span><div class="sgpb-gdpr-error-message"></div></div>';
		$text = html_entity_decode($text);
		$gdprConfirmationText = '<div class="sgpb-alert-info sgpb-alert sgpb-gdpr-info js-subs-text-checkbox sgpb-gdpr-text-js">'.$text.'</div>';
		if ($options['gdprConfirmationText'] != '') {
			$inputElement .= $gdprConfirmationText;
		}
		$gdprField .= $inputElement;
		$gdprField .= '</div>';
		$gdprField .= '<div id="simple-validate-message" class="gdpr-error-message sgpb-validate-message"></div>';
		$gdprField .= '</div>';

		return $gdprField;
	}

	private function buildFormFields($fieldsData)
	{
		$fields = '';

		if (empty($fieldsData)) {
			return $fields;
		}

		foreach ($fieldsData as $field) {
			switch ($field['type']) {
				case 'text':
				case 'email':
				case 'zip':
				case 'url':
				case 'number':
					$fields .= $this->createSimpleElement($field);
					break;
				case 'imageurl':
					$field['type'] = 'url';
					$fields .= $this->createSimpleElement($field);
					break;
				case 'date':
				case 'birthday':
					$fields .= $this->createDateElement($field);
					break;
				case 'radio':
				case 'checkboxes':
					$fields .= $this->createRadioElements($field);
					break;
				case 'dropdown':
					$fields .= $this->createSelectElement($field);
					break;
				case 'phone':
					$fields .= $this->createPhoneField($field);
					break;
				case 'address':
					$fields .= $this->createAddressField($field);
					break;
				case 'gdpr':
					$fields .= $this->createGdprFields();
					break;

			}
		}
		$fields .= $this->createSubmitButton();

		return $fields;
	}

	private function buildMailchmpForm($formData)
	{
		$options = $this->getSavedOptionFields();
		$form = '';
		$form .= '<form action="/" method="post" class="sgpbmMailchimpForm">';
			$form .= $this->buildFormFields($formData);
		$form .= '</form>';

		$id = $options['popupId'];
		$formHtml = '<div class="sgpb-mailchimp-'.$id.'">';
			$formHtml .= $form;
		$formHtml .= '</div>';

		return $formHtml;
	}

	public function getFieldsData()
	{
		$params = $this->getSavedOptionFields();
		$listId = $params['listId'];
		$mergeFields = $this->getListMergeFields($listId);
		$mergeFields = $this->addCustomFields($mergeFields);
		// malchimp group fields
		$interestData = $this->getListInterestData($listId);
		$formData = $this->reformatMergeFieldsForBuilder($mergeFields);
		$formData = array_merge($formData, $interestData);

		return $formData;
	}

	public static function addCustomFields($mergeFields = array())
	{
		$mergeFields['merge_fields'][] = array(
			'tag' => 'sgpbmGdpr',
			'type' => 'gdpr',
			'required' => true
		);

		return $mergeFields;
	}

	public function getListFormHtml($params)
	{
		$this->setSavedOptionFields($params);
		$this->setShowRequiredFields($params['showRequiredFields']);
		$formData = $this->getFieldsData();

		return $this->buildMailchmpForm($formData);
	}


	/**
	 * Check Api key status
	 *
	 * @since 1.0.0
	 *
	 * @param sting $apiKey
	 *
	 * @return bool
	 */
	public static function apiKeyStatus($apiKey)
	{
		$dashPosition = strpos($apiKey, '-');
		// when the structure of the User inserted api key is different from the structure of the mailchimp Api key
		if ($dashPosition !== false) {
			$apiUrl = 'https://'.substr($apiKey, $dashPosition + 1).'.api.mailchimp.com/3.0/?apikey='.$apiKey;

			/* cache the request to not call every time */
			$request = get_transient('sgpbm_mailchimp_api_status_request');
			if ($request === false) {
				$request = wp_remote_get($apiUrl);
				set_transient('sgpbm_mailchimp_api_status_request', $request, WEEK_IN_SECONDS);
			}

			if (is_wp_error($request)) {
				return false;
			}

			$body = wp_remote_retrieve_body($request);

			// if the body is empty status will not be connected
			if (empty($body)) {
				return false;
			}
			// if the connection is correct it will return the account information data
			$data = json_decode($body, true);
			if (isset($data) && is_array($data)) {
				if (isset($data['account_id'])) {
					return true;
				}
				return false;
			}
			// when has error for example inserted vl9573bf5f9b9e2a00457ba419c8afa8-us25
			return false;
		}

		return false;
	}

	/**
	 * Check is user connected
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function isConnected()
	{
		$apiKey = get_option('SGPB_MAILCHIMP_API_KEY');

		if (!$apiKey) {
			return false;
		}

		$status = MailchimpApi::apiKeyStatus($apiKey);

		return $status;
	}

	private function isNumberElement($field)
	{
		$isNumber = false;

		if ($field['type'] == 'phone') {
			if ($field['options']['phone_format'] == 'none') {
				$isNumber = true;
			}
		}
		else if ($field['type'] == 'number') {
			$isNumber = true;
		}

		return $isNumber;
	}

	private static function isComplexElement($field)
	{
		$isComplexElement = false;

		if ($field['type'] == 'phone') {
			if ($field['options']['phone_format'] == 'US') {
				$isComplexElement = true;
			}
		}

		if ($field['type'] == 'date' || $field['type'] == 'birthday') {
			$isComplexElement = true;
		}
		if ($field['type'] == 'address') {
			$isComplexElement = true;
		}

		return $isComplexElement;
	}

	private static function createComplexElementValidation($field, $requiredMessage)
	{
		if ($field['type'] == 'phone') {
			if ($field['options']['phone_format'] == 'US') {
				$validateData['ruleData'][] = '"'.$field['name'].'[area]" : {
					"complexFieldsValidation": true,
					"numberCustomCheck": true
				},';
				$validateData['message'][] = '"'.$field['name'].'[area]": {"complexFieldsValidation": "'.$requiredMessage.'", "numberCustomCheck": "Please enter only digits."},';
			}
		}
		if ($field['type'] == 'date' || $field['type'] == 'birthday') {
			$validateData['ruleData'][] = '"'.$field['name'].'[month]": {
					"complexFieldsValidation": true
				},';
			$validateData['message'][] = '"'.$field['name'].'[month]": "'.$requiredMessage.'",';
		}
		if ($field['type'] == 'address') {
			$validateData['ruleData'][] = '"'.$field['name'].'[addr1]" : {
					"complexFieldsValidation": true
				},';
			$validateData['message'][] = '"'.$field['name'].'[addr1]": "'.$requiredMessage.'",';
			$validateData['ruleData'][] = '"'.$field['name'].'[city]" : {
					"complexFieldsValidation": true
				},';
			$validateData['message'][] = '"'.$field['name'].'[city]": "'.$requiredMessage.'",';
			$validateData['ruleData'][] = '"'.$field['name'].'[state]" : {
					"complexFieldsValidation": true
				},';
			$validateData['message'][] = '"'.$field['name'].'[state]": "'.$requiredMessage.'",';
			$validateData['ruleData'][] = '"'.$field['name'].'[zip]" : {
					"complexFieldsValidation": true
				},';
			$validateData['message'][] = '"'.$field['name'].'[zip]": "'.$requiredMessage.'",';
		}

		return $validateData;
	}

	public function getValidateScript($params, $currentPopupId)
	{
		$this->setSavedOptionFields($params);
		$fieldsData = $this->getFieldsData();

		$requiredMessage = $params['requiredMessage'];
		$rules = '"rules": { ';
		$messages = '"messages": { ';
		$validateObj = '{ ';

		foreach ($fieldsData as $field) {
			if (empty($field)) {
				continue;
			}

			if ($field['type'] == 'email') {
				$rules .= '"EMAIL": {
					"required": true,
					"email": true
				},';
				$messages .= '"EMAIL": {"required" :"'.$requiredMessage.'", "email": "'.$params['emailMessage'].'"},';
				continue;
			}

			if (!$field['required']) {
				continue;
			}

			$isNumber = $this->isNumberElement($field);
			if ($isNumber) {
				$rules .= '"'.$field['name'].'": {
					"required": true,
					"number": true
				},';
				$messages .= '"'.$field['name'].'": {"required" :"'.$requiredMessage.'"},';
				continue;
			}

			$isComplexElement = self::isComplexElement($field);

			if ($isComplexElement) {
				$complexElementValidateData = self::createComplexElementValidation($field, $requiredMessage);
				if (!empty($complexElementValidateData['ruleData'])) {
					foreach ($complexElementValidateData['ruleData'] as $ruleData) {
						$rules .= $ruleData;
					}
				}
				if (!empty($complexElementValidateData['message'])) {
					foreach ($complexElementValidateData['message'] as $ruleMessage) {
						$messages .= $ruleMessage;
					}
				}

				continue;
			}

			$rules .= '"'.$field['name'].'": "required"'.',';
			$messages .= '"'.$field['name'].'": "'.$requiredMessage.'",';
		}

		$rules = rtrim($rules, ",");
		$messages = rtrim($messages, ",");

		$rules .= '},';
		$messages .= '}';
		$validateObj .= $rules;
		$validateObj .= $messages;
		$validateObj .= '}';

		return $validateObj;
	}

	public function getMergeFieldsValuesFromListForm($listId, $formData)
	{
		$mergeFields = $this->getListMergeFields($listId);
		$mergeFields = $this->reformatMergeFieldsForBuilder($mergeFields);
		$mergeData = array();

		foreach ($mergeFields as $fieldData) {
			$name = $fieldData['name'];
			if (!isset($formData[$name])) {
				continue;
			}
			$value = $formData[$name];

			switch ($fieldData['type']) {
				case 'date':
				case 'birthday':
					$value = $this->implodeArrayValues($value);
					$mergeData[$name] = $value;
					break;
				case 'phone':
					if (!is_array($value)) {
						$mergeData[$name] = $value;
						break;
					}
					$assocArrayValues = array_values($value);
					$implodeStirng = implode(' - ', $assocArrayValues);
					$mergeData[$name] = $implodeStirng;
					break;
				default:
					$mergeData[$name] = $value;
			}
		}

		return $mergeData;
	}

	/**
	 * Assoc array to string
	 *
	 * @since 1.1.7
	 *
	 * @param assoc array $dataArray
	 *
	 * @return string $implodeStirng
	 *
	 */
	public function implodeArrayValues($dataArray)
	{
		$implodeString = '';

		if (!empty($dataArray['month'])) {
			$implodeString .= $dataArray['month'].'/';
		}
		if (!empty($dataArray['day'])) {
			$implodeString .= $dataArray['day'];
		}
		if (!empty($dataArray['year'])) {
			$implodeString .= '/'.$dataArray['year'];
		}

		return $implodeString;
	}

	public function getInterestFiledsValuesFromListForm($listId, $formData)
	{
		$interestData = $this->getListInterestData($listId);
		$groupData = array();

		foreach ($interestData as $fieldData) {

			if (!isset($fieldData['name'])) {
				continue;
			}
			$name = $fieldData['name'];
			if (!isset($formData[$name])) {
				continue;
			}
			if (!is_array($formData[$name]) && !empty($formData[$name])) {
				$groupData[$formData[$name]] = true;
				continue;
			}

			foreach ($formData[$name] as $currentName) {
				$groupData[$currentName] = true;
			}
		}

		return $groupData;
	}

	public function subscribe($listId, $params)
	{
		$result = MailchimpApi::$mailchimpObj->post('/lists/'.$listId.'/members', $params);

		return $result;
	}
}
