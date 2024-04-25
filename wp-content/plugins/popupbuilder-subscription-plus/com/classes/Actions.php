<?php
namespace sgpbsubscriptionplus;
use sgpb\AdminHelper as PopupAdminHelper;
use sgpbform\FormCreator;

if (!file_exists(@SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php')) {
	return '';
}
require_once(@SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');
use sgpb\SGPopup;
use sgpb\AdminHelper;
use sgpbsubscriptionplus\SubscriptionPlusRegisterPostType as SubscriptionPlusRegisterPostType;

class Actions
{
	public $customPostTypeObj;

	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_action('admin_head', array($this, 'customStyles'));
		add_action('init', array($this, 'subscriptionPlusPostTypeInit'), 9999);
		add_action('sgpbProcessAfterSuccessfulSubmission', array($this ,'sendSuccessEmails'), 10, 2);
		add_action('sgpbProcessAfterSuccessfulSubmission', array($this ,'sendConfirmEmailToSubscriber'), 10, 2);
		add_action('sgpbProcessAfterSuccessfulSubmission', array($this ,'sendIntegrationsData'), 10, 2);
		add_action('admin_menu', array($this, 'addSubMenu'));
		add_action('add_meta_boxes', array($this, 'templateMetaboxes'), 100);
		add_filter('sgpbSubscribersContent', array($this, 'subscribersContent'), 100, 1);
		add_action('manage_'.SG_POPUP_AUTORESPONDER_POST_TYPE.'_posts_custom_column' , array($this, 'autorespondersTableColumnsValues'), 10, 2);
		add_action('sgpbAfterEmailConfirmationActions', array($this ,'sendSuccessEmails'), 10, 2);
		add_filter('views_edit-'.SG_POPUP_TEMPLATE_POST_TYPE, array($this, 'allEmailTemplatesMainActionButtons'), 10);
	}

	public function allEmailTemplatesMainActionButtons($views)
	{
		require_once(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'emailTemplates/allEmailTemplates.php');

		return $views;
	}

	public function sendConfirmEmailToSubscriber($popupPostId, $formData)
	{
		global $wpdb;
		$popup = SGPopup::find($popupPostId);
		$doubleOptions = $popup->getOptionValue('sgpb-subs-double-option');

		$subscriberEmail = $formData['email'];
		$subscriber = SubscriptionPlusAdminHelper::findSubscribersByEmail($subscriberEmail, $popupPostId);
		$subscriberId = @$subscriber['id'];

		if (!isset($doubleOptions) || !$doubleOptions) {
			$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' SET emailStatus = %d WHERE id = %d', 1, $subscriberId);
			$wpdb->query($sql);

			return false;
		}
		$adminEmail = get_option('admin_email');
		$subject = __('Confirmation Email', SG_POPUP_TEXT_DOMAIN);
		$title = __('CONFIRM SUBSCRIPTION', SG_POPUP_TEXT_DOMAIN);
		$blogname = wp_specialchars_decode( get_bloginfo('name') );
		$homeUrl = get_home_url();

		$confirmLink = get_home_url();
		$confirmLink .= '?sgpbEmailConfirm='.md5($subscriberId.$subscriberEmail);
		$confirmLink .= '&email='.$subscriberEmail;
		$confirmLink .= '&popup='.$popupPostId;
		$confirmLink = '<br><a href="'.$confirmLink.'">'.$title.'</a><br>';

		$mailContent = __("Hi there!<br><br>
					Congratulations you have completed the first step by subscribing to <b><a href=".esc_attr($homeUrl).">".$blogname."</a></b> Newsletter.
					Please, go with the link below, in order to confirm that you wish to receive marketing emails from us.<br><br>".$confirmLink."<br><br>
					If this email was not intended to you or you don’t want to subscribe, please ignore this message.",SG_POPUP_TEXT_DOMAIN);

		$headers = "MIME-Version: 1.0\r\n" .
		"From: ".$blogname ." <" .$adminEmail . ">\r\n" .
		"Reply-to: ".$adminEmail."\r\n" .
		"Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";

		$result = wp_mail($subscriberEmail, $subject, $mailContent, $headers);
	}

	public static function confirmSubscriber($params = array())
	{
		global $wpdb;
		$homeUrl = get_home_url();

		if (empty($params)) {
			return false;
		}

		$prepareSql = $wpdb->prepare('UPDATE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' SET emailStatus = 1 WHERE id = %s ', $params['subscriberId']);
		$wpdb->query($prepareSql);

		_e('<span>You have successfully confirmed your email address. <a href="'.esc_attr($homeUrl).'">Click here</a> to go to the home page.</spsan>', SG_POPUP_TEXT_DOMAIN);
		$userData = SubscriptionPlusAdminHelper::getSubscriberDetails($params['subscriberId'], $params['popup']);
		$userData = json_decode($userData, true);
		$userData['subscriberId'] = $params['subscriberId'];
		do_action('sgpbAfterEmailConfirmationActions', $params['popup'], $userData);
		//TODO: render a view instead of a blank page.
		wp_die();

	}

	public function customStyles()
	{
		echo '<style>.subscription-popup {background-image: url("'.SG_POPUP_IMG_URL.'/subscriptionPlusTypeIcon.png");}</style>';
	}

	private function subscriberFields() {
		return  array('id', 'firstName', 'lastName', 'email', 'cDate', 'subscriptionType');
	}

	public function subscribersContent($content)
	{
		$query = PopupAdminHelper::subscribersRelatedQuery('', 'submittedData, subscriptionType, ');
		$fields = $this->subscriberFields();

		if (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
			$orderBy = sanitize_text_field($_GET['orderby']);
			if (!in_array($orderBy, $fields)){
				wp_redirect(get_home_url());
				exit();
			}
			if (isset($_GET['order']) && !empty($_GET['order'])) {
				$order = array('ASC', 'DESC');
				if (!in_array(sanitize_text_field($_GET['order']), $order)){
					wp_redirect(get_home_url());
					exit();
				}
				$query .= ' ORDER BY '.esc_sql($_GET['orderby']).' '.esc_sql($_GET['order']);
			}
		}
		global $wpdb;
		$subscribers = $wpdb->get_results($query, ARRAY_A);

		$popupId = $subscribers[0]['subscriptionType'];
		parse_str($subscribers[0]['submittedData'], $submittedData);

		$popupObj = SGPopup::find($popupId);
		$obj = FormCreator::createSubscriptionFormObj($popupObj);
		if (!is_object($obj)) {
			return '';
		}

		$customContent = '';
		$showSettings = $obj->getFieldNameAndValues($submittedData);
		$keys = array_keys($showSettings);
		// free subscibers list
		if (empty($showSettings)) {
			$keys[] = 'email';
			$keys[] = 'firstName';
			$keys[] = 'lastName';
		}

		$keys[] = 'date';
		$keys[] = 'popup';

		foreach ($keys as $value) {
			$customContent .= $value;
			if ($value != 'popup') {
				$customContent .= ',';
			}
		}
		$customContent .= "\n";

		foreach ($subscribers as $values) {
			parse_str($values['submittedData'], $currentData);
			// free subscibers list
			if (empty($currentData)) {
				$customContent .= $values['email'].',';
				$customContent .= $values['firstName'].',';
				$customContent .= $values['lastName'].',';
			}
			foreach ($currentData as $key => $value) {
				$customContent .= $value;
				if ($key != 'sgpb-subs-hidden-checker') {
					$customContent .= ',';
				}
			}
			$customContent .= $values['cDate'].',';
			$customContent .= $values['subscriptionTitle'];

			$customContent .= "\n";
		}

		return $customContent;
	}

	public function subscriptionPlusPostTypeInit()
	{
		$this->customPostTypeObj = new SubscriptionPlusRegisterPostType();
		$confirmArgs = SubscriptionPlusAdminHelper::collectConfirmArgs();
		if (!empty($confirmArgs)) {
			$this->confirmSubscriber($confirmArgs);
		}
	}

	public function sendIntegrationsData($popupPostId, $formData)
	{
		$submissionData = @$_POST['formData'];
		parse_str($submissionData, $formData);

		$popup = SGPopup::find($popupPostId);
		// we need to get all active integrations(activated from integrations menu)
		$options = $popup->getOptionValue('sgpb-subs-active-integrations');
		$options = json_decode($options, true);
		$emailIntegrationsData = get_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS);
		foreach ($options as $key => $value) {
			// get provider object
			$obj = EmailIntegrations::createProviderObjectById($key);
			// get provider API object
			$obj = $obj::api($emailIntegrationsData['sgpb-'.$key]);
			$data = EmailIntegrations::prepareDataToSend($formData, $obj, $popupPostId);
			$res = $obj->addEntryFields($data, $value['list']);
		}
	}

	public function sendSuccessEmails($popupPostId, $subscriptionDetails)
	{
		$subscriberId = 0;
		$popup = SGPopup::find($popupPostId);
		$doubleOptions = $popup->getOptionValue('sgpb-subs-double-option');
		// if it is new version and double optin already exists
		$databaseUpdated = get_option('sgpbDontAlterSubmittedEmailStatusSet');
		if ((!$databaseUpdated && !isset($doubleOptions)) || ($databaseUpdated && (isset($doubleOptions) && $doubleOptions == 'checked') && !isset($subscriptionDetails['subscriberId']))) {
			return false;
		}
		if (!is_object($popup)) {
			return false;
		}
		if (isset($subscriptionDetails['subscriberId'])) {
			$subscriberId = $subscriptionDetails['subscriberId'];
		}

		$popupOptions = $popup->getOptions();
		$adminUserName = 'admin';

		$adminEmail = get_option('admin_email');
		$userData = @get_user_by_email($adminEmail);

		if (!empty($userData)) {
			$adminUserName = $userData->display_name;
		}

		if (!isset($popupOptions['sgpb-subs-notifications-email'])) {
			return false;
		}
		$notificationEmail = $popupOptions['sgpb-subs-notifications-email'];
		$receiverEmailsArray = array($notificationEmail);
		//get all emails
		if (!empty($notificationEmail)) {
			$receiverEmailsArray = array();
			$notificationEmail = str_replace(' ', '', $notificationEmail);
			$notificationEmail = explode(',', $notificationEmail);
			foreach ($notificationEmail as $mail) {
				if (is_email($mail)) {
					$receiverEmailsArray[] = $mail;
				}
			}
		}
		// array with index 0 exists
		$newSubscriberEmailHeader = AdminHelper::getEmailHeader($receiverEmailsArray[0]);
		$subscriberEmail = $subscriptionDetails['email'];
		$subscriber = SubscriptionPlusAdminHelper::findSubscribersByEmail($subscriberEmail, $popupPostId);
		$approvedEmail = @$subscriber['emailStatus'];
		if ($approvedEmail == 1) {
			return false;
		}

		if (!empty($popupOptions['sgpb-subs-enable-email-notifications'])) {
			// notify about new subscriber
			$newSubscriberEmailTitle = __('New Subscriber', SG_POPUP_TEXT_DOMAIN);

			$templateFilePath = SGPB_SUBSCRIPTION_PLUS_TEMPLATES_PATH.'newSubscriptrionEmail.html';
			$newSubscriptionEmailTemplate = SubscriptionPlusAdminHelper::pushCustomFieldsShortcodesIntoTemplate($templateFilePath, $popupPostId, $subscriberId);

			$title = $popup->getTitle();
			$replaceMaping = array(
				'/\[adminUserName]/' => $adminUserName,
				'/\[popupTile]/' => $title,
			);

			foreach ($replaceMaping as $key => $value) {
				$newSubscriptionEmailTemplate = preg_replace($key, $value, $newSubscriptionEmailTemplate);
			}

			$sendStatus = wp_mail($receiverEmailsArray, $newSubscriberEmailTitle, $newSubscriptionEmailTemplate, $newSubscriberEmailHeader); //return true or false
		}
	}

	public function addSubMenu()
	{
		$this->customPostTypeObj->addSubMenu();
	}

	public function templateMetaboxes()
	{
		$this->customPostTypeObj->addTemplatesShortcodeMetaboxes();
	}

	public function autorespondersTableColumnsValues($column, $postId)
	{
		$postId = (int)$postId;// Convert to int for security reasons
		$switchButton = '';
		$isActive = 'checked';
		global $post_type;

		if ($postId) {
			$alreadySavedOptions = get_post_meta($postId, 'sgpb_autoresponder_options', true);
		}
		if (empty($alreadySavedOptions) && $post_type == SG_POPUP_POST_TYPE) {
			return false;
		}
		if ($column == 'onOff') {
			$autoresponderPostStatus = get_post_status($postId);
			if ($autoresponderPostStatus == 'publish' || $autoresponderPostStatus == 'draft') {
				if (isset($alreadySavedOptions['sgpb-is-active']) && $alreadySavedOptions['sgpb-is-active'] == '') {
					$isActive = '';
				}
			}
			$switchButton .= '<label class="sgpb-switch">';
			$switchButton .= '<input class="sg-switch-checkbox" data-switch-id="'.$postId.'" type="checkbox" '.$isActive.'>';
			$switchButton .= '<div class="sgpb-slider sgpb-round"></div>';
			$switchButton .= '</label>';
			echo $switchButton;
		}
	}
}
