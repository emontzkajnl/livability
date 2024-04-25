<?php
namespace sgpbedd;
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
		add_action('edd_ajax_add_to_cart_response', array($this, 'addedToCart'), 10, 1);
		add_filter('sgpbConditionalJsClasses', array($this, 'addConditionalClassName'), 10, 1);
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

	public function addConditionalClassName($classes)
	{
		$classes[] = 'SGPBEdd';

		return $classes;
	}

	public function eventsData($eventsData)
	{
		$eventsData[][SGPB_EDD_ADD_TO_CART_KEY] = null;
		$eventsData['param'][SGPB_EDD_ADD_TO_CART_KEY] = __('EDD Add To Cart Click', SG_POPUP_TEXT_DOMAIN);

		return $eventsData;
	}

	public function addedToCart($args = array())
	{
		$currentProdPrice = GetProductsHelper::getPriceById($args['id']);
		$price = GetProductsHelper::getTotalPrice();
		$totalPrice = $currentProdPrice + $price;
		$args['sgpbTotalPrice'] = $totalPrice;
		$args['sgpbProductsIds'] = GetProductsHelper::getProductsIds();

		return $args;
	}

	private function init()
	{
		add_filter('sgPopupEventsData', array($this, 'eventsData'), 10, 1);
		add_filter('sgpbSavePopupOptions', array($this, 'saveOptions'), 10, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'defaultOption'), 1, 1);
		add_filter('sgpbAdditionalMetaboxes', array($this, 'metabox'), 9, 1);
	}

	public function metabox($metaboxes) {
		$metaboxes['eddSpecialEvents'] = array(
			'key' => 'eddSpecialEvents',
			'displayName' => 'EDD Settings',
			'filePath' => SGPB_EDD_VIEWS_PATH.'mainView.php',
			'short_description' => 'Select after which shopping behavior popup will be triggered',
			'priority' => 'low'
		);

		return $metaboxes;
	}

	public function saveOptions($popupOptions)
	{
		$specialBehaviors = @$popupOptions['sgpb-edd-special-events'];
		if (!empty($specialBehaviors) && is_array($specialBehaviors)) {
			foreach ($specialBehaviors as $groupId => $groupRow) {
				foreach ($groupRow as $ruleId => $ruleRow) {
					if (!empty($ruleRow['operator']) && $ruleRow['operator'] == 'product-is') {
						$args = array(
							'post__in' => $ruleRow['value'],
							'posts_per_page' => 10,
							'post_type'      => SGPB_EDD_PRODUCT_POST_TYPE
						);

						$searchResults = ConfigDataHelper::getPostTypeData($args);
						$popupOptions['sgpb-edd-special-events'][$groupId][$ruleId]['value'] = $searchResults;
					}
				}
			}
		}

		return $popupOptions;
	}

	public function defaultOption($options)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$specialEventsDefaultData = array($SGPB_DATA_CONFIG_ARRAY['edd-special-events']['initialData']);
		$options[] = array('name' => 'sgpb-edd-special-events', 'type' => 'array', 'defaultValue' => $specialEventsDefaultData);
		return $options;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_EDD] = SGPB_EDD_CLASSES_PATH;

		return $typePaths;
	}
}
