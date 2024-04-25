<?php
/**
 * Plugin Name: Popup Builder Yes/No buttons
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Yes/No buttons extension into Popup Builder.
 * Version: 3.2
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License: Commercial Use License
 */

// If this file is called directly, abort.

if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_RESTRICTION_CLASSES_PATH')) {
	_e('You already have Popup Builder Yes/No buttons extension. Please, remove this one.', SGPB_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_RESTRICTION_FILE_NAME')) {
	define('SGPB_RESTRICTION_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_RESTRICTION_FOLDER_NAME')) {
	define('SGPB_RESTRICTION_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_RESTRICTION_CLASSES_PATH.'AgerestrictionMain.php');
