<?php
/**
 * Function helpers.
 *
 * @package ALMTemplates
 */

/**
 * Plugin activation hook.
 *
 * @param bool $network_wide Network wide activation.
 * @return void
 */
function alm_templates_after_activation( $network_wide ) {
	$plugins = [
		'ajax-load-more-theme-repeaters/ajax-load-more-theme-repeaters.php',
		'ajax-load-more-repeaters-v2/ajax-load-more-repeaters-v2.php',
	];
	deactivate_plugins( $plugins ); // Deactivate plugins.

	if ( is_multisite() && $network_wide ) {
		global $wpdb;
		// Get all blogs in the network and create table for each.
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			alm_templates_create_table();
			restore_current_blog();
		}
	} else {
		alm_templates_create_table();
	}
}

/**
 * Create Table in WP DB.
 *
 * @return void
 */
function alm_templates_create_table() {
	if ( defined( 'ALM_DISABLE_REPEATER_TEMPLATES' ) && ALM_DISABLE_REPEATER_TEMPLATES ) {
		return;  // Exit if disabled, we don't want to create the table.
	}

	global $wpdb;
	$table_name = $wpdb->prefix . ALM_TEMPLATES_TABLE_NAME;

	// Create table, if it doesn't already exist.
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) { // phpcs:ignore
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			repeaterDefault longtext NOT NULL,
			alias TEXT NOT NULL,
			UNIQUE KEY id (id)
		);";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}

/**
 * Table exists checker function.
 *
 * @return void
 */
function alm_templates_check_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . ALM_TEMPLATES_TABLE_NAME;
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) { // phpcs:ignore
		alm_templates_create_table();
	}
}

/**
 * ALM Templates upgrade routine.
 *
 * @return void
 */
function alm_templates_upgrade_routine() {
	// Delete `pluginVersion` column from `alm_unlimited` table.
	if ( get_option( 'alm_templates_drop_pluginVersion' ) !== 'true' ) {
		global $wpdb;
		$table_name = $wpdb->prefix . ALM_TEMPLATES_TABLE_NAME;
		$wpdb->hide_errors(); // Suppress errors.
		$wpdb->query( "ALTER TABLE $table_name DROP pluginVersion" );
		update_option( 'alm_templates_drop_pluginVersion', 'true' );

		// Delete alm_unlimited_version option.
		delete_option( 'alm_unlimited_version' );
	}
}
