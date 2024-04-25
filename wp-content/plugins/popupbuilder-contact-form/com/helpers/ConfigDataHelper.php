<?php
namespace sgpbcontactform;

class ConfigDataHelper
{
	public static function defaultData()
	{
		$data = array();

		$data['contactFormSuccessBehavior'] = array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-6 sgpb-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'subFormItem__title sgpb-margin-right-10'
				),
				'groupWrapperAttr' => array(
					'class' => 'subFormItem sgpb-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-contact-success-behavior',
						'class' => 'contact-success-message',
						'data-attr-href' => 'contact-show-success-message',
						'value' => 'showMessage'
					),
					'label' => array(
						'name' => __('Success message', SG_POPUP_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-contact-success-behavior',
						'class' => 'contact-redirect-to-URL ok-ggoel',
						'data-attr-href' => 'contact-redirect-to-URL',
						'value' => 'redirectToURL'
					),
					'label' => array(
						'name' => __('Redirect to URL', SG_POPUP_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-contact-success-behavior',
						'class' => 'contact-success-open-popup',
						'data-attr-href' => 'contact-open-popup',
						'value' => 'openPopup'
					),
					'label' => array(
						'name' => __('Open popup', SG_POPUP_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-contact-success-behavior',
						'class' => 'contact-hide-popup',
						'value' => 'hidePopup'
					),
					'label' => array(
						'name' => __('Hide popup', SG_POPUP_TEXT_DOMAIN).':'
					)
				)
			)
		);
		$data['messageResize'] = array(
			'both' => __('Both', SG_POPUP_TEXT_DOMAIN),
			'horizontal' => __('Horizontal', SG_POPUP_TEXT_DOMAIN),
			'vertical' => __('Vertical', SG_POPUP_TEXT_DOMAIN),
			'none' => __('None', SG_POPUP_TEXT_DOMAIN),
			'inherit' => __('Inherit', SG_POPUP_TEXT_DOMAIN)
		);
		return $data;
	}
}
