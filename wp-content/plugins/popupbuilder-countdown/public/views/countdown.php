<?php
	use sgpb\AdminHelper;
	use sgpb\MultipleChoiceButton;
	use sgpbcountdown\ConfigDataHelper;
	use sgpbcountdown\CountdownAdminHelper;

	$defaultData = ConfigDataHelper::defaultData();
	$popupId = $popupTypeObj->getOptionValue('sgpb-post-id');
	if (!$popupId) {
		$popupId = 0;
	}
	$params = $popupTypeObj->getCountdownParamsById($popupId, true);
	if (PHP_VERSION < '5.4.0'){
		$params = json_encode($params);
	} else {
		$params = json_encode($params,JSON_UNESCAPED_UNICODE);
	}
	$params = base64_encode($params);

	$args = array(
		'popupId' => $popupId,
		'countdownBgColor' => esc_html($popupTypeObj->getOptionValue('sgpb-counter-background-color')),
		'countdownTextColor' => esc_html($popupTypeObj->getOptionValue('sgpb-counter-text-color')),
		'countdownLabelsColor' => esc_html($popupTypeObj->getOptionValue('sgpb-counter-labels-color')),
		'countdownDividerColor' => esc_html($popupTypeObj->getOptionValue('sgpb-counter-divider-color')),
		'countdownPosition' => esc_html($popupTypeObj->getOptionValue('sgpb-countdown-fixed-position'))
	);
