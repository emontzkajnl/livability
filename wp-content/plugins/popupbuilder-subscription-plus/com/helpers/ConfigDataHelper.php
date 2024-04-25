<?php
namespace sgpbsubscriptionplus;

class SubscriptionPlusConfigDataHelper 
{
	public static $providersData;

	public static function providersDefaultData() 
	{
		$providersData = array(
			'default' => array(
				'name' => 'Local List',
				'logo' => SGPB_SUBSCRIPTION_PLUS_IMG_URL.'/defaultListIcon.png'
			), 
			'active-campaign' => array(
				'name' => 'ActiveCampaign',
				'logo' => SG_POPUP_EMAIL_INTEGRATIONS_URL.'active-campaign/img/logo.png',
				'info' => __('Log in to your <a href="https://www.activecampaign.com/login/"> ActiveCampaign </a>account to get your URL and API Key.', SG_POPUP_TEXT_DOMAIN),
				'classPath' => SG_POPUP_EMAIL_INTEGRATIONS_PATH.'active-campaign/activecampaign.php',
				'connectionFormfields' => array(
					'api_url' => array(
						'label' => __('API URL', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('Enter URL', SG_POPUP_TEXT_DOMAIN)
					),
					'api_key' => array(
						'label' => __('API Key', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('Enter Key', SG_POPUP_TEXT_DOMAIN)
					),
					'identifier' => array(
						'label' => __('Identifier', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('E.g. Business Account', SG_POPUP_TEXT_DOMAIN)
					)
				)
			),
			'getresponse' => array(
				'name' => 'GetResponse',
				'logo' => SG_POPUP_EMAIL_INTEGRATIONS_URL.'getresponse/img/logo.png',
				'info' => __('Log in to your <a href="https://app.getresponse.com/api">GetResponse account</a> to get your API Key v3.', SG_POPUP_TEXT_DOMAIN),
				'classPath' => SG_POPUP_EMAIL_INTEGRATIONS_PATH.'getresponse/getresponse.php',
				'connectionFormfields' => array(
					'api_key' => array(
						'label' => __('API Key', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('Enter Key', SG_POPUP_TEXT_DOMAIN)
					),
					'identifier' => array(
						'label' => __('Identifier', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('E.g. Business Account', SG_POPUP_TEXT_DOMAIN)
					)
				)
			),
			'sendinblue' => array(
				'name' => 'SendinBlue',
				'logo' => SG_POPUP_EMAIL_INTEGRATIONS_URL.'sendinblue/img/logo.png',
				'info' => __('To get SendinBlue API key v3.0 log in <a href="https://account.sendinblue.com/advanced/api">campaigns dashboard</a> and click on SMTP & API in left menu.', SG_POPUP_TEXT_DOMAIN),
				'classPath' => SG_POPUP_EMAIL_INTEGRATIONS_PATH.'sendinblue/sendinblue.php',
				'connectionFormfields' => array(
					'api_key' => array(
						'label' => __('API Key', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('Enter Key', SG_POPUP_TEXT_DOMAIN)
					),
					'identifier' => array(
						'label' => __('Identifier', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('E.g. Business Account', SG_POPUP_TEXT_DOMAIN)
					)
				)			
			),
			'hubspot' => array(
				'name' => 'HubSpot',
				'logo' => SG_POPUP_EMAIL_INTEGRATIONS_URL.'hubspot/img/logo.png',
				'info' => __('Log in to your <a href="https://app.hubspot.com/myaccounts-beta">HubSpot account</a> to get your API Key.', SG_POPUP_TEXT_DOMAIN),
				'classPath' => SG_POPUP_EMAIL_INTEGRATIONS_PATH.'hubspot/hubspot.php',
				'connectionFormfields' => array(
					'api_key' => array(
						'label' => __('API Key', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('Enter Key', SG_POPUP_TEXT_DOMAIN)
					),
					'identifier' => array(
						'label' => __('Identifier', SG_POPUP_TEXT_DOMAIN).' *',
						'placeholder' => __('E.g. Business Account', SG_POPUP_TEXT_DOMAIN)
					)
				)			
			)
		);

		return $providersData;
	}

	public static function addNewAutoresponderUrl() {
		return admin_url('post-new.php?post_type='.SG_POPUP_AUTORESPONDER_POST_TYPE);
	}
	public static function addNewEmailTemplateUrl() {
		return admin_url('post-new.php?post_type='.SG_POPUP_TEMPLATE_POST_TYPE);
	}
}
