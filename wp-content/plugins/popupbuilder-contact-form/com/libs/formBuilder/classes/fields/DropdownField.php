<?php
namespace sgpbcontactform;
require_once(SGPB_CF_FORM_CLASSES_FIELDS.'Field.php');

class DropdownField extends Field
{
	public function __construct()
	{
		$this->setDisplayName('Dropdown');
		$this->setType('dropdown');
		$this->updateSettings();
	}

	public function getDefaultChoices()
	{
		$choices = array(
			0 => __('First choice', SG_POPUP_TEXT_DOMAIN),
			1 => __('Second choice', SG_POPUP_TEXT_DOMAIN),
			2 => __('Third choice', SG_POPUP_TEXT_DOMAIN)
		);

		return $choices;
	}

	private function updateSettings()
	{
		$settings = $this->getFieldSettings();
		$settings['fieldName'] = $this->getDisplayName();
		$settings['choices'] = $this->getDefaultChoices();
		$settings['label'] = $this->getDisplayName();
		$this->setFieldSettings($settings);
	}

	public function getFieldRequiredSettings($index = '')
	{
		$settings = $this->getFieldSettings();
		$uniqueIndex = '';

		if (!empty($index)) {
			$uniqueIndex = '-'.$index;
		}

		// customize field title
		$settings['attrs']['name'] = 'sgpb-dropdown'.$uniqueIndex;

		return $settings;
	}

	public function getEditSettingsRowsHtml()
	{
		$html = parent::getGeneralEditSettingsRowsHtml('dropdown');
		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Field name', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value"><input type="text" data-key="fieldName" class="sgpb-settings-field sgpb-settings-field-name sgpb-full-width-events sgpb-width-100" value=""></div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Required', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-onOffSwitch">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox sgpb-settings-field sgpb-settings-required" id="jsFormEnableRequired_dropdown"  data-key="required" value="">
					<label class="sgpb-onOffSwitch__label" for="jsFormEnableRequired_dropdown">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper">';
		$html .= '<label>'.__('Choices', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper">';
		$html .= '<div class="sgpb-choices-wrapper"></div>';
		$html .= '</div>';

		return $html;
	}

	public function createSelectBox($data, $selectedValue = '', $attrs = '')
	{
		$attrString = '';
		$selected = '';
		$selectBoxCloseTag = '</select>';

		$form = $this->getFormObj();
		$attrString = $form->createAttrStr($attrs);

		$selectBox = '<select '.$attrString.'>';

		if (empty($data)) {
			$selectBox .= $selectBoxCloseTag;
			return $selectBox;
		}
		foreach ($data as $value => $label) {
			if (is_null($label) || empty($value)) {
				continue;
			}
			// When is multiSelect
			if (is_array($selectedValue)) {
				$isSelected = in_array($value, $selectedValue);
				if ($isSelected) {
					$selected = 'selected';
				}
			}
			else if ($selectedValue == $value) {
				$selected = 'selected';
			}
			else if (is_array($value) && in_array($selectedValue, $value)) {
				$selected = 'selected';
			}

			if (is_array($label)) {
				$selectBox .= '<optgroup label="'.$value.'">';
				foreach ($label as $key => $optionLabel) {
					$selected = '';
					if (is_array($selectedValue)) {
						$isSelected = in_array($key, $selectedValue);
						if ($isSelected) {
							$selected = 'selected';
						}
					}
					else if ($selectedValue == $key) {
						$selected = 'selected';
					}
					else if (is_array($key) && in_array($selectedValue, $key)) {
						$selected = 'selected';
					}

					$selectBox .= '<option value="'.$key.'" '.$selected.'>'.$optionLabel.'</option>';
				}
				$selectBox .= '</optgroup>';
			}
			else {
				$selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
			}

			$selected = '';
		}

		$selectBox .= $selectBoxCloseTag;

		return $selectBox;
	}

	public function getFieldHtml()
	{
		$settings = $this->getFieldSettings();
		$values = @$settings['choices'];

		if (!empty($values) && is_array($values)) {
			$values = array_combine($values, $values);
		}

		return $this->createSelectBox($values, '', @$settings['attrs']);
	}
}
