<?php
namespace sgpbaw;
use \AWeberAPIException;
use \AWeberAPI;
use sgpb\Functions;
if (!class_exists('AWeberAPI')) {
	require_once(SGPB_AWEBER_LIB_PATH.'aweber-api/aweber_api.php');
}

class SGPBAWeberApi
{
	public $sgawAccount;
	private $fromSubmittingData;
	private $aweberObj = null;

	public function __construct()
	{
		try {
			$connectedObj = $this->getAweberAccountObj();
		}
		catch (\Exception $ex) {

		}
	}

	public function setFormData($formData)
	{
		$this->fromSubmittingData = $formData;
	}

	public function getFormData()
	{
		return $this->fromSubmittingData;
	}


    /**
     * Establish connection between popup and aweber account
     *
     * @since 1.0.0
     *
     *
     * @return obj $aweber
     *
     */
	public function getAweberObj()
	{
		$consumerKey	= SGPBAW_CUSTOMER_KEY;
		$consumerSecret = SGPBAW_CUSTOMER_SECRET_KEY;

		if (empty($this->aweberObj)) {
			$this->aweberObj = new AWeberAPI($consumerKey, $consumerSecret);
		}

		return $this->aweberObj;
	}

    /**
     * Check is secure url
     *
     * @since 1.0.0
     *
     *
     * @return bool $isSecure
     *
     */
	public function isSecureUrl()
	{
		$isSecure = false;

		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
			$isSecure = true;
		}
		else if (
			!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])
			&& $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
			|| !empty($_SERVER['HTTP_X_FORWARDED_SSL'])
			&& $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'
		) {
			$isSecure = true;
		}

		return $isSecure;
	}

    /**
     * Get callback url
     *
     * @since 1.0.0
     *
     *
     * @return string $callbackUrl
     *
     */
	public function getCallBackUrl()
	{
		$callbackUrl = '';
		$isSecure = $this->isSecureUrl();

		if (!$isSecure) {
			$callbackUrl .= 'http://';
		}
		else {
			$callbackUrl .= 'https://';
		}

		$callbackUrl .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		return $callbackUrl;
	}

    /**
     * Make API call to receive user data
     *
     * @since 1.0.0
     *
     *
     * @return obj $sgawAccount
     *
     */
	public function getAweberAccountObj()
	{
		$aweber = $this->getAweberObj();

		$accessToken = get_option('sgpbAccessToken');
		$authorizationCode = get_option('sgpbAccessTokenSecret');
		/*When authorizationCode does not exists*/
		if (!$accessToken) {
			return false;
		}

		$sgawAccount = $aweber->getAccount($accessToken, $authorizationCode);

		$this->sgawAccount = $sgawAccount;

		return $sgawAccount;
	}

	/**
	 * Get account id
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return int $accountId
	 *
	 */
	public function getAccountId()
	{
		$accountId = 0;

		if (empty($this->sgawAccount)) {
			return $accountId;
		}
		$accountId = $this->sgawAccount->data['id'];

		return $accountId;
	}

	/**
	 * Get aweber all list data
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return array $data
	 *
	 */
	public function getAllListData()
	{
		$accountId = $this->getAccountId();
		$data = array();

		if (empty($this->sgawAccount)) {
			return $data;
		}

		$listUrl = 'https://api.aweber.com/1.0/accounts/'.$accountId.'/lists';
		$lists = $this->sgawAccount->loadFromUrl($listUrl);
		$data = $lists->data;

		return $data;
	}

	/**
	 * Get list id and title
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return array $idTitles
	 *
	 */
	public function createIdAndTitles()
	{
		$data = $this->getAllListData();
		$idTitles = array();

		if (empty($data['entries'])) {
			return $idTitles;
		}

		foreach ($data['entries'] as $list) {
			if (empty($list)) {
				continue;
			}
			$idTitles[$list['id']] = $list['name'];
		}

		return $idTitles;
	}

	/**
	 * Get Sign up forms list id and title
	 *
	 * @param int $listId
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return array $idTitles
	 *
	 */
	public function getListIdAndTitles($listId)
	{
		$formsObj = $this->getListWebFormObj($listId);
		$idTitles = array();

		if (empty($formsObj['entries'])) {
			return $idTitles;
		}

		foreach ($formsObj['entries'] as $form) {
			if (empty($form)) {
				continue;
			}
			$formId = $form['id'];
			$idTitles[$formId] = $form['name'];
		}

		return $idTitles;
	}

	/**
	 * Get list web form
	 *
	 * @since 1.0.0
	 *
	 * @param int $listId
	 *
	 * @return int $accountId
	 *
	 */
	public function getListWebFormObj($listId)
	{
		$accountId = $this->getAccountId();
		$url = 'https://api.aweber.com/1.0/accounts/'.$accountId.'/lists/'.$listId.'/web_forms';
		if (!$this->sgawAccount) {
			return array();
		}

		$webFormObj = $this->sgawAccount->adapter->request('GET', $url);

		return $webFormObj;
	}

	/**
	 * Get web form list Options
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return string
	 *
	 */
	public function getWebFormListOptions($listId)
	{
		$webForms = $this->getListWebFormObj($listId);
		$webFormSelectBoxDataObj = array();
		$options = '';

		foreach ($webForms['entries'] as $webForm) {
			$webFormId = $webForm['id'];
			$webFormSelectBoxDataObj[$webFormId] = $webForm['name'];
		}

		return $webFormSelectBoxDataObj;
	}

	/**
	 * Get web form html
	 *
	 * @since 1.0.0
	 *
	 * @param int $listId
	 * @param int $formId
	 *
	 * @return string
	 *
	 */
	public function getWebFormHtmlUrl($listId, $formId)
	{
		$webForms = $this->getListWebFormObj($listId);
		if (empty($webForms)) {
			return '';
		}

		foreach ($webForms['entries'] as $webForm) {
			if ($webForm['id'] == $formId) {
				return $webForm['html_source_link'];
			}
		}

		return '';
	}

	public function getWebformHtml($listId, $formId)
	{
		$formHtmlUrl = $this->getWebFormHtmlUrl($listId, $formId);
		// this does the same as file_get_contents function
		$form = wp_remote_retrieve_body(wp_remote_get(($formHtmlUrl)));

		return $form;
	}

	/**
	 * Get AWeber list id
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return int
	 *
	 */
	private function getListId()
	{
		$formData = $this->getFormData();
		$listId = '';

		if (isset($formData['listname'])) {
			$listId = str_replace('awlist', '', $formData['listname']);
		}

		return (int)$listId;
	}

	/**
	 * Get AWeber list Obj
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return obj
	 *
	 */
	private function getListSubscribersObj()
	{
		$accountId = $this->getAccountId();
		$listId = $this->getListId();
		$listURL = '/accounts/'.$accountId.'/lists/'.$listId;

		$sgawAccount = $this->sgawAccount;
		$list = $sgawAccount->loadFromUrl($listURL);

		$subscribers = $list->subscribers;

		return $subscribers;
	}

	/**
	 * Get AWeber web form custom fields data
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return array
	 *
	 */
	private function getCustomFields()
	{
		$formData = $this->getFormData();
		$customFields = array();

		if (is_array($formData)) {

			foreach ($formData as $key => $value) {
				if (strpos($key, 'custom_') !== false) {
					$customFiledName = str_replace('custom_', '', $key);
					$customFields[$customFiledName] = $value;
				}
			}
		}

		return $customFields;
	}

	/**
	 * Get AWeber main require data
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return array
	 *
	 */
	public function getFormMainElementsName()
	{
		$formData = $this->getFormData();
		$mainElementsData = array();

		if (isset($formData['name'])) {
			$mainElementsData['name'] = $formData['name'];
		}
		if (isset($formData['name_(awf_first)'])) {
			$mainElementsData['name'] = $formData['name_(awf_first)'].' '.$formData['name_(awf_last)'];
		}
		if (isset($formData['email'])) {
			$mainElementsData['email'] = $formData['email'];
		}

		return $mainElementsData;
	}

	/**
	 * User subscribe to aweber list
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return array
	 *
	 */
	public function subscribeToList()
	{
		$subscribersObj = $this->getListSubscribersObj();
		$mainElements = $this->getFormMainElementsName();
		$customFields = $this->getCustomFields();
		$formStatusData = array();

		$email = $mainElements['email'];
		unset($mainElements['email']);

		try {
			$aweberSubscribeData = array(
				'email' => $email,
				'ip_address' => Functions::getIpAddress(),
				'ad_tracking' => '',
				'misc_notes' => ''
			);

			if (!empty($mainElements)) {
				foreach ($mainElements as $elementName => $elementValue) {
					$aweberSubscribeData[$elementName] = $elementValue;
				}
			}
			if (!empty($customFields)) {
				$aweberSubscribeData['custom_fields'] = $customFields;
			}

			$subscribersObj->create($aweberSubscribeData);
			$formStatusData['status'] = 200;
			$formStatusData['message'] = __('Successfully subscribed', SG_POPUP_TEXT_DOMAIN);
			return $formStatusData;
		}
		catch (AWeberAPIException $exc) {
			$formStatusData['status'] = $exc->status;
			$formStatusData['message'] = ucfirst($exc->message);
			return $formStatusData;
		}
	}

	/**
	 * Doing from submitting data collection
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return
	 *
	 */
	public function aweberFormSubmitting()
	{
		$mainElements = $this->getFormMainElementsName();

		if (!empty($mainElements)) {
			return	$this->subscribeToList();
		}

		return $mainElements;
	}

	/**
	 * Save AWeber tokens from GET request
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return void
	 *
	 */
	public function saveTokensFromGetRequest()
	{
		$aweber = $this->getAweberObj();
		$aweber->user->tokenSecret  = get_option('sgpbRequestTokenSecret');
		$aweber->user->requestToken = $_GET['oauth_token'];
		$aweber->user->verifier = $_GET['oauth_verifier'];
		list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();
		update_option('sgpbAccessToken', $accessToken);
		update_option('sgpbAccessTokenSecret', $accessTokenSecret);
	}

	/**
	 * Save Request secret token
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return void
	 *
	 */
	public function saveRequestSecretToken()
	{
		$aweber = $this->getAweberObj();
		$callbackUrl = $this->getCallBackUrl();
		list($requestToken, $requestTokenSecret) = $aweber->getRequestToken($callbackUrl);
		update_option('sgpbRequestTokenSecret', $requestTokenSecret);
	}
}
