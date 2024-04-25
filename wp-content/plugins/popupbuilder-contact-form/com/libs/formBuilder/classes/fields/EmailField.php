<?php
namespace sgpbcontactform;
require_once(SGPB_CF_FORM_CLASSES_FIELDS.'Field.php');

class EmailField extends Field
{
	public function __construct()
	{
		$this->setDisplayName('Email');
		$this->setType('email');
		$this->updateSettings();
	}

	private function updateSettings()
	{
		$settings = $this->getFieldSettings();
		$displayName = $this->getDisplayName();

		$settings['placeholder'] = $displayName;
		$settings['fieldName'] = $displayName;
		$settings['label'] = $displayName;
		@$settings['attrs']['class'][] = 'js-contact-text-inputs';
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
		$settings['attrs']['name'] = 'sgpb-email'.$uniqueIndex;
		$settings['fieldName'] = $this->getDisplayName();

		return $settings;
	}

	public function getEditSettingsRowsHtml()
	{
		$html = parent::getGeneralEditSettingsRowsHtml('email');
		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Field name', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value"><input type="text" data-key="fieldName" class="sgpb-settings-field sgpb-settings-field-name sgpb-full-width-events sgpb-width-100" value=""></div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="col-md-6">'.__('Placeholder', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value col-md-6"><input type="text" data-key="placeholder" class="sgpb-settings-field sgpb-settings-placeholder sgpb-full-width-events sgpb-width-100" value=""></div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Required', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-onOffSwitch">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox sgpb-settings-field sgpb-settings-required" id="jsFormEnableRequired_email"  data-key="required" value="">
					<label class="sgpb-onOffSwitch__label" for="jsFormEnableRequired_email">
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

		return '<input type="email" '.$attrStr.' placeholder="'.$placeHolder.'" value="">';
	}
}
