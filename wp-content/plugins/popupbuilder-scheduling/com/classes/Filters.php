<?php
namespace sgpbscheduling;
use sgpb\PopupBuilderActivePackage;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'schedulingDefaultOptions'), 10, 1);
		add_filter('sgpbAdditionalMetaboxes', array($this, 'schedulingMetaboxes'), 10, 1);
		add_filter('sgpbOptionAvailable', array($this, 'filterOption'), 10, 1);
	}

	public function schedulingDefaultOptions($options)
	{
		$options[] = array('name' => 'sgpb-schedule-status', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-schedule-weeks', 'type' => 'array', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-schedule-start-time', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-schedule-end-time', 'type' => 'text', 'defaultValue' => '');

		return $options;
	}

	public function schedulingMetaboxes($metaboxes)
	{
		$metaboxes['otherConditionsMetaBoxView'] = array(
			'key' => 'otherConditionsMetaBoxView',
			'displayName' => 'Scheduling',
			'short_description' => 'Schedule your popup for a particular day or for a selected timeframe',
			'filePath' => SGPB_SCHEDULING_VIEWS_PATH.'scheduling.php',
			'priority' => 'high'
		);

		return $metaboxes;
	}

	public function filterOption($filterOption)
	{
		if (isset($filterOption['name'])) {
			$name = $filterOption['name'];
			if ($name == 'otherConditionsMetaBoxView') {
				$filterOption['status'] = true;
			}

			return $filterOption;
		}

		return $filterOption;
	}
}
