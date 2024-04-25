<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class SGPBSchedulingExtension implements SgpbIPopupExtension
{
	public function getScripts($pageName, $data)
	{
		$jsFiles = array();
		$scriptData = array();
		$localizeData = array();
		if ($pageName == 'editpage') {
			$jsFiles[] = array('folderUrl' => SGPB_SCHEDULING_JS_URL, 'filename' => 'jquery.datetimepicker.full.min.js', 'inFooter' => true);
			$jsFiles[] = array('folderUrl' => SGPB_SCHEDULING_JS_URL, 'filename' => 'Scheduling.js');
		}
		$scriptData = array(
			'jsFiles' => $jsFiles,
			'localizeData' => $localizeData
		);
		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		if ($page == 'editpage') {
			$cssFiles[] = array('folderUrl' => SGPB_SCHEDULING_CSS_URL, 'filename' => 'scheduling.css');
		}
		$cssData = array(
			'cssFiles' => $cssFiles
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $popupData)
	{
		$scriptData = array();
		$hasExitIntent = false;

		if (empty($popupData['popups'])) {
			return $scriptData;
		}

		foreach ($popupData['popups'] as $popup) {

			if (empty($popup)) {
				continue;
			}

			$popupId = $popup->getId();
			$eventsData = get_post_meta($popupId, 'sg_popup_events');

			if (empty($eventsData)) {
				continue;
			}

			$eventsData = $eventsData[0];
			if (!empty($eventsData[0])) {
				foreach ($eventsData[0] as $eventData) {
					if ($eventData['param'] == SGPB_SCHEDULING_ACTION_KEY) {
						$hasExitIntent = true;
					}
				}
			}
		}

		if (!$hasExitIntent) {
			return $scriptData;
		}

		$jsFiles = array();
		$localizeData = array();
		$jsFiles[] = array('folderUrl' => SGPB_SCHEDULING_JS_URL, 'filename' => 'jquery.datetimepicker.full.min.js');
		$jsFiles[] = array('folderUrl' => SGPB_SCHEDULING_JS_URL, 'filename' => 'Scheduling.js');

		$localizeData[] = array(

		);

		$scriptData = array(
			'jsFiles' => $jsFiles,
			'localizeData' => $localizeData
		);

		return $scriptData;
	}

	public function getFrontendStyles($page, $popupData)
	{

	}
}
