function SGPBScheduling ()
{
	this.timePicker();
	this.fullTimePicker();
}

SGPBScheduling.prototype.timePicker = function()
{
	if (jQuery('.sg-time-picker').length == 0) {
		return;
	}
	jQuery('.sg-time-picker').datetimepicker({
		datepicker:false,
		format:'H:i'
	});
};

SGPBScheduling.prototype.fullTimePicker = function()
{
	var startTimerOptions = {
		format:'M d y H:i',
		minDate: 0
	};
	var finishTimerOptions = {
		format:'M d y H:i',
		minDate: 0
	};

	/*  for escape javascript errors if element does not exist */
	if (jQuery('.popup-start-timer').length == 0) {
		return;
	}

	var startCalendar = jQuery('.popup-start-timer').datetimepicker(startTimerOptions);
	var finishCalendar = jQuery('.popup-finish-timer').datetimepicker(finishTimerOptions);

	/* Detect start change for disable finish date before current start date */
	startCalendar.change(function() {
		/* Current start date */
		var currentStartDate = jQuery(this).val();
		/*Start date to UTC for for minDate */
		var startDate = new Date(currentStartDate);

		var finishTimerOptions = {
			format:'M d y H:i',
			minDate: startDate
		};
		/*Change finish minimum date disabel days */
		jQuery('.popup-finish-timer').datetimepicker(finishTimerOptions)
	});
};

jQuery(document).ready(function() {
	$schedulingObj = new SGPBScheduling();
});
