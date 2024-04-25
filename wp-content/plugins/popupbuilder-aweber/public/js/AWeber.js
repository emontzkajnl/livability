function SGPBAweber()
{
	this.newWindow = null;
	this.submissionPopupId = 0;
	this.init();
}

SGPBAweber.cookieExpirationDate = 365;
SGPBAweber.cookieName = 'SGPBAweberPopup';

SGPBAweber.prototype.init = function()
{
	this.authUser();
	this.disconnect();
	this.changeList();
	//this.changeWebForm();
};

SGPBAweber.prototype.changeList = function()
{
	var aweberList = jQuery('.js-sgpb-aweber-lists');
	var that = this;

	if (!aweberList.length) {
		jQuery('.sgpb-aweber-spinner-js').addClass('sg-hide-element');
		jQuery('.sgpb-aweber-options-wrapper-js').removeClass('sg-hide-element');
		return false;
	}
	aweberList.change(function() {
		var listId = jQuery('option:selected', this).val();

		var data = {
			action: 'sgpb_aweber_change_list',
			nonce: SGPB_AWEBER_PARAMS.nonce,
			listId: listId,
			formId: jQuery('#sgpb-aweber-lists').data('form-id'),
			beforeSend: function() {
				jQuery('.js-sg-spinner').removeClass('sg-hide-element');
				jQuery('.sgpb-sgpb-spinner-aweber-lists').removeClass('sg-hide-element');
				jQuery('.aweber-signup-form-wrapper').addClass('sgpb-pointer-events-none');
				jQuery('#publish').addClass('disabled');
				jQuery('#post-preview').addClass('disabled');
			}
		};

		jQuery.post(ajaxurl, data, function(responce) {
			var data = jQuery.parseJSON(responce);
			jQuery('#sgpb-aweber-lists').data('form-id', '');
			jQuery('.js-sg-spinner').addClass('sg-hide-element');
			jQuery('.aweber-signup-form-wrapper').html(data['webForms']);
			jQuery('#sgpb-aweber-webform-wrapper').html(data['webFormHtml']);
			jQuery('.sgpb-spinner-aweber-lists').addClass('sg-hide-element');
			jQuery('#publish').removeClass('disabled');
			jQuery('#post-preview').removeClass('disabled');
			jQuery('.aweber-signup-form-wrapper').removeClass('sgpb-pointer-events-none');
			jQuery(window).trigger('sgpbAweberFormReady');
			that.reinit();
		});
	});

	jQuery(window).bind('sgpbAweberFormReady', function() {
		that.changeWebForm();
		jQuery('.sgpb-aweber-spinner-js').addClass('sg-hide-element');
		jQuery('.sgpb-aweber-options-wrapper-js').removeClass('sg-hide-element');
		setTimeout(function () {
			let minHeightShouldBe = jQuery('.sgpb-options-menu-active').next().height();
			jQuery('#allMetaboxesView').css('min-height', parseInt(minHeightShouldBe+100)+'px');
		});
	});
	aweberList.trigger('change');
};

SGPBAweber.prototype.changeWebForm = function()
{
	var webForms = jQuery('.js-sgpb-aweber-signup-forms');
	var listId = jQuery('.js-sgpb-aweber-lists').val();

	if (!webForms.length) {
		jQuery('.sgpb-aweber-spinner-js').addClass('sg-hide-element');
		jQuery('.sgpb-aweber-options-wrapper-js').removeClass('sg-hide-element');
	}
	var that = this;

	webForms.bind('change', function() {
		var webFormId = jQuery('option:selected', this).val();

		var data = {
			action: 'sgpb_aweber_change_form',
			nonce: SGPB_AWEBER_PARAMS.nonce,
			listId: listId,
			webFormId: webFormId,
			beforeSend: function() {
				jQuery('.sgpb-spinner-aweber-lists').removeClass('sg-hide-element');
				jQuery('.aweber-signup-form-wrapper').addClass('sgpb-pointer-events-none');
			}
		};

		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#sgpb-aweber-webform-wrapper').html(response);
			jQuery('.aweber-signup-form-wrapper').removeClass('sgpb-pointer-events-none');
			jQuery('.sgpb-spinner-aweber-lists').addClass('sg-hide-element');
			that.reinit();
		});
	});
};

SGPBAweber.prototype.reinit = function()
{
	var backObj = new SGPBBackend();
	backObj.sgInit();
};

