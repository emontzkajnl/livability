<?php

use sgpbgamification\AdminHelper;

$tyneMceArgs = AdminHelper::getTyneMceArgs();
?>
<div class="sgpb-margin-y-10 sgpb-gamification-contents">
	<label class="formItem__title sgpb-margin-bottom-10 sgpb-display-block">
		<?php _e( 'Main screen message', SG_POPUP_TEXT_DOMAIN ); ?>:
	</label>
	<?php
	$editorId = 'sgpb-gamification-start-text';
	$content  = $popupTypeObj->getOptionValue( $editorId );
	wp_editor( stripslashes( $content ), $editorId, $tyneMceArgs );
	?>
</div>
<div class="sgpb-margin-y-10 sgpb-gamification-contents">
	<label class="formItem__title sgpb-margin-bottom-10 sgpb-display-block">
		<?php _e( 'Play screen message', SG_POPUP_TEXT_DOMAIN ); ?>:
	</label>
	<?php
	$editorId = 'sgpb-gamification-play-text';
	$content  = $popupTypeObj->getOptionValue( $editorId );
	wp_editor( $content, $editorId, $tyneMceArgs );
	?>
</div>
<div class="sgpb-margin-y-10 sgpb-gamification-contents">
	<label class="formItem__title sgpb-margin-bottom-10 sgpb-display-block">
		<?php _e( 'Win screen message', SG_POPUP_TEXT_DOMAIN ); ?>:
	</label>
	<?php
	$editorId = 'sgpb-gamification-win-text';
	$content  = $popupTypeObj->getOptionValue( $editorId );
	wp_editor( $content, $editorId, $tyneMceArgs );
	?>
</div>
<div class="sgpb-margin-y-10 sgpb-gamification-contents">
	<label class="formItem__title sgpb-margin-bottom-10 sgpb-display-block">
		<?php _e( 'Lose screen message', SG_POPUP_TEXT_DOMAIN ); ?>:
	</label>
	<?php
	$editorId = 'sgpb-gamification-lose-text';
	$content  = $popupTypeObj->getOptionValue( $editorId );
	wp_editor( $content, $editorId, $tyneMceArgs );
	?>
</div>
