<?php
namespace sgpbcontactform;
use sgpb\Functions as Functions;

class Installer
{
    public static function createTables($tables, $blogId = '')
    {
        global $wpdb;
        if (empty($tables)) {
            return false;
        }

        foreach ($tables as $table) {
            $createTable = 'CREATE TABLE IF NOT EXISTS ';
            $createTable .= $wpdb->prefix.$blogId;
            $createTable .= $table;
            $wpdb->query($createTable);
        }

        return true;
    }

    public static function install()
    {
        if (get_option('sgpb-dont-delete-data') === false) {
            return false;
        }
        $tables = self::getTablesSql();
        $filteredTables = apply_filters('sgpbContactFormTables', $tables);

        self::createTables($filteredTables);

        // get_current_blog_id() == 1 When plugin activated inside the child of multisite instance
        if (is_multisite() && get_current_blog_id() == 1) {
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
                // blog Id 1 for multisite main site
                if ($blogId != 1) {
                    self::createTables($filteredTables, $blogId);
                }
            }
        }

        return true;
    }

    private static function getTablesSql()
    {
        $tablesSql = array();
        $dbEngine = Functions::getDatabaseEngine();

        $tablesSql[] = SGPB_CONTACTED_SUBSCRIBERS_TABLE_NAME.' (
				`id` int(12) NOT NULL AUTO_INCREMENT,
				`email` varchar(255),
				`cDate` varchar(255),
				`submittedData` text,
				`popupId` int(12),
				PRIMARY KEY (id)
		) ENGINE='.$dbEngine.' DEFAULT CHARSET=utf8;';

        return $tablesSql;
    }
}
