<?php
namespace sgpbm;
use sgpbm\api\MailChimp;

class MailchimpSingleton
{
	// Hold an instance of the class
	private static $instance;

	// The singleton method
	public static function getInstance($key)
	{
		if (!isset(self::$instance)) {
			self::$instance = new MailChimp($key);
		}

		return self::$instance;
	}

	private function __clone()
	{

	}

	private function __construct()
	{

	}
}