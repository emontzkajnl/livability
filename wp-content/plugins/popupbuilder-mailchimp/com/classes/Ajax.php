<?php
namespace sgpbm;

class Ajax
{
	public function __construct()
	{
		$this->init();
	}

	public function sanitizeField($optionsKey, $isTextField = false)
	{
		if (isset($_POST[$optionsKey])) {
			if ($isTextField == true) {
				$sgPopupData = $_POST[$optionsKey];
				return $sgPopupData;
			}
			return sanitize_text_field($_POST[$optionsKey]);
		}

		return '';
	}

	private function init()
	{
		add_action('wp_ajax_sgpbm_change_mailchimp_list', array($this, 'changeMailchimpList'));
		add_action('wp_ajax_sgpb_mailchimp_subscribe', array($this, 'mailchimpSubscribe'));
		add_action('wp_ajax_nopriv_sgpb_mailchimp_subscribe', array($this, 'mailchimpSubscribe'));
	}

	public function changeMailchimpList()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$sgpbMailchimp = MailchimpApi::getInstance();
		$args = @$_POST['apiArgs'];
		$params = array(
			'popupId' => @$args['popupId'],
			'listId' => @$args['listId'],
			'emailLabel' => @$args['emailLabel'],
			'asteriskLabel' => @$args['asteriskLabel'],
			'showRequiredFields' => (@$args['showRequiredFields'] == 'true') ? true : false,
			'submitTitle' => @$args['submitTitle'],
			'gdprStatus' => @$args['gdprStatus'],
			'gdprLabel' => @$args['gdprLabel'],
			'gdprConfirmationText' => @$args['gdprConfirmationText']
		);

		echo $sgpbMailchimp->getListFormHtml($params);
		wp_die();
	}

	public function mailchimpSubscribe()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$doubleOptionStatus = 'subscribed';
		$listId = $this->sanitizeField('listId');
		$formData = $this->sanitizeField('formData', true);
		parse_str($formData, $formData);
		$email = @$formData['EMAIL'];

		$doubleOptin = $this->sanitizeField('doubleOptin');

		if ($doubleOptin == 'true') {
			$doubleOptionStatus = 'pending';
		}
		$sgpbMailchimp = MailchimpApi::getInstance();
		$margeData = $sgpbMailchimp->getMergeFieldsValuesFromListForm($listId, $formData);
		$interestData = $sgpbMailchimp->getInterestFiledsValuesFromListForm($listId, $formData);

		$params = array(
			'email_address' => $email,
			'status' => $doubleOptionStatus // double opt-in
		);

		if (!empty($margeData)) {
			$params['merge_fields'] = $margeData;
		}

		// When exist group element(s)
		if (!empty($interestData)) {
			$params['interests'] = $interestData;
		}
		$result = $sgpbMailchimp->subscribe($listId, $params);

		$responseStat = @$result['status'];
		$responseTitle = @$result['title'];
		if ($responseStat != '400') {
			$responseStat = 200;
		}
		if ($responseTitle == 'Member Exists') {
			$responseStat = 401;
		}
		// When successfully subscribed
		if (!isset($result['title'])) {
			$responseTitle = __('Almost finished... We need to confirm your email address. To complete the subscription process, please click the link in the email we just sent you', SG_POPUP_TEXT_DOMAIN).'.';
			// When turn of double opt-in option
			if ($doubleOptin == 'true') {
				$responseTitle = __('You have successfully subscribed to our mail list', SG_POPUP_TEXT_DOMAIN).'.';
			}
		}

		$formData = array(
			'status' => $responseStat,
			'message' => $responseTitle
		);

		echo json_encode($formData);
		wp_die();
	}
}

new Ajax();
