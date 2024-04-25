function SGPBSubscriptionPlus()
{
	this.init();
	this.makePopupTitleRequired();
	this.showSubmittedData();
	/* newsletter section */
	this.showCustomFormFields();
	/* autoresponder section */
	this.switchAutoresponderActive();
	this.showCofirmEmailInfo();
}

SGPBSubscriptionPlus.prototype.init = function()
{
	var that = this;
	var eachSubscriberDataBtn = jQuery('.sgpb-show-subscribers-additional-data-js');
	if (!eachSubscriberDataBtn.length) {
		return false;
	}

	eachSubscriberDataBtn.click(function() {
		var subscriberId = jQuery(this).data('subscriber-id');
		var subscriberData = jQuery(this).data('attr-subscriber-data');
		if (Object.keys(subscriberData).length) {
			that.showSubmittedDetailsPopup(subscriberData);
		}
	});
};

SGPBSubscriptionPlus.prototype.switchAutoresponderActive = function()
{
	var that = this;
	jQuery('.sg-switch-checkbox').on('change', function() {
		var postId = jQuery(this).attr('data-switch-id');
		if (jQuery(this).is(':checked')) {
			that.changeAutoresponderStatus('checked', postId);
		}
		else {
			that.changeAutoresponderStatus('', postId);
		}
	});
};

SGPBSubscriptionPlus.prototype.changeAutoresponderStatus = function(status, postId)
{
	var data = {
		action: 'sgpb_change_autoresponder_status',
		nonce: SGPB_JS_PARAMS.nonce,
		type: 'POST',
		postId: postId,
		autoresponderStatus: status
	};

	jQuery.post(ajaxurl, data, function(response) {
		/* error case */
		if (!response) {
			alert(SGPB_SUBSCRIPTION_PLUS_AUTORESPONDER_STATUS_MESSAGE.value['message']);
			location.reload();
		}
	});
};

SGPBSubscriptionPlus.prototype.showSubmittedData = function()
{
	var iframe = jQuery('#content_ifr');
	this.applyStyles(iframe);
};

SGPBSubscriptionPlus.prototype.showSubmittedDetailsPopup = function(submittedData)
{
	var sgpbModal = new SGPBModals();
	var header = 'Subscriber submitted data';

	var content = '';
	for (var fieldName in submittedData) {
		var label = fieldName;
		var value = submittedData[fieldName];
		if (!isNaN(parseInt(fieldName))) {
			var savedData = submittedData[fieldName];
			value = savedData.value;
			label = savedData.label;
		}
		content += '<div class="formItem"><div class="formItem__title sgpb-flex-100 sgpb-margin-0 sgpb-text-capitalize"><b>'+label+'</b></div><div class="col-md-6">'+value+'</div></div>';
	}
	sgpbModal.openModal(sgpbModal.modalContent('', header, content));
	sgpbModal.actionsCloseModal(true)
};

SGPBSubscriptionPlus.prototype.makePopupTitleRequired = function()
{
	if (jQuery('#title').length) {
		var postType = jQuery('#post_type');
		if (postType.length && postType.val() == 'sgpbtemplate') {
			jQuery('#title').attr('required', 'required');
		}
	}
};

SGPBSubscriptionPlus.prototype.applyStyles = function(iframe)
{
	if (typeof SGPB_SUBSCRIPTION_PLUS_JS_PARAMS == 'undefined' ||
		SGPB_SUBSCRIPTION_PLUS_JS_PARAMS.currentPostType != SGPB_SUBSCRIPTION_PLUS_JS_PARAMS.templatePostType) {
		return false;
	}
	iframe.contents().find('html').css({
		'background': 'rgba(0, 0, 0, 0.5) none repeat scroll 0% 0%'
	});
	iframe.contents().find('body').css({
		'background-color': 'rgb(238, 238, 238)',
		'border-color': 'rgb(238, 238, 238)',
		'border-width': '8px',
		'border-style': 'none',
		'width': '600px',
		'color': 'rgb(51, 51, 51)',
		'margin': '20px auto',
		'height': 'auto',
		'min-width': '200px',
		'max-width': '100%',
		'box-shadow': 'rgb(102, 102, 102) 0px 0px 10px',
		'padding-top': '25px !important',
		'padding-right': '25px !important',
		'padding-bottom': '25px !important',
		'padding-left': '25px !important'
	});
};

SGPBSubscriptionPlus.prototype.showCustomFormFields = function()
{
	if (!jQuery('.js-sg-newsletter-forms').length) {
		return false;
	}

	jQuery('.js-sg-newsletter-forms').bind('change', function() {
		var selectedPopupId = jQuery(this).val();
		var data = {
			nonce: SGPB_JS_PARAMS.nonce,
			action: 'sgpb_newsletter_custom_form_fields',
			newsletterData: {
				selectedPopupId: selectedPopupId,
				beforeSend: function() {
					jQuery('.sgpb-newsletter-custom-fields-wrapper').empty();
					jQuery('.sgpb-js-newsletter-custom-fields-spinner').removeClass('sgpb-hide');
				}
			}
		};
		jQuery.post(ajaxurl, data, function(customFields) {
			jQuery('.sgpb-js-newsletter-custom-fields-spinner').addClass('sgpb-hide');
			var customFieldsHtml = '';
			customFields = JSON.parse(customFields);
			for (var i in customFields) {
				customFieldsHtml += '<div class="row form-group">';
				customFieldsHtml += '<div class="col-md-6">';
				customFieldsHtml += '<code>['+customFields[i]["fieldName"]+']</code>';
				customFieldsHtml += '</div>';
				customFieldsHtml += '<div class="col-md-6">';
				customFieldsHtml += customFields[i]["fieldName"];
				customFieldsHtml += '</div>';
				customFieldsHtml += '</div>';
			}
			jQuery('.sgpb-newsletter-custom-fields-wrapper').append(customFieldsHtml);
		});
	});
};

SGPBSubscriptionPlus.prototype.showCofirmEmailInfo = function()
{
	jQuery('.sgpb-email-status').hover(
		function() {jQuery(this).next().css('display', 'block')},
		function() {jQuery(this).next().css('display', 'none')}
	);
}

jQuery(window).on('load', function(){
	new SGPBSubscriptionPlus();
});

