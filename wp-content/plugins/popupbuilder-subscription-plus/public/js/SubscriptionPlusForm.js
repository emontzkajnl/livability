function SGPBSubscriptionPlus()
{
	this.init();
}

function SGPBSubscription(){}

SGPBSubscriptionPlus.prototype.init = function()
{
	this.formSubmission();
};

SGPBSubscriptionPlus.cookieName = 'SGPBSubscription';

SGPBSubscriptionPlus.prototype.formSubmission = function ()
{
	var subscriptionPlusForms = jQuery('form.sgpb-subscription-plus-form');

	if (!subscriptionPlusForms.length) {
		return false;
	}
	var that = this;

	subscriptionPlusForms.each(function() {
		var form = jQuery(this);
		var formValidateJson = form.attr('data-validate-json');
		var validateObj = jQuery.parseJSON(formValidateJson);

		var currentSubmitHandler = that.submitHandlers;
		var submitArgs = {submitionForm: form, that: that};
		currentSubmitHandler = currentSubmitHandler.bind(submitArgs);

		validateObj.submitHandler = currentSubmitHandler;

		that.validateMessages();
		form.validate(validateObj);
	});
};

SGPBSubscriptionPlus.prototype.validateMessages = function()
{
	jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			var currentElement = jQuery(element);
			var currentSubscriptionPlusForm = currentElement.parents('.sgpb-subscription-plus-form').first();

			if (currentSubscriptionPlusForm.length) {
				var formId = currentSubscriptionPlusForm.data('popup-id');
				var currentFieldName = currentElement.attr('name');
				if (currentElement.data('field-type') == 'customMultiple') {
					jQuery('.sgpb-subscription-plus-form-'+formId+' [name="'+currentFieldName+'"]').parent().next('.sgpb-custom-field-error-message').html(error);
					return true;
				}

				if (currentFieldName.indexOf('[]') > 0) {
					currentFieldName = currentFieldName.substring(0, currentFieldName.length-2);
				}
				jQuery('.sgpb-subscription-plus-form-'+formId+' .sgpb-form-field-'+currentFieldName+'-error').html(error);
			}
		}
	});
};

SGPBSubscriptionPlus.prototype.submitHandlers = function()
{
	var additionalPopupParams = {};
	var form = this.submitionForm;
	var popupId = jQuery(form).data('popup-id');
	var popupOptions = SGPBPopup.getPopupOptionsById(popupId);
	var formSerializedData = form.serialize();
	var firstNameValue = '';
	var lastNameValue = '';
	var firstNameInput = jQuery('.sgpb-subscription-plus-form-'+popupId+' .sgpb-field-firstname-wrapper input').first();
	var lastNameInput = jQuery('.sgpb-subscription-plus-form-'+popupId+' .sgpb-field-lastname-wrapper input').first();
	var submitButton = jQuery('.sgpb-subscription-plus-form-'+popupId+' .sgpb-field-submit-wrapper input').first();
	var emailValue = jQuery('.sgpb-subscription-plus-form-'+popupId+' .sgpb-field-email-wrapper input').first().val();

	var that = this.that;
	if (firstNameInput.length) {
		firstNameValue = firstNameInput.val();
	}
	if (firstNameInput.length) {
		lastNameValue = lastNameInput.val();
	}
	var data = {
		action: 'sgpb_subscription_plus_subscription',
		nonce: SGPB_JS_PARAMS.nonce,
		beforeSend: function () {
			submitButton.prop('disabled', true);
			submitButton.val(submitButton.data('progress-title'));
			if (popupOptions['sgpb-subs-success-behavior'] == 'redirectToURL' && popupOptions['sgpb-subs-success-redirect-new-tab']) {
				that.newWindow = window.open(popupOptions['sgpb-subs-success-redirect-URL']);
			}
		},
		registerUser: popupOptions['sgpb-subs-register-user'],
		formData: formSerializedData,
		popupPostId: popupId,
		firstNameValue: firstNameValue,
		lastNameValue: lastNameValue,
		emailValue: emailValue
	};

	jQuery.post(SGPB_JS_PARAMS.ajaxUrl, data, function(res) {

		jQuery('.sgpb-form-'+popupId+'-wrapper .subs-form-messages').addClass('sg-hide-element');
		that.submissionPopupId = popupId;
		submitButton.val(submitButton.data('title'));
		submitButton.prop('disabled', false);

		additionalPopupParams['res'] = res;
		that.showMessages(additionalPopupParams, data);
	});
};

SGPBSubscriptionPlus.prototype.showMessages = function(res, data)
{
	/*When successfully subscribed*/
	if (res['res'] == 1) {
		this.subscriptionSuccessBehavior();
		this.processAfterSuccessfulSubmission(data);
	}
	else {
		if (this.newWindow != null) {
			this.newWindow.close();
		}

		this.showErrorMessage();
	}

	/*After subscription it's will be call reposition of popup*/
	window.dispatchEvent(new Event('resize'));
	return true;
};

