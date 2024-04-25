<?php
namespace sgpbform;
use sgpbsubscriptionplus\SubscriptionPlusAdminHelper;

if (class_exists('form')) {
	return false;
}

abstract class Form
{
	private $fieldTypes = array(
		'text',
		'textarea',
		'radiobutton',
		'phone',
		'advancedphone',
		'number',
		'lastname',
		'firstname',
		'email',
		'dropdown',
		'multiselect',
		'gdpr',
		'checkbox',
		'plaintext'
	);
	private $id = 0;
	private $currentFields = array();
	private $fieldTypesObj = array();
	private $currentFieldsObjs = array();
	private $popupObj;
	private $stylesConfig;

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setPopupObj($popupObj)
	{
		$this->popupObj = $popupObj;
	}

	public function getPopupObj()
	{
		return $this->popupObj;
	}

	public function setStylesConfig($stylesConfig)
	{
		$this->stylesConfig = $stylesConfig;
	}

	public function getStylesConfig()
	{
		return $this->stylesConfig;
	}

	public function setSavedObj($savedObj)
	{
		$savedReorderObj = array();

		if (!empty($savedObj)) {
			$submitObj = null;

			foreach($savedObj as $key => $obj) {
				if (empty($obj)) {
					continue;
				}

				$savedReorderObj[] = $obj;
			}
		}

		$this->savedObj = $savedReorderObj;
	}

	public function getSavedObj()
	{
		return $this->savedObj;
	}

	// input types
	public function addInputType($fieldType)
	{
		$this->fieldTypes[] = $fieldType;
	}

	public function deleteInputType($fieldType)
	{
		$types = $this->fieldTypes;
		unset($types[$fieldType]);

		$this->$types = $types;
	}

	protected function getAllFieldTypes()
	{
		return $this->fieldTypes;
	}

	// current field
	public function addCurrentField($field)
	{
		$this->currentFields[] = $field;
	}

	public function addCurrentFields($fields)
	{
		$this->currentFields = $fields;
	}

	public function getCurrentFields()
	{
		return $this->currentFields;
	}

	public function setCurrentFieldsObjs()
	{
		$fields = $this->getCurrentFields();
		$obj = $this->createFieldsObjFromType($fields);

		$this->currentFieldsObjs = $obj;
	}

	public function getCurrentFieldsObjs()
	{
		return $this->currentFieldsObjs;
	}

	private function setAllFieldObjsFromTypes()
	{
		$types = $this->fieldTypes;
		$objs = $this->createFieldsObjFromType($types);

		$this->fieldTypesObj = $objs;
	}

	public function getAllFieldObjsFromTypes()
	{
		return $this->fieldTypesObj;
	}

	public function __toString()
	{
		$form = $this->renderForm();

		return $form;
	}

	public function renderForm()
	{
		$savedObj = $this->getSavedObj();
		$fieldsHtml = '';

		if (empty($savedObj)) {
			return $fieldsHtml;
		}

		$fieldsHtml = $this->getFormFieldsFromSavedObj($savedObj);

		return $fieldsHtml;
	}

	private function getFormFieldsFromSavedObj($savedObj)
	{
		$fieldsHtml = '';

		foreach($savedObj as $key => $obj) {
			$type = $obj['type'];
			$fieldObj = $this->createFieldObjByType($type);
			$settings = (array)$obj;
			$fieldObj->setFieldSettings($settings);
			$settings = $this->getFieldSettings($key, $fieldObj);
			$fieldObj->setFieldSettings($settings);
			$fieldHtml = $fieldObj->renderHtml();

			$fieldsHtml .= $fieldHtml;
		}
		$fieldsHtml .= '<input type="hidden" name="sgpb-subs-hidden-checker" value="">';

		return $fieldsHtml;
	}

	public function getLivePreview($popupTypeObj)
	{
		$this->setPopupObj($popupTypeObj);

		$savedObj = $this->getSavedObj();
		$styles = $this->getStylesConfig();
		$styles = json_encode($styles);

		$formFields = $this->getFormFieldsFromSavedObj($savedObj);
		$formFields .= $this->setPopupObj($popupTypeObj);
		$formFields .= $this->getFormStyles($styles, 0, false);

		return $formFields;
	}

	private function createFieldsObjFromType($types)
	{
		$objs = array();

		if (empty($types)) {
			return $objs;
		}

		foreach ($types as $index => $type) {
			if (empty($type)) {
				continue;
			}
			$fieldObj = $this->createFieldObjByType($type);

			if (empty($fieldObj)) {
				continue;
			}
			$objs[] = $fieldObj;
		}

		return $objs;
	}

	public function createFieldObjByType($type)
	{
		$typeClassName = $this->getFieldClassNameByType($type);
		$fieldPath = SGPB_SUBSCRIPTION_PLUS_FORM_CLASSES_FIELDS.'/'.$typeClassName.'.php';

		if (!file_exists($fieldPath)) {
			return false;
		}

		require_once($fieldPath);
		$typeClassName = __NAMESPACE__.'\\'.$typeClassName;
		$fieldObj = new $typeClassName();
		$fieldObj->setFormObj($this);
		$fieldObj->setType($type);

		return $fieldObj;
	}