SGPBAweber.prototype.authUser = function()
{
	var connectButton = jQuery('.sgpb-aweber-connect-button');

	if (!connectButton.length) {
		return false;
	}

	connectButton.bind('click', function() {
		var authUrl = jQuery(this).attr('data-auth-url');

		var authWindow = window.open(authUrl,'targetWindow', 'toolbar=no, location=1, status=1, menubar=no, scrollbars=yes, resizable=yes, width=500, height=500');
		var widthPosition = jQuery(window).width()/2 - 250;
		var heightPosition = jQuery(window).height()/2 - 250;
		authWindow.moveTo(widthPosition, heightPosition);
	});
};

SGPBAweber.prototype.disconnect = function()
{
	var disconnect = jQuery('.sg-aweber-disconnect');

	if (!disconnect.length) {
		return false;
	}
	disconnect.bind('click', function() {
		var disconnect = confirm(SGPB_AWEBER_PARAMS.confirmText);
		if (disconnect) {
			var webFormData = {
				action: 'sgpb_aweber_disconnect_from_aweber_api',
				'nonce': SGPB_AWEBER_PARAMS.nonce,
				beforeSend: function() {
					jQuery('.spinner-aweber-disconnect').removeClass('sg-hide-element')
				}
			};

			jQuery.post(ajaxurl, webFormData, function() {
				jQuery('.spinner-aweber-disconnect').addClass('sg-hide-element');
				window.location.reload();
			});
		}
	});
};

SGPBAweber.prototype.aweberSendToList = function()
{
	var that = this;
	var aweberValidateObj = that.getValidateObject();
	var formValidateObject = jQuery('.sgpb-aweber-form-wrapper-' + SgpbAweberParams.popupId + ' form').validate(aweberValidateObj);

	jQuery('.sgpb-aweber-form-wrapper-' + SgpbAweberParams.popupId + ' form').submit(function(e) {
		e.preventDefault();

		if (!formValidateObject.valid()) {
			return false;
		}

		var formData = jQuery(this).serialize();

		var aweberSubscribeData = {
			'formData': formData,
			'action': 'list_webform_subscribe',
			beforeSend: function() {
				if (SgpbAweberParams.aweberSuccessBehavior == 'redirectToURL' && SgpbAweberParams.aweberSuccessRedirectNewTab) {
					that.newWindow = window.open(SgpbAweberParams.aweberSuccessRedirectUrl);
				}
				jQuery('.sgpb-aweber-form-wrapper-' + SgpbAweberParams.popupId + ' [name="submit"]').after("<img src='"+SgpbAweberParams.aweberImagesUrl+'wpAjax.gif'+"' class='sgpb-aweber-ajax-spinner'>");
				jQuery('.sgpb-aweber-form-wrapper-' + SgpbAweberParams.popupId + ' [name="submit"]').prop('disabled', true);
			}
		};

		jQuery.post(SgpbAweberParams.sgpbAweberAjaxUrl, aweberSubscribeData, function(response,d) {

			jQuery('.sgpb-aweber-form-wrapper-' + SgpbAweberParams.popupId + ' [name="submit"]').prop('disabled', false);
			jQuery('.sgpb-aweber-form-wrapper-' + SgpbAweberParams.popupId + ' .sgpb-aweber-ajax-spinner').remove();
			that.aweberResponse(response);
		});
	});
};

SGPBAweber.prototype.getValidateObject = function()
{
	var aweberValidateObj = {};
	if (!jQuery('[name="meta_required"]').length) {
		return aweberValidateObj;
	}
	var requiredFields = jQuery('[name="meta_required"]').val();
	var validateMessages = {
		required: SgpbAweberParams.aweberRequiredMessage,
		email: SgpbAweberParams.aweberValidateEmailMessage
	};

	if (requiredFields == '') {
		return aweberValidateObj;
	}
	var requiredFields = requiredFields.split(',');

	if (requiredFields.length) {
		var rules = {};
		var messages = {};
		for (var elementIndex in requiredFields) {
			var element = requiredFields[elementIndex];
			if (element == 'email') {
				rules[element] = {
					required: true,
					email: true
				};
				messages[element] = validateMessages['email'];
				continue;
			}
			rules[element] = {
				required: true
			};
			messages[element] = validateMessages['required'];
		}
		aweberValidateObj['rules'] = rules;
		aweberValidateObj['messages'] = messages;
	}

	return aweberValidateObj;
};

SGPBAweber.prototype.aweberResponse = function(data)
{
	var that = this;
	var parseData = JSON.parse(data);

	jQuery('.sgpb-alert').addClass('sg-hide-element');
	if (parseData.status == 200) {
		this.aweberSuccess(parseData);
	}
	else {
		if (that.newWindow) {
			that.newWindow.close();
		}
		this.aweberError(parseData);
	}
	window.dispatchEvent(new Event('resize'));
	jQuery('#sgcboxLoadedContent').scrollTop(5);
};

