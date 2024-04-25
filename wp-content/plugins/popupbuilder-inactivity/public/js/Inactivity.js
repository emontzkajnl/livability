SgpbEventListener.prototype.sgpbInactivity = function(listenerObj, eventData)
{
	var that = this;
	var popupId = 1;
	var timeout = parseInt(eventData.value);
	timeout = timeout*1000;

	var idleInterval = setInterval(function() {listenerObj.timerIncrement(listenerObj, idleInterval) }, timeout);

	jQuery(window).mousemove(function(e) {
		SgpbEventListener.inactivityIdicator++;
	});
	jQuery(window).keypress(function(e) {
		SgpbEventListener.inactivityIdicator++;
	});

	window.addEventListener("touchstart", handlerFunction, false);
	function handlerFunction(event) {
		SgpbEventListener.inactivityIdicator++;
	}
};