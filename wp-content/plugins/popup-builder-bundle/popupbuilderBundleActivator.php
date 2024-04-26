<?php
/**
* Plugin Name: Popup Builder activator
* Plugin URI: https://popup-builder.com
* Description: The most complete popup plugin. Html, image, iframe, shortcode, video and many other popup types. Manage popup dimensions, effects, themes and more.
* Version: 1.0.0
* Author: Sygnoos
* Author URI: https://sygnoos.com
* License: GPLv2
*/
define('SGPB_ACTIVATOR_PLUGIN', plugin_basename(__FILE__));
use sgpbactivator\PopupExtensionActivator;

class SGPBActivatorPlugin
{

	public function __construct()
	{
		$this->init();
		add_action('wp_before_admin_bar_render', array($this, 'pluginActivated'), 10, 2);
	}

	private function init()
	{
		require_once (dirname(__FILE__).'/SgpbPopupExtensionActivator.php');
		register_activation_hook(SGPB_ACTIVATOR_PLUGIN, array($this, 'activate'));
	}

	public function activate()
	{
		$obj = new PopupExtensionActivator();
		$obj->install();
	}
	public function pluginActivated()
	{
		if (!get_option('sgpbActivateExtensions')) {
			$obj = new PopupExtensionActivator();
			$obj->activate();
		}
	}
}

new SGPBActivatorPlugin();