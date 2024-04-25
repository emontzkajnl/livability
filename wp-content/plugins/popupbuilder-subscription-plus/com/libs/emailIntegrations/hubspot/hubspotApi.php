<?php
namespace sgpbsubscriptionplus;

class SGPBHubspotApi  
{
	private $apiKey;
	private $endpoint = 'https://api.hubapi.com/';

	public function __construct($apiKey, $args = array())
	{
		$this->apiKey = $apiKey;

		if(isset($args['endpoint'])) {
			$this->endpoint = $args['endpoint'];
		}
	}

	private function request($verb = 'GET', $action, $args = array())
	{
		$url = trailingslashit($this->endpoint).$action;
		$url .= ('?'.http_build_query(array('hapikey' => $this->apiKey)));
	
		if (isset($args)) {
			$postJson = json_encode($args);
		}

		$request = curl_init($url);
		curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		
		if ('POST' === $verb) {
			curl_setopt($request, CURLOPT_POST, true);
			curl_setopt($request, CURLOPT_POSTFIELDS, $postJson); 
		}	
		
		$res = curl_exec($request);
		$res = json_decode($res, true);
		
		return $res;
	}

	private function get($action, $args = array())
	{
		return $this->request('GET', $action, $args);
	}

	private function post($action, $args = array())
	{
		return $this->request('POST', $action, $args);
	}

	public function getLists()
	{
		$res = $this->get('contacts/v1/lists');

		return $res;
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
			else if (strpos($key, 'sgpb-phone') !== false && empty($data['phone'])) {
				$data['defaultArgs']['phone'] = $value;
			}
			else {
				$data[$key] = $value;
			}
		}

