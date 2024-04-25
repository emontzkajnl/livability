<?php
namespace sgpbm;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		add_action('admin_menu', array($this, 'addSubMenu'), 11);
		add_action('admin_post_sgpb_save_mailchimp_api_key', array($this, 'mailchimpApiKey'));
		add_action('sgpbDeletePopupData', array($this, 'popupDeleteAction'));
	}

	public function addSubMenu()
	{
		add_submenu_page('edit.php?post_type='.SG_POPUP_POST_TYPE, __('Mailchimp', SG_POPUP_TEXT_DOMAIN), __('Mailchimp', SG_POPUP_TEXT_DOMAIN), 'sgpb_manage_options', SGPB_POPUP_TYPE_MAILCHIMP, array($this, 'mailchimpSettings'));
	}

	public function mailchimpSettings()
	{
		require_once(SGPB_MAILCHIMP_VIEWS_PATH.'mailchimpSettings.php');
	}

	public function mailchimpApiKey()
	{
		if (isset($_POST)) {
			check_admin_referer('sgpbPopupBuilderMailchimpApiKeySave');
		}
		if(isset($_POST['mailchimp-api-key']) && $_POST['mailchimp-api-key'] != '') {
			update_option('SGPB_MAILCHIMP_API_KEY', $_POST['mailchimp-api-key']);
		}
		if (get_transient('sgpbm_mailchimp_api_status_request')){
			delete_transient('sgpbm_mailchimp_api_status_request');
		}
		wp_redirect(SG_POPUP_ADMIN_URL.'edit.php?post_type='.SG_POPUP_POST_TYPE.'&page='.SGPB_POPUP_TYPE_MAILCHIMP);
	}

	public function popupDeleteAction()
	{
		delete_option('SGPB_MAILCHIMP_API_KEY');
	}
}
