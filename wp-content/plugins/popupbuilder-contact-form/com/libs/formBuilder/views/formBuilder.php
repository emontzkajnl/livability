<?php
	require_once(SGPB_CF_FORM_BUILDER.'config.php');
	use \sgpbcontactform\CFFormCreator;
	use sgpb\Functions;
	use sgpb\AdminHelper;
	use sgpb\MultipleChoiceButton;
	use sgpbcontactform\ConfigDataHelper;
	require_once(SGPB_CF_FORM_CLASSES_FORMS.'CFFormCreator.php');
	$placeholderColor = $popupTypeObj->getOptionValue('sgpb-contact-inputs-placeholder-color');
	$defaultData = ConfigDataHelper::defaultData();
	$cfSubPopups = $popupTypeObj->getPopupsIdAndTitle();
	$successPopup = $popupTypeObj->getOptionValue('sgpb-contact-success-popup');
	$postId = 0;
	if ($popupTypeObj->getOptionValue('sgpb-post-id')) {
		$postId = $popupTypeObj->getOptionValue('sgpb-post-id');
	}
?>

<div class="sgpb-form-admin-wrapper">
	<?php
		$formFieldsJson = $popupTypeObj->getOptionValue('sgpb-contact-fields-json');
		// free contact form Saved options
		$freeSavedOptions = $popupTypeObj->getOptionValue('sgpb-contact-fields');
		// for back work compatibility
		if (empty($formFieldsJson) && !empty($freeSavedOptions)) {
			$formFieldsJson = CFFormCreator::createSavedObjFromFreeOptions($freeSavedOptions, $popupTypeObj);
		}
		// for the old silver/gold/platinum version backward compatibility
		else if (empty($formFieldsJson) && empty($freeSavedOptions)) {
			$savedFormFields = $popupTypeObj->createFormFieldsDataOld();
			$formFieldsJson = CFFormCreator::createSavedObjFromFreeOptions($savedFormFields, $popupTypeObj);
		}

		$savedFormFields = json_decode($formFieldsJson, true);
		$creationArgs = array('savedFormFields' => $savedFormFields);
		$formCreator = new CFFormCreator();
		$formCreator->setClassPath(SGPB_CF_FORM_CLASSES_FORMS);
		$form = $formCreator->create('Contactbuilder', $creationArgs);
	?>
</div>

