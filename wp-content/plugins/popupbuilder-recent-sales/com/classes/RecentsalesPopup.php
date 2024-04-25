<?php
namespace sgpb;
use sgpbrs\DefaultOptionsData;
use sgpbrs\AdminHelper as AdminHelperRecentSales;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class RecentSalesPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbRecentSalesAdminCssFiles', array($this, 'popupAdminCssFilter'), 1, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'));
	}

	/**
	 * It returns what the current post supports (for example: title, editor, etc...)
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function getPopupTypeSupports()
	{
		return array('title');
	}

	public static function allowToOpen($options, $args)
	{
		$popupObj = @$args['popupObj'];

		$source = $options['sgpb-sales-source'];
		$orders = $popupObj->getOrdersBySource($source);

		return $orders;
	}

	public function recentSalesEvent($events, $popupObj)
	{
		if ($popupObj->getType() != 'recentSales') {
			return $events;
		}
		$initialDelay = $this->getOptionValue('sgpb-sales-initial-delay');
		if (empty($initialDelay)) {
			return $events;
		}
		$recentSalesDefaultEvent = array();
		$recentSalesDefaultEvent[] = array('param' => 'recentSales', 'value' => $initialDelay);

		return $recentSalesDefaultEvent;
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$popupType = AdminHelper::getCurrentPopupType();
		if ($popupType != SGPB_POPUP_TYPE_RECENT_SALES) {
			return $defaultOptions;
		}

		$changingOptions = array(
			'sgpb-popup-fixed' => array('name' => 'sgpb-popup-fixed', 'type' => 'checkbox', 'defaultValue' => 'on'),
			'sgpb-popup-fixed-position' => array('name' => 'sgpb-popup-fixed-position', 'type' => 'text', 'defaultValue' => 7),
			'sgpb-auto-close-time' => array('name' => 'sgpb-auto-close-time', 'type' => 'number', 'defaultValue' => 5),
			'sgpb-open-animation' => array('name' => 'sgpb-open-animation', 'type' => 'checkbox', 'defaultValue' => 'on'),
			'sgpb-open-animation-speed' => array('name' => 'sgpb-open-animation-speed', 'type' => 'text', 'defaultValue' => 1)
		);

		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		return $defaultOptions;
	}

	public function recentSalesFrontendJsFilter($jsFiles)
	{
		$isActive = $this->getOptionValue('sgpb-is-active', true);
		if (!$isActive) {
			return $jsFiles;
		}
		$popupId = $this->getId();

		$jsFiles[] = array('folderUrl' => SGPB_RECENT_SALES_JS_URL, 'filename' => 'RecentSales.js', 'dep' => array('PopupBuilder.js'));

		$localizeData[] = array(
			'handle' => 'RecentSales.js',
			'name' => 'SgpbRecentSalesPopupData',
			'data' => $this->getRecentSalesPopupData($popupId)
		);

		$localizeData[] = array(
			'handle' => 'RecentSales.js',
			'name' => 'SgpbRecentSalesShortcodes',
			'data' => $this->supportedShortcodes()
		);

		$localizeData[] = array(
			'handle' => 'RecentSales.js',
			'name' => 'SgpbRecentSalesPopupType',
			'data' => SGPB_POPUP_TYPE_RECENT_SALES
		);

		$localizeData[] = array(
			'handle' => 'RecentSales.js',
			'name' => 'SgpbRecentSalesTextLocalization',
			'data' => array(
				'about' => __('about', SG_POPUP_TEXT_DOMAIN),
				'ago' => __('ago', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$jsFiles = array(
			'jsFiles' => $jsFiles,
			'localizeData' => $localizeData
		);

		return $jsFiles;
	}

	public function popupAdminJsFilter($jsFiles)
	{
		return $jsFiles;
	}

	public function popupAdminCssFilter($cssFiles)
	{
		$cssFiles[] = array(
			'folderUrl'=> SGPB_RECENT_SALES_CSS_URL,
			'filename' => 'recentSalesAdmin.css'
		);

		return $cssFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeOptionsView()
	{
		return array();
	}

	public function getPopupTypeMainView()
	{
		return array(
			'filePath' => SGPB_RECENT_SALES_VIEWS_PATH.'mainView.php',
			'metaboxTitle' => __('Recent Sales Settings', SG_POPUP_TEXT_DOMAIN),
			'short_description' => 'Select the desired options and trigger popup based on your recent sales'
		);
	}

	private function frontendFilters()
	{
		add_filter('sgpbRecentSalesFrontendJs', array($this, 'recentSalesFrontendJsFilter'), 1, 1);
		add_filter('sgpbPopupEvents', array($this, 'recentSalesEvent'), 1, 2);
		add_filter('sgpbPopupRenderOptions', array($this, 'renderOptionsFilter'), 1, 2);
	}

	public function renderOptionsFilter($options)
	{
		$removeOptions = $this->getRemoveOptions();
		if (empty($removeOptions)) {
			return $options;
		}

		foreach ($removeOptions as $name => $value) {
			$options[$name] = false;
		}

		return $options;
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$popupId = $this->getId();
		$data = $this->getRecentSalesPopupData($popupId);

		if (!empty($data)) {
			$data = json_encode($data);
		}

		echo '<div class="sgpb-recent-sales-popup-data-'.$popupId.'" data-params="'.esc_attr($data).'"></div>';

		return $this->getOptionValue('sgpb-sales-content');
	}

	public function getExtraRenderOptions()
	{
		return array(
			'sgpb-popup-dimension-mode' => 'customMode',
			'sgpb-width' => '300px',
			'sgpb-height' => '60px'
		);
	}

	public function getRecentSalesPopupData($popupId = 0)
	{
		$source = $this->getOptionValue('sgpb-sales-source');
		$orders = $this->getOrdersBySource($source);
		$popupId = $this->getId();
		$content = $this->getOptionValue('sgpb-sales-content');
		$recentSalesData = array(
			'popupId' => $popupId,
			'content' => $content,
			'orders' => $orders
		);

		return $recentSalesData;
	}

	public function getOrdersBySource($source = 'woocommerce')
	{
		$orders = array();
		$count = $this->getOptionValue('sgpb-sales-popup-count');
		$statusLists = $this->getOptionValue('sgpb-orders-status-lists');
		if (empty($statusLists)) {
			$statusLists = 'completed';
		}
		if (!isset($count)) {
			$count = SGPB_DEFAULT_RECENT_SALES_POPUP_COUNT;
		}
		$args = array(
			'limit' => $count,
			'status' => $statusLists,
			'orderby' => 'date',
			'order' => 'DESC'
		);

		if ($source == 'woocommerce') {
			$orders = $this->getWooCommerceOrders($args);
			$orders = $this->prepareWooCommerceOrders($orders);
		}
		if ($source == SGPB_EDD_PLUGIN_SOURCE_KEY) {
			// payment status publish it is mean payment has been completed
			$args['status'] = 'publish';

			$payments = $this->getEddOrders($args);
			$orders = $this->prepareEddCommerceOrders($payments);
		}

		return $orders;
	}

	private function getEddOrders($args)
	{
		if (function_exists('edd_get_payments')) {
			return edd_get_payments($args);
		}

		return array();
	}

	public function getRemoveOptions()
	{
		// Where 1 mean this options must not show for this popup type
		$removeOptions = array(
			'sgpb-auto-close' => 1,
			'sgpb-auto-close-time' => 1,
			'sgpb-enable-content-scrolling' => 1,
			'sgpb-disable-page-scrolling' => 1,
			'sgpb-reopen-after-form-submission' => 1,
			'sgpb-popup-delay' => 1,// custom event delay
			'sgpb-show-popup-same-user' => 1,
			'sgpb-content-padding' => 1,
			'sgpb-enable-popup-overlay' => 1,
			'sgpb-background-image' => 1,
			'sgpb-background-image-mode' => 1,
			'sgpb-popup-z-index' => 1,
			'sgpb-popup-order' => 1,
			'content-copy-to-clipboard' => 1,
			'sgpb-esc-key' => 1,
			'sgpb-close-button-delay' => 1,
			'sgpb-close-button-position' => 1
		);

		return $removeOptions;
	}

	public function getWooCommerceOrders($args = array())
	{
		if (function_exists('wc_get_orders')) {
			return wc_get_orders($args);
		}

		return array();
	}

	public function prepareWooCommerceOrders($orders = array())
	{
		$filteredOrders = array();
		$allCountries = AdminHelperRecentSales::countriesIsoData();

		if (empty($orders)) {
			return false;
		}
		$imageType = $this->getOptionValue('sgpb-sales-image-type');
		$customImage = $this->getOptionValue('sgpb-sales-image');

		foreach ($orders as $index => $order) {
			$name = '';
			$country = '';
			$city = '';
			$title = '';
			$firstName = '';
			$lastName = '';

			$orderData = $order->get_data();
			if (empty($orderData) || empty($orderData['billing'])) {
				continue;
			}
			// get username
			$customerId = (int)$orderData['customer_id'];
			$customer = get_user_by('id', $customerId);
			if (!empty($customer)) {
				$name .= $customer->user_nicename;
			}
			if ($name == '') {
				$name = @$orderData['billing']['first_name'].' '.@$orderData['billing']['last_name'];
			}
			$firstName = @$orderData['billing']['first_name'];
			$lastName = @$orderData['billing']['last_name'];

			$filteredOrders[$index]['name'] = $name;

			// get country
			if (isset($orderData['billing']['country'])) {
				$countryIndex = $orderData['billing']['country'];
				// get country by iso (AM, RU, US ...)
				if (isset($allCountries[$countryIndex])) {
					$country .= $allCountries[$countryIndex];
				}
			}
			if (!$country) {
				$country = $orderData['billing']['state'];
			}
			$filteredOrders[$index]['country'] = $country;
			$orderItems = $order->get_items();
			if (empty($orderItems)) {
				continue;
			}

			// get city
			if (isset($orderData['billing']['city'])) {
				$city = $orderData['billing']['city'];
			}
			$filteredOrders[$index]['city'] = $city;
			$orderItems = $order->get_items();
			if (empty($orderItems)) {
				continue;
			}

			$productObj = reset($orderItems);
			if (empty($productObj) || !($productObj instanceof \WC_Order_Item_Product)) {
				continue;
			}
			$productData = $productObj->get_data();
			$orderId = $productData['order_id'];
			$itemId = 0;
			foreach ($orderItems as $item) {
				if ($item instanceof \WC_Order_Item_Product) {
					$itemId = $item->get_product_id();
					break;
				}
			}
			if (isset($productData['name'])) {
				$title .= $productData['name'];
			}
			if ($imageType == 'buyer') {
				$email = $orderData['billing']['email'];
				$image = get_avatar_data($email);
				$image = $image['url'];
			}
			else if ($imageType == 'product') {
				$image = wp_get_attachment_image_src(get_post_thumbnail_id($itemId));
				if (isset($image[0])) {
					$image = $image[0];
				}
				else {
					$image = DefaultOptionsData::getDefaultProductImage();
				}
			}
			else if ($imageType == 'custom') {
				if (!$customImage) {
					$customImage = DefaultOptionsData::getDefaultCustomImage();
				}
				$image = $customImage;
			}
			$filteredOrders[$index]['image'] = $image;
			$filteredOrders[$index]['title'] = $title;
			$filteredOrders[$index]['firstName'] = $firstName;
			$filteredOrders[$index]['lastName'] = $lastName;
			$date = (array)$orderData['date_created'];
			$date = $date['date'];
			$date = human_time_diff(strtotime($date), current_time('timestamp'));
			$filteredOrders[$index]['time'] = $date;
		}

		return array_values($filteredOrders);
	}

	private function prepareEddCommerceOrders($orders)
	{
		$filteredOrders = array();
		$allCountries = AdminHelperRecentSales::countriesIsoData();

		if (empty($orders)) {
			return false;
		}

		$imageType = $this->getOptionValue('sgpb-sales-image-type');
		$customImage = $this->getOptionValue('sgpb-sales-image');

		foreach ($orders as $index => $order) {

			$orderId = $order->ID;
			$orderData = edd_get_payment($orderId);

			if (empty($orderData)) {
				continue;
			}

			// get username
			$userName = '';
			$firstName = '';
			$lastName = '';
			if (!empty($orderData->user_info)) {
				$userInfo = $orderData->user_info;
				if (!empty($userInfo['first_name'])) {
					$userName .= $userInfo['first_name'];
					$firstName .= $userInfo['first_name'];
				}

				if (!empty($userInfo['last_name'])) {
					$userName .=  ' '.$userInfo['last_name'];
					$lastName .=  ' '.$userInfo['last_name'];
				}
			}
			$filteredOrders[$index]['name'] = $userName;
			$filteredOrders[$index]['firstName'] = $firstName;
			$filteredOrders[$index]['lastName'] = $lastName;

			// get country
			$countryName = __('Unknown country', SG_POPUP_TEXT_DOMAIN);
			if (!empty($orderData->address)) {
				$addressData = $orderData->address;
				$countryIndex = $addressData['country'];
				// get country by iso (AM, RU, US ...)
				if (!empty($allCountries[$countryIndex])) {
					$countryName = $allCountries[$countryIndex];
				}
			}

			$filteredOrders[$index]['country'] = $countryName;

			$paymentMeta = $orderData->payment_meta;
			$downloads = array();
			if (!empty($paymentMeta)) {
				$downloads = $paymentMeta['downloads'];
			}
			if (empty($downloads[0]['id'])) {
				continue;
			}
			$productPostId = $downloads[0]['id'];
			$title = get_the_title($productPostId);

			if ($imageType == 'buyer' && !empty($paymentMeta['email'])) {
				$email = $paymentMeta['email'];
				$image = get_avatar_data($email);
				$image = $image['url'];
			}
			else if ($imageType == 'product') {
				$image = wp_get_attachment_image_src(get_post_thumbnail_id($productPostId));
				if (isset($image[0])) {
					$image = $image[0];
				}
				else {
					$image = DefaultOptionsData::getDefaultProductImage();
				}
			}
			else if ($imageType == 'custom') {
				if (!$customImage) {
					$customImage = DefaultOptionsData::getDefaultCustomImage();
				}
				$image = $customImage;
			}
			$filteredOrders[$index]['image'] = $image;
			$filteredOrders[$index]['title'] = $title;
			$date = $paymentMeta['date'];
			$date = human_time_diff(strtotime($date), current_time('timestamp'));
			$filteredOrders[$index]['time'] = $date;
		}

		return array_values($filteredOrders);
	}

	public function supportedShortcodes()
	{
		$shortcodes = array(
			'name',
			'firstName',
			'lastName',
			'country',
			'city',
			'title',
			'time',
			'image'
		);

		return $shortcodes;
	}
}
