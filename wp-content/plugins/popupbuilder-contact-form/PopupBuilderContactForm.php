<?php
/**
 * Plugin Name: Popup Builder Contact Form
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Contact Form extension into Popup Builder.
 * Version: 3.3
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License: Commercial Use License
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_CONTACT_FORM_CLASSES_PATH')) {
	_e('You already have Popup Builder Contact form extension. Please, remove this one.', SGPB_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_CONTACT_FORM_FILE_NAME')) {
	define('SGPB_CONTACT_FORM_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_CONTACT_FORM_FOLDER_NAME')) {
	define('SGPB_CONTACT_FORM_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_CONTACT_FORM_CLASSES_PATH.'ContactFormMain.php');
