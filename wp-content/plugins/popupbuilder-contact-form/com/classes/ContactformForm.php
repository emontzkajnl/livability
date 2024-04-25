<?php
namespace sgpbcontactform;
use \sgpbcontactform\CFFormCreator;
require_once(SGPB_CF_FORM_CLASSES_FORMS.'/CFFormCreator.php');

class ContactformForm
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
        add_filter('sgpbContactFormJsFilter', array($this, 'contactFormFrontendJs'), 10, 1);
    }

    public function contactFormFrontendJs($jsData)
    {
        $jsFiles = $jsData['jsFiles'];
        $localizeData = $jsData['localizeData'];

        $jsFiles[] = array('folderUrl'=> SGPB_CONTACT_FORM_JS_URL, 'filename' => 'CFForm.js');
        $jsFiles[] = array('folderUrl'=> SGPB_CONTACT_FORM_JS_URL, 'filename' => 'Validate.js');

        $scriptData = array(
            'jsFiles' => $jsFiles,
            'localizeData' => $localizeData
        );

        return $scriptData;
    }

    public function render()
    {
        $popupObj = $this->getPopupObj();
        $cfForm = CFFormCreator::createCFFormObj($popupObj);

        $this->frontendFilters();

        return $cfForm;
    }
}
