<?php
namespace sgpbsubscriptionplus;
use \sgpbform\FormCreator;
require_once(SGPB_FORM_CLASSES_FORMS.'FormCreator.php');

class SubscriptionPlusForm
{
	private $popupObj;

	public function setPopupObj($popupObj)
	{
		$this->popupObj = $popupObj;
	}

	public function getPopupObj()
	{
		return $this->popupObj;
	}

	public function frontendFilters()
	{
		add_filter('sgpbSubscriptionPlusJsFilter', array($this, 'subscriptionPlusFrontendJs'), 10, 1);
	}

	public function subscriptionPlusFrontendJs($jsData)
	{
		$jsFiles = $jsData['jsFiles'];
		$localizeData = $jsData['localizeData'];

		$jsFiles[] = array('folderUrl'=> SGPB_SUBSCRIPTION_PLUS_JS_URL, 'filename' => 'SubscriptionPlusForm.js', 'dep' => array('jquery'),'inFooter' => true);
		$jsFiles[] = array('folderUrl'=> SGPB_SUBSCRIPTION_PLUS_JS_URL, 'filename' => 'Validate.js', 'dep' => array('jquery'), 'inFooter' => true);

		$scriptData = array(
			'jsFiles' => $jsFiles,
			'localizeData' => $localizeData
		);

		return $scriptData;
	}

	public function render()
	{
		$popupObj = $this->getPopupObj();
		$subscriptionPlusForm = FormCreator::createSubscriptionFormObj($popupObj);

		$this->frontendFilters();

		return $subscriptionPlusForm;
	}
}