SGPBSubscriptionPlus.prototype.processAfterSuccessfulSubmission = function(ajaxData)
{
	if (jQuery.isEmptyObject(ajaxData)) {
		return false;
	}
	ajaxData.action = 'sgpb_process_after_submission';
	jQuery.post(SGPB_JS_PARAMS.ajaxUrl, ajaxData, function (res) {

	})
}

SGPBSubscriptionPlus.prototype.subscriptionSuccessBehavior = function()
{
	var settings = {
		popupId: this.submissionPopupId,
		eventName: 'sgpbSubscriptionSuccess'
	};

	jQuery(window).trigger('sgpbFormSuccess', settings);

	var popupId = parseInt(this.submissionPopupId);
	var popupOptions = SGPBPopup.getPopupOptionsById(popupId);
	var behavior = 'showMessage';
	jQuery('.sgpb-form-'+popupId+'-wrapper form').remove();

	if (typeof popupOptions['sgpb-subs-hide-subs-users'] != 'undefined') {
	       this.setSubscriptionCookie(popupId);
	}
	if (typeof popupOptions['sgpb-subs-success-behavior'] != 'undefined') {
		behavior = popupOptions['sgpb-subs-success-behavior'];
	}
	this.resetFieldsValues();

	switch (behavior) {
		case 'showMessage':
			jQuery('.sgpb-form-'+popupId+'-wrapper .sgpb-alert-success').removeClass('sg-hide-element');
			break;
		case 'redirectToURL':
			this.redirectToURL(popupOptions);
			break;
		case 'openPopup':
			this.openSuccessPopup(popupOptions);
			break;
		case 'hidePopup':
			SGPBPopup.closePopupById(this.submissionPopupId);
			break;
	}
};

SGPBSubscriptionPlus.prototype.redirectToURL = function(popupOptions)
{
	var redirectURL = popupOptions['sgpb-subs-success-redirect-URL'];
	var redirectToNewTab = popupOptions['sgpb-subs-success-redirect-new-tab'];
	SGPBPopup.closePopupById(this.submissionPopupId);

	if (redirectToNewTab) {
		return true;
	}

	window.location.href = redirectURL;
};

SGPBSubscriptionPlus.prototype.openSuccessPopup = function(popupOptions)
{
	var that = this;

	/*We did this so that the "close" event works*/
	setTimeout(function() {
		SGPBPopup.closePopupById(that.submissionPopupId);
	}, 0);

	if (typeof popupOptions['sgpb-subs-success-popup'] != 'undefined') {
		sgAddEvent(window, 'sgpbDidClose', this.openPopup(popupOptions));
	}
};

SGPBSubscriptionPlus.prototype.showErrorMessage = function()
{
	var popupId = parseInt(this.submissionPopupId);
	jQuery('.sgpb-form-'+popupId+'-wrapper .sgpb-alert-danger').removeClass('sg-hide-element');
};

SGPBSubscriptionPlus.prototype.resetFieldsValues = function()
{
	if (!jQuery('.js-subs-text-inputs').length) {
		return false;
	}

	jQuery('.js-subs-text-inputs').each(function() {
		jQuery(this).val('');
	});
};

SGPBSubscriptionPlus.prototype.setSubscriptionCookie = function(popupId)
{
	var currentUrl = window.location.href;
	var cookieName = SGPBSubscriptionPlus.cookieName + popupId;
	var expiryTime = this.expiryTime;

	if (SGPopup.getCookie(cookieName) == '') {
		var cookieObject = [currentUrl];
		SGPBPopup.setCookie(cookieName, JSON.stringify(cookieObject), expiryTime);
	}
};

SGPBSubscriptionPlus.prototype.openPopup = function(popupOptions)
{
	if (typeof popupOptions['sgpb-subs-success-popup'] == 'undefined') {
		return false;
	}

	var subPopupId = parseInt(popupOptions['sgpb-subs-success-popup']);
	var subPopupOptions = SGPBPopup.getPopupOptionsById(subPopupId);

	var popupObj = new SGPBPopup();
	popupObj.setPopupId(subPopupId);
	popupObj.setPopupData(subPopupOptions);
	setTimeout(function() {
		popupObj.prepareOpen();
	}, 500);
};

SGPBSubscription.prototype.allowToOpen = function(popupId)
{
	var allowStatus = true;
	var cookieName = SGPBSubscriptionPlus.cookieName + popupId;

	if (SGPopup.getCookie(cookieName) != '') {
		allowStatus = false;
	}

	return allowStatus;
};

jQuery('document').ready(function() {
	new SGPBSubscriptionPlus();
});
