<?php
require_once(SG_POPUP_EXTENSION_PATH.'SgpbIPopupExtension.php');

class PopupBuilderInactivityExtension implements SgpbIPopupExtension
{
	public function getScripts($page, $data)
	{
		if (empty($data['popupType']) || @$data['popupType'] != SGPB_POPUP_TYPE_INACTIVITY) {
			return false;
		}

		$jsFiles = array();
		$localizeData = array();

		$scriptData = array(
			'jsFiles' => apply_filters('sgpbInactivityAdminJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbInactivityAdminJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbInactivityAdminJs', $scriptData);

		return $scriptData;
	}

	public function getStyles($page, $data)
	{
		$cssFiles = array();
		// for current popup type page load and for popup types pages too
		if (@$data['popupType'] == SGPB_POPUP_TYPE_INACTIVITY || $page == 'popupType') {
			// here we will include current popup type custom styles
		}

		$cssData = array(
			'cssFiles' => apply_filters('sgpbInactivityAdminCssFiles', $cssFiles)
		);

		return $cssData;
	}

	public function getFrontendScripts($page, $data)
	{
		$jsFiles = array();
		$localizeData = array();

		$hasInactivityPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasInactivityPopup) {
			return false;
		}

		$jsFiles[] = array('folderUrl'=> SGPB_INACTIVITY_JS_URL, 'filename' => 'Inactivity.js', 'dep' => array('PopupBuilder.js'));
		$scriptData = array(
			'jsFiles' => apply_filters('sgpbInactivityJsFiles', $jsFiles),
			'localizeData' => apply_filters('sgpbInactivityJsLocalizedData', $localizeData)
		);

		$scriptData = apply_filters('sgpbInactivityJsFilter', $scriptData);

		return $scriptData;
	}

	public function getFrontendStyles($page, $data)
	{
		$cssFiles = array();

		$hasInactivityPopup = $this->hasConditionFromLoadedPopups($data['popups']);

		if (!$hasInactivityPopup) {
			return false;
		}
		$cssData = array(
			'cssFiles' => apply_filters('sgpbInactivityCssFiles', $cssFiles)
		);

		return $cssData;
	}

	protected function hasConditionFromLoadedPopups($popups)
	{
		$hasType = false;

		foreach ($popups as $popup) {
			if (!is_object($popup)) {
				continue;
			}
			$events = $popup->getEvents();

			if (empty($events)) {
				continue;
			}

			foreach ($events as $event) {
				if (isset($event['param']) && $event['param'] == SGPB_INACTIVITY_EVENT_KEY) {
					$hasType = true;
					break;
				}
			}
		}

		return $hasType;
	}
}
