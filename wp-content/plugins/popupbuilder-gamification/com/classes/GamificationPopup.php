<?php
namespace sgpb;
use sgpb\Functions;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class GamificationPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'), 1, 1);
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendJsFiles', array($this, 'popupFrontJsFilter'), 1, 1);
	}


	public function filterPopupDefaultOptions($defaultOptions)
	{
		if (!empty($_GET['sgpb_type']) && $_GET['sgpb_type'] != SGPB_POPUP_TYPE_GAMIFICATION) {
			return $defaultOptions;
		}

		$changingOptions = array(
			'sgpb-show-background' => array('name' => 'sgpb-show-background', 'type' => 'checkbox', 'defaultValue' => 'on'),
			'sgpb-content-opacity' => array('name' => 'sgpb-content-opacity', 'type' => 'text', 'defaultValue' => '1'),
			'sgpb-background-image-mode' => array('name' => 'sgpb-background-image-mode', 'type' => 'text', 'defaultValue' => 'cover'),
			'sgpb-popup-themes' => array('name' => 'sgpb-popup-themes', 'type' => 'text', 'defaultValue' => 'sgpb-theme-6'),
			'sgpb-button-position-right' => array('name' => 'sgpb-button-position-right', 'type' => 'text', 'defaultValue' => '-15'),
			'sgpb-button-image-width' => array('name' => 'sgpb-button-image-width', 'type' => 'text', 'defaultValue' => '37'),
			'sgpb-button-image-height' => array('name' => 'sgpb-button-image-height', 'type' => 'text', 'defaultValue' => '37'),
			'sgpb-button-image' => array('name' => 'sgpb-button-image', 'type' => 'text', 'defaultValue' => SGPB_GAMIFICATION_IMG_URL.SGPB_GAMIFICATION_DEFAULT_CLOSE_BUTTON_NAME),
			'sgpb-background-image' => array('name' => 'sgpb-background-image', 'type' => 'text', 'defaultValue' => SGPB_GAMIFICATION_IMG_URL.SGPB_GAMIFICATION_DEFAULT_BG_NAME)
		);

		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		$defaultOptions[] = array('name' => 'sgpb-button-position-top', 'type' => 'text', 'defaultValue' => '-11');
		$defaultOptions[] = array('name' => 'sgpb-gamification-win-chance', 'type' => 'text', 'defaultValue' => '50');
		$defaultOptions[] = array('name' => 'sgpb-hide-form', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-gamification-only-btn-text', 'type' => 'text', 'defaultValue' => __('I\'m lucky today'));

		return $defaultOptions;
	}

	public function popupFrontJsFilter($jsFiles)
	{
		return $jsFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeMainView()
	{
		return array(
			'filePath' => SGPB_GAMIFICATION_VIEWS_PATH.'gamification.php',
			'metaboxTitle' => 'Gamification Settings',
			'short_description' => 'Create attractive gamification offers, customize the winning chance'
		);
	}

	private function getFormFields()
	{
		$submitStyles = array();
		$inputStyles = array();

		if ($this->getOptionValue('sgpb-gamification-btn-width')) {
			$submitWidth = $this->getOptionValue('sgpb-gamification-btn-width');
			$submitStyles['width'] = AdminHelper::getCSSSafeSize($submitWidth).' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-btn-height')) {
			$submitHeight = $this->getOptionValue('sgpb-gamification-btn-height');
			$submitStyles['height'] = AdminHelper::getCSSSafeSize($submitHeight).' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-btn-bg-color')) {
			$submitStyles['background-color'] = $this->getOptionValue('sgpb-gamification-btn-bg-color').' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-btn-text-color')) {
			$submitStyles['color'] = $this->getOptionValue('sgpb-gamification-btn-text-color').' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-btn-border-radius')) {
			$borderRadius = AdminHelper::getCSSSafeSize($this->getOptionValue('sgpb-gamification-btn-border-radius'));
			$submitStyles['border-radius'] = $borderRadius.' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-btn-border-width')) {
			$submitStyles['border-width'] = $this->getOptionValue('sgpb-gamification-btn-border-width').' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-btn-border-color')) {
			$submitStyles['border-color'] = $this->getOptionValue('sgpb-gamification-btn-border-color').' !important';
		}
		$submitStyles['text-transform'] = 'none !important';
		$submitStyles['border-style'] = 'solid';
		$submitStyles['margin-bottom'] = '0px !important';
		$submitStyles['margin-top'] = '19px !important';
		$submitStyles['font-size'] = '25px !important';
		$submitStyles['padding'] = '0px !important';
		$submitStyles['font-family'] = 'Segoe UI !important';


		$inputStyles['autocomplete'] = 'off';
		$inputStyles['margin-top'] = '0 !important';
		$inputStyles['margin-bottom'] = '0 !important';
		$inputStyles['border-style'] = 'solid !important';
		$inputStyles['text-indent'] = '13px';
		$inputStyles['border-color'] = '#f2f3e8';

		if ($this->getOptionValue('sgpb-gamification-text-width'))  {
			$inputWidth = $this->getOptionValue('sgpb-gamification-text-width');
			$inputStyles['width'] = AdminHelper::getCSSSafeSize($inputWidth).' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-text-height')) {
			$inputHeight = $this->getOptionValue('sgpb-gamification-text-height');
			$inputStyles['height'] = AdminHelper::getCSSSafeSize($inputHeight).' !important';
		}
		if ($this->getOptionValue('sgpb-gamification-text-border-width')) {
			$inputBorderWidth = $this->getOptionValue('sgpb-gamification-text-border-width');
			$inputStyles['border-width'] = AdminHelper::getCSSSafeSize($inputBorderWidth);
		}
		if ($this->getOptionValue('sgpb-gamification-text-border-radius')) {
			$inputStyles['border-radius'] = $this->getOptionValue('sgpb-gamification-text-border-radius');
		}
		if ($this->getOptionValue('sgpb-gamification-text-border-color')) {
			$inputStyles['border-color'] = $this->getOptionValue('sgpb-gamification-text-border-color');
		}
		if ($this->getOptionValue('sgpb-gamification-text-bg-color')) {
			$inputStyles['background-color'] = $this->getOptionValue('sgpb-gamification-text-bg-color');
		}
		if ($this->getOptionValue('sgpb-gamification-text-color')) {
			$inputStyles['color'] = $this->getOptionValue('sgpb-gamification-text-color');
		}

		$submitTitle = $this->getOptionValue('sgpb-gamification-btn-title');
		$progressTitle = $this->getOptionValue('sgpb-gamification-btn-progress-title');

		$formData['email'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'email',
				'data-required' => true,
				'placeholder' => $this->getOptionValue('sgpb-gamification-text-placeholder'),
				'name' => 'sgpb-subs-email',
				'spellcheck' => 'off',
				'autocapitalize' => 'off',
				'autocorrect' => 'off',
				'autocomplete' => 'off',
				'class' => 'js-gamification-text-inputs js-gamification-email-input',
				'data-error-message-class' => 'sgpb-gamification-email-error-message'
			),
			'style' => $inputStyles,
			'errorMessageBoxStyles' => $inputStyles['width']
		);

		$onlyButton = $this->getOptionValue('sgpb-hide-form');
		// if only game mode is selected, we don't need email field
		if ($onlyButton) {
			unset($formData['email']);
		}

		$formData['submit'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'submit',
				'name' => 'sgpb-gamification-submit',
				'value' => $submitTitle,
				'data-title' => $submitTitle,
				'data-progress-title' => $progressTitle,
				'class' => 'js-gamification-submit-btn'
			),
			'style' => $submitStyles
		);

		return $formData;
	}

	private function getFormMessages()
	{
		$errorMessage = $this->getOptionValue('sgpb-gamification-error-message');
		ob_start();
		?>
		<div class="gamification-form-messages sgpb-alert sgpb-alert-danger sg-hide-element">
			<p><?php echo $errorMessage; ?></p>
		</div>
		<?php
		$messages = ob_get_contents();
		ob_end_clean();

		return $messages;
	}

	private function renderForm()
	{
		$formFields = $this->getFormFields();
		$id = $this->getId();
		$validationMessage = $this->getOptionValue('sgpb-gamification-validation-message');
		$emailMessage = $this->getOptionValue('sgpb-gamification-invalid-message');
		$gdprTerm = $this->getOptionValue('sgpb-gamification-gdpr-terms');
		$onlyButton = $this->getOptionValue('sgpb-hide-form');
		if ($onlyButton) {
			$gdprTerm = '<input type="hidden" class="sgpb-hide-form">';
		}

		$form = '<form class="sgpb-gamification-form sgpb-gamification-form-'.esc_attr($id).'" data-id="'.esc_attr($id).'" data-required-message="'.$validationMessage.'" data-email-message="'.$emailMessage.'">';
		$form .= $this->getFormMessages();
		$form .= Functions::renderForm($formFields);
		$form .= '<div class="sgpb-gamification-gdpr-text">'.$gdprTerm.'</div>';
		$form .= '<div class="sgpb-email-validate-message"></div>';
		$form .= '</form>';

		return $form;
	}

	private function getContents()
	{
		$contents = '<div class="sgpb-gamification-texts sgpb-gamification-start-text">'.$this->getOptionValue('sgpb-gamification-start-text').'</div>';
		$contents .= '<div class="sgpb-gamification-texts sgpb-gamification-play-text sg-hide">'.$this->getOptionValue('sgpb-gamification-play-text').'</div>';
		$contents .= '<div class="sgpb-gamification-texts sgpb-gamification-win-text sg-hide">'.$this->getOptionValue('sgpb-gamification-win-text').'</div>';
		$contents .= '<div class="sgpb-gamification-texts sgpb-gamification-lose-text sg-hide">'.$this->getOptionValue('sgpb-gamification-lose-text').'</div>';

		return $contents;
	}

	// get gift content (images)
	private function getGifts()
	{
		$popupId = $this->getId();
		$savedGift = $this->getOptionValue('sgpb-gamification-gift-image');
		$placeholderColor = $this->getOptionValue('sgpb-gamification-text-placeholder-color');
		$gifts = '<div class="sgpb-gifts sgpb-gifts-'.$popupId.'">';
		$gifts .= '<img class="sgpb-gift" width="72px" height="73px" src="'.$savedGift.'">';
		$gifts .= '<img class="sgpb-gift" width="72px" height="73px" src="'.$savedGift.'">';
		$gifts .= '<img class="sgpb-gift" width="72px" height="73px" src="'.$savedGift.'">';
		$gifts .= '<img class="sgpb-gift" width="72px" height="73px" src="'.$savedGift.'">';
		$gifts .= '<img class="sgpb-gift" width="72px" height="73px" src="'.$savedGift.'">';
		$gifts .= '</div>';
		$gifts .= '<style type="text/css">.sgpb-popup-builder-content-'.$popupId.' .sgpb-gamification-content-wrapper {padding:0;}</style>';
		$gifts .= '<style type="text/css">';
		$gifts .= '.sgpb-popup-builder-content-'.$popupId.' .js-gamification-text-inputs::-webkit-input-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;}';
		$gifts .= '.sgpb-popup-builder-content-'.$popupId.' .js-gamification-text-inputs::-moz-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;}';
		$gifts .= '.sgpb-popup-builder-content-'.$popupId.' .js-gamification-text-inputs:-ms-input-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;} /* ie */';
		$gifts .= '.sgpb-popup-builder-content-'.$popupId.' .js-gamification-text-inputs:-moz-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;}';
		$gifts .= '</style>';

		return $gifts;
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$form = $this->renderForm();

		$popupContent = '<div class="sgpb-gamification-content-wrapper">';
		$popupContent .= '<div class="sgpb-gifts-content-wrapper">';
		$popupContent .= $this->getContents();
		$popupContent .= $form;
		$popupContent .= '</div>';
		$popupContent .= $this->getGifts();
		$popupContent .= '</div>';

		return $popupContent;
	}

	/**
	 * It returns what the current post supports (for example: title, editor, etc...)
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function getPopupTypeSupports()
	{
		return array('title');
	}

	public function getExtraRenderOptions()
	{
		$options = $this->getOptions();

		return $options;
	}
}
