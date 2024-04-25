<?php
class sgpbcontactformBuilderConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_CF_FORM_DIR', dirname(__FILE__).'/');
		self::addDefine('SGPB_CF_FORM_VIEWS', SGPB_CF_FORM_DIR.'views/');
		self::addDefine('SGPB_CF_FORM_CLASSES', SGPB_CF_FORM_DIR.'classes/');
		self::addDefine('SGPB_CF_FORM_HELPERS', SGPB_CF_FORM_DIR.'helpers/');
		self::addDefine('SGPB_CF_FORM_CLASSES_FORMS', SGPB_CF_FORM_CLASSES.'forms/');
		self::addDefine('SGPB_CF_FORM_CLASSES_FIELDS', SGPB_CF_FORM_CLASSES.'fields/');
		self::addDefine('SGPB_CF_FORM_FILES', SGPB_CF_FORM_DIR.'files/');
	}
}

sgpbcontactformBuilderConfig::init();
