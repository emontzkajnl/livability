<?php
namespace sgpbcontactform;
require_once(SGPB_CF_FORM_CLASSES_FIELDS.'Field.php');

class GdprField extends Field
{
    public function __construct()
    {
        $this->setDisplayName('GDPR');
        $this->setType('gdpr');
        $this->setLabelPosition('right');
        $this->updateSettings();
    }

    private function updateSettings()
    {
        $settings = $this->getFieldSettings();
        $displayName = $this->getDisplayName();

        $settings['fieldName'] = $displayName;
        $settings['required'] = true;
        $settings['label'] = __('Accept Terms', SG_POPUP_TEXT_DOMAIN);
        $settings['enableLabel'] = 'on';
        $settings['gdprText'] = __('Admin will use the information you provide on this form to be in touch with you and to provide updates and marketing.', SG_POPUP_TEXT_DOMAIN);
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
        $settings['attrs']['name'] = 'sgpb-gdpr'.$uniqueIndex;
        // it's by default will be enable for this field
        $settings['enableLabel'] = 'on';
        $settings['fieldName'] = $this->getDisplayName();

        return $settings;
    }

    public function getEditSettingsRowsHtml()
    {
        $html = '<div class="sgpb-row-wrapper formItem">';
        $html .= '<label class="formItem__title">Label</label>';
        $html .= '<div class="sgpb-field-value"><input type="text" data-key="label" class="sgpb-settings-field sgpb-settings-label sgpb-full-width-events sgpb-width-100" value=""></div>';
        $html .= '</div>';

        $html .= '<div class="sgpb-row-wrapper formItem">';
        $html .= '<label class="formItem__title">'.__('Field name', SG_POPUP_TEXT_DOMAIN).'</label>';
        $html .= '<div class="sgpb-field-value"><input type="text" data-key="fieldName" class="sgpb-settings-field sgpb-settings-field-name sgpb-full-width-events sgpb-width-100" value=""></div>';
        $html .= '</div>';

        $html .= '<div class="sgpb-row-wrapper formItem">';
        $html .= '<label class="formItem__title">'.__('Confirmation text', SG_POPUP_TEXT_DOMAIN).'</label>';
        $html .= '<div class="sgpb-field-value"><textarea data-key="gdprText" class="sgpb-settings-field sgpb-settings-gdprText sgpb-width-100"></textarea></div>';
        $html .= '</div>';

        return $html;
    }

    public function getFieldHtml()
    {
        $settings = $this->getFieldSettings();
        $form = $this->getFormObj();
        $settings['attrs']['id'] = @$settings['attrs']['name'];
        $attrStr = $form->createAttrStr(@$settings['attrs']);

        return '<input type="checkbox" '.$attrStr.' value="">';
    }

    public function getAfterFieldHtml()
    {
        $settings = $this->getFieldSettings();
        $infoDivInlineStyles = '';

        $formObj = $this->getFormObj();
        if (!empty($formObj)) {
            $popup = $formObj->getPopupObj();
            if (is_object($popup)) {
                $fieldsDesign = $popup->getOptionValue('sgpb-contact-fields-design-json');
                $fieldSettings = json_decode($fieldsDesign, true);
                $inputWidth = @$fieldSettings['inputStyles']['width'];

                // for the backward compatibility
                if (empty($inputWidth)) {
                    $inputWidth =  $popup->getOptionValue('sgpb-contact-inputs-width');
                }
                $infoDivInlineStyles .= 'width: '.$inputWidth.';max-width: 100%;';
            }
        }

        $gdprDescription = '<div class="sgpb-contact-text-checkbox-gdpr" style="'.$infoDivInlineStyles.'">'.@$settings['gdprText'].'</div>';

        return $gdprDescription;
    }
}
