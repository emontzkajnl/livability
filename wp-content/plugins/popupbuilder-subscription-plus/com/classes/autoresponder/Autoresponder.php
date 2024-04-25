<?php
namespace sgpbsubscriptionplus;
use sgpb\AdminHelper;

if (!file_exists(@SG_POPUP_HELPERS_PATH.'ConfigDataHelper.php')) {
	return '';
}
require_once(@SG_POPUP_HELPERS_PATH.'ConfigDataHelper.php');

class Autoresponder
{
	public $post = array();

	public function setPost($post)
	{
		$this->post = $post;
	}

	public function getPost()
	{
		return $this->post;
	}

	public static function getData()
	{
		$autoresponderData = array();
		$autoresponderId = get_the_ID();
		if (isset($autoresponderId)) {
			if (get_post_meta($autoresponderId, 'sgpb_autoresponder_options', true)) {
				$autoresponderData = get_post_meta($autoresponderId, 'sgpb_autoresponder_options', true);
			}
		}

		return $autoresponderData;
	}

	public static function create($data = array())
	{
		$postId = @$data['sgpb-autoresponder-id'];
		update_post_meta($postId, 'sgpb_autoresponder_options', $data);
	}

	public static function getAllAutorespondersForCurrentPopup($selectedPopupId = 0)
	{
		$args = array(
			'post_type' => SG_POPUP_AUTORESPONDER_POST_TYPE
		);
		$allAutoresponders = array();
		$allPostData = \ConfigDataHelper::getQueryDataByArgs($args);

		if (empty($allPostData)) {
			return $allAutoresponders;
		}

		foreach ($allPostData->posts as $postData) {
			if (empty($postData)) {
				continue;
			}
			$autoresponderId = $postData->ID;

			$autoresponderOptions = self::getOptionsById($autoresponderId);
			if (empty($autoresponderOptions) || !isset($autoresponderOptions['sgpb-autoresponder-lists'])) {
				continue;
			}
			$popups = $autoresponderOptions['sgpb-autoresponder-lists'];
			if (is_array($popups) && !in_array($selectedPopupId, $popups)) {
				continue;
			}

			$allAutoresponders[] = $autoresponderOptions;
		}

		return $allAutoresponders;
	}

	public static function getOptionsById($id = 0)
	{
		return get_post_meta($id, 'sgpb_autoresponder_options', true);
	}

