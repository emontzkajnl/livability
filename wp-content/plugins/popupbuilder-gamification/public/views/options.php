<?php

use sgpbgamification\AdminHelper as GamificationAdminHelper;
use sgpb\AdminHelper;

$winChance = GamificationAdminHelper::winningChance();
?>
<div class="formItem">
	<label class="formItem__title sgpb-margin-right-10">
		<?php _e( 'Winning chance', SG_POPUP_TEXT_DOMAIN ); ?>:
	</label>
	<?php echo AdminHelper::createSelectBox( $winChance, esc_html( $popupTypeObj->getOptionValue( 'sgpb-gamification-win-chance' ) ), array( 'name'  => 'sgpb-gamification-win-chance',
	                                                                                                                                         'class' => 'js-sg-select2'
	) ); ?>
</div>

<div class="formItem">
	<label class="formItem__title sgpb-margin-right-10">
		<?php _e( 'Hide for already played users', SG_POPUP_TEXT_DOMAIN ); ?>:
	</label>
	<input type="checkbox" id="sgpb-check-cookie"
	       name="sgpb-gamification-already-subscribed" <?php echo $popupTypeObj->getOptionValue( 'sgpb-gamification-already-subscribed' ); ?>>
</div>

<div class="formItem">
	<label class="formItem__title sgpb-margin-right-10">
		<?php _e( 'Start game by button', SG_POPUP_TEXT_DOMAIN ); ?>:
	</label>
	<input type="checkbox" id="sgpb-hide-form"
	       name="sgpb-hide-form" <?php echo $popupTypeObj->getOptionValue( 'sgpb-hide-form' ); ?>>
</div>
