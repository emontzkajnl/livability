<?php
namespace sgpbedd;

class Actions
{
	public function __construct()
	{
		global $SGPB_DATA_CONFIG_ARRAY;

		$SGPB_DATA_CONFIG_ARRAY['edd-special-events'] = $this->getBehaviorAfterSpecialEventsConfig();
	}

	public function eddSettingsView()
	{
		require_once SGPB_EDD_VIEWS_PATH.'mainView.php';
	}

	public function getBehaviorAfterSpecialEventsConfig()
	{
		$columns = array(
			'param' => 'Condition',
			'operator' => 'Parameter',
			'value' => 'Value'
		);

		$columnTypes = array(
			'param' => 'select',
			'operator' => 'select',
			'value' => 'select',
			'select_behavior' => 'select',
			'number-of-product' => 'number',
			'number-of-product-lower' => 'number',
			'product-is' => 'select',
			'total-price' => 'number',
			'total-price-lower' => 'number'
		);

		$params = array(
			'param' => array(
				SGPB_EDD_CONDITION_KEY => __('Shopping cart', SG_POPUP_TEXT_DOMAIN)
			),
			'operator' => array(
				'select_behavior' => __('Select behavior', SG_POPUP_TEXT_DOMAIN),
				__('Behaviors', SG_POPUP_TEXT_DOMAIN) => array(
					'number-of-product' => __('Number of products >=', SG_POPUP_TEXT_DOMAIN),
					'number-of-product-lower' => __('Number of products <=', SG_POPUP_TEXT_DOMAIN),
					'cart-is-empty' => __('Cart is empty', SG_POPUP_TEXT_DOMAIN),
					'product-is' => __('Product(s) added', SG_POPUP_TEXT_DOMAIN),
					'total-price' => __('Total price >=', SG_POPUP_TEXT_DOMAIN),
					'total-price-lower' => __('Total price <=', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'number-of-product' => 1,
			'number-of-product-lower' => 1,
			'product-is' => array(),
			'total-price' => '',
			'total-price-lower' => ''
		);

		$initialData = array(
			array(
				'param' => SGPB_EDD_CONDITION_KEY,
				'operator' => 'select_behavior'
			)
		);

		$attrs = array(
			'param' => array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2 js-select-basic',
					'data-select-class' => 'js-select-basic',
					'data-select-type' => 'basic'
				),
				'infoAttrs' => array(
					'label' => __('Condition', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'operator' => array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2 js-select-basic',
					'data-select-class' => 'js-select-basic',
					'data-select-type' => 'basic'
				),
				'infoAttrs' => array(
					'label' => __('Parameter', SG_POPUP_TEXT_DOMAIN),
					'info' => __('Select a shopping cart condition after which the popup will appear', SG_POPUP_TEXT_DOMAIN).'.'
				)
			),
			'number-of-product' => array(
				'htmlAttrs' => array(
					'class' => 'sg-full-width',
					'required' => 'required'
				),
				'infoAttrs' => array(
					'label' => __('Number of product', SG_POPUP_TEXT_DOMAIN),
					'info' => __('If the number of products in the cart is higher or is equal to the specified amount, the popup will appear.', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'number-of-product-lower' => array(
				'htmlAttrs' => array(
					'class' => 'sg-full-width',
					'required' => 'required'
				),
				'infoAttrs' => array(
					'label' => __('Number of product', SG_POPUP_TEXT_DOMAIN),
					'info' => __('If the number of products in the cart is lower or is equal to the specified amount, the popup will appear.', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'product-is' => array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2 js-select-ajax',
					'data-select-class' => 'js-select-ajax',
					'data-select-type' => 'ajax',
					'multiple' => 'multiple',
					'data-value-param' => SGPB_EDD_PRODUCT_POST_TYPE,
					'required' => 'required'
				),
				'infoAttrs' => array(
					'label' => __('Select product', SG_POPUP_TEXT_DOMAIN),
					'info' => __('If the following product(s) is/are added to the cart the popup will appear.', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'total-price' => array(
				'htmlAttrs' => array(
					'class' => 'sg-full-width',
					'required' => 'required',
					'value' => ' ',
					'min' => 0
				),
				'infoAttrs' => array(
					'label' => __('Total price', SG_POPUP_TEXT_DOMAIN),
					'info' => __('If the value of the total price (integer) is higher or is equal to the specified amount, the popup will appear.', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'total-price-lower' => array(
				'htmlAttrs' => array(
					'class' => 'sg-full-width',
					'required' => 'required',
					'value' => ' ',
					'min' => 0
				),
				'infoAttrs' => array(
					'label' => __('Total price', SG_POPUP_TEXT_DOMAIN),
					'info' => __('If the value of the total price (integer) is lower or is equal to the specified amount, the popup will appear.', SG_POPUP_TEXT_DOMAIN)
				)
			)
		);

		$operators = array(
			array('operator' => 'add', 'name' => __('Add', SG_POPUP_TEXT_DOMAIN)),
			array('operator' => 'delete', 'name' => __('Delete', SG_POPUP_TEXT_DOMAIN))
		);

		$config = array();
		$config['columns'] = apply_filters('sgPopupEddColumns', $columns);
		$config['columnTypes'] = apply_filters('sgPopupEddColumnTypes', $columnTypes);
		$config['paramsData'] = apply_filters('sgPopupEddParams', $params);
		$config['initialData'] = apply_filters('sgPopupEddInitialData', $initialData);
		$config['attrs'] = apply_filters('sgPopupEddAttrs', $attrs);
		$config['operators'] = apply_filters('sgPopupEddOperators', $operators);
		$config['specialDefaultOperator'] = apply_filters('sgPopupEddOperators', ' ');

		return $config;
	}
}
