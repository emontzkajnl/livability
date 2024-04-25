<?php
namespace sgpbsubscriptionplus;
use sgpb\AdminHelper;
use sgpbsubscriptionplus\SubscriptionPlusAdminHelper as SubscriptionPlusAdminHelper;
use sgpbform\FormCreator;

class Filters
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_filter('sgAutoresponderTargetParams', array($this, 'extendParams'), 1, 1);
		add_filter('sgAutoresponderEventsParams', array($this, 'addPopupTargetData'), 1, 1);
		add_filter('sgAutoresponderEventsColumnTypes', array($this, 'addPopupTargetTypes'), 1, 1);
		add_filter('sgAutoresponderEventsAttrs', array($this, 'addPopupTargetAttrs'), 1, 1);

		add_filter('sgpbPopupDefaultOptions', array($this, 'subscriptionPlusDefaultOptions'), 10, 1);
		add_filter('sgpbAdditionalMetaboxes', array($this ,'subscriptionPlusMetaboxes'), 100, 1);
		add_filter('admin_url', array($this, 'addNewTemplateUrl'), 10, 2);
		add_filter('default_content', array($this, 'addDefaultContentToTemplate'));
		add_filter('sgpbEmailTemplateHeader', array($this, 'templateHeader'), 10, 1);
		add_filter('sgpbEmailTemplateContent', array($this, 'templateContent'), 10, 1);
		add_filter('sgpbEmailTemplateFooter', array($this, 'templateFooter'), 10, 1);
		add_filter('sgpbSubscriptionForm', array($this, 'subscriptionForm'), 2, 1);
		add_filter('sgpbEditSubscribersTableRowValues', array($this, 'editSubscribersTableRowValues'), 10, 2);
		add_filter('sgpbAlterColumnIntoSubscribers', array($this, 'alterColumnIntoSubscribers'), 10, 1);
		add_filter('sgpbConfigArray', array($this, 'configArray'), 10, 1);
		add_filter('sgpbAdditionalConditionBuilder', array($this, 'conditionBuilder'), 100, 1);
		add_filter('sgpbGetSubscriptionLabels', array($this, 'getSubscriptionLabels'), 100, 2);
		add_filter('sgpbImportToSubscriptionList', array($this, 'importSubscriberToList'), 100, 3);
		add_filter('sgpNewsletterSendingSubscribers', array($this, 'newsletterSubscribers'), 100, 1);
		add_filter('sgpbNewsletterShortcodes', array($this, 'newsletterShortcodes'), 100, 3);
		add_filter('manage_'.SG_POPUP_AUTORESPONDER_POST_TYPE.'_posts_columns', array($this, 'autorespondersTableColumns'));
		add_filter('sgpbUserSelectionQuery', array($this, 'userSelectionQueryExtraAttributes'), 100, 1);
	}

    public function userSelectionQueryExtraAttributes($query)
    {
        $query .= ' and emailStatus = 1';

        return $query;
    }

	public function newsletterShortcodes($shortcodes = array(), $popupId = 0, $subscriberId = 0)
	{
		$submittedData = SubscriptionPlusAdminHelper::getSubscriberDetails($subscriberId, $popupId);
		$shortcodes['extraShortcodesWithValues'] = json_decode($submittedData, true);

		return $shortcodes;
	}

	public function newsletterSubscribers($subscribers = array())
	{
		foreach ($subscribers as $subscriber) {
			$subscriberData = SubscriptionPlusAdminHelper::getSubscriberDataById($subscriber['id']);
			if (empty($subscriberData['submittedData'])) {
				continue;
			}
			$newFieldsArray = array();
			$submittedData = @$subscriberData['submittedData'];
			parse_str($submittedData, $newFieldsArray);
			$newFieldsArray = array_values($newFieldsArray);
			$subscriber['submittedData'] = $newFieldsArray;
		}

		return $subscribers;
	}

	public function importSubscriberToList($csvFileArray, $mapping, $formId)
	{
		$currentMapping = $mapping;
		unset($currentMapping['date']);
		unset($currentMapping['popup']);
		foreach ($csvFileArray as $csvData) {
			global $wpdb;
			$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;
			$email = $csvData[$mapping['sgpb-email']];
			if (empty($email)) {
				$email = $csvData[$mapping['email']];
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				continue;
			}
			$date = $csvData[$mapping['date']];
			$date = date('Y-m-d', strtotime($date));
			$popup = $csvData[$mapping['popup']];
			$currentSavedData = '';
			$firstName = '';
			$lastName = '';

			foreach ($currentMapping as $name => $index) {
				// for the free version csv file
				if ($name == 'firstName') {
					$name = 'sgpb-first-name-'.$index;
				}
				if ($name == 'lastName') {
					$name = 'sgpb-last-name-'.$index;
				}
				if ($name == 'email') {
					$name = 'sgpb-email';
				}

				if (strpos($name, 'sgpb-first-') === 0) {
					$firstName = $csvData[$index];
				}
				if (strpos($name, 'sgpb-last-') === 0) {
					$lastName = $csvData[$index];
				}
				$currentSavedData .= $name.'='.$csvData[$index].'&';
			}
			$sql = $wpdb->prepare('INSERT INTO '.$subscribersTableName.' (firstName, lastName, email, cDate, subscriptionType, status, unsubscribed, submittedData) VALUES (%s, %s, %s, %s, %d, %d, %d, %s) ', $firstName, $lastName, $email, $date, $formId, 0, 0, $currentSavedData);
			$wpdb->query($sql);
		}

		return -1;
	}

	public function getSubscriptionLabels($data, $popup)
	{
		$savedJson = $popup->getOptionValue('sgpb-subscription-fields-json');
		$savedJson = json_decode($savedJson, true);

		$obj = FormCreator::createSubscriptionFormObj($popup);
		if (!is_object($obj)) {
			return '';
		}
		$showSettings = $obj->getFieldKeyAndLabel($savedJson, array('submit'));

		return $showSettings;
	}

	public function addPopupTargetData($targetData)
	{
		$allCustomPostTypes = SubscriptionPlusAdminHelper::getAllCustomPosts();

		foreach ($allCustomPostTypes as $customPostType) {
			$targetData[$customPostType.'_all'] = null;
			$targetData[$customPostType.'_categories'] = SubscriptionPlusAdminHelper::getCustomPostCategories($customPostType);
		}

		return $targetData;
	}

	public function addPopupTargetTypes($targetTypes)
	{
		$allCustomPostTypes = SubscriptionPlusAdminHelper::getAllCustomPosts();

		foreach ($allCustomPostTypes as $customPostType) {
			$targetTypes[$customPostType.'_selected'] = 'select';
			$targetTypes[$customPostType.'_categories'] = 'select';
		}

		return $targetTypes;
	}

	public function addPopupTargetAttrs($targetAttrs)
	{
		$allCustomPostTypes = SubscriptionPlusAdminHelper::getAllCustomPosts();

		foreach ($allCustomPostTypes as $customPostType) {
			$targetAttrs[$customPostType.'_categories']['htmlAttrs'] = array('class' => 'js-sg-select2 js-select-ajax', 'data-select-class' => 'js-select-ajax', 'isNotPostType' => true, 'data-value-param' => $customPostType, 'multiple' => 'multiple');
			$targetAttrs[$customPostType.'_categories']['infoAttrs'] = array('label' => __('Select ', SG_POPUP_TEXT_DOMAIN).$customPostType.' categories');
		}

		return $targetAttrs;
	}

	public function conditionBuilder($conditions = array())
	{
		$data = SubscriptionPlusAdminHelper::getOption('sgpb-autoresponder-events');
		$data['conditionName'] = 'autoresponder-events';

		$conditions[] = $data;

		return $conditions;
	}

	public function configArray($args)
	{
		$args['autoresponder-events'] = self::getBehaviorAfterSpecialEventsConfig();

		return $args;
	}

	private static function getBehaviorAfterSpecialEventsConfig()
	{
		$mainPostTypesArray = array();
		$postTypes = \ConfigDataHelper::getAllCustomPosts();
		$allLists = SubscriptionPlusAdminHelper::getListsIdAndTitle(array('type' => 'subscription'));

		if (empty($postTypes)) {
			$postTypes = array();
		}

		$targetDataOperator = array(
			'==' => __('Is', SG_POPUP_TEXT_DOMAIN)
		);

		$subscriptionOperator = array(
			'popupsIs' => __('Is', SG_POPUP_TEXT_DOMAIN)
		);

		$pageCreationOperator = array(
			'allPagesIs' => __('Is', SG_POPUP_TEXT_DOMAIN)
		);

		$columns = array(
			'param' => __('Autoresponder rule', SG_POPUP_TEXT_DOMAIN),
			'operator' => __('Post type', SG_POPUP_TEXT_DOMAIN),
			'value' => __('Category', SG_POPUP_TEXT_DOMAIN)
		);

		$columnTypes = array(
			'param' => 'select',
			'operator' => 'select',
			'value' => 'select',
			'post_categoryOperator' => 'select',
			'post_tags_ids' => 'select',
			'subscription' => 'select',
			'popupsIs' => 'select'
		);

		$targetParams = array(
			'select_event' => __('Select event', SG_POPUP_TEXT_DOMAIN),
			__('Special events', SG_POPUP_TEXT_DOMAIN) => array(
				'subscription' => __('After successful subscription', SG_POPUP_TEXT_DOMAIN),
				SGPB_AUTORESPONDER_EVENT_PAGE_CREATION => __('After new page creation', SG_POPUP_TEXT_DOMAIN)
			),
			'Tags' => array(
				'post_tags' => __('All tags', SG_POPUP_TEXT_DOMAIN),
				'post_tags_ids' => __('Selected tags', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$targetParams = apply_filters('sgAutoresponderTargetParams', $targetParams);

		$params = array(
			'param' => $targetParams,
			'operator' => $targetDataOperator
		);

		$initialData = array(
			array(
				'param' => 'select_event',
				'operator' => '=='
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
					'label' => __('Autoresponder rule', SG_POPUP_TEXT_DOMAIN),
					'info' => __('Specify when the Autoresponder email should be sent.', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'operator' => array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2 js-select-basic',
					'data-select-class' => 'js-select-basic',
					'data-select-type' => 'basic'
				),
				'infoAttrs' => array(
					'label' => __('Is', SG_POPUP_TEXT_DOMAIN),
					'info' => __('Allow or disallow autoresponder to work for the selected rule.', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'popupsIs' => array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2',
					'required' => 'required',
					'multiple' => 'multiple'
				),
				'infoAttrs' => array(
					'label' => __('Select popup', SG_POPUP_TEXT_DOMAIN),
					'info' => __('Select the popup that should be opened.', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'post_tags_ids' => array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2 js-select-ajax',
					'data-select-class' => 'js-select-ajax',
					'data-select-type' => 'multiple',
					'data-value-param' => 'postTags',
					'isNotPostType' => true,
					'multiple' => 'multiple'
				),
				'infoAttrs' => array(
					'label' => __('Select tags', SG_POPUP_TEXT_DOMAIN),
					'info' => __('Select the tags for new posts to send autoresponder', SG_POPUP_TEXT_DOMAIN)
				)
			),
			'subscription' => array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2 js-select-ajax',
					'data-select-class' => 'js-select-ajax',
					'data-select-type' => 'multiple',
					'data-value-param' => 'postTags',
					'isNotPostType' => true,
					'multiple' => 'multiple'
				),
				'infoAttrs' => array(
					'label' => __('Select the popup(s)', SG_POPUP_TEXT_DOMAIN)
				)
			)
		);

		$operators = array(
			array('operator' => 'add', 'name' => __('Add', SG_POPUP_TEXT_DOMAIN)),
			array('operator' => 'delete', 'name' => __('Delete', SG_POPUP_TEXT_DOMAIN))
		);

		$params['all_postsOperator'] = $targetDataOperator;
		$params['popupsIs'] = $allLists;
		$params['subscription'] = SubscriptionPlusAdminHelper::getListsIdAndTitle(array('type' => 'subscription'));
		$params['page_creationOperator'] = $pageCreationOperator;
		$params['post_tags_ids'] = apply_filters('sgAutoresponderTags', SubscriptionPlusAdminHelper::getAllTags());

		$config = array();
		$config['columns'] = apply_filters('sgAutoresponderEventsColumns', $columns);
		$config['columnTypes'] = apply_filters('sgAutoresponderEventsColumnTypes', $columnTypes);
		$config['paramsData'] = apply_filters('sgAutoresponderEventsParams', $params);
		$config['initialData'] = apply_filters('sgAutoresponderEventsInitialData', $initialData);
		$config['attrs'] = apply_filters('sgAutoresponderEventsAttrs', $attrs);
		$config['operators'] = apply_filters('sgAutoresponderEventsOperators', $operators);
		// if we don't have static operators (2-nd column)
		$config['operatorAllowInConditions'] = apply_filters('sgAutoresponderEventsOperators', array(SGPB_AUTORESPONDER_EVENT_ALL_POSTS_CREATION, SGPB_AUTORESPONDER_EVENT_PAGE_CREATION));
		$config['specialDefaultOperator'] = apply_filters('sgAutoresponderEventsOperators', ' ');

		return $config;
	}

	public function extendParams($targetParams)
	{
		$allCustomPostTypes = SubscriptionPlusAdminHelper::getAllCustomPosts();
		// for conditions, to exclude other post types, tags etc.
		if (isset($targetParams['select_role'])) {
			return $targetParams;
		}

		foreach ($allCustomPostTypes as $customPostType) {
			$targetParams[$customPostType] = array(
				$customPostType.'_all' => 'All New '.ucfirst($customPostType).'s',
				$customPostType.'_categories' => 'Select '.ucfirst($customPostType).' categories'
			);
		}

		return $targetParams;
	}

	public function alterColumnIntoSubscribers($filterColumnsSettings = array())
	{
		if (empty($filterColumnsSettings)) {
			return $filterColumnsSettings;
		}

		global $wpdb;
		$subscribersTable = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;
		if (isset($filterColumnsSettings['columns'])) {
			$filterColumnsSettings['columns'][] = $subscribersTable.'.submittedData';
		}
		if (isset($filterColumnsSettings['displayColumns'])) {
			$filterColumnsSettings['displayColumns']['submittedData'] = __('Additional Data', SG_POPUP_TEXT_DOMAIN);
		}

		return $filterColumnsSettings;
	}

	public function editSubscribersTableRowValues($rows = array(), $popupId = 0)
	{
		if (empty($rows)) {
			return $rows;
		}
		$subscriberId = @$rows[0];
		$submittedData = self::getSubscriberDetails($subscriberId, $popupId);
		$subscriberData = SubscriptionPlusAdminHelper::getSubscriberDataById($subscriberId);

		if ($submittedData != '[]') {
			$rows[] = '<input type="button" data-attr-subscriber-data="'.esc_attr($submittedData).'" data-subscriber-id="'.esc_attr($subscriberId).'" class="sgpb-btn sgpb-btn-dark-outline sgpb-show-subscribers-additional-data-js" value="'.__('More details', SG_POPUP_TEXT_DOMAIN).'">';
		}
		else {
			$rows[] = '<i>'.__('No more data.', SG_POPUP_TEXT_DOMAIN).'</i>';
		}

		$inactiveEmailClass = '';
		if (!$subscriberData['emailStatus']) {
			$inactiveEmailClass = '-inactive';
		}

		$style = '<style>';
		$style .= '.sgpb-email-status {
				color: #0CA587 !important;
				margin-left: 5px;
				margin-top: 3px;
				font-size: 16px;
			}
			.sgpb-email-status-inactive {
				color: #CCCCCC !important;
				margin-left: 5px;
				margin-top: 3px;
				font-size: 16px;
			}
			.wp-list-table .column-id { width: 5%; }
			.wp-list-table .column-email { width: 265px; }';
		$style .= '</style>';

		$emailStatusHtml = '<i class="dashicons dashicons-yes-alt sgpb-email-status'.$inactiveEmailClass.'"></i>';
		$emailStatusHtml .= '<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none; margin-top: 20px;">';
		$emailStatusHtml .= __('The email address had been verified.', SG_POPUP_TEXT_DOMAIN);
		$emailStatusHtml .= '</span>';
		$rows[3] = '<p>'. '<span>'.$rows[3] .'</span>'.$emailStatusHtml.'</p>' . $style;

		return $rows;
	}

	public static function getSubscriberDetails($subscriberId, $popupId)
	{
		$subscriberData = SubscriptionPlusAdminHelper::getSubscriberDataById($subscriberId);
		$defaultData = array(
			'label' => __('Email', SG_POPUP_TEXT_DOMAIN),
			'value' => @$subscriberData['email']
		);

		$defaultData = array($defaultData);
		parse_str($subscriberData['submittedData'], $subscriberSubmittedData);
		$subscriberSubmittedData['popupId'] = $popupId;

		$result = SubscriptionPlusAdminHelper::collectDataIntoArray($subscriberSubmittedData);

		if (!empty($result)) {
			return $result;
		}

		return json_encode($defaultData);
	}

	public function subscriptionForm($popupObj)
	{
		if (!is_object($popupObj)) {
			return $popupObj;
		}
		$subscriptionPlusFormObj = new SubscriptionPlusForm();
		$subscriptionPlusFormObj->setPopupObj($popupObj);
		$formContent = $subscriptionPlusFormObj->render();

		$popupObj->setFormContent($formContent);

		return $popupObj;
	}

	public function addDefaultContentToTemplate($content) {
		if (!empty($_GET['sgpb_template_type'])) {
			$templateType = (int)$_GET['sgpb_template_type'];
			$content = EmailTemplate::getHtmlContentById($templateType);
			if ($content) {
				return $content;
			}
		}

		return $content;
	}

	public function addNewTemplateUrl($url, $path)
	{
		if ($path == 'post-new.php?post_type='.SG_POPUP_TEMPLATE_POST_TYPE) {
			$url = str_replace('post-new.php?post_type='.SG_POPUP_TEMPLATE_POST_TYPE, 'edit.php?post_type='.SG_POPUP_POST_TYPE.'&page='.SG_POPUP_TEMPLATE_POST_TYPE, $url);
		}

		return $url;
	}

	public function subscriptionPlusDefaultOptions($options)
	{
		$options[] = array('name' => 'sgpb-subscription-fields-json', 'type' => 'sgpb', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-subscription-fields-design-json', 'type' => 'sgpb', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-subs-enable-email-notifications', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'sgpb-subs-notifications-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$options[] = array('name' => 'sgpb-subs-register-user', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-subs-label-color', 'type' => 'text', 'defaultValue' => '#000000');
		$options[] = array('name' => 'sgpb-subs-text-active-border-color', 'type' => 'text', 'defaultValue' => '#cccccc');
		$options[] = array('name' => 'sgpb-subs-text-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'sgpb-subs-btn-font-size', 'type' => 'text', 'defaultValue' => '18px');
		$options[] = array('name' => 'sgpb-subs-form-padding-top', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-form-padding-right', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-form-padding-bottom', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-form-padding-left', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-btn-bg-hover-color', 'type' => 'text', 'defaultValue' => '#007fe1');
		$options[] = array('name' => 'sgpb-subs-active-integrations', 'type' => 'array', 'defaultValue' => array());

		$options[] = array('name' => 'sgpb-subs-input-margin-top', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-input-margin-right', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-input-margin-bottom', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-input-margin-left', 'type' => 'text', 'defaultValue' => '2');

		$options[] = array('name' => 'sgpb-subs-button-margin-top', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-button-margin-right', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-button-margin-bottom', 'type' => 'text', 'defaultValue' => '2');
		$options[] = array('name' => 'sgpb-subs-button-margin-left', 'type' => 'text', 'defaultValue' => '2');

		$options[] = array('name' => 'sgpb-subs-field-horizontally', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-subs-except-button', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-subs-double-option', 'type' => 'checkbox', 'defaultValue' => '');

		return $options;
	}

	public function subscriptionPlusMetaboxes($metaboxes)
	{
		$popupType = SubscriptionPlusAdminHelper::getPopupTypeName();
		if ($popupType != SGPB_POPUP_TYPE_SUBSCRIPTION_PLUS) {
			return $metaboxes;
		}
		$isSubscriptionPlusActive = is_plugin_active(SGPB_POPUP_SUBSCRIPTION_PLUS_EXTENSION_KEY);

		if (!$isSubscriptionPlusActive) {
			return $metaboxes;
		}

		$metabox = array(
			'key' => 'popupTypeOptionsView',
			'displayName' => 'Subscription Plus Settings',
			'short_description' => 'Create subscription form, customize the fields and styles',
			'filePath' => SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'notifications.php',
			'priority' => 'high'
		);
		$metaboxes = SubscriptionPlusAdminHelper::arrayUnshiftAssoc($metaboxes,'popupTypeOptionsView', $metabox);

		return $metaboxes;
	}

	public function templateHeader($actionsObj = array())
	{
		$headerOptionValue = AdminHelper::getOption(SGPB_DEFAULT_EMAIL_TEMPLATES_HEADER);
		$header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head> <meta charset="UTF-8"> <meta content="width=device-width, initial-scale=1" name="viewport"> <meta name="x-apple-disable-message-reformatting"> <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta content="telephone=no" name="format-detection"> <title></title><!--[if (mso 16)]> <style type="text/css"> a{text-decoration: none;}</style><![endif]--> <style type="text/css"> table td{border-width: 0px !important;}</style></head><body>';
		if (!$headerOptionValue) {
			AdminHelper::updateOption(SGPB_DEFAULT_EMAIL_TEMPLATES_HEADER, $header);
		}
		$urlParams = $actionsObj->getParams();

		if (empty($urlParams['header'])) {
			$urlParams['header'] = $headerOptionValue;
		}

		$hContent = AdminHelper::getFileFromURL($urlParams['header']);
		$actionsObj->setEmailTemplateHeader($urlParams['header']);

		return $actionsObj;
	}

	public function templateFooter($actionsObj = array())
	{
		$footerOptionValue = AdminHelper::getOption(SGPB_DEFAULT_EMAIL_TEMPLATES_FOOTER);
		$footer = '</body></html>';
		if (!$footerOptionValue) {
			AdminHelper::updateOption(SGPB_DEFAULT_EMAIL_TEMPLATES_FOOTER, $footer);
		}

		$urlParams = $actionsObj->getParams();
		if (empty($urlParams['footer'])) {
			$urlParams['footer'] = $footerOptionValue;
		}
		$fContent = AdminHelper::getFileFromURL($urlParams['footer']);
		$actionsObj->setEmailTemplateFooter($fContent);

		return $actionsObj;
	}

	public function templateContent($actionsObj = array())
	{
		$body = '';
		$urlParams = $actionsObj->getParams();
		$urlParams['body'] = SGPB_SUBSCRIPTION_PLUS_TEMPLATES_URL.'template1.php';
		if (!empty($urlParams['templateId'])) {
			$templateId = (int)$urlParams['templateId'];
			//get template from db
			$contentFromDb = EmailTemplate::getTemplateBodyHtmlByIdFromDB($templateId);
			$actionsObj->setEmailTemplateContent($contentFromDb);
		}
		// if no templateId, get default template body
		else {
			$content = AdminHelper::getFileFromURL($urlParams['body']);
			$actionsObj->setEmailTemplateContent($content);

		}

		return $actionsObj;
	}

	public function autorespondersTableColumns($columns)
	{
		unset($columns['date']);
		$additionalItems = array();
		$additionalItems['onOff'] = __('ON/OFF Autoresponder', SG_POPUP_TEXT_DOMAIN);
		$additionalItems['date'] = __('Date', SG_POPUP_TEXT_DOMAIN);

		return $columns + $additionalItems;
	}
}
