<?php
namespace sgpb;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class LoginPopup extends SGPopup
{
	private $data;
	private $validateObj;

	public function __construct()
	{

	}

	public function adminCssInit() {
		add_filter('sgpbAdminCssFiles', array($this, 'adminCssFilter'), 1, 1);
	}

	public function adminJsInit()
	{
 		add_filter('sgpbAdminJsFiles', array($this, 'adminJsFilter'), 1, 1);
 	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function adminJsFilter($jsFiles)
	{
		$jsFiles[] = array('folderUrl' => SGPB_LOGIN_JS_URL, 'filename' => 'LoginAdmin.js');

		return $jsFiles;
	}

	public function setValidateObj($validateObj)
	{
		$this->validateObj = $validateObj;
	}

	public function getValidateObj()
	{
		return $this->validateObj;
	}

	public function adminCssFilter($cssFiles)
	{
		$cssFiles[] = array(
			'folderUrl' => SG_POPUP_CSS_URL,
			'filename' => 'ResetFormStyle.css'
		);

		$cssFiles[] = array(
			'folderUrl'=> SGPB_LOGIN_CSS_URL,
			'filename' => 'login.css'
		);

		return $cssFiles;
	}

	public function cssFilter($cssFiles)
	{
		$cssFiles[] = array(
			'folderUrl' => SG_POPUP_CSS_URL,
			'filename' => 'ResetFormStyle.css'
		);

		$cssFiles[] = array(
			'folderUrl'=> SGPB_LOGIN_CSS_URL,
			'filename' => 'login.css'
		);

		return $cssFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendJs', array($this, 'jsFilter'), 1, 1);
		add_filter('sgpbFrontendCssFiles', array($this, 'cssFilter'), 1, 1);
	}

	public function frontJsFilter($jsFiles)
	{
		$jsFiles[] = array(
			'folderUrl' => SGPB_LOGIN_JS_URL,
			'filename' => 'Validate.js'
		);
		$jsFiles[] = array(
			'folderUrl' => SGPB_LOGIN_JS_URL,
			'filename' => 'Login.js'
		);

		return $jsFiles;
	}

	public static function allowToOpen($options, $args)
	{
		$popupObj = @$args['popupObj'];
		$status = true;
		$userStatus = is_user_logged_in();

		if ($popupObj->getType() == SGPB_POPUP_TYPE_LOGIN && $userStatus) {
			$status = false;
		}

		return $status;
	}

	public function jsFilter($jsFiles)
	{
		$popupId = $this->getId();
		$jsFiles['jsFiles'][] = array(
			'folderUrl' => SGPB_LOGIN_JS_URL,
			'filename' => 'Validate.js'
		);

		$jsFiles['jsFiles'][] = array(
			'folderUrl' => SGPB_LOGIN_JS_URL,
			'filename' => 'Login.js'
		);

		$jsFiles['localizeData'][] = array(
			'handle' => 'Login.js',
			'name' => 'SGPB_LOGIN_VALIDATE_JSON_'.$popupId,
			'data' => $this->getValidateObj()
		);

		$jsFiles['localizeData'][] = array(
			'handle' => 'Login.js',
			'name' => 'SGPB_USER_STATUS',
			'data' => array(
				'isLoggedIn' => is_user_logged_in()
			)
		);

		return $jsFiles;
	}

	public function localizedData($localized)
	{

		$localizeData[] = '';

		return $localized;
	}

	public function getPopupTypeContent()
	{
		$id = $this->getId();
		$content = $this->getContent();
		$this->setLoginFormData($id);
		$formData = $this->createFormFieldsData();
		$forceRtlClass = '';


		$styleData = array(
			'placeholderColor' => $this->getOptionValue('sgpb-login-text-placeholder-color')
		);
		$content .= '<div class="sgpb-login-form-'.$id.' sgpb-login-form-admin-wrapper'.$forceRtlClass.'">';
		$content .= $this->getFormMessages();
		$content .= Functions::renderForm(@$formData, array('id'=> $id));
		$content .= '</div>';

		$content .= $this->getFormCustomStyles(@$styleData);

		$validateObj = $this->createValidateObj($formData);
		$this->setValidateObj($validateObj);

		$this->frontendFilters();

		return $content;
	}

	public function createValidateObj($contactFields)
	{
		$validateObj = '';
		$requiredMessage = $this->getOptionValue('sgpb-login-required-error');

		if (empty($contactFields)) {
			return $validateObj;
		}

		$rules = '"rules": { ';
		$messages = '"messages": { ';
		$validateObj = '{ ';

		foreach ($contactFields as $contactField) {

			if (empty($contactField['attrs'])) {
				continue;
			}

			$attrs = $contactField['attrs'];
			$type = 'text';
			$name = '';
			$required = false;

			if (!empty($attrs['type'])) {
				$type = $attrs['type'];
			}
			if (!empty($attrs['name'])) {
				$name = $attrs['name'];
			}
			if (!empty($attrs['data-required'])) {
				$required = $attrs['data-required'];
			}

			if ($type == 'email') {
				$rules .= '"'.$name.'": {required: true, email: true},';
				continue;
			}

			if (!$required) {
				continue;
			}

			$rules .= '"'.$name.'" : "required",';
			$messages .= '"'.$name.'" : "'.$requiredMessage.'",';
		}

		$rules = rtrim($rules, ',');
		$messages = rtrim($messages, ',');

		$rules .= '},';
		$messages .= '}';
		$validateObj .= $rules;
		$validateObj .= $messages;
		$validateObj .= '}';

		return $validateObj;
	}

	public function getExtraRenderOptions()
	{
		return array();
	}

	public function getPopupTypeMainView()
	{
		$this->adminJsInit();
		$this->adminCssInit();
		return array(
			'filePath' => SGPB_LOGIN_VIEWS_PATH.'mainView.php',
			'metaboxTitle' => __('Login Settings', SG_POPUP_TEXT_DOMAIN),
			'short_description' => 'Create your unique login popup by customizing all the features'
		);
	}

	public function setLoginFormData($formId)
	{
		$savedData = array();

		if (!empty($formId)) {
			$savedData = SGPopup::getSavedData($formId);
		}

		$this->setData($savedData);
	}

	private function getFieldValue($optionName)
	{
		$optionValue = '';
		$postData = $this->getData();

		if (!empty($postData[$optionName])) {
			return $postData[$optionName];
		}

		$defaultData = $this->getDefaultDataByName($optionName);

		// when saved data does not exist we try find inside default values
		if (empty($postData) && !empty($defaultData)) {
			return $defaultData['defaultValue'];
		}

		return $optionValue;
	}

	public function createFormFieldsData()
	{
		$formData = array();
		$inputStyles = array();
		$submitStyles = array();
		$postData = $this->getData();
		$passwordPlaceholder = $this->getFieldValue('sgpb-password-placeholder');

		$usernameLabel = false;
		$passwordLabel = false;
		$rememberMeLabel = false;
		if ($this->getFieldValue('sgpb-username-label'))  {
			$usernameLabel = $this->getFieldValue('sgpb-username-label');
		}
		if ($this->getFieldValue('sgpb-password-label'))  {
			$passwordLabel = $this->getFieldValue('sgpb-password-label');
		}
		if ($this->getFieldValue('sgpb-remember-me-label'))  {
			$rememberMeLabel = $this->getFieldValue('sgpb-remember-me-label');
		}

		if ($this->getFieldValue('sgpb-login-text-width'))  {
			$inputWidth = $this->getFieldValue('sgpb-login-text-width');
			$inputStyles['width'] = AdminHelper::getCSSSafeSize($inputWidth);
		}
		if ($this->getFieldValue('sgpb-login-text-height')) {
			$inputHeight = $this->getFieldValue('sgpb-login-text-height');
			$inputStyles['height'] = AdminHelper::getCSSSafeSize($inputHeight);
		}
		if ($this->getFieldValue('sgpb-login-text-border-width')) {
			$inputBorderWidth = $this->getFieldValue('sgpb-login-text-border-width');
			$inputStyles['border-width'] = AdminHelper::getCSSSafeSize($inputBorderWidth);
		}
		if ($this->getFieldValue('sgpb-login-text-border-color')) {
			$inputStyles['border-color'] = $this->getFieldValue('sgpb-login-text-border-color');
		}
		if ($this->getFieldValue('sgpb-login-text-bg-color')) {
			$inputStyles['background-color'] = $this->getFieldValue('sgpb-login-text-bg-color');
		}
		if ($this->getFieldValue('sgpb-login-text-color')) {
			$inputStyles['color'] = $this->getFieldValue('sgpb-login-text-color');
		}

		if ($this->getFieldValue('sgpb-login-btn-width')) {
			$submitWidth = $this->getFieldValue('sgpb-login-btn-width');
			$submitStyles['width'] = AdminHelper::getCSSSafeSize($submitWidth);
		}
		if ($this->getFieldValue('sgpb-login-btn-height')) {
			$submitHeight = $this->getFieldValue('sgpb-login-btn-height');
			$submitStyles['height'] = AdminHelper::getCSSSafeSize($submitHeight);
		}
		if ($this->getFieldValue('sgpb-login-btn-bg-color')) {
			$submitStyles['background-color'] = $this->getFieldValue('sgpb-login-btn-bg-color');
		}
		if ($this->getFieldValue('sgpb-login-btn-text-color')) {
			$submitStyles['color'] = $this->getFieldValue('sgpb-login-btn-text-color');
		}
		$submitStyles['text-transform'] = 'none !important';

		$firstNamePlaceholder = $this->getFieldValue('sgpb-username-placeholder');
		$requiredFields = true;
		if (is_admin()) {
			$requiredFields = false;
		}
		$inputStyles['border-style'] = 'solid';
		$formData['username'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'text',
				'hasLabel' => $usernameLabel,
				'data-required' => $requiredFields,
				'autocomplete' => 'off',
				'name' => 'sgpb-login-username',
				'data-username' => 'sgpb-login-username',
				'placeholder' => $firstNamePlaceholder,
				'class' => 'js-login-text-inputs js-login-username-input',
				'labelClass' => 'js-login-username-label-edit',
				'data-error-message-class' => 'sgpb-login-username-error-message'
			),
			'style' => $inputStyles,
			'errorMessageBoxStyles' => $inputStyles['width']
		);

		$formData['password'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'password',
				'hasLabel' => $passwordLabel,
				'data-required' => $requiredFields,
				'name' => 'sgpb-login-password',
				'autocomplete' => 'new-password',
				'data-password' => 'sgpb-login-password',
				'placeholder' => $passwordPlaceholder,
				'class' => 'js-login-text-inputs js-login-password-input',
				'labelClass' => 'js-login-password-label-edit',
				'data-error-message-class' => 'sgpb-login-password-error-message'
			),
			'style' => $inputStyles,
			'errorMessageBoxStyles' => $inputStyles['width']
		);

