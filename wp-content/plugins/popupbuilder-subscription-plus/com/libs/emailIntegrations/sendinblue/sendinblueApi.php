<?php
namespace sgpbsubscriptionplus;

class SGPBSendinblueApi
{

	private $apiKey;

	private $endpoint = 'https://api.sendinblue.com/v3/';

	private static $instances = array();

	public function __construct($apiKey, $args = array())
	{
		$this->apiKey = $apiKey;
	}

	public static function boot($apiKey) 
	{
		$instanceKey = md5($apiKey);
		if (!isset(self::$instances[$instanceKey])) {
			self::$instances[$instanceKey] = new static($apiKey);
		}

		return self::$instances[$instanceKey];
	}

	/**
	 * Sends request to the endpoint url with the provided $action
	 * @param string $verb
	 * @param string $action rest action
	 * @param array $args
	 *
	 * @return object|WP_Error
	 */
	private function request($verb = 'GET', $action, $args = array())
	{
		$url = trailingslashit($this->endpoint).$action;
		if (isset($args)) {
			$defaultArgs = $args;
		}
		$args = array(
			'method' => $verb,
			'headers' =>  array(
				'api-key' 		=> $this->apiKey,
				'content-type'	=> 'application/json',
				'accept' => 'application/json'
			)
		);

		if ('GET' === $verb) {
			$url .= ('?'.http_build_query($args));
		}
		else {
			$args['body'] = wp_json_encode($defaultArgs);
		}

		$res = wp_remote_request($url, $args);
	
		if (!is_wp_error($res) && is_array($res) && $res['response']['code'] <= 204) {
			return json_decode(wp_remote_retrieve_body($res), true);
		}

		if (is_wp_error($res)) {
			return $res;	
		}	
	}

	private function get($action, $args = array())
	{
		return $this->request('GET', $action, $args);
	}

	private function post($action, $args = array())
	{
		return $this->request('POST', $action, $args);
	}

	public function getAccount() 
	{
		$res = $this->get('account', array());

		return $res;
	}

	public function createContact($data) 
	{
		$res = $this->post('contacts', $data);

		return $res;
	}

	public function getAttributes() 
	{
		$res = $this->get('contacts/attributes',array());

		return $res;
	}

	public function createAttributes($name, $category = 'normal', $data = array()) 
	{
		$res = $this->post('contacts/attributes/' 
			. rawurlencode(trim($category)) . '/' 
			. rawurlencode(trim($name)), 
			$data
		);

		return $res; 
	}

	public function getLists()
	{
		$res = $this->get('contacts/lists');

		return $res;
	}

	public function addEntryFields($submittedData, $listId) 
	{
		$listId = (int)$listId;
		$email = $submittedData['email'];
		$isSent = false;
		$memberStatus = __( 'Member could not be subscribed.', SG_POPUP_TEXT_DOMAIN);
		$mergeVals	 = array();

		if (isset($submittedData['first_name'] ) && !empty($submittedData['first_name'])) {
			$submittedData['FIRSTNAME'] = $submittedData['first_name'];
		}
		if (isset($submittedData['last_name'] ) && !empty($submittedData['last_name'])) {
			$submittedData['LASTNAME'] = $submittedData['last_name'];
		}

		//unset this as we don't need it
		unset($submittedData['first_name']);
		unset($submittedData['last_name']);

		foreach($submittedData as $key => $subDataValue){
			if ($key === 'email') {
				continue;
			}
			$key = str_replace(array('-', '_', ' '), '', $key);
			$customFields[] = array(
				'name' => strtoupper($key),
			);

			$mergeVals[strtoupper($key)] = $subDataValue;
		}

		//currently only supports text fields
		if (!empty($mergeVals)) {
			//get custom fields
			$result = $this->getAttributes();
			$apiFields = array();

			if (!empty($result)) {
				$apiFields = wp_list_pluck($result['attributes'], 'name');
			}

			$fields = wp_list_pluck($customFields, 'name');
			$newFields = array_udiff($fields, $apiFields, 'strcasecmp');

			foreach ($newFields as $customField) {
				//create custom fields
				$this->createAttributes(
					$customField, 
					'normal', 
					array('type' => 'text')
				);
			}
		}
		$subscribeData = array(
			'email' => $email,
			'listIds' 	=> array($listId),
			'smtpBlacklistSender' => array(),
			'updateEnabled' => true
		);

		if (!empty($mergeVals)) {
			$subscribeData['attributes'] = $mergeVals;
		}

		$res = $this->createContact($subscribeData);
		if (is_wp_error($res)) {
			$details = $res->get_error_message();
		} 
		else {
			$isSent = true;
			$details = __('Successfully added or updated member on SendinBlue list', SG_POPUP_TEXT_DOMAIN);
			$memberStatus = __('OK', SG_POPUP_TEXT_DOMAIN);
		}

		$entryFields = array(
			array(
				'name'  => 'status',
				'value' => array(
					'is_sent'       => $isSent,
					'description'   => $details,
					'member_status' => $memberStatus
				),
			),
		);

		return $entryFields;
	}
	
	public function getSubmitedDataForDefaultFields($submitedData)
	{
		$data = array(
			'defaultArgs' => array()
		);
		foreach ($submitedData as $key => $value) {
			if (strpos($key,'sgpb-email') !== false) {
				$data['defaultArgs']['email'] = $value; 
			}
			else if (strpos($key, 'sgpb-first-name') !== false && empty($data['first_name'])) {
				$data['defaultArgs']['first_name'] = $value; 
			}
			else if (strpos($key, 'sgpb-last-name') !== false && empty($data['last_name'])) {
				$data['defaultArgs']['last_name'] = $value;
			}
			else if ($key == 'sgpb-subs-hidden-checker') {
				continue;
			}
			else {
				$data[$key] = $value;
			}
		}

		return $data;
	}
}
