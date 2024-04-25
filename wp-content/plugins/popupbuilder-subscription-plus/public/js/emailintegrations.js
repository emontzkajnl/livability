function SGPBIntegrations()
{
	this.init();
	this.connect();
	this.appId;
	this.editSettingsArea();
	this.addAction();
}

SGPBIntegrations.prototype.init = function()
{
	var that = this;
	var connectButton = jQuery('.sgpb-connect-button');

	connectButton.click(function() {
		that.appId = jQuery(this).data('app-id');
		that.showPopupDialog();
	});

	var disconnectButton = jQuery('.sgpb-disconnect-field');
	disconnectButton.click(function() {
		that.appId = jQuery(this).data('app-id');
		that.disconnect();
	});
};

SGPBIntegrations.prototype.showPopupDialog = function()
{
	jQuery('.sgpb-dialog-wrapper').removeClass('sgpb-hide');
	dataWrapper = jQuery('.sgpb-integration-dialog-wrapper');
	dataWrapper.css({'position': 'fixed', 'left': '50%', 'top': '50%', 'margin-top': '-' + (dataWrapper.height()/2) + 'px', 'margin-left': '-' + (dataWrapper.width()/2) + 'px'});

	var providersData = SGPB_SUBSCRIPTION_PLUS_EMAIL_INTEGRATIONS_PARAMS['availableProvidersData'];
	var currentProviderData = providersData[this.appId];

	var imgSrc = currentProviderData['logo'];
	jQuery('.sgpb-dialog-image img').attr('src', imgSrc);

	var handleText = jQuery('.sgpb-dialog-handle').text();
	handleText = handleText.split(' ')[0];
	jQuery('.sgpb-dialog-handle').empty();
	jQuery('.sgpb-dialog-handle').text(handleText + ' ' + currentProviderData['name']);

	var info = currentProviderData['info'];
	jQuery('.sgpb-dialog-info').html(info);

	var dialogForms = currentProviderData['connectionFormfields'];
	var html = '';
	for (var form in dialogForms) {
		html += '<label for="'+form+'">'+dialogForms[form]['label']+'</label><br>';
		html += '<input type="text" name="'+form+'" placeholder="'+dialogForms[form]['placeholder']+'" required>';
		html += '<label class="sgpb-required-field-message text-danger" data-attr-appid="'+form+'"></label><br>';

	}
	jQuery('.sgpb-dialog-div').empty();
	jQuery('.sgpb-dialog-div').html(html);

	jQuery("input[type='text']").mouseup(function() {
		jQuery(this).next().text('');
	});

	this.clearFormData('#dialog-form');
	this.hidePopupDialog();
	this.escKeyClosePopup();
}

SGPBIntegrations.prototype.clearFormData = function(formSelector)
{
	element = jQuery('.sgpb-error-message');
	if (!element.hasClass('sgpb-hide')) {
		element.addClass('sgpb-hide');
	}
	jQuery('.sgpb-required-field-message').text('');
	jQuery(':input', formSelector).each(function() {
		this.value = '';
	});
}

SGPBIntegrations.prototype.hidePopupDialog = function()
{
	var that = this;
	jQuery('.sgpb-subscriber-data-popup-close-btn-js').bind('click', function() {
		jQuery('.sgpb-dialog-wrapper').addClass('sgpb-hide');
	});
}

SGPBIntegrations.prototype.escKeyClosePopup = function()
{
	var that = this;
	jQuery(document).keyup(function(e) {
		 if (e.keyCode == 27) {
			if (!jQuery('.sgpb-dialog-wrapper').hasClass('sgpb-hide')) {
				jQuery('.sgpb-dialog-wrapper').addClass('sgpb-hide');
			}
		}
	});
}

SGPBIntegrations.prototype.prepareRequest = function()
{
	var that = this;
	var hasError = false;
	var requiredMessageFields = jQuery('.sgpb-dialog-div .sgpb-required-field-message');
	var configData = {};
	for (var field in requiredMessageFields) {
		element = requiredMessageFields[field];
		var fieldId = jQuery(element).data('attrAppid');
		if (typeof requiredMessageFields[field] != 'object' || typeof fieldId == 'undefined') {
			break;
		}
		var value = jQuery("input[name='"+fieldId+"']").val();
		if (!value) {
			jQuery(element).text(SGPB_SUBSCRIPTION_PLUS_EMAIL_INTEGRATIONS_PARAMS['requiredFieldMessage']);
			hasError = true;
		}
		configData[fieldId] = value;
	}
	if (!hasError) {
		return configData;
	}
}

