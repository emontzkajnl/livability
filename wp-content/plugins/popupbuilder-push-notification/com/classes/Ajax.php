<?php
namespace sgpbpush;
require_once(SGPB_PUSH_NOTIFICATION_LIBS_PATH.'/vendor/autoload.php');
use Minishlink\WebPush\WebPush;
use sgpbpush\AdminHelper as PushNotificationAdminHelper;

class Ajax {
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        add_action('wp_ajax_sgpb_notification_delete', array($this, 'deleteNotifications'));
        add_action('wp_ajax_sgpb_campaigns_delete', array($this, 'deleteCampaigns'));
        add_action('wp_ajax_sgpb_notification_register', array($this, 'notificationRegister'));
        add_action('wp_ajax_nopriv_sgpb_notification_register', array($this, 'notificationRegister'));

        add_action('wp_ajax_sgpb_notification_click', array($this, 'notificationClick'));
        add_action('wp_ajax_nopriv_sgpb_notification_click', array($this, 'notificationClick'));

        add_action('wp_ajax_sgpb_notification_delivered', array($this, 'notificationDelivered'));
        add_action('wp_ajax_nopriv_sgpb_notification_delivered', array($this, 'notificationDelivered'));

        add_action('wp_ajax_sgpb_send_notification', array($this, 'sgpbSendNotification'));
    }

    public function notificationClick()
    {
        wp_verify_nonce($_POST['nonce'], 'nonce');
        global $wpdb;

        $campaignId = (int)$_POST['campaignId'];

        $sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME.' SET click = click + 1  WHERE id=%d', $campaignId);
        $wpdb->query($sql);

        echo SGPB_AJAX_STATUS_TRUE;
        wp_die();
    }

    public function notificationDelivered()
    {
        wp_verify_nonce($_POST['nonce'], 'nonce');
        global $wpdb;

        $campaignId = (int)$_POST['campaignId'];

        $sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME.' SET delivered = delivered + 1  WHERE id=%d', $campaignId);
        $wpdb->query($sql);

        echo SGPB_AJAX_STATUS_TRUE;
        wp_die();
    }

    public function sgpbSendNotification()
    {
        check_ajax_referer(SG_AJAX_NONCE, 'nonce');

        $popupId = (int)$_POST['popupId'];
        $title = $_POST['title'];
        $text = $_POST['text'];
        $icon = $_POST['icon'];
        $customLink = $_POST['customLink'];
        global $wpdb;

        $sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_TABLE_NAME.' WHERE popupId=%d', $popupId);
        $subscribers = $wpdb->get_results($sql);
        $allSubscribersCount = count($subscribers);

        $this->addNewCampaigns($allSubscribersCount, $title);
        $campaignId  =$this->getLastCampaignId();

        $auth = array(
            'VAPID' => array(
                'subject' => get_site_url(),
                'publicKey' => PushNotificationAdminHelper::getOption('sgpb-push-notification-public-key'),
                'privateKey' => PushNotificationAdminHelper::getOption('sgpb-push-notification-private-key') // in the real world, this would be in a secret file
            ),
        );
        $webPush = new WebPush($auth);
        foreach ($subscribers as $subscriber) {
            $sent = $webPush->sendNotification(
                $subscriber->endpoint,
                '{
                    "title" : "'.stripcslashes(($title)).'",
                    "msg" : "'.esc_html($text).'",
                    "icon" : "'.esc_html($icon).'",
                    "badge" : "'.esc_html($icon).'",
                    "campaignId" : "'.esc_html($campaignId).'",
                    "url" : "'.esc_html($customLink).'",
                    "nonce" : "'.wp_create_nonce(SG_AJAX_NONCE).'",
                    "ajaxUrl" : "'.admin_url('admin-ajax.php').'"
                }',
                str_replace(['_', '-'], ['/', '+'],$subscriber->p256dh),
                str_replace(['_', '-'], ['/', '+'],$subscriber->auth),
                true
            );
        }

        echo json_encode($sent);
        wp_die();
    }

    public function addNewCampaigns($allCount, $title)
    {
        global $wpdb;
        $cDate = date('Y-m-d');

        $sql = 'INSERT INTO '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME;
        $sql .= ' (cDate, campaignTitle, sent, click, delivered) VALUES (%s, %s, %d, %d, %d) ';
        $sql = $wpdb->prepare($sql, $cDate, $title, $allCount, 0, 0);
        $wpdb->query($sql);
    }

    public function getLastCampaignId()
    {
        global $wpdb;
        $lastRow = $wpdb->get_row('SELECT id from '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME.' ORDER BY ID DESC LIMIT 1');

        return $lastRow->id;
    }

    public function notificationRegister()
    {
        check_ajax_referer(SG_AJAX_NONCE, 'nonce');
        $postData = $_POST;
        global $wpdb;

        if (!empty($postData['type']) && $postData['type'] == 'subscribe') {
            $region = AdminHelper::getRegion();
            $browser = $postData['browserName'];
            $cDate = date('Y-m-d');
            $endpoint = $postData['endpoint'];
            $auth = $postData['token'];
            $p256dh = $postData['key'];
            $popupId = $postData['popupId'];
            $options = json_encode(array());

            $sql = 'INSERT INTO '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_TABLE_NAME;
            $sql .= ' (region, browser, cDate, endpoint, auth, p256dh, popupId, options) VALUES (%s, %s, %s, %s, %s, %s, %d, %s) ';
            $sql = $wpdb->prepare($sql, $region, $browser, $cDate, $endpoint, $auth, $p256dh, $popupId, $options);
            $res = $wpdb->query($sql);
        }

        echo $res;
        wp_die();
    }

    public function deleteNotifications()
    {
        global $wpdb;
        check_ajax_referer(SG_AJAX_NONCE, 'nonce');

        $notificationsId = array_map('sanitize_text_field', $_POST['notificationsId']);
        $whereCond = '(';

        foreach ($notificationsId as $id) {
            $whereCond .= $id.', ';
        }
        $whereCond = rtrim($whereCond, ', ');
        $whereCond .= ')';
        $wpdb->query('DELETE FROM '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_TABLE_NAME.' WHERE id in'.$whereCond);

        echo SGPB_AJAX_STATUS_TRUE;
        wp_die();
    }

    public function deleteCampaigns()
    {
        global $wpdb;
        check_ajax_referer(SG_AJAX_NONCE, 'nonce');

        $notificationsId = array_map('sanitize_text_field', $_POST['campaignsId']);
        $whereCond = '(';

        foreach ($notificationsId as $id) {
            $whereCond .= $id.', ';
        }
        $whereCond = rtrim($whereCond, ', ');
        $whereCond .= ')';
        $wpdb->query('DELETE FROM '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME.' WHERE id in'.$whereCond);

        echo SGPB_AJAX_STATUS_TRUE;
        wp_die();
    }
}

new Ajax();
