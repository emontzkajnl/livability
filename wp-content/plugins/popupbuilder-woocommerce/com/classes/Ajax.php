<?php
namespace sgpbwoo;
use sgpbwoo\GetProductsHelper;

class Ajax
{
	public function __construct()
	{
		$this->init();
	}


	private function init()
	{
		add_action('wp_ajax_sgpb_woo_get_cart_items', array($this, 'getCartItems'));
		add_action('wp_ajax_nopriv_sgpb_woo_get_cart_items', array($this, 'getCartItems'));
	}

	public function getCartItems()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$cartItem = array(
			'total-price' => GetProductsHelper::getTotalPrice(),
			'number-of-product' => GetProductsHelper::getNumberOfProducts(),
			'products-ids' => GetProductsHelper::getProductsIds()
		);

		wp_die(json_encode($cartItem));
	}
}
