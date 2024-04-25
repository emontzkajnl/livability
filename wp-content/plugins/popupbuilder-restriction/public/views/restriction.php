<?php
	use sgpb\AdminHelper;
	$defaultData = ConfigDataHelper::defaultData();
?>
<div class="sgpb formItem">
	<div class="sgpb-wrapper sgpb-width-100 sgpb-restrictions">
			<div class="formItem">
				<label class="formItem__title">
					<?php _e('"Yes" button', SG_POPUP_TEXT_DOMAIN)  ?>
				</label>
			</div>

		<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-btn" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<input name="sgpb-restriction-yes-btn" id="sgpb-restriction-yes-btn" type="text" placeholder="<?php _e('e.g.: Yes', SG_POPUP_TEXT_DOMAIN) ;?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-yes-btn'))?>" required>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-btn-bg-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN) ;?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker" id="sgpb-restriction-yes-btn-bg-color" type="text" name="sgpb-restriction-yes-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-yes-btn-bg-color'))?>" />
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-btn-text-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Text color', SG_POPUP_TEXT_DOMAIN) ;?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker" id="sgpb-restriction-yes-btn-text-color" type="text" name="sgpb-restriction-yes-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-yes-btn-text-color'))?>" />
				</div>
			</div>
			<!-- border settings start -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-btn-border-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN) ;?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker" id="sgpb-restriction-yes-btn-border-color" type="text" name="sgpb-restriction-yes-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-yes-btn-border-color'))?>" />
				</div>
			</div>
			<div class="formItem subFormItem">
				<label class="subFormItem__title sgpb-margin-right-10" for="sgpb-custom-yes-btn-fixed-size">
					<?php _e('Fixed sizes', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-custom-yes-btn-fixed-size" name="sgpb-custom-yes-btn-fixed-size"
						<?php echo $popupTypeObj->getOptionValue('sgpb-custom-yes-btn-fixed-size'); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-custom-yes-btn-fixed-size">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
			</div>
			<!-- width settings start -->
			<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-x-20">
				<div class="formItem subFormItem">
					<label for="sgpb-restriction-yes-button-width" class="sgpb-margin-right-10">
						<?php _e('Width', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-restriction-yes-button-width" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-yes-button-width" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-yes-button-width'); ?>">
					<div class="sgpb-col-no-padding">
						<span class="sgpb-restriction-unit">px</span>
					</div>
				</div>
				<!-- width settings end -->
				<!-- height settings start -->
				<div class="formItem subFormItem">
					<label for="sgpb-restriction-yes-button-height" class="sgpb-margin-right-10">
						<?php _e('Height', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-restriction-yes-button-height" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-yes-button-height" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-yes-button-height'); ?>">
					<div class="sgpb-col-no-padding">
						<span class="sgpb-restriction-unit">px</span>
					</div>
				</div>
			</div>
			<!-- height settings end -->
			<!-- button padding settings start -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-button-padding" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Padding', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-yes-button-padding" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-yes-button-padding" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-yes-button-padding'); ?>">
				<div class="sgpb-col-no-padding">
					<span class="sgpb-restriction-unit">px</span>
				</div>
			</div>
			<!-- button padding settings end -->
			<!-- font size settings start -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-button-font-size" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Font size', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-yes-button-font-size" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-yes-button-font-size" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-yes-button-font-size'); ?>">
				<div class="sgpb-col-no-padding">
					<span class="sgpb-restriction-unit">px</span>
				</div>
			</div>
			<!-- font size settings end -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-btn-border-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-yes-btn-border-width" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-yes-btn-border-width" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-yes-btn-border-width'); ?>">
				<div class="sgpb-col-no-padding">
					<span class="sgpb-restriction-unit">px</span>
				</div>
			</div>
			<!-- border settings end -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-btn-radius" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Radius', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-yes-btn-radius" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-yes-btn-radius" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-yes-btn-radius'); ?>">
				<div class="sgpb-col-no-padding">
					<?php echo AdminHelper::createSelectBox($defaultData['pxPercent'], $popupTypeObj->getOptionValue('sgpb-restriction-yes-btn-radius-type'), array('name' => 'sgpb-restriction-yes-btn-radius-type', 'class'=>'js-sg-select2')); ?>
				</div>
			</div>
			<div class="formItem subFormItem">
				<label class="subFormItem__title sgpb-margin-right-10" for="save-choice">
					<?php _e('Save choice', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="save-choice" name="sgpb-restriction-save-choice"
						<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-save-choice'); ?>>
					<label class="sgpb-onOffSwitch__label" for="save-choice">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-x-20">
				<div class="formItem subFormItem">
					<label for="sgpb-restriction-yes-expiration-time" class="sgpb-margin-right-10">
						<?php _e('Expiration time', SG_POPUP_TEXT_DOMAIN)  ?>:
					</label>
					<input name="sgpb-restriction-yes-expiration-time" id="sgpb-restriction-yes-expiration-time" type="number" min="0" class="sgpb-margin-right-10" required value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-yes-expiration-time'))?>">
					<span class="question-mark sgpb-info-icon">B</span>
					<div class="sgpb-info-wrapper">
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e('Estimate the count of the days after which the popup will be shown to the same user after they confirm with "Yes" button.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
					</div>
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-restriction-cookie-level" class="sgpb-margin-right-10">
						<?php _e('Page level cookie saving', SG_POPUP_TEXT_DOMAIN)  ?>:
					</label>
					<input type="checkbox" id="sgpb-restriction-cookie-level" class="sgpb-margin-right-10" name="sgpb-restriction-cookie-level" <?php echo $popupTypeObj->getOptionValue('sgpb-restriction-cookie-level'); ?>>
					<span class="question-mark sgpb-info-icon">B</span>
					<div class="sgpb-info-wrapper">
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" >
							<?php _e('If this option is checked the popup confirmation date will refer to the current page. Otherwise the popup will be shown for specific times on each page selected.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
					</div>
				</div>
			</div>

		</div>


		<div class="formItem">
			<label class="formItem__title">
				<?php _e('"No" button', SG_POPUP_TEXT_DOMAIN)  ?>
			</label>
		</div>
		<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-btn" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<input name="sgpb-restriction-no-btn" id="sgpb-restriction-no-btn" type="text" placeholder="<?php _e('e.g.: No', SG_POPUP_TEXT_DOMAIN) ;?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-no-btn'))?>" required>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-btn-bg-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN) ;?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker" id="sgpb-restriction-no-btn-bg-color" type="text" name="sgpb-restriction-no-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-no-btn-bg-color'))?>" />
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-btn-text-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Text color', SG_POPUP_TEXT_DOMAIN) ;?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker" id="sgpb-restriction-no-btn-text-color" type="text" name="sgpb-restriction-no-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-no-btn-text-color'))?>" />
				</div>
			</div>
			<!-- border settings start -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-btn-border-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN) ;?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker" id="sgpb-restriction-no-btn-border-color" type="text" name="sgpb-restriction-no-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-no-btn-border-color'))?>" />
				</div>
			</div>
			<div class="formItem subFormItem">
				<label class="subFormItem__title sgpb-margin-right-10" for="sgpb-custom-no-btn-fixed-size">
					<?php _e('Fixed sizes', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-custom-no-btn-fixed-size" name="sgpb-custom-no-btn-fixed-size"
						<?php echo $popupTypeObj->getOptionValue('sgpb-custom-no-btn-fixed-size'); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-custom-no-btn-fixed-size">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-x-20">
				<!-- width settings start -->
				<div class="formItem subFormItem">
					<label for="sgpb-restriction-no-button-width" class="sgpb-margin-right-10">
						<?php _e('Width', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-restriction-no-button-width" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-no-button-width" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-no-button-width'); ?>">
					<div class="col-md-1 sgpb-col-no-padding">
						<span class="sgpb-restriction-unit">px</span>
					</div>
				</div>
				<!-- width settings end -->
				<!-- height settings start -->
				<div class="formItem subFormItem">
					<label for="sgpb-restriction-no-button-height" class="sgpb-margin-right-10">
						<?php _e('Height', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-restriction-no-button-height" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-no-button-height" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-no-button-height'); ?>">
					<div class="col-md-1 sgpb-col-no-padding">
						<span class="sgpb-restriction-unit">px</span>
					</div>
				</div>
			</div>
			<!-- height settings end -->
			<!-- button padding settings start -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-button-padding" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Padding', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-no-button-padding" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-no-button-padding" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-no-button-padding'); ?>">
				<div class="col-md-1 sgpb-col-no-padding">
					<span class="sgpb-restriction-unit">px</span>
				</div>
			</div>
			<!-- button padding settings end -->
			<!-- font size settings start -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-button-font-size" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Font size', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-no-button-font-size" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-no-button-font-size" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-no-button-font-size'); ?>">
				<div class="col-md-1 sgpb-col-no-padding">
					<span class="sgpb-restriction-unit">px</span>
				</div>
			</div>
			<!-- font size settings end -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-btn-border-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-no-btn-border-width" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-no-btn-border-width" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-no-btn-border-width'); ?>">
				<div class="col-md-1 sgpb-col-no-padding">
					<span class="sgpb-restriction-unit">px</span>
				</div>
			</div>
			<!-- border settings end -->
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-btn-radius" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Radius', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<input id="sgpb-restriction-no-btn-radius" class="sgpb-margin-right-10" type="number" min="0" name="sgpb-restriction-no-btn-radius" value="<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-no-btn-radius'); ?>">
				<div class="sgpb-col-no-padding">
					<?php echo AdminHelper::createSelectBox($defaultData['pxPercent'], $popupTypeObj->getOptionValue('sgpb-restriction-no-btn-radius-type'), array('name' => 'sgpb-restriction-no-btn-radius-type', 'class'=>'js-sg-select2')); ?>
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-no-url" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Restriction URL', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<input name="sgpb-restriction-no-url" id="sgpb-restriction-no-url" type="url" class="sgpb-margin-right-10" placeholder="https://www.example.com" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-restriction-no-url'))?>" required>
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Add the URL to which the users will be redirected to, after selecting the "No" button.', SG_POPUP_TEXT_DOMAIN);?>
					</span>
				</div>
			</div>
		</div>

		<div class="formItem">
			<label for="sgpb-restriction-to-bottom" class="formItem__title sgpb-margin-right-10">
				<?php _e('Push to bottom', SG_POPUP_TEXT_DOMAIN)  ?>:
			</label>
			<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
				<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-restriction-to-bottom" name="sgpb-restriction-to-bottom"
					<?php echo $popupTypeObj->getOptionValue('sgpb-restriction-to-bottom'); ?>>
				<label class="sgpb-onOffSwitch__label" for="sgpb-restriction-to-bottom">
					<span class="sgpb-onOffSwitch-inner"></span>
					<span class="sgpb-onOffSwitch-switch"></span>
				</label>
			</div>
			<span class="question-mark sgpb-info-icon">B</span>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text">
					<?php esc_html_e('This option will work correctly if popup’s dimensions are set in custom mode', SG_POPUP_TEXT_DOMAIN);?>
				</span>
			</div>
		</div>
	</div>
</div>

<style>
	.sgpb .formItem .subFormItem .wp-picker-container button {
		margin: 0 6px;
	}
	.post-type-popupbuilder .sgpb-restrictions .select2 {
		min-width: auto;
	}
</style>
