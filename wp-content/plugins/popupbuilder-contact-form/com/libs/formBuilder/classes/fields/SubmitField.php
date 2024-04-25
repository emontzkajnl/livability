<?php
namespace sgpbcontactform;
require_once(SGPB_CF_FORM_CLASSES_FIELDS.'Field.php');

class SubmitField extends Field
{
	public function __construct()
	{
		$this->setDisplayName('Submit');
		$this->setType('submit');
		$this->updateSettings();
	}

	private function updateSettings()
	{
		$settings = $this->getFieldSettings();
		$settings['title'] = 'Submit';
		$settings['fieldName'] = 'Submit';
		$settings['buttonLabel'] = __('Contact', SG_POPUP_TEXT_DOMAIN);;
		$settings['dataProgress'] = __('Please wait...', SG_POPUP_TEXT_DOMAIN);
		@$settings['attrs']['class'][] = 'js-contact-submit-btn';
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
		$settings['attrs']['name'] = 'sgpb-submit'.$uniqueIndex;
		$settings['fieldName'] = $this->getDisplayName();

		return $settings;
	}

	public function getEditSettingsRowsHtml()
	{
		$html = '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Button label', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value"><input type="text" data-key="buttonLabel" class="sgpb-settings-field sgpb-settings-placeholder sgpb-full-width-events sgpb-width-100 js-contact-btn-title" value=""></div>';
		$html .= '</div>';

		$html .= '<div class="sgpb-row-wrapper formItem">';
		$html .= '<label class="formItem__title">'.__('Title (in progress)', SG_POPUP_TEXT_DOMAIN).'</label>';
		$html .= '<div class="sgpb-field-value"><input type="text" data-key="dataProgress" class="sgpb-settings-field sgpb-settings-placeholder sgpb-full-width-events sgpb-width-100" value=""></div>';
		$html .= '</div>';

		return $html;
	}

	public function getFieldHtml()
	{
		$settings = $this->getFieldSettings();
		$form = $this->getFormObj();
		unset($settings['attrs']['name']);
		$attrStr = $form->createAttrStr(@$settings['attrs']);
		$title = @$settings['buttonLabel'];
		$dataProgressTitle = @$settings['dataProgress'];

		return '<input type="submit" '.$attrStr.' data-progress-title="'.esc_attr($dataProgressTitle).'" data-title="'.esc_attr($title).'" value="'.$title.'">';
	}
}