?>
<div class="sgpb sgpb-wrapper">
	<div class="row formItem">
		<div class="col-md-12">
			<div class="formItem">
				<div class="sgpb-bg-black__opacity-02 sgpb-shadow-black-10 sgpb-border-radius-5px sgpb-padding-30">
					<h1 class="sgpb-margin-bottom-20 sgpb-margin-auto sgpb-align-item-center sgpb-btn sgpb-btn-gray-light sgpb-btn--rounded sgpb-display-flex sgpb-justify-content-center">
						<img class="sgpb-margin-right-10" src="<?php echo SG_POPUP_PUBLIC_URL.'icons/Black/eye.svg'; ?>" alt="Eye icon">
						<?php _e('Live Preview', SG_POPUP_TEXT_DOMAIN)?>
					</h1>
					<div class="sgpb-countdown-wrapper sgpb-countdown-js-<?php echo $popupId; ?>" id="sgpb-clear-countdown" data-params='<?php echo $params; ?>'>
						<div class="sgpb-counts-content sgpb-flipclock-js-<?php echo $popupId; ?>">
						</div>
					</div>
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-counter-background-color" class="formItem__title">
					<?php _e('Counter background color', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input type="text" class="sgpb-color-picker" id="sgpb-counter-background-color" name="sgpb-counter-background-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-counter-background-color')); ?>">
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-counter-text-color" class="formItem__title">
					<?php _e('Counter digits color', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input type="text" class="sgpb-color-picker" id="sgpb-counter-text-color" name="sgpb-counter-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-counter-text-color')); ?>">
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-counter-labels-color" class="formItem__title">
					<?php _e('Counter labels color', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input type="text" class="sgpb-color-picker" id="sgpb-counter-labels-color" name="sgpb-counter-labels-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-counter-labels-color')); ?>">
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-counter-divider-color" class="formItem__title">
					<?php _e('Counter divider color', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="sgpb-color-picker-wrapper">
					<input type="text" class="sgpb-color-picker" id="sgpb-counter-divider-color" name="sgpb-counter-divider-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-counter-divider-color')); ?>">
				</div>
			</div>
			<?php
				$multipleChoiceButton = new MultipleChoiceButton($defaultData['countdownDateFormat'], $popupTypeObj->getOptionValue('sgpb-countdown-date-format'));
				echo $multipleChoiceButton;
			?>
			<div class="sg-hide sgpb-width-100 sgpb-padding-10 sgpb-bg-black__opacity-02" id="sgpb-countdown-date-format-from-date">
				<input type="text" id="sgpb-date-picker" class="sgpb-bg-date sgpb-margin-left-40" name="sgpb-countdown-due-date" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-countdown-due-date')); ?>">
			</div>
			<div class="sg-hide sgpb-width-100 sgpb-padding-10 sgpb-bg-black__opacity-02" id="sgpb-countdown-date-format-from-input">
				<div class="formItem subFormItem">
					<div class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-right-10">
						<label for="sgpb-countdown-date-days" class="sgpb-margin-right-10 subFormItem__title"><?php _e('Days', SG_POPUP_TEXT_DOMAIN);?></label>
						<input type="number" min="0" max="9999" data-type="days" class="sgpb-countdown-date-input" id="sgpb-countdown-date-days" name="sgpb-countdown-date-days" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-countdown-date-days'))?>">
					</div>
					<div class="sgpb-align-item-center sgpb-display-inline-flex sgpb-margin-right-10">
						<label for="sgpb-countdown-date-hours" class="sgpb-margin-right-10 subFormItem__title"><?php _e('Hours', SG_POPUP_TEXT_DOMAIN);?></label>
						<input type="number" min="0" max="60" data-type="hours" class="sgpb-countdown-date-input" id="sgpb-countdown-date-hours" name="sgpb-countdown-date-hours" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-countdown-date-hours'))?>">
					</div>
					<div class="sgpb-align-item-center sgpb-display-inline-flex">
						<label for="sgpb-countdown-date-minutes" class="sgpb-margin-right-10 subFormItem__title"><?php _e('Minutes', SG_POPUP_TEXT_DOMAIN);?></label>
						<input type="number" min="0" max="60" data-type="minutes" class="sgpb-countdown-date-input" id="sgpb-countdown-date-minutes" name="sgpb-countdown-date-minutes" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-countdown-date-minutes'))?>">
					</div>
				</div>
				<div class="subFormItem formItem">
					<label class="subFormItem__title sgpb-margin-right-10" for="sgpb-countdown-repetitive-timer">
						<?php _e('Repeat after timout', SG_POPUP_TEXT_DOMAIN)?>:
					</label>

					<div class="sgpb-onOffSwitch">
						<input type="checkbox" id="sgpb-countdown-repetitive-timer" class="js-checkbox-accordion sgpb-onOffSwitch-checkbox"
						       name="sgpb-countdown-repetitive-timer" <?php echo $popupTypeObj->getOptionValue('sgpb-countdown-repetitive-timer'); ?>>
						<label class="sgpb-onOffSwitch__label" for="sgpb-countdown-repetitive-timer">
							<span class="sgpb-onOffSwitch-inner"></span>
							<span class="sgpb-onOffSwitch-switch"></span>
						</label>
					</div>
					<input type="hidden" id="sgpb-countdown-repetitive-seconds" name="sgpb-countdown-repetitive-seconds" value="<?php echo $popupTypeObj->getOptionValue('sgpb-countdown-repetitive-seconds'); ?>">
					<div class="question-mark">B</div>
					<div class="sgpb-info-wrapper">
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e('Use this option to repeat the countdown after ending the previous one', SG_POPUP_TEXT_DOMAIN)?>.
						</span>
					</div>
				</div>
			</div>
			<div class="formItem">
				<label class="formItem__title">
					<?php _e('Countdown format', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<?php echo AdminHelper::createSelectBox($defaultData['countdownFormat'], esc_html($popupTypeObj->getOptionValue('sgpb-countdown-type')), array('name' => 'sgpb-countdown-type', 'class'=>'js-sg-select2')); ?>
			</div>
			<div class="formItem">
				<label class="formItem__title">
					<?php _e('Timezone', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<?php echo AdminHelper::createSelectBox($defaultData['countdownTimezone'], esc_html($popupTypeObj->getOptionValue('sgpb-countdown-timezone')), array('name' => 'sgpb-countdown-timezone', 'class'=>'js-sg-select2')); ?>
				<span class="sgpb-info-span sgpb-margin-left-10"><?php _e('Note: please make sure you have selected the correct time zone for your popup.', SG_POPUP_TEXT_DOMAIN)?></span>
			</div>
			<div class="formItem">
				<label class="formItem__title">
					<?php _e('Select language', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<?php echo AdminHelper::createSelectBox($defaultData['countdownLanguage'], esc_html($popupTypeObj->getOptionValue('sgpb-countdown-language')), array('name' => 'sgpb-countdown-language', 'class'=>'js-sg-select2')); ?>
			</div>
			<div class="formItem">
				<label class="formItem__title">
					<?php _e('Countdown Location', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="sgpb-onOffSwitch">
					<input type="checkbox" id="popup-countdown" class="js-checkbox-accordion sgpb-onOffSwitch-checkbox"
					       name="sgpb-countdown-location"
						<?php echo ($popupTypeObj->getOptionValue('sgpb-countdown-show-on-top') || $popupTypeObj->getOptionValue('sgpb-countdown-location')) ? ' checked' : ''?>>
					<label class="sgpb-onOffSwitch__label" for="popup-countdown">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="row sg-full-width form-group">
				<div class="col-md-5"></div>
				<div class="col-md-6">
					<div class="fixed-wrapper">
						<div class="countdown-position-top">
							<div class="js-countdown-position-style" id="countdown-position1" data-sgvalue="<?php echo SG_COUNTDOWN_COUNTER_LOCATION_TOP_LEFT; ?>"></div>
							<div class="js-countdown-position-style" id="countdown-position2" data-sgvalue="<?php echo SG_COUNTDOWN_COUNTER_LOCATION_TOP; ?>"></div>
							<div class="js-countdown-position-style" id="countdown-position3" data-sgvalue="<?php echo SG_COUNTDOWN_COUNTER_LOCATION_TOP_RIGHT; ?>"></div>
						</div>
						<div class="countdown-position-bottom">
							<div class="js-countdown-position-style" id="countdown-position4" data-sgvalue="<?php echo SG_COUNTDOWN_COUNTER_LOCATION_BOTTOM; ?>"></div>
						</div>
					</div>
					<input type="hidden" name="sgpb-countdown-fixed-position" class="js-countdown-position" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-countdown-fixed-position'));?>">
				</div>
			</div>

			<?php
				echo CountdownAdminHelper::renderCountdownStyles($args);
			?>
		</div>
	</div>

</div>
