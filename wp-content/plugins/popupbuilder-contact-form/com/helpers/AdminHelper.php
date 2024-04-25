<?php
namespace sgpbcontactform;
use sgpb\SGPopup;

class AdminHelper
{
	public static function oldPluginDetected()
	{
		$hasOldPlugin = false;
		$message = '';

		$pbEarlyVersions = array(
			'popup-builder-silver',
			'popup-builder-gold',
			'popup-builder-platinum',
			'sg-popup-builder-silver',
			'sg-popup-builder-gold',
			'sg-popup-builder-platinum'
		);
		foreach ($pbEarlyVersions as $pbEarlyVersion) {
			$file = WP_PLUGIN_DIR.'/'.$pbEarlyVersion;
			if (file_exists($file)) {
				$pluginKey = $pbEarlyVersion.'/popup-builderPro.php';
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
				if (is_plugin_active($pluginKey)) {
					$hasOldPlugin = true;
					break;
				}
			}
		}

		if ($hasOldPlugin) {
			$message = __("You're using an old version of Popup Builder plugin. We have a brand-new version that you can download from your popup-builder.com account. Please, install the new version of Popup Builder plugin to be able to use it with the new extensions.", 'popupBuilder').'.';
		}

		$result = array(
			'status' => $hasOldPlugin,
			'message' => $message
		);

		return $result;
	}

	/*
	 * check allow to install current extension
	 */
	public static function isSatisfyParameters()
	{
		$hasOldPlugin = AdminHelper::oldPluginDetected();

		if (@$hasOldPlugin['status'] == true) {
			return array('status' => false, 'message' => @$hasOldPlugin['message']);
		}

		return array('status' => true, 'message' => '');
	}

	public static function hex2rgb($colour, $opacity = 1)
	{
		if ($colour[0] == '#') {
			$colour = substr($colour, 1);
		}
		if (strlen($colour) == 6) {
				list($r, $g, $b) = array($colour[0].$colour[1], $colour[2].$colour[3], $colour[4].$colour[5]);
		}
		elseif (strlen($colour) == 3 ) {
				list($r, $g, $b) = array($colour[0].$colour[0], $colour[1].$colour[1], $colour[2].$colour[2]);
		}
		else {
				return false;
		}
		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);

