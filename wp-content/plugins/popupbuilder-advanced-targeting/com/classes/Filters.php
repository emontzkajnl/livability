<?php
namespace sgpbadvancedtargeting;
use sgpbadvancedtargeting\AdminHelper;
use sgpb\AdminHelper as MainAdminHelper;

class Filters
{
	private $allowedUrl = '';

	public function __construct()
	{
		add_filter('sgpbOptionAvailable', array($this, 'filterOption'), 10, 1);
		add_filter('sgpbAdditionalMetaboxes', array($this, 'geoTargetingMetaboxes'), 100, 1);
		add_filter('sgpbConditionalJsClasses', array($this, 'addConditionalClassName'), 10, 1);
		// option filter
		add_filter('sgPopupConditionsData', array($this, 'conditionData'), 10, 1);
		add_filter('sgPopupConditionsAttrs', array($this, 'conditionsAttrs'), 10, 1);
		add_filter('sgPopupConditionsTypes', array($this, 'conditionsTypes'), 10, 1);
		add_filter('sgPopupConditionsOperatorAllowInConditions', array($this, 'allowInConditions'), 10, 1);
		// checker
		add_filter('isAllowedConditionFilters',  array($this, 'conditionFilter'), 100, 1);
		add_filter('sgpbFilterDividedConditions', array($this, 'filterConditions'), 100, 1);
		add_filter('sgpbAdditionalPermissiveOperators', array($this, 'permissiveOperators'), 1, 1);
		add_filter('sgpbAdditionalForbiddenOperators', array($this, 'forbiddenOperators'), 1, 1);
		add_filter('sgpbconditionsoperatorParam', array($this, 'changeConditionsParamAttributes'), 1, 2);
	}

	public function changeConditionsParamAttributes($optionsAttrs = array(), $savedData = array())
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$SGPB_DATA_CONFIG_ARRAY['conditions']['attrs'][SGPB_POPUP_COOKIE_DETECTION_KEY];
		if ($savedData['param'] == SGPB_POPUP_COOKIE_DETECTION_KEY) {
			$optionsAttrs['infoAttrs'] = $SGPB_DATA_CONFIG_ARRAY['conditions']['attrs'][SGPB_POPUP_COOKIE_DETECTION_KEY]['operatorAttrs'];
		}

