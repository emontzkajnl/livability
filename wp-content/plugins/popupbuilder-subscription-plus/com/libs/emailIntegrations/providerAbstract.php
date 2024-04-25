<?php
namespace sgpbsubscriptionplus;
require_once(SG_POPUP_EMAIL_INTEGRATIONS_PATH.'providerInterface.php');

abstract class SGPBProviderAbstract implements SGPBProviderInterface
{
	const LISTS = 'lists';
	protected static $instance;
	protected $slug;
	protected $class;
	protected $title;

	final public function getSlug() 
	{
		return $this->slug;
	}

	final public function getClass() 
	{
		return $this->class;
	}

	final public function getTitle() 
	{
		return $this->title;
	}

	final public function toArray() 
	{
		$to_array = array(
			'slug' => $this->getSlug(),
			'title' => $this->getTitle(),
			'class' => $this->getClass(),
		);

		return $to_array;
	}

	protected function getCurrentData($currentData, $submittedData) 
	{
		foreach ($currentData as $key => $currentField) {
			if (isset($submittedData[$key])) {
				$currentData[$key] = $submittedData[$key];
			}
			elseif (isset($savedSettings[$key])) {
				$currentData[$key] = $savedSettings[$key];
			} 
		}

		return $currentData;
	}

	protected function providerConnectionFalied()
	{
		$errorMessage = sprintf(__('We couldn\'t connect to your %s account. Please resolve the errors below and try again.', SG_POPUP_TEXT_DOMAIN), $this->title);
		return $errorMessage;
	}
}
