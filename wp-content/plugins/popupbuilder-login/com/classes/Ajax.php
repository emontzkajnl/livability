<?php
namespace sgpblogin;

class Ajax
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_action('wp_ajax_sgpb_login_action', array($this, 'loginAction'));
		add_action('wp_ajax_nopriv_sgpb_login_action', array($this, 'loginAction'));
	}

	public function loginAction()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$userForm = $_POST['userForm'];
		parse_str($userForm, $userForm);
		$remember = false;
		$result = array(
			'status' => 200,
			'message' => __('You have successful login.', SG_POPUP_TEXT_DOMAIN)
		);

		if (!empty($_POST['sgpb-remember-me'])) {
			$remember = true;
		}

		$userName = sanitize_text_field($_POST['userName']);
		$passwordName = sanitize_text_field($_POST['passwordName']);

		$userLoginValue = sanitize_text_field($userForm[$userName]);
		$password = sanitize_text_field($userForm[$passwordName]);

		$creds = array();
		$creds['user_login'] = $userLoginValue;
		$creds['user_password'] = $password;
		$creds['remember'] = $remember;

		$user = wp_signon($creds, false);

		if (is_wp_error($user)) {
			$result['status'] = 400;
			$result['message'] = $user->get_error_message();
			echo json_encode($result);
			wp_die();
		}

		wp_set_auth_cookie($user->ID, true);

		echo json_encode($result);
		wp_die();
	}
}

new Ajax();
