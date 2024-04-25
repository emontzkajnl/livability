<?php
namespace sgpbform;
require_once(SGPB_SUBSCRIPTION_PLUS_FORM_CLASSES_FIELDS.'Field.php');

class PhoneField extends Field
{
	public function __construct()
	{
		$this->setDisplayName('Phone');
		$this->setType('phone');
		$this->updateSettings();
	}

	private function updateSettings()
	{
		$settings = $this->getFieldSettings();
		$settings['placeholder'] = $this->getDisplayName();
		$settings['fieldName'] = $this->getDisplayName();
		$settings['label'] = $this->getDisplayName();
		@$settings['attrs']['class'][] = 'js-subs-text-inputs';
		$this->setFieldSettings($settings);
	}

	public function getFieldRequiredSettings($index)
	{
		$settings = $this->getFieldSettings();
		$uniqueIndex = '';

		if (!empty($index)) {
			$uniqueIndex = '-'.$index;
		}

		// customize field title
		$settings['attrs']['name'] = 'sgpb-phone'.$uniqueIndex;
		@$settings['attrs']['minlength'] = 6;

		return $settings;
	}

	public function getEditSettingsRowsHtml()
	{
		$html = parent::getGeneralEditSettingsRowsHtml('phone');
		$html .= '<div class="sgpb-row-wrapper formItem ">';
		$html .= '<label class="formItem__title">'.__('Field name', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value"><input type="text" data-key="fieldName" class="sgpb-settings-field sgpb-settings-field-name sgpb-width-100" value=""></div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper formItem ">';
		$html .= '<label class="formItem__title">'.__('Placeholder', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value"><input type="text" data-key="placeholder" class="sgpb-settings-field sgpb-settings-placeholder sgpb-width-100" value=""></div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Required', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-onOffSwitch">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox sgpb-settings-field sgpb-settings-required" id="jsFormEnableRequired_phone"  data-key="required" value="">
					<label class="sgpb-onOffSwitch__label" for="jsFormEnableRequired_phone">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>';
		$html .= '</div>';

		return $html;
	}

	public function getFieldHtml()
	{
		$settings = $this->getFieldSettings();
		$form = $this->getFormObj();
		$settings['attrs']['id'] = @$settings['attrs']['name'];
		$attrStr = $form->createAttrStr(@$settings['attrs']);
		$placeHolder = $settings['placeholder'];

		return '<input type="number" '.$attrStr.' placeholder="'.$placeHolder.'" value="">';
	}
}
