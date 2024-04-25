<?php
namespace sgpb;
use sgpbpush\AdminHelper as PushNotificationAdminHelper;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class PushNotificationPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbAdminJsFiles', array($this, 'adminJsFilter'), 1, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'));
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendJs', array($this, 'popupFrontJsFilter'), 1, 1);
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$popupType = AdminHelper::getCurrentPopupType();
		if ($popupType != SGPB_POPUP_TYPE_PUSH_NOTIFICATION) {
			return $defaultOptions;
		}

		$changingOptions = array(
			'sgpb-disable-page-scrolling' => array('name' => 'sgpb-disable-page-scrolling', 'type' => 'checkbox', 'defaultValue' => 'on'),
			'sgpb-popup-fixed' => array('name' => 'sgpb-popup-fixed', 'type' => 'checkbox', 'defaultValue' => 'on'),
			'sgpb-popup-fixed-position' => array('name' => 'sgpb-popup-fixed-position', 'type' => 'text', 'defaultValue' => 1)
		);

		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		$defaultOptions[] = array('name' => 'sgpb-push-notification-cookie-level', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-yes-btn-radius', 'type' => 'number', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-no-btn-radius', 'type' => 'number', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-yes-btn-radius-type', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-no-btn-radius-type', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-yes-btn-bg-color', 'type' => 'text', 'defaultValue' => '#222222');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-no-btn-bg-color', 'type' => 'text', 'defaultValue' => '#222222');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-yes-btn-text-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-yes-btn-border-color', 'type' => 'text', 'defaultValue' => '#222222');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-yes-btn-border-width', 'type' => 'number', 'defaultValue' => 2);
		$defaultOptions[] = array('name' => 'sgpb-push-notification-no-btn-border-color', 'type' => 'text', 'defaultValue' => '#222222');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-no-btn-border-width', 'type' => 'number', 'defaultValue' => 2);
		$defaultOptions[] = array('name' => 'sgpb-push-notification-no-btn-text-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-disallow-expiration-time', 'type' => 'number', 'defaultValue' => 365);
		$defaultOptions[] = array('name' => 'sgpb-push-notification-save-choice', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-yes-btn', 'type' => 'text', 'defaultValue' => __('Allow', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-push-notification-to-bottom', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-push-notification-no-btn', 'type' => 'text', 'defaultValue' => __('Disallow', SG_POPUP_TEXT_DOMAIN));

		return $defaultOptions;
	}

	public function popupFrontJsFilter($jsFiles)
	{
		$isActive = $this->getOptionValue('sgpb-is-active', true);

		if (!$isActive) {
			return $jsFiles;
		}
		$popupId = $this->getId();
		$pushNotificationParams = array();
		$pushNotificationParams['popupId'] = $popupId;
		$pushNotificationParams['cookieLevel'] = $this->getOptionValue('sgpb-push-notification-cookie-level');
		$pushNotificationParams['expirationTime'] = $this->getOptionValue('sgpb-push-notification-disallow-expiration-time');
		$pushNotificationParams['restrictionUrl'] = $this->getOptionValue('sgpb-push-notification-no-url');
		$pushNotificationParams['saveChoice'] = $this->getOptionValue('sgpb-push-notification-save-choice');
		$pushNotificationParams['pushNotificationType'] = SGPB_POPUP_TYPE_PUSH_NOTIFICATION;

		$jsFiles['jsFiles'][] = array('folderUrl' => SGPB_PUSH_NOTIFICATION_JS_URL, 'filename' => 'PushNotification.js');
		$jsFiles['localizeData'][] = array(
			'handle' => 'PushNotification.js',
			'name' => 'SgpbPushNotificationParams'.$popupId,
			'data' => $pushNotificationParams
		);

		$jsFiles['localizeData'][] = array(
			'handle' => 'PushNotification.js',
			'name' => 'SGPB_PUSH_NOTIFICATION',
			'data' => array(
				'publicKey' => PushNotificationAdminHelper::getOption('sgpb-push-notification-public-key'),
				'jsUrl' => SGPB_PUSH_NOTIFICATION_JS_URL
			)
		);

		$jsFiles['localizeData'][] = array(
			'handle' => 'PopupBuilder.js',
			'name' => 'SgpbPushNotificationParams'.$popupId,
			'data' => array(
				'popupTypePushNotification' => SGPB_POPUP_TYPE_PUSH_NOTIFICATION
			)
		);

		return $jsFiles;
	}

	public function adminJsFilter($jsFiles)
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
			'filePath' => SGPB_PUSH_NOTIFICATION_VIEWS_PATH.'pushNotification.php',
			'metaboxTitle' => 'Push Notifications Settings',
			'short_description' => 'Create push notification, customize the look'
		);
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$popupId = (int)$this->getId();
		$popupContent = $this->getContent();
		$pushToBottom = $this->getOptionValue('sgpb-push-notification-to-bottom');

		$yesButton = $this->getOptionValue('sgpb-push-notification-yes-btn');
		$noButton = $this->getOptionValue('sgpb-push-notification-no-btn');

		$popupContent .= '<div class="sgpb-push-notification-buttons-wrapper-'.esc_attr($popupId).'" >';
		$popupContent .= '<button id="sgpb-disallow-button"  class="sgpb-push-notification-button" type="button">'.$noButton.'</button>';
		$popupContent .= '<button id="sgpb-allow-button" class="sgpb-push-notification-button" type="button" data-id="'.esc_attr($popupId).'">'.$yesButton.'</button>';
		$popupContent .= '</div>';

		$popupStyles = $this->renderStyles();
		$popupContent .= $popupStyles;

		if ($pushToBottom) {
			$selector = '.sgpb-push-notification-buttons-wrapper-'.$popupId;
			$popupContent .= PushNotificationAdminHelper::setPushToBottom($selector);
		}

		return $popupContent;
	}

	public function getRemoveOptions()
	{
		// Where 1 mean this options must not show for this popup type
		$removeOptions = array(
			'sgpb-overlay-click' => 1,
			'sgpb-esc-key' => 1,
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
			'sgpb-enable-close-button' => ''
		);
	}

	public function renderStyles()
	{
		$popupId                  = $this->getId();
		$yesButtonBackgroundColor = $this->getOptionValue('sgpb-push-notification-yes-btn-bg-color');
		$yesButtonBorderColor     = $this->getOptionValue('sgpb-push-notification-yes-btn-border-color');
		$yesButtonBorderWidth     = $this->getOptionValue('sgpb-push-notification-yes-btn-border-width');
		$noButtonBackgroundColor  = $this->getOptionValue('sgpb-push-notification-no-btn-bg-color');
		$noButtonBorderColor      = $this->getOptionValue('sgpb-push-notification-no-btn-border-color');
		$noButtonBorderWidth      = $this->getOptionValue('sgpb-push-notification-no-btn-border-width');
		$yesButtonTextColor       = $this->getOptionValue('sgpb-push-notification-yes-btn-text-color');
		$noButtonTextColor        = $this->getOptionValue('sgpb-push-notification-no-btn-text-color');
		$yesButtonRadius          = $this->getOptionValue('sgpb-push-notification-yes-btn-radius');
		$noButtonRadius           = $this->getOptionValue('sgpb-push-notification-no-btn-radius');
		$yesButtonRadiusType      = $this->getOptionValue('sgpb-push-notification-yes-btn-radius-type');
		$noButtonRadiusType       = $this->getOptionValue('sgpb-push-notification-no-btn-radius-type');

		if ($yesButtonBorderWidth) {
			$yesButtonBorderWidth .= 'px solid';
		}
		if ($noButtonBorderWidth) {
			$noButtonBorderWidth .= 'px solid';
		}

		$styles = "<style>
			.sgpb-push-notification-buttons-wrapper-$popupId #sgpb-allow-button {
				background-color: $yesButtonBackgroundColor;
				color: $yesButtonTextColor;
				border: $yesButtonBorderWidth;
				border-radius: $yesButtonRadius$yesButtonRadiusType;
				border-color: $yesButtonBorderColor;
			}
			.sgpb-push-notification-buttons-wrapper-$popupId #sgpb-disallow-button {
				background-color: $noButtonBackgroundColor;
				color: $noButtonTextColor;
				border: $noButtonBorderWidth;
				border-radius: $noButtonRadius$noButtonRadiusType;
				border-color: $noButtonBorderColor;
			}
			.sgpb-push-notification-buttons-wrapper-$popupId #sgpb-allow-button,
			.sgpb-push-notification-buttons-wrapper-$popupId #sgpb-disallow-button {
				min-height: 20px !important;
				padding: 12px;
				font-weight: bold;
				font-size: 15px;
			}
			.sgpb-push-notification-buttons-wrapper-$popupId #sgpb-disallow-button,
			.sgpb-push-notification-buttons-wrapper-$popupId #sgpb-allow-button {
				margin-left: 5px;
			}
			.sgpb-push-notification-buttons-wrapper-$popupId {
				text-align: center;
			}
			#sgcboxLoadedContent .sgpb-push-notification-buttons-wrapper-$popupId button {
				padding: 12px !important;
				line-height: 0.4;
				margin-bottom: 4px;
			}
			.sgpb-push-notification-button {
				cursor: pointer !important;
			}
			.sgpb-hide-overflow {
				overflow: hidden;
			}
		</style>";

		return $styles;
	}
}
