<?php
namespace sgpbform;
require_once(SGPB_SUBSCRIPTION_PLUS_FORM_CLASSES_FIELDS.'Field.php');

class PlaintextField extends Field
{
    public function __construct()
    {
        $this->setDisplayName('Plain Text');
        $this->setType('plaintext');
        $this->setLabelPosition('right');
        $this->updateSettings();
    }

    private function updateSettings()
    {
        $settings = $this->getFieldSettings();
        $displayName = $this->getDisplayName();

        $settings['fieldName'] = $displayName;
        $settings['required'] = false;
        $settings['plainText'] = __('Your text here', SG_POPUP_TEXT_DOMAIN);
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
        $settings['attrs']['name'] = 'sgpb-plaintext'.$uniqueIndex;
        // it's by default will be enable for this field
        $settings['fieldName'] = $this->getDisplayName();

        return $settings;
    }

    public function getEditSettingsRowsHtml()
    {
        $html = '<div class="sgpb-row-wrapper formItem ">';
        $html .= '<label class="formItem__title">'.__('Field name', SG_POPUP_TEXT_DOMAIN).'</label>';
        $html .= '<div class="sgpb-field-value"><input type="text" data-key="fieldName" class="sgpb-settings-field sgpb-settings-field-name sgpb-width-100" value=""></div>';
        $html .= '</div>';

        $html .= '<div class="sgpb-row-wrapper formItem ">';
        $html .= '<label class="formItem__title">'.__('Plain text', SG_POPUP_TEXT_DOMAIN).'</label>';
        $html .= '<div class="sgpb-field-value"><textarea data-key="plainText" class="sgpb-settings-field sgpb-settings-plainText sgpb-width-100">'._e('Your text here ...', SG_POPUP_TEXT_DOMAIN).'</textarea></div>';
        $html .= '</div>';

        return $html;
    }

    public function getFieldHtml()
    {
        return '';
    }

    public function getAfterFieldHtml()
    {
        $settings = $this->getFieldSettings();
        $infoDivInlineStyles = '';

        $formObj = $this->getFormObj();
        if (!empty($formObj)) {
            $popup = $formObj->getPopupObj();
            if (is_object($popup)) {
                $fieldsDesign = $popup->getOptionValue('sgpb-subscription-fields-design-json');
                $fieldSettings = json_decode($fieldsDesign, true);
                $inputWidth = @$fieldSettings['inputStyles']['width'];

                // for the backward compatibility
                if (empty($inputWidth)) {
                    $inputWidth =  $popup->getOptionValue('sgpb-subs-text-width');
                }
                $infoDivInlineStyles .= 'width: '.$inputWidth.';max-width: 100%;';
            }
        }

        $plainTextDescription = '<div class="sgpb-subs-text-checkbox-plaintext" style="'.$infoDivInlineStyles.'">'.@$settings['plainText'].'</div>';

        return $plainTextDescription;
    }
}
