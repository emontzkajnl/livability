<div class="sgpb formItem">
	<div class="sgpb-wrapper sgpb-width-100">
		<div class="formItem">
			<label for="sgpb-age-verification-exit" class="formItem__title sgpb-margin-right-20">
				<?php _e('Exit URL', SG_POPUP_TEXT_DOMAIN)?>:
			</label>
			<input type="url" placeholder="https://" class="sgpb-countdown-date-input" id="sgpb-age-verification-exit" name="sgpb-age-verification-exit" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-age-verification-exit'))?>" required="required">
			<span class="question-mark sgpb-info-icon">B</span>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text">
				<?php _e('Add the URL to which the user will be redirected if he/she doesn\'t verify the age.', SG_POPUP_TEXT_DOMAIN);?>
			</span>
			</div>
		</div>
		<div class="formItem">
			<label for="sgpb-age-verification-lockout-count" class="formItem__title sgpb-margin-right-20">
				<?php _e('Lockout Try Count', SG_POPUP_TEXT_DOMAIN)?>:
			</label>
			<input type="number" class="sgpb-countdown-date-input" id="sgpb-age-verification-lockout-count" name="sgpb-age-verification-lockout-count" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-age-verification-lockout-count'))?>">
			<span class="question-mark sgpb-info-icon">B</span>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
					<?php _e('Specify the number of how many times the user can fail the verification.', SG_POPUP_TEXT_DOMAIN);?>
				</span>
			</div>
		</div>
		<div class="formItem">
			<label for="sgpb-age-verification-required-age" class="formItem__title sgpb-margin-right-20">
				<?php _e('Required Age', SG_POPUP_TEXT_DOMAIN)?>:
			</label>
			<input type="number" class="sgpb-countdown-date-input" id="sgpb-age-verification-required-age" name="sgpb-age-verification-required-age" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-age-verification-required-age'))?>">
			<span class="question-mark sgpb-info-icon">B</span>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
				<?php _e('Specify the required age for the users to pass the verification. You are free to specify any age you wish.', SG_POPUP_TEXT_DOMAIN);?>
			</span>
			</div>
		</div>
		<div class="formItem">
			<label class="formItem__title" for="sgpb-save-choice">
				<?php _e('Remember choice', SG_POPUP_TEXT_DOMAIN); ?>:
			</label>
			<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
				<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-save-choice" name="sgpb-age-verification-save-choice"
					<?php echo $popupTypeObj->getOptionValue('sgpb-age-verification-save-choice'); ?>>
				<label class="sgpb-onOffSwitch__label" for="sgpb-save-choice">
					<span class="sgpb-onOffSwitch-inner"></span>
					<span class="sgpb-onOffSwitch-switch"></span>
				</label>
			</div>
			<span class="question-mark sgpb-info-icon">B</span>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text">
					<?php _e('If this option is checked, you can specify the number of days after which the popup will be shown to the user after verification.', SG_POPUP_TEXT_DOMAIN);?>
				</span>
			</div>
		</div>
		<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-x-20">
			<div class="formItem subFormItem">
				<label for="sgpb-restriction-yes-expiration-time" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Expiration time', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<input name="sgpb-age-verification-expiration-time" id="sgpb-age-verification-expiration-time" type="number" min="0" required value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-expiration-time'))?>">
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
					<?php _e('Estimate the count of the days after which the popup will be shown to the same user after they verify their age with the choices.', SG_POPUP_TEXT_DOMAIN);?>
				</span>
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-age-verification-cookie-level" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Page level cookie saving', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<input type="checkbox" id="sgpb-age-verification-cookie-level" name="sgpb-age-verification-cookie-level" <?php echo $popupTypeObj->getOptionValue('sgpb-age-verification-cookie-level'); ?>>
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e('If this option is checked the popup confirmation date will refer to the current page. Otherwise the popup will be shown for specific times on each page selected.', SG_POPUP_TEXT_DOMAIN);?>
					</span>
				</div>
			</div>
		</div>
		<!-- Verification button start -->
		<div class="formItem">
			<label class="formItem__title">
				<?php _e('Verification button styles', SG_POPUP_TEXT_DOMAIN); ?>:
			</label>
		</div>
		<div class="sgpb-bg-black__opacity-02 formItem sgpb-flex-direction-column sgpb-align-item-start sgpb-padding-x-20">
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="age-verificationbtn-title" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Title', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input type="text" name="sgpb-age-verification-btn-title" id="age-verificationbtn-title" class="js-age-verificationbtn-title" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-title')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-btn-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-age-verificationrel="js-age-verificationsubmit-btn" data-field-type="submit" data-style-type="width" type="text" name="sgpb-age-verification-btn-width" id="age-verificationbtn-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-width')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-btn-height" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-age-verificationrel="js-age-verificationsubmit-btn" data-field-type="submit" data-style-type="height" type="text" name="sgpb-age-verification-btn-height" id="sgpb-age-verification-btn-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-height')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-btn-border-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="border-width" type="text" name="sgpb-age-verification-btn-border-width" id="sgpb-age-verification-btn-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-border-width')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-btn-border-radius" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-age-verificationrel="js-age-verificationsubmit-btn" data-field-type="submit" data-style-type="border-radius" type="text" name="sgpb-age-verification-btn-border-radius" id="sgpb-age-verification-btn-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-border-radius')); ?>">
			</div>
			<div class="sgpb-margin-y-10 formItem subFormItem">
				<label for="sgpb-age-verification-btn-border-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input id="sgpb-age-verification-btn-border-color" class="sgpb-color-picker js-age-verificationcolor-picker" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="border-color" type="text" name="sgpb-age-verification-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-border-color')); ?>" >
				</div>
			</div>

			<div class="sgpb-margin-y-10 formItem subFormItem">
				<label class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker js-age-verificationcolor-picker" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="background-color" type="text" name="sgpb-age-verification-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-bg-color')); ?>" >
				</div>
			</div>
			<div class="sgpb-margin-y-10 formItem subFormItem">
				<label class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker js-age-verificationcolor-picker" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="color" type="text" name="sgpb-age-verification-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-btn-text-color')); ?>" >
				</div>
			</div>

		</div>
	<!-- Submit button end -->

		<!-- Restriction button start -->
		<div class="formItem">
			<label class="formItem__title sgpb-margin-right-10">
				<?php _e('Restriction button styles', SG_POPUP_TEXT_DOMAIN); ?>:
			</label>
		</div>
		<div class="sgpb-bg-black__opacity-02 formItem sgpb-flex-direction-column sgpb-align-item-start sgpb-padding-x-20">
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-restriction-btn-title" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Title', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input type="text" name="sgpb-age-verification-restriction-btn-title" id="sgpb-age-verification-restriction-btn-title" class="js-age-verificationbtn-title" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-title')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-restriction-btn-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-age-verificationrel="js-age-verificationsubmit-btn" data-field-type="submit" data-style-type="width" type="text" name="sgpb-age-verification-restriction-btn-width" id="age-verificationbtn-restriction-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-width')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-restriction-btn-height" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-age-verificationrel="js-age-verificationsubmit-btn" data-field-type="submit" data-style-type="height" type="text" name="sgpb-age-verification-restriction-btn-height" id="sgpb-age-verification-restriction-btn-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-height')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-restriction-btn-border-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="border-width" type="text" name="sgpb-age-verification-restriction-btn-border-width" id="sgpb-age-verification-restriction-btn-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-border-width')); ?>">
			</div>
			<div class="sgpb-margin-y-10 subFormItem">
				<label for="sgpb-age-verification-restriction-btn-border-radius" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input class="js-age-verificationdimension" data-age-verificationrel="js-age-verificationsubmit-btn" data-field-type="submit" data-style-type="border-radius" type="text" name="sgpb-age-verification-restriction-btn-border-radius" id="sgpb-age-verification-restriction-btn-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-border-radius')); ?>">
			</div>
			<div class="sgpb-margin-y-10 formItem subFormItem">
				<label for="sgpb-age-verification-restriction-btn-border-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input id="sgpb-age-verification-restriction-btn-border-color" class="sgpb-color-picker js-age-verificationcolor-picker" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="border-color" type="text" name="sgpb-age-verification-restriction-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-border-color')); ?>" >
				</div>
			</div>

			<div class="sgpb-margin-y-10 formItem subFormItem">
				<label class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker js-age-verificationcolor-picker" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="background-color" type="text" name="sgpb-age-verification-restriction-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-bg-color')); ?>" >
				</div>
			</div>
			<div class="sgpb-margin-y-10 formItem subFormItem">
				<label class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input class="sgpb-color-picker js-age-verificationcolor-picker" data-field-type="submit" data-age-verificationrel="js-age-verificationsubmit-btn" data-style-type="color" type="text" name="sgpb-age-verification-restriction-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-restriction-btn-text-color')); ?>" >
				</div>
			</div>
		</div>

		<!-- Submit button end -->

		<div class="formItem">
			<label class="formItem__title">
				<?php _e('Error message label', SG_POPUP_TEXT_DOMAIN); ?>:
			</label>
			<input type="text" name="sgpb-age-verification-error-message" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-error-message'))?>">
		</div>
		<div class="formItem">
			<label class="formItem__title">
				<?php _e('Required age message', SG_POPUP_TEXT_DOMAIN); ?>:
			</label>
			<input type="text" name="sgpb-age-verification-required-message" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-age-verification-required-message'))?>">
		</div>

	</div>

</div>
