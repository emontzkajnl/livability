<?php
	use sgpb\AdminHelper;
	use sgpb\MultipleChoiceButton;
	use sgpb\Functions;
	$popupId = $popupTypeObj->getId();

	if (!empty($_GET['post'])) {
		$popupId = (int)$_GET['post'];
	}
	$contactFormSubPopups = $popupTypeObj->getPopupsIdAndTitle();
	$successPopup = $popupTypeObj->getOptionValue('sgpb-contact-success-popup');

	// for old popups
	if (function_exists('sgpb\sgpGetCorrectPopupId')) {
		$successPopup = sgpb\sgpGetCorrectPopupId($successPopup);
	}
	$forceRtlClass = '';
	$forceRtl = $popupTypeObj->getOptionValue('sgpb-force-rtl');
	if ($forceRtl) {
		$forceRtlClass = ' sgpb-forms-preview-direction';
	}
?>
<div class="sgpb sgpb-wrapper">
	<div class="formItem">
		<label class="formItem__title sgpb-static-padding-top" for="sgpb-contact-to-email">
			<?php _e('Contact to', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input type="text" id="sgpb-contact-to-email" class="control-label sgpb-full-width-events" name="sgpb-contact-to-email" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-to-email'));?>">
	</div>
	<div class="formItem">
		<label class="formItem__title sgpb-static-padding-top" for="sgpb-contact-to-email-subject">
			<?php _e('Contact email subject', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input type="text" id="sgpb-contact-to-email-subject" class="control-label sgpb-full-width-events" name="sgpb-contact-to-email-subject" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-to-email-subject'));?>">
	</div>
	<?php require_once SGPB_CF_FORM_BUILDER_VIEWS.'formBuilder.php'; ?>
</div>
