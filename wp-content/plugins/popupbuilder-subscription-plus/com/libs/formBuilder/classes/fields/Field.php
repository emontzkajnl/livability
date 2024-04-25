<?php
namespace sgpbform;

if (class_exists('Field')) {
	return false;
}

abstract class Field
{
	private $displayName = '';
	private $type = 'text';
	private $formObj;
	private $labelPosition = 'left';

	private $settings = array(
		'required' => '',
		'enableLabel' => '',
		'label' => 'Label',
		'fieldName' => 'Title',
		'attrs' => array(
			'name' => 'sampleName',
			'class' => array('sgpb-simple-inputs'),
			'autocomplete' => 'off'
		)
	);

	abstract function getFieldRequiredSettings($index);

	public function setDisplayName($displayName)
	{
		$this->displayName = __($displayName, SG_POPUP_TEXT_DOMAIN);
	}

	public function getDisplayName()
	{
		return $this->displayName;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getFieldSettings()
	{
		return $this->settings;
	}

	public function setFieldSettings($settings)
	{
		$this->settings = $settings;
	}

	public function setFormObj($formObj)
	{
		$this->formObj = $formObj;
	}

	public function getFormObj()
	{
		return $this->formObj;
	}

	public function setLabelPosition($labelPosition)
	{
		$this->labelPosition = $labelPosition;
	}

	public function getLabelPosition()
	{
		return $this->labelPosition;
	}

	public function getFieldAdminIcon($settings)
	{
		$type = $this->getType();
		$displayName = $this->getDisplayName();
		$settings = json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);

		$iconHtml = '<button type="button" class="sgpb-list-group-item sgpb-fields-buttons" data-type="'.$type.'" data-settings=\''.$settings.'\'>'.$displayName.'</button>';

		return $iconHtml;
	}

	public function getFieldAdminFieldIcon($currentIndex, $savedData = array())
	{
		$type = $this->getType();
		$displayName = $this->getDisplayName();
		$formObj = $this->getFormObj();
		$disallowToDeleteClass = '';

		if (!empty($savedData)) {
			$displayName = $savedData['fieldName'];
			$type = $savedData['type'];
		}
		$isAllowField = $formObj->allowDeleteField($type);

		$iconHtml = '<div class="sgpb-form-fields-main-wrapper" data-order-index="'.$currentIndex.'"><div onclick="javascript:void(0)" class="sgpb-field-icon-wrapper sgpb-field-icon-wrapper-'.$type.'" data-type="'.$type.'" data-index="'.$currentIndex.'">';
		$iconHtml .= '<div class="sgpb-field-display-name sgpb-edit-settings">'.$displayName.'</div>';
		$iconHtml .= '<div class="sgpb-field-config">';
		if (!$isAllowField) {
			$disallowToDeleteClass = 'sgpb-disallow-to-edit';
		}
		$iconHtml .= '<div class="sgpb-margin-left-10 sgpb-edit-settings"><i class="sgpb-icons sgpb-cursor-pointer icons_blue" data-id="">C</i></div>';
		$iconHtml .= '<div class="sgpb-margin-left-10 sgpb-delete-field '.$disallowToDeleteClass.'"><i class="sgpb-icons  icons_pink" data-id="">I</i></div>';
		$iconHtml .= '</div>';
		$iconHtml .= '</div>';
		$iconHtml .= '<div class="sgpb-edit-settings-area-wrapper-'.$currentIndex.' sgpb-edit-settings-area-wrapper">';
		$iconHtml .= '<div class="sgpb-edit-settings-area"></div>';
		$iconHtml .= '</div>';
		$iconHtml .= '</div>';

		return $iconHtml;
	}

	public function getEditSettingsHtml()
	{
		if (!method_exists($this, 'getEditSettingsRowsHtml')) {
			return false;
		}
		$type = $this->getType();

		$html = '<div class="sgpb-settings-'.$type.' sgpb-field-settings-wrapper" id="sgpb-settings-'.$type.'">';
		$html .= $this->getEditSettingsRowsHtml();
		$html .= '</div>';

		return $html;
	}

	public function getGeneralEditSettingsRowsHtml($field = '')
	{
		$html = '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Enable Label', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-onOffSwitch">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-form-enable-settings sgpb-settings-field sgpb-settings-enable-label" id="jsFormEnableLabel'.$field.'"  data-key="enableLabel" value="">
					<label class="sgpb-onOffSwitch__label" for="jsFormEnableLabel'.$field.'">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-full-width sgpb-bg-black__opacity-02 sgpb-padding-20"><div class="sgpb-display-flex sgpb-align-item-center">';
		$html .= '<label class="formItem__title">'.__('Label', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value sgpb-width-100"><input type="text" data-key="label" class="sgpb-settings-field sgpb-input sgpb-settings-label sgpb-full-width-events sgpb-width-100" value=""></div>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	public function renderHtml()
	{
		if (!method_exists($this, 'getFieldHtml')) {
			return '';
		}
		$type = $this->getType();
		$settings = $this->getFieldSettings();
		$name = $settings['attrs']['name'];

		$field = '<div class="sgpb-field-wrapper sgpb-field-'.$type.'-wrapper">'.$this->getFieldHtml().'</div>';
		$currentField = '<div class="sgpb-field-wrapper sgpb-field-'.$type.'-wrapper sgpb-each-field-main-wrapper">';
		$currentField .= apply_filters('sgpbBeforeFieldHtml', '');
		$label = '';

		if (!empty($settings['enableLabel'])) {
			$label .= '<div class="sgpb-label-wrapper sgpb-label-'.$type.'-wrapper"><label for="'.$name.'">'.$settings['label'].'</label></div>';
		}
		$labelPosition = $this->getLabelPosition();

		if ($labelPosition == 'left') {
			$field = $label.$field;
		}
		else {
			$field = $field.$label;
		}
		if (strpos($name, '[]')) {
			$name = rtrim($name, '[]');		
		}

		$currentField .= $field;
		$currentField .= '<div class="sgpb-subscription-plus-form-error-placement sgpb-form-field-'.$name.'-error"></div>';
		$currentField .= apply_filters('sgpbAfterFieldHtml', '');
		$currentField .= $this->getAfterFieldHtml();
		$currentField .= '</div>';

		if ($type == 'submit') {
			$currentField = '</div><div class="sgpb-button-container">'.$currentField.'</div>';
		}

		return $currentField;
	}

	public function getAfterFieldHtml()
	{
		return '';
	}
}
