<?php
namespace sgpbcontactform;

class CFFormCreator
{
	private $classPath = SGPB_CF_FORM_CLASSES_FORMS;

	public function setClassPath($classPath)
	{
		$this->classPath = $classPath;
	}

	public function getClassPath()
	{
		return $this->classPath;
	}

	public function create($className, $args = array())
	{
		$classPath = $this->getClassPath();
		$className = ucfirst($className).'Form';
		$classFullPath = $classPath.$className.'.php';
		if (!file_exists($classFullPath)) {
			return '';
		}
		require_once($classFullPath);
		$className = __NAMESPACE__.'\\'.$className;
		if (!isset($args['savedFormFields'])) {
			$args['savedFormFields'] = array();
		}
		$obj = new $className($args['savedFormFields']);

		return $obj;
	}

	public static function createCFFormObj($popupObj)
	{
		if (!is_object($popupObj)) {
			return '';
		}
		$currentFieldsJson = $popupObj->getOptionValue('sgpb-contact-fields-json');
		$freeSavedOptions = $popupObj->getOptionValue('sgpb-contact-fields');
		// for back work compatibility
		if (empty($currentFieldsJson) && !empty($freeSavedOptions)) {
			$currentFieldsJson = CFFormCreator::createSavedObjFromFreeOptions($freeSavedOptions, $popupObj);
		}
		// for the old silver/gold/platinum vesion compatibility
		else if (empty($freeSavedOptions)) {
			$savedFormFields = $popupObj->createFormFieldsDataOld();
			$currentFieldsJson = CFFormCreator::createSavedObjFromFreeOptions($savedFormFields, $popupObj);
		}
		$savedFormFields = json_decode($currentFieldsJson, true);

		if (empty($savedFormFields)) {
			return '';
		}

		$creationArgs = array('savedFormFields' => $savedFormFields);
		$formCreator = new CFFormCreator();
		$form = $formCreator->create('Contactbuilder', $creationArgs);
		$form->setPopupObj($popupObj);

		return $form;
	}

	/**
	 * Backward compatibility
	 *
	 * @param $freeOptions
	 * @param $popupObj
	 * @return string
     */
	public static function createSavedObjFromFreeOptions($freeOptions, $popupObj)
	{
		$savedObj = array();

		// form Obj
		$formCreator = new CFFormCreator();
		$form = $formCreator->create('Contactbuilder');
		$form->setPopupObj($popupObj);

		foreach ($freeOptions as $fieldKey => $data) {
			if (empty($data['isShow'])) {
				continue;
			}

			$type = $fieldKey;
			if ($fieldKey == 'message') {
				$type = 'textarea';
			}
			if ($fieldKey == 'first-name') {
				$type = 'firstname';
			}
			else if ($fieldKey == 'last-name') {
				$type = 'lastname';
			}

			$fieldObj = $form->createFieldObjByType($type);

			if (empty($fieldObj)) {
				continue;
			}
			$settings = $fieldObj->getFieldSettings();

			// for gdpr field
			if (!empty($data['label'])) {
				$settings['label'] = $data['label'];
			}
			if (!empty($data['text'])) {
				$settings['gdprText']  = $data['text'];
			}

			// for submit button
			if (!empty($data['attrs']['value'])) {
				$settings['buttonLabel'] = $data['attrs']['value'];
			}
			if (!empty($data['attrs']['data-progress-title'])) {
				$settings['dataProgress'] = $data['attrs']['data-progress-title'];
			}

			$settings['required'] = @$data['attrs']['data-required'];
			@$settings['placeholder'] = @$data['attrs']['placeholder'];
			$settings['type'] = $type;

			$savedObj[] = $settings;
		}

		return json_encode($savedObj);
	}
}
