<?php
	require_once(SGPB_SUBSCRIPTION_FORM_BUILDER.'config.php');
	use \sgpbform\FormCreator;
	use sgpb\Functions;
	use sgpb\AdminHelper;
	use sgpb\MultipleChoiceButton;
	use sgpbsubscriptionplus\EmailIntegrations as EmailIntegrations;
	require_once(SGPB_FORM_CLASSES_FORMS.'FormCreator.php');
	$placeholderColor = $popupTypeObj->getOptionValue('sgpb-subs-text-placeholder-color');
	$defaultData = ConfigDataHelper::defaultData();
	$subscriptionSubPopups = $popupTypeObj->getPopupsIdAndTitle();
	$successPopup = $popupTypeObj->getOptionValue('sgpb-subs-success-popup');
?>

<div class="sgpb-form-admin-wrapper sg-hide">
	<?php
		$formFieldsJson = $popupTypeObj->getOptionValue('sgpb-subscription-fields-json');
		// free subscription Saved options
		$freeSavedOptions = $popupTypeObj->getOptionValue('sgpb-subs-fields');
		// for back work compatibility
		if (empty($formFieldsJson) && !empty($freeSavedOptions)) {
			$formFieldsJson = FormCreator::createSavedObjFromFreeOptions($freeSavedOptions, $popupTypeObj);
		}
		/* for the old silver/gold/platinum version backward compatibility */
		else if (empty($formFieldsJson) && empty($freeSavedOptions)) {
			$savedFormFields = $popupTypeObj->createFormFieldsData();
			$formFieldsJson = FormCreator::createSavedObjFromFreeOptions($savedFormFields, $popupTypeObj);
		}

		$savedFormFields = json_decode($formFieldsJson, true);

		$creationArgs = array('savedFormFields' => $savedFormFields);
		$formCreator = new FormCreator();
		$formCreator->setClassPath(SGPB_FORM_CLASSES_FORMS);
		$form = $formCreator->create('Subscription', $creationArgs);
	?>
</div>

