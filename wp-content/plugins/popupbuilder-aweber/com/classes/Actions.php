<?php
namespace sgpbaw;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		add_action('admin_menu', array($this, 'addSubMenu'), 11);
		add_action('sgpbDeletePopupData', array($this, 'deleteAWeberSettings'));
	}

	public function addSubMenu()
	{
		add_submenu_page('edit.php?post_type='.SG_POPUP_POST_TYPE, __('AWeber', SG_POPUP_TEXT_DOMAIN), __('AWeber', SG_POPUP_TEXT_DOMAIN), 'sgpb_manage_options', SGPB_POPUP_TYPE_AWEBER, array($this, 'aweverSettings'));
	}

	public function aweverSettings()
	{
		require_once(SGPB_AWEBER_VIEWS_PATH.'aweberSettings.php');
	}

	public function deleteAWeberSettings()
	{
		delete_option('sgpbAccessToken');
		delete_option('sgpbAccessTokenSecret');
		delete_option('sgpbRequestTokenSecret');
	}
}
