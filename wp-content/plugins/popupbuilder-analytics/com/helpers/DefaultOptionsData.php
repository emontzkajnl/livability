<?php
namespace sgpban;
use \SGPBAnalyticsConfig;

class DefaultOptionsData
{
	public static function getDateRanges()
	{
		$dateRanges = array();

		$dateRanges[0] = __('Today', SG_POPUP_TEXT_DOMAIN);
		$dateRanges[1] = __('Yesterday', SG_POPUP_TEXT_DOMAIN);
		$dateRanges[7] = __('Last 7 Days', SG_POPUP_TEXT_DOMAIN);
		$dateRanges[30] = __('Last 30 Days', SG_POPUP_TEXT_DOMAIN);
		$dateRanges[60] = __('Last 60 Days', SG_POPUP_TEXT_DOMAIN);
		$dateRanges[90] = __('Last 90 Days', SG_POPUP_TEXT_DOMAIN);
		$dateRanges[120] = __('Last 120 Days', SG_POPUP_TEXT_DOMAIN);

		return $dateRanges;
	}

	public static function getEventsKeys()
	{
		$eventsList = SGPBAnalyticsConfig::sgpbEvents();
		$keys = array();

		if (empty($eventsList)) {
			return $keys;
		}

		foreach ($eventsList as $event) {
			$keys[] = $event['key'];
		}

		return $keys;
	}

	public static function getEventsFieldsAttributes()
	{
		$fields = array();
		$eventsList = SGPBAnalyticsConfig::sgpbEvents();

		if (empty($eventsList)) {
			return $fields;
		}

		foreach ($eventsList as $eventKey => $eventValue) {
			$fields[] = array(
					'attr' => array(
						'type' => 'checkbox',
						'name' => 'sgpb-event-name',
						'class' => 'sgpb-events-list-checkbox',
						'id' => $eventKey,
						'value' => $eventValue['key']
					),
					'label' => array(
						'name' => $eventValue['label']
					)
				);
		}

		return $fields;
	}

	public static function getEventsCheckboxData()
	{
		$fields = self::getEventsFieldsAttributes();
		$events = array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-2 sgpb-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-10 sgpb-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group sgpb-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => $fields
		);

		return $events;
	}
}
