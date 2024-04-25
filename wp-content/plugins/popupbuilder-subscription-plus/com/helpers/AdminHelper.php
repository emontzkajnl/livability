<?php
namespace sgpbsubscriptionplus;
use sgpb\SGPopup;
use sgpb\AdminHelper;
use sgpbform\FormCreator;

class SubscriptionPlusAdminHelper
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
		$hasOldPlugin = self::oldPluginDetected();

		if (@$hasOldPlugin['status'] == true) {
			return array('status' => false, 'message' => @$hasOldPlugin['message']);
		}

		return array('status' => true, 'message' => '');
	}

	public static function getOption($optionName)
	{
		$defaultData = self::getDefaultDataByName($optionName);
		$savedData = Autoresponder::getData();
		$optionValue = null;

		if (!isset($defaultData['type'])) {
			if ($defaultData == ''){
				$defaultData = array('type' => 'string');
			} else {
				@$defaultData['type'] = 'string';
			}
		}

		if (!empty($savedData)) { //edit mode
			if (isset($savedData[$optionName])) { //option exists in the database
				$optionValue = $savedData[$optionName];
			}
			/* if it's a checkbox, it may not exist in the db
			 * if we don't care about it's existance, return empty string
			 * otherwise, go for it's default value
			 */
			else if ((isset($defaultData['type']) && $defaultData['type'] == 'checkbox') && !$forceDefaultValue) {
				$optionValue = '';
			}
		}

		if ($optionValue === null && !empty($defaultData['defaultValue'])) {
			$optionValue = $defaultData['defaultValue'];
		}

		if (isset($defaultData['type']) && $defaultData['type'] == 'checkbox') {
			$optionValue = self::boolToChecked($optionValue);
		}

		if (isset($defaultData['type']) && $defaultData['type'] == 'number' && $optionValue == 0) {
			$optionValue = 0;
		}

		return $optionValue;
	}

	public static function getDefaultDataByName($optionName)
	{
		$defaultValue = '';
		$defaultOptions = array();

		$defaultOptions[] = array('name' => 'sgpb-subs-enable-email-notifications', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-subs-notifications-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$defaultOptions[] = array('name' => 'sgpb-autoresponder-subject', 'type' => 'text', 'defaultValue' => __('Popup Builder', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-autoresponder-from-name', 'type' => 'text', 'defaultValue' => __('Admin', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-autoresponder-from-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$defaultOptions[] = array('name' => 'sgpb-autoresponder-reply-to', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		global $SGPB_DATA_CONFIG_ARRAY;

		$specialEventsDefaultData = array($SGPB_DATA_CONFIG_ARRAY['autoresponder-events']['initialData']);
		$defaultOptions[] = array('name' => 'sgpb-autoresponder-events', 'type' => 'text', 'defaultValue' => $specialEventsDefaultData);

		foreach ($defaultOptions as $option) {
			if (isset($option['name']) && $option['name'] == $optionName) {
				if (!isset($option['defaultValue'])) {
					continue;
				}
				$defaultValue = $option;
			}
		}

		return $defaultValue;
	}

	public static function boolToChecked($var)
	{
		return ($var?'checked':'');
	}

	public static function getListsIdAndTitle($filters = array())
	{
		$allPopups = SGPopup::getAllPopups($filters);
		$popupIdTitles = array();

		if (empty($allPopups)) {
			return $popupIdTitles;
		}

		foreach ($allPopups as $popup) {
			if (empty($popup)) {
				continue;
			}
			$id = $popup->getId();

			$title = $popup->getTitle();
			$type = $popup->getType();
			if (isset($filters['type'])) {
				if ($type != $filters['type']) {
					continue;
				}
			}

			$popupIdTitles[$id] = $title;
		}

		return $popupIdTitles;
	}

	public static function getSubscriberDataById($id)
	{
		global $wpdb;
		$result = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE id='.$id, ARRAY_A);

		return $result;
	}

	public static function getAllSubscribersByPopupId($popupId)
	{
		global $wpdb;
		$query = $wpdb->prepare('SELECT id, firstName, lastName, email FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE subscriptionType = %d', $popupId);
		$subscribers = $wpdb->get_results($query, ARRAY_A);

		return $subscribers;
	}

	public static function collectDataIntoArray($submittedData = '')
	{
		if (empty($submittedData)) {
			return $submittedData;
		}

		$collectedDataArray = array();
		$popupOptions = SGPopup::getPopupOptionsById($submittedData['popupId']);
		$subscriptionFieldsJson = isset($popupOptions['sgpb-subscription-fields-json']) ? $popupOptions['sgpb-subscription-fields-json'] : '';
		$subscriptionFields = json_decode($subscriptionFieldsJson, true);

		if (empty($subscriptionFields)) {
			return $collectedDataArray;
		}

		$popupObj = SGPopup::find($submittedData['popupId']);
		$obj = FormCreator::createSubscriptionFormObj($popupObj);
		if (!is_object($obj)) {
			return '';
		}
		$showSettings = $obj->getFieldNameAndValues($submittedData);

		return json_encode($showSettings);
	}

	public static function getPopupTypeName()
	{
		$popupType = 'html';

		if (!empty($_GET['post'])) {
			$popupId = $_GET['post'];
			$popupOptionsData = SGPopup::getPopupOptionsById($popupId);
			if (!empty($popupOptionsData['sgpb-type'])) {
				$popupType = $popupOptionsData['sgpb-type'];
			}
		}
		else if (!empty($_GET['sgpb_type'])) {
			$popupType = $_GET['sgpb_type'];
		}

		return $popupType;
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

	public static function getAllEvents()
	{
		$events = array(
			'subscription' => __('After successful subscription', SG_POPUP_TEXT_DOMAIN),
			SGPB_AUTORESPONDER_EVENT_PAGE_CREATION => __('After new page creation', SG_POPUP_TEXT_DOMAIN)
		);

		return $events;
	}

	public static function getCategoriesByPostType($postType = '')
	{
		$categories = array();
		$catsParams = array();
		$supportedTaxonomies = get_object_taxonomies($postType);
		foreach ($supportedTaxonomies as $taxonomy) {
			$usedTaxonomies = get_taxonomies(array('name' => $taxonomy, 'public' => true));
			$usedTaxonomies = key($usedTaxonomies);
			if ($usedTaxonomies) {
				if (!isset($catsParams[$usedTaxonomies])) {
					$catsParams[$usedTaxonomies] = $usedTaxonomies;
				}
			}
		}
		foreach ($catsParams as $singleTaxonomy) {
			$terms = get_terms(array(
				'taxonomy' => $singleTaxonomy,
				'hide_empty' => false,
			));

			foreach ($terms as $term) {
				$categories[$term->term_id] = $term->name;
			}
		}

		return $categories;
	}

	public static function findSubscribersByEmail($subscriberEmail = '', $list = 0)
	{
		global $wpdb;
		$subscriber = array();

		$prepareSql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE email = %s AND subscriptionType = %d ', $subscriberEmail, $list);
		$subscriber = $wpdb->get_row($prepareSql, ARRAY_A);
		if (!$list) {
			$prepareSql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE email = %s ', $subscriberEmail);
			$subscriber = $wpdb->get_results($prepareSql, ARRAY_A);
		}

		return $subscriber;
	}

	public static function arrayUnshiftAssoc(&$arr, $key, $val)
	{
		$arr = array_reverse($arr, true);
		$arr[$key] = $val;
		$arr = array_reverse($arr, true);

		return $arr;
	}

	public static function getAllTags()
	{
		$allTags = array();
		$tags = get_tags(array(
			'hide_empty' => false
		));

		foreach ($tags as $tag) {
			$allTags[$tag->slug] = $tag->name;
		}

		return $allTags;
	}

	public static function getCustomPostCategories($postTypeName)
	{
		$taxonomyObjects = get_object_taxonomies($postTypeName);
		if ($postTypeName == 'product') {
			$taxonomyObjects = array('product_cat');
		}
		$categories = self::getPostsAllCategories($postTypeName, $taxonomyObjects);

		return $categories;
	}

	public static function getPostsAllCategories($postType = 'post', $taxonomies = array())
	{

		$cats =  get_terms(
			array(
				'hide_empty' => 0,
				'type'      => $postType,
				'orderby'   => 'name',
				'number'    => 50,
				'order'     => 'ASC'
			)
		);
		$supportedTaxonomies = array('category');
		if (!empty($taxonomies)) {
			$supportedTaxonomies = $taxonomies;
		}

		$catsParams = array();
		foreach ($cats as $cat) {
			if (isset($cat->taxonomy)) {
				if (!in_array($cat->taxonomy, $supportedTaxonomies)) {
					continue;
				}
			}
			$id = $cat->term_id;
			$name = $cat->name;
			$catsParams[$id] = $name;
		}

		return $catsParams;
	}

	public static function getAllCustomPosts()
	{
		$args = array(
			'public' => true,
			'_builtin' => false
		);

		$allCustomPosts = get_post_types($args);
		$allCustomPosts = array('post') + $allCustomPosts;

		if (isset($allCustomPosts[SG_POPUP_POST_TYPE])) {
			unset($allCustomPosts[SG_POPUP_POST_TYPE]);
		}

		return $allCustomPosts;
	}

	public static function getCustomFormFieldsByPopupId($popupId)
	{
		$customShortcodes = array();
		$neededFields = array();
		$popupOptions = SGPopup::getPopupOptionsById($popupId);
		$subscriptionFieldsJson = @$popupOptions['sgpb-subscription-fields-json'];
		$subscriptionFields = json_decode($subscriptionFieldsJson, true);
		if (empty($subscriptionFields)) {
			return $customShortcodes;
		}
		$i = 0;
		foreach ($subscriptionFields as $field) {
			if (isset($field['type']) && $field['type'] == 'submit') {
				continue;
			}
			/*$field['sortNumber'] = $i;
			$field['shortcode'] = $field['attrs']['name'].'-'.$i;
			$neededFields[] = $field;
			$i++;*/
			$neededFields[] = $field;
		}

		return $neededFields;
	}

	public static function getSubscriberDetails($subscriberId, $popupId)
	{
		$subscriberData = self::getSubscriberDataById($subscriberId);
		$defaultData = array(
			'label' => __('Email', SG_POPUP_TEXT_DOMAIN),
			'value' => @$subscriberData['email']
		);
		parse_str($subscriberData['submittedData'], $subscriberSubmittedData);
		$subscriberSubmittedData['popupId'] = $popupId;

		$result = self::collectDataIntoArray($subscriberSubmittedData);

		if (!empty($result)) {
			return $result;
		}

		return json_encode($defaultData);
	}

	public static function getJsLocalizedData()
	{
		$data = array(
			'message'   => __('Can not change the current autoresponder status.', SG_POPUP_TEXT_DOMAIN)
		);

		return $data;
	}

	public static function getCustomFieldsShortcodes($popupId, $subscriberId = 0)
	{
		$submissionData = @$_POST['formData'];
		if (empty($submissionData)) {
			$submissionData = self::getSubscriberDataById($subscriberId);
			if (empty($submissionData['submittedData'])) {
				return array();
			}
			$submissionData = $submissionData['submittedData'];
		}
		parse_str($submissionData, $data);
		$data['popupId'] = $popupId;
		$data = self::collectDataIntoArray($data);
		if (!empty($data)) {
			$data = json_decode($data, true);
		}
		$shortcodesArray = array();
		foreach ($data as $key => $value) {
			$name = $key;
			$shortcode = str_replace(' ', '', $key);
			$shortcodesArray[$name] = array(
				'shortcode' => $shortcode,
				'value' => $value
			);
		}

		return $shortcodesArray;
	}

	public static function pushCustomFieldsShortcodesIntoTemplate($templatePath, $popupId, $subscriberId = 0)
	{
		// append shortcodes
		$shortcodesArray = self::getCustomFieldsShortcodes($popupId, $subscriberId);
		$fileContent = file($templatePath);

		$shortcodes = array();
		$replaceMaping = array();
		foreach ($shortcodesArray as $key => $value) {
			$shortcodes[] = '<p><b>'.$key.':<b> ['.$value['shortcode'].']';
			$replaceMaping['/\['.$value['shortcode'].']/'] = $value['value'];
		}

		$searchword = 'custom fields here';
		foreach ($fileContent as $lineNumber => $line) {
			if (strpos($line, $searchword)) {
				break;
			}
		}
		$res = array();
		$fileContent1 = array_slice($fileContent, 0, $lineNumber);
		$fileContent2 = array_slice($fileContent, $lineNumber);
		$res = array_merge($fileContent1, $shortcodes, $fileContent2);

		$newSubscriptionEmailTemplate = implode(' ', $res);

		foreach ($replaceMaping as $key => $value) {
			$newSubscriptionEmailTemplate = preg_replace($key, $value, $newSubscriptionEmailTemplate);
		}

		return $newSubscriptionEmailTemplate;
	}

	public static function collectConfirmArgs()
	{
		global $wpdb;
		$noSubscriber = true;

		if (!isset($_GET['sgpbEmailConfirm'])) {
			return false;
		}

		$args = array();
		if (isset($_GET['sgpbEmailConfirm'])) {
			$args['token'] = $_GET['sgpbEmailConfirm'];
		}
		if (isset($_GET['email'])) {
			$args['email'] = $_GET['email'];
		}
		if (isset($_GET['popup'])) {
			$args['popup'] = $_GET['popup'];
		}

		$prepareSql = $wpdb->prepare('SELECT id FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE email = %s && subscriptionType = %s', $args['email'], $args['popup']);
		$res = $wpdb->get_row($prepareSql, ARRAY_A);
		if (!isset($res['id'])) {
			$noSubscriber = false;
		}
		$args['subscriberId'] = $res['id'];

		$subscriber = AdminHelper::subscriberExists($args);
		if ($subscriber && $noSubscriber) {
			return $args;
		}
		else if (!$noSubscriber) {
			_e('<span>Oops, something went wrong, please try again or contact the administrator to check more info.</span>', SG_POPUP_TEXT_DOMAIN);
			wp_die();
		}
	}
}

