<?php
namespace sgpbaw;
use sgpb\SGPopup;

class Filters
{
	private $popup = array();

	public function setPopup($popup)
	{
		$this->popup = $popup;
	}

	public function getPopup()
	{
		return $this->popup;
	}

	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		// by default, it's called inside after register popup builder post type but here we need it to call to get current popup type
		if (class_exists('\SgpbPopupConfig')) {
			\SgpbPopupConfig::popupTypesInit();
		}
		if (isset($_GET['post']) && class_exists('sgpb\SGPopup')) {
			$popup = @SGPopup::find($_GET['post']);
			$this->setPopup($popup);
		}
		$this->init();
	}

	private function init()
	{
		$popup = $this->getPopup();
		// popup builder pages
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11);
		}
		// edit page
		if ((isset($_GET['sgpb_type']) && $_GET['sgpb_type'] == SGPB_POPUP_TYPE_AWEBER) || (is_object($popup) && $popup->getType() == SGPB_POPUP_TYPE_AWEBER)) {
			add_action('sgpbPopupDefaultOptions', array($this, 'defaultOptions'), 11);
		}
	}

	public function defaultOptions($options)
	{
		$sgpbAweberObj = new SGPBAWeberApi();
		if (empty($sgpbAweberObj)) {
			return $options;
		}
		$defaultList = '';
		$defaultFormId = '';

		$idTitles = $sgpbAweberObj->createIdAndTitles();
		if (!empty($idTitles)) {
			$listIds = array_keys($idTitles);
			$defaultList = $listIds[0];
		}

		if (!empty($defaultList)) {
			$signupsIdTitles = $sgpbAweberObj->getListIdAndTitles($defaultList);

			if (!empty($signupsIdTitles)) {
				$formId = array_keys($signupsIdTitles);
				$defaultFormId = $formId[0];
			}
		}
		$options[] = array('name' => 'sgpb-aweber-list', 'type' => 'text', 'defaultValue' => $defaultList);
		$options[] = array('name' => 'sgpb-aweber-signup-form', 'type' => 'text', 'defaultValue' => $defaultFormId);
		$options[] = array('name' => 'sgpb-aweber-invalid-email', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-aweber-invalid-email-message', 'type' => 'text', 'defaultValue' => __('Unable to send', SG_POPUP_TEXT_DOMAIN).'.');
		$options[] = array('name' => 'sgpb-aweber-custom-subscribed', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-aweber-custom-subscribed-message', 'type' => 'text', 'defaultValue' => __('A user with this email address has already subscribed', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-aweber-required-message', 'type' => 'text', 'defaultValue' => __('This field is required', SG_POPUP_TEXT_DOMAIN).'.');
		$options[] = array('name' => 'sgpb-aweber-validate-email-message', 'type' => 'text', 'defaultValue' => __('Invalid email', SG_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'sgpb-aweber-success-message', 'type' => 'text', 'defaultValue' => __('You have successfully subscribed to our mail list', SG_POPUP_TEXT_DOMAIN).'.');
		$options[] = array('name' => 'sgpb-aweber-success-redirect-URL', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-aweber-success-redirect-new-tab', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'sgpb-aweber-success-behavior', 'type' => 'text', 'defaultValue' => 'showMessage');

		return $options;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_AWEBER] = SGPB_AWEBEBER_AVALIABLE_VERSION;


		return $popupType;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_AWEBER] = SGPB_AWEBER_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_AWEBER] = __('AWeber', SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}
}
