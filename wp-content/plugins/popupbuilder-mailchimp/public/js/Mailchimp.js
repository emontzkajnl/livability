function sgAddEvent(element, eventName, fn) {
	if (element.addEventListener)
		element.addEventListener(eventName, fn, false);
	else if (element.attachEvent)
		element.attachEvent('on' + eventName, fn);
}

function SGPBMailchimp()
{
	this.id = null;
	this.localizeData = {};
}

SGPBMailchimp.prototype.cookieExpirationDate = 365;
SGPBMailchimp.prototype.cookieName = 'SGPMailChimpPopup';

SGPBMailchimp.prototype.setId = function(id)
{
	this.id = parseInt(id);
};

SGPBMailchimp.prototype.getId = function()
{
	return this.id
};

SGPBMailchimp.prototype.setLocalizeData = function(localizeData)
{
	this.localizeData = localizeData;
};

SGPBMailchimp.prototype.getLocalizeData = function()
{
	return this.localizeData
};

SGPBMailchimp.prototype.init = function()
{
	var id = this.getId();
	var that = this;
	var options = SGPBPopup.getPopupOptionsById(id);
	var doubleOptin = options['sgpb-enable-double-optin'] ? true : false;
	var listId = options['sgpb-mailchimp-lists'];
	var submitButtonTitle = options['sgpb-mailchimp-submit-title'];

	this.validateComplexDataCustomRule(id);
	var localizedData = eval('SGPB_MAILCHIMP_PARAMS_'+id);
	var validateObj = localizedData.validateScript;
	validateObj = JSON.parse(validateObj);

	validateObj.submitHandler = function()
	{
		var submitButton = mailchimpForm.find('.sgpb-embedded-subscribe');
		var formData = mailchimpForm.serialize();
		var popupId = that.getId();

		var data = {
			action: 'sgpb_mailchimp_subscribe',
			nonce: localizedData.nonce,
			listId: listId,
			formData: formData,
			doubleOptin: doubleOptin,
			beforeSend: function() {
				jQuery('.sgpb-popup-builder-content-' + popupId + '.mailchimp-form-messages').addClass('sg-hide-element');
				submitButton.val(localizedData.pleaseWait);
				submitButton.attr('disabled', 'disabled');
				var popupOptions = SGPBPopup.getPopupOptionsById(popupId);
				if (popupOptions['sgpb-mailchimp-success-behavior'] == 'redirectToURL' && popupOptions['sgpb-mailchimp-success-redirect-new-tab']) {
					that.newWindow = window.open(popupOptions['sgpb-mailchimp-success-redirect-URL']);
				}
			}
		};

		jQuery.post(localizedData.ajaxUrl, data, function(responce) {
			var responce = jQuery.parseJSON(responce);
			jQuery('.sgpb-popup-builder-content-'+popupId+' .mailchimp-form-messages').addClass('sg-hide-element');
			submitButton.val(submitButtonTitle);
			submitButton.removeAttr('disabled');

			if (jQuery('.sgpb-content-'+popupId).length) {
				var popupWidthForSuccessMessage = jQuery('.sgpb-content-'+popupId).width();
				popupWidthForSuccessMessage -= 2;
			}
			var additionalPopupParams = {};
			additionalPopupParams['responce'] = responce;
			additionalPopupParams['popupWidth'] = popupWidthForSuccessMessage;
			that.showMessages(additionalPopupParams);
		})
	};
	var mailchimpForm = jQuery('.sgpb-content-'+id+' .sgpb-mailchimp-'+id+' form');
	mailchimpForm.validate(validateObj);
};

SGPBMailchimp.prototype.showMessages = function(additionalPopupParams)
{
	/* When successfully submitted */
	var status = additionalPopupParams['responce']['status'];
	if (status == 200) {
		this.submissionSuccessBehavior(additionalPopupParams['popupWidth']);
	}
	else if (status == 401) {
		this.sgpbMailchimpMemberExists(additionalPopupParams);
	}
	else {
		this.sgpbMailchimpError(additionalPopupParams)
	}

	window.dispatchEvent(new Event('resize'));
};

SGPBMailchimp.prototype.sgpbMailchimpError = function(additionalPopupParams)
{
	var popupId = this.getId();
	jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-alert-error').removeClass('sg-hide-element').css('width', additionalPopupParams['popupWidth']);

	if (this.newWindow != null) {
		this.newWindow.close();
	}
};

SGPBMailchimp.prototype.sgpbMailchimpMemberExists = function (additionalPopupParams)
{
	var popupId = this.getId();
	var popupOptions = SGPBPopup.getPopupOptionsById(popupId);

	var mustClosePopup = popupOptions['sgpb-mailchimp-close-popup-already-subscribed'];
	this.dontShowSubscribedUsers();

	if (!mustClosePopup) {
		this.sgpbMailchimpError(additionalPopupParams);
		return;
	}

	SGPBPopup.closePopupById(popupId);
};

