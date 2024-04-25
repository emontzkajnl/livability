function SGPBLogin()
{
	this.submissionPopupId = 0;
}

SGPBLogin.prototype.setSubmissionPopupId = function(popupId)
{
	this.submissionPopupId = popupId;
};

SGPBLogin.prototype.init = function()
{
	var that = this;
	sgAddEvent(window, 'sgpbDidOpen', function(e) {
		var args = e.detail;
		var popupId = parseInt(args.popupId);
		if (parseInt(that.submissionPopupId) == 0) {
			that.setSubmissionPopupId(popupId);
		}

		try {
			eval('SGPB_LOGIN_VALIDATE_JSON_'+popupId);
			var validateObj = eval('SGPB_LOGIN_VALIDATE_JSON_'+popupId);
		}
		catch (e) {
			return false;
		}

		var popupOptions = SGPBPopup.getPopupOptionsById(popupId);
		that.extraActions(popupId, popupOptions);
		validateObj = jQuery.parseJSON(validateObj);
		var additionalPopupParams = {};
		var currentLoginForm = jQuery('.sgpb-login-form-'+popupId+' form');
		var submitButton = currentLoginForm.find('.js-login-submit-btn');

		validateObj.errorPlacement = function(error, element) {
			var placement = jQuery(currentLoginForm).find(element).data('error');
			if (placement) {
				jQuery(placement).append(error)
			}
			else {
				error.insertAfter(element);
			}
	    }
		validateObj.submitHandler = function()
		{
			var userInput = jQuery('.sgpb-login-form-'+popupId+' [data-username]');
			var userPassword = jQuery('.sgpb-login-form-'+popupId+' [data-password]');

			var data = {
				'action': 'sgpb_login_action',
				'nonce': SGPB_JS_PARAMS.nonce,
				'userForm': jQuery('.sgpb-login-form-'+popupId+' form').serialize(),
				'userName': userInput.attr('name'),
				'passwordName': userPassword.attr('name'),
				'beforeSend': function () {
					submitButton.val(submitButton.attr('data-progress-title'));
					if (popupOptions['sgpb-login-success-behavior'] == 'redirectToURL' && popupOptions['sgpb-login-success-redirect-new-tab']) {
						that.newWindow = window.open(popupOptions['sgpb-login-success-redirect-URL']);
					}
				},
			};

			jQuery.post(SGPB_JS_PARAMS.ajaxUrl, data, function(response) {
				/* if set to new tab, after new tab creating, we need to refresh it to make the user logged in */
				if (popupOptions['sgpb-login-success-behavior'] == 'redirectToURL' && popupOptions['sgpb-login-success-redirect-new-tab']) {
					that.newWindow.location.href = popupOptions['sgpb-login-success-redirect-URL'];
				}
				that.submissionPopupId = popupId;
				jQuery('.sgpb-login-form-'+popupId+' .sgpb-alert').addClass('sg-hide-element');
				submitButton.val(submitButton.attr('data-title'));
				additionalPopupParams['res'] = response;
				that.showMessages(additionalPopupParams);
			})
		};

		currentLoginForm.validate(validateObj);
	});
};

SGPBLogin.prototype.extraActions = function(popupId, popupOptions)
{
	var that = this;
	if (SGPB_USER_STATUS.isLoggedIn) {
		jQuery('.sgpb-login-form-'+popupId).find('.sgpb-form').hide();
		jQuery('.sgpb-login-form-'+popupId).find('.sgpb-alert-success').removeClass('sg-hide-element');
		setTimeout(function() {
			/* we are unable to open new tab, because the user action was missed */
			if (popupOptions['sgpb-login-success-behavior'] == 'redirectToURL' && popupOptions['sgpb-login-success-redirect-new-tab']) {
				window.location.href = popupOptions['sgpb-login-success-redirect-URL'];
			}
			that.loginSuccessBehavior();
		}, 1000);
	}
};

SGPBLogin.prototype.showMessages = function(res)
{
	var that = this;
	result = JSON.parse(res['res']);
	/*When successfully login*/
	if (result['status'] == 200) {
		this.loginSuccessBehavior();
	}
	else {
		if (that.newWindow != null) {
			that.newWindow.close();
		}

		this.showErrorMessage(result);
	}

	/*After login it's will be call reposition of popup*/
	window.dispatchEvent(new Event('resize'));
	return true;
};

SGPBLogin.prototype.showErrorMessage = function(result)
{
	var popupId = parseInt(this.submissionPopupId);
	setTimeout(function() {
		if (!jQuery('.sgpb-custom-login-error-message').length) {
			jQuery('.sgpb-login-form-'+popupId+' .sgpb-alert-danger').html(result['message']);
		}
		jQuery('.sgpb-login-form-'+popupId+' .sgpb-alert-danger').removeClass('sg-hide-element');
	}, 500);
};

SGPBLogin.prototype.loginSuccessBehavior = function()
{
	var settings = {
		popupId: this.submissionPopupId,
		eventName: 'sgpbLoginSuccess'
	};

	jQuery(window).trigger('sgpbFormSuccess', settings);

	var popupId = parseInt(this.submissionPopupId);
	var popupOptions = SGPBPopup.getPopupOptionsById(popupId);
	var behavior = 'refresh';
	jQuery('.sgpb-login-form-'+popupId+' form').remove();

	if (typeof popupOptions['sgpb-login-success-behavior'] != 'undefined') {
		behavior = popupOptions['sgpb-login-success-behavior'];
	}
	this.resetFieldsValues();

	switch (behavior) {
		case 'refresh':
			SGPBPopup.closePopupById(this.submissionPopupId);
			location.reload();
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

SGPBLogin.prototype.resetFieldsValues = function()
{
	if (!jQuery('.js-login-text-inputs').length) {
		return false;
	}

	jQuery('.js-login-text-inputs').each(function() {
		jQuery(this).val('');
	});
};

SGPBLogin.prototype.redirectToURL = function(popupOptions)
{
	var redirectURL = popupOptions['sgpb-login-success-redirect-URL'];
	var redirectToNewTab = popupOptions['sgpb-login-success-redirect-new-tab'];
	SGPBPopup.closePopupById(this.submissionPopupId);

	if (redirectToNewTab) {
		return true;
	}

	window.location.href = redirectURL;
};

SGPBLogin.prototype.openSuccessPopup = function(popupOptions)
{
	var that = this;

	/*We did this so that the "close" event works*/
	setTimeout(function() {
		SGPBPopup.closePopupById(that.submissionPopupId);
	}, 0);

	if (typeof popupOptions['sgpb-login-success-popup'] != 'undefined') {
		sgAddEvent(window, 'sgpbDidClose', this.openPopup(popupOptions));
	}
};

SGPBLogin.prototype.openPopup = function(popupOptions)
{
	if (typeof popupOptions['sgpb-login-success-popup'] == 'undefined') {
		return false;
	}
	var subPopupId = parseInt(popupOptions['sgpb-login-success-popup']);
	var subPopupOptions = SGPBPopup.getPopupOptionsById(subPopupId);

	var popupObj = new SGPBPopup();
	popupObj.setPopupId(subPopupId);
	popupObj.setPopupData(subPopupOptions);
	setTimeout(function() {
		popupObj.prepareOpen();
	}, 500);
};

jQuery(document).ready(function() {
	var obj = new SGPBLogin();
	obj.init();
});