<div class="sgpb-wrapper sgpb-fb-wrapper">
	<div class="sgpb-bg-black__opacity-02 sgpb-display-flex sgpb-padding-10 sgpb-fb-wrapper-main">
		<div class="sgpb-width-50 sgpb-fb-main-options sgpb-padding-x-20">
			<div class="sgpb-tabs sgpb-margin-bottom-20">
				<span class="sgpb-tab-link sgpb-padding-10 sgpb-flex-auto sgpb-tab-contact-form-link sgpb-contact-form-tab-1 sgpb-tab-active" onclick="SGPBContactFormBuilder.prototype.changeTab(1)">
					<?php _e('Content', SG_POPUP_TEXT_DOMAIN)?>
				</span>
				<span class="sgpb-tab-link sgpb-padding-10 sgpb-flex-auto sgpb-tab-contact-form-link sgpb-contact-form-tab-2" onclick="SGPBContactFormBuilder.prototype.changeTab(2)">
					<?php _e('Style', SG_POPUP_TEXT_DOMAIN)?>
				</span>
				<span class="sgpb-tab-link sgpb-padding-10 sgpb-flex-auto sgpb-tab-contact-form-link sgpb-contact-form-tab-3" onclick="SGPBContactFormBuilder.prototype.changeTab(3)">
					<?php _e('Advanced', SG_POPUP_TEXT_DOMAIN)?>
				</span>
			</div>
			<div id="sgpb-contact-form-options-tab-content-wrapper-1" class="sgpb-contact-form-options-tab-content-wrapper" style="display: block;">
				<div id="sgpb-form-fields-html">
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
							<?php echo $form->getAllFieldsEditSettingsTemplate(); ?>
						</div>
					</div>
				</div>
				<div class="sgpb-display-none" id="sgpbContactFormFieldsListShortHtml">
					<?php echo $form->getFieldsListShortHtml(); ?>
				</div>
				<button type="button" class="sgpb-btn sgpb-btn-blue sgpb-contact-form-add-field-js" style="width: auto; font-size: 17px">
					<?php _e('ADD FIELD', SG_POPUP_TEXT_DOMAIN); ?>
				</button>
			</div>

			<div id="sgpb-contact-form-options-tab-content-wrapper-2" class="sgpb-contact-form-options-tab-content-wrapper">
				<!-- form background options start -->
				<div class="formItem sgpb-margin-0">
					<label class="sgpb-width-100">
						<input type="checkbox" class="js-checkbox-accordion-style-option"  style="display: none;" checked="">
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
							<input class="js-contact-additional-color-picker sgpb-color-picker" data-field-type="form" data-contact-rel="sgpb-contact-admin-wrapper" data-style-type="background-color" type="text" name="sgpb-contact-form-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-form-bg-color')); ?>" autocomplete="off">
						</div>
					</div>

					<div class="subFormItem formItem">
						<label for="content-padding" class="sgpb-static-padding-top subFormItem__title sgpb-margin-right-10">
							<?php _e('Form background opacity', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-slider-wrapper">
							<div class="slider-wrapper sgpb-display-inline-flex">
								<input type="range" class="sgpb-range-input js-contact-bg-opacity  sgpb-margin-right-10 "
								       name="sgpb-contact-form-bg-opacity"
								       id="js-popup-overlay-opacity" min="0.0" step="0.1" max="1" value="<?php echo $popupTypeObj->getOptionValue('sgpb-contact-form-bg-opacity');?>">
								<span class="js-popup-overlay-opacity-value"><?php echo $popupTypeObj->getOptionValue('sgpb-contact-form-bg-opacity')?></span>
							</div>
						</div>
					</div>
					<div class="subFormItem formItem">
						<label for="sgpb-contact-form-padding" class="control-label subFormItem__title">
							<?php _e('Form padding', SG_POPUP_TEXT_DOMAIN); ?>
						</label>
					</div>
					<div class="formItem">
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Top', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-form-padding-top'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="top" id="sgpb-contact-form-padding-top" name="sgpb-contact-form-padding-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-form-padding-top')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Right', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-form-padding-right'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="right" id="sgpb-contact-form-padding-right" name="sgpb-contact-form-padding-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-form-padding-right')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Bottom', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-form-padding-bottom'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="bottom" id="sgpb-contact-form-padding-bottom" name="sgpb-contact-form-padding-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-form-padding-bottom')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-form-padding" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Left', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-form-padding-left'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding sgpb-width-40" data-padding-direction="left" id="sgpb-contact-form-padding-left" name="sgpb-contact-form-padding-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-form-padding-left')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
					</div>

					<div class="formItem sgpb-align-item-center">
						<label for="sgpb-contact-field-horizontally" class="subFormItem__title sgpb-static-padding-top">
							<?php _e('Set fields horizontally', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input class="js-contact-set-horizontally js-checkbox-accordion sgpb-margin-0 sgpb-margin-left-10" type="checkbox" id="content-click" name="sgpb-contact-field-horizontally" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-field-horizontally')); ?>>
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
							<label for="sgpb-contact-except-button" class="sgpb-static-padding-top">
								<?php 
									_e('Except button', SG_POPUP_TEXT_DOMAIN);
								?>
							</label>
							<input type="checkbox" class="sgpb-margin-left-10" id="sgpb-contact-except-button" name="sgpb-contact-except-button" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-except-button')); ?>>
							<span class="question-mark sgpb-info-icon">B</span>
							<div class="sgpb-info-wrapper">
								<span class="infoSelectRepeat samefontStyle sgpb-info-text">
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
				<div class="row formItem sgpb-margin-bottom-0">
					<label class="col-md-12 control-label">
						<input type="checkbox" class="js-checkbox-accordion-style-option" style="display: none;">
						<div class="sgpb-style-options-title sgpb-align-item-center sgpb-display-flex sgpb-justify-content-between">
							<div class="formItem__title">
								<?php _e('Inputs\' style', SG_POPUP_TEXT_DOMAIN); ?>
							</div>
							<span class="sgpb-arrows sgpb-arrow-up sgpb-arrow-down">
								<span></span>
								<span></span>
							</span>
						</div>
					</label>
				</div>
				<div class="sg-full-width sgpb-style-options sgpb-margin-0 formItem">
					<div class="row form-group">
						<label for="contact-text-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input type="text" class="js-contact-dimension sgpb-width-100" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="width" name="sgpb-contact-inputs-width" id="contact-text-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-inputs-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="contact-text-height" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input type="text" class="js-contact-dimension sgpb-width-100" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="height" name="sgpb-contact-inputs-height" id="contact-text-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-inputs-height')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="contact-text-border-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-contact-dimension sgpb-width-100" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="border-width" type="text" name="sgpb-contact-inputs-border-width" id="contact-text-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-inputs-border-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-text-border-radius" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class="js-contact-dimension sgpb-width-100" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="border-radius" type="text" name="sgpb-contact-text-border-radius" id="sgpb-contact-text-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-text-border-radius')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-inputs-margin" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Margin', SG_POPUP_TEXT_DOMAIN); ?>
						</label>
					</div>
					<div class="formItem">
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30"><?php _e('Top', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-input-margin-top'))?>" class="sgpb-margin-x-10 js-sgpb-inputs-margin sgpb-width-40" data-inputs-margin-direction="top" id="sgpb-contact-input-margin-top" name="sgpb-contact-input-margin-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-input-margin-top')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30"><?php _e('Right', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-input-margin-right'))?>" class="sgpb-margin-x-10 js-sgpb-inputs-margin sgpb-width-40" data-inputs-margin-direction="right" id="sgpb-contact-input-margin-right" name="sgpb-contact-input-margin-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-input-margin-right')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30"><?php _e('Bottom', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-input-margin-bottom'))?>" class="sgpb-margin-x-10 js-sgpb-inputs-margin sgpb-width-40" data-inputs-margin-direction="bottom" id="sgpb-contact-input-margin-bottom" name="sgpb-contact-input-margin-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-input-margin-bottom')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-inputs-margin" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30"><?php _e('Left', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-input-margin-left'))?>" class="sgpb-margin-x-10 js-sgpb-inputs-margin sgpb-width-40" data-inputs-margin-direction="left" id="sgpb-contact-input-margin-left" name="sgpb-contact-input-margin-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-input-margin-left')) ;?>" autocomplete="off">
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
								<input class="js-contact-color-picker sgpb-color-picker js-enable-color-picker-inputs" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="background-color" type="text" name="sgpb-contact-inputs-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-inputs-bg-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="sgpb-color-picker js-contact-color-picker" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="border-color" type="text" name="sgpb-contact-inputs-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-inputs-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Active border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-additional-color-picker sgpb-color-picker js-enable-color-picker-inputs" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="active-border-color" type="text" name="sgpb-contact-text-active-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-text-active-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker sgpb-color-picker" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="color" type="text" name="sgpb-contact-inputs-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-inputs-text-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Placeholder color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker sgpb-color-picker" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="placeholder" type="text" name="sgpb-contact-inputs-placeholder-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-inputs-placeholder-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="subFormItem formItem row form-group">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Label color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-additional-color-picker sgpb-color-picker js-enable-color-picker-inputs" data-field-type="input" data-contact-rel="js-contact-text-inputs" data-style-type="label-color" type="text" name="sgpb-contact-label-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-label-color')); ?>" >
							</div>
						</div>
					</div>
				</div>
				<!-- Input styles end -->
				<!-- Textarea styles start -->
				<div class="row formItem sgpb-margin-bottom-0">
					<label class="col-md-12 control-label">
						<input type="checkbox" class="js-checkbox-accordion-style-option" style="display: none;">
						<div class="sgpb-style-options-title sgpb-align-item-center sgpb-display-flex sgpb-justify-content-between">
							<h3 class="formItem__title">
								<?php _e('Message style', SG_POPUP_TEXT_DOMAIN); ?>
							</h3>
							<span class="sgpb-arrows sgpb-arrow-up sgpb-arrow-down">
								<span></span>
								<span></span>
							</span>
						</div>
					</label>
				</div>
				<div class="sg-full-width sgpb-style-options sgpb-margin-0 formItem">
					<div class="row form-group">
						<label for="sgpb-contact-message-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input type="text" class=" js-contact-dimension sgpb-width-100" data-field-type="message" data-style-type="width" name="sgpb-contact-message-width" id="sgpb-contact-message-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-message-height" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input type="text" class=" js-contact-dimension sgpb-width-100" data-field-type="message" data-style-type="height" name="sgpb-contact-message-height" id="sgpb-contact-message-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-height')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-message-border-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input type="text" class=" js-contact-dimension sgpb-width-100" data-field-type="message" data-style-type="border-width" name="sgpb-contact-message-border-width" id="sgpb-contact-message-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-border-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-message-border-radius" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class=" js-contact-dimension sgpb-width-100 " data-field-type="message" data-contact-rel="js-contact-message-inputs" data-style-type="border-radius" type="text" name="sgpb-contact-message-border-radius" id="sgpb-contact-message-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-border-radius')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-message-margin" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Margin', SG_POPUP_TEXT_DOMAIN); ?>
						</label>
					</div>
					<div class="formItem">
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-message-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30"><?php _e('Top', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-message-margin-top'))?>" class="sgpb-margin-x-10 js-sgpb-message-margin sgpb-width-40" data-message-margin-direction="top" id="sgpb-contact-message-margin-top" name="sgpb-contact-message-margin-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-message-margin-top')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-message-margin" class="sgpb-align-item-center sgpb-display-inline-flex ">
								<span class="sgpb-width-30"><?php _e('Right', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-message-margin-right'))?>" class="sgpb-margin-x-10 js-sgpb-message-margin sgpb-width-40" data-message-margin-direction="right" id="sgpb-contact-message-margin-right" name="sgpb-contact-message-margin-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-message-margin-right')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-message-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30"><?php _e('Bottom', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-message-margin-bottom'))?>" class="sgpb-margin-x-10 js-sgpb-message-margin sgpb-width-40" data-message-margin-direction="bottom" id="sgpb-contact-message-margin-bottom" name="sgpb-contact-message-margin-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-message-margin-bottom')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-message-margin" class="sgpb-align-item-center sgpb-display-inline-flex ">
								<span class="sgpb-width-30"><?php _e('Left', SG_POPUP_TEXT_DOMAIN); ?></span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-message-margin-left'))?>" class="sgpb-margin-x-10 js-sgpb-message-margin sgpb-width-40" data-message-margin-direction="left" id="sgpb-contact-message-margin-left" name="sgpb-contact-message-margin-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-message-margin-left')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
					</div>


					<div class="row form-group formItem subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker sgpb-color-picker js-enable-color-picker-inputs" data-field-type="message" data-contact-rel="js-contact-field-textarea" data-style-type="background-color" type="text" name="sgpb-contact-message-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-bg-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-grou formItemp subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker sgpb-color-picker" data-field-type="message" data-contact-rel="js-contact-field-textarea" data-style-type="border-color" type="text" name="sgpb-contact-message-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group formItem subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Active border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-additional-color-picker sgpb-color-picker js-enable-color-picker-inputs" data-field-type="message" data-contact-rel="js-contact-field-textarea" data-style-type="message-active-border-color" type="text" name="sgpb-contact-message-active-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-active-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group formItem subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker sgpb-color-picker" data-field-type="message" data-contact-rel="js-contact-field-textarea" data-style-type="color" type="text" name="sgpb-contact-message-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-text-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group formItem subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Placeholder color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker sgpb-color-picker" data-field-type="message" data-contact-rel="js-contact-field-textarea" data-style-type="placeholder" type="text" name="sgpb-contact-message-placeholder-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-placeholder-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group formItem subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Label color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-additional-color-picker sgpb-color-picker js-enable-color-picker-inputs" data-field-type="message" data-contact-rel="js-contact-field-textarea" data-style-type="message-label-color" type="text" name="sgpb-contact-message-label-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-label-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group formItem subFormItem">
						<label class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Resize', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<?php echo AdminHelper::createSelectBox($defaultData['messageResize'], esc_html($popupTypeObj->getOptionValue('sgpb-contact-message-resize')), array('name' => 'sgpb-contact-message-resize', 'class'=>'sgpb-contact-message-resize js-sg-select2 sgpb-width-100')); ?>
						</div>
					</div>
				</div>
				<!-- Textarea styles end -->
				<!-- Submit styles start -->
				<div class="row formItem sgpb-margin-bottom-0">
					<label class="col-md-12 control-label">
						<input type="checkbox" class="js-checkbox-accordion-style-option" style="display: none;">
						<div class="sgpb-style-options-title sgpb-align-item-center sgpb-display-flex sgpb-justify-content-between">
							<h3 class="formItem__title"><?php _e('Submit button styles', SG_POPUP_TEXT_DOMAIN); ?></h3>
							<span class="sgpb-arrows sgpb-arrow-up sgpb-arrow-down">
								<span></span>
								<span></span>
							</span>
						</div>
					</label>
				</div>
				<div class="sg-full-width sgpb-style-options sgpb-margin-0 formItem">
					<div class="row form-group">
						<label for="contact-btn-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class=" js-contact-dimension sgpb-width-100" data-contact-rel="js-contact-submit-btn" data-field-type="submit" data-style-type="width" type="text" name="sgpb-contact-submit-width" id="contact-btn-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-submit-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="contact-btn-height" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class=" js-contact-dimension sgpb-width-100" data-contact-rel="js-contact-submit-btn" data-field-type="submit" data-style-type="height" type="text" name="sgpb-contact-submit-height" id="contact-btn-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-submit-height')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-submit-border-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option subFormItem__title">
							<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class=" js-contact-dimension sgpb-width-100" data-field-type="submit" data-contact-rel="js-contact-submit-btn" data-style-type="border-width" type="text" name="sgpb-contact-submit-border-width" id="sgpb-contact-submit-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-submit-border-width')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-submit-border-radius" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class=" js-contact-dimension sgpb-width-100" data-contact-rel="js-contact-submit-btn" data-field-type="submit" data-style-type="border-radius" type="text" name="sgpb-contact-submit-border-radius" id="sgpb-contact-submit-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-submit-border-radius')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-btn-font-size" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Font size', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input class=" js-contact-dimension sgpb-width-100" data-contact-rel="js-contact-submit-btn" data-field-type="submit" data-style-type="font-size" type="text" name="sgpb-contact-btn-font-size" id="sgpb-contact-btn-font-size" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-btn-font-size')); ?>">
						</div>
					</div>
					<div class="row form-group">
						<label for="sgpb-contact-button-margin" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
								<?php _e('Margin', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
					</div>
					<div class="formItem">
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Top', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-button-margin-top'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-button-margin-direction="top" id="sgpb-contact-button-margin-top" name="sgpb-contact-button-margin-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-button-margin-top')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Right', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-button-margin-right'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-button-margin-direction="right" id="sgpb-contact-input-margin-right" name="sgpb-contact-button-margin-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-button-margin-right')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
						<div class="sgpb-display-inline-flex sgpb-flex-direction-column sgpb-width-50">
							<label for="sgpb-contact-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-bottom-10">
								<span class="sgpb-width-30">
									<?php _e('Bottom', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-button-margin-bottom'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-button-margin-direction="bottom" id="sgpb-contact-button-margin-bottom" name="sgpb-contact-button-margin-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-button-margin-bottom')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
							<label for="sgpb-contact-button-margin" class="sgpb-align-item-center sgpb-display-inline-flex">
								<span class="sgpb-width-30">
									<?php _e('Left', SG_POPUP_TEXT_DOMAIN); ?>
								</span>
								<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-contact-button-margin-left'))?>" class="js-sgpb-button-margin sgpb-margin-x-10 sgpb-width-40" data-button-margin-direction="left" id="sgpb-contact-button-margin-left" name="sgpb-contact-button-margin-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-button-margin-left')) ;?>" autocomplete="off">
								<span class="sgpb-restriction-unit">px</span>
							</label>
						</div>
					</div>

					<div class="row form-group subFormItem">
						<label for="sgpb-contact-submit-border-color" class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input id="sgpb-contact-submit-border-color" class="js-contact-color-picker sgpb-color-picker" data-field-type="submit" data-contact-rel="js-contact-submit-btn" data-style-type="border-color" type="text" name="sgpb-contact-submit-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-submit-border-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker js-enable-color-picker-inputs sgpb-color-picker" data-field-type="submit" data-contact-rel="js-contact-submit-btn" data-style-type="background-color" type="text" name="sgpb-contact-submit-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-submit-bg-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input class="js-contact-color-picker js-enable-color-picker-inputs sgpb-color-picker" data-field-type="submit" data-contact-rel="js-contact-submit-btn" data-style-type="color" type="text" name="sgpb-contact-submit-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-submit-text-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group subFormItem">
						<label class="col-md-6 control-label sgpb-sub-option subFormItem__title">
							<?php _e('Hover background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper">
								<input id="sgpb-contact-btn-bg-hover-color" class="js-contact-additional-color-picker sgpb-color-picker js-enable-color-picker-inputs" data-field-type="submit" data-contact-rel="js-contact-submit-btn" data-style-type="hover-background-color" type="text" name="sgpb-contact-btn-bg-hover-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-btn-bg-hover-color')); ?>" autocomplete="off">
							</div>
						</div>
					</div>
				</div>
				<!-- submit styles end -->
			</div>

			<div id="sgpb-contact-form-options-tab-content-wrapper-3" class="sgpb-contact-form-options-tab-content-wrapper">
				<div class="formItem">
					<div class="sgpb-width-100">
						<div class="row form-group">
							<label for="contact-validation-message" class="col-md-6 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('Required field message', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<input type="text" name="sgpb-contact-required-message" id="contact-validation-message" class=" sgpb-width-100" maxlength="90" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-contact-required-message')); ?>">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('Error message', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<input type="text" class=" sgpb-width-100" name="sgpb-contact-error-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-error-message')); ?>">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('Invalid email message', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<input type="text" class=" sgpb-width-100" name="sgpb-contact-invalid-email-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-invalid-email-message')); ?>">
							</div>
						</div>
						<div class="row form-group">
							<label for="sgpb-contact-hide-for-contacted-users" class="col-md-6 control-label subFormItem__title">
								<?php _e('Hide for already contacted users', SG_POPUP_TEXT_DOMAIN)?>:
							</label>
							<div class="col-md-6">

								<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
									<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-contact-hide-for-contacted-users" name="sgpb-contact-hide-for-contacted-users"
										<?php echo $popupTypeObj->getOptionValue('sgpb-contact-hide-for-contacted-users'); ?>>
									<label class="sgpb-onOffSwitch__label" for="sgpb-contact-hide-for-contacted-users">
										<span class="sgpb-onOffSwitch-inner"></span>
										<span class="sgpb-onOffSwitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-6 control-label sgpb-static-padding-top subFormItem__title" for="sgpb-contact-show-form-to-top">
								<?php _e('Show form on the Top', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
							<div class="col-md-6">
								<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
									<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-contact-show-form-to-top" name="sgpb-contact-show-form-to-top"
										<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-show-form-to-top')); ?>>
									<label class="sgpb-onOffSwitch__label" for="sgpb-contact-show-form-to-top">
										<span class="sgpb-onOffSwitch-inner"></span>
										<span class="sgpb-onOffSwitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-md-12 control-label sgpb-static-padding-top subFormItem__title">
								<?php _e('After successful form submission', SG_POPUP_TEXT_DOMAIN); ?>:
							</label>
						</div>
						<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
							<?php
							$multipleChoiceButton = new MultipleChoiceButton($defaultData['contactFormSuccessBehavior'], $popupTypeObj->getOptionValue('sgpb-contact-success-behavior'));
							echo $multipleChoiceButton;
							?>
						</div>

						<div class="sg-hide sg-full-width formItem" id="contact-show-success-message">
							<div class="row  sgpb-align-item-center sgpb-display-flex">
								<label for="sgpb-contact-success-message" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Success message', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6"><input type="text" name="sgpb-contact-success-message" id="sgpb-contact-success-message" class="sgpb-width-100" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-contact-success-message')); ?>"></div>
							</div>
						</div>
						<div class="sg-hide sg-full-width" id="contact-redirect-to-URL">
							<div class="row sgpb-align-item-center sgpb-display-flex formItem">
								<label for="sgpb-contact-success-redirect-URL" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Redirect URL', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6"><input type="url" name="sgpb-contact-success-redirect-URL" id="sgpb-contact-success-redirect-URL" placeholder="https://www.example.com" class="sgpb-width-100" value="<?php echo $popupTypeObj->getOptionValue('sgpb-contact-success-redirect-URL'); ?>"></div>
							</div>
							<div class="row sgpb-align-item-center sgpb-display-flex formItem">
								<label for="contact-success-redirect-new-tab" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Redirect to new tab', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6">
									<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
										<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="contact-success-redirect-new-tab" name="sgpb-contact-success-redirect-new-tab"
											<?php echo $popupTypeObj->getOptionValue('sgpb-contact-success-redirect-new-tab'); ?>>
										<label class="sgpb-onOffSwitch__label" for="contact-success-redirect-new-tab">
											<span class="sgpb-onOffSwitch-inner"></span>
											<span class="sgpb-onOffSwitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="sg-hide sg-full-width formItem" id="contact-open-popup">
							<div class="row sgpb-align-item-center sgpb-display-flex">
								<label for="sgpb-contact-success-redirect-URL" class="col-md-6 control-label sgpb-double-sub-option">
									<?php _e('Select popup', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6">
									<?php echo AdminHelper::createSelectBox($cfSubPopups, $successPopup, array('name' => 'sgpb-contact-success-popup', 'class'=>'js-sg-select2 sgpb-width-100')); ?>
								</div>
							</div>
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
					<div class="sgpb-contact-form-live-preview sgpb-contact-form-0 sgpb-contact-admin-wrapper">
						<div class="sgpb-js-form-loader-spinner">
							<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="<?php _e('loading', SG_POPUP_TEXT_DOMAIN)?>" class="sgpb-js-form-loader-spinner" width="30px">
						</div>
					</div>
				</div>

				<input type="hidden" class="sgpb-fields-json" id="sgpb-contact-fields-json" name="sgpb-contact-fields-json" value='<?php echo $form->getFieldsJson(); ?>'>
				<input type="hidden" class="sgpb-contact-fields-design-json" id="sgpb-contact-fields-design-json" name="sgpb-contact-fields-design-json" value='<?php echo $form->getFieldsDesignJson($popupTypeObj); ?>'>
			</div>
		</div>
	</div>
</div>