		return $optionsAttrs;
	}

	public function permissiveOperators($operators = array())
	{
		$operators[] = SGPB_POPUP_URL_DETECTION_KEY.'%';
		$operators[] = SGPB_POPUP_URL_DETECTION_KEY.'==';
		$operators[] = SGPB_POPUP_URL_DETECTION_KEY.'!=';
		$operators[] = SGPB_POPUP_URL_DETECTION_KEY.'===';

		$operators[] = SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'%';
		$operators[] = SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'==';
		$operators[] = SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'!=';
		$operators[] = SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'===';

		$operators[] = SGPB_POPUP_COOKIE_DETECTION_KEY.'%';
		$operators[] = SGPB_POPUP_COOKIE_DETECTION_KEY.'==';
		$operators[] = SGPB_POPUP_COOKIE_DETECTION_KEY.'!=';
		$operators[] = SGPB_POPUP_COOKIE_DETECTION_KEY.'===';

		return $operators;
	}

	public function forbiddenOperators($operators = array())
	{
		$operators[] = SGPB_POPUP_COOKIE_DETECTION_KEY.'!=';

		return $operators;
	}

	private function setAllowedUrl($allowedUrl)
	{
		$this->allowedUrl = $allowedUrl;
	}

	private function getAllowedUrl()
	{
		return $this->allowedUrl;
	}

	public function filterConditions($conditions = array())
	{
		$operator = '';
		if (!empty($conditions['permissive'])) {
			$permissiveOptions = $conditions['permissive'];
			foreach ($permissiveOptions as $key => $permissiveOption) {
				if (isset($permissiveOption['param']) && $permissiveOption['param'] == SGPB_POPUP_URL_DETECTION_KEY) {
					$value = $permissiveOption['value'];
					$operator = $permissiveOption['operator'];
					unset($conditions['permissive'][$key]);
					$permissiveOptions['permissive'][] = $value;
				}
			}
			if ($operator != '') {
				$conditions['permissive'][] = array(
					'param' => SGPB_POPUP_URL_DETECTION_KEY,
					'operator' => $operator,
					'value' => $permissiveOptions['permissive']
				);
			}
		}
		if (!empty($conditions['forbidden'])) {
			$forbiddenConditions = $conditions['forbidden'];
			foreach ($forbiddenConditions as $key => $forbiddenCondition) {
				if (isset($forbiddenCondition['param']) && $forbiddenCondition['param'] == SGPB_POPUP_URL_DETECTION_KEY) {
					$value = $forbiddenCondition['value'];
					$operator = $forbiddenCondition['operator'];
					unset($conditions['forbidden'][$key]);
					$forbiddenConditions['forbidden'][] = $value;
				}
			}
			if ($operator != '') {
				$conditions['forbidden'][] = array(
					'param' => SGPB_POPUP_URL_DETECTION_KEY,
					'operator' => $operator,
					'value' => $forbiddenConditions['forbidden']
				);
			}
		}

		return $conditions;
	}

	public function addConditionalClassName($classes)
	{
		$classes[] = 'SGPBAdvancedTargeting';

		return $classes;
	}

	public function allowInConditions($options)
	{
		$options[] = SGPB_POPUP_AFTER_X_PAGE_KEY;
		$options[] = SGPB_POPUP_URL_DETECTION_KEY;
		$options[] = SGPB_POPUP_QUERY_STRING_DETECTION_KEY;
		$options[] = SGPB_POPUP_COOKIE_DETECTION_KEY;

		return $options;
	}

	public function conditionFilter($option)
	{
		// for url detection condition, fitler and select only url_detection conditions
		$paramName = $option[0]['param'];
		$value = $option[0]['value'];

		if (empty($option['status'])) {
			$option['status'] = false;
		}

		if ($paramName == SGPB_POPUP_GROUPS_DEVICES_KEY && !empty($value)) {
			if (is_array($value)) {
				$device = AdminHelper::getUserDevice();
				if (in_array($device, $value)) {
					$option['status'] = true;
				}
				else {
					$option['status'] = false;
				}
			}
		}
		else if ($paramName == SGPB_POPUP_GROUPS_USER_ROLE_KEY && !empty($value)) {
			$userStatus = is_user_logged_in();
			if ($userStatus) {
				$option['status'] = true;
			}
			else {
				$option['status'] = false;
			}
		}
		else if ($paramName == SGPB_POPUP_GROUPS_USER_STATUS_KEY && !empty($value)) {
			if (is_array($value)) {
				$currentUserRole = AdminHelper::getCurrentUserRole();
				if (array_intersect($currentUserRole, $value)) {
					$option['status'] = true;
				}
				else {
					$option['status'] = false;
				}
			}
		}
		else if ($paramName == SGPB_POPUP_URL_DETECTION_KEY && !empty($value)) {
			$condition = $option[0]['operator'];
			$allowToOpen = $this->isMatchReferralUrl($value, $condition);
			if ($allowToOpen) {
				$option['status'] = true;
			}
			else {
				$option['status'] = false;
			}
		}
		else if ($paramName == SGPB_POPUP_QUERY_STRING_DETECTION_KEY && !empty($value)) {
			$condition = $option[0]['operator'];
			$allowToOpen = $this->queryStringHasInUrl($value, $condition);
			if ($allowToOpen) {
				$option['status'] = true;
			}
			else {
				$option['status'] = false;
			}
		}
		else if ($paramName == SGPB_POPUP_COOKIE_DETECTION_KEY && !empty($value)) {
			$condition = $option[0]['operator'];
			$cookieNames = array_keys($_COOKIE);

			if (!empty($value) && strpos($value, ',')) {
				$cookies = explode(',', $value);
				foreach ($cookies as $cookie) {
					$cookie = str_replace(' ', '', $cookie);
					$allowToOpen = $this->isMatchCookie($cookieNames, $condition, $cookie);
					if($allowToOpen) {
						$option['status'] = true;
						break;
					}
				}
			}

			if (!$option['status']) {
				$allowToOpen = $this->isMatchCookie($cookieNames, $condition, $value);

				if ($allowToOpen) {
					$option['status'] = true;
				}
				else {
					$option['status'] = false;
				}
			}

		}
		else if ($paramName == SGPB_POPUP_GROUPS_OS_KEY && !empty($value)) {
			if (is_array($value)) {
				$os = AdminHelper::getUserOS();
				if (in_array($os, $value)) {
					$option['status'] = true;
				}
				else {
					$option['status'] = false;
				}
			}
		}
		else if ($paramName == SGPB_POPUP_GROUPS_BROWSER_KEY && !empty($value)) {
			if (is_array($value)) {
				$browser = AdminHelper::getWebBrowser();
				$browsers = $value;
				foreach ($browsers as $singleBrowser) {
					if ($browser == ucfirst($singleBrowser)) {
						$option['status'] = true;
						break;
					}
				}
			}
		}
		else if ($paramName == SGPB_POPUP_AFTER_X_PAGE_KEY) {
			$option['status'] = true;
		}

		return $option;
	}

	public function geoTargetingMetaboxes($metaboxes)
	{
		$metaboxes['conditionsMetaboxView'] = array(
			'key' => 'conditionsMetaboxView',
			'displayName' => 'Conditions',
			'short_description' => 'Select advanced conditions for more professional targeting',
			'filePath' => SGPB_ADVANCED_TARGETING_VIEWS_PATH.'conditionsView.php',
			'priority' => 'high'
		);

		return $metaboxes;
	}

	public function filterOption($filterOption)
	{
		if (isset($filterOption['name'])) {
			$name = $filterOption['name'];
			if ($name == 'popupConditionsSection') {
				$filterOption['status'] = true;
			}

			return $filterOption;
		}

		return $filterOption;
	}

	public function conditionData($conditionData)
	{
		$options = array();
		$userStatus = array(
			'loggedIn' => 	__('logged in', SG_POPUP_TEXT_DOMAIN)
		);
		$userSavedRoles = get_option('sgpb-user-roles');
		$userRoles = AdminHelper::getUserRoles();
		$urlDetectionOperators = array(
			SGPB_POPUP_URL_DETECTION_KEY.'==' => __('Starts with', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_URL_DETECTION_KEY.'%' => __('Contains', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_URL_DETECTION_KEY.'!=' => __('Doesn\'t contain', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_URL_DETECTION_KEY.'===' => __('Equals', SG_POPUP_TEXT_DOMAIN)
		);

		$queryStringDetectionOperators = array(
			SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'==' => __('Starts with', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'%' => __('Contains', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'!=' => __('Doesn\'t contain', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'===' => __('Equals', SG_POPUP_TEXT_DOMAIN)
		);

		$cookieDetectionOperators = array(
			SGPB_POPUP_COOKIE_DETECTION_KEY.'%' => __('Contains', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_COOKIE_DETECTION_KEY.'==' => __('Starts with', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_COOKIE_DETECTION_KEY.'!=' => __('Doesn\'t contain', SG_POPUP_TEXT_DOMAIN),
			SGPB_POPUP_COOKIE_DETECTION_KEY.'===' => __('Equals', SG_POPUP_TEXT_DOMAIN)
		);

		$options[SGPB_POPUP_GROUPS_DEVICES_KEY] = __('Devices', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_GROUPS_USER_ROLE_KEY] = __('User status', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_AFTER_X_PAGE_KEY] = __('After X pages visit', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_GROUPS_USER_STATUS_KEY] = __('User role', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_QUERY_STRING_DETECTION_KEY] = __('URL Query String', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_URL_DETECTION_KEY] = __('Referral URL Detection', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_COOKIE_DETECTION_KEY] = __('Cookie Detection', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_GROUPS_OS_KEY] = __('Operating System', SG_POPUP_TEXT_DOMAIN);
		$options[SGPB_POPUP_GROUPS_BROWSER_KEY] = __('Web Browser', SG_POPUP_TEXT_DOMAIN);
		$conditionData[SGPB_POPUP_GROUPS_DEVICES_KEY] = apply_filters('sgPopupConditionsDevices', AdminHelper::getDevices());
		$conditionData[SGPB_POPUP_GROUPS_USER_ROLE_KEY] = apply_filters('sgPopupConditionsUserStatus', $userStatus);
		$conditionData[SGPB_POPUP_GROUPS_USER_STATUS_KEY] = apply_filters('sgPopupConditionsUserStatus', $userRoles);
		$conditionData[SGPB_POPUP_AFTER_X_PAGE_KEY] = apply_filters('sgPopupConditionsAfterXpage', 0);
		$conditionData[SGPB_POPUP_URL_DETECTION_KEY] = apply_filters('sgPopupConditionsUrlDetection', ' ');
		$conditionData[SGPB_POPUP_QUERY_STRING_DETECTION_KEY] = apply_filters('sgPopupConditionsQueryStringDetection', ' ');
		$conditionData[SGPB_POPUP_COOKIE_DETECTION_KEY] = apply_filters('sgPopupConditionsCookieDetection', ' ');
		$conditionData[SGPB_POPUP_GROUPS_OS_KEY] = apply_filters('sgPopupOperationSystem', AdminHelper::getOperationSystems());
		$conditionData[SGPB_POPUP_GROUPS_BROWSER_KEY] = apply_filters('sgPopupWebBrowser', AdminHelper::getWebBrowsers());
		$conditionData[SGPB_POPUP_URL_DETECTION_KEY.'Operator'] = $urlDetectionOperators;
		$conditionData[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'Operator'] = $queryStringDetectionOperators;
		$conditionData[SGPB_POPUP_COOKIE_DETECTION_KEY.'Operator'] = $cookieDetectionOperators;
		$conditionData[SGPB_POPUP_COOKIE_DETECTION_KEY.'%'] = 0;
		$conditionData[SGPB_POPUP_COOKIE_DETECTION_KEY.'=='] = 0;
		$conditionData[SGPB_POPUP_COOKIE_DETECTION_KEY.'!='] = 0;
		$conditionData[SGPB_POPUP_COOKIE_DETECTION_KEY.'==='] = 0;

		$conditionData[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'%'] = 0;
		$conditionData[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'=='] = 0;
		$conditionData[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'!='] = 0;
		$conditionData[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'==='] = 0;

		$conditionData[SGPB_POPUP_URL_DETECTION_KEY.'%'] = 0;
		$conditionData[SGPB_POPUP_URL_DETECTION_KEY.'=='] = 0;
		$conditionData[SGPB_POPUP_URL_DETECTION_KEY.'!='] = 0;
		$conditionData[SGPB_POPUP_URL_DETECTION_KEY.'==='] = 0;

		$conditionData['param']['Groups'] = $options;

		return $conditionData;
	}

	public function conditionsAttrs($conditionAttrs)
	{
		$conditionAttrs[SGPB_POPUP_GROUPS_DEVICES_KEY] = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic',
				'multiple' => 'multiple'
			),
			'infoAttrs' => array(
				'label' => 'Select user devices',
				'info' => __('Select the device for which the popup will be available.', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$conditionAttrs[SGPB_POPUP_GROUPS_USER_ROLE_KEY] = array(
			'htmlAttrs' => 	array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic'
			),
			'infoAttrs' => array(
				'label' => 'Select user status',
				'info' => __('Set up the popup to allow it for logged-in or logged-out users.', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$conditionAttrs[SGPB_POPUP_AFTER_X_PAGE_KEY] = array(
			'htmlAttrs' => 	array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'min' => 0,
				'data-select-type' => 'ajax'
			),
			'infoAttrs' => array(
				'label' => 'Is at least',
				'info' => __('If this option is enabled, you can show a popup after the user has visited your specified number of pages.', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$conditionAttrs[SGPB_POPUP_GROUPS_USER_STATUS_KEY] = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic',
				'multiple' => 'multiple'
			),
			'infoAttrs' => array(
				'label' => 'Select user role',
				'info' => __('Set up the popup to allow it for the selected user role.', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$conditionAttrsUrlDetection = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic'
			),
			'infoAttrs' => array(
				'label' => 'Add the url',
				'info' => __('Add the referral URL by which the popup will be shown.', SG_POPUP_TEXT_DOMAIN)
			)
		);
		$conditionAttrs[SGPB_POPUP_URL_DETECTION_KEY] = $conditionAttrsUrlDetection;
		$conditionAttrs[SGPB_POPUP_URL_DETECTION_KEY.'%'] = $conditionAttrsUrlDetection;
		$conditionAttrs[SGPB_POPUP_URL_DETECTION_KEY.'=='] = $conditionAttrsUrlDetection;
		$conditionAttrs[SGPB_POPUP_URL_DETECTION_KEY.'!='] = $conditionAttrsUrlDetection;
		$conditionAttrs[SGPB_POPUP_URL_DETECTION_KEY.'==='] = $conditionAttrsUrlDetection;
		$conditionAttrs[SGPB_POPUP_URL_DETECTION_KEY.'Operator'] = $conditionAttrsUrlDetection;

		$conditionAttrsQueryStringDetection = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic'
			),
			'infoAttrs' => array(
				'label' => 'Add the URL Query String',
				'info' => __('Add the URL Query String by which popup will be displayed.', SG_POPUP_TEXT_DOMAIN)
			)
		);
		$conditionAttrs[SGPB_POPUP_QUERY_STRING_DETECTION_KEY] = $conditionAttrsQueryStringDetection;
		$conditionAttrs[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'%'] = $conditionAttrsQueryStringDetection;
		$conditionAttrs[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'=='] = $conditionAttrsQueryStringDetection;
		$conditionAttrs[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'!='] = $conditionAttrsQueryStringDetection;
		$conditionAttrs[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'==='] = $conditionAttrsQueryStringDetection;
		$conditionAttrs[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'Operator'] = $conditionAttrsQueryStringDetection;

		$conditionAttrs[SGPB_POPUP_COOKIE_DETECTION_KEY] = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic'
			),
			'infoAttrs' => array(
				'label' => 'Cookie name',
				'info' => __('Add the cookie name to trigger the popup. If you have several cookies via which you want to show/hide the popup, you can add them via comma, so the rule will work with "OR" condition.', SG_POPUP_TEXT_DOMAIN)
			),
			'operatorAttrs' => array(
				'info' => __('Allow or Disallow popup showing for the selected cookie', SG_POPUP_TEXT_DOMAIN),
				'label' => __('Rule', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$conditionAttrs[SGPB_POPUP_COOKIE_DETECTION_KEY.'Operator'] = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic'
			),
			'infoAttrs' => array(
				'label' => 'Cookie name',
				'info' => __('Add the cookie name to trigger the popup. If you have several cookies via which you want to show/hide the popup, you can add them via comma, so the rule will work with "OR" condition.', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$conditionAttrsCookieDetection = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic'
			),
			'infoAttrs' => array(
				'label' => 'Add cookie name',
				'info' => __('Add the cookie name to trigger the popup. If you have several cookies via which you want to show/hide the popup, you can add them via comma, so the rule will work with "OR" condition.', SG_POPUP_TEXT_DOMAIN)
			)
		);
		$conditionAttrs[SGPB_POPUP_COOKIE_DETECTION_KEY.'%'] = $conditionAttrsCookieDetection;
		$conditionAttrs[SGPB_POPUP_COOKIE_DETECTION_KEY.'=='] = $conditionAttrsCookieDetection;
		$conditionAttrs[SGPB_POPUP_COOKIE_DETECTION_KEY.'!='] = $conditionAttrsCookieDetection;
		$conditionAttrs[SGPB_POPUP_COOKIE_DETECTION_KEY.'==='] = $conditionAttrsCookieDetection;

		$conditionAttrs[SGPB_POPUP_GROUPS_OS_KEY] = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic',
				'multiple' => 'multiple'
			),
			'infoAttrs' => array(
				'label' => 'Select needed OS',
				'info' => __('Select the operating system for which the popup will be available.', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$conditionAttrs[SGPB_POPUP_GROUPS_BROWSER_KEY] = array(
			'htmlAttrs' => array(
				'class' => 'js-sg-select2 js-select-basic',
				'data-select-class' => 'js-select-basic',
				'data-select-type' => 'basic',
				'multiple' => 'multiple'
			),
			'infoAttrs' => array(
				'label' => 'Select Web Browser',
				'info' => __('Select the Web Browser for which the popup will be available.', SG_POPUP_TEXT_DOMAIN)
			)
		);

		return $conditionAttrs;
	}

	public function conditionsTypes($conditionTypes)
	{
		$conditionTypes[SGPB_POPUP_GROUPS_DEVICES_KEY] = 'select';
		$conditionTypes[SGPB_POPUP_GROUPS_USER_ROLE_KEY] = 'select';
		$conditionTypes[SGPB_POPUP_AFTER_X_PAGE_KEY] = 'number';
		$conditionTypes[SGPB_POPUP_GROUPS_USER_STATUS_KEY] = 'select';
		$conditionTypes[SGPB_POPUP_URL_DETECTION_KEY] = 'text';
		$conditionTypes[SGPB_POPUP_QUERY_STRING_DETECTION_KEY] = 'text';
		$conditionTypes[SGPB_POPUP_COOKIE_DETECTION_KEY] = 'text';
		$conditionTypes[SGPB_POPUP_GROUPS_OS_KEY] = 'select';
		$conditionTypes[SGPB_POPUP_GROUPS_BROWSER_KEY] = 'select';

		$conditionTypes[SGPB_POPUP_URL_DETECTION_KEY.'%'] = $conditionTypes[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'%'] = $conditionTypes[SGPB_POPUP_COOKIE_DETECTION_KEY.'%'] = 'text';
		$conditionTypes[SGPB_POPUP_URL_DETECTION_KEY.'=='] = $conditionTypes[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'=='] = $conditionTypes[SGPB_POPUP_COOKIE_DETECTION_KEY.'=='] = 'text';
		$conditionTypes[SGPB_POPUP_URL_DETECTION_KEY.'!='] = $conditionTypes[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'!='] = $conditionTypes[SGPB_POPUP_COOKIE_DETECTION_KEY.'!='] = 'text';
		$conditionTypes[SGPB_POPUP_URL_DETECTION_KEY.'==='] = $conditionTypes[SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'==='] = $conditionTypes[SGPB_POPUP_COOKIE_DETECTION_KEY.'==='] = 'text';

		return $conditionTypes;
	}

	private function queryStringHasInUrl($value, $condition = '')
	{
		$status = false;
		$detectedQueryString = '?'.$_SERVER['QUERY_STRING'];
		$value = htmlspecialchars_decode($value);
		// starts with
		if ($condition == SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'==') {
			if (strpos($detectedQueryString, $value) !== false && strpos($detectedQueryString, $value) == 1) {
				$status = true;
			}
		}
		// contains
		else if ($condition == SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'%') {
			$value = str_replace(' ', '', $value);
			$value = explode(',', $value);
			foreach ($value as $string) {
				if (strpos($detectedQueryString, $string) !== false) {
					$status = true;
					break;
				}
			}
		}
		// doesn't contain
		else if ($condition == SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'!=') {
			if (strpos($detectedQueryString, $value) === false) {
				$status = true;
			}
		}
		// equals
		else if ($condition == SGPB_POPUP_QUERY_STRING_DETECTION_KEY.'===') {
			$value = '?'.$value;
			if ($detectedQueryString == $value) {
				$status = true;
			}
		}

		return $status;
	}

	private function isMatchReferralUrl($values, $condition = '')
	{
		if (empty($values) || !is_array($values)) {
			return false;
		}
		$status = false;
		$detectedUrl = AdminHelper::getReferalUrl();

		foreach ($values as $value) {
			$value = AdminHelper::filterUrl($value);
			// url contains and doesn't contain
			if ($condition == SGPB_POPUP_URL_DETECTION_KEY.'%' || $condition == SGPB_POPUP_URL_DETECTION_KEY.'!=') {
				if (strpos($detectedUrl, $value) !== false) {
					$status = true;
					break;
				}
			}
			else if ($condition == SGPB_POPUP_URL_DETECTION_KEY.'==') {
				if (strpos($detectedUrl, $value) !== false && strpos($detectedUrl, $value) == 0) {
					$status = true;
					break;
				}
			}
			// url equals
			else {
				if ($detectedUrl === $value) {
					$status = true;
					break;
				}
			}
		}

		if ($condition == SGPB_POPUP_URL_DETECTION_KEY.'!=') {
			$status = !$status;
		}

		return $status;
	}

	private function isMatchCookie($values, $condition = '', $currentValue = '')
	{
		if (empty($values) || !is_array($values)) {
			return false;
		}
		$status = false;

		if ($condition == SGPB_POPUP_COOKIE_DETECTION_KEY.'!=' && in_array($currentValue, $values)) {
			return $status;
		}

		foreach ($values as $value) {
			if ($condition == SGPB_POPUP_COOKIE_DETECTION_KEY.'%' || $condition == SGPB_POPUP_COOKIE_DETECTION_KEY.'!=') {
				if (strpos($value, $currentValue) !== false) {
					$status = true;
					break;
				}
			}
			else if ($condition == SGPB_POPUP_COOKIE_DETECTION_KEY.'==') {
				if (strpos($value, $currentValue) !== false && strpos($value, $currentValue) == 0) {
					$status = true;
					break;
				}
			}
			else if ($condition == SGPB_POPUP_COOKIE_DETECTION_KEY.'==='){
				if ($currentValue === $value) {
					$status = true;
					break;
				}
			}
		}

		if ($condition == SGPB_POPUP_COOKIE_DETECTION_KEY.'!=') {
			$status = !$status;
		}

		return $status;
	}
}
