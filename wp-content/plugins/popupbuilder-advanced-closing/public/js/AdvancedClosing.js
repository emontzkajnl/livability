function AdvancedClosing()
{
	this.init();
}

AdvancedClosing.prototype.init = function()
{
	var that = this;
	sgAddEvent(window, 'sgpbDidOpen', function(e) {
		var args = e.detail;
		var popupId = parseInt(args['popupId']);
		var popupData = args.popupData;

		var showCountdown = SGPBPopup.varToBool(popupData['sgpb-add-closing-countdown']);
		var autoCloseStatus = SGPBPopup.varToBool(popupData['sgpb-auto-close']);

		if (autoCloseStatus) {
			var autoCloseTime = parseInt(popupData['sgpb-auto-close-time'])*1000;
			if (showCountdown) {
				jQuery('#sg-popup-content-wrapper-'+popupId).prepend('<span id="sgpb-auto-close-timer-'+popupId+'" class="sgpb-auto-close-timer"></span>');
				var style = that.getCountdownStyles(popupId, popupData);
				jQuery('#sgpb-auto-close-timer-'+popupId).css(style);
				function startTimer(duration, display) {
					var timer = duration, minutes, seconds;
					var durationInterval = setInterval(function () {
						minutes = parseInt(timer / 60, 10);
						seconds = parseInt(timer % 60, 10);
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;
						if (showCountdown) {
							display.textContent = minutes + ':' + seconds;
						}
						if (--timer < 0) {
							clearInterval(durationInterval);
						}
					}, 1000);
				}
				var durationMinutes = parseInt(popupData['sgpb-auto-close-time'])-1,
					display = document.querySelector('#sgpb-auto-close-timer-'+popupId);
				startTimer(durationMinutes, display);
			}
			setTimeout(function() {
				SGPBPopup.closePopupById(popupId);
			}, autoCloseTime);
		}
		var pageScroll = SGPBPopup.varToBool(popupData['sgpb-close-after-page-scroll']);
		if (pageScroll) {
			jQuery(window).on('scroll', function() {
				setTimeout(function() {
					SGPBPopup.closePopupById(popupId);
				}, 500);
			});
		}
	});
};

AdvancedClosing.prototype.getCountdownStyles = function(popupId, popupData)
{
	var countdownPosition = popupData['sgpb-closing-countdown-position'];
	var positionRight = popupData['sgpb-closing-countdown-position-right'];
	var positionTop = popupData['sgpb-closing-countdown-position-top'];

	var digitsColor = popupData['sgpb-closing-countdown-digits-color'];
	var countdownBgColor = popupData['sgpb-closing-countdown-bg-color'];

	var styleObj = {};

	if (countdownPosition.indexOf('Right') >= 0) {
		styleObj['right'] = positionRight + 'px';
	}
	else if (countdownPosition.indexOf('Left') != -1) {
		styleObj['left'] = positionRight + 'px';
	}

	if (countdownPosition.indexOf('top') != -1) {
		styleObj['top'] = positionTop + 'px';
	}
	else if (countdownPosition.indexOf('bottom') != -1) {
		styleObj['bottom'] = positionTop + 'px';
	}

	styleObj['color'] = digitsColor;
	styleObj['background-color'] = countdownBgColor;

	return styleObj;
};

jQuery(document).ready(function() {
	new AdvancedClosing();
});