<div class="sgpb-wrapper sgpb-fb-wrapper">
	<div class=" sgpb-bg-black__opacity-02 sgpb-display-flex sgpb-padding-10 sgpb-fb-wrapper-main">
		<div class="sgpb-width-50 sgpb-fb-main-options sgpb-padding-x-10">
			<div class="sgpb-tabs sgpb-margin-bottom-20">
				<span class="sgpb-tab-link sgpb-padding-10 sgpb-flex-auto sgpb-tab-subscription-plus-link sgpb-subscription-plus-tab-1 sgpb-tab-active" onclick="SGPBFormBuilder.prototype.changeTab(1)">
					<?php _e('Content', SG_POPUP_TEXT_DOMAIN)?>
				</span>
				<span class="sgpb-tab-link sgpb-padding-10 sgpb-flex-auto sgpb-tab-subscription-plus-link sgpb-subscription-plus-tab-2" onclick="SGPBFormBuilder.prototype.changeTab(2)">
					<?php _e('Style', SG_POPUP_TEXT_DOMAIN)?>
				</span>
				<span class="sgpb-tab-link sgpb-padding-10 sgpb-flex-auto sgpb-tab-subscription-plus-link sgpb-subscription-plus-tab-3" onclick="SGPBFormBuilder.prototype.changeTab(3)">
					<?php _e('Advanced', SG_POPUP_TEXT_DOMAIN)?>
				</span>
				<span class="sgpb-tab-link sgpb-padding-10 sgpb-flex-auto sgpb-tab-subscription-plus-link sgpb-subscription-plus-tab-4" onclick="SGPBFormBuilder.prototype.changeTab(4)">
					<?php _e('Integrations', SG_POPUP_TEXT_DOMAIN)?>
				</span>
			</div>
			<div id="sgpb-subscription-plus-options-tab-content-wrapper-1" class="sgpb-subscription-plus-options-tab-content-wrapper" style="display: block;">
				<div id="sgpb-form-fields-html" >
					<div>
						<div class="sgpb-current-fields-wrapper">
							<?php
								echo $form->getCurrentFieldsAdminHtml();
							?>
						</div>
						<div class="sgpb-hide sgpb-admin-current-field-template">
							<?php
								echo $form->getCurrentFieldsAdminHtmlTemplate();
							?>
						</div>
						<div class="sgpb-hide sgpb-fields-edit-settings-template">
							<?php 
								echo $form->getAllFieldsEditSettingsTemplate();
							?>
						</div>
					</div>
				</div>
				<div class="sgpb-display-none" id="sgpbSubscriptionPlusFieldsListShortHtml" >
					<?php  echo $form->getFieldsListShortHtml(); ?>
				</div>
				<button class="sgpb-btn sgpb-btn-blue sgpb-subscription-plus-add-field-js" type="button">
					<?php _e('ADD FIELD', SG_POPUP_TEXT_DOMAIN); ?>
				</button>
			</div>

			<div id="sgpb-subscription-plus-options-tab-content-wrapper-2" class="sgpb-subscription-plus-options-tab-content-wrapper">
				<!-- form background options start -->
				<div class="formItem sgpb-margin-0">
					<label class="sgpb-width-100">
						<input type="checkbox" class="js-checkbox-accordion-style-option" style="display: none;" checked="">
						<div class="sgpb-style-options-title sgpb-align-item-center sgpb-display-flex sgpb-justify-content-between">
							<h3 class="formItem__title">
								<?php _e('Form background options', SG_POPUP_TEXT_DOMAIN); ?>
							</h3>
							<span class="sgpb-arrows sgpb-arrow-up"><span></span><span></span></span>
						</div>
					</label>
				</div>
				<div class="sg-full-width sgpb-style-options formItem sgpb-margin-0">
					<div class="subFormItem formItem">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Form background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-subs-color-picker js-enable-color-picker-inputs" data-subs-rel="sgpb-subscription-admin-wrapper" data-style-type="background-color" type="text" name="sgpb-subs-form-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-form-bg-color')); ?>" autocomplete="off">
						</div>
					</div>
					<div class="subFormItem formItem">
						<label class="sgpb-static-padding-top subFormItem__title sgpb-margin-right-10">
							<?php _e('Form background opacity', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-slider-wrapper">
							<div class="slider-wrapper sgpb-display-inline-flex">
								<input type="range" class="sgpb-range-input js-subs-bg-opacity sgpb-margin-right-10 "
								       name="sgpb-subs-form-bg-opacity"
								       id="js-popup-overlay-opacity" min="0.0" step="0.1" max="1" value="<?php echo $popupTypeObj->getOptionValue('sgpb-subs-form-bg-opacity')?>">
								<span class="js-popup-overlay-opacity-value"><?php echo $popupTypeObj->getOptionValue('sgpb-subs-form-bg-opacity')?></span>
							</div>
						</div>
					</div>
					<div class="subFormItem formItem">
						<label for="sgpb-subs-form-padding" class="control-label subFormItem__title">
							<?php _e('Form padding', SG_POPUP_TEXT_DOMAIN); ?>
						</label>
					</div>
					<div class="formItem">
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-subs-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Top', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-form-padding-top'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="top" id="sgpb-subs-form-padding-top" name="sgpb-subs-form-padding-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-form-padding-top')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-subs-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Right', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-form-padding-right'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="right" id="sgpb-subs-form-padding-right" name="sgpb-subs-form-padding-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-form-padding-right')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-subs-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Bottom', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-form-padding-bottom'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="bottom" id="sgpb-subs-form-padding-bottom" name="sgpb-subs-form-padding-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-form-padding-bottom')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-subs-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Left', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-form-padding-left'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="left" id="sgpb-subs-form-padding-left" name="sgpb-subs-form-padding-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-form-padding-left')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
					</div>

					<div class="formItem sgpb-align-item-center">
						<label for="sgpb-subs-field-horizontally" class="sgpb-static-padding-top subFormItem__title">
							<?php _e('Set fields horizontally', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input class="js-subs-set-horizontally sgpb-margin-0 js-checkbox-accordion sgpb-margin-left-10" type="checkbox" id="content-click" name="sgpb-subs-field-horizontally" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-field-horizontally')); ?>>
						<span class="question-mark sgpb-info-icon">B</span>
						<div class="sgpb-info-wrapper">
							<span class="infoSelectRepeat samefontStyle sgpb-info-text" >
								<?php
									_e('If this option is checked, all fields will be placed horizontally in the popup. Please, be noted that the horizontal look is not visible in the "Live Preview". Check the look on a test page to see the horizontal view of the form', SG_POPUP_TEXT_DOMAIN);
								?>.
							</span>
						</div>
					</div>
					<div class="sg-full-width sgpb-margin-bottom-20">
						<div class="formItem sgpb-align-item-center sgpb-margin-0">
							<label for="sgpb-subs-except-button" class="sgpb-static-padding-top">
								<?php 
									_e('Except button', SG_POPUP_TEXT_DOMAIN);
								?>
							</label>
							<input type="checkbox" class="sgpb-margin-left-10" id="sgpb-subs-except-button" name="sgpb-subs-except-button" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-except-button')); ?>>
							<span class="question-mark sgpb-info-icon">B</span>
							<div class="sgpb-info-wrapper">
								<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
									<?php
									_e('This way the button will be shown at the bottom of the fields', SG_POPUP_TEXT_DOMAIN);
									?>.
								</span>
							</div>
						</div>

					</div>
				</div>
				<!-- form background options end -->
				<!-- Input styles start -->
				<div class="row formItem sgpb-margin-bottom-0" >
					<label class="col-md-12 control-label">
						<input type="checkbox" class="js-checkbox-accordion-style-option" style="display: none;">
						<div class="sgpb-style-options-title sgpb-align-item-center sgpb-display-flex sgpb-justify-content-between">
							<h3 class="formItem__title">
								<?php _e('Inputs\' style', SG_POPUP_TEXT_DOMAIN); ?>
							</h3>
							<span class="sgpb-arrows sgpb-arrow-up sgpb-arrow-down">
								<span></span>
								<span></span>
							</span>
						</div>
					</label>
				</div>
				<div class="sg-full-width sgpb-style-options sgpb-margin-0 formItem" >
					<div class="row form-group">
						<label for="subs-text-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input type="text" class="js-subs-dimension sgpb-width-100" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="width" name="sgpb-subs-text-width" id="subs-text-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="subs-text-height" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="height" type="text" name="sgpb-subs-text-height" id="subs-text-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-height')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="subs-text-border-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="border-width" type="text" name="sgpb-subs-text-border-width" id="subs-text-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-border-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-subs-text-border-radius" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="border-radius" type="text" name="sgpb-subs-text-border-radius" id="sgpb-subs-text-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-border-radius')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-subs-inputs-margin" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Margin', SG_POPUP_TEXT_DOMAIN); ?>
						</label>
					</div>
					<div class="formItem">
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-subs-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Top', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-input-margin-top'))?>" class="js-sgpb-inputs-margin sgpb-margin-x-10 sgpb-width-40" data-inputs-margin-direction="top" id="sgpb-subs-input-margin-top" name="sgpb-subs-input-margin-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-input-margin-top')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-subs-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Right', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-input-margin-right'))?>" class="js-sgpb-inputs-margin sgpb-margin-x-10 sgpb-width-40" data-inputs-margin-direction="right" id="sgpb-subs-input-margin-right" name="sgpb-subs-input-margin-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-input-margin-right')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-subs-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Bottom', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-input-margin-bottom'))?>" class="js-sgpb-inputs-margin sgpb-margin-x-10 sgpb-width-40" data-inputs-margin-direction="bottom" id="sgpb-subs-input-margin-bottom" name="sgpb-subs-input-margin-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-input-margin-bottom')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-subs-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Left', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-input-margin-left'))?>" class="js-sgpb-inputs-margin sgpb-margin-x-10 sgpb-width-40" data-inputs-margin-direction="left" id="sgpb-subs-input-margin-left" name="sgpb-subs-input-margin-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-input-margin-left')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
					</div>

					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="sgpb-color-picker js-subs-color-picker js-enable-color-picker-inputs" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="background-color" type="text" name="sgpb-subs-text-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-bg-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="sgpb-color-picker js-subs-color-picker js-enable-color-picker-inputs" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="border-color" type="text" name="sgpb-subs-text-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Active border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-subs-additional-color-picker js-enable-color-picker-inputs" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="active-border-color" type="text" name="sgpb-subs-text-active-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-active-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="sgpb-color-picker js-subs-color-picker js-enable-color-picker-inputs" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="color" type="text" name="sgpb-subs-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Placeholder color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="sgpb-color-picker js-subs-color-picker sgpb-full-width-events js-enable-color-picker-inputs" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="placeholder" type="text" name="sgpb-subs-text-placeholder-color" value="<?php echo esc_html($placeholderColor); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Label color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-subs-additional-color-picker js-enable-color-picker-inputs" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="label-color" type="text" name="sgpb-subs-label-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-label-color')); ?>" >
							</div>
						</div>
					</div>
				</div>
				<!-- Input styles end -->
				<!-- Submit styles start -->
				<div class="row formItem sgpb-margin-bottom-0" >
					<label class="col-md-12 control-label">
						<input type="checkbox" class="js-checkbox-accordion-style-option" style="display: none;">
						<div class="sgpb-style-options-title sgpb-align-item-center sgpb-display-flex sgpb-justify-content-between">
							<h3 class="formItem__title">
								<?php _e('Submit button styles', SG_POPUP_TEXT_DOMAIN); ?>
							</h3>
							<span class="sgpb-arrows sgpb-arrow-up sgpb-arrow-down">
								<span></span>
								<span></span>
							</span>
						</div>
					</label>
				</div>
				<div class="sg-full-width sgpb-style-options sgpb-margin-0 formItem" >
					<div class="row form-group">
						<label for="subs-btn-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-subs-rel="js-subs-submit-btn" data-field-type="submit" data-style-type="width" type="text" name="sgpb-subs-btn-width" id="subs-btn-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="subs-btn-height" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-subs-rel="js-subs-submit-btn" data-field-type="submit" data-style-type="height" type="text" name="sgpb-subs-btn-height" id="subs-btn-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-height')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-subs-btn-border-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="border-width" type="text" name="sgpb-subs-btn-border-width" id="sgpb-subs-btn-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-border-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-subs-btn-border-radius" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-subs-rel="js-subs-submit-btn" data-field-type="submit" data-style-type="border-radius" type="text" name="sgpb-subs-btn-border-radius" id="sgpb-subs-btn-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-border-radius')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-subs-button-margin" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Margin', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
					</div>
					<div class="formItem">
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-subs-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Top', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-button-margin-top'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-button-margin-direction="top" id="sgpb-subs-button-margin-top" name="sgpb-subs-button-margin-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-button-margin-top')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-subs-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex ">
								<span class="sgpb-width-30">
									<?php _e('Right', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-button-margin-right'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-button-margin-direction="right" id="sgpb-subs-input-margin-right" name="sgpb-subs-button-margin-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-button-margin-right')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-subs-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Bottom', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-button-margin-bottom'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-botton-margin-direction="bottom" id="sgpb-subs-button-margin-bottom" name="sgpb-subs-button-margin-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-button-margin-bottom')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-subs-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex ">
								<span class="sgpb-width-30">
									<?php _e('Left', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-button-margin-left'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-button-margin-direction="left" id="sgpb-subs-button-margin-left" name="sgpb-subs-button-margin-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-button-margin-left')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
					</div>

					<div class="row form-group">
						<label for="sgpb-subs-btn-font-size" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Font size', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-subs-dimension sgpb-width-100" data-subs-rel="js-subs-submit-btn" data-field-type="submit" data-style-type="font-size" type="text" name="sgpb-subs-btn-font-size" id="sgpb-subs-btn-font-size" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-font-size')); ?>">
						</div>
					</div>
					<div class="row form-group subFormItem">
						<label for="sgpb-subs-btn-border-color" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input id="sgpb-subs-btn-border-color" class="sgpb-color-picker js-subs-color-picker js-enable-color-picker-inputs" data-field-type="submit" data-subs-rel="js-subs-submit-inputs" data-style-type="border-color" type="text" name="sgpb-subs-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="sgpb-color-picker js-subs-color-picker js-enable-color-picker-inputs" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="background-color" type="text" name="sgpb-subs-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-bg-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Hover background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input id="sgpb-subs-btn-bg-hover-color" class="js-subs-additional-color-picker js-enable-color-picker-inputs" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="hover-background-color" type="text" name="sgpb-subs-btn-bg-hover-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-bg-hover-color')); ?>" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row form-group subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="sgpb-color-picker js-subs-color-picker js-enable-color-picker-inputs" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="color" type="text" name="sgpb-subs-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-text-color')); ?>" >
							</div>
						</div>
					</div>
				</div>
				<!-- submit styles end -->
			</div>

			<div id="sgpb-subscription-plus-options-tab-content-wrapper-3" class="sgpb-subscription-plus-options-tab-content-wrapper ">
				<div class="formItem">
					<div class="sgpb-width-100">
						<div class="row form-group">
							<label for="subs-validation-message" class="col-md-6 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('Required field message', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<input type="text" name="sgpb-subs-validation-message" id="subs-validation-message" class="sgpb-width-100" maxlength="90" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-validation-message')); ?>">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('Error message', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<input type="text" class="sgpb-width-100" name="sgpb-subs-error-message"  value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-error-message')); ?>">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('Invalid email message', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<input type="text" class="sgpb-width-100" name="sgpb-subs-invalid-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-invalid-message')); ?>">
							</div>
						</div>

						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title" for="sgpb-subs-register-user">
								<?php _e('Register as a WP user', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
									<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-subs-register-user" name="sgpb-subs-register-user"
										<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-register-user')); ?>>
									<label class="sgpb-onOffSwitch__label" for="sgpb-subs-register-user">
										<span class="sgpb-onOffSwitch-inner"></span>
										<span class="sgpb-onOffSwitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title" for="sgpb-subs-hide-subs-users">
								<?php _e('Hide for already subscribed users', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
									<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-subs-hide-subs-users" name="sgpb-subs-hide-subs-users"
										<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-hide-subs-users')); ?>>
									<label class="sgpb-onOffSwitch__label" for="sgpb-subs-hide-subs-users">
										<span class="sgpb-onOffSwitch-inner"></span>
										<span class="sgpb-onOffSwitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title" for="sgpb-subs-double-option">
								<?php _e('Enable double opt-in', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6 sgpb-display-inline-flex sgpb-align-item-center">
								<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
									<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-subs-double-option" name="sgpb-subs-double-option"
										<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-double-option')); ?>>
									<label class="sgpb-onOffSwitch__label" for="sgpb-subs-double-option">
										<span class="sgpb-onOffSwitch-inner"></span>
										<span class="sgpb-onOffSwitch-switch"></span>
									</label>
								</div>
								<span class="question-mark sgpb-info-icon">B</span>
								<div class="sgpb-info-wrapper">
							<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
								<?php
								_e('By checking this option you will ask the new subscriber to verify their email address and confirm the subscription', SG_POPUP_TEXT_DOMAIN);
								?>.
							</span>
								</div>

							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title" for="sgpb-subs-show-form-to-top">
								<?php _e('Show form on the Top', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
									<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-subs-show-form-to-top" name="sgpb-subs-show-form-to-top"
										<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-show-form-to-top')); ?>>
									<label class="sgpb-onOffSwitch__label" for="sgpb-subs-show-form-to-top">
										<span class="sgpb-onOffSwitch-inner"></span>
										<span class="sgpb-onOffSwitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="row form-group formItem">
							<label class="col-md-12 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('After successful subscription', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
						</div>
						<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
							<?php
							$multipleChoiceButton = new MultipleChoiceButton($defaultData['subscriptionSuccessBehavior'], $popupTypeObj->getOptionValue('sgpb-subs-success-behavior'));
							echo $multipleChoiceButton;
							?>
						</div>
						<div class="sg-hide sg-full-width formItem" id="subs-show-success-message">
							<div class="row sgpb-align-item-center sgpb-display-flex">
								<label for="sgpb-subs-success-message" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Success message', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6">
									<input type="text" name="sgpb-subs-success-message" id="sgpb-subs-success-message" class="sgpb-width-100" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-success-message')); ?>">
								</div>
							</div>
						</div>
						<div class="sg-hide sg-full-width " id="subs-redirect-to-URL">
							<div class="row sgpb-align-item-center sgpb-display-flex formItem">
								<label for="sgpb-subs-success-redirect-URL" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Redirect URL', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6">
									<input type="url" name="sgpb-subs-success-redirect-URL" id="sgpb-subs-success-redirect-URL" placeholder="https://www.example.com" class="sgpb-width-100" value="<?php echo $popupTypeObj->getOptionValue('sgpb-subs-success-redirect-URL'); ?>">
								</div>
							</div>
							<div class="row sgpb-align-item-center sgpb-display-flex formItem">
								<label for="subs-success-redirect-new-tab" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Redirect to new tab', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6">
									<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
										<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="subs-success-redirect-new-tab" name="sgpb-subs-success-redirect-new-tab"
											<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-success-redirect-new-tab')); ?>>
										<label class="sgpb-onOffSwitch__label" for="subs-success-redirect-new-tab">
											<span class="sgpb-onOffSwitch-inner"></span>
											<span class="sgpb-onOffSwitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="sg-hide sg-full-width formItem" id="subs-open-popup">
							<div class="row sgpb-align-item-center sgpb-display-flex">
								<label for="sgpb-subs-success-redirect-URL" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Select popup', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6">
									<?php echo AdminHelper::createSelectBox($subscriptionSubPopups, $successPopup, array('name' => 'sgpb-subs-success-popup', 'class'=>'js-sg-select2 sgpb-width-100')); ?>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
			<!-- Integrations tab design -->
			<div id="sgpb-subscription-plus-options-tab-content-wrapper-4" class="sgpb-subscription-plus-options-tab-content-wrapper">
				<div id="sgpb-form-fields-html" class="row">
					<div class="col-md-12">
						<div class="sgpb-integrations-fields-wrapper">
							<?php 
								echo EmailIntegrations::getActiveIntegrationsAdminTemplate();
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sgpb-width-50 sgpb-fb-live-preview sgpb-padding-x-10">
			<div class="sgpb-position-sticky sgpb-shadow-black-10 sgpb-border-radius-5px sgpb-bg-white sgpb-padding-20">
				<div class="sgpb-form-wrapper sgpb-overflow-auto">
					<h1 class="sgpb-margin-bottom-20 sgpb-margin-auto sgpb-align-item-center sgpb-btn sgpb-btn-gray-light sgpb-btn--rounded sgpb-display-flex sgpb-justify-content-center">
						<img class="sgpb-margin-right-10" src="<?php echo SG_POPUP_PUBLIC_URL.'icons/Black/eye.svg'; ?>" alt="Eye icon">
						<?php _e('Live Preview', SG_POPUP_TEXT_DOMAIN)?>
					</h1>
					<div class="sgpb-subscription-plus-form-live-preview sgpb-subscription-plus-form-0 sgpb-subscription-admin-wrapper">
						<div class="sgpb-js-form-loader-spinner">
							<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="<?php _e('loading', SG_POPUP_TEXT_DOMAIN)?>" class="sgpb-js-form-loader-spinner" width="30px">
						</div>
					</div>
				</div>
				<input type="hidden" class="sgpb-fields-json" id="sgpb-subscription-fields-json" name="sgpb-subscription-fields-json" value='<?php echo $form->getFieldsJson(); ?>'>
				<input type="hidden" class="sgpb-fields-design-json" id="sgpb-fields-design-json" name="sgpb-subscription-fields-design-json" value='<?php  echo $form->getFieldsDesignJson($popupTypeObj); ?>'>
				<input type="hidden" class="sgpb-subs-active-integrations" id="sgpb-subs-active-integrations" name="sgpb-subs-active-integrations" value='<?php echo EmailIntegrations::getIntegrationFieldsJson();?>'>

			</div>
		</div>
	</div>
</div>
