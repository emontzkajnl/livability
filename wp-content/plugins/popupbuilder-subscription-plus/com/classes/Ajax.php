<?php
namespace sgpbsubscriptionplus;
use sgpb\SGPopup;
use sgpb\AdminHelper;
use sgpbform\SubscriptionForm;
use sgpbsubscriptionplus\SubscriptionPlusAdminHelper as SubscriptionPlusAdminHelper;
use sgpbsubscriptionplus\EmailIntegrations as EmailIntegrations;

class Ajax
{

	private $postData;

	public function __construct()
	{
		$this->init();
	}

	public function setPostData($postData)
	{
		$this->postData = $postData;
	}

	public function getPostData()
	{
		return $this->postData;
	}

	/**
	 * Return ajax param form post data by key
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 *
	 * @return string $value
	 */
	public function getValueFromPost($key)
	{
		$postData = $this->getPostData();
		$value = '';

		if (!empty($postData[$key])) {
			$value = $postData[$key];
		}

		return $value;
	}

	private function init()
	{
		add_action('wp_ajax_sgpb_subscription_plus_subscription', array($this, 'subscriptionAction'));
		add_action('wp_ajax_nopriv_sgpb_subscription_plus_subscription', array($this, 'subscriptionAction'));
		add_action('wp_ajax_sgpb_process_after_submission', array($this, 'sgpbSubsciptionFormSubmittedAction'));
		add_action('wp_ajax_nopriv_sgpb_process_after_submission', array($this, 'sgpbSubsciptionFormSubmittedAction'));
		add_action('wp_ajax_sgpb_subscription_plus_form_live_preview', array($this, 'livePreview'));
		// autoresponder
		add_action('wp_ajax_sgpb_change_autoresponder_status', array($this, 'changeAutoresponderStatus'), 0, 100);
		// newsletter
		add_action('wp_ajax_sgpb_newsletter_custom_form_fields', array($this, 'newsletterCustomFormFields'));
		// emailIntegrations
		add_action('wp_ajax_sgpb_email_integrations_connect', array($this, 'emailIntegrationsConnect'));
		add_action('wp_ajax_sgpb_email_integrations_disconnect', array($this, 'emailIntegrationsDisconnect'));
	}

