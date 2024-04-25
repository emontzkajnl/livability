<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}
use sgpb\Actions;
if (!defined('SGPB_GEO_TARGETING_FILE_NAME')) {
	define('SGPB_GEO_TARGETING_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_GEO_TARGETING_FOLDER_NAME')) {
	define('SGPB_GEO_TARGETING_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(dirname(__FILE__).'/com/config/config.php');

class SGPBGeoTargetingUninstall
{
	public static function uninstallTables($blogId = '')
	{
		global $wpdb;
		$prefix = $wpdb->prefix.$blogId;
		$sql = 'DROP TABLE '.$prefix.SGPB_CITIES_TABLE_NAME;
		$wpdb->query($sql);
		delete_option('sgpb-popup-city');
		delete_option(SGPB_CITIES_TABLE_UPDATED);
		delete_option(SGPB_CITIES_TABLE_UPDATED_V1);
		delete_option(SGPB_CITIES_TABLE_UPDATED_V2);
	}

	public static function uninstall()
	{
		if (is_multisite()) {
			global $wp_version;
			if ($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}

			foreach ($sites as $site) {
				$blogId = $site['blog_id'].'_';

				if ($wp_version > '4.6.0') {
					$blogId = $site->blog_id.'_';
				}

				self::uninstallTables($blogId);
			}
		}
		self::uninstallTables();
	}
}

SGPBGeoTargetingUninstall::uninstall();