	private function getFieldClassNameByType($type)
	{
		$type = ucfirst($type);
		$typeClassName = $type.'Field';

		return $typeClassName;
	}

	// html templates create functions
	protected function getFieldShortIconTemplate()
	{
		$this->setAllFieldObjsFromTypes();

		$objs = $this->getAllFieldObjsFromTypes();
		$template = '';

		if (empty($objs)) {
			return $template;
		}
		$template .= '<div class="sgpb-list-group sgpb-list-group-flush sgpb-padding-x-20">';

		foreach ($objs as $obj) {
			$type = $obj->getType();
			$mustNotShow = $this->mustNotHaveDelete();

			if (in_array($type, $mustNotShow)) {
				continue;
			}
			$settings = $this->getFieldSettings('', $obj);
			$template .= $obj->getFieldAdminIcon($settings);
		}
		$template .= '</div>';
		$html = '<div class="sgpb-margin-y-20"><div class="sgpb-display-flex sgpb-flex-direction-column">';
		$html .= $template;
		$html .= '</div></div>';

		return $html;
	}

	protected function getCurrentFieldsAdminTemplate()
	{
		$this->setCurrentFieldsObjs();
		$objs = $this->getCurrentFieldsObjs();
		$savedObj = $this->getSavedObj();
		$template = '';

		if (empty($objs)) {
			return $template;
		}

		foreach ($objs as $key => $obj) {
			$savedData = @$savedObj[$key];
			$template .= $obj->getFieldAdminFieldIcon($key, $savedData);
		}

		return $template;
	}

	public function getCurrentFieldsAdminHtmlTemplate()
	{
		// shortcodes  sgTypeShortcode, sgIndexShortcode, sgDisplayNameShortcode will be release via Js
		$iconHtml = '<div class="sgpb-form-fields-main-wrapper" data-order-index="sgIndexShortcode"><div class="sgpb-edit-settings-area-wrapper-sgIndexShortcode sgpb-field-icon-wrapper sgpb-bbounce" data-type="sgTypeShortcode" data-index="sgIndexShortcode">';
		$iconHtml .= '<span class="sgpb-field-display-name sgpb-edit-settings">sgDisplayNameShortcode</span>';
		$iconHtml .= '<div class="sgpb-field-config">';
		$iconHtml .= '<div class="sgpb-edit-settings sgpb-margin-left-10"><i class="sgpb-icons  icons_blue" data-id="">C</i></div>';
		$iconHtml .= '<div class="sgpb-delete-field sgpb-margin-left-10"><i class="sgpb-icons  icons_pink" data-id="">I</i></div>';

		$iconHtml .= '</div>';
		$iconHtml .= '</div>';

		$iconHtml .= '<div class="sgpb-edit-settings-area-wrapper-sgIndexShortcode">';
		$iconHtml .= '<div class="sgpb-edit-settings-area"></div>';
		$iconHtml .= '</div>';
		$iconHtml .= '</div>';

		return $iconHtml;
	}

	private function getFieldsSettings()
	{
		$objs = $this->getCurrentFieldsObjs();

		$allSettings = array();

		if (empty($objs)) {
			return $allSettings;
		}

 		foreach ($objs as $key => $obj) {
			$settings = $this->getFieldSettings($key, $obj);
			$allSettings[] = $settings;
		}
		$this->allSettings = $allSettings;

		return $allSettings;
	}

	public function getFieldsSettingsObj()
	{
		$allSettings = $this->getSavedObj();
		if (empty($allSettings)) {
			$allSettings = $this->getFieldsSettings();
		}

		return $allSettings;
	}

	public function getAllFieldsEditSettingsTemplate()
	{
		$this->setAllFieldObjsFromTypes();
		$objs = $this->getAllFieldObjsFromTypes();
		$editSettingsHtml = '';

		if (empty($objs)) {
			return $editSettingsHtml;
		}
		$submitObj = $this->createFieldObjByType('submit');

		if (!empty($submitObj)) {
			$objs[] = $submitObj;
		}

		foreach ($objs as $key => $obj) {
			$editSettingsHtml .= $obj->getEditSettingsHtml($key);
		}

		return $editSettingsHtml;
	}

	public function getFieldSettings($key, $obj)
	{
		$settings = $obj->getFieldRequiredSettings($key);
		$settings = $this->filterSettings($settings, $obj);

		return $settings;
	}

	public function createAttrStr($attrs)
	{
		$attrStr = '';

		if (empty($attrs)) {
			return false;
		}

		foreach ($attrs as $attrKey => $attrValue) {
			$attrValueStr = $attrValue;
			if (is_array($attrValue) && !empty($attrValue)) {
				$attrValueStr = '';
				foreach ($attrValue as $value) {
					$attrValueStr .= $value.' ';
				}
				$attrValueStr = rtrim($attrValueStr, ' ');
			}
			$attrStr .= $attrKey.'="'.$attrValueStr.'" ';
		}

		return $attrStr;
	}

	public function getCSSSafeSize($dimension)
	{
		if (empty($dimension)) {
			return 'inherit';
		}

		$size = (int)$dimension.'px';
		// If user write dimension in px or % we give that dimension to target otherwise the default value will be px
		if (strpos($dimension, '%') || strpos($dimension, 'px')) {
			$size = $dimension;
		}

		return $size;
	}
}
