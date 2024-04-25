SgpbEventListener.prototype.sgpbOnScroll = function(listenerObj, eventData)
{
	var scrollValue = eventData['value'];
	var operator = eventData['operator'];

	if (operator == 'scrollDistance') {
		var scrollIntValue = parseInt(scrollValue);
		var showAfter = scrollIntValue;
		var docHeight = jQuery(document).height();
		var winHeight = jQuery(window).height();

		var height = (docHeight - winHeight);
		if (scrollValue.indexOf('%') != '-1') {
			showAfter = (height*scrollIntValue)/100;
		}
		if (scrollValue.indexOf('em') != '-1') {
			scrollIntValue = parseFloat(eventData['value']);
			var bodyFontSize = jQuery("body").css('font-size');
			showAfter = parseInt(bodyFontSize)*scrollIntValue;
		}
		if (scrollValue.indexOf('rem') != '-1') {
			scrollIntValue = parseFloat(eventData['value']);
			var constOfRem = 16;
			showAfter = parseInt(constOfRem)*scrollIntValue;
		}
	}
	else if (operator == 'scrollTop') {
		var lastScrollTop = 0;
		window.addEventListener('scroll', function() {
		   var currentStatement = window.pageYOffset || document.documentElement.scrollTop;
		   if (currentStatement < lastScrollTop) {
			  if (currentStatement == 0) {
			  	listenerObj.getPopupObj().prepareOpen();
			  }
		   }
		   lastScrollTop = currentStatement <= 0 ? 0 : currentStatement;
		}, false);
	}
	else {
		showAfter = 0;
		var element = jQuery(eventData['value']);
		var elementPosition = element.position();
		var elementHeight = element.height();
		if (elementPosition && elementPosition.top) {
			showAfter = elementPosition.top;
		}
		/* open popup when the specified target is in the middle of the window */
		showAfter = showAfter+(elementHeight/2);
	}

	var scrollStatus = false;
	var alreadyOpened = false;
	var windowHeight = jQuery(window).height();
	jQuery(window).on('scroll touchmove', function() {
		setTimeout(function() {
			var scrollTop = jQuery(window).scrollTop();
			var scrollTopWithHeight = scrollTop + windowHeight/2;
			if (!alreadyOpened && (scrollTop <= showAfter && scrollTopWithHeight >= showAfter)) {
				if (scrollStatus == false) {
					listenerObj.getPopupObj().prepareOpen();
					scrollStatus = true;
					alreadyOpened = true;
				}
			}
			/* if the value smaller than the window height, it should be opened on any scroll */
			if ((windowHeight > showAfter) && !alreadyOpened) {
				listenerObj.getPopupObj().prepareOpen();
				scrollStatus = true;
				alreadyOpened = true;
			}
		}, 100);
	});
};
