function SGPBAgeVerification()
{

}

SGPBAgeVerification.cookieName = 'SGPBAgeverificationCookie';

SGPBAgeVerification.prototype.init = function()
{
	var verificationBtn = jQuery('.js-age-verification-submit-btn');

	if (!verificationBtn.length) {
		return false;
	}
	var that = this;

	verificationBtn.each(function () {
		var currentId = jQuery(this).data('id');
		var options = jQuery(this).data('options');
		that.actionButton(jQuery(this), options, currentId);
	});
};

SGPBAgeVerification.prototype.allowToOpen = function(id)
{
	var canBeOpened = true;
	var cookieObject = SGPopup.getCookie(SGPBAgeVerification.cookieName + id);
	var SgpbAgeRestrictionParams = eval('SgpbAgeVerificationParams' + id);
	jQuery(window).bind('sgpbDidOpen', function() {
		jQuery('html, body').addClass('sgpb-overflow-hidden');
	});
	if (cookieObject == '') {
		return canBeOpened;
	}
	var currentCookie = JSON.parse(cookieObject);
	if (typeof currentCookie === 'undefined') {
		return canBeOpened;
	}
	/* find current page */
	if (SgpbAgeRestrictionParams.cookieLevel) {
		var currentUrl = window.location.href;
		if (currentCookie.length && currentCookie.indexOf(currentUrl) != -1) {
			canBeOpened = false;
		}
	}
	/* else, if no page cookie level set */
	else {
		canBeOpened = false;
	}

	return canBeOpened;
};


SGPBAgeVerification.prototype.actionButton = function(element, options, currentId)
{
	var that = this;
	var errorMessgae = jQuery('.sgpb-age-verification-error-message');
	var requiredAgeError = jQuery('.sgpb-age-verification-required-age-error');

	element.parents('.sgpb-popup-builder-content-html').find('.js-age-verification-restriction-submit-btn').bind('click', function () {
		setTimeout(function () {
			window.location.href = options['exitURL'];
		}, 0);
	});

	element.bind('click', function(e) {
		e.preventDefault();
		errorMessgae.addClass('sg-hide-element');
		requiredAgeError.addClass('sg-hide-element');

		var day = jQuery('.sgpb-varification-days').val();
		var month = jQuery('.sgpb-varification-months').val();
		var year = jQuery('.sgpb-varification-years').val();
		var isValidDate = function(d) {
			return d instanceof Date && !isNaN(d);
		};
		var userDate = new Date(month+'/'+day+'/'+year);
		if (!isValidDate(userDate) || !day || !month || !year) {
			errorMessgae.removeClass('sg-hide-element');
			return false;
		}
		var currentDate = new Date();
		var yearsDiff = that.diffYears(userDate, currentDate);

		/* when user age less than the requried age */
		if (yearsDiff < options['requiredAge']) {
			var lockoutCount = parseInt(options['lockoutCount']);
			var currentTriedCount = jQuery(element).data('count');

			if (currentTriedCount < lockoutCount) {
				jQuery(element).data('count', ++currentTriedCount);
				requiredAgeError.removeClass('sg-hide-element');
			}
			else {
				window.location.href = options['exitURL'];
			}
		}
		else {
			// close popup and set cookie
			setTimeout(function() {
				that.setVerificationCookie(options, currentId);
				SGPBPopup.closePopupById(currentId);
				jQuery('html, body').removeClass('sgpb-overflow-hidden');
			}, 500);
		}
	});
};

SGPBAgeVerification.prototype.setVerificationCookie = function(options, currentId)
{
	var pageCookieLevel = options.cookieLevel;
	var expTime = parseInt(options.expirationTime);
	var saveChoice = options.saveChoice;
	var currentUrl = window.location.href;
	var cookieName = SGPBAgeVerification.cookieName + currentId;

	if (saveChoice) {
		/* for the first time */
		if (SGPopup.getCookie(cookieName) == '') {
			var cookieObject = [];
			cookieObject.push(currentUrl);
			SGPBPopup.setCookie(cookieName, JSON.stringify(cookieObject), expTime);
		}
		else {
			var cookieObject = SGPopup.getCookie(cookieName);
			var currentPopupCookieObject = JSON.parse(cookieObject);
			if (pageCookieLevel) {
				if (currentPopupCookieObject.length && currentPopupCookieObject.indexOf(currentUrl) == -1) {
					currentPopupCookieObject.push(currentUrl);
				}
				SGPBPopup.setCookie(cookieName, JSON.stringify(currentPopupCookieObject), expTime);
			}
			else {
				if (typeof currentPopupCookieObject != 'undefined') {
					currentPopupCookieObject.push(currentUrl);
					SGPBPopup.setCookie(cookieName, JSON.stringify(cookieObject), expTime);
				}
			}
		}
	}
};

SGPBAgeVerification.prototype.diffYears = function(dt2, dt1)
{

	var diff =(dt2.getTime() - dt1.getTime()) / 1000;
	diff /= (60 * 60 * 24);

	return Math.abs(Math.round(diff/365.25));
};

jQuery(document).ready(function () {
	var obj = new SGPBAgeVerification();
	obj.init();
});
