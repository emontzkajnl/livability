<?php
namespace sgpbrs;
use sgpbrs\AdminHelper as AdminHelperRecentSales;

class DefaultOptionsData
{
	public static function getSource()
	{
		$source = array(
		);

		$isWooActive = AdminHelperRecentSales::isWoocommerceActive();
		$isEddActive = AdminHelperRecentSales::isEddActive();
		if ($isWooActive) {
			$source[SGPB_WOO_PLUGIN_SOURCE_KEY] = SGPB_WOO_DISPLAY_NAME;
		}

		if ($isEddActive) {
			$source[SGPB_EDD_PLUGIN_SOURCE_KEY] = SGPB_EDD_DISPLAY_NAME;
		}

		return $source;
	}

	public static function getPurchases()
	{
		return self::getSource();
	}

	public static function getImageTypes()
	{
		$types = array(
			'buyer' => 'Buyer',
			'product' => 'Product',
			'custom' => 'Custom'
		);

		return $types;
	}

	public static function getDefaultAvatarImage()
	{
		return SGPB_RECENT_SALES_IMG_URL.'defaultAvatarImage.png';
	}

	public static function getDefaultProductImage()
	{
		return SGPB_RECENT_SALES_IMG_URL.'defaultProductImage.png';
	}

	public static function getDefaultCustomImage()
	{
		return SGPB_RECENT_SALES_IMG_URL.'defaultCustomImage.png';
	}
}
