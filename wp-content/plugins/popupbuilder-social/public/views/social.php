<?php
	use sgpb\AdminHelper;
	use sgpb\MultipleChoiceButton;
	use sgpbsocial\ConfigDataHelper;
	$defaultData = ConfigDataHelper::defaultData();
?>
<div class="sgpb ">
	<div class="sgpb-social">
		<div class="sgpb-wrapper  formItem">
			<div class="sgpb-padding-10 sgpb-width-100">
				<div class="sgpb-width-60 sgpb-padding-x-10">
					<div class="sgpb-position-sticky sgpb-shadow-black-10 sgpb-border-radius-5px sgpb-bg-white sgpb-padding-20">
						<h1 class="sgpb-margin-bottom-20 sgpb-margin-auto sgpb-align-item-center sgpb-btn sgpb-btn-gray-light sgpb-btn--rounded sgpb-display-flex sgpb-justify-content-center">
							<img class="sgpb-margin-right-10" src="<?php echo SG_POPUP_PUBLIC_URL.'icons/Black/eye.svg'; ?>" alt="Eye icon">
							<?php _e('Live Preview', SG_POPUP_TEXT_DOMAIN)?>
						</h1>
						<div class="sgpb-socials-admin-wrapper">
							<?php echo $popupTypeObj->includeSocialButton(); ?>
						</div>
					</div>
				</div>
				<div class="sgpb-padding-x-20">
					<?php
					$multipleChoiceButton = new MultipleChoiceButton($defaultData['socialShareOptions'], $popupTypeObj->getOptionValue('sgpb-social-share-url-type'));
					echo $multipleChoiceButton;
					?>
					<div class="sg-hide sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-y-20" id="sgpb-social-share-url-wrapper">
						<div class="formItem subFormItem">
							<label for="sgpb-social-share-url" class="subFormItem__title sgpb-margin-right-10 sgpb-static-padding-top sgpb-sub-option"><?php _e('URL', SG_POPUP_TEXT_DOMAIN)  ?>:</label>
							<div>
								<input type="url" placeholder="https://www.example.com" id="sgpb-social-share-url" class="sgpb-full-width-events" name="sgpb-social-share-url" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-social-share-url')) ?>">
								<span><?php _e('If empty, current URL will be used.', SG_POPUP_TEXT_DOMAIN); ?></span>
							</div>
						</div>
					</div>
					<div class="formItem">
						<label class="formItem__title">
							<?php _e('Configuration of the buttons', SG_POPUP_TEXT_DOMAIN)  ?>:
						</label>
					</div>
					<div class="sgpb-padding-20 sgpb-bg-black__opacity-02">
						<div class="subFormItem sgpb-social-themes">
							<label class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Theme', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<div class="formItem">

								<?php
								AdminHelper::createRadioButtons(
									$defaultData['socialShareTheme'],
									"sgpb-social-share-theme",
									esc_html($popupTypeObj->getOptionValue('sgpb-social-share-theme')),
									true,
									'bg_img');
								?>

							</div>

						</div>
						<div class="formItem subFormItem">
							<label class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Font size', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<?php echo AdminHelper::createSelectBox($defaultData['socialThemeSizes'], esc_html($popupTypeObj->getOptionValue('sgpb-social-theme-size')), array('name' => 'sgpb-social-theme-size', 'class'=>'js-sg-select2 js-sgpb-social-theme-size')); ?>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-show-labels" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Show labels', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<input type="checkbox" id="sgpb-social-show-labels" name="sgpb-social-show-labels" <?php echo $popupTypeObj->getOptionValue('sgpb-social-show-labels'); ?>>
						</div>
						<div class="formItem subFormItem">
							<label class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Show share count', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<?php echo AdminHelper::createSelectBox($defaultData['socialThemeShereCount'], esc_html($popupTypeObj->getOptionValue('sgpb-social-share-count')), array('name' => 'sgpb-social-share-count', 'class'=>'js-sg-select2 js-sgpb-social-share-count')); ?>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-round-buttons" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Use round buttons', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<input type="checkbox" id="sgpb-social-round-buttons" name="sgpb-social-round-buttons" <?php echo $popupTypeObj->getOptionValue('sgpb-social-round-buttons'); ?>>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-to-bottom" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Push to bottom', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<input type="checkbox" id="sgpb-social-to-bottom" name="sgpb-social-to-bottom" <?php echo $popupTypeObj->getOptionValue('sgpb-social-to-bottom'); ?>>
							<span class="question-mark sgpb-info-icon">B</span>
							<div class="sgpb-info-wrapper">
								<span class="infoSelectRepeat samefontStyle sgpb-info-text">
									<?php esc_html_e('This option will work correctly if popup’s dimensions are set in custom mode', SG_POPUP_TEXT_DOMAIN);?>
								</span>
							</div>
						</div>
					</div>

					<div class="formItem">
						<label class="formItem__title sgpb-margin-right-10">
							<?php _e('Share Buttons', SG_POPUP_TEXT_DOMAIN)  ?>:
						</label>
					</div>
					<div class="sgpb-padding-20 sgpb-bg-black__opacity-02">
						<div class="formItem subFormItem">
							<label for="sgpb-social-status-email" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('E-mail', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
								<input type="checkbox"
								       data-social-name="email"
								       class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-social-network" id="sgpb-social-status-email" name="sgpb-social-status-email"
									<?php echo $popupTypeObj->getOptionValue('sgpb-social-status-email'); ?>>
								<label class="sgpb-onOffSwitch__label" for="sgpb-social-status-email">
									<span class="sgpb-onOffSwitch-inner"></span>
									<span class="sgpb-onOffSwitch-switch"></span>
								</label>
							</div>
						</div>
						<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-10">
							<div class="subFormItem">
								<label for="sgpb-social-label-email" class="sgpb-margin-right-10">
									<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
								</label>
								<input type="text" class="js-sgpb-social-label sgpb-full-width-events" data-social-name="email" id="sgpb-social-label-email" name="sgpb-social-label-email" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-social-label-email')); ?>">
							</div>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-status-twitter" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Twitter', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
								<input type="checkbox"
								       data-social-name="twitter"
								       class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-social-network" id="sgpb-social-status-twitter" name="sgpb-social-status-twitter"
									<?php echo $popupTypeObj->getOptionValue('sgpb-social-status-twitter'); ?>>
								<label class="sgpb-onOffSwitch__label" for="sgpb-social-status-twitter">
									<span class="sgpb-onOffSwitch-inner"></span>
									<span class="sgpb-onOffSwitch-switch"></span>
								</label>
							</div>
						</div>
						<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-10">
							<div class="subFormItem">
								<label for="sgpb-social-label-twitter" class="sgpb-margin-right-10">
									<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
								</label>
								<input type="text" class="js-sgpb-social-label sgpb-full-width-events" data-social-name="twitter" id="sgpb-social-label-twitter" name="sgpb-social-label-twitter" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-social-label-twitter')); ?>">
							</div>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-status-facebook" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Facebook', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
								<input type="checkbox"
								       data-social-name="facebook"
								       class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-social-network" id="sgpb-social-status-facebook" name="sgpb-social-status-facebook"
									<?php echo $popupTypeObj->getOptionValue('sgpb-social-status-facebook'); ?>>
								<label class="sgpb-onOffSwitch__label" for="sgpb-social-status-facebook">
									<span class="sgpb-onOffSwitch-inner"></span>
									<span class="sgpb-onOffSwitch-switch"></span>
								</label>
							</div>
						</div>
						<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-10">
							<div class="subFormItem">
								<label for="sgpb-social-label-facebook" class="sgpb-margin-right-10">
									<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
								</label>
								<input type="text" class="js-sgpb-social-label sgpb-full-width-events" data-social-name="facebook" id="sgpb-social-label-facebook" name="sgpb-social-label-facebook" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-social-label-facebook')); ?>">
							</div>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-status-googleplus" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Google+', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
								<input type="checkbox"
								       data-social-name="googleplus"
								       class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-social-network" id="sgpb-social-status-googleplus" name="sgpb-social-status-googleplus"
									<?php echo $popupTypeObj->getOptionValue('sgpb-social-status-googleplus'); ?>>
								<label class="sgpb-onOffSwitch__label" for="sgpb-social-status-googleplus">
									<span class="sgpb-onOffSwitch-inner"></span>
									<span class="sgpb-onOffSwitch-switch"></span>
								</label>
							</div>
						</div>
						<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-10">
							<div class="subFormItem">
								<label for="sgpb-social-label-googleplus" class="sgpb-margin-right-10">
									<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
								</label>
								<input type="text" class="js-sgpb-social-label sgpb-full-width-events" data-social-name="googleplus" id="sgpb-social-label-googleplus" name="sgpb-social-label-googleplus" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-social-label-googleplus')); ?>">
							</div>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-status-linkedin" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('LinkedIn', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
								<input type="checkbox"
								       data-social-name="linkedin"
								       class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-social-network" id="sgpb-social-status-linkedin" name="sgpb-social-status-linkedin"
									<?php echo $popupTypeObj->getOptionValue('sgpb-social-status-linkedin'); ?>>
								<label class="sgpb-onOffSwitch__label" for="sgpb-social-status-linkedin">
									<span class="sgpb-onOffSwitch-inner"></span>
									<span class="sgpb-onOffSwitch-switch"></span>
								</label>
							</div>
						</div>
						<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-10">
							<div class="subFormItem">
								<label for="sgpb-social-label-linkedin" class="subFormItem__title sgpb-margin-right-10">
									<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
								</label>
								<input type="text" class="js-sgpb-social-label sgpb-full-width-events" data-social-name="linkedin" id="sgpb-social-label-linkedin" name="sgpb-social-label-linkedin" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-social-label-linkedin')); ?>">
							</div>
						</div>
						<div class="formItem subFormItem">
							<label for="sgpb-social-status-pinterest" class="subFormItem__title sgpb-margin-right-10">
								<?php _e('Pinterest', SG_POPUP_TEXT_DOMAIN)  ?>:
							</label>
							<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
								<input type="checkbox"
								       data-social-name="linkedin"
								       class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-social-network" id="sgpb-social-status-pinterest" name="sgpb-social-status-pinterest"
									<?php echo $popupTypeObj->getOptionValue('sgpb-social-status-pinterest'); ?>>
								<label class="sgpb-onOffSwitch__label" for="sgpb-social-status-pinterest">
									<span class="sgpb-onOffSwitch-inner"></span>
									<span class="sgpb-onOffSwitch-switch"></span>
								</label>
							</div>
						</div>
						<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-10">
							<div class="subFormItem">
								<label for="sgpb-social-label-pinterest" class="sgpb-margin-right-10">
									<?php _e('Label', SG_POPUP_TEXT_DOMAIN)  ?>:
								</label>
								<input type="text" class="js-sgpb-social-label sgpb-full-width-events" data-social-name="pinterest" id="sgpb-social-label-pinterest" name="sgpb-social-label-pinterest" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-social-label-pinterest')); ?>">
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>