		return 'rgba('.$r.', '.$g.', '.$b.', '.$opacity.')';
	}

	public static function collectDataIntoArray($submittedData = '')
	{
		if (empty($submittedData)) {
			return $submittedData;
		}

		$collectedDataArray = array();
		$popupOptions = SGPopup::getPopupOptionsById($submittedData['popupId']);
		$subscriptionFieldsJson = @$popupOptions['sgpb-contact-fields-json'];
		$subscriptionFields = json_decode($subscriptionFieldsJson, true);
		if (empty($subscriptionFields)) {
			return $collectedDataArray;
		}

		$popupObj = SGPopup::find($submittedData['popupId']);
		$obj = CFFormCreator::createCFFormObj($popupObj);
		if (!is_object($obj)) {
			return '';
		}
		$showSettings = $obj->getFieldNameAndValues($submittedData);

		return $showSettings;
	}

	public static function collectAjaxSubmittedData($submittedData)
	{
		if (empty($submittedData)) {
			return $submittedData;
		}

		$collectedDataArray = array();
		$popupOptions = SGPopup::getPopupOptionsById($submittedData['popupId']);
		$subscriptionFieldsJson = @$popupOptions['sgpb-contact-fields-json'];
		$subscriptionFields = json_decode($subscriptionFieldsJson, true);

		if (empty($subscriptionFields)) {
			return $collectedDataArray;
		}

		$popupObj = SGPopup::find($submittedData['popupId']);
		$obj = CFFormCreator::createCFFormObj($popupObj);
		if (!is_object($obj)) {
			return '';
		}
		$showSettings = $obj->getFieldNameAndValuesFromSavedData($submittedData, $subscriptionFields);

		return $showSettings;

	}

	public static function getAllContactPopups()
	{
		$popupArgs = array();
		$popupArgs['type'] = 'contactForm';
		$allPopups = SGPopup::getAllPopups($popupArgs);

		return $allPopups;
	}

	public static function getAllContactForms()
	{
		$contactFormList = array();
		$contactForms = self::getAllContactPopups();

		foreach ($contactForms as $subscriptionForm) {
			$title = $subscriptionForm->getTitle();
			$id = $subscriptionForm->getId();
			if ($title == '') {
				$title = '('.__('no title', SG_POPUP_TEXT_DOMAIN).')';
			}
			$contactFormList[$id] = $title;
		}

		return $contactFormList;
	}

	public static function getAllContactedDate()
	{
		$subsDateList = array();
		global $wpdb;
		$subscriptionPopups = $wpdb->get_results('SELECT id, cDate FROM '.$wpdb->prefix.SGPB_CONTACTED_SUBSCRIBERS_TABLE_NAME, ARRAY_A);

		foreach ($subscriptionPopups as $subscriptionForm) {
			$date = substr($subscriptionForm['cDate'], 0, 7);
			$subsDateList[$date] = self::getFormattedDate($date);
		}

		$uniqueDates = array_unique($subsDateList, SORT_REGULAR);

		return $uniqueDates;
	}

	public static function getFormattedDate($date)
	{
		$date = strtotime($date);
		$month = date('F', $date);
		$year = date('Y', $date);

		return $month.' '.$year;
	}

	public static function filterQuery(&$query) {

		$searchQuery = '';

		if (isset($_GET['sgpb-contact-date']) && !empty($_GET['sgpb-contact-date'])) {
			$filterCriteria = esc_sql($_GET['sgpb-contact-date']);
			if ($filterCriteria != 'all') {
				if ($searchQuery != '') {
					$searchQuery .= ' AND ';
				}
				$searchQuery .= " cDate LIKE '$filterCriteria%'";
			}
		}
		if (!empty($_GET['sgpb-contact-popup-id']) && $_GET['sgpb-contact-popup-id'] != 'all') {
			if ($searchQuery != '') {
				$searchQuery .= ' AND ';
			}
			$searchQuery .='popupId = '.$_GET['sgpb-contact-popup-id'];
		}
		if (isset($_GET['s']) && !empty($_GET['s'])) {
			$searchCriteria = esc_sql($_GET['s']);
			$searchQuery .= " (email LIKE '%$searchCriteria%')";
		}

		if (!empty($searchQuery)) {
			$query .= ' WHERE '.$searchQuery;
		}
	}

	public static function contactersRelatedQuery($query = '', $additionalColumn = '')
	{
		global $wpdb;
		$contactersTablename = $wpdb->prefix.SGPB_CONTACTED_SUBSCRIBERS_TABLE_NAME;
		$postsTablename = $wpdb->prefix.SGPB_POSTS_TABLE_NAME;

		if ($query == '') {
			$query = 'SELECT email, cDate, '.$additionalColumn.' '.$postsTablename.'.post_title AS contactFormTitle FROM '.$contactersTablename.' ';
		}

		$filterCriteria = '';

		$query .= ' LEFT JOIN '.$postsTablename.' ON '.$postsTablename.'.ID='.$contactersTablename.'.popupId';

		if (isset($_GET['sgpb-contact-popup-id']) && !empty($_GET['sgpb-contact-popup-id'])) {
			$filterCriteria = esc_sql($_GET['sgpb-contact-popup-id']);
			if ($filterCriteria != 'all') {
				$searchQuery .= " (popupId = $filterCriteria)";
			}
		}
		if ($filterCriteria != '' && $filterCriteria != 'all' && isset($_GET['s']) && !empty($_GET['s'])) {
			$searchQuery .= ' AND ';
		}
		if (isset($_GET['s']) && !empty($_GET['s'])) {
			$searchCriteria = esc_sql($_GET['s']);
			$lastPartOfTheQuery = substr($searchQuery, -5);
			if (strpos($lastPartOfTheQuery, 'AND') <= 0) {
				$searchQuery .= ' AND ';
			}
			$searchQuery .= "(email LIKE '%$searchCriteria%' or $postsTablename.post_title LIKE '%$searchCriteria%')";
		}
		if (isset($_GET['sgpb-contact-date']) && !empty($_GET['sgpb-contact-date'])) {
			$filterCriteria = esc_sql($_GET['sgpb-contact-date']);
			if ($filterCriteria != 'all') {
				if ($searchQuery != '') {
					$searchQuery .= ' AND ';
				}
				$searchQuery .= " cDate LIKE '$filterCriteria%'";
			}
		}
		if ($searchQuery != '') {
			$query .= " WHERE $searchQuery";
		}

		return $query;
	}
}
