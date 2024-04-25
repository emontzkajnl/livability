<?php
class SGPBFormBuilderConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_FORM_DIR', dirname(__FILE__).'/');
		self::addDefine('SGPB_FORM_VIEWS', SGPB_FORM_DIR.'views/');
		self::addDefine('SGPB_FORM_CLASSES', SGPB_FORM_DIR.'classes/');
		self::addDefine('SGPB_FORM_HELPERS', SGPB_FORM_DIR.'helpers/');
		self::addDefine('SGPB_FORM_CLASSES_FORMS', SGPB_FORM_CLASSES.'forms/');
		self::addDefine('SGPB_SUBSCRIPTION_PLUS_FORM_CLASSES_FIELDS', SGPB_FORM_CLASSES.'fields/');
		self::addDefine('SGPB_FORM_FILES', SGPB_FORM_DIR.'files/');
	}
}

SGPBFormBuilderConfig::init();
