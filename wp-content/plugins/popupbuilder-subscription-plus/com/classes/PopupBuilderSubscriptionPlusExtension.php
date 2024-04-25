<?php
use sgpb\AdminHelper;
use sgpbsubscriptionplus\SubscriptionPlusAdminHelper;
use sgpbsubscriptionplus\EmailIntegrations as EmailIntegrations;
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderSubscriptionPlusExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();
		$popupType = '';
		$newsletterPage = SG_POPUP_POST_TYPE.'_page_'.SG_POPUP_NEWSLETTER_PAGE;
		if (!empty($data['popupType'])) {
			$popupType = $data['popupType'];
		}
		if ($page == SG_POPUP_EMAIL_INTEGRATIONS_PAGE || $page == 'editpage') {
			$jsFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_PLUS_JS_URL, 'filename' => 'emailintegrations.js');
			
			$allProviders = EmailIntegrations::allEmailProvidersList(true);
			$availableProviders = $allProviders['available'];
			
			$localizeData[] = array(
				'handle' => 'emailintegrations.js',
				'name' => 'SGPB_SUBSCRIPTION_PLUS_EMAIL_INTEGRATIONS_PARAMS',
				'data' => array(
					'availableProvidersData' => $availableProviders,
					'requiredFieldMessage' => __('This field is required', SG_POPUP_TEXT_DOMAIN),
				)
			);
		}

		$currentPostType = AdminHelper::getCurrentPostType();
		// for current popup type page load and for popup types pages too
		if ($page == SG_POPUP_SUBSCRIBERS_PAGE || $page == SG_POPUP_POST_TYPE.'_page_'.SG_POPUP_SUBSCRIBERS_PAGE || ($page == 'editpage' && $popupType == 'subscription') || ($currentPostType == SG_POPUP_AUTORESPONDER_POST_TYPE)) {
			wp_enqueue_script('jquery-ui-droppable');
			$jsFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_PLUS_JS_URL, 'filename' => 'SubscriptionPlus.js');
			$jsFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_PLUS_MCE_JS_URL . 'table/', 'filename' => 'plugin.js');
			$jsFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_PLUS_JS_URL, 'filename' => 'SubscriptionPlus.js');
			$localizeData[] = array(
				'handle' => 'SubscriptionPlus.js',
				'name' => 'SGPB_SUBSCRIPTION_PLUS_JS_PARAMS',
				'data' => array(
					'ajaxUrl' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce(SG_AJAX_NONCE),
					'currentPostType' => $currentPostType,
					'templatePostType' => SG_POPUP_TEMPLATE_POST_TYPE
				)
			);

			$localizeData[] = array(
				'handle' => 'SubscriptionPlus.js',
				'name' => 'SGPB_SUBSCRIPTION_PLUS_AUTORESPONDER_STATUS_MESSAGE',
				'data' => array(
					'ajaxUrl' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce(SG_AJAX_NONCE),
					'value' => SubscriptionPlusAdminHelper::getJsLocalizedData()
				)
			);

			$jsFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_FORM_BUILDER_URL . 'js/', 'filename' => 'formBuilder.js');
			$localizeData[] = array(
				'handle' => 'formBuilder.js',
				'name' => 'SGPB_SUBSCRIPTION_PLUS_PUBLIC_URL',
				'data' => array(
					'value' => SGPB_SUBSCRIPTION_PLUS_PUBLIC_URL
				)
			);

		}

		if ($page == $newsletterPage) {
			$jsFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_PLUS_JS_URL, 'filename' => 'SubscriptionPlus.js');
		}


		$scriptData = array(
			'jsFiles' => apply_filters('sgpbSubscriptionPlusAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbSubscriptionPlusAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbSubscriptionPlusAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		$popupType = '';
		if (!empty($data['popupType'])) {
			$popupType = $data['popupType'];
		}
		// for current popup type page load and for popup types pages too
		if ($page != 'editpage' && $page != SG_SUBSCRIPTION_PLUS_PAGE && $page != SG_POPUP_EMAIL_INTEGRATIONS_PAGE) {
			// here we will include current popup type custom styles
			return $cssFiles;
		}
		if (!empty($popupType) && $popupType != 'subscription') {
			return $cssFiles;
		}
		if (SG_POPUP_EMAIL_INTEGRATIONS_PAGE) {
			$cssFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_PLUS_CSS_URL, 'filename' => 'emailintegrations.css');
		}
		$cssFiles[] = array(
			'folderUrl' => SG_POPUP_CSS_URL,
			'filename' => 'ResetFormStyle.css'
		);
		$cssFiles[] = array(
			'folderUrl' => SG_POPUP_CSS_URL,
			'filename' => 'SubscriptionForm.css'
		);

		$cssFiles[] = array('folderUrl'=> SGPB_SUBSCRIPTION_FORM_BUILDER_URL.'css/', 'filename' => 'formAdmin.css');
		$cssFiles[] = array('folderUrl' => SGPB_SUBSCRIPTION_PLUS_CSS_URL, 'filename' => 'subscriptionPlus.css');
		
		$cssData = array(
			'cssFiles' => apply_filters('sgpbSubscriptionPlusAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasSubscriptionPlusPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasSubscriptionPlusPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbSubscriptionPlusJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbSubscriptionPlusJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbSubscriptionPlusJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasSubscriptionPlusPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasSubscriptionPlusPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbSubscriptionPlusCssFiles', $cssFiles)
		);

		return $cssData;
	}

	protected function hasConditionFromLoadedPopups($popups)
	{
		$hasType = false;

		foreach ($popups as $popup) {
			if (!is_object($popup)) {
				continue;
			}
			if ($popup->getType() == SGPB_POPUP_TYPE_SUBSCRIPTION_PLUS) {
				$hasType = true;
			}
		}

		return $hasType;
	}
}
