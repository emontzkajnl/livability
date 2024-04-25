<?php
namespace sgpbedd;


class AdminHelper
{
	public static function isEddExists()
	{
		return file_exists(WP_PLUGIN_DIR.'/'.SGPB_EDD_PLUGIN_KEY);
	}

	public static function isEddActive()
	{
		@include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return is_plugin_active(SGPB_EDD_PLUGIN_KEY);
	}
}
