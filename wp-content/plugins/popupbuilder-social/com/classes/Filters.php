<?php
namespace sgpbsocial;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11);
		}
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_SOCIAL] = SGPB_SOCIAL_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_SOCIAL] = __(SGPB_POPUP_TYPE_SOCIAL_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_SOCIAL] = SGPB_SOCIAL_AVALIABLE_VERSION;


		return $popupType;
	}
}
