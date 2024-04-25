<?php
namespace sgpbwoo;
use sgpb\SGPopup;
use \ConfigDataHelper;

class Filters
{
	private $popup = array();

	public function setPopup($popup)
	{
		$this->popup = $popup;
	}

	public function getPopup()
	{
		return $this->popup;
	}

	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		add_filter('woocommerce_add_to_cart_fragments', array($this, 'addedToCart'), 10, 1);
		add_filter('sgpbConditionalJsClasses', array($this, 'addConditionalClassName'), 10, 1);
		add_action('woocommerce_add_to_cart', array($this, 'addToCartNotAjax'));
		// by default, it's called inside after register popup builder post type but here we need it to call to get current popup type
		if (class_exists('\SgpbPopupConfig')) {
			\SgpbPopupConfig::popupTypesInit();
		}
		if (isset($_GET['post']) && class_exists('sgpb\SGPopup')) {
			$popup =  @SGPopup::find($_GET['post']);
			$this->setPopup($popup);
		}

		$this->init();
	}

	public function addToCartNotAjax()
	{
		$cartArgs = array(
			'total-price' => GetProductsHelper::getTotalPrice(),
			'number-of-product' => GetProductsHelper::getNumberOfProducts(),
			'products-ids' => GetProductsHelper::getProductsIds()
		);
		$cartArgs = json_encode($cartArgs);
		$redirectAfterAddToCart = get_option('woocommerce_cart_redirect_after_add');
		if (!defined('DOING_AJAX') && $redirectAfterAddToCart == 'no') {
			echo "<script>var sgpbNotAjaxAddedToCart = $cartArgs; </script>";
		}
	}

	private function init()
	{
		add_filter('sgPopupEventsData', array($this, 'eventsData'), 10, 1);
		add_filter('sgpbSavePopupOptions', array($this, 'saveOptions'), 10, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'defaultOption'), 1, 1);
		add_filter('sgPopupEventAttrs', array($this, 'addPopupEvents'), 2, 1);
		add_filter('sgPopupEventTypes', array($this, 'addPopupEventTypes'), 2, 1);
		add_filter('sgpbAdditionalMetaboxes', array($this, 'metabox'), 9, 1);
	}


	public function addPopupEventTypes($eventColumnType)
	{
		$eventColumnType[SGPB_ADD_TO_CART_KEY] = 'select';

		return $eventColumnType;
	}

	public function addPopupEvents($eventsAttrs)
	{
		$eventsAttrs[SGPB_ADD_TO_CART_KEY] = array(
			'htmlAttrs' => array('class' => 'js-sg-select2'),
			'infoAttrs' => array(
				'label' => __('Select Event', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$eventAttrs[SGPB_ADD_TO_CART_KEY] = array(
			'class' => 'js-sg-select2 js-select-basic sgpb-popup-option',
			'data-select-class' => 'js-select-basic',
			'data-select-type' => 'basic'
		);


		return $eventsAttrs;
	}

	public function addConditionalClassName($classes)
	{
		$classes[] = 'SGPBWoo';

		return $classes;
	}

	public function eventsData($eventsData)
	{
		$eventsData['param'][SGPB_ADD_TO_CART_KEY] = __('WooCommerce Events', SG_POPUP_TEXT_DOMAIN);
		$eventsData[SGPB_ADD_TO_CART_KEY] = array(
			SGPB_WOO_ADD_TO_CART => __('Add to  cart', SG_POPUP_TEXT_DOMAIN),
			SGPB_WOO_REMOVE_FROM_CART => __('Remove from cart', SG_POPUP_TEXT_DOMAIN)
		);

		return $eventsData;
	}

	public function addedToCart($args)
	{
		$args['total-price'] = GetProductsHelper::getTotalPrice();
		$args['number-of-product'] = GetProductsHelper::getNumberOfProducts();
		$args['products-ids'] = GetProductsHelper::getProductsIds();

		return $args;
	}

	public function metabox($metaboxes)
	{
		$metaboxes['wooSpecialEvents'] = array(
			'key' => 'wooSpecialEvents',
			'displayName' => SGPB_POPUP_TYPE_WOO_DISPLAY_NAME.' Settings',
			'filePath' => SGPB_WOO_VIEWS_PATH.'mainView.php',
			'short_description' => 'Select after which shopping behavior popup will be triggered',
			'priority' => 'low'
		);

		return $metaboxes;
	}

	public function saveOptions($popupOptions)
	{
		$specialBehaviors = @$popupOptions['sgpb-woocommerce-special-events'];
		if (!empty($specialBehaviors) && is_array($specialBehaviors)) {
			foreach ($specialBehaviors as $groupId => $groupRow) {
				foreach ($groupRow as $ruleId => $ruleRow) {
					if (!empty($ruleRow['operator']) && $ruleRow['operator'] == 'product-is') {
						$args = array(
							'post__in' => $ruleRow['value'],
							// no need to set the value of products more than 100, user just can set by category
							'posts_per_page' => 100,
							'post_type'      => 'product'
						);

						$searchResults = ConfigDataHelper::getPostTypeData($args);
						$popupOptions['sgpb-woocommerce-special-events'][$groupId][$ruleId]['value'] = $searchResults;
					}
				}
			}
		}

		return $popupOptions;
	}

	public function defaultOption($options)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$specialEventsDefaultData = array($SGPB_DATA_CONFIG_ARRAY['woocommerce-special-events']['initialData']);
		$options[] = array('name' => 'sgpb-woocommerce-special-events', 'type' => 'array', 'defaultValue' => $specialEventsDefaultData);
		return $options;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_WOO] = SGPB_WOO_CLASSES_PATH;

		return $typePaths;
	}
}
