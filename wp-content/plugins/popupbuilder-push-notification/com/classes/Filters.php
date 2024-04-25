<?php
namespace sgpbpush;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11);
		}
		add_filter('sgpbPopupEvents', array($this, 'popupEvents'), 9, 1);
	}

	public function popupEvents($events)
	{
		foreach ($events as $eventKey => $eventData) {
			if (isset($eventData['param'])) {
				if ($eventData['param'] == SGPB_CSS_CLASS_ACTIONS_KEY) {
					$events[] = array('param' => 'pushNotification');
				}
			}
		}

		return $events;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_PUSH_NOTIFICATION] = SGPB_PUSH_NOTIFICATION_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_PUSH_NOTIFICATION] = __(SGPB_POPUP_TYPE_PUSH_NOTIFICATION_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_PUSH_NOTIFICATION] = SGPB_PUSH_NOTIFICATION_AVAILABLE_VERSION;

		return $popupType;
	}
}
