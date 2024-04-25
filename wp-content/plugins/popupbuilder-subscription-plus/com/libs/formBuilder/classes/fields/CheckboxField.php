<?php
namespace sgpbform;
require_once(SGPB_SUBSCRIPTION_PLUS_FORM_CLASSES_FIELDS.'Field.php');
require_once(SGPB_FORM_HELPERS.'MultipleChoiceButton.php');

class CheckboxField extends Field
{
	public function __construct()
	{
		$this->setDisplayName('Checkbox');
		$this->setType('checkbox');
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
		$settings['attrs']['name'] = 'sgpb-checkbox'.$uniqueIndex.'[]';

		return $settings;
	}

	public function getEditSettingsRowsHtml()
	{
		$html = parent::getGeneralEditSettingsRowsHtml('checkbox');
		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Field name', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value"><input type="text" data-key="fieldName" class="sgpb-settings-field sgpb-settings-field-name sgpb-full-width-events sgpb-width-100" value=""></div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Required', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-onOffSwitch">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox sgpb-settings-field sgpb-settings-required" id="jsFormEnableRequired_checkbox"  data-key="required" value="">
					<label class="sgpb-onOffSwitch__label" for="jsFormEnableRequired_checkbox">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper sgpb-bg-black__opacity-02 sgpb-padding-20">';
		$html .= '<label>'.__('Choices', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-choices-wrapper sgpb-margin-y-10"></div>';
		$html .= '</div>';

		return $html;
	}

	private function getFieldsArray()
	{
		$settings = $this->getFieldSettings();
		$attrs = @$settings['attrs'];
		$values = @$settings['choices'];
		$settings = array();

		if (empty($values)) {
			return $values;
		}

		foreach($values as $index => $value) {
			if (empty($value)) {
				continue;
			}

			$currentSettingsAttrs = $attrs;
			$currentSettingsAttrs['type'] = 'checkbox';
			$currentSettingsAttrs['value'] = $value;
			$currentSettingsAttrs['id'] = @$attrs['name'].$value.$index;
			$settings[] = array(
				'attr' => $currentSettingsAttrs,
				'label' => array('name' => $value)
			);
		}

		return $settings;
	}

	public function getFieldHtml()
	{
		$fields = $this->getFieldsArray();

		$radioButtonSettings =  array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'sgpb-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'sgpb-choice-option-wrapper sgpb-sub-option-label'
				),
				'groupWrapperAttr' => array(
					'class' => 'sgpb-choice-wrapper'
				)
			),
			'buttonPosition' => 'left',
			'nextNewLine' => true,
			'fields' => $fields
		);

		return new MultipleChoiceButton($radioButtonSettings);
	}
}
