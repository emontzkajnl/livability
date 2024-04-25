<?php
namespace sgpbinactivity;

class Filters
{
	public function __construct()
	{
		add_filter('sgPopupEventsData', array($this, 'eventsData'), 10, 1);
		add_filter('sgPopupEventAttrs', array($this, 'eventsAttrs'), 10, 1);
		add_filter('sgPopupEventTypes', array($this, 'eventsTypes'), 10, 1);
		add_filter('sgpbProEvents', array($this, 'proEvents'), 10, 1);
	}

	public function proEvents($events)
	{
		if (empty($events)) {
			return $events;
		}

		$key = array_search('inactivity', $events);
		if ($key !== false) {
			unset($events[$key]);
		}

		return $events;
	}

	public function eventsData($eventsData)
	{
		$eventsData['param'][SGPB_INACTIVITY_EVENT_KEY] = __('Inactivity', SG_POPUP_TEXT_DOMAIN);
		$eventsData[SGPB_INACTIVITY_EVENT_KEY] = 0;

		return $eventsData;
	}

	public function eventsAttrs($eventsAttrs)
	{
		$eventsAttrs[SGPB_INACTIVITY_EVENT_KEY] = array(
				'htmlAttrs' => array('class' => 'js-sg-inactivity-text', 'min' => 0, 'placeholder' => __('Seconds', SG_POPUP_TEXT_DOMAIN)),
				'infoAttrs' => array(
					'label' => 'Delay',
					'info' => __('Show the popup after some time of inactivity. The popup will appear if a user does nothing for some specific time mentioned.', SG_POPUP_TEXT_DOMAIN)
				)
			);

		return $eventsAttrs;
	}

	public function eventsTypes($eventsTypes)
	{
		$eventsTypes[SGPB_INACTIVITY_EVENT_KEY] = 'number';

		return $eventsTypes;
	}
}
