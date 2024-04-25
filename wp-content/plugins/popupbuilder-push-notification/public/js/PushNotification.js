function SGPBPushNotification()
{
	this.SgpbPushNotificationParams = [];
	this.alreadySubscribedExpirationDate = 365;
	this.expiryTime = 365;
	this.publicKey = '';
	this.isSubscribed = false;
	this.swRegistration = null;
	this.popupId;
	this.allowCookieName = 'SGPBPushNotificationAllow';
	this.disallowCookieName = 'sgpbPushNotificationDisallow';
}

SGPBPushNotification.prototype.urlB64ToUint8Array =  function(base64String)
{
	var padding = '='.repeat((4-base64String.length%4)%4);
	var base64 = (base64String + padding)
		.replace(/\-/g, '+')
		.replace(/_/g, '/');

	var rawData = window.atob(base64);
	var outputArray = new Uint8Array(rawData.length);

	for(var i = 0; i < rawData.length; ++i) {
		outputArray[i] = rawData.charCodeAt(i);
	}

	return outputArray;
};

SGPBPushNotification.prototype.init = function()
{
	var that = this;
	that.declineButtonInit();
	that.confirmButtonInit();
	that.publicKey = SGPB_PUSH_NOTIFICATION.publicKey;
};

SGPBPushNotification.prototype.allowToOpen = function(id)
{
	var canBeOpened = true;
	var that = this;
	/*Service Worker Register*/
	if ('serviceWorker' in navigator && 'PushManager' in window) {
		navigator.serviceWorker.register(SGPB_PUSH_NOTIFICATION.jsUrl+'PushNotificationWorker.js')
			.then(function(swReg) {
				that.swRegistration = swReg;
				that.popupListener(id);
			})
			.catch(function(error) {
			});
	}
	else {
		canBeOpened = false;

		return canBeOpened;
	}

	var cookieObject = SGPopup.getCookie('sgpbPushNotification' + id);
	var SgpbPushNotificationParams = eval('SgpbPushNotificationParams' + id);

	if (SGPopup.getCookie(this.allowCookieName + id)) {
		return false;
	}

	if (cookieObject == '') {
		return canBeOpened;
	}
	var currentCookie = JSON.parse(cookieObject);
	if (typeof currentCookie === 'undefined') {
		return canBeOpened;
	}
	/* find current page */
	if (SgpbPushNotificationParams.cookieLevel) {
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

SGPBPushNotification.prototype.confirmButtonInit = function()
{
	var id = this.SgpbPushNotificationParams.popupId;
	var that = this;
	/*
	 * if confirmed, disable popup and don't show until expiration time
	 * for current page if pageLevelCookie checked, else for all site
	 */
	jQuery('.sgpb-content-'+id+' #sgpb-allow-button').unbind().bind('click', function(e) {
		e.preventDefault();
		that.popupId = jQuery(this).data('id');
		SGPBPopup.closePopupById(that.popupId);
		that.processToSubscribe();
		that.setAllowCookie(that.popupId);
	});
};

SGPBPushNotification.prototype.setAllowCookie = function(popupId)
{
	var expiryTime = this.expiryTime;
	var cookieName = this.allowCookieName + popupId;

	if (SGPopup.getCookie(cookieName) == '') {
		SGPBPopup.setCookie(cookieName, 1, expiryTime);
	}
};

SGPBPushNotification.prototype.processToSubscribe = function()
{
	var subscribedCookieName = this.disallowCookieName + this.popupId;

	if (SGPopup.getCookie(subscribedCookieName) == '') {
		this.subscribeUser();
	}
	else {
		this.unsubscribeUser();
	}
};

SGPBPushNotification.prototype.subscribeUser = function()
{
	var that = this;
	var applicationServerKey = this.urlB64ToUint8Array(this.publicKey);
	this.swRegistration.pushManager.subscribe({
		userVisibleOnly: true,
		applicationServerKey: applicationServerKey
	}).then(function(subscription) {
		that.updateSubscriptionOnServer(subscription);
		that.isSubscribed = true;
	})
	.catch(function(err) {
		console.log(err);
	});
};

SGPBPushNotification.prototype.unsubscribeUser = function()
{
	/*
	 *TO DO
	 * unsubscribe logic here
	 */
};

SGPBPushNotification.prototype.updateSubscriptionOnServer = function(subscription)
{
	if (subscription) {
		var key = subscription.getKey('p256dh');
		var token = subscription.getKey('auth');
		var data = {
			action: 'sgpb_notification_register',
			nonce: SGPB_JS_PARAMS.nonce,
			popupId: this.popupId,
			key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('p256dh')))) : null,
			token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('auth')))) : null,
			endpoint: subscription.endpoint,
			browserName: this.getBrowserName(),
			type: 'subscribe'
		};
		jQuery.ajax(
			{
				type: 'POST',
				dataType: 'json',
				url: SGPB_JS_PARAMS.ajaxUrl,
				data: data,
				success: function (response) {
					console.log(response);
				}
			}
		);
	}
};

SGPBPushNotification.prototype.getBrowserName = function()
{
	var browser = '';

	if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
		browser = 'Opera';
	}
	else if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
		browser = 'MSIE';
	}
	else if (/Navigator[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
		browser = 'Netscape';
	}
	else if (/Chrome[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
		browser = 'Chrome';
	}
	else if (/Safari[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
		browser = 'Safari';
	}
	else if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
		browser = 'Firefox';
	}

	return browser;
};

SGPBPushNotification.prototype.setCookie = function()
{
	var id = this.SgpbPushNotificationParams.popupId;
	var pageCookieLevel = this.SgpbPushNotificationParams.cookieLevel;
	var expTime = parseInt(this.SgpbPushNotificationParams.expirationTime);
	var saveChoice = this.SgpbPushNotificationParams.saveChoice;
	var currentUrl = location.href;
	var cookieName = 'sgpbPushNotification' + id;
	var subscribedCookieName = this.disallowCookieName + id;

	if ((SGPopup.getCookie(subscribedCookieName) == '')) {
		SGPBPopup.setCookie(subscribedCookieName, 1, this.alreadySubscribedExpirationDate);
	}

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

	jQuery('html').css({overflow: 'inherit'});
	SGPBPopup.closePopupById(id);
};

SGPBPushNotification.prototype.declineButtonInit = function()
{
	var that = this;
	var SgpbPushNotificationParams = this.SgpbPushNotificationParams;
	var id = SgpbPushNotificationParams.popupId;

	jQuery('#sgpb-popup-dialog-main-div #sgpb-disallow-button').click(function(e) {
		e.preventDefault();
		jQuery('html').css({overflow: 'inherit'});
		SGPBPopup.closePopupById(id);
		/*
		for the future
		if (SgpbPushNotificationParams.restrictionUrl == '') {
			SGPBPopup.closePopupById(id);
		}
		else {
			window.location.href = SgpbPushNotificationParams.restrictionUrl;
		}
		*/
		that.setCookie();
	});
};

SGPBPushNotification.prototype.popupListener = function(popupId)
{
	var that = this;
	try {
		var options = SGPBPopup.getPopupOptionsById(popupId);
		var SgpbPushNotificationParams = eval('SgpbPushNotificationParams' + popupId);
		if (options['sgpb-type'] == SgpbPushNotificationParams.pushNotificationType) {
			that.SgpbPushNotificationParams = SgpbPushNotificationParams;
			that.init();
		}
	}
	catch (e) {}
};
