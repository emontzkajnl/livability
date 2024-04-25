<?php
namespace sgpb;
use sgpbrestriction\AdminHelper as RestrictionAdminHelper;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'/SGPopup.php');

class AgerestrictionPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'), 1, 100);
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendJs', array($this, 'popupFrontJsFilter'), 1, 1);
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		if (!empty($_GET['sgpb_type']) && $_GET['sgpb_type'] != SGPB_POPUP_TYPE_RESTRICTION) {
			return $defaultOptions;
		}

		$changingOptions = array(
			'sgpb-min-width' => array('name' => 'sgpb-min-width', 'type' => 'text', 'defaultValue' => 200)
		);

		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-button-width', 'type' => 'number', 'defaultValue' => 100);
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-button-height', 'type' => 'number', 'defaultValue' => 40);
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-button-padding', 'type' => 'number', 'defaultValue' => 7);
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-button-font-size', 'type' => 'number', 'defaultValue' => 20);
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-button-width', 'type' => 'number', 'defaultValue' => 100);
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-button-height', 'type' => 'number', 'defaultValue' => 40);
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-button-padding', 'type' => 'number', 'defaultValue' => 7);
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-button-font-size', 'type' => 'number', 'defaultValue' => 20);

		$defaultOptions[] = array('name' => 'sgpb-restriction-cookie-level', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-btn-radius', 'type' => 'number', 'defaultValue' => 5);
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-btn-radius', 'type' => 'number', 'defaultValue' => 5);
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-btn-radius-type', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-btn-radius-type', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-btn-bg-color', 'type' => 'text', 'defaultValue' => '#105fba');
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-btn-bg-color', 'type' => 'text', 'defaultValue' => '#fcfcfc');
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-btn-text-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-btn-border-color', 'type' => 'text', 'defaultValue' => '#1056b2');
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-btn-border-width', 'type' => 'number', 'defaultValue' => 2);
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-btn-border-color', 'type' => 'text', 'defaultValue' => '#000000');
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-btn-border-width', 'type' => 'number', 'defaultValue' => 2);
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-btn-text-color', 'type' => 'text', 'defaultValue' => '#000000');
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-expiration-time', 'type' => 'number', 'defaultValue' => 365);
		$defaultOptions[] = array('name' => 'sgpb-restriction-save-choice', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-restriction-yes-btn', 'type' => 'text', 'defaultValue' => __('Yes', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-restriction-to-bottom', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-restriction-no-btn', 'type' => 'text', 'defaultValue' => __('No', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-custom-yes-btn-fixed-size', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-custom-no-btn-fixed-size', 'type' => 'checkbox', 'defaultValue' => 'on');

		return $defaultOptions;
	}

	public function popupFrontJsFilter($jsFiles)
	{
		$isActive = $this->getOptionValue('sgpb-is-active', true);

		if (!$isActive) {
			return $jsFiles;
		}
		$popupId = $this->getId();
		$restrictionParams = array();
		$restrictionParams['popupId']        = $popupId;
		$restrictionParams['cookieLevel']    = $this->getOptionValue('sgpb-restriction-cookie-level');
		$restrictionParams['expirationTime'] = $this->getOptionValue('sgpb-restriction-yes-expiration-time');
		$restrictionParams['restrictionUrl'] = $this->getOptionValue('sgpb-restriction-no-url');
		$restrictionParams['saveChoice'] = $this->getOptionValue('sgpb-restriction-save-choice');
		$restrictionParams['ageRestrictionType'] = SGPB_POPUP_TYPE_RESTRICTION;

		$jsFiles['jsFiles'][] = array('folderUrl' => SGPB_RESTRICTION_JS_URL, 'filename' => 'AgeRestriction.js');
		$jsFiles['localizeData'][] = array(
			'handle' => 'AgeRestriction.js',
			'name' => 'SgpbAgeRestrictionParams'.$popupId,
			'data' => $restrictionParams
		);

		$jsFiles['localizeData'][] = array(
			'handle' => 'PopupBuilder.js',
			'name' => 'SgpbAgeRestrictionParams'.$popupId,
			'data' => array(
				'popupTypeAgeRestriction' => SGPB_POPUP_TYPE_RESTRICTION
			)
		);

		return $jsFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeMainView()
	{
		return array(
			'filePath' => SGPB_RESTRICTION_VIEWS_PATH.'restriction.php',
			'metaboxTitle' => 'Yes/No Settings',
			'short_description' => 'Create a popup for asking your website visitors any type of question'
		);
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$selector = '';
		$popupId = (int)$this->getId();
		$popupContent = $this->getContent();
		$popupSizingMode = $this->getOptionValue('sgpb-popup-dimension-mode');
		$pushToBottom = $this->getOptionValue('sgpb-restriction-to-bottom');

		$yesButton = $this->getOptionValue('sgpb-restriction-yes-btn');
		$noButton = $this->getOptionValue('sgpb-restriction-no-btn');

		$popupContent .= '<div class="sgpb-restriction-buttons-wrapper-'.$popupId.'">';
		$popupContent .= '<button id="sgpb-yes-button" class="sgpb-restriction-button" type="button">'.$yesButton.'</button>';
		$popupContent .= '<button id="sgpb-no-button"  class="sgpb-restriction-button" type="button">'.$noButton.'</button>';
		$popupContent .= '</div>';

		$popupStyles = $this->renderStyles();
		$popupContent .= $popupStyles;

		if ($pushToBottom) {
			$selector = '.sgpb-restriction-buttons-wrapper-'.$popupId;
			if ($popupSizingMode == 'customMode') {
				$popupContent .= RestrictionAdminHelper::setPushToBottom($selector);
			}
		}
		return $popupContent;
	}

	public function getRemoveOptions()
	{
		// Where 1 mean this options must not show for this popup type
		$removeOptions = array(
			'sgpb-overlay-click' => 1,
			'sgpb-esc-key' => 1,
			'sgpb-disable-page-scrolling' => 1,
			'sgpb-enable-close-button' => 1,
			'sgpb-show-popup-same-user' => 1
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
			'sgpb-disable-page-scrolling' => ''
		);
	}

	public function renderStyles()
	{
		$popupId                  = $this->getId();
		$popupOptions = $this->getPopupOptionsById($popupId);
		$popupMinWidth = 120;
		$yesButtonWidth = @$popupOptions['sgpb-restriction-yes-button-width'];
		$yesButtonHeight = @$popupOptions['sgpb-restriction-yes-button-height'];
		$yesButtonPadding = $popupOptions['sgpb-restriction-yes-button-padding'];
		$yesButtonFontSize = $popupOptions['sgpb-restriction-yes-button-font-size'];
		$noButtonWidth = @$popupOptions['sgpb-restriction-no-button-width'];
		$noButtonHeight = @$popupOptions['sgpb-restriction-no-button-height'];
		$noButtonPadding = $popupOptions['sgpb-restriction-no-button-padding'];
		$noButtonFontSize = $popupOptions['sgpb-restriction-no-button-font-size'];
		$yesButtonFixedSize = @$popupOptions['sgpb-custom-yes-btn-fixed-size'];
		$noButtonFixedSize = @$popupOptions['sgpb-custom-no-btn-fixed-size'];

		$yesButtonBackgroundColor = $popupOptions['sgpb-restriction-yes-btn-bg-color'];
		$yesButtonBorderColor     = $popupOptions['sgpb-restriction-yes-btn-border-color'];
		$yesButtonBorderWidth     = $popupOptions['sgpb-restriction-yes-btn-border-width'];
		$noButtonBackgroundColor  = $popupOptions['sgpb-restriction-no-btn-bg-color'];
		$noButtonBorderColor      = $popupOptions['sgpb-restriction-no-btn-border-color'];
		$noButtonBorderWidth      = $popupOptions['sgpb-restriction-no-btn-border-width'];
		$yesButtonTextColor       = $popupOptions['sgpb-restriction-yes-btn-text-color'];
		$noButtonTextColor        = $popupOptions['sgpb-restriction-no-btn-text-color'];
		$yesButtonRadius          = $popupOptions['sgpb-restriction-yes-btn-radius'];
		$noButtonRadius           = $popupOptions['sgpb-restriction-no-btn-radius'];
		$yesButtonRadiusType      = $popupOptions['sgpb-restriction-yes-btn-radius-type'];
		$noButtonRadiusType       = $popupOptions['sgpb-restriction-no-btn-radius-type'];

		// add some count to popup min width (80), to allow buttons be side to side
		if (!empty($yesButtonWidth) && !empty($noButtonWidth) && !empty($yesButtonPadding) && !empty($noButtonPadding)) {
			$popupMinWidth = $yesButtonWidth+$noButtonWidth+$yesButtonPadding+$noButtonPadding;
			$popupMinWidth += 80;
			$popupMinWidth = $popupMinWidth;
		}

		if ($yesButtonBorderWidth) {
			$yesButtonBorderWidth .= 'px solid';
		}
		if ($noButtonBorderWidth) {
			$noButtonBorderWidth .= 'px solid';
		}

		if (empty($yesButtonWidth)) {
			$yesButtonWidth = 30;
		}
		$yesButtonWidth .= 'px !important';

		if (empty($yesButtonHeight)) {
			$yesButtonHeight = 20;
		}
		$yesButtonHeight .= 'px !important';

		if (!isset($yesButtonFixedSize)) {
			$yesButtonWidth = 'auto !important';
			$yesButtonHeight = 'auto !important';
		}

		if (empty($yesButtonPadding)) {
			$yesButtonPadding = 12;
		}
		$yesButtonPadding .= 'px !important';

		if (empty($yesButtonFontSize)) {
			$yesButtonFontSize = 15;
		}
		$yesButtonFontSize .= 'px !important';

		if (empty($noButtonWidth)) {
			$noButtonWidth = 30;
		}
		$noButtonWidth .= 'px !important';

		if (empty($noButtonHeight)) {
			$noButtonHeight = 20;
		}
		$noButtonHeight .= 'px !important';

		if (!isset($noButtonFixedSize)) {
			$noButtonWidth = 'auto !important';
			$noButtonHeight = 'auto !important';
		}

		if (empty($noButtonPadding)) {
			$noButtonPadding = 12;
		}
		$noButtonPadding .= 'px !important';

		if (empty($noButtonFontSize)) {
			$noButtonFontSize = 15;
		}
		$noButtonFontSize .= 'px !important';

		$styles = "<style>
			.sgpb-restriction-buttons-wrapper-$popupId #sgpb-yes-button {
				width: $yesButtonWidth;
				height: $yesButtonHeight;
				min-height: $yesButtonHeight;
				padding: $yesButtonPadding;
				font-size: $yesButtonFontSize;
				background-color: $yesButtonBackgroundColor;
				color: $yesButtonTextColor;
				border: $yesButtonBorderWidth;
				border-radius: $yesButtonRadius$yesButtonRadiusType;
				border-color: $yesButtonBorderColor !important;
				line-height: normal !important;
				vertical-align: top;
			}
			.sgpb-restriction-buttons-wrapper-$popupId #sgpb-no-button {
				width: $noButtonWidth;
				height: $noButtonHeight;
				min-height: $noButtonHeight;
				padding: $noButtonPadding;
				font-size: $noButtonFontSize;
				background-color: $noButtonBackgroundColor;
				color: $noButtonTextColor;
				border: $noButtonBorderWidth;
				border-radius: $noButtonRadius$noButtonRadiusType;
				border-color: $noButtonBorderColor !important;
				line-height: normal !important;
				vertical-align: top;
			}
			#sgpb-yes-button:hover,
			#sgpb-no-button:hover {
				opacity: 0.8 !important;
			}
			.sgpb-restriction-buttons-wrapper-$popupId #sgpb-yes-button,
			.sgpb-restriction-buttons-wrapper-$popupId #sgpb-no-button {
				min-height: 20px !important;
				font-weight: bold;
			}
			.sgpb-restriction-buttons-wrapper-$popupId #sgpb-no-button,
			.sgpb-restriction-buttons-wrapper-$popupId #sgpb-yes-button {
				margin-left: 5px;
				margin-bottom: 5px;
			}
			.sgpb-restriction-buttons-wrapper-$popupId {
				text-align: center;
				min-width: $popupMinWidth".'px'.";
			}
			#sgcboxLoadedContent .sgpb-restriction-buttons-wrapper-$popupId button {
				padding: 12px !important;
				line-height: 0.4;
				margin-bottom: 4px;
			}
			.sgpb-restriction-button {
				cursor: pointer !important;
			}
			.sgpb-hide-overflow {
				overflow: hidden;
			}
		</style>";

		return $styles;
	}
}