		if (is_admin()) {
			$formData['email']['attrs']['type'] = 'text';
			$formData['password']['attrs']['type'] = 'text';
		}

		// remember me checkbox
		$rememberMeLabel = $this->getOptionValueFromSavedData('sgpb-remember-me-label');
		$isShow = ($this->getOptionValueFromSavedData('sgpb-remember-me-status')) ? true : false;

		$formData['remember-me'] = array(
			'isShow' => $isShow,
			'attrs' => array(
				'type' => 'customCheckbox',
				'hasLabel' => $rememberMeLabel,
				'data-required' => false,
				'name' => 'sgpb-remember-me',
				'class' => 'js-login-remember-me-inputs js-login-remember-me-label',
				'id' => 'sgpb-remember-me-field-label',
				'labelClass' => 'js-login-remember-me-label-edit',
				'data-error-message-class' => 'sgpb-remember-me-error-message'
			),
			'style' => array('width' => $inputWidth),
			'label' => $rememberMeLabel,
			'text' => $this->getFieldValue('sgpb-remember-me-text'),
			'errorMessageBoxStyles' => $inputStyles['width']
		);

		$hiddenChecker['position'] = 'absolute';
		// For protected bots and spams
		$hiddenChecker['left'] = '-5000px';
		$hiddenChecker['padding'] = '0';
		$formData['hidden-checker'] = array(
			'isShow' => false,
			'attrs' => array(
				'type' => 'hidden',
				'data-required' => false,
				'name' => 'sgpb-login-hidden-checker',
				'value' => '',
				'class' => 'js-login-text-inputs js-login-last-name-input'
			),
			'style' => $hiddenChecker
		);

