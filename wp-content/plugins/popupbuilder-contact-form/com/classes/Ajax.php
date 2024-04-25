<?php
namespace sgpbcontactform;
use sgpb\SGPopup;

Class Ajax
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

	public function init()
	{
		add_action('wp_ajax_sgpb_contact_submission', array($this, 'contactFormSubmission'));
		add_action('wp_ajax_nopriv_sgpb_contact_submission', array($this, 'contactFormSubmission'));
		add_action('wp_ajax_sgpb_cf_form_live_preview', array($this, 'livePreview'));
		add_action('wp_ajax_sgpb_sontacted_subscribers_delete', array($this, 'deleteSubscribers'));
	}

	public function deleteSubscribers()
	{
		global $wpdb;
		$subscribersId = array_map('sanitize_text_field', $_POST['subscribersId']);
		foreach ($subscribersId as $subscriberId) {
			$prepareSql = $wpdb->prepare('DELETE FROM '.$wpdb->prefix.SGPB_CONTACTED_SUBSCRIBERS_TABLE_NAME.' WHERE id = %d', $subscriberId);
			$wpdb->query($prepareSql);
		}
	}

	public function livePreview()
	{
		$data = $_POST;
		require_once(SGPB_CF_FORM_CLASSES_FORMS.'ContactbuilderForm.php');
		$designOptions = $data['designOptions'];
		$fields = stripslashes_deep($data['fields']);
		
		$cfObj = new ContactbuilderForm($fields);
		$cfObj->setStylesConfig($designOptions);

		// popup obj
		$popupClassName = 'ContactformPopup';

		if (!file_exists(SGPB_CONTACT_FORM_CLASSES_PATH.$popupClassName.'.php')) {
			die(__('Popup class does not exist', SG_POPUP_TEXT_DOMAIN));
		}

		require_once(SGPB_CONTACT_FORM_CLASSES_PATH.$popupClassName.'.php');
		$popupClassName = '\sgpb\\'.$popupClassName;
		$popupTypeObj = new $popupClassName();
		$popupTypeObj->setId($_POST['popupId']);

		echo $cfObj->getLivePreview($popupTypeObj);
		wp_die();
	}

	public function contactFormSubmission()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$submissionData = $_POST['formData'];

		parse_str($submissionData, $formData);

		if (empty($formData)) {
			echo SGPB_AJAX_STATUS_FALSE;
			wp_die();
		}

		$this->setPostData($formData);

		$popupId = (int)$_POST['popupPostId'];
		$popupTypeObj = SGPopup::createPopupTypeObjById($popupId);

		// if popup type object does not exit ajax's status is false
		if (!(isset($popupTypeObj) && $popupTypeObj instanceof SGPopup)) {
			echo SGPB_AJAX_STATUS_FALSE;
			wp_die();
		}
		$receiverEmailsArray = array();
		$receiverEmail = $popupTypeObj->getOptionValue('sgpb-contact-receiver-email');
		$contactToEmail = $popupTypeObj->getOptionValue('sgpb-contact-to-email');
		$contactToEmailSubject = $popupTypeObj->getOptionValue('sgpb-contact-to-email-subject');
		if ($contactToEmail) {
			$receiverEmail = $contactToEmail;
		}
		//get all emails
		if (!empty($receiverEmail)) {
			$receiverEmail = explode(',', $receiverEmail);
			$mail = '';
			foreach ($receiverEmail as $mail) {
				$mail = str_replace(' ', '', $mail);
				if (is_email($mail)) {
					$receiverEmailsArray[] = $mail;
				}
			}
		}
		else {
			$receiverEmailsArray[] = get_option('admin_email');
		}

		$popupPostTitle = get_the_title($popupId);
		$userEmail = isset($_POST['emailValue']) ? $_POST['emailValue'] : '';
		$userPhone = isset($_POST['phoneValue']) ? $_POST['phoneValue'] : '';
		$userAdvancedPhone = isset($_POST['advancedPhoneValue']) ? $_POST['advancedPhoneValue'] : '';
		$formData['popupId'] = $popupId;
		$allSubmittedData = AdminHelper::collectAjaxSubmittedData($formData);

		if (!empty($userEmail)) {
			$emailTitle = __('New message from ', SG_POPUP_TEXT_DOMAIN).$popupPostTitle.__(' popup by Popup Builder', SG_POPUP_TEXT_DOMAIN);
			if (!empty($contactToEmailSubject)) {
				$emailTitle = $contactToEmailSubject;
			}
			$message = __('Hi admin. One of your visitors has contacted you. You can find the details below:', SG_POPUP_TEXT_DOMAIN).'<br>';
			foreach ($allSubmittedData as $singleFieldDataLabel => $singleFieldDataValue) {
				$message .= '<b>'.__($singleFieldDataLabel, SG_POPUP_TEXT_DOMAIN).'</b>: '.$singleFieldDataValue.'<br>';
			}
			$headers  = 'MIME-Version: 1.0'."\r\n";
			$headers .= 'From: '.$userEmail."\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n"; //set UTF-8

			$sendStatus = wp_mail($receiverEmailsArray, $emailTitle, $message, $headers); //return true or false

			if (!empty($_POST['registerUser'])) {
				$website = get_home_url();
				$userData = array(
					'user_login' => $userEmail,
					'user_email' => $userEmail,
					'user_url' => $website,
					'user_pass' => NULL  // When creating a new user, `user_pass` is expected.
				);

				$userId = wp_insert_user($userData);
				wp_new_user_notification($userId, ' ');
			}
		} else {
			$userEmail = !empty($userPhone) ? $userPhone : $userAdvancedPhone;
			$sendStatus = SGPB_AJAX_STATUS_TRUE;
		}

		global $wpdb;
		$tableName = $wpdb->prefix.SGPB_CONTACTED_SUBSCRIBERS_TABLE_NAME;
		$getSubscriberCountQuery = $wpdb->prepare('SELECT COUNT(id) as countIds FROM '.$tableName.' WHERE email = %s', $userEmail);
		$count = $wpdb->get_row($getSubscriberCountQuery, ARRAY_A);
		$cDate = date('Y-m-d');

		if ($count['countIds'] == 0) {
			$allSubmittedData = json_encode($allSubmittedData);
			$sql = $wpdb->prepare('INSERT INTO '.$tableName.' (`email`, `cDate`, `submittedData`, `popupId`) values (%s, %s, %s, %d)', $userEmail, $cDate, $allSubmittedData, $popupId);
			$wpdb->query($sql);
		}

		$ajaxStatus = ($sendStatus) ? SGPB_AJAX_STATUS_TRUE : SGPB_AJAX_STATUS_FALSE;

		echo $ajaxStatus;
		wp_die();
	}
}

new Ajax();
