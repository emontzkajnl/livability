<?php
namespace sgpbrestriction;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11, 1);
		}
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_RESTRICTION] = SGPB_RESTRICTION_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_RESTRICTION] = __(SGPB_POPUP_TYPE_RESTRICTION_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_RESTRICTION] = SGPB_RESTRICTION_AVALIABLE_VERSION;

		return $popupType;
	}
}
