<?php
/**
 * Plugin Name: Popup Builder AWeber
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Aweber forms into Popup Builder.
 * Version:	3.2
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License:
 */
/*If this file is called directly, abort.*/
if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_AWEBER_CLASSES_PATH')) {
	_e('You already have AWeber extension. Please, remove this one', SGPB_AWEBER_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_AWEBER_FILE_NAME')) {
	define('SGPB_AWEBER_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_AWEBER_FOLDER_NAME')) {
	define('SGPB_AWEBER_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_AWEBER_CLASSES_PATH.'AWeberMain.php');
