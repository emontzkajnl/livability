<?php
namespace sgpbadb;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_filter('sgPopupEventsData', array($this, 'addPopupEventColumn'), 2, 1);
		add_filter('sgPopupEventTypes', array($this, 'addPopupEventTypes'), 2, 1);
		add_filter('sgEventsHiddenData', array($this, 'eventHiddenData'), 2, 1);
		add_filter('sgPopupEventAttrs', array($this, 'addPopupEvents'), 2, 1);
	}

	public function addPopupEvents($eventsAttrs)
	{
		$eventsAttrs[SGPB_AD_BLOCK_ACTION_KEY] = array(
			'htmlAttrs' => array('class' => 'sgpb-input'),
			'infoAttrs' => array(
				'label' => __('Delay', SGPB_AD_BLOCK_TEXT_DOMAIN)
			)
		);

		return $eventsAttrs;
	}

	public function addPopupEventColumn($eventsColumnData)
	{
		$eventsColumnData['param'][SGPB_AD_BLOCK_ACTION_KEY] = __('AdBlock', SGPB_AD_BLOCK_TEXT_DOMAIN);
		$eventsColumnData[SGPB_AD_BLOCK_ACTION_KEY] = 0;

		return $eventsColumnData;
	}

	public function addPopupEventTypes($eventColumnType)
	{
		$eventColumnType[SGPB_AD_BLOCK_ACTION_KEY] = 'number';
		$eventsColumnData[SGPB_AD_BLOCK_ACTION_KEY] = 'number';

		return $eventColumnType;
	}

	public function eventHiddenData($hiddenData)
	{
		$eventsColumnData[SGPB_AD_BLOCK_ACTION_KEY] = 0;

		return $hiddenData;
	}
}
