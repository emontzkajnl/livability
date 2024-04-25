<?php
/**
 * Plugin Name: Popup Builder Recent Sales
 * Plugin URI: https://popup-builder.com/
 * Description: Convert visitors with real-time social proof - recent sales extension. Recent sales extension will help you to increase your website conversion instantly.
 * Version:	2.1
 * Author: Sygnoos
 * Author URI: https://popup-builder.com/
 * License:
 */

if (!defined('WPINC')) {
	wp_die();
}

if (defined('SGPB_RECENT_SALES_CLASSES_PATH')) {
	_e('You already have Recent Sales extension. Please, remove this one', SGPB_RECENT_SALES_TEXT_DOMAIN);
	wp_die();
}

if (!defined('SGPB_RECENT_SALES_FILE_NAME')) {
	define('SGPB_RECENT_SALES_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_RECENT_SALES_FOLDER_NAME')) {
	define('SGPB_RECENT_SALES_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}
require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(SGPB_RECENT_SALES_CLASSES_PATH.'RecentSalesMain.php');
