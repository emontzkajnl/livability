function SGPBMailchimpBackend()
{
	this.apiKeyToggleShow();
	this.changeMailchimpList();
	this.changeLabelColor();
	this.changeDimension();
	this.changeColors();
	this.changeEmailLabel();
	this.changeIndicateRequiredFieldLabel();
	this.showIndicateRequiredFields();
	this.showAsteriskTitle();
	this.changeLabelAlign();
	this.changeFormAlign();
	this.changeButtonTitle();
	this.changeGdprFieldTexts();
	this.addGdprField();
}

SGPBMailchimpBackend.prototype.changeButtonTitle = function()
{
	var buttonTitle = jQuery('#sgpb-mailchimp-submit-title');

	if (!buttonTitle.length) {
		return false;
	}

	buttonTitle.bind('input', function() {
		var val = jQuery(this).val();
		jQuery('#sgpbm-mce-subscribe').val(val);
	});
};

SGPBMailchimpBackend.prototype.changeGdprFieldTexts = function()
{
	var gdprLabel = jQuery('#sgpb-mailchimp-gdpr-label');

	gdprLabel.bind('change', function() {
		var labelText = jQuery(this).val();
		jQuery(this).text('');
		jQuery(this).text(labelText);
		jQuery('.js-gdpr-label').text(labelText);
	});

	var gdprCondirmationText = jQuery('#sgpb-mailchimp-gdpr-text');

	gdprCondirmationText.bind('change', function() {
		var text = jQuery(this).val();
		jQuery(this).text('');
		jQuery(this).text(text);
		jQuery('.sgpb-gdpr-text-js').html(text);
	});
};

SGPBMailchimpBackend.prototype.changeLabelAlign = function()
{
	var labelAlign = jQuery('.sgpb-mailchimp-label-alignment');

	if (!labelAlign.length) {
		return false;
	}

	labelAlign.bind('change', function() {
		var val = jQuery(this).val();
		jQuery('.sgpb-label').css({'text-align': val})
	});
};

SGPBMailchimpBackend.prototype.changeFormAlign = function()
{
	var formAlign = jQuery('.sgpb-mailchimp-form-align');

	if (!formAlign.length) {
		return false;
	}
	var popupId = jQuery('#post_ID').val();

	/*for create new page popup id*/
	if (jQuery('#auto_draft').length) {
		popupId = 0;
	}

	formAlign.bind('change', function() {
		var val = jQuery(this).val();
		/* input alignment */
		jQuery('.sgpb-live-preview-wrapper').find('.mc-field-group.sgpb-field-group').css({'text-align': val});
		/* submit button alignment */
		jQuery('.sgpb-live-preview-wrapper').find('.sg-submit-wrapper').css({'text-align': val});
	});
};

SGPBMailchimpBackend.prototype.showIndicateRequiredFields = function()
{
	var status = jQuery('#sgpb-enable-asterisk-label');

	status.bind('change', function() {
		var isChecked = jQuery(this).is(':checked');

		if (isChecked) {
			jQuery('.sgpb-asterisk').show();
		}
		else {
			jQuery('.sgpb-asterisk').hide()
		}
	})
};

SGPBMailchimpBackend.prototype.showAsteriskTitle = function()
{
	var titleStatus = jQuery('#sgpb-enable-asterisk-title');

	if (!titleStatus.length) {
		return false;
	}

	titleStatus.bind('change', function() {
		var isChecked = jQuery(this).is(':checked');
		if (isChecked) {
			jQuery('.sgpb-indicates-required').show();
		}
		else {
			jQuery('.sgpb-indicates-required').hide()
		}
	});
};

SGPBMailchimpBackend.prototype.changeIndicateRequiredFieldLabel = function()
{
	var indicateRequired = jQuery('#sgpb-mailchimp-asterisk-label');

	if (!indicateRequired) {
		return false;
	}

	indicateRequired.bind('input', function() {
		var val = jQuery(this).val();
		jQuery('.sgpb-indicates-required-title').html(val);
	});
};

SGPBMailchimpBackend.prototype.changeEmailLabel = function()
{
	var emailLabel = jQuery('#sgpb-mailchimp-email-label');

	if (!emailLabel.length) {
		return false;
	}

	emailLabel.bind('input', function() {
		var val = jQuery(this).val();
		jQuery('.sgpb-label-EMAIL').html(val);
	})
};

SGPBMailchimpBackend.prototype.changeColors = function()
{
	var colors = jQuery('.js-sgpb-colors');

	if (!colors.length) {
		return false;
	}
	var that = this;

	colors.wpColorPicker({
		change: function() {
			that.changeTargetColor(jQuery(this));
		}
	});

	jQuery('.wp-picker-holder').bind('click', function() {
		var selectedInput = jQuery(this).prev().find('.js-sgpb-colors');
		that.changeTargetColor(selectedInput);
	});
};

SGPBMailchimpBackend.prototype.changeTargetColor = function(colorInput)
{
	var fieldType = colorInput.data('field-type');
	var styleType = colorInput.data('style-type');
	var color = colorInput.val();
	var styleObj = {};
	styleObj[styleType] = color;
	jQuery('.sgpb-mailchimp-'+fieldType).css(styleObj);
	jQuery('.sgpb-'+fieldType).css(styleObj);
};

