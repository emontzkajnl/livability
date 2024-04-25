<?php

namespace sgpbgeotargeting;
class Filters
{
	public function __construct()
	{
		add_filter('sgPopupConditionsData', array($this, 'conditionData'), 10, 1);
		add_filter('sgPopupConditionsAttrs', array($this, 'conditionsAttrs'), 10, 1);
		add_filter('sgPopupConditionsTypes', array($this, 'conditionsTypes'), 10, 1);
		add_filter('isAllowedConditionFilters', array($this, 'isAllowedConditionFilters'), 100, 1);
		add_filter('sgpbAdditionalMetaboxes', array($this, 'geoTargetingMetaboxes'), 100, 1);
		add_filter('sgpbOptionAvailable', array($this, 'filterOption'), 10, 1);
		// proStartLightproEndLight
	}
	// proStartLightproEndLight
	public function saveConditions($conditions)
	{
		return $conditions;
	}
	// proStartLightproEndLight
	public function geoTargetingMetaboxes($metaboxes)
	{
		$metaboxes['conditionsMetaboxView'] = array(
			'key' => 'conditionsMetaboxView',
			'displayName' => 'Conditions',
			'short_description' => 'Select advanced conditions for more professional targeting',
			'filePath' => SGPB_GEO_TARGETING_VIEWS_PATH.'conditionsView.php',
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

	public function isAllowedConditionFilters($args = array())
	{
		if(empty($args['status'])) {
			$args['status'] = false;
		}

		if($args[0]['param'] == 'groups_countries' && !empty($args[0]['value'])) {
			if(is_array($args[0]['value'])) {
				$ipAddress = AdminHelper::getIpAddress();
				$country = AdminHelper::getCountryName($ipAddress);
				$args['status'] = false;
				if(in_array($country, $args[0]['value'])) {
					$args['status'] = true;
				}
			}
		}
		// proStartLightproEndLight
		if($args[0]['param'] == 'certain_ip' && !empty($args[0]['value'])) {
			if(isset($args[0]['value'])) {
				$ipAddress = AdminHelper::getIpAddress();
				$certainIpAddresses = explode(',', str_replace(' ', '', $args[0]['value']));
				$args['status'] = false;
				if(in_array($ipAddress, $certainIpAddresses)) {
					$args['status'] = true;
				}
			}
		}
		return $args;
	}

	public function conditionData($conditionData)
	{
		if (defined('SGPB_POPUP_ADVANCED_TARGETING_EXTENSION_KEY') && is_plugin_active(SGPB_POPUP_ADVANCED_TARGETING_EXTENSION_KEY)) {
			$conditionData['param']['Groups'][SGPB_GEO_TARGETING_EVENT_KEY] = __('Countries', SG_POPUP_TEXT_DOMAIN);
			// proStartLightproEndLight
			$conditionData['param']['Groups'][SGPB_GEO_TARGETING_CERTAIN_IP_KEY] = __('Visitor IP', SG_POPUP_TEXT_DOMAIN);
		}
		else {
			$conditionData['param'][SGPB_GEO_TARGETING_EVENT_KEY] = __('Countries', SG_POPUP_TEXT_DOMAIN);
			// proStartLightproEndLight
			$conditionData['param'][SGPB_GEO_TARGETING_CERTAIN_IP_KEY] = __('Visitor IP', SG_POPUP_TEXT_DOMAIN);

		}
		$conditionData[SGPB_GEO_TARGETING_EVENT_KEY] = AdminHelper::countriesIsoData();
		// proStartLightproEndLight
		$conditionData[SGPB_GEO_TARGETING_CERTAIN_IP_KEY] = '';

		return $conditionData;
	}

	public function conditionsAttrs($conditionAttrs)
	{
		$conditionAttrs[SGPB_GEO_TARGETING_EVENT_KEY] = array(
				'htmlAttrs' => array(
					'class' => 'js-sg-select2 js-select-basic',
					'data-select-class' => 'js-select-basic',
					'data-select-type' => 'basic',
					'multiple' => 'multiple'
				),
				'infoAttrs' => array(
					'label' => 'Select countries',
					'info' => __('Select the countries for which the popup will be shown or hidden.', SG_POPUP_TEXT_DOMAIN)
				)
			);
		// proStartLightproEndLight
		$conditionAttrs[SGPB_GEO_TARGETING_CERTAIN_IP_KEY] = array(
				'htmlAttrs' => array(
					'class' => 'sgpb-full-width-events form-control',
					'data-select-class' => 'js-select-basic',
					'data-select-type' => 'basic',
					'required' => 'required'
				),
				'infoAttrs' => array(
					'label' => 'Visitor IP',
					'info' => __('Enter the IP address(es) for which the popup will be shown or hidden.', SG_POPUP_TEXT_DOMAIN)
				)
			);

		return $conditionAttrs;
	}

	public function conditionsTypes($conditionTypes)
	{
		$conditionTypes[SGPB_GEO_TARGETING_EVENT_KEY] = 'select';
		// proStartLightproEndLight
		$conditionTypes[SGPB_GEO_TARGETING_CERTAIN_IP_KEY] = 'text';

		return $conditionTypes;
	}
}
