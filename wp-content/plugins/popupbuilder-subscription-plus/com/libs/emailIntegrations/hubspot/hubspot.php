<?php
namespace sgpbsubscriptionplus;
require_once(SG_POPUP_EMAIL_INTEGRATIONS_PATH.'providerAbstract.php');

if (class_exists('SGPBHubSpot')) {
	return false;
}

include_once 'hubspotApi.php';
class SGPBHubSpot extends SGPBProviderAbstract 
{
	const SLUG = 'hubspot';
	protected  static $api;
	protected  static $errors;
	protected static $instance = null;
	protected $slug = 'hubspot';
	protected $class = __CLASS__;
	protected $title = 'HubSpot';

	public static function getInstance() 
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function api($integrationDetails)
	{
		$apiKey = $integrationDetails['api_key'];

		if (empty(self::$api)) {
			try {
				self::$api = new SGPBHubspotApi($apiKey, array('debug' => true));
				self::$errors = array();
			} 
			catch (Exception $e) {
				self::$errors = array('api_error' => $e) ;
			}
		}

		return self::$api;
	}

	public function configureApiKey($integrationDetails) 
	{
		$hasErrors = false;
		$defaultData = array(
			'api_key' => '',
			'identifier' => '',
		);
		$currentData = $this->getCurrentData($defaultData, $integrationDetails);
		$isSubmit = isset($integrationDetails['api_key']);
		$apiKeyValidated = true;
		if ($isSubmit) {
			$apiKeyValidated = $this->validateApiKey($integrationDetails);
			if (!$apiKeyValidated) {
				$errorMessage = $this->providerConnectionFalied();
				$hasErrors = true;
			}
			if (!$hasErrors) {
				return array(
					'redirect'     => false,
					'has_errors'   => false,
					'html' => '<strong>' . $this->getTitle() . '</strong> ' . __('Successfully connected', SG_POPUP_TEXT_DOMAIN)
				);
			}
		}	
		$options = array(
			'api_key' => __('Please enter a valid HubSpot API key', SG_POPUP_TEXT_DOMAIN)
		);

		$stepHtml = '';
		if ($hasErrors) {
			$stepHtml .= '<span class="sui-notice sui-notice-error"><p>' . esc_html($errorMessage) . '</p></span>';
		}
	
		$response = array(
			'html' => $stepHtml,
			'has_errors' => $hasErrors,
			'options'    => $options
		);

		return $response;
	}

	private function validateApiKey($integrationDetails)
	{
		if (empty($integrationDetails)) {
			return false;
		}
		// Check if API key is valid
		$api = self::api($integrationDetails);
		if ($api) {
			$lists = $api->getLists();
		} 

		if (is_wp_error($lists) || $lists['status'] == 'error') {
			return false;
		}
		
		return true;
	}
}