<?php
namespace sgpbrs;
use sgpb\SGPopup;

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
		add_filter('sgpbHidePageBuilderEditButtons', array($this, 'hidePageBuilderEditButtons'), 10, 1);
		// by default, it's called inside after register popup builder post type but here we need it to call to get current popup type
		if (class_exists('\SgpbPopupConfig')) {
			\SgpbPopupConfig::popupTypesInit();
		}
		if (isset($_GET['post']) && class_exists('sgpb\SGPopup')) {
			$popup = @SGPopup::find($_GET['post']);
			$this->setPopup($popup);
		}
		$this->init();
	}

	private function init()
	{
		$popup = $this->getPopup();
		// popup builder pages
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11);
		}
		// edit page
		if ((isset($_GET['sgpb_type']) && $_GET['sgpb_type'] == SGPB_POPUP_TYPE_RECENT_SALES) || (is_object($popup) && $popup->getType() == SGPB_POPUP_TYPE_RECENT_SALES)) {
			add_action('sgpbPopupDefaultOptions', array($this, 'defaultOptions'), 11);
			add_filter('sgpbAdditionalMetaboxes', array($this, 'metaboxes'));
		}
	}
	private function array_insert($array,$values) {
		return array_slice($array, 0, 1, true) + $values + array_slice($array, 1, NULL, true);
	}
	public function metaboxes($metaboxes)
	{
		unset($metaboxes['eventsMetaboxView']);
		unset($metaboxes['behaviorAfterSpecialEventsMetaboxView']);
		unset($metaboxes['popupDesignMetaBoxView']);
		unset($metaboxes['spgdimension']);
		$dataToAdd['recentSalesMetabox'] = array(
			'key' => 'recentSalesMetabox',
			'displayName' => __('Helper Shortcodes', SG_POPUP_TEXT_DOMAIN),
			'short_description' => 'Use these shortcodes for Recent Sales popup',
			'filePath' => SGPB_RECENT_SALES_VIEWS_PATH.'info.php',
			'context' => 'side'
		);
		return $this->array_insert($metaboxes, $dataToAdd );
	}

	public function defaultOptions($options)
	{
		$options[] = array('name' => 'sgpb-sales-initial-delay', 'type' => 'number', 'defaultValue' => 10);
		$options[] = array('name' => 'sgpb-sales-popup-count', 'type' => 'number', 'defaultValue' => 5);
		$options[] = array('name' => 'sgpb-sales-between-popup-delay', 'type' => 'number', 'defaultValue' => 5);
		$options[] = array('name' => 'sgpb-auto-close-time-recent-sales', 'type' => 'number', 'defaultValue' => 3);
		$options[] = array('name' => 'sgpb-sales-content', 'type' => 'textarea', 'defaultValue' => __('[name] from [country] Purchased [title] [time]', SG_POPUP_TEXT_DOMAIN));

		return $options;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_RECENT_SALES] = SGPB_RECENT_SALES_AVALIABLE_VERSION;


		return $popupType;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_RECENT_SALES] = SGPB_RECENT_SALES_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_RECENT_SALES] = __('Recent Sales', SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}

	public function hidePageBuilderEditButtons($popupTypes = array())
	{
		$popupTypes[] = SGPB_POPUP_TYPE_RECENT_SALES;

		return $popupTypes;
	}
}
