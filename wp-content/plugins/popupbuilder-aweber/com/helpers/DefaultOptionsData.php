<?php
namespace sgpbaw;

class DefaultOptionsData
{
	public static function getSuccessBehavior()
	{
		$behavior = array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'sgpb-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'subFormItem__title sgpb-margin-right-10 sgpb-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'formItem sgpb-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-aweber-success-behavior',
						'class' => 'aweber-success-message ',
						'data-attr-href' => 'aweber-show-success-message',
						'value' => 'showMessage',
						'id' => 'aweber-success-message'
					),
					'label' => array(
						'name' => __('Success message', SG_POPUP_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-aweber-success-behavior',
						'class' => 'aweber-redirect-to-URL',
						'data-attr-href' => 'aweber-redirect-to-URL',
						'value' => 'redirectToURL',
						'id' => 'sgpb-aweber-redirect-to-url'
					),
					'label' => array(
						'name' => __('Redirect to URL', SG_POPUP_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-aweber-success-behavior',
						'class' => 'aweber-success-open-popup',
						'data-attr-href' => 'aweber-open-popup',
						'value' => 'openPopup',
						'id' => 'sgpb-aweber-open-popup'
					),
					'label' => array(
						'name' => __('Open popup', SG_POPUP_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-aweber-success-behavior',
						'class' => 'aweber-hide-popup',
						'value' => 'hidePopup',
						'id' => 'sgpb-aweber-hide-popup'
					),
					'label' => array(
						'name' => __('Hide popup', SG_POPUP_TEXT_DOMAIN).':'
					)
				)
			)
		);

		return $behavior;
	}
}
