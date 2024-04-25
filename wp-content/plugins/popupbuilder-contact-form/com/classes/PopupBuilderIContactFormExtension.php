<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBPopupBuilderContactFormExtension implements SgpbIPopupExtension
{
	private function allowedPages()
	{
		$pages = array(SG_POPUP_POST_TYPE.'_page_'.SGPB_CONTACT_CONTACTED_PAGE);

		return $pages;
	}

	public function getScripts($page, $data)
	{
		$popupType = '';
		$scriptData = array();
		$allowedPages = $this->allowedPages();

		$jsFiles = array();
		$localizeData = array();

		if (!empty($data['popupType'])) {
			$popupType = $data['popupType'];
		}

		if ((!empty($popupType) && $popupType == SGPB_POPUP_TYPE_CONTACT_FORM) || in_array($page, $allowedPages)) {
			wp_enqueue_script('jquery-ui-droppable');
			$jsFiles[] = array('folderUrl'=> SGPB_CONTACT_FORM_JS_URL, 'filename' => 'ContactFormAdmin.js');
			$localizeData[] = array(
				'handle' => 'ContactFormAdmin.js',
				'name' => 'SGPB_CONTACT_JS_LOCALIZATION',
				'data' => array(
					'areYouSure' => __('Are you sure?', SG_POPUP_TEXT_DOMAIN),
					'selectLastOne' => __('Please select at least one.', SG_POPUP_TEXT_DOMAIN)
				)
			);

			$localizeData[] = array(
				'handle' => 'ContactFormAdmin.js',
				'name' => 'SGPB_JS_ADMIN_URL',
				'data' => array(
					'url'   => SG_POPUP_ADMIN_URL.'admin-post.php',
					'nonce' => wp_create_nonce(SG_AJAX_NONCE)
				)
			);
			$localizeData[] = array(
				'handle' => 'formBuilder.js',
				'name' => 'SGPB_CONTACT_FORM_PUBLIC_URL',
				'data' => array(
					'value' => SGPB_CONTACT_FORM_PUBLIC_URL
				)
			);
			$scriptData = array(
				'jsFiles' => apply_filters('sgpbContactFormAdminJsFiles', $jsFiles),
				'localizeData' => apply_filters('sgpbContactFormAdminJsLocalizedData', $localizeData)
			);

			$scriptData = apply_filters('sgpbContactFormAdminJs', $scriptData);
		}

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
		if (empty($popupType) || $popupType != SGPB_POPUP_TYPE_CONTACT_FORM) {
			// here we will include current popup type custom styles
			return $cssFiles;
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbContactFormAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasContactFormPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasContactFormPopup) {
			return false;
		}

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbContactFormJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbContactFormJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbContactFormJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasContactFormPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasContactFormPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbContactFormCssFiles', $cssFiles)
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
		}

		return $hasType;
	}
}
