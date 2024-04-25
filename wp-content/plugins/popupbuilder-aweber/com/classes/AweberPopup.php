<?php

namespace sgpb;
use sgpbaw\SGPBAWeberApi;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class AweberPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbHasSubPopups', array($this, 'subPopups'), 10, 1);
		add_filter('sgpbPopupRenderOptions', array($this, 'renderOptions'), 10, 1);
	}

	private function frontendFilters()
	{
		add_filter('sgpbAWeberFrontendJs', array($this, 'aweberFrontendJsFilter'), 10, 1);
	}

	public function renderOptions($options)
	{
		// for old popups
		if (isset($options['sgpb-aweber-success-popup']) && function_exists('sgpb\sgpGetCorrectPopupId')) {
			$options['sgpb-aweber-success-popup'] = sgpGetCorrectPopupId($options['sgpb-aweber-success-popup']);
		}
		
		return $options;
	}

	public function subPopups($subPopups)
	{
		$subPopups[] = 'aweber';

		return $subPopups;
	}

	public function aweberFrontendJsFilter($jsFiles)
	{
		$isActive = $this->getOptionValue('sgpb-is-active', true);
		if (!$isActive) {
			return $jsFiles;
		}
		$popupId = $this->getId();

		$jsFiles[] = array('folderUrl' => SGPB_AWEBER_JS_URL, 'filename' => 'AWeber.js');
		$jsFiles[] = array('folderUrl' => SG_POPUP_JS_URL, 'filename' => 'Validate.js');

		$localizeData[] = array(
			'handle' => 'AWeber.js',
			'name' => 'SgpbAweberParams',
			'data' => $this->getAWeberPopupParams($popupId)
		);

		$jsFiles = array(
			'jsFiles' => $jsFiles,
			'localizeData' => $localizeData
		);

		return $jsFiles;
	}

	public function popupAdminJsFilter($jsFiles)
	{
		return $jsFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeOptionsView()
	{
		return array();
	}

	public function getPopupTypeMainView()
	{
		return array(
			'filePath' => SGPB_AWEBER_VIEWS_PATH.'mainView.php',
			'metaboxTitle' => __('AWeber Settings', SG_POPUP_TEXT_DOMAIN),
			'short_description' => 'Connect your AWeber account, subscribers will be automatically added to your account'
		);
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$aweberContent = $this->getContent();
		$messageHtml = '';
		$formHtml = '<b>'.__('Popup Builder AWeber extension requires authentication.', SG_POPUP_TEXT_DOMAIN).'</b>';
		$popupId = $this->getId();
		$listId = $this->getOptionValue('sgpb-aweber-list');
		$webFormId = $this->getOptionValue('sgpb-aweber-signup-form');
		$aweberObj = new SGPBAWeberApi();
		if (get_option('sgpbAccessToken') && (!empty($listId) || !empty($webFormId))) {
			$formHtml = $aweberObj->getWebformHtml($listId, $webFormId);
			$messageHtml = $this->getSuccessErrorMessagesHtml();
			$formHtml = '<div class="sgpb-aweber-form-wrapper-'.$popupId.'">'.$formHtml.'</div>';
		}

		return $aweberContent.$messageHtml.$formHtml;
	}

	public function getSuccessErrorMessagesHtml()
	{
		$messageHtml = '';
		$popupId = $this->getId();
		$alreadySubscribed = $this->getOptionValue('sgpb-aweber-custom-subscribed-message');
		$success = $this->getOptionValue('sgpb-aweber-success-message');
		$messageHtml .= '<div class="sg-hide-element sgpb-alert sgpb-alert-success sgpb-aweber-success-message-'.$popupId.'"><p>'.$success.'</p></div>';
		$messageHtml .= '<div class="sg-hide-element sgpb-alert sgpb-alert-danger sgpb-aweber-already-subscribed-message-'.$popupId.'"><p>'.$alreadySubscribed.'</p></div>';

		return $messageHtml;
	}

	public function getExtraRenderOptions()
	{
		return array();
	}

	public function getAWeberPopupParams($popupId = 0)
	{
		$successStatus = $this->getOptionValue('sgpb-aweber-custom-success-message');
		$successMessage = $this->getOptionValue('sgpb-aweber-success-message');
		$invalidEmailStatus = $this->getOptionValue('sgpb-aweber-custom-invalid-email-message');
		$invalidEmailMessage = $this->getOptionValue('sgpb-aweber-invalid-email');
		$subscribedStatus = $this->getOptionValue('sgpb-aweber-custom-subscribed-message');
		$subscribedMessage = $this->getOptionValue('sgpb-aweber-custom-subscribed-message');
		$aweberRequiredMessage = $this->getOptionValue('sgpb-aweber-required-message');
		$aweberValidateEmailMessage = $this->getOptionValue('sgpb-aweber-validate-email-message');
		$aweberSuccessBehavior = $this->getOptionValue('sgpb-aweber-success-behavior');
		$aweberSuccessRedirectUrl = $this->getOptionValue('sgpb-aweber-success-redirect-URL');
		$aweberSuccessPopupsList = $this->getOptionValue('sgpb-aweber-success-popups-list');
		$aweberSuccessRedirectNewTab = $this->getOptionValue('sgpb-aweber-success-redirect-new-tab');
		if (!$this->getOptionValue('sgpb-aweber-invalid-email')) {
			$invalidEmailStatus = '';
		}

		$sgAweberParams = array(
			'popupId' => $popupId,
			'successStatus' => $successStatus,
			'successMessage' => $successMessage,
			'invalidEmailStatus' => $invalidEmailStatus,
			'invalidEmailMessage' => $invalidEmailMessage,
			'subscribedStatus' => $subscribedStatus,
			'subscribedMessage' => $subscribedMessage,
			'aweberImagesUrl' => SGPB_AWEBER_IMG_URL,
			'aweberRequiredMessage' => $aweberRequiredMessage,
			'aweberValidateEmailMessage' => $aweberValidateEmailMessage,
			'aweberSuccessBehavior' => $aweberSuccessBehavior,
			'aweberSuccessRedirectUrl' => $aweberSuccessRedirectUrl,
			'aweberSuccessPopupsList' => $aweberSuccessPopupsList,
			'aweberSuccessRedirectNewTab' => $aweberSuccessRedirectNewTab,
			'sgpbAweberAjaxUrl' => admin_url('admin-ajax.php')
		);

		return $sgAweberParams;
	}

	public function getSubPopupObj()
	{
		$options = $this->getOptions();
		$subPopups = parent::getSubPopupObj();

		if ($options['sgpb-aweber-success-behavior'] == 'openPopup') {
			$subPopupId = (!empty($options['sgpb-aweber-success-popup'])) ? (int)$options['sgpb-aweber-success-popup']: null;

			if (empty($subPopupId)) {
				return $subPopups;
			}

			// for old popups
			if (function_exists('sgpb\sgpGetCorrectPopupId')) {
				$subPopupId = sgpGetCorrectPopupId($subPopupId);
			}

			$subPopupObj = SGPopup::find($subPopupId);
			if (!empty($subPopupObj) && ($subPopupObj instanceof SGPopup)) {
				// We remove all events because this popup will be open after successful subscription
				$subPopupObj->setEvents(array('param' => 'click', 'value' => ''));
				$subPopups[] = $subPopupObj;
			}
		}

		return $subPopups;
	}
}