		return $data;
	}

	public function addEntryFields($submittedData, $listId) 
	{
		$listId = (int)$listId;
		$isSent = false;
		$memberStatus = __( 'Member could not be subscribed.', SG_POPUP_TEXT_DOMAIN);
		$details = __('Unable to add this subscriber', SG_POPUP_TEXT_DOMAIN);

		// Extra fields
		$extraData = array_diff_key($submittedData, array(
			'email' => '',
			'first_name' => '',
			'last_name' => '',
		));
		$extraData = array_filter($extraData);
		if (!empty($extraData)) {
			$customFields = array();
			foreach ($extraData as $key => $value) {
				$customFields[] = array(
					'name' => $key,
					'label' => $key,
				);
			}
			$this->addCustomFields($customFields);
		}

		$emailExist = $this->emailExists($submittedData['email']);
		if ($emailExist && !empty($emailExist['vid'])) {
			//Add to list
			$contactId = '';
			if(!empty($emailExist['list-memberships'])) {
				$lists = wp_list_pluck($emailExist['list-memberships'], 'static-list-id');
				if (!empty($lists)) {
					$contactId = $emailExist['vid'];
				}
			}
			
			$res = $this->updateContact($contactId, $submittedData);
			if (is_wp_error($res)) {
				$details = $res->get_error_message();
			} 
			else {
				$isSent = true;
				$memberStatus = __('OK', SG_POPUP_TEXT_DOMAIN);
				$details = __('Successfully updated member on HubSpot list', SG_POPUP_TEXT_DOMAIN);
			}
		} 
		else {
			$contactId = $this->addContact($submittedData);
		}
		// Add contact to contact list
		if (!empty($contactId) && !is_object($contactId) && (int)$contactId > 0) {
			$res = $this->addToContactList($contactId, $submittedData['email'], $listId);
			if (is_wp_error($res)) {
				$details = $res->get_error_message();
			} 
			else if (true !== $res) {
				$details = __('Unable to add this contact to contact list.', SG_POPUP_TEXT_DOMAIN);
			} 
			else {
				$isSent = true;
				$memberStatus = __('OK', SG_POPUP_TEXT_DOMAIN);
				$details = __('Successfully added or updated member on HubSpot list', SG_POPUP_TEXT_DOMAIN);
			}
		}

		$entryFields = array(
			array(
				'name' => 'status',
				'value'=> array(
					'is_sent' => $isSent,
					'description' => $details,
					'member_status' => $memberStatus,
				),
			),
		);

		$entryFields = apply_filters(
			$entryFields,
			$submittedData
		);

		return $entryFields;
	}
	
	public function updateContact($id, $data) 
	{
		$props = array();
		$defaultData = array('first_name', 'last_name');
		$existingProperties = array_merge($this->getProperties(), array_flip($defaultData));
		$filteredData = array_intersect_key($data, $existingProperties);

		$difference = array_diff_key($data, $filteredData);

		foreach ($data as $key => $value) {
			if ('first_name' === $key) {
				$key = 'firstname';
			}
			if ('last_name' === $key) {
				$key = 'lastname';
			}

			$props[] = array(
				'property' => $key,
				'value' => $value,
			);
		}

		$args = array('properties' => $props);
		$endpoint = 'contacts/v1/contact/vid/' . $id .'/profile';
		$res = $this->post($endpoint, $args);

		return $res;
	}

	public function addCustomFields($fields) 
	{
		$error = false;
		$props = $this->getProperties();
		$newFields = array();
		foreach ($fields as $field) {
			if (!isset($props[$field['name']])) {
				$newFields[] = $field;
			}
		}
		foreach ($newFields as $field) {
			// Add the new field as property
			$name = strtolower($field['name']);
			$name = str_replace(' ', '_', $name);
			$property = array(
				'name' => $name,
				'label' => $field['label'],
				'type' => 'string',
				'fieldType' => 'text',
				'groupName' => 'contactinformation',
			);
			if (!$this->addProperty($property)) {
				$error = true;
			}
		}
	}

	public function getProperties() 
	{
		$properties = array();
		$res = $this->get('properties/v1/contacts/properties');
		if (!is_wp_error($res) && !isset($res->status)) {
			foreach ($res as $prop) {
				$properties[$prop['name']] = $prop['label']; 
			}
		}

		return $properties;
	}

	public function addProperty(array $property) 
	{
		$res = $this->post('properties/v1/contacts/properties', $property);
		if (!is_wp_error($res) && $res['status'] !== 'error') {
			return true;
		}

		return false;
	}

	public function emailExists($email) 
	{
		$args = array('showListMemberships' => true);
		$endpoint = 'contacts/v1/contact/email/' . $email . '/profile';

		$res = $this->get($endpoint, $args);
		
		if (!is_wp_error($res) && !empty($res['vid'])) {
			return $res; 
		}

		return false;
	}

	public function addContact($data) 
	{
		$props = array();
		$defaultData = array('first_name', 'last_name');
		$existingProperties = array_merge(
			$this->getProperties(), 
			array_flip($defaultData)
		);
		$filteredData = array_intersect_key($data, $existingProperties);

		$difference = array_diff_key($data, $filteredData);

		foreach ($data as $key => $value) {
			if ('first_name' === $key) {
				$key = 'firstname';
			}
			if ('last_name' === $key) {
				$key = 'lastname';
			}
			$key = strtolower($key);
			$key = str_replace(' ', '_', $key);
			$props[] = array(	
				'property' => $key,
				'value' => $value,
			);
		}

		$args = array('properties' => $props);
		$endpoint = 'contacts/v1/contact';

		$res = $this->post($endpoint, $args);
		if (!is_wp_error($res) && ! empty($res['vid'])) {
			return $res['vid']; 
		}

		return $res;
	}

	public function addToContactList($contactId, $email, $emailList) 
	{
		$args = array(
			'listId' => $emailList,
			'vid' => array($contactId),
			'emails' => array($email),
		);
		$endpoint = 'contacts/v1/lists/' . $emailList . '/add';
		$res = $this->post($endpoint, $args);
		if (!is_wp_error($res) && !empty($res['updated'])) {
			return true;
		}

		if (!empty($res['status']) && 'error' === $res['status'] && !empty($res['message'])) {
			$res = new WP_Error('provider_error', $res['message']);
		}

		return $res;
	}
}