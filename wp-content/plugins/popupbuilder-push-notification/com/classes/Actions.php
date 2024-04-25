<?php
namespace sgpbpush;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		add_action('admin_menu', array($this, 'addSubMenu'));
		add_action('admin_init', array($this, 'additionalTableSettings'));
	}

	public function addSubMenu()
	{
		add_submenu_page(
			'edit.php?post_type=' . SG_POPUP_POST_TYPE,
			__('Push Notification', SG_POPUP_TEXT_DOMAIN),
			__('Push Notification', SG_POPUP_TEXT_DOMAIN),
			'sgpb_manage_options',
			SGPB_PUSH_NOTIFICATION_PAGE_KEY,
			array($this, 'pushNotificationPage')
		);
	}

	public function pushNotificationPage()
	{
		require_once(SGPB_PUSH_NOTIFICATION_VIEWS_PATH.'pushNotificationsSettings.php');
	}

	public function additionalTableSettings()
	{
		if (!get_option('sgpb-push-notification-table-edited')) {
			Installer::alterTable();
			update_option('sgpb-push-notification-table-edited', '1');
		}
	}
}
