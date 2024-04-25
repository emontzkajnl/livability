<?php
/**
 * Plugin Name: Popup Builder Random
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Random extension into Popup Builder.
 * Version: 2.2
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License: Commercial Use License
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_RANDOM_CLASSES_PATH')) {
	_e('You already have Popup Builder Random extension. Please, remove this one.', SGPB_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_RANDOM_FILE_NAME')) {
	define('SGPB_RANDOM_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_RANDOM_FOLDER_NAME')) {
	define('SGPB_RANDOM_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_RANDOM_CLASSES_PATH.'RandomMain.php');
