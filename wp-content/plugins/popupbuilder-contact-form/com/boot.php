<?php
use sgpbcontactform\AdminHelper;
require_once(dirname(__FILE__).'/helpers/AdminHelper.php');

$isSatisfy = AdminHelper::isSatisfyParameters();
if (empty($isSatisfy['status'])) {
	echo @$isSatisfy['message'];
	wp_die();
}
require_once(dirname(__FILE__).'/config/config.php');
require_once(SGPB_CF_FORM_BUILDER.'config.php');