		$submitTitle = $this->getFieldValue('sgpb-login-btn-title');
		$progressTitle = $this->getFieldValue('sgpb-login-btn-progress-title');
		$formData['submit'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'submit',
				'name' => 'sgpb-login-submit',
				'value' => $submitTitle,
				'data-title' => $submitTitle,
				'data-progress-title' => $progressTitle,
				'class' => 'js-login-submit-btn'
			),
			'style' => $submitStyles
		);

		return $formData;
	}

	public function getFormCustomStyles($styleData)
	{
		$placeholderColor = $styleData['placeholderColor'];
		$formBackgroundColor = $this->getFieldValue('sgpb-login-form-bg-color');
		$formPadding = $this->getFieldValue('sgpb-login-form-padding');
		$formBackgroundOpacity = $this->getFieldValue('sgpb-login-form-bg-opacity');
		$popupId = $this->getId();
		if (isset($styleData['formBackgroundOpacity'])) {
			$formBackgroundOpacity = $styleData['formBackgroundOpacity'];
		}
		if (isset($styleData['formColor'])) {
			$formBackgroundColor = $styleData['formColor'];
		}
		if (isset($styleData['formPadding'])) {
			$formPadding = $styleData['formPadding'];
		}
		$formBackgroundColor = AdminHelper::hexToRgba($formBackgroundColor, $formBackgroundOpacity);

		ob_start();
		?>
			<style type="text/css">
				.sgpb-login-form-<?php echo $popupId; ?> {background-color: <?php echo $formBackgroundColor; ?>;padding: <?php echo $formPadding.'px'; ?>}
				.sgpb-login-form-<?php echo $popupId; ?> .js-login-text-inputs::-webkit-input-placeholder {color: <?php echo $placeholderColor; ?>;font-weight: lighter;}
				.sgpb-login-form-<?php echo $popupId; ?> .js-login-text-inputs::-moz-placeholder {color:<?php echo $placeholderColor; ?>;font-weight: lighter;}
				.sgpb-login-form-<?php echo $popupId; ?> .js-login-text-inputs:-ms-input-placeholder {color:<?php echo $placeholderColor; ?>;font-weight: lighter;} /* ie */
				.sgpb-login-form-<?php echo $popupId; ?> .js-login-text-inputs:-moz-placeholder {color:<?php echo $placeholderColor; ?>;font-weight: lighter;}
			</style>
		<?php
		$styles = ob_get_contents();
		ob_get_clean();

		return $styles;
	}

	public function getSubPopupObj()
	{
		$options = $this->getOptions();
		$subPopups = parent::getSubPopupObj();
		if ($options['sgpb-login-success-behavior'] == 'openPopup') {
			$subPopupId = (!empty($options['sgpb-login-success-popup'])) ? (int)$options['sgpb-login-success-popup']: null;

			if (empty($subPopupId)) {
				return $subPopups;
			}

			$subPopupObj = SGPopup::find($subPopupId);
			if (!empty($subPopupObj) && ($subPopupObj instanceof SGPopup)) {
				// We remove all events because this popup will be open after successful login
				$subPopupObj->setEvents(array('param' => 'click', 'value' => ''));
				$subPopups[] = $subPopupObj;
			}
		}

		return $subPopups;
	}

	public function getFormMessages()
	{
		$alreadyLoggedInMessage = $this->getOptionValue('sgpb-already-login-message');
		if (!$alreadyLoggedInMessage) {
			$alreadyLoggedInMessage = 'You are already logged In. Processing after login actions ...please wait';
		}
		$customErrorMessage = $this->getOptionValue('sgpb-custom-error-message');
		$customErrorClass = $errorMessage = '';
		if ($customErrorMessage) {
			$errorMessage = $this->getOptionValue('sgpb-custom-login-error-message');
			$customErrorClass = ' sgpb-custom-login-error-message';
		}
		$messages = '<div class="login-form-messages sgpb-alert sgpb-alert-danger sg-hide-element'.$customErrorClass.'">';
		$messages .= '<p>'.$errorMessage.'</p>';
		$messages .= '</div>';
		$messages .= '<div class="sgpb-alert sgpb-alert-success sg-hide-element">';
		$messages .= '<p>'.$alreadyLoggedInMessage.'</p>';
		$messages .= '</div>';

		return $messages;
	}
}
