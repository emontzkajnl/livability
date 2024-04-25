<?php
class SGPBAdBlock
{
	public static function addDefine($name, $value)
	{
		if(!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_AD_BLOCK_PATH', WP_PLUGIN_DIR.'/'.SGPB_AD_BLOCK_FOLDER_NAME.'/');
		self::addDefine('SGPB_AD_BLOCK_DYNAMIC_CLASS_PATH', SGPB_AD_BLOCK_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_AD_BLOCK_PUBLIC_URL', plugins_url().'/'.SGPB_AD_BLOCK_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_AD_BLOCK_COM_PATH', SGPB_AD_BLOCK_PATH.'com/');
		self::addDefine('SGPB_AD_BLOCK_PUBLIC_PATH', SGPB_AD_BLOCK_PATH.'public/');
		self::addDefine('SGPB_AD_BLOCK_CLASSES_PATH', SGPB_AD_BLOCK_COM_PATH.'classes/');
		self::addDefine('SGPB_AD_BLOCK_VERSION_DETECTION_PATH', SGPB_AD_BLOCK_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_AD_BLOCK_EXTENSION_FILE_NAME', 'PopupBuilderAdBlockExtension.php');
		self::addDefine('SGPB_AD_BLOCK_EXTENSION_CLASS_NAME', 'SGPBPopupBuilderAdBlockExtension');
		self::addDefine('SGPB_AD_BLOCK_HELPERS', SGPB_AD_BLOCK_COM_PATH.'helpers/');

		self::addDefine('SGPB_AD_BLOCK_URL', plugins_url().'/'.SGPB_AD_BLOCK_FOLDER_NAME.'/');
		self::addDefine('SGPB_AD_BLOCK_JAVASCRIPT_URL', SGPB_AD_BLOCK_PUBLIC_URL.'js/');
		self::addDefine('SGPB_AD_BLOCK_TEXT_DOMAIN', SGPB_AD_BLOCK_FOLDER_NAME);
		self::addDefine('SGPB_AD_BLOCK_PLUGIN_MAIN_FILE', 'PopupBuilderAdBlock.php');
		self::addDefine('SGPB_AD_BLOCK_ACTION_KEY', 'AdBlock');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_AD_BLOCK_STORE_URL', 'https://popup-builder.com/');
		self::addDefine('SGPB_AD_BLOCK_ITEM_ID', 9329);
		self::addDefine('SGPB_AD_BLOCK_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_AD_BLOCK_KEY', 'POPUP_AD_BLOCK');
	}
}

SGPBAdBlock::init();