SGPBMailchimp.prototype.submissionSuccessBehavior = function(popupWidth)
{
	var popupId = this.getId();
	var popupOptions = SGPBPopup.getPopupOptionsById(popupId);
	var behavior = 'showMessage';
	jQuery('#sgpb-popup-dialog-main-div .sgpb-mailchimp-'+popupId+' form').addClass('sg-hide-element');

	if (typeof popupOptions['sgpb-mailchimp-success-behavior'] != 'undefined') {
		behavior = popupOptions['sgpb-mailchimp-success-behavior'];
	}

	this.dontShowSubscribedUsers();

	switch (behavior) {
		case 'showMessage':
			jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-alert-success').removeClass('sg-hide-element').css('width', popupWidth);;
			break;
		case 'redirectToURL':
			this.redirectToURL(popupOptions);
			break;
		case 'openPopup':
			this.openSuccessPopup(popupOptions);
			break;
		case 'hidePopup':
			SGPBPopup.closePopupById(popupId);
			break;
	}
};

SGPBMailchimp.prototype.dontShowSubscribedUsers = function()
{
	var popupId = this.getId();
	SGPBPopup.setCookie(this.cookieName + popupId, popupId, this.cookieExpirationDate, false);
};

SGPBMailchimp.prototype.allowToOpen = function(popupId)
{
	var allowStatus = true;
	var cookieName = this.cookieName + popupId;

	if (SGPopup.getCookie(cookieName) != '') {
		allowStatus = false;
	}

	return allowStatus;
};

SGPBMailchimp.prototype.openSuccessPopup = function(popupOptions)
{
	var that = this;
	var popupId = this.getId();

	if (typeof popupOptions['sgpb-mailchimp-success-popup'] != 'undefined') {
		sgAddEvent(window, 'sgpbDidClose', this.openPopup(popupOptions));
	}

	/*We did this so that the "close" event works*/
	setTimeout(function() {
		SGPBPopup.closePopupById(popupId);
	}, 0);
};

SGPBMailchimp.prototype.openPopup = function(popupOptions)
{
	if (typeof popupOptions['sgpb-mailchimp-success-popup'] == 'undefined') {
		return false;
	}
	var subPopupId = parseInt(popupOptions['sgpb-mailchimp-success-popup']);
	var subPopupOptions = SGPBPopup.getPopupOptionsById(subPopupId);

	var popupObj = new SGPBPopup();
	popupObj.setPopupId(subPopupId);
	popupObj.setPopupData(subPopupOptions);
	setTimeout(function() {
		popupObj.prepareOpen();
	}, 500);
};

SGPBMailchimp.prototype.redirectToURL = function(popupOptions)
{
	var popupId = this.getId();
	var redirectURL = popupOptions['sgpb-mailchimp-success-redirect-URL'];
	var redirectToNewTab = popupOptions['sgpb-mailchimp-success-redirect-new-tab'];
	SGPBPopup.closePopupById(popupId);

	if (redirectToNewTab) {
		return true;
	}

	window.location.href = redirectURL;
};

SGPBMailchimp.prototype.validateComplexDataCustomRule = function(popupId)
{
	jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			var errorWrapperClassName = jQuery(element).attr('data-error-message-class');
			jQuery('.sgpb-mailchimp-'+popupId+' .'+errorWrapperClassName).html(error);
		}
	});

	var currentForm = jQuery('.sgpb-mailchimp-'+popupId+' form');
	jQuery.validator.addMethod('complexFieldsValidation', function(value, element) {
		var className = jQuery(element).attr('data-class-name');
		var status = true;
		var validateElements = jQuery('.'+className);
		validateElements = currentForm.find(validateElements);

		if (validateElements.length) {
			validateElements.removeClass('sgpb-validate-message');
			validateElements.each(function() {
				if (jQuery(this).val() == '') {
					status = false;
					validateElements.addClass('sgpb-validate-message');
				}
			})
		}

		return status;
	});

	jQuery.validator.addMethod('numberCustomCheck', function(value, element) {
		var className = jQuery(element).attr('data-class-name');
		var status = true;
		var validateElements = jQuery('.'+className);
		validateElements = currentForm.find(validateElements);

		if (validateElements.length) {
			validateElements.removeClass('sgpb-validate-message');
			validateElements.each(function() {

				if (!isFinite(jQuery(this).val())) {
					status = false;
					validateElements.addClass('sgpb-validate-message');
				}
			})
		}

		return status;
	});
};

sgAddEvent(window, 'sgpbDidOpen', function(e) {
	var args = e.detail;
	var popupId = parseInt(args.popupId);
	var popupData = args.popupData;

	if (popupData['sgpb-type'] != 'mailchimp') {
		return false;
	}

	var mailchimpObj = new SGPBMailchimp();
	mailchimpObj.setId(popupId);
	mailchimpObj.init();
});
