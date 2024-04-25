<?php
use sgpbcountdown\CountdownAdminHelper;
require_once(dirname(__FILE__).'/helpers/AdminHelper.php');

$isSatisfy = CountdownAdminHelper::isSatisfyParameters();
if (empty($isSatisfy['status'])) {
	echo $isSatisfy['message'];
	wp_die();
}

require_once(dirname(__FILE__).'/config/config.php');
