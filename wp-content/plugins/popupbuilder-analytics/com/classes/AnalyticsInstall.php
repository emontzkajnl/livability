<?php
namespace sgpban;
use sgpb\Functions as SGPBFunctions;

class AnalyticsInstall
{
	public static function createTables($blogId = '')
	{
		global $wpdb;
		$createTable = 'CREATE TABLE IF NOT EXISTS ';
		$dbEngine = SGPBFunctions::getDatabaseEngine();
		// to do: check InnoDB
		$sgpbAnalytics = $createTable.$wpdb->prefix.$blogId.SGPB_ANALYTICS_TABLE_NAME.' (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`event_id` int(11) NOT NULL,
			`target_id` int(11) NOT NULL,
			`target_type` int(11) NOT NULL,
			`cdate` DATE,
			`page_url` TEXT NOT NULL,
			`info` TEXT NOT NULL,
			PRIMARY KEY (id)
		) ENGINE='.$dbEngine.' DEFAULT CHARSET=utf8';

		$wpdb->query($sgpbAnalytics);
	}

	private function insertOldDataToNew($blogId = '')
	{
		$convertAnalytics = get_option('sgpbAnalyticsConvert');

		if ($convertAnalytics) {
			return false;
		}
		global $wpdb;
		$sql = 'SELECT `event_id`, `user_id`, `target_id`, `target_type`, `cdate`, `page_url`, `info` FROM '.$wpdb->prefix.$blogId.'sg_event_analytics';
		$results = $wpdb->get_results($sql, ARRAY_A);

		if (empty($results)) {
			return false;
		}

		foreach ($results as $result) {

			if (empty($result)) {
				continue;
			}
			$popupId = $result['target_id'];

			// for get new converted popup id
			if (function_exists('sgpb\sgpGetCorrectPopupId')) {
				$popupId = \sgpb\sgpGetCorrectPopupId($popupId);
			}
			$data = array(
				'event_id'=> $result['event_id'],
				'target_id' => $popupId,
				'target_type'=> $result['target_type'],
				'cdate'=> $result['cdate'],
				'page_url'=> $result['page_url'],
				'info'=> $result['info']
			);
			$formats = array('%d', '%d', '%d', '%s', '%s', '%s');
			$wpdb->insert($wpdb->prefix.$blogId.SGPB_ANALYTICS_TABLE_NAME, $data, $formats);
		}

		return true;
	}

	public static function install()
	{
		$obj = new self();
		// it's for backwards compatibility
		if ($obj->hasOldAnalytics()) {
			$obj->insertOldDataToNew();
		}
		$obj->createTables();

		if (is_multisite()) {
			global $wp_version;
			if ($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}

			foreach ($sites as $site) {
				if ($wp_version > '4.6.0') {
					$blogId = $site->blog_id.'_';
				}
				else {
					$blogId = $site['blog_id'].'_';
				}

				if ($blogId != 1) {
					$obj->createTables($blogId);
					// it's for backwards compatibility
					if ($obj->hasOldAnalytics()) {
						$obj->insertOldDataToNew($blogId);
					}
				}
			}
		}

		update_option('sgpbAnalyticsConvert', 1);
	}

	/**
	 * Check has user the old Analytic extension
	 *
	 * @return bool
	 */
	public function hasOldAnalytics()
	{
		$has = false;
		global $wpdb;
		$result = $wpdb->get_row('SHOW TABLES LIKE "'.$wpdb->prefix.'sg_event_analytics"');

		if (!empty($result)) {
			$has = true;
		}

		return $has;
	}

	public static function uninstallTables($blogId = '')
	{
		global $wpdb;

		$deleteAnalyticsTableName = $wpdb->prefix.$blogId.SGPB_ANALYTICS_TABLE_NAME;
		$deleteAnalyticsTableSql = 'DROP TABLE '.$deleteAnalyticsTableName;

		$wpdb->query($deleteAnalyticsTableSql);
	}

	public static function uninstall()
	{
		$obj = new self();
		$obj->uninstallTables();

		if (is_multisite()) {
			global $wp_version;
			if ($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}

			foreach ($sites as $site) {
				$blogId = $site['blog_id'].'_';

				if ($wp_version > '4.6.0') {
					$blogId = $site->blog_id.'_';
				}

				$obj->uninstallTables($blogId);
			}
		}
	}
}