	public static function send($params = array(), $post = array())
	{
		$fromText = $params['fromText'];
		$subscriptionFormId = $params['subscriptionFormId'];
		$fromEmail = $params['fromEmail'];
		$replyTo = $params['replyTo'];
		$subscriberEmail = $params['subscriberEmail'];
		$subscriberFirstName = $params['firstName'];
		$subscriberLastName = $params['lastName'];
		$subject = $params['subject'];
		$popupTitle = $params['popupTitle'];
		$adminUserName = 'admin';
		$subscriber = SubscriptionPlusAdminHelper::findSubscribersByEmail($subscriberEmail, $subscriptionFormId);
		$subscriberId = @$subscriber['id'];
		$blogname = get_bloginfo('name');
		$adminEmail = get_option('admin_email');
		$userData = @get_user_by_email($adminEmail);
		$patternUnsubscribe = '';
		$patternPostLink = '';
		$title = '';

		if (!empty($userData)) {
			$adminUserName = $userData->display_name;
		}

		$emailTemplate = $params['emailTemplate'];
		$replaceMaping = array(
			'/\[adminUserName]/' => $adminUserName,
			'/\[blogname]/' => $blogname,
			'/\[popupTitle]/' => $popupTitle,
			'/\[email]/' => $subscriberEmail,
			'/\[firstName]/' => $subscriberFirstName,
			'/\[lastName]/' => $subscriberLastName,
			'/\[postTitle]/' => get_the_title($post)
		);

		//get Unsubscribe title
		$pattern = "/\[(\[?)(Unsubscribe)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]\*+(?:\[(?!\/\2\])[^\[]\*+)\*+)\[\/\2\])?)(\]?)/";
		preg_match($pattern, $emailTemplate, $matches);
		if ($matches) {
			$patternUnsubscribe = $matches[0];
			$title = 'Unsubscribe';
			// If user didn't change anything inside the [unsubscribe] shortcode $matches[2] will be equal to 'Unsubscribe'
			if ($matches[2] == 'Unsubscribe') {
				$pattern = '/\s(\w+?)="(.+?)"]/';
				preg_match($pattern, $matches[0], $matchesTitle);
				if (!empty($matchesTitle[2])) {
					$title = AdminHelper::removeAllNonPrintableCharacters($matchesTitle[2], 'Unsubscribe');
				}
			}
		}
		$unsubscribeLink = get_home_url();
		$unsubscribeLink .= '?sgpbUnsubscribe='.md5($subscriberId.$subscriberEmail);
		$unsubscribeLink .= '&email='.$subscriberEmail;
		$unsubscribeLink .= '&popup='.$subscriptionFormId;

		$unsubscribeLink = '<br><a href="'.$unsubscribeLink.'">'.$title.'</a>';
		$replaceMaping[$patternUnsubscribe] = $unsubscribeLink;

		//get postURL title
		$pattern = "/\[(\[?)(postLink)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]\*+(?:\[(?!\/\2\])[^\[]\*+)\*+)\[\/\2\])?)(\]?)/";
		preg_match($pattern, $emailTemplate, $matches);
		if ($matches) {
			$patternPostLink = $matches[0];
			$title = get_the_title($post);
			// If user didn't change anything inside the [postURL] shortcode $matches[2] will be equal to 'postURL'
			if ($matches[2] == 'postLink') {
				$pattern = '/\s(\w+?)="(.+?)"]/';
				preg_match($pattern, $matches[0], $matchesTitle);
				if (!empty($matchesTitle[2]) && $matchesTitle[2] != 'title_text_here') {
					$title = AdminHelper::removeAllNonPrintableCharacters($matchesTitle[2], $title);
				}
			}
		}

		$postLink = get_permalink($post->ID);
		$postLink = '<br><a href="'.$postLink.'">'.$title.'</a>';
		$replaceMaping[$patternPostLink] = $postLink;

		foreach ($replaceMaping as $key => $value) {
			if ($key == $patternUnsubscribe || $key == $patternPostLink) {
				$emailTemplate = str_replace($key, $value, $emailTemplate);
			}
			else {
				$emailTemplate = preg_replace($key, $value, $emailTemplate);
			}
		}
		$headers  = "From: ".$fromText." <".$fromEmail." >\n";
		$headers .= "X-Priority: 1\n";
		$headers .= "Reply-to: ".$replyTo."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\n";

		$result = wp_mail($subscriberEmail, $subject, $emailTemplate, $headers);

		return $result;
	}

	public static function getMatchAutoresponders($post = array(), $isGutenbergInUse = false)
	{
		$args = array(
			'post_type' => SG_POPUP_AUTORESPONDER_POST_TYPE
		);
		$allAutoresponders = array();
		if (empty($post)) {
			return $allAutoresponders;
		}
		$allPostData = \ConfigDataHelper::getQueryDataByArgs($args);

		if (empty($allPostData)) {
			return $allAutoresponders;
		}

		foreach ($allPostData->posts as $postData) {
			if (empty($postData)) {
				continue;
			}
			$autoresponderId = $postData->ID;

			$autoresponderOptions = self::getOptionsById($autoresponderId);
			if (isset($autoresponderOptions['sgpb-is-active']) && $autoresponderOptions['sgpb-is-active'] == '') {
				continue;
			}
			if (empty($autoresponderOptions) || !isset($autoresponderOptions['sgpb-autoresponder-events'])) {
				continue;
			}
			$autoresponderEvents = $autoresponderOptions['sgpb-autoresponder-events'];
			if (!is_array($autoresponderEvents)) {
				continue;
			}

			$allAutoresponders[$autoresponderId]['events'] = $autoresponderEvents;
			$allAutoresponders[$autoresponderId]['options'] = $autoresponderOptions;
		}
		$allAutoresponders = self::filterAutoresponders($allAutoresponders, $post, $isGutenbergInUse);

		return $allAutoresponders;
	}

	public static function filterAutoresponders($allAutoresponders, $post, $isGutenbergInUse = false)
	{
		$filteredAutoresponders = array();
		$postType = $post->post_type;
		$filteredAutoresponders = self::filterPagePostAutoresponders($allAutoresponders, $postType, $isGutenbergInUse, $post);

		return $filteredAutoresponders;
	}

