<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}
use sgpbrandom\Installer;

if (!defined('SGPB_RANDOM_FILE_NAME')) {
	define('SGPB_RANDOM_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_RANDOM_FOLDER_NAME')) {
	define('SGPB_RANDOM_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(dirname(__FILE__).'/com/config/config.php');
require_once(SGPB_RANDOM_CLASSES_PATH.'Installer.php');

Installer::uninstall();