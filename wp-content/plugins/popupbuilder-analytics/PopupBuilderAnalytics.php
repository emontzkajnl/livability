<?php
/**
 * Plugin Name: Popup Builder Analytics
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Analytics extension into Popup Builder.
 * Version:	4.4
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License:
 */

/*If this file is called directly, abort.*/
if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_ANALYTICS_CLASSES_PATH')) {
	_e('You already have Analytics extension. Please, remove this one.', SGPB_EXIT_INTENT_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_ANALYTICS_FILE_NAME')) {
	define('SGPB_ANALYTICS_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_ANALYTICS_FOLDER_NAME')) {
	define('SGPB_ANALYTICS_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_ANALYTICS_CLASSES_PATH.'AnalyticsMain.php');
