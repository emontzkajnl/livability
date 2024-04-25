<?php
class SGPBGeoTargetingConfig
{
	public static function addDefine($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public static function init()
	{
		self::addDefine('SGPB_GEO_TARGETING_PATH', WP_PLUGIN_DIR.'/'.SGPB_GEO_TARGETING_FOLDER_NAME.'/');
		self::addDefine('SGPB_GEO_TARGETING_PUBLIC_URL', plugins_url().'/'.SGPB_GEO_TARGETING_FOLDER_NAME.'/public/');
		self::addDefine('SGPB_GEO_TARGETING_DYNAMIC_CLASS_PATH', SGPB_GEO_TARGETING_FOLDER_NAME.'/com/classes/');
		self::addDefine('SGPB_GEO_TARGETING_COM_PATH', SGPB_GEO_TARGETING_PATH.'com/');
		self::addDefine('SG_POPUP_GEO_TARGETING_LIBS_PATH', SGPB_GEO_TARGETING_COM_PATH.'libs/');
		self::addDefine('SGPB_GEO_TARGETING_PUBLIC_PATH', SGPB_GEO_TARGETING_PATH.'public/');
		self::addDefine('SGPB_GEO_TARGETING_VIEWS_PATH', SGPB_GEO_TARGETING_PUBLIC_PATH.'views/');
		self::addDefine('SGPB_GEO_TARGETING_CLASSES_PATH', SGPB_GEO_TARGETING_COM_PATH.'classes/');
		self::addDefine('SGPB_GEO_TARGETING_VERSION_DETECTION_PATH', SGPB_GEO_TARGETING_CLASSES_PATH.'_detection/');
		self::addDefine('SGPB_GEO_TARGETING_EXTENSION_FILE_NAME', 'PopupBuilderGeoTargetingExtension.php');
		self::addDefine('SGPB_GEO_TARGETING_EXTENSION_CLASS_NAME', 'PopupBuilderGeoTargetingExtension');
		self::addDefine('SGPB_GEO_TARGETING_HELPERS', SGPB_GEO_TARGETING_COM_PATH.'helpers/');
		self::addDefine('SGPB_GEO_TARGETING_EVENT_KEY', 'groups_countries');
		self::addDefine('SGPB_GEO_TARGETING_EVENT_CITIES_KEY', 'groups_cities');
		self::addDefine('SGPB_GEO_TARGETING_EVENT_REGIONS_KEY', 'groups_regions');
		self::addDefine('SGPB_GEO_TARGETING_CERTAIN_IP_KEY', 'certain_ip');
		self::addDefine('SGPB_CITIES_TABLE_NAME', 'sgpb_cities');
		self::addDefine('SGPB_POPUP_TYPE_GEO_TARGETING_DISPLAY_NAME', 'Geo Targeting');
		self::addDefine('SG_POPUP_POST_TYPE', 'popupbuilder');
		self::addDefine('SGPB_POPUP_TYPE_GEO_TARGETING', 'geoTargeting');
		self::addDefine('SG_POPUP_TEXT_DOMAIN', 'popupBuilder');
		self::addDefine('SGPB_GEO_TARGETING_URL', plugins_url().'/'.SGPB_GEO_TARGETING_FOLDER_NAME.'/');
		self::addDefine('SGPB_POPUP_ADVANCED_TARGETING_EXTENSION_KEY', 'popupbuilder-advanced-targeting/PopupBuilderAdvancedTargeting.php');
		self::addDefine('SGPB_POPUP_GEO_TARGETING_EXTENSION_KEY', 'popupbuilder-geo-targeting/PopupBuilderGeoTargeting.php');
		self::addDefine('SGPB_GEO_TAREGETING_VERSION', '2.6');
		self::addDefine('SGPB_CITIES_TABLE_UPDATED', 'sgpbCitiesUpdated');
		self::addDefine('SGPB_CITIES_TABLE_UPDATED_V1', 'sgpbCitiesUpdated_v1');
		self::addDefine('SGPB_CITIES_TABLE_UPDATED_V2', 'sgpbCitiesUpdated_v2');

		self::addDefine('SGPB_GEO_TARGETING_JS_URL', SGPB_GEO_TARGETING_PUBLIC_URL.'js/');
		self::addDefine('SGPB_GEO_TARGETING_CSS_URL', SGPB_GEO_TARGETING_PUBLIC_URL.'css/');
		self::addDefine('SGPB_GEO_TARGETING_TEXT_DOMAIN', SGPB_GEO_TARGETING_FOLDER_NAME);
		self::addDefine('SGPB_GEO_TARGETING_PLUGIN_MAIN_FILE', 'PopupBuilderGeoTargeting.php');
		self::addDefine('SGPB_GEO_TARGETING_AVALIABLE_VERSION', 1);

		self::addDefine('SGPB_GEO_TARGETING_ACTION_KEY', 'geoTargeting');
		self::addDefine('SGPB_GEO_TARGETING_STORE_URL', 'https://popup-builder.com/');

		self::addDefine('SGPB_GEO_TARGETING_ITEM_ID', 106644);
		self::addDefine('SGPB_GEO_TARGETING_AUTHOR', 'Sygnoos');
		self::addDefine('SGPB_GEO_TARGETING_KEY', 'POPUP_GEO_TARGETING');
	}
}

SGPBGeoTargetingConfig::init();
