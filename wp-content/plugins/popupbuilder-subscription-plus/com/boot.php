<?php
use sgpbsubscriptionplus\SubscriptionPlusAdminHelper;
require_once(dirname(__FILE__).'/helpers/AdminHelper.php');

$isSatisfy = SubscriptionPlusAdminHelper::isSatisfyParameters();
if (empty($isSatisfy['status'])) {
	echo $isSatisfy['message'];
	wp_die();
}
require_once(dirname(__FILE__).'/config/config.php');
// include form builder lib config
require_once(SGPB_SUBSCRIPTION_FORM_BUILDER.'config.php');
