<?php
/**
 * Plugin Name: Popup Builder Scheduling
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Scheduling extension into Popup Builder.
 * Version:	3.1
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License: Commercial Use License
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_SCHEDULING_CLASSES_PATH')) {
	_e('You already have Scheduling extension. Please, remove this one.', SGPB_SCHEDULING_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_SCHEDULING_FILE_NAME')) {
	define('SGPB_SCHEDULING_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_SCHEDULING_FOLDER_NAME')) {
	define('SGPB_SCHEDULING_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_SCHEDULING_CLASSES_PATH.'SchedulingMain.php');