SGPBMailchimpBackend.prototype.changeDimension = function()
{
	var dimension = jQuery('.js-mailchimp-dimension');

	if (!dimension) {
		return false;
	}
	var that = this;

	dimension.bind('change', function() {
		var fieldType = jQuery(this).data('field-type');
		var styleType = jQuery(this).data('style-type');
		var dimension = that.getCSSSafeSize(jQuery(this).val());
		if (fieldType == 'input' && styleType == 'width') {
			jQuery('.sgpb-mailchimp-admin-form-wrapper').attr('style', 'max-width:'+dimension);
		}
		var styleObj = {};
		styleObj[styleType] = dimension;
		jQuery('.sgpb-mailchimp-'+fieldType).css(styleObj);
		jQuery('.sgpb-'+fieldType).css(styleObj);
	});
};

SGPBMailchimpBackend.prototype.getCSSSafeSize = function(dimension)
{
	var size;
	size =  parseInt(dimension)+'px';
	/*If user write dimension in px or % we give that dimension to target or we added dimension in px*/
	if (dimension.indexOf('%') != -1 || dimension.indexOf('px') != -1) {
		size = dimension;
	}

	return size;
};

SGPBMailchimpBackend.prototype.changeLabelColor = function()
{
	var labelColor = jQuery('.sgpb-mailchimp-label-color');

	if (!labelColor.length) {
		return false;
	}

	labelColor.wpColorPicker({
		change: function() {
			jQuery('.sgpb-label').css('cssText', 'color: '+jQuery(this).val()+' !important');
		}
	});

	jQuery('.sgpb-label-color .wp-picker-holder').bind('click', function() {
		var selectedInput = jQuery(this).prev().find('.sgpb-mailchimp-label-color');
		jQuery('.sgpb-label').css('cssText', 'color: '+selectedInput.val()+' !important');
	});
};

SGPBMailchimpBackend.init = function()
{
	new SGPBMailchimpBackend();
};

SGPBMailchimpBackend.prototype.changeMailchimpList = function() {
	var that = this;
	var lists = jQuery('.sgpb-mailchimp-lists, #sgpb-show-required-fields');

	if (!lists.length) {
		return false;
	}
	var popupId = jQuery('#post_ID').val();

	/*for create new page popup id*/
	if (jQuery('#auto_draft').length) {
		popupId = 0;
	}

	lists.bind('change', function() {
		var apiArgs = {
			popupId:popupId,
			listId: jQuery('.sgpb-mailchimp-lists option:selected').val(),
			emailLabel: jQuery('#sgpb-mailchimp-email-label').val(),
			asteriskLabel: jQuery('#sgpb-mailchimp-asterisk-label').val(),
			showRequiredFields: jQuery('#sgpb-show-required-fields').is(':checked'),
			submitTitle: jQuery('#sgpb-mailchimp-submit-title').val(),
			gdprStatus: jQuery('#sgpb-mailchimp-gdpr-status').is(':checked'),
			gdprLabel: jQuery('#sgpb-mailchimp-gdpr-label').val(),
			gdprConfirmationText: jQuery('#sgpb-mailchimp-gdpr-text').html()
		};

		var data  = {
			action: 'sgpbm_change_mailchimp_list',
			apiArgs: apiArgs ,
			nonce: SGPB_MAILCHIMP_PARAMS.nonce,
			beforeSend: function() {
				jQuery('.sgpb-spinner-mailchimp-list, .sgpb-loader').removeClass('sg-hide-element');
				jQuery('.sgpbmMailchimpForm').html('')
			}
		};

		jQuery.post(ajaxurl, data, function(responce) {
			jQuery('.sgpb-spinner-mailchimp-list, .sgpb-loader').addClass('sg-hide-element');
			jQuery('.sgpbmMailchimpForm').html(responce);
			that.preventDefaultSubmission();
		});
	});

	lists.trigger('change');
};

SGPBMailchimpBackend.prototype.addGdprField = function()
{
	var that = this;

	jQuery('#sgpb-mailchimp-gdpr-status').bind('click', function() {
		var isChecked = jQuery(this).is(':checked');
		var elementClassName = jQuery(this).attr('data-mailchimp-field-wrapper');
		var element = jQuery('.'+elementClassName);
		console.log(elementClassName);
		console.log(element);
		that.toggleVisible(element, isChecked);
	});
};

SGPBMailchimpBackend.prototype.toggleVisible = function(toggleElement, elementStatus)
{
	if (elementStatus) {
		toggleElement.css({'display': 'block'});
	}
	else {
		toggleElement.css({'display': 'none'});
	}
};

SGPBMailchimpBackend.prototype.preventDefaultSubmission = function()
{
	var formSubmitButton = jQuery('.sgpb-mailchimp-admin-form-wrapper input[type="submit"]');

	if (!formSubmitButton.length) {
		return false;
	}

	formSubmitButton.bind('click', function(e) {
		e.preventDefault();
	});
};

SGPBMailchimpBackend.prototype.apiKeyToggleShow = function()
{
	jQuery('#sg-show-mailchimp-apikey').bind('click', function() {
		if (jQuery(this).prop('checked')) {
			jQuery('#sgpb-mailchimp-api-key').attr('type', 'text');
		}
		else {
			jQuery('#sgpb-mailchimp-api-key').attr('type', 'password');
		}
	});
};

jQuery(document).ready(function() {
	SGPBMailchimpBackend.init();
});
