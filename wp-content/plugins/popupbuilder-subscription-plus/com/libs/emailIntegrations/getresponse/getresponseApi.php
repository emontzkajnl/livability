<?php
namespace sgpbsubscriptionplus;

class SGPBGetresponseApi  
{

	private $apiKey;

	private $endpoint = 'https://api.getresponse.com/v3/';

	public function __construct($apiKey, $args = array())
	{
		$this->apiKey = $apiKey;

		if(isset($args['endpoint'])) {
			$this->endpoint = $args['endpoint'];
		}
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
		if (isset($args['body'])) {
			$defaultArgs = $args['body'];
		}
		
		$args = array(
			'method' => $verb,
			'headers' =>  array('X-Auth-Token' => 'api-key '. $this->apiKey,
				'Content-Type' => 'application/json;charset=utf-8'
			)
		);

		if ('GET' === $verb) {
			$url .= ('?'.http_build_query($args));
			if ('contacts' === $action) {
				$url = rawurldecode($url);
			}
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

	/**
	 * Retrieves campaigns as array of objects
	 *
	 * @return array|WP_Error
	 */
	public function getLists()
	{
		$res = $this->get('campaigns', array(
			'name' => array('CONTAINS' => '%'),
			'perPage' => 1000
			)
		);

		return $res;
	}

	/**
	 * Retrieves contacts as array of objects
	 *
	 * @since 4.0
	 * @param $email
	 * @return boolen
	 */
	public function getContact($email)
	{
		$res = $this->get('contacts', array(
			'query[email]' => $email,
		));

		if (empty($res)) {
			return false;
		}

		return true;
	}

	/**
	 * Add new contact
	 *
	 * @param $data
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe($data)
	{
		$action = 'contacts';
		$args = array(
			'body' =>  $data,
		);

		$res = $this->post($action, $args);
		if (empty($res)) {
			$message = __('Successful subscription', SG_POPUP_TEXT_DOMAIN);
			return $message;
		}

		return $res;
	}

	public function getCustomFields() 
	{
		$args = array('fields' => 'name');
		$res = $this->get('custom-fields', $args);

		return $res;
	}

	/**
	 * Add custom field
	 *
	 * @param (array) $custom_field
	 **/
	public function addCustomFields($customField) 
	{
		$action = 'custom-fields';
		$args = array(
			'body' => $customField,
		);
		$res = $this->post($action, $args);
		if (is_wp_error($res)) {
			return $res;
		}
		if (!empty($res) && !empty($res['customFieldId'])) { // phpcs:ignore
			return $res['customFieldId']; // phpcs:ignore
		}

		return false;
	}

	public function addEntryFields($submittedData, $listId) 
	{
		$email = $submittedData['email'];
		$name = array();
		if (!empty($submittedData['first_name'])) {
			$name['first_name'] = $submittedData['first_name'];
		}
		if (!empty( $submittedData['last_name'])) {
			$name['last_name'] = $submittedData['last_name'];
		}
		$newData = array(
			'email' => $email,
			'campaign' => array(
				'campaignId' => $listId,
			)
		);

		if (count($name)) {
			$newData['name'] = implode(' ', $name); 
		}

		// Extra fields
		$extraData = array_diff_key($submittedData, array(
			'email' => '',
			'first_name' => '',
			'last_name' => '',
		));
		$extraData = array_filter($extraData);
		$extraData = array_change_key_case($extraData, CASE_LOWER);
		$isSent = false;
		$memberStatus = __('Member could not be subscribed.', SG_POPUP_TEXT_DOMAIN);

		if (!empty($extraData)) {
			$newData['customFieldValues'] = array();

			$cf = $this->getCustomFields();
			$cf = array_change_key_case($cf, CASE_LOWER);
			if (is_wp_error($cf)) {
				throw new Exception($cf->get_error_message());
			}
			$customFields = wp_list_pluck($cf, 'name', 'customFieldId');
			foreach ($extraData as $key => $value) {
				$key = str_replace(array('-', '_', ' '), '', $key);
				if (in_array($key, $customFields, true)) {
					$customFieldId = array_search($key, $customFields, true);
				} 
				else {
					$customField = array(
						'name' => $key,
						'type' => 'text', // We only support text for now
						'hidden' => false,
						'values' => array(),
					);
					$customFieldId = $this->addCustomFields($customField);
					if (is_wp_error($customFieldId)) {
						throw new Exception($customFieldId->get_error_message());
					}
				}

				$newData['customFieldValues'][] = array(
					'customFieldId' => $customFieldId,
						'value' => array($value),
				);
			}
		}

		$res = $this->subscribe($newData);
		if (is_wp_error($res)) {
			$errorCode = $res->get_error_code();
			$errorMessage = $res->get_error_message($errorCode);

			if (preg_match('%Conflict%', $errorMessage)) {
				$details = __('This email address has already subscribed.', SG_POPUP_TEXT_DOMAIN);
			} 
			else {
				$details = $res->get_error_message();
			}
		} 
		else {
			$isSent = true;
			$details = __('Successfully added or updated member on Get_Response list', SG_POPUP_TEXT_DOMAIN);
			$memberStatus = __('OK', SG_POPUP_TEXT_DOMAIN);
		}

		$entryFields = array(
			array(
				'name'  => 'status',
				'value' => array(
					'is_sent'       => $isSent,
					'description'   => $details,
					'member_status' => $memberStatus,
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
