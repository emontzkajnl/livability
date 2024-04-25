<?php
namespace sgpbwoo;

class GetProductsHelper
{
	public static function getTotalPrice()
	{
		global $woocommerce;
		$totalPrice = 0;

		if (empty($woocommerce->cart)) {
			return $totalPrice;
		}

		$totalPrice = (int)$woocommerce->cart->total;

		return $totalPrice;
	}

	public static function getNumberOfProducts()
	{
		global $woocommerce;
		$numberOfProducts = 0;

		if (empty($woocommerce->cart)) {
			return $numberOfProducts;
		}
		$numberOfProducts = (int)$woocommerce->cart->cart_contents_count;

		return $numberOfProducts;
	}

	public static function getProductsIds()
	{
		global $woocommerce;
		$productIds = array();

		if (empty($woocommerce->cart)) {
			return $productIds;
		}
		$cart = $woocommerce->cart->get_cart();

		if (empty($cart)) {
			return $productIds;
		}

		foreach ($cart as $item => $value) {
			if (empty($value)) {
				continue;
			}
			$productIds[] = $value['product_id'];
		}

		return $productIds;
	}
}