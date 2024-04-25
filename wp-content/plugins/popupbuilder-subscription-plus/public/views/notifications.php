<div class="sgpb sgpb-wrapper">
	<div class="sgpb-subscription-plus">
		<div class="formItem">
			<label class="formItem__title sgpb-margin-right-10" for="sgpb-subs-enable-email-notifications">
				<?php _e('Enable email notifications', SG_POPUP_TEXT_DOMAIN); ?>:
			</label>
			<input id="sgpb-subs-enable-email-notifications" type="checkbox" class="js-checkbox-accordion" name="sgpb-subs-enable-email-notifications" <?php echo $popupTypeObj->getOptionValue('sgpb-subs-enable-email-notifications');?>>
		</div>
		<div class="sgpb-width-100">
			<div class="formItem">
				<label class="formItem__title sgpb-margin-right-10" for="sgpb-subs-notifications-email">
					<?php _e('Notify to', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input type="text" id="sgpb-subs-notifications-email" name="sgpb-subs-notifications-email" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-notifications-email'));?>">

			</div>
		</div>
		<?php require_once SGPB_SUBSCRIPTION_FORM_BUILDER_VIEWS.'formBuilder.php'; ?>
	</div>
</div>
