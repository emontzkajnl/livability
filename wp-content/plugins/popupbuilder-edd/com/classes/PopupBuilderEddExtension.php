<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');
use sgpbedd\GetProductsHelper;

class SGPBPopupBuilderEddExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_EDD) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbEddAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbEddAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbEddAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_EDD || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbEddAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasEddPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasEddPopup) {
			return false;
		}

		$jsFiles[] = array('folderUrl' => SGPB_EDD_JS_URL, 'filename' => 'Edd.js', 'dep' => array('PopupBuilder.js'));
		$localizeData[] = array(
			'handle' => 'Edd.js',
			'name' => 'SgpbEddParams',
			'data' => array(
				'total-price' => GetProductsHelper::getTotalPrice(),
				'number-of-product' => GetProductsHelper::getNumberOfProducts(),
				'products-ids' => GetProductsHelper::getProductsIds()
			)
		);

		$localizeData[] = array(
			'handle' => 'Edd.js',
			'name' => 'SgpbEddGeneralParams',
			'data' => array(
				'eddAddToCartKey' => SGPB_EDD_ADD_TO_CART_KEY
			)
		);
		$scriptData = array(
			'jsFiles' => apply_filters('sgpbEddJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbEddJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbEddJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasEddPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasEddPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbEddCssFiles', $cssFiles)
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

			$eddSavedCondtion = $popup->getOptionValue('sgpb-edd-special-events');
			if ($eddSavedCondtion[0][0]['operator'] != 'select_behavior') {
				$hasType = true;
				break;
			}
		}

		return $hasType;
	}
}
