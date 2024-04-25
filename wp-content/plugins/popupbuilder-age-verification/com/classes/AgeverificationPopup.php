<?php
namespace sgpb;
use sgpb\AdminHelper as AdminHelper;
use sgpbageverification\AdminHelper as AgeverificationAdminHelper;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'/SGPopup.php');

class AgeverificationPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'), 1, 100);
		add_filter('sgpbAgeverificationJsFilter', array($this, 'popupJsFilter'), 1, 1);
	}

	public function popupJsFilter($jsFiles)
	{
		$popupId = $this->getId();
		$restrictionParams = $this->getRequiredOptions();
		$jsFiles['jsFiles'][] = array('folderUrl' => SGPB_AGE_VERIFICATION_JS_URL, 'filename' => 'AgeVerification.js', 'ver' => SG_VERSION_POPUP_AGE_VERIFICATION, 'dep' => array('PopupBuilder.js'));
		$jsFiles['localizeData'][] = array(
			'handle' => 'AgeVerification.js',
			'name' => 'SgpbAgeVerificationParams'.$popupId,
			'data' => $restrictionParams
		);

		return $jsFiles;
	}

	private function getRequiredOptions()
	{
		$dataOptions = array(
			'exitURL' => $this->getOptionValue('sgpb-age-verification-exit'),
			'lockoutCount' => $this->getOptionValue('sgpb-age-verification-lockout-count'),
			'requiredAge' => $this->getOptionValue('sgpb-age-verification-required-age'),
			'saveChoice' => $this->getOptionValue('sgpb-age-verification-save-choice'),
			'expirationTime' => $this->getOptionValue('sgpb-age-verification-expiration-time'),
			'cookieLevel' => $this->getOptionValue('sgpb-age-verification-cookie-level')
		);

		return $dataOptions;
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		if (!empty($_GET['sgpb_type']) && $_GET['sgpb_type'] != SGPB_POPUP_TYPE_AGE_VERIFICATION) {
			return $defaultOptions;
		}

		$defaultOptions[] = array('name' => 'sgpb-age-verification-lockout-count', 'type' => 'number', 'defaultValue' => 3);
		$defaultOptions[] = array('name' => 'sgpb-age-verification-required-age', 'type' => 'number', 'defaultValue' => 18);
		$defaultOptions[] = array('name' => 'sgpb-age-verification-save-choice', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-expiration-time', 'type' => 'number', 'defaultValue' => 365);
		$defaultOptions[] = array('name' => 'sgpb-age-verification-cookie-level', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-width', 'type' => 'text', 'defaultValue' => '300px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-height', 'type' => 'text', 'defaultValue' => '40px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-border-radius', 'type' => 'text', 'defaultValue' => '4px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-border-width', 'type' => 'text', 'defaultValue' => '1px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-border-color', 'type' => 'text', 'defaultValue' => '#f4b802');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-title', 'type' => 'text', 'defaultValue' => __('Verify age', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-bg-color', 'type' => 'text', 'defaultValue' => '#f5be17');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-btn-text-color', 'type' => 'text', 'defaultValue' => '#585858');

		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-width', 'type' => 'text', 'defaultValue' => '300px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-height', 'type' => 'text', 'defaultValue' => '40px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-border-radius', 'type' => 'text', 'defaultValue' => '4px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-border-width', 'type' => 'text', 'defaultValue' => '1px');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-border-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-title', 'type' => 'text', 'defaultValue' => __('I\'m NOT 18', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-bg-color', 'type' => 'text', 'defaultValue' => '#585858');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-restriction-btn-text-color', 'type' => 'text', 'defaultValue' => '#fcfcfc');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-error-message', 'type' => 'text', 'defaultValue' => __('Please, fill in the fields to submit the form', SG_POPUP_TEXT_DOMAIN).'.');
		$defaultOptions[] = array('name' => 'sgpb-age-verification-required-message', 'type' => 'text', 'defaultValue' => __('Sorry, your specified age is less than the required', SG_POPUP_TEXT_DOMAIN).'.');

		$changingOptions = array(
			'sgpb-background-color' => array('name' => 'sgpb-background-color', 'type' => 'text', 'defaultValue' => '#585858'),
			'sgpb-content-opacity' => array('name' => 'sgpb-content-opacity', 'type' => 'text', 'defaultValue' => 1),
			'sgpb-overlay-opacity' => array('name' => 'sgpb-overlay-opacity', 'type' => 'text', 'defaultValue' => 1),
			'sgpb-show-background' => array('name' => 'sgpb-show-background', 'type' => 'checkbox', 'defaultValue' => 1),
			'sgpb-popup-themes' => array('name' => 'sgpb-popup-themes', 'type' => 'text', 'defaultValue' => 'sgpb-theme-6'),
			'sgpb-content-padding' => array('name' => 'sgpb-content-padding', 'type' => 'text', 'defaultValue' => 12)
		);

		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		return $defaultOptions;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeOptionsView()
	{
		$optionsViewData = array(
			'filePath' => SGPB_AGE_VERIFICATION_VIEWS_PATH.'ageVerification.php',
			'metaboxTitle' => 'Age Restriction Settings',
			'short_description' => 'Create a popup for asking your website visitors to verify their age'
		);

		return $optionsViewData;
	}

	private function createStylesObj($key = '')
	{
		$submitStyles = array();

		if ($this->getOptionValue('sgpb-age-verification-'.$key.'btn-width')) {
			$submitWidth = $this->getOptionValue('sgpb-age-verification-'.$key.'btn-width');
			$submitStyles['width'] = AdminHelper::getCSSSafeSize($submitWidth).' !important';
		}
		if ($this->getOptionValue('sgpb-age-verification-'.$key.'btn-height')) {
			$submitHeight = $this->getOptionValue('sgpb-age-verification-'.$key.'btn-height');
			$submitStyles['height'] = AdminHelper::getCSSSafeSize($submitHeight).' !important';
		}
		if ($this->getOptionValue('sgpb-age-verification-'.$key.'btn-bg-color')) {
			$submitStyles['background-color'] = $this->getOptionValue('sgpb-age-verification-'.$key.'btn-bg-color');
		}
		if ($this->getOptionValue('sgpb-age-verification-'.$key.'btn-text-color')) {
			$submitStyles['color'] = $this->getOptionValue('sgpb-age-verification-'.$key.'btn-text-color');
		}
		if ($this->getOptionValue('sgpb-age-verification-'.$key.'btn-border-radius')) {
			$borderRadius = AdminHelper::getCSSSafeSize($this->getOptionValue('sgpb-age-verification-'.$key.'btn-border-radius'));
			$submitStyles['border-radius'] = $borderRadius.' !important';
		}
		if ($this->getOptionValue('sgpb-age-verification-'.$key.'btn-border-width')) {
			$submitStyles['border-width'] = $this->getOptionValue('sgpb-age-verification-'.$key.'btn-border-width');
		}
		if ($this->getOptionValue('sgpb-age-verification-'.$key.'btn-border-color')) {
			$submitStyles['border-color'] = $this->getOptionValue('sgpb-age-verification-'.$key.'btn-border-color');

		}
		$submitStyles['text-transform'] = 'none !important';
		$submitStyles['border-style'] = 'solid';
		$submitStyles['margin-bottom'] = '0px !important';
		$submitStyles['margin-top'] = '19px !important';
		$submitStyles['font-size'] = '25px !important';
		$submitStyles['padding'] = '0px !important';
		$submitStyles['font-family'] = 'Segoe UI !important';

		return $submitStyles;
	}

	private function getFormFields()
	{
		$submitStyles = $this->createStylesObj('');
		$restrictionStyles = $this->createStylesObj('restriction-');

		$submitTitle = $this->getOptionValue('sgpb-age-verification-btn-title');
		$progressTitle = $this->getOptionValue('sgpb-age-verification-btn-progress-title');

		$dataOptions = $this->getRequiredOptions();;
		$dataOptions = json_encode($dataOptions);

		$formData['submit'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'submit',
				'name' => 'sgpb-age-verification-submit',
				'value' => $submitTitle,
				'data-title' => $submitTitle,
				'data-progress-title' => $progressTitle,
				'data-options' => $dataOptions,
				'data-id' => $this->getId(),
				'data-count' => 0,
				'class' => 'js-age-verification-submit-btn'
			),
			'style' => $submitStyles
		);

		$submitTitle = $this->getOptionValue('sgpb-age-verification-restriction-btn-title');
		$progressTitle = $this->getOptionValue('sgpb-age-verification-restriction-btn-progress-title');

		$formData['restriction'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'submit',
				'name' => 'sgpb-age-verification-restriction-submit',
				'value' => $submitTitle,
				'data-title' => $submitTitle,
				'data-progress-title' => $progressTitle,
				'data-options' => $dataOptions,
				'data-id' => $this->getId(),
				'data-count' => 0,
				'class' => 'js-age-verification-restriction-submit-btn'
			),
			'style' => $restrictionStyles
		);

		return $formData;
	}

	public function getRemoveOptions()
	{
		// Where 1 mean this options must not show for this popup type
		$removeOptions = array(
			'sgpb-overlay-click' => 1,
			'sgpb-esc-key' => 1,
			'sgpb-disable-page-scrolling' => 1,
			'sgpb-enable-close-button' => 1,
			'sgpb-show-popup-same-user' => 1,
			'sgpb-disable-popup-closing' => 1,
		);

		$parentOptions = parent::getRemoveOptions();

		return $removeOptions + $parentOptions;
	}

	public function getExtraRenderOptions()
	{
		return array(
			'sgpb-overlay-click'       => '',
			'sgpb-esc-key'             => '',
			'sgpb-enable-close-button' => '',
			'sgpb-disable-page-scrolling' => '',
			'sgpb-disable-popup-closing' => ''
		);
	}

	public function getPopupTypeContent()
	{
		$popupContent = $this->getContent();
		$formData = $this->getFormFields();
		$form = Functions::renderForm($formData);

		$defaultSettings = AgeverificationAdminHelper::defaultSettings();

		$days = AdminHelper::createSelectBox($defaultSettings['days'], '', array('name' => 'sgpb-varification-days', 'class'=>'sgpb-varification-days sgpb-varification-select'));
		$mounts = AdminHelper::createSelectBox($defaultSettings['months'], '', array('name' => 'sgpb-varification-months', 'class'=>'sgpb-varification-months sgpb-varification-select'));
		$years = AdminHelper::createSelectBox($defaultSettings['years'], '', array('name' => 'sgpb-varification-years', 'class'=>'sgpb-varification-years sgpb-varification-select'));
		$errorMessage = $this->getOptionValue('sgpb-age-verification-error-message');
		$requiredAge = $this->getOptionValue('sgpb-age-verification-required-message');
		$yesBtnWidth = (int)$this->getOptionValue('sgpb-age-verification-btn-width');
		$noBtnWidth = (int)$this->getOptionValue('sgpb-age-verification-restriction-btn-width');
		$sumOfBtnWidths = 2*($yesBtnWidth+$noBtnWidth).'px';

		$popupContent .= '<div class="sgpb-age-verification-wrapper">';
		$popupContent .= '<div class="sgpb-age-verification-error-message sgpb-alert sgpb-alert-danger sg-hide-element" style="margin-bottom: 15px;">'.$errorMessage.'</div>';
		$popupContent .= '<div class="sgpb-age-verification-required-age-error sgpb-alert sgpb-alert-danger sg-hide-element" style="margin-bottom: 15px;">'.$requiredAge.'</div>';
		$popupContent .= $days;
		$popupContent .= $mounts;
		$popupContent .= $years;
		$popupContent .= $form;
		$popupContent .= '</div>';
		$popupContent .= "<style>
							.sgpb-age-verification-wrapper {box-sizing: border-box;}
							.sgpb-age-verification-wrapper .sgpb-form-wrapper{display: inline-flex;}
							.sgpb-age-verification-wrapper select {margin-right: 10px; padding: 0 8px;}
							.sgpb-age-verification-wrapper {text-align: center;}
							.sgpb-age-verification-wrapper .sgpb-inputs-wrapper {display: inline-block !important;box-sizing: border-box;}
							.sgpb-age-verification-wrapper .js-submit-wrapper {margin-right:5px;}
							.sgpb-age-verification-wrapper .js-restriction-wrapper {margin-right:2px;}
							@media (max-width: $sumOfBtnWidths) {
								.sgpb-age-verification-wrapper .sgpb-form-wrapper{display: block !important;}
							}
						</style>";

		return $popupContent;
	}
}