	public function emailIntegrationsDisconnect()
	{
		$data = $_POST;
		$appId = $data['data']['appID'];
		if (!isset($appId)) {
			wp_die('');
		}

		$emailIntegrationsData = get_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS);
		unset($emailIntegrationsData['sgpb-'.$appId]);
		$emailIntegrationsData = update_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS, $emailIntegrationsData);

		wp_die('true');

	}

	public function emailIntegrationsConnect()
	{
		$data = $_POST;
		$appId = $data['data']['appID'];
		if (!isset($appId)) {
			wp_die(SGPB_AJAX_STATUS_FALSE);
		}

		$submitedData = $data['data']['configData'];
		$emailIntegrationsData = get_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS);
		if (!is_array($emailIntegrationsData)) {
			$emailIntegrationsData = array();
		}
		$obj = EmailIntegrations::createProviderObjectById($appId);
		$result = $obj->configureApiKey($submitedData);
		if (empty($result['has_errors'])) {
			$optionName = 'sgpb-'.$appId;
			// if () {}
			$emailIntegrationsData[$optionName] = $submitedData;
			update_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS, $emailIntegrationsData);
		}

		if (empty($result['options'])) {
			$result['options'] = '';
		}

		$res = array(
			'errors' => $result['has_errors'],
			'html' => $result['html'],
			'options' => $result['options']
		);

		echo json_encode($res);
		wp_die();
	}

	public function changeAutoresponderStatus()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$postId = (int)$_POST['postId'];
		$options = get_post_meta($postId, 'sgpb_autoresponder_options', true);
		if (gettype($options) != 'array') {
			$options = json_decode($options);
		}
		$options['sgpb-is-active'] = $_POST['autoresponderStatus'];
		update_post_meta($postId, 'sgpb_autoresponder_options', $options);

		wp_die($postId);
	}

	public function livePreview()
	{
		$data = $_POST;
		require_once(SGPB_FORM_CLASSES_FORMS.'SubscriptionForm.php');
		$designOptions = $data['designOptions'];
		$fields = stripslashes_deep($data['fields']);
		$subscriptionObj = new SubscriptionForm($fields);
		$subscriptionObj->setStylesConfig($designOptions);
		// popup obj

		$popupClassName = 'SubscriptionPopup';

		if (!file_exists(SG_POPUP_CLASSES_POPUPS_PATH.$popupClassName.'.php')) {
			die(__('Popup class does not exist', SG_POPUP_TEXT_DOMAIN));
		}

		require_once(SG_POPUP_CLASSES_POPUPS_PATH.$popupClassName.'.php');
		$popupClassName = '\sgpb\\'.$popupClassName;
		$popupTypeObj = new $popupClassName();
		$popupTypeObj->setId($_POST['popupId']);

		echo $subscriptionObj->getLivePreview($popupTypeObj);
		wp_die();
	}

	public function newsletterCustomFormFields()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$newsletterData = stripslashes_deep($_POST['newsletterData']);
		$popupId = (int)$newsletterData['selectedPopupId'];
		$customFields = SubscriptionPlusAdminHelper::getCustomFormFieldsByPopupId($popupId);

		echo json_encode($customFields);
		wp_die();
	}

	public function sgpbSubsciptionFormSubmittedAction()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$this->setPostData($_POST);

		$submissionData = $this->getValueFromPost('formData');
		$popupPostId = (int)$this->getValueFromPost('popupPostId');
		parse_str($submissionData, $formData);
		if (empty($_POST)) {
			echo SGPB_AJAX_STATUS_FALSE;
			wp_die();
		}
		$email = sanitize_email($_POST['emailValue']);
		$firstName = sanitize_text_field($_POST['firstNameValue']);
		$lastName = sanitize_text_field($_POST['lastNameValue']);
		$userData = array(
			'email' => $email,
			'firstName' => $firstName,
			'lastName' => $lastName
		);
		$this->sendSuccessEmails($popupPostId, $userData);
		do_action('sgpbProcessAfterSuccessfulSubmission', $popupPostId, $userData);
	}

	public function sendSuccessEmails($popupPostId, $subscriptionDetails)
	{
		global $wpdb;
		$popup = SGPopup::find($popupPostId);

		if (!is_object($popup)) {
			return false;
		}
		$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;

		$getSubscriberCountQuery = $wpdb->prepare('SELECT COUNT(id) as countIds FROM '.$subscribersTableName.' WHERE subscriptionType = %d', $popupPostId);
		$count = $wpdb->get_row($getSubscriberCountQuery, ARRAY_A);

		$popupOptions = $popup->getOptions();
		$adminUserName = 'admin';

		$adminEmail = get_option('admin_email');
		$userData = @get_user_by_email($adminEmail);

		if (!empty($userData)) {
			$adminUserName = $userData->display_name;
		}

		$newSubscriberEmailHeader = AdminHelper::getEmailHeader($adminEmail);
		$takeReviewAfterFirstSubscription = get_option('sgpb-new-subscriber');

		if ($count['countIds'] == 1 && !$takeReviewAfterFirstSubscription) {
			// take review
			update_option('sgpb-new-subscriber', 1);
			$newSubscriberEmailTitle = __('Congrats! You have already 1 subscriber!', SG_POPUP_TEXT_DOMAIN);
			$reviewEmailTemplate = AdminHelper::getFileFromURL(SG_POPUP_EMAIL_TEMPLATES_URL.'takeReviewAfterSubscribe.html');
			$reviewEmailTemplate = preg_replace('/\[adminUserName]/', $adminUserName, $reviewEmailTemplate);
			$sendStatus = wp_mail($adminEmail, $newSubscriberEmailTitle, $reviewEmailTemplate, $newSubscriberEmailHeader); //return true or false
		}
	}

	public function subscriptionAction()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$postData = $_POST;
		$formDataStr = $_POST['formData'];
		$this->setPostData($postData);
		$submissionData = $this->getValueFromPost('formData');
		$popupPostId = (int)$this->getValueFromPost('popupPostId');
		parse_str($submissionData, $formData);
		$hiddenChecker = sanitize_text_field($formData['sgpb-subs-hidden-checker']);

		// this check is made to protect ourselves from bot
		if (!empty($hiddenChecker)) {
			echo 'Bot';
			wp_die();
		}

		$status = SGPB_AJAX_STATUS_FALSE;

		if (empty($formData)) {
			echo $status;
			wp_die();
		}

		global $wpdb;

		$date = date('Y-m-d');
		$email = sanitize_email($postData['emailValue']);
		$firstName = sanitize_text_field($postData['firstNameValue']);
		$lastName = sanitize_text_field($postData['lastNameValue']);

		$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;

		$getSubscriberQuery = $wpdb->prepare('SELECT id FROM '.$subscribersTableName.' WHERE email = %s AND subscriptionType = %d', $email, $popupPostId);
		$list = $wpdb->get_row($getSubscriberQuery, ARRAY_A);

		// When subscriber does not exist we insert to subscribers table otherwise we update user info
		if (empty($list['id'])) {
			$sql = $wpdb->prepare('INSERT INTO '.$subscribersTableName.' (firstName, lastName, email, cDate, subscriptionType, status, unsubscribed, submittedData, emailStatus) VALUES (%s, %s, %s, %s, %d, %d, %d, %s, %d) ', $firstName, $lastName, $email, $date, $popupPostId, 0, 0, $formDataStr, 0);
			$res = $wpdb->query($sql);
		}
		else {
			$sql = $wpdb->prepare('UPDATE '.$subscribersTableName.' SET firstName = %s, lastName = %s, email = %s, cDate = %s, subscriptionType = %d, submittedData = %s WHERE id = %d', $firstName, $lastName, $email, $date, $popupPostId, $formDataStr, $list['id']);
			$wpdb->query($sql);
			$res = 1;
		}

		if ($res) {
			$userData = array(
				'email' => $email,
				'firstName' => $firstName,
				'lastName' => $lastName,
				'formData' => $formDataStr
			);
			$status = SGPB_AJAX_STATUS_TRUE;
		}

		if (!empty($postData['registerUser'])) {
			$website = get_home_url();
			$userData = array(
				'user_login'  =>  $email,
				'user_email'  =>  $email,
				'user_url'    =>  $website,
				'user_pass'   =>  NULL  // When creating a new user, `user_pass` is expected.
			);

			$userId = wp_insert_user($userData);
			wp_new_user_notification($userId, ' ');
		}

		echo $status;
		wp_die();
	}

}
