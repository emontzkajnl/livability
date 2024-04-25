<?php
namespace sgpbsubscriptionplus;
require_once(SG_POPUP_EMAIL_INTEGRATIONS_PATH.'providerAbstract.php');

if (class_exists('SGPBActiveCampaign')) {
	return false;
}
include_once 'activecampaignApi.php';

class SGPBActiveCampaign extends SGPBProviderAbstract 
{
	const SLUG = 'activecampaign';
	protected  static $api;
	protected  static $errors;
	protected static $instance = null;
	protected $slug = 'activecampaign';
	protected $class = __CLASS__;
	protected $title = 'ActiveCampaign';

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
		$url = $integrationDetails['api_url'];

		if (empty(self::$api)) {
			try {
				self::$api = new SGPBActivecampaignApi($apiKey, $url);
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
			'api_url' => '',
			'identifier' => ''
		);
		$currentData = $this->getCurrentData($defaultData, $integrationDetails);
		$isSubmit = isset($integrationDetails['api_url']) && isset($integrationDetails['api_key']);
		$apiUrlValid = $apiKeyValid = true;
		if ($isSubmit) {
			$apiUrlValid = $this->isNonEmpty($currentData['api_url']);
			$apiKeyValid = $this->isNonEmpty($currentData['api_key']);
			$apiKeyValidated = $apiUrlValid && $apiKeyValid && $this->validateCredentials($integrationDetails);
			if (!$apiKeyValidated) {
				$errorMessage = $this->providerConnectionFalied();
				$apiUrlValid = $apiKeyValid = false;
				$hasErrors = true;
			}
			if (!$hasErrors) {
				return array(
					'redirect' => false,
					'has_errors' => false,
					'html' => '<strong>' . $this->getTitle() . '</strong> ' . __('Successfully connected', SG_POPUP_TEXT_DOMAIN)
				);
			}

		}

		$options = array(
			'api_url' => __( 'Please enter a valid ActiveCampaign URL', SG_POPUP_TEXT_DOMAIN ),
			'api_key' => __( 'Please enter a valid ActiveCampaign API key', SG_POPUP_TEXT_DOMAIN )
		);

		$stepHtml = '';
		if ($hasErrors) {
			$stepHtml .= '<span class="sui-notice sui-notice-error"><p>' . esc_html($errorMessage) . '</p></span>';
		}

		$response = array(
			'html'  => $stepHtml,
			'has_errors' => $hasErrors,
			'options' => $options
		);

		return $response;
	}

	private function validateCredentials($integrationDetails) 
	{
		if (empty($integrationDetails)) {
			return false;
		}

		$api = self::api($integrationDetails);
		if ($api) {
			$lists = $api->getLists();
		}

		if (is_wp_error($lists) || !$lists) {
			return false;
		}

		return true;
	}

	private function isNonEmpty($value) 
	{
		return !empty(trim($value));
	}
}
