<?php
namespace sgpbpush;
use sgpb\Functions as Functions;
use Minishlink\WebPush\VAPID;

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
        if (!extension_loaded('gmp')) {
            _e('Popup Builder Push Notification plugin requires PHP GMP extension, please turn it ON from your PHP settings or contact to your hosting provider.', SG_POPUP_TEXT_DOMAIN);
            wp_die();
        }

        if (!AdminHelper::getOption('sgpb-push-notification-public-key')) {
            $vapid = new VAPID();
            $keys = $vapid->createVapidKeys();
            if (!empty($keys['publicKey']) && !empty($keys['privateKey'])) {
                AdminHelper::updateOption('sgpb-push-notification-public-key', $keys['publicKey']);
                AdminHelper::updateOption('sgpb-push-notification-private-key', $keys['privateKey']);
            }
        }

        $filteredTables = apply_filters('sgpbPushNotificationTables', $tables);

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
        $dbEngine = Functions::getDatabaseEngine();
        $tablesSql = array();

        $tablesSql[] = SGPB_PUSH_NOTIFICATION_TABLE_NAME.' (
                    `id` int(12) NOT NULL AUTO_INCREMENT,
                    `region` varchar(255),
                    `browser` varchar(255),
                    `cDate` varchar(255),
                    `endpoint` varchar(600),
                    `auth` varchar(255),
                    `p256dh` varchar(255),
                    `popupId` int(12),
                    `options` text,
                    PRIMARY KEY (id)
            ) ENGINE='.$dbEngine.' DEFAULT CHARSET=utf8;';

        $tablesSql[] = SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME.' (
                    `id` int(12) NOT NULL AUTO_INCREMENT,
                    `cDate` varchar(255),
                    `campaignTitle` varchar(255),
                    `sent` varchar(255),
                    `click` int(12),
                    `delivered` int(12),
                    PRIMARY KEY (id)
            ) ENGINE='.$dbEngine.' DEFAULT CHARSET=utf8;';

        return $tablesSql;
    }

    public static function alterTable()
    {
        global $wpdb;

        $sql = 'ALTER TABLE '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_TABLE_NAME.' MODIFY endpoint VARCHAR(600)';
        $wpdb->query($sql);
    }
}
