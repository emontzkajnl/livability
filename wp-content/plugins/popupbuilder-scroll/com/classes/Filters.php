<?php
namespace sgpbscroll;

class Filters
{
	public function __construct()
	{
		add_filter('sgPopupEventsData', array($this, 'eventsData'), 10, 1);
		add_filter('sgPopupEventAttrs', array($this, 'eventsAttrs'), 10, 1);
		add_filter('sgPopupEventTypes', array($this, 'eventsTypes'), 10, 1);
		add_filter('sgpbProEvents', array($this, 'proEvents'), 10, 1);
		add_filter('sgPopupEventsOperatorAllowInConditions', array($this, 'allowInConditions'), 10, 1);
		add_filter('sgpbPopupEvents', array($this, 'popupEvents'), 10, 2);
		add_filter('sgpbSavedPostData', array($this, 'savedPostData'), 10, 1);
	}

	public function popupEvents($events, $popupObj = '')
	{
		foreach ($events as $key => $event) {
			if (!empty($event['param']) && $event['param'] == 'onScroll' && empty($event['operator'])) {
				$events[$key]['operator'] = 'scrollDistance';
				$events[$key]['value'] = (int)$event['value'].'%';
			}
		}

		return $events;
	}

	public function savedPostData($savedPostData)
	{
		if (empty($savedPostData['sgpb-events'][0])) {
			return $savedPostData;
		}

		foreach ($savedPostData['sgpb-events'][0] as $key => $event) {
			if (isset($event['value'])) {
				$value = (int)$event['value'];
			}
			if ($event['param'] == 'onScroll' && empty($event['operator'])) {
				unset($savedPostData['sgpb-events'][0][$key]['value']);
				$savedPostData['sgpb-events'][0][$key]['operator'] = 'scrollDistance';
				$savedPostData['sgpb-events'][0][$key]['value'] = $value.'%';
			}
		}

		return $savedPostData;
	}

	public function proEvents($events)
	{
		if (empty($events)) {
			return $events;
		}

		$key = array_search('onScroll', $events);
		if ($key !== false) {
			unset($events[$key]);
		}

		return $events;
	}

	public function allowInConditions($conditions)
	{
		$conditions[] = SGPB_SCROLL_EVENT_KEY;
		return $conditions;
	}

	public function eventsData($eventsData)
	{
		$eventsData['param'][SGPB_SCROLL_EVENT_KEY] = 'On Scroll';
		$eventsData[SGPB_SCROLL_EVENT_KEY.'Operator'] = array(
			'scrollDistance' => __('Distance', SG_POPUP_TEXT_DOMAIN),
			'scrollElement' => __('Element', SG_POPUP_TEXT_DOMAIN),
			'scrollTop' => __('Scroll to top', SG_POPUP_TEXT_DOMAIN)
		);
		$eventsData['scrollDistance'] = 0;
		$eventsData['scrollElement'] = '';

		return $eventsData;
	}

	public function eventsAttrs($eventsAttrs)
	{
		$eventsAttrs['scrollDistance'] = array(
			'htmlAttrs' => array('class' => 'js-sg-onScroll-text', 'min' => 0),
			'infoAttrs' => array(
				'label' => 'After x distance',
				'info' => __('The scrolling distance after which the popup will be opened. Examples: 10px or 10% or 10em or 10rem', SG_POPUP_TEXT_DOMAIN)
			)
		);

		$eventsAttrs['scrollElement'] = array(
			'htmlAttrs' => array('class' => 'js-sg-onScroll-text'),
			'infoAttrs' => array(
				'label' => 'Element selector',
				'info' => __('CSS selector of the element after which the popup will be opened. Examples #blogpost1 or .blogpost.', SG_POPUP_TEXT_DOMAIN)
			)
		);


		return $eventsAttrs;
	}

	public function eventsTypes($eventsTypes)
	{
		$eventsTypes['scrollDistance'] = 'text';
		$eventsTypes['scrollElement'] = 'text';

		return $eventsTypes;
	}
}
