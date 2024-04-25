<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');
use sgpbwoo\GetProductsHelper;

class SGPBPopupBuilderWooExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_WOO) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbWooAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbWooAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbWooAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_WOO || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbWooAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();
		
		$hasWooPopup = $this->hasConditionFromLoadedPopups($data['popups']);
		
		if (!$hasWooPopup) {
			return false;
		}
		
		$jsFiles[] = array('folderUrl' => SGPB_WOO_JS_URL, 'filename' => 'Woocommerce.js', 'dep' => array('PopupBuilder.js'));
		$localizeData[] = array(
			'handle' => 'Woocommerce.js',
			'name' => 'SgpbWooParams',
			'data' => array(
				'total-price' => GetProductsHelper::getTotalPrice(),
				'number-of-product' => GetProductsHelper::getNumberOfProducts(),
				'products-ids' => GetProductsHelper::getProductsIds()
			)
		);
		
		$localizeData[] = array(
			'handle' => 'Woocommerce.js',
			'name' => 'SgpbWooGeneralParams',
			'data' => array(
				'addToCartKey' => SGPB_WOO_ADD_TO_CART,
				'removeFromCartKey' => SGPB_WOO_REMOVE_FROM_CART
			)
		);

		$localizeData[] = array(
			'handle' => 'Woocommerce.js',
			'name' => 'SGPB_WOO_JS_PARAMS',
			'data' => array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce(SG_AJAX_NONCE)
			)
		);

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbWooJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbWooJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbWooJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasWooPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasWooPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbWooCssFiles', $cssFiles)
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
			$eventsData = $popup->getOptionValue('sgpb-events');
			$eventsData = $eventsData[0][0];
			$wooSavedCondtion = $popup->getOptionValue('sgpb-woocommerce-special-events');
			$wooSavedCondtion = $wooSavedCondtion[0][0];
			if ($wooSavedCondtion['operator'] != 'select_behavior' || $eventsData['param'] == 'addToCart') {
				$hasType = true;
				break;
			}
		}

		return $hasType;
	}
}