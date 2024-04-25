<?php
namespace sgpbsubscriptionplus;

if (!file_exists(@SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php')) {
	return '';
}

require_once(@SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');
use sgpb\SGPopup;
use sgpb\AdminHelper;
use sgpbsubscriptionplus\AutoresponderRegisterPostType as AutoresponderRegisterPostType;

class AutoresponderActions
{
	public $customPostTypeObj;

	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_action('init', array($this, 'autoresponderPostTypeInit'), 9999);
		add_action('add_meta_boxes', array($this, 'autoresponderMetaboxes'), 100);
		add_action('save_post', array($this, 'savePost'), 100, 3);
		add_action('admin_head', array($this, 'hideDraftsForAutoresponders'));
		add_action('sgpbProcessAfterSuccessfulSubmission', array($this ,'prepareToSend'), 10, 2);
		add_filter('views_edit-'.SG_POPUP_AUTORESPONDER_POST_TYPE, array($this, 'autoresponderMainActionButtons'), 10);
	}
	public function autoresponderMainActionButtons($views)
	{
		require_once(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'autoresponder/autoresponder.php');

		return $views;
	}
	public function hideDraftsForAutoresponders()
	{
		if (!empty($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_AUTORESPONDER_POST_TYPE) {
			echo '<style>
				#post-preview,#save-post {
					display:none !important;
				}
			</style>';
		}
	}

	public function prepareToSend($popupPostId, $subscriptionDetails)
	{
		$activeAutoresponders = Autoresponder::getAllAutorespondersForCurrentPopup($popupPostId);

		$adminEmail = get_option('admin_email');
		$args = array();

		$emailSendingData = array();
		$emailSendingData['subject'] = '';
		$emailSendingData['fromText'] = '';
		$emailSendingData['fromEmail'] = '';
		$emailSendingData['firstName'] = @$subscriptionDetails['firstName'];
		$emailSendingData['lastName'] = @$subscriptionDetails['lastName'];
		$emailSendingData['replyTo'] = $adminEmail;
		$emailSendingData['subscriptionFormId'] = $popupPostId;
		$emailSendingData['emailTemplate'] = AdminHelper::getFileFromURL(SGPB_SUBSCRIPTION_PLUS_TEMPLATES_URL.'defaultAutoresponderTemplate.html');
		$emailSendingData['subscriberEmail'] = $subscriptionDetails['email'];

		$popup = SGPopup::find($popupPostId);
		if (empty($popup)) {
			$emailSendingData['popupTitle'] = 'Popup Builder';
		}
		$emailSendingData['popupTitle'] = $popup->getTitle();
		foreach ($activeAutoresponders as $autoresponderOptions) {
			if (isset($autoresponderOptions['sgpb-autoresponder-subject'])) {
				$emailSendingData['subject'] = $autoresponderOptions['sgpb-autoresponder-subject'];
			}
			if (isset($autoresponderOptions['sgpb-autoresponder-from-name'])) {
				$emailSendingData['fromText'] = $autoresponderOptions['sgpb-autoresponder-from-name'];
			}
			if (isset($autoresponderOptions['sgpb-autoresponder-from-email'])) {
				$emailSendingData['fromEmail'] = $autoresponderOptions['sgpb-autoresponder-from-email'];
			}
			if (isset($autoresponderOptions['sgpb-autoresponder-reply-to'])) {
				$emailSendingData['replyTo'] = $autoresponderOptions['sgpb-autoresponder-reply-to'];
			}
			if (isset($autoresponderOptions['sgpb-autoresponder-email-template'])) {
				$templateId = $autoresponderOptions['sgpb-autoresponder-email-template'];
				$args['templateId'] = $templateId;
				$emailSendingData['emailTemplate'] = EmailTemplate::getFullTemplateHtml($args);
			}

			Autoresponder::send($emailSendingData);
		}

		echo SGPB_AJAX_STATUS_TRUE;
		wp_die();
	}

	public function autoresponderPostTypeInit()
	{
		$this->customPostTypeObj = new AutoresponderRegisterPostType();
	}

	public function autoresponderMetaboxes()
	{
		$this->customPostTypeObj->addPopupMetaboxes();
	}

	public function savePost($postId = 0, $post = array())
	{
		$isGutenbergInUse = false;
		if (!count($_POST)) {
			$isGutenbergInUse = true;
		}
		// we need to compare the post(publish) date and modified date, if they are the same, then it's a new post
		if ((!empty($post) || $isGutenbergInUse) && $post->post_modified_gmt == $post->post_date_gmt) {
			// exclude for popup builder post type and autoresponder
			if ($post->post_type != SG_POPUP_AUTORESPONDER_POST_TYPE && $post->post_type != SG_POPUP_POST_TYPE) {
				$activeAutoresponders = Autoresponder::getMatchAutoresponders($post, $isGutenbergInUse);
				if (!empty($activeAutoresponders) && $post->post_status == 'publish') {
					$this->prepareAutorespondersToSend($post, $activeAutoresponders);
				}
			}
		}
		$postData = SGPopup::parsePopupDataFromData($_POST);
		$postData['sgpb-autoresponder-id'] = $postId;
		if (!empty($post) && $post->post_type == SG_POPUP_AUTORESPONDER_POST_TYPE) {
			Autoresponder::create($postData);
		}
	}

	public function prepareAutorespondersToSend($post = array(), $activeAutoresponders = array())
	{
		$dataFromPost = array();
		$args = array();
		$adminEmail = get_option('admin_email');
		if (empty($post) || empty($activeAutoresponders)) {
			return $dataFromPost;
		}

		foreach ($activeAutoresponders as $autoresponder) {
			if (empty($autoresponder['sgpb-autoresponder-lists'])) {
				continue;
			}
			$popupIds = $autoresponder['sgpb-autoresponder-lists'];
			foreach ($popupIds as $popupId) {
				$subscribers = SubscriptionPlusAdminHelper::getAllSubscribersByPopupId($popupId);
				foreach ($subscribers as $subscriber) {
					$emailSendingData = array();
					$emailSendingData['subject'] = '';
					$emailSendingData['fromText'] = '';
					$emailSendingData['fromEmail'] = '';
					$emailSendingData['firstName'] = @$subscriber['firstName'];
					$emailSendingData['lastName'] = @$subscriber['lastName'];
					$emailSendingData['replyTo'] = $adminEmail;
					$emailSendingData['subscriptionFormId'] = $popupId;
					$emailSendingData['emailTemplate'] = AdminHelper::getFileFromURL(SGPB_SUBSCRIPTION_PLUS_TEMPLATES_URL.'defaultAutoresponderTemplate.html');
					$emailSendingData['subscriberEmail'] = $subscriber['email'];
					$emailSendingData['popupTitle'] = get_the_title($popupId);
					if (empty($emailSendingData['popupTitle'])) {
						$emailSendingData['popupTitle'] = 'Popup Builder';
					}

					if (isset($autoresponder['sgpb-autoresponder-subject'])) {
						$emailSendingData['subject'] = $autoresponder['sgpb-autoresponder-subject'];
					}
					if (isset($autoresponder['sgpb-autoresponder-from-name'])) {
						$emailSendingData['fromText'] = $autoresponder['sgpb-autoresponder-from-name'];
					}
					if (isset($autoresponder['sgpb-autoresponder-from-email'])) {
						$emailSendingData['fromEmail'] = $autoresponder['sgpb-autoresponder-from-email'];
					}
					if (isset($autoresponder['sgpb-autoresponder-reply-to'])) {
						$emailSendingData['replyTo'] = $autoresponder['sgpb-autoresponder-reply-to'];
					}
					if (isset($autoresponder['sgpb-autoresponder-email-template'])) {
						$templateId = $autoresponder['sgpb-autoresponder-email-template'];
						$args['templateId'] = $templateId;
						$emailSendingData['emailTemplate'] = EmailTemplate::getFullTemplateHtml($args);
					}

					$isSent = Autoresponder::send($emailSendingData, $post);
					if (!$isSent) {
						continue;
					}
				}
			}
		}

		return true;
	}
}
