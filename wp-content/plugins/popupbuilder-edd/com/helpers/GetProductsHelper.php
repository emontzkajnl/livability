<?php
namespace sgpbedd;

class GetProductsHelper
{
	public static function getTotalPrice()
	{
		$totalPrice = 0;
		if (!function_exists('edd_get_cart_contents')) {
			return $totalPrice;
		}
		$carts = EDD()->cart->details;
		if (empty($carts)) {
			return $totalPrice;
		}
		foreach ($carts as $item) {
			$totalPrice += (int)$item['price'];
		}

		return $totalPrice;
	}

	public static function getPriceById($id = 0)
	{
		$totalPrice = edd_cart_item_price($id);
		$totalPrice = str_replace('&#36;', '', $totalPrice);

		return (int)$totalPrice;
	}

	public static function getNumberOfProducts()
	{
		$numberOfProducts = 0;

		if (!function_exists('edd_get_cart_contents')) {
			return $numberOfProducts;
		}

		$carts = edd_get_cart_contents();
		if (empty($carts)) {
			return $numberOfProducts;
		}

		$numberOfProducts = count($carts);

		return $numberOfProducts;
	}

	public static function getProductsIds()
	{
		$productIds = array();
		if (!function_exists('edd_get_cart_contents')) {
			return $productIds;
		}
		$carts = edd_get_cart_contents();
		if (empty($carts)) {
			return $productIds;
		}

		foreach ($carts as $item) {
			if (empty($item)) {
				continue;
			}
			if (!empty($item['id'])) {
				$productIds[] = $item['id'];
			}
		}

		return $productIds;
	}
}
