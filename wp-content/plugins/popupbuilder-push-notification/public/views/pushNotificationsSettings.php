<?php
use sgpbpush\Tabs;
use sgpbpush\SgpbPopupVersionDetectionPushNotification;
use sgpbpush\AdminHelper as NotificationAdminHelper;

$tabsSettings = NotificationAdminHelper::getPushNotificationsSettingsTabConfig();

require_once(SGPB_PUSH_NOTIFICATION_DATA_TABLE_PATH.'NotificationSubscribers.php');
require_once(SGPB_PUSH_NOTIFICATION_DATA_TABLE_PATH.'NotificationCampaigns.php');
$versionDetection = new SgpbPopupVersionDetectionPushNotification();
?>
<?php if ($versionDetection->canLoadView()) : ?>

	<div class="sgpb">
	    <div class="sgpb-wrapper sgpb-settings sgpb-push-notification">
	        <div id="post-body" class="sgpb-padding-20">
	            <h3 class="sgpb-header-h1 sgpb-margin-bottom-20">
	                <?php _e('Settings', SG_POPUP_TEXT_DOMAIN); ?>
	            </h3>
	            <div class="sgpb-tabs-content-wrapper">
	                <?php
	                    $tabName = '';
	                    if (!empty($_GET['sgpbPageKeyTab'])) {
	                        $tabName = $_GET['sgpbPageKeyTab'];
	                    }
	                    else if(!empty($_COOKIE['SGPBPushNotificationActiveTab'])) {
	                        $tabName = $_COOKIE['SGPBPushNotificationActiveTab'];
	                    }

	                    $tabs = Tabs::create($tabsSettings, $tabName);
	                    echo $tabs->render();
	                ?>
	                <div id="sgpb-tab-content-wrapper-sendPush" class="sgpb-tab-content-wrapper" <?php echo ($tabName == 'sendPush') ? 'style="display: block;"': ''; ?>>
	                    <?php require_once(SGPB_PUSH_NOTIFICATION_VIEWS_PATH.'sendPushNotification.php'); ?>
	                </div>
	                <div id="sgpb-tab-content-wrapper-subscribers" class="sgpb-tab-content-wrapper" <?php echo ($tabName == 'subscribers') ? 'style="display: block;"': ''; ?>>
	                    <?php require_once(SGPB_PUSH_NOTIFICATION_VIEWS_PATH.'subscribers.php'); ?>
	                </div>
	                <div id="sgpb-tab-content-wrapper-campaigns" class="sgpb-tab-content-wrapper" <?php echo ($tabName == 'campaigns') ? 'style="display: block;"': ''; ?>>
	                    <?php require_once(SGPB_PUSH_NOTIFICATION_VIEWS_PATH.'campaigns.php'); ?>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>


<?php endif ?>
