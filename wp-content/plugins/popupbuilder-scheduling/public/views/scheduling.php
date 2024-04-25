<?php
namespace sgpb;
use sgpb\PopupBuilderActivePackage;

$defaultData = \ConfigDataHelper::defaultData();
$required = '';
if ($popupTypeObj->getOptionValue('sgpb-schedule-status')) {
	$required = 'required';
}

?>
<div class="sgpb ">
	<div class="formItem sgpb-scheduling sgpb-wrapper">
		<div class="sgpb-width-100">
			<div class="form-group formItem">
				<label class="formItem__title">
					<?php _e( 'Schedule', SG_POPUP_TEXT_DOMAIN ); ?>:
				</label>
				<div class="col-md-6 sgpb-align-item-center sgpb-display-inline-flex">
					<div class="sgpb-onOffSwitch">
						<input type="checkbox" id="schedule-status" class="js-checkbox-accordion sgpb-onOffSwitch-checkbox"
						       name="sgpb-schedule-status" <?php echo $popupTypeObj->getOptionValue( 'sgpb-schedule-status' ); ?>>
						<label class="sgpb-onOffSwitch__label" for="schedule-status">
							<span class="sgpb-onOffSwitch-inner"></span>
							<span class="sgpb-onOffSwitch-switch"></span>
						</label>
					</div>

					<div class="question-mark sgpb-info-icon">B</div>
					<div class="sgpb-info-wrapper">
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e( 'Select the day(s) of the week and specific time during which the popup will be shown. Popup will be scheduled with your WordPress timezone.', SG_POPUP_TEXT_DOMAIN ) ?>
						</span>
					</div>
				</div>
			</div>
			<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-20">
				<div class="subForm">
					<div class="formItem">
						<label class="subFormItem__title sgpb-margin-right-20">
							<?php _e( 'Every', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div>
							<?php echo AdminHelper::createSelectBox( $defaultData['weekDaysArray'], $popupTypeObj->getOptionValue( 'sgpb-schedule-weeks' ), array( 'name'     => 'sgpb-schedule-weeks[]',
							                                                                                                                                       'class'    => 'schedule-start-selectbox sg-margin0 js-select-basic js-sg-select2',
							                                                                                                                                       'multiple' => 'multiple',
							                                                                                                                                       'size'     => 7,
							                                                                                                                                       $required  => $required
							) ); ?>
						</div>
					</div>
					<div class="formItem">
						<div class="formItem  sgpb-position-relative">
							<span class="subFormItem__title sgpb-margin-right-20"><?php _e( 'From', SG_POPUP_TEXT_DOMAIN ); ?>:</span>
							<input id="sgpb-schedule-start-time" type="text"
							       class="sg-time-picker sg-time-picker-style sgpb-full-width-events"
							       name="sgpb-schedule-start-time"
							       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-schedule-start-time' ) ); ?>">
							<span class="sgpb-timePicker-icon">
								<span></span>
								<span></span>
							</span>
						</div>
						<div class="formItem sgpb-position-relative">
							<span class="subFormItem__title sgpb-margin-x-10"><?php _e( 'To', SG_POPUP_TEXT_DOMAIN ); ?>:</span>
							<input id="sgpb-schedule-end-time" type="text"
							       class="sg-time-picker sg-time-picker-style sgpb-full-width-events"
							       name="sgpb-schedule-end-time"
							       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-schedule-end-time' ) ); ?>">
							<span class="sgpb-timePicker-icon">
								<span></span>
								<span></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group formItem">
				<label class="formItem__title">
					<?php _e( 'Show popup in date range', SG_POPUP_TEXT_DOMAIN ) ?>:
				</label>
				<div class="col-md-7 sgpb-align-item-center sgpb-display-inline-flex">
					<div class="sgpb-onOffSwitch">
						<input type="checkbox" name="sgpb-popup-timer-status" id="sgpb-popup-timer-status"
						       class="js-checkbox-accordion sgpb-onOffSwitch-checkbox" <?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-popup-timer-status' ) ); ?>>
						<label class="sgpb-onOffSwitch__label" for="sgpb-popup-timer-status">
							<span class="sgpb-onOffSwitch-inner"></span>
							<span class="sgpb-onOffSwitch-switch"></span>
						</label>
					</div>
					<div class="question-mark sgpb-info-icon">B</div>
					<div class="sgpb-info-wrapper">
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e( 'Specify date and hours for the start and end of popup showing. Popup will be scheduled with your WordPress timezone.', SG_POPUP_TEXT_DOMAIN ) ?>
						</span>
					</div>
				</div>
			</div>
			<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-20">
				<div class="formItem">
					<div class="formItem sgpb-position-relative">
						<label class="subFormItem__title sgpb-margin-right-20" for="sgpb-popup-start-timer">
							<?php _e( 'Start date', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="text" class="popup-start-timer sgpb-dateTimePicker-icon"
						       id="sgpb-popup-start-timer" name="sgpb-popup-start-timer"
						       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-popup-start-timer' ) ); ?>">

					</div>
					<div class="formItem sgpb-position-relative">
						<label class="subFormItem__title sgpb-margin-x-20" for="sgpb-popup-end-timer">
							<?php _e( 'End date', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="text" class="popup-start-timer sgpb-dateTimePicker-icon"
						       id="sgpb-popup-end-timer" name="sgpb-popup-end-timer"
						       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-popup-end-timer' ) ); ?>">

					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<style type="text/css">
	#ui-datepicker-div {
		z-index: 9999 !important;
	}
</style>
