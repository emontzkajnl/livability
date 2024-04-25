<?php
namespace sgpban;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		add_action('admin_menu', array($this, 'addSubMenu'), 11);
		add_action('sgpbDeletePopupData', array($this, 'popupDeleteAction'));
	}

	public function addSubMenu()
	{
		add_submenu_page('edit.php?post_type='.SG_POPUP_POST_TYPE, __('Analytics', SG_POPUP_TEXT_DOMAIN), __('Analytics', SG_POPUP_TEXT_DOMAIN), 'sgpb_manage_options', SGPB_POPUP_TYPE_ANALYTICS, array($this, 'analyticsSettings'));
	}

	public function analyticsSettings()
	{
		require_once(SGPB_ANALYTICS_VIEWS_PATH.'analytics.php');
	}

	public function popupDeleteAction()
	{

	}
}