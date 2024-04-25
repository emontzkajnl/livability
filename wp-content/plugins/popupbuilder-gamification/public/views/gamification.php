<?php

use sgpbgamification\AdminHelper;
use sgpbgamification\Tabs;

$tyneMceArgs = AdminHelper::getTyneMceArgs();
$settingsTab = AdminHelper::getGamificationSettingsTabConfig();
?>
<div class="sgpb">
	<div class="sgpb-wrapper formItem">
		<div class="sgpb-tabs-content-wrapper sgpb-width-70">
			<?php
			$tabName = 'contents';
			if ( ! empty( $_GET['sgpbPageKeyTab'] ) ) {
				$tabName = $_GET['sgpbPageKeyTab'];
			} else if ( ! empty( $_COOKIE['SGPBGamificationActiveTab'] ) ) {
				$tabName = $_COOKIE['SGPBGamificationActiveTab'];
			}
			$tabs = Tabs::create( $settingsTab, $tabName, $popupTypeObj );
			echo $tabs->render();
			?>
		</div>
	</div>
</div>
