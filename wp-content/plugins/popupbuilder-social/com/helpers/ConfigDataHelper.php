<?php
namespace sgpbsocial;

class ConfigDataHelper
{
	public static function defaultData()
	{
		$data = array();

		$data['socialShareOptions'] = array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'sgpb-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'formItem__title sgpb-choice-option-wrapper'
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
						'name' => 'sgpb-social-share-url-type',
						'class' => 'sgpb-share-url-type',
						'data-attr-href' => '',
						'value' => 'activeUrl'
					),
					'label' => array(
						'name' => __('Use active URL', SG_POPUP_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'sgpb-social-share-url-type',
						'class' => 'sgpb-share-url-type',
						'data-attr-href' => 'sgpb-social-share-url-wrapper',
						'value' => 'shareUrl'
					),
					'label' => array(
						'name' => __('Share URL', SG_POPUP_TEXT_DOMAIN).':'
					)
				)
			)
		);

		$jsSocialsUlr =  SGPB_SOCIAL_CSS_URL.'jssocial/';
		$data['socialShareTheme'] = array(
			array(
				'value' => 'flat',
				'label_class' => 'sgpb-display-flex sgpb-flex-direction-column-reverse sgpb-align-center sgpb-text-capitalize',
				'data-attributes' => array(
					'class' => 'js-social-share-theme sgpb-popup-theme-flat',
					'data-popup-theme-number' => 1,
					'data-jssocial-url' => $jsSocialsUlr
				)
			),
			array(
				'value' => 'classic',
				'label_class' => 'sgpb-display-flex sgpb-flex-direction-column-reverse sgpb-align-center sgpb-text-capitalize',
				'data-attributes' => array(
					'class' => 'js-social-share-theme sgpb-popup-theme-classic',
					'data-popup-theme-number' => 2,
					'data-jssocial-url' => $jsSocialsUlr
				)
			),
			array(
				'value' => 'minima',
				'label_class' => 'sgpb-display-flex sgpb-flex-direction-column-reverse sgpb-align-center sgpb-text-capitalize',
				'data-attributes' => array(
					'class' => 'js-social-share-theme sgpb-popup-theme-minima',
					'data-popup-theme-number' => 3,
					'data-jssocial-url' => $jsSocialsUlr
				)
			),
			array(
				'value' => 'plain',
				'label_class' => 'sgpb-display-flex sgpb-flex-direction-column-reverse sgpb-align-center sgpb-text-capitalize',
				'data-attributes' => array(
					'class' => 'js-social-share-theme sgpb-popup-theme-plain',
					'data-popup-theme-number' => 4,
					'data-jssocial-url' => $jsSocialsUlr
				)
			),
		);

		$data['socialThemeSizes'] = array(
			'8' => '8',
			'10' => '10',
			'12' => '12',
			'14' => '14',
			'16' => '16',
			'18' => '18',
			'20' => '20',
			'24' => '24'
		);

		$data['socialThemeShereCount'] = array(
			'true' => __('True', SG_POPUP_TEXT_DOMAIN),
			'false' => __('False', SG_POPUP_TEXT_DOMAIN),
			'inside' => __('Inside', SG_POPUP_TEXT_DOMAIN)
		);

		return $data;
	}
}