SGPBAweber.prototype.aweberError = function(data) {
	var message = data.message;

	/*Invalid Email case*/
	if (message.indexOf('[email]') != -1 && SgpbAweberParams.invalidEmailStatus != '') {
		jQuery('.sgpb-aweber-success-message-' + SgpbAweberParams.popupId).html(SgpbAweberParams.invalidEmailMessage);
	}
	/*Already subscribed case*/
	else if (message.indexOf('already subscribed') != -1) {
		jQuery('.sgpb-aweber-success-message-' + SgpbAweberParams.popupId).html(SgpbAweberParams.subscribedMessage);
	}
	else {
		jQuery('.sgpb-aweber-success-message-' + SgpbAweberParams.popupId).html(message);
	}
	jQuery('.sgpb-aweber-success-message-' + SgpbAweberParams.popupId).addClass('sgpb-alert-danger');
	jQuery('.sgpb-aweber-success-message-' + SgpbAweberParams.popupId).removeClass('sg-hide-element');
};

SGPBAweber.prototype.aweberSuccess = function(data)
{
	var popupOptions = SGPBPopup.getPopupOptionsById(SgpbAweberParams.popupId);
	var behavior = SgpbAweberParams.aweberSuccessBehavior;
	var redirectToNewTab = SgpbAweberParams.aweberSuccessRedirectNewTab;
	var openPopupStatus = false;
	var that = this;

	switch (behavior) {
		case 'showMessage':
			that.dontShowSubscribedUsers();
			jQuery('.sgpb-aweber-success-message-' + SgpbAweberParams.popupId).removeClass('sg-hide-element sgpb-alert-danger');
			jQuery('.sgpb-aweber-form-wrapper-' + SgpbAweberParams.popupId + ' form').addClass('sg-hide-element');
			break;
		case 'redirectToURL':
			that.dontShowSubscribedUsers();
			that.redirectToURL();
		break;
		case 'openPopup':
			that.dontShowSubscribedUsers();
			this.openSuccessPopup(popupOptions);
			break;
		case 'hidePopup':
			that.dontShowSubscribedUsers();
			SGPBPopup.closePopupById(SgpbAweberParams.popupId);
			break;
	}
};

SGPBAweber.prototype.openSuccessPopup = function(popupOptions)
{
	var that = this;
	/*We did this so that the "close" event works*/
	setTimeout(function() {
		SGPBPopup.closePopupById(SgpbAweberParams.popupId);
	}, 0);

	if (typeof popupOptions['sgpb-aweber-success-popup'] != 'undefined') {
		sgAddEvent(window, 'sgpbDidClose', this.openPopup(popupOptions));
	}
};

SGPBAweber.prototype.openPopup = function(popupOptions)
{
	if (typeof popupOptions['sgpb-aweber-success-popup'] == 'undefined') {
		return false;
	}

	var subPopupId = parseInt(popupOptions['sgpb-aweber-success-popup']);
	var subPopupOptions = SGPBPopup.getPopupOptionsById(subPopupId);

	var popupObj = new SGPBPopup();
	popupObj.setPopupId(subPopupId);
	popupObj.setPopupData(subPopupOptions);
	setTimeout(function() {
		popupObj.prepareOpen();
	}, 500);
};

SGPBAweber.prototype.redirectToURL = function()
{
	var redirectURL = SgpbAweberParams.aweberSuccessRedirectUrl;
	var redirectToNewTab = SgpbAweberParams.aweberSuccessRedirectNewTab;
	SGPBPopup.closePopupById(SgpbAweberParams.popupId);

	if (redirectToNewTab) {
		return true;
	}

	window.location.href = redirectURL;
};

SGPBAweber.prototype.dontShowSubscribedUsers = function()
{
	SGPBPopup.setCookie(SGPBAweber.cookieName + SgpbAweberParams.popupId, SgpbAweberParams.popupId, SGPBAweber.cookieExpirationDate, true);
};

SGPBAweber.prototype.allowToOpen = function(popupId)
{
	var allowStatus = true;
	var cookieName = SGPBAweber.cookieName + popupId;

	if (SGPopup.getCookie(cookieName) != '') {
		allowStatus = false;
	}

	return allowStatus;
};

jQuery(document).ready(function() {
	var aweberObj = new SGPBAweber();
	if (typeof SgpbAweberParams != 'undefined') {
		aweberObj.aweberSendToList();
	}
});
