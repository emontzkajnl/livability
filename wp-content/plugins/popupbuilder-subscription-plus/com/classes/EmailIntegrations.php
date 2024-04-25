<?php 
namespace sgpbsubscriptionplus;
use sgpbsubscriptionplus\SubscriptionPlusConfigDataHelper as SubscriptionPlusConfigDataHelper; 
use sgpb\AdminHelper;
use sgpb\SGPopup;

class EmailIntegrations
{
	private static $instance = null;

	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$this->includeFiles();
		add_filter('sgpbSavePopupOptions', array($this, 'filterOptions'), 10, 1);
	}

	public function filterOptions($data) 
	{
		$emailIntegrations = get_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS);
		if (empty($emailIntegrations)) {
			return $data;
		}
		foreach ($emailIntegrations as $appKey => $appValue) {
			$integrationName = str_replace('sgpb-', '', $appKey);
			if (!isset($data['sgpb-subs-active-integrations-'.$integrationName])) {
				unset($data['sgpb-subs-active-integrations-list-'.$integrationName]);
			}
		}
		self::updateOption($data);

		return $data;
	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function getSubscriberDetails($subscriberData, $popupId)
	{
		$defaultData = array(
			'label' => __('Email', SG_POPUP_TEXT_DOMAIN),
			'value' => @$subscriberData['email']
		);
		$defaultData = array($defaultData);
		$subscriberData['popupId'] = $popupId;
		$result = SubscriptionPlusAdminHelper::collectDataIntoArray($subscriberData);

		if (!empty($result)) {
			$result = json_decode($result, true);
			return $result;
		}

		return json_encode($defaultData);
	}

	public static function createProviderObjectById($appId)
	{
		$allProviders = EmailIntegrations::allEmailProvidersList();
		$currenProviderData = $allProviders[$appId];

		$classPrefix = 'SGPB';
		$filePath = $currenProviderData['classPath'];

		if (!file_exists($filePath)) {
			wp_die(SGPB_AJAX_STATUS_FALSE);
		}
		require_once($filePath);
		// get class name
		$className = $classPrefix.$currenProviderData['name'];
		$className = sprintf("sgpbsubscriptionplus\%s", $className); 
		// create an object from a class (new ActiveCampaign(), etc.)
		$obj = $className::getInstance();

		return $obj;
	}

	public static function prepareDataToSend($formData, $apiObj, $popupPostId)
	{
		// Get data for current provider reserved fields
		$data = $apiObj->getSubmitedDataForDefaultFields($formData);
		$defaultData = $data['defaultArgs'];
		unset($data['defaultArgs']);
		$formData = EmailIntegrations::getSubscriberDetails($data, $popupPostId);
		$data = array_merge($defaultData, $formData);

		return $data;
	}
	
	public static function getData()
	{
		$popupId = get_the_ID();
		$options = get_post_meta($popupId, 'sg_popup_options', true);

		return $options;
	}

	public static function updateOption($data) 
	{
		$popupId = get_the_ID();
		update_post_meta($popupId, 'sg_popup_options', $data);
	}

	public static function includeFiles()
	{
		$apps = self::allEmailProvidersList();
		foreach ($apps as $key => $value) {
			if ($key == 'default') {
				continue;
			}
			if (file_exists($value['classPath'])) {
				require_once($value['classPath']);	
			}
		} 
	}

	public static function allEmailProvidersList($separate = false) 
	{
		$providers = SubscriptionPlusConfigDataHelper::providersDefaultData();

		if (!$separate) {
			return $providers;
		}

		// this option used to get active providers apiKey and apiUrl
		$emailIntegrationsData = get_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS);
		$availableProviders = array();
		$activeProviders = array();

		foreach ($providers as $key => $value) {                
			$optionName = 'sgpb-'.$key;
			if (isset($emailIntegrationsData[$optionName]) || $key == 'default') {
				$activeProviders[$key] = $value;
			}
			else {
				$availableProviders[$key] = $value;
			}
		}

		$providers = array (
			'active' => $activeProviders,
			'available' => $availableProviders
		);

		return $providers;
	}

	public static function getIntegrationFieldsJson()
	{
		$settings = self::allEmailProvidersList();
		$options = self::getData();
		$data = array();
		
		foreach ($settings as $key => $value) {
			$status = '';
			if (isset($options['sgpb-subs-active-integrations-'.$key]) && $key != 'default') {
				$list = $options['sgpb-subs-active-integrations-list-'.$key]; 
				$status = 'checked';
				$data[$key] = array('list' => $list);
			}
		}
		$json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		if (isset($options['sgpb-subs-active-integrations'])){
			$options['sgpb-subs-active-integrations']= $json;
		}
		self::updateOption($options);

		return $json;
	}

	public static function getActiveIntegrationsAdminTemplate()
	{
		$html = ''; 
		$allProvidersData = self::allEmailProvidersList(true);
		$allActiveProvidersData = $allProvidersData['active'];
		$options = self::getData();
	
		foreach ($allActiveProvidersData as $key => $value) {
			$isActive = '';
//			$icon = '<img class="sgpb-app-icon" src="'.$value['logo'].'">';
			$disabled = '';

			if ($key == 'default' || isset($options['sgpb-subs-active-integrations-'.$key])) {
				$isActive = 'checked';
				if ($key == 'default') {
					$disabled = ' sgpb-integrations-default-disabled-connection';
//					$icon = '<span class="dashicons dashicons-yes"></span>';
				}
				$icon = '';
			} else {
				$icon = '<span class="sgpb-arrows sgpb-arrow-up"><span></span><span></span></span>';
			}

			$html .= '<div class="sgpb-integrations-fields-main-wrapper" data-order-index="'.$key.'"><div onclick="javascript:void(0)" class="sgpb-field-icon-wrapper sgpb-field-icon-wrapper-'.$key.''.$disabled.'" data-type="integrations" data-index="'.$key.'">';
			$html .= '<div class="sgpb-field-display-name sgpb-add-integrations-'.$key.' sgpb-integrations-field-icon-wrapper sgpb-display-flex sgpb-justify-content-between sgpb-padding-x-10"><div><span>'.$value['name'].'</span><div class="sgpb-field-config">
				<label class="sgpb-switch"><input class="sg-switch-checkbox sgpb-integrations-checkbox" data-switch-id="'.$key.'" type="checkbox" '.$isActive.' name="sgpb-subs-active-integrations-'.$key.'"><div class="sgpb-slider sgpb-round"></div></label>
				</div></div>'.$icon.'</div>';
			$html .= '</div>';
			$html .= '<div class="sgpb-edit-settings-area-wrapper-'.$key.' sgpb-edit-settings-area-wrapper sgpb-hide">';
			$html .= '<div style="display: block;">';
			$html .= self::getEditSettingsRowsHtml($key).'</div>';
			$html .= '</div>';
			
			unset($allActiveProvidersData['default']);
			if ($key == 'default' && !empty($allActiveProvidersData)) {
				$html .= '<label>Available extra connections</label>';	
			}
			
			$html .= '</div>';
		}
		if (empty($allActiveProvidersData)) {
			$html .= '<a class="sgpb-connect-link" href="'.admin_url().'/edit.php?post_type='.SG_POPUP_POST_TYPE.'&page='.SG_POPUP_EMAIL_INTEGRATIONS_SCREEN.'">'.__('Click here to connect the popup with your mailing service', SG_POPUP_TEXT_DOMAIN).'.</a>';
		}

		return $html;
	}   

	public static function getEditSettingsRowsHtml($key)
	{
		$html = '';
		$selectedList = '';
		if ($key == 'default') {
			return $html;
		}

		$apiObject = self::initApiObject($key);
		$lists = self::getLists($apiObject);
		$options = self::getData();

		if (isset($options['sgpb-subs-active-integrations-list-'.$key])) {
			$selectedList = $options['sgpb-subs-active-integrations-list-'.$key];
		}
		
		$html .= '<div class="formItem">';
		$html .= '<label class="col-md-6">'.__('Choose email list', SG_POPUP_TEXT_DOMAIN).'</label>';
		$selectBox = AdminHelper::createSelectBox($lists, $selectedList, array('name' => 'sgpb-subs-active-integrations-list-'.$key, 'class' => 'js-sg-select2 sgpb-width-100'));
		$html .= '<div class="col-md-6">'.$selectBox.'</div>';
		$html .= '</div>';
		
		return $html;
	}   

	private static function initApiObject($key)
	{
		$allProvidersData = self::allEmailProvidersList(true);
		$allActiveProvidersData = $allProvidersData['active'];
		$currenProviderData = $allActiveProvidersData[$key];
		
		$settings = get_option(SG_POPUP_EMAIL_INTEGRATIONS_SETTINGS);
		if (!isset($settings['sgpb-'.$key])) {
			return '';
 		}
		
		$className = 'SGPB'.$currenProviderData['name'];
		$className = sprintf("sgpbsubscriptionplus\%s",$className); 
		$obj = $className::api($settings['sgpb-'.$key]);

		return $obj;
	}

	private static function getLists($apiObj)
	{
		$lists = $apiObj->getLists();
		$res = array();
		$listId = '';
		if (empty($lists)) {
			return $res;
		}
		if (array_key_exists('lists', $lists )) {
			$lists = $lists['lists'];
		}
		
		foreach ($lists as $key => $list) {
			if (!is_array($lists[$key])) {
				$res = $lists;	
			}
			elseif (array_key_exists('listId', $list)) {
				$listId = $list['listId'];
				$res[$listId] = $list['name'];	
			}
			else {
				$listId = array_values($list)[0];
				$res[$listId] = $list['name'];	
			}
		}
		
		return $res;
	}
}

EmailIntegrations::getInstance();