SGPBIntegrations.prototype.connect = function()
{
	var connectBtn = jQuery('.sgpb-connection');
	if (!connectBtn.length) {
		return false;
	}
	var that = this;
	connectBtn.bind('click', function(e) {
		configData = that.prepareRequest();
		if (!configData) {
			return false;
		}
		var ajaxData = {
			action: 'sgpb_email_integrations_connect',
			nonce_ajax: SGPB_JS_PARAMS.nonce,
			data: {
				configData:configData,
				appID: that.appId,
				beforeSend: function() {
					connectBtn.attr('disabled', true);
					jQuery('.sgpb-js-integration-spinner').removeClass('sgpb-hide');
				}
			}
		};
		jQuery.post(ajaxurl, ajaxData, function(response) {
			connectBtn.attr('disabled', false);
			var response = JSON.parse(response);
			jQuery('.sgpb-js-integration-spinner').addClass('sgpb-hide');
			if (response.errors) {
				jQuery('.sgpb-error-message').removeClass('sgpb-hide');
				jQuery('.sgpb-error-message').html(response.html);
				var options = response.options;
				for (var element in options) {
					jQuery('*[data-attr-appid="'+element+'"]').text(options[element]);
				}
			}
			else {
				element = jQuery('.sgpb-error-message');
				if (!element.hasClass('sgpb-hide')) {
					element.addClass('sgpb-hide');
				}
				jQuery('.sgpb-integration-dialog-wrapper').addClass('sgpb-hide');
				jQuery('.sgpb-success-message').removeClass('sgpb-hide');
				jQuery('.sgpb-success-message-text').html(response.html);
				setTimeout(function(){
					location.reload();
					jQuery('.sgpb-success-message').addClass('sgpb-hide');
				}, 1000);
			}
		});
	});
}

SGPBIntegrations.prototype.disconnect = function()
{
	var ajaxData = {
		action: 'sgpb_email_integrations_disconnect',
		nonce_ajax: SGPB_JS_PARAMS.nonce,
		data: {
			appID: this.appId
		}
	};
	jQuery.post(ajaxurl, ajaxData, function(response) {
		if (response.length > 1) {
			location.reload();
		}
	});
}

SGPBIntegrations.prototype.addAction = function()
{
	var element = jQuery('.sgpb-integrations-checkbox');
	if (!element.length) {
		return false;
	}

	var that = this;
	element.on('change', function() {
		var currentElementWrapper = jQuery(this).parents('.sgpb-field-icon-wrapper').parent();
		var index = currentElementWrapper.attr('data-order-index');
		var popupId = currentElementWrapper.attr('data-popup-id');
		var currentEditableElement = jQuery('.sgpb-add-integrations-'+index+' span').first();
		if (jQuery(this).is(':checked')) {
			jQuery('.sgpb-field-icon-wrapper-'+index).css({'opacity':'0.8'});
		}
		else {
			jQuery('.sgpb-field-icon-wrapper-'+index).css({'opacity':'1'});
		}
	});
}

SGPBIntegrations.prototype.editSettingsArea = function()
{
	var that = this;
	jQuery('.sgpb-field-icon-wrapper').unbind('click').bind('click', function() {
		element = jQuery(this);
		var currentElementWrapper = jQuery(this).parent();
		var type = element.data('type');
		var index = currentElementWrapper.attr('data-order-index');
		if (type != 'integrations') {
			return false;
		}

		if (index == 'default') {
			return false;
		}

		var currentEditableTemplate = jQuery('.sgpb-edit-settings-area-wrapper-'+index);
		if (currentEditableTemplate.hasClass('sgpb-hide')) {
			currentEditableTemplate.removeClass('sgpb-hide');
		}
		else {
			currentEditableTemplate.addClass('sgpb-hide');
		}
	});
}

jQuery(window).on('load', function(){
	new SGPBIntegrations();
});
