<?php
/**
 * Plugin Name: Popup Builder Advanced Targeting
 * Plugin URI: https://popup-builder.com/
 * Description: Integrate Advanced Targeting extension into Popup Builder.
 * Version: 3.1
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License: Commercial Use License
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_ADVANCED_TARGETING_CLASSES_PATH')) {
	_e('You already have Popup Builder Advanced Targeting extension. Please, remove this one.', SGPB_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_ADVANCED_TARGETING_FILE_NAME')) {
	define('SGPB_ADVANCED_TARGETING_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_ADVANCED_TARGETING_FOLDER_NAME')) {
	define('SGPB_ADVANCED_TARGETING_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_ADVANCED_TARGETING_CLASSES_PATH.'AdvancedTargetingMain.php');
