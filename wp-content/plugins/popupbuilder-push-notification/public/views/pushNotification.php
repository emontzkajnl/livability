<?php
	use sgpb\AdminHelper;
	$defaultData = ConfigDataHelper::defaultData();
?>
<div class="sgpb">
	<div class="sgpb-wrapper formItem sgpb-push-notification-settings">
		<div class="sgpb-width-100">
			<div class="formItem">
				<label class="formItem__title">
					<?php _e('"Allow" button', SG_POPUP_TEXT_DOMAIN)  ?>
				</label>
			</div>
			<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-yes-btn" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
					</label>
					<input name="sgpb-push-notification-yes-btn" id="sgpb-push-notification-yes-btn" type="text" placeholder="<?php _e('e.g.: Yes', SG_POPUP_TEXT_DOMAIN) ;?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-yes-btn'))?>" required>
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-yes-btn-bg-color" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button background color', SG_POPUP_TEXT_DOMAIN) ;?>:
					</label>
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker" id="sgpb-push-notification-yes-btn-bg-color" type="text" name="sgpb-push-notification-yes-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-yes-btn-bg-color'))?>" />
					</div>
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-yes-btn-text-color" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button text color', SG_POPUP_TEXT_DOMAIN) ;?>:
					</label>
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker" id="sgpb-push-notification-yes-btn-text-color" type="text" name="sgpb-push-notification-yes-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-yes-btn-text-color'))?>" />
					</div>
				</div>
				<!-- border settings start -->
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-yes-btn-border-color" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button border color', SG_POPUP_TEXT_DOMAIN) ;?>:
					</label>
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker" id="sgpb-push-notification-yes-btn-border-color" type="text" name="sgpb-push-notification-yes-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-yes-btn-border-color'))?>" />
					</div>
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-yes-btn-border-width" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button border width', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-push-notification-yes-btn-border-width" type="number" min="0" name="sgpb-push-notification-yes-btn-border-width" value="<?php echo $popupTypeObj->getOptionValue('sgpb-push-notification-yes-btn-border-width'); ?>">
					<span class="sgpb-push-notification-unit">px</span>
				</div>
				<!-- border settings end -->
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-yes-btn-radius" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button radius', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-push-notification-yes-btn-radius" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-push-notification-yes-btn-radius" value="<?php echo $popupTypeObj->getOptionValue('sgpb-push-notification-yes-btn-radius'); ?>">
					<?php echo AdminHelper::createSelectBox($defaultData['pxPercent'], $popupTypeObj->getOptionValue('sgpb-push-notification-yes-btn-radius-type'), array('name' => 'sgpb-push-notification-yes-btn-radius-type', 'class'=>'js-sg-select2')); ?>
				</div>
			</div>

			<div class="formItem">
				<label class="formItem__title">
					<?php _e('"Disallow" button', SG_POPUP_TEXT_DOMAIN)  ?>
				</label>
			</div>

			<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-no-btn" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
					</label>
					<input name="sgpb-push-notification-no-btn" id="sgpb-push-notification-no-btn" type="text" placeholder="<?php _e('e.g.: No', SG_POPUP_TEXT_DOMAIN) ;?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-no-btn'))?>" required>
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-no-btn-bg-color" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button background color', SG_POPUP_TEXT_DOMAIN) ;?>:
					</label>
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker" id="sgpb-push-notification-no-btn-bg-color" type="text" name="sgpb-push-notification-no-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-no-btn-bg-color'))?>" />
					</div>
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-no-btn-text-color" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button text color', SG_POPUP_TEXT_DOMAIN) ;?>:
					</label>
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker" id="sgpb-push-notification-no-btn-text-color" type="text" name="sgpb-push-notification-no-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-no-btn-text-color'))?>" />
					</div>
				</div>
				<!-- border settings start -->
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-no-btn-border-color" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button border color', SG_POPUP_TEXT_DOMAIN) ;?>:
					</label>
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker" id="sgpb-push-notification-no-btn-border-color" type="text" name="sgpb-push-notification-no-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-no-btn-border-color'))?>" />
					</div>
				</div>
				<div class="formItem subFormItem">
					<label class="subFormItem__title sgpb-margin-right-10" for="sgpb-push-notification-save-choice">
						<?php _e('Save choice', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
						<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-push-notification-save-choice" name="sgpb-push-notification-save-choice"
							<?php echo $popupTypeObj->getOptionValue('sgpb-push-notification-save-choice'); ?>>
						<label class="sgpb-onOffSwitch__label" for="sgpb-push-notification-save-choice">
							<span class="sgpb-onOffSwitch-inner"></span>
							<span class="sgpb-onOffSwitch-switch"></span>
						</label>
					</div>
				</div>
				<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-20">
					<div class="formItem subFormItem">
						<label for="sgpb-push-notification-disallow-expiration-time" class="sgpb-margin-right-10">
							<?php _e('Expiration time', SG_POPUP_TEXT_DOMAIN)  ?>:
						</label>
						<input name="sgpb-push-notification-disallow-expiration-time" id="sgpb-push-notification-disallow-expiration-time" type="number" min="0" required value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-disallow-expiration-time'))?>">
						<span class="question-mark sgpb-info-icon">B</span>
						<div class="sgpb-info-wrapper">
							<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e('Estimate the count of the days after which the popup will be shown to the same user after they confirm with "Disallow" button.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
						</div>
					</div>
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-no-btn-border-width" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button border width', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-push-notification-no-btn-border-width" type="number" min="0" name="sgpb-push-notification-no-btn-border-width" value="<?php echo $popupTypeObj->getOptionValue('sgpb-push-notification-no-btn-border-width'); ?>">
					<span class="sgpb-push-notification-unit">px</span>
				</div>
				<!-- border settings end -->
				<div class="formItem subFormItem">
					<label for="sgpb-push-notification-no-btn-radius" class="subFormItem__title sgpb-margin-right-10">
						<?php _e('Button radius', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-push-notification-no-btn-radius" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-push-notification-no-btn-radius" value="<?php echo $popupTypeObj->getOptionValue('sgpb-push-notification-no-btn-radius'); ?>">
					<?php echo AdminHelper::createSelectBox($defaultData['pxPercent'], $popupTypeObj->getOptionValue('sgpb-push-notification-no-btn-radius-type'), array('name' => 'sgpb-push-notification-no-btn-radius-type', 'class'=>'js-sg-select2')); ?>
				</div>
			</div>
			<!--
			for the future
			<div class="row form-group">
				<label for="sgpb-push-notification-no-url" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Restriction URL', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<div class="col-md-6">
					<input name="sgpb-push-notification-no-url" id="sgpb-push-notification-no-url" type="url" class="sgpb-full-width form-control" placeholder="https://www.example.com" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-push-notification-no-url'))?>" required>
				</div>
				<div class="col-md-1 sgpb-info-wrapper">
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Add the URL to which the users will be redirected to, after selecting the "No" button.', SG_POPUP_TEXT_DOMAIN);?>
					</span>
				</div>
			</div>
			-->
			<div class="formItem">
				<label for="sgpb-push-notification-to-bottom" class="formItem__title sgpb-margin-right-10">
					<?php _e('Push to bottom', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox" id="sgpb-push-notification-to-bottom" name="sgpb-push-notification-to-bottom"
						<?php echo $popupTypeObj->getOptionValue('sgpb-push-notification-to-bottom'); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-push-notification-to-bottom">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
			</div>
		</div>
	</div>
</div>
