<?php
namespace sgpbsubscriptionplus;

class SGPBActivecampaignApi 
{
	private $url;    
	private $key;

	public function __construct($apiKey, $url) 
	{
		$this->url = trailingslashit($url) . 'admin/api.php';
		$this->key = $apiKey;
	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
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
		$url = $this->url;
		$apidata = array(
			'api_action' => $action,
			'api_key' => $this->key,
			'api_output' => 'serialize',
		);

		$url = add_query_arg($apidata, $url);
		$request = curl_init($url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, false); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		if (array() !== $args) {
			if ('POST' === $verb) {
				curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query(array_merge($apidata, $args)));
				curl_setopt($request, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/x-www-form-urlencoded'
				));
			}
			else {
				$url = add_query_arg($args, $url);
				curl_setopt($request, CURLOPT_URL, $url);
			}
		}

		$response = (string)curl_exec($request); //execute curl fetch and store results in $response
		curl_close($request);

		return unserialize($response);
	}

	private function get($action, $args = array())
	{
		return $this->request('GET', $action, $args);
	}

	private function post($action, $args = array())
	{
		return $this->request('POST', $action, $args);
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
			else if (strpos($key, 'sgpb-phone') !== false && empty($data['phone'])) {
				$data['defaultArgs']['phone'] = $value;
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

	public function getCustomFields()
	{
		$res = $this->get('list_field_view', array('ids' => 'all'));

		if(is_wp_error($res) || !is_array($res)) {
			return $res;
		}

		$customFields = array();
		foreach ($res as $key => $value) {
			if(is_numeric($key)) {
				array_push($customFields, $value);
			}
		}

		return $customFields;
	}

	public function addCustomFields($customFields, $list) {
		if (!empty($customFields)) {
			foreach ($customFields as $key => $label) {
				$key = strtoupper($key);
				$fieldData = array(
					'title' => ucfirst(strtolower($label)),
					'type' => 1, 
					'perstag' => $key,
					'p[0]' => 0,
					'req' => 0,
				);
				$res = $this->post('list_field_add', $fieldData);
			}
		}
	}

	public function addEntryFields($submitedData, $listId) 
	{
		$id = (int)$listId;

		//set up custom fields
		$customFields = array_diff_key($submitedData, array(
			'first_name' => '',
			'last_name' => '',
			'email' => '',
			'phone' => '',
		));

		$existedCustomFields = $this->getCustomFields();
		$array1 = array_change_key_case($customFields, CASE_UPPER);
		$array2 = wp_list_pluck($existedCustomFields, 'perstag');
		$extraCustomFields = @array_diff($array1, $array2);
		$reservedFields = array('FIRSTNAME', 'LASTNAME', 'EMAIL', 'PHONE');
		if ($extraCustomFields) {
			$preparedFields = array();
			foreach ($extraCustomFields as $newField => $value) {
				if (!in_array(strtoupper($newField), $reservedFields, true)) {
					$preparedFields[$newField] = $newField;
				}
			}
			$this->addCustomFields($preparedFields, $id);
		}	
		//store the new custom fields key
		if (!empty($customFields)) {
			foreach ($customFields as $key => $value) {
				if (!in_array(strtoupper($key), $reservedFields, true)) {
					$key = str_replace(' ', '', $key);
					$key = 'field[%' . strtoupper($key) . '%,0]';
					$submitedData[$key] = $value;
				}
			}
		}
		$res = $this->subscribe($id, $submitedData);
		//result validation
		if (is_wp_error($res)) {
			$isSent = false;
			$memberStatus = __('Member could not be subscribed.', SG_POPUP_TEXT_DOMAIN);
			$errorDetail = $res->get_error_message();
		} else {
			$memberStatus = $res['result_message'];
			$isSent = true;
		}

		if ($isSent) {
			$description =  __('Successfully added or updated member on ActiveCampaign list', SG_POPUP_TEXT_DOMAIN);
		}
		else {
			$description = $errorDetail;			
		}
		
		$entryFields = array(
			array(
				'name'  => 'status',
				'value' => array(
					'is_sent'       => $isSent,
					'description'   => $description,
					'member_status' => $memberStatus,
				),
			),
		);

		if (!empty($id)) {
			$entryFields[0]['value']['list_name'] = $id;
		}

		return $entryFields;
	}

	public function getLists()
	{
		$res = $this->get('list_list', array(
			'ids' => 'all',
			'global_fields' => 0
		));
		
		if (is_wp_error($res) || !is_array($res)) {
			return $res;
		}

		$res2 = array();
		foreach ($res as $key => $value) {
			if (is_numeric($key)) {
				array_push($res2, $value);
			}
		}

		return $res2;
	}

	/**
	 * Add new contact
	 *
	 * @param string $id ID of the List or Form to which the user will be subscribed to
	 * @param array 
	 * @param string $signUpTo Indicates if the subscription is done to a Form or to a List
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe($id, $data, $signUpTo = 'list')
	{	
		if (false === $this->emailExist($data['email'], $id, $signUpTo)) {
			if ('list' === $signUpTo) {
				if ((int)$id > 0) {
					$data['p'] = array( $id => $id );
					$data['status'] = array($id => 1);
					$res = $this->post('contact_sync', $data);
				} 
				else {
					$res = $this->post('contact_add', $data);
				}
			} 
			else {
				$data['form'] = $id;
				$res = $this->post('contact_sync', $data);
			}

			if (is_array($res) && isset($res['result_code']) && 'SUCCESS' === $res['result_code']) {
				return __( 'Successful subscription', SG_POPUP_TEXT_DOMAIN );
			} 
			else if (empty($res)) {
				return __( 'Successful subscription', SG_POPUP_TEXT_DOMAIN );
			}
		} 
		else {
			$res = $this->post('contact_sync', $data);
		}

		return $res;
	}

	public function emailExist($email, $id, $type = 'list') 
	{
		$res = $this->post('contact_view_email', array('email' => $email));
		// See if duplicate exists.
		if (!empty($res) && !empty($res['id']) && !empty($res['lists'])) {
			if ('list' === $type) {
				// Also make sure duplicate is in active list.
				foreach ($res['lists'] as $response_list) {
					if ($response_list['listid'] === $id) {
						// Duplicate exists.
						return true;
					}
				}
			} 
			else {
				// Or active form if checking on a form
				if ( $id === $res['formid'] ) {
					return true;
				}
			}
		}

		// Otherwise assume no duplicate.
		return false;
	}
}
