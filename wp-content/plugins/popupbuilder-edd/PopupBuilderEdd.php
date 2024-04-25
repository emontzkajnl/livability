<?php
/**
 * Plugin Name: Popup Builder Easy Digital Downloads
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Easy Digital Downloads extension into Popup Builder.
 * Version: 2.1
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License:
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_EDD_CLASSES_PATH')) {
	_e('You already have Easy Digital Downloads extension. Please, remove this one', SGPB_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_EDD_FILE_NAME')) {
	define('SGPB_EDD_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_EDD_FOLDER_NAME')) {
	define('SGPB_EDD_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_EDD_CLASSES_PATH.'EddMain.php');
