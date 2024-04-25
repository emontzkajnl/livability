<?php
namespace sgpbaw;
use sgpb\AdminHelper as PopupBuilderAdminHelper;

class Ajax
{
	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		add_action('wp_ajax_sgpb_aweber_disconnect_from_aweber_api', array($this, 'aweberDisconnect'));
		add_action('wp_ajax_sgpb_aweber_change_list', array($this, 'aweberChangeList'));
		add_action('wp_ajax_sgpb_aweber_change_form', array($this, 'aweberChangeForm'));
		add_action('wp_ajax_list_webform_subscribe', array($this, 'aweberSendSubscribe'));
		add_action('wp_ajax_nopriv_list_webform_subscribe', array($this, 'aweberSendSubscribe'));
	}

	public function aweberSendSubscribe()
	{
		parse_str($_POST['formData'], $formData);

		$sgAweberApiObj = new SGPBAWeberApi();
		$sgAweberApiObj->setFormData($formData);
		$data = $sgAweberApiObj->aweberFormSubmitting();

		echo json_encode($data);
		die();
	}

	public function aweberChangeForm()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$sgpbAweberObj = new SGPBAWeberApi();
		$listId = (int)$_POST['listId'];
		$webFormId = (int)$_POST['webFormId'];

		echo $sgpbAweberObj->getWebformHtml($listId, $webFormId);
		wp_die();
	}

	public function aweberDisconnect()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		delete_option('sgpbAccessToken');
		delete_option('sgpbAccessTokenSecret');

		echo SGPB_AJAX_STATUS_TRUE;
		wp_die();
	}

	public function aweberChangeList()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$listId = @(int)$_POST['listId'];
		$signupFormId = @(int)$_POST['formId'];
		$data = array();
		$sgpbAweberObj = new SGPBAWeberApi();

		$signupsIdTitles = $sgpbAweberObj->getListIdAndTitles($listId);

		if (!empty($signupsIdTitles) && empty($signupFormId)) {

			$keys = array_keys($signupsIdTitles);
			$signupFormId = $keys[0];
		}

		$data['webForms'] =  PopupBuilderAdminHelper::createSelectBox($signupsIdTitles, $signupFormId, array('name' => 'sgpb-aweber-signup-form', 'class' => 'js-sg-select2 js-sgpb-aweber-signup-forms'));
		$data['webFormHtml'] = $sgpbAweberObj->getWebformHtml($listId, $signupFormId);

		echo json_encode($data);
		wp_die();
	}
}

new Ajax();