	public static function filterPagePostAutoresponders($allAutoresponders, $postType = '', $isGutenbergInUse = false, $post = array())
	{
		$readyToUseAutoresponders = array();

		if (empty($allAutoresponders) || ($isGutenbergInUse && !isset($_REQUEST['_locale'])) || (!$isGutenbergInUse && !count($_POST))) {
			return $readyToUseAutoresponders;
		}

		foreach ($allAutoresponders as $autoresponderId => $autoresponder) {
			$autorespondersFor = $autoresponder['events'][0];
			foreach ($autorespondersFor as $autoresponderFor) {
				if (empty($autoresponderFor) || empty($autoresponderFor['param'])) {
					continue;
				}
				$autoresponderParam = @$autoresponderFor['param'];
				if ($autoresponderParam == $postType.'_all') {
					$readyToUseAutoresponders[$autoresponderId] = $autoresponder['options'];
				}
				if ($autoresponderParam == $postType.'_categories') {
					if (!empty($autoresponderFor['value'])) {
						foreach ($autoresponderFor['value'] as $value) {
							$usedCategory = self::filterAutoresponderForCategories($post, $postType, $value);
							if (!empty($usedCategory)) {
								$readyToUseAutoresponders[$autoresponderId] = $autoresponder['options'];
								continue;
							}
						}
					}
				}
				else if ($autoresponderParam == 'post_tags') {
					if (has_tag()) {
						$readyToUseAutoresponders[$autoresponderId] = $autoresponder['options'];
					}
				}
				else if ($autoresponderParam == 'post_tags_ids') {
					global $post;
					$tagsObj = wp_get_post_tags($post->ID);
					$postTagsValues = (array)@$autoresponderFor['value'];
					$selectedTags = array_values($postTagsValues);

					foreach ($tagsObj as $tagObj) {
						if (in_array($tagObj->slug, $selectedTags)) {
							$readyToUseAutoresponders[$autoresponderId] = $autoresponder['options'];
							break;
						}
					}
				}
				if ($autoresponderParam == SGPB_AUTORESPONDER_EVENT_PAGE_CREATION && $postType == 'page') {
					// allPagesIs is equal to '==', which has changed to avoid conflicts
					if (isset($autoresponderFor['operator']) && $autoresponderFor['operator'] == 'allPagesIs') {
						unset($autoresponder['options']['sgpb-autoresponder-events']);
						$readyToUseAutoresponders[$autoresponderId] = $autoresponder['options'];
					}
				}
				else if ($autoresponderParam == SGPB_AUTORESPONDER_EVENT_ALL_POSTS_CREATION && $postType == 'post') {
					if (isset($autoresponderFor['operator']) && $autoresponderFor['operator'] == '==') {
						unset($autoresponder['options']['sgpb-autoresponder-events']);
						$readyToUseAutoresponders[$autoresponderId] = $autoresponder['options'];
					}
				}
				// if param is numeric, it's a category id
				else if ((int)$autoresponderParam !== 0) {
					if (isset($autoresponderFor['operator'])) {
						// $postType is e.g.: product (woocommerce product)1
						$postType = $autoresponderFor['operator'];
						if (isset($autoresponderFor['operator'])) {
							// $categories is categories of the selected post type (array with categories id)
							// param will become a value (category id)
							$category = $autoresponderFor['param'];
							$usedCategory = self::filterAutoresponderForCategories($post, $postType, $category);
							if (!$usedCategory) {
								continue;
							}
							unset($autoresponder['options']['sgpb-autoresponder-events']);
							$readyToUseAutoresponders[$autoresponderId] = $autoresponder['options'];
						}
					}
				}
			}
		}

		return $readyToUseAutoresponders;
	}

	public static function filterAutoresponderForCategories($post = array(), $postType = '', $category = '')
	{
		global $wpdb;
		$categoryMeetTheCondition = false;
		$postId = $post->ID;
		$relationship = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'term_relationships WHERE object_id = '.$postId.' && term_taxonomy_id = '.$category);
		if (!empty($relationship) && is_object($relationship)) {
			$categoryMeetTheCondition = true;
		}

		return $categoryMeetTheCondition;
	}
}
