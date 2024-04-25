<?php
namespace sgpbrs;
use sgpb\SGPopup;
class AjaxRecentSales
{
	private $postData;

	public function __construct()
	{
		$this->actions();
	}

	public function actions()
	{
		add_action('wp_ajax_sgpb_orders_status_lists', array($this, 'changeSource'));
	}

	public function changeSource()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$popupData = $_POST;
		$allStatuses = array();

		if (isset($popupData['source'])) {
			if ($popupData['source'] == SGPB_EDD_PLUGIN_SOURCE_KEY) {
				$allStatuses = edd_get_payment_statuses();
			}
			else if ($popupData['source'] == SGPB_WOO_PLUGIN_SOURCE_KEY) {
				$allStatuses = wc_get_order_statuses();
			}
		}

		echo json_encode($allStatuses);
		wp_die();
	}
}
