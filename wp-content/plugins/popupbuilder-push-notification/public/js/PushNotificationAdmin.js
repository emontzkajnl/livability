function SGPBPushNotificationAdmin()
{
	this.init();
}

SGPBPushNotificationAdmin.tabCookieName = 'SGPBPushNotificationActiveTab';

SGPBPushNotificationAdmin.prototype.init = function()
{
	this.toggleCheckedBulkAction();
	this.deleteNotification();
	this.deleteCampaigns();

	this.buttonImageUpload();
	this.buttonImageRemove();

	this.tabsLinks();

	this.notificationLivewPreview();
	this.filtersChange();
	this.campaignsFiltersChange();
	this.sendNotification();
};

SGPBPushNotificationAdmin.prototype.sendNotification = function()
{
	var successMessage = jQuery('.sgpb-notification-send-success-js');
	var errorMessage = jQuery('.sgpb-notification-send-error-js');
	var btn = jQuery('.sgpb-send-notification');

	if (!btn.length) {
		return false;
	}

	btn.bind('click', function(e) {
		e.preventDefault();
		var btnText = jQuery(this).val();
		if (!successMessage.hasClass('sg-hide')) {
			successMessage.addClass('sg-hide');
		}
		if (!errorMessage.hasClass('sg-hide')) {
			errorMessage.addClass('sg-hide');
		}
		var popupId = jQuery('.sgpb-push-notification-popups option:selected').val();
		var title = jQuery('#sgpb-notification-title').val();
		var text = jQuery('#sgpb-notification-text').val();
		var icon = jQuery('#js-push-upload-image').val();
		var customLink = jQuery('#sgpb-notification-custom-link').val();

		var data = {
			action: 'sgpb_send_notification',
			nonce: SGPB_JS_PARAMS.nonce,
			popupId: popupId,
			title: title,
			text: text,
			customLink: customLink,
			icon: icon,
			beforeSend: function () {
				btn.val(btnText+'...');
				btn.prop('disabled', true);
			}
		};

		jQuery.post(ajaxurl, data, function(response) {
			btn.val(btnText);
			btn.prop('disabled', false);
			if (response !== null) {
				successMessage.removeClass('sg-hide');
			}
			else {
				errorMessage.removeClass('sg-hide');
			}
		});
	});
};

SGPBPushNotificationAdmin.prototype.filtersChange = function()
{
	jQuery('#sgpb-notification-popup').on('change', function() {
		jQuery('.sgpb-notification-popup-id').val(jQuery(this).val());
	});
	jQuery('#sgpb-notification-date-list').on('change', function() {
		jQuery('.sgpb-notification-date').val(jQuery(this).val());
	})
};

SGPBPushNotificationAdmin.prototype.campaignsFiltersChange = function()
{
	jQuery('#sgpb-campaigns-date-list').on('change', function() {
		jQuery('.sgpb-campaigns-date-list').val(jQuery(this).val());
	})
};

SGPBPushNotificationAdmin.prototype.notificationLivewPreview = function()
{
	this.changeTitle();
	this.changeMessage();
	this.changeImage();
};

SGPBPushNotificationAdmin.prototype.changeImage = function()
{
	var image = jQuery('#js-push-upload-image');

	if (!image.length) {
		return false;
	}

	image.bind('change', function() {
		var val = jQuery(this).val();
		jQuery('.sgpb-custom-image').attr('src', val);
	});
};

SGPBPushNotificationAdmin.prototype.changeTitle = function()
{
	var title = jQuery('#sgpb-notification-title');

	if (!title.length) {
		return false;
	}

	title.bind('change keydown keyup', function() {
		var val = jQuery(this).val();
		jQuery('.sgpb-notify-title').text(val);
	});
};

SGPBPushNotificationAdmin.prototype.changeMessage = function()
{
	var text = jQuery('#sgpb-notification-text');

	if (!text.length) {
		return false;
	}

	text.bind('change keydown keyup', function() {
		var val = jQuery(this).val();
		jQuery('.sgpb-notify-message').text(val);
	});
};

SGPBPushNotificationAdmin.prototype.tabsLinks = function()
{
	var tabs = jQuery('.sgpb-tab-link');

	if (!tabs) {
		return false;
	}
	var that = this;

	tabs.bind('click', function() {
		var currentKey = jQuery(this).data('key');
		var wrapper = jQuery(this).parents('.sgpb-tabs-content-wrapper').first();
		that.changeTab(currentKey, wrapper);
		that.setActiveTab(currentKey);
	});

	var wrapper = tabs.parents('.sgpb-tabs-content-wrapper').first();
	var key = jQuery('.sgpb-active-tab-name').val();
	if (!key) {
		key = wrapper.find('.sgpb-tab-link').first().data('key');
	}

	that.changeTab(key, wrapper);
	that.hideShowActiveTab();
};

SGPBPushNotificationAdmin.prototype.setActiveTab = function(key)
{
	SGPopup.setCookie(SGPBPushNotificationAdmin.tabCookieName, key);
};

SGPBPushNotificationAdmin.prototype.getActiveTab = function()
{
	return SGPopup.getCookie(SGPBPushNotificationAdmin.tabCookieName);
};

SGPBPushNotificationAdmin.prototype.hideShowActiveTab = function()
{
	var activeTab = this.getActiveTab();
	if (!activeTab) {
		this.setActiveTab('sendPush');
		activeTab = 'sendPush';
	}
	jQuery('.sgpb-tab-link').each(function(){
		jQuery(this).removeClass('sgpb-tab-active');
	});
	jQuery('.sgpb-tab-content-wrapper').each(function(){
		jQuery(this).css({display: 'none'});
	});

	jQuery('#sgpb-tab-content-wrapper-'+activeTab).css({display: 'block'});
	jQuery('.sgpb-option-tab-' + activeTab).addClass('sgpb-tab-active');
};



SGPBPushNotificationAdmin.prototype.changeTab = function(key, wrapper)
{
	var tabsContent = wrapper.find('.sgpb-tab-content-wrapper');
	tabsContent.each(function(){
		jQuery(this).css('display', 'none');
	});
	tablinks = wrapper.find('.sgpb-tab-link');
	tablinks.each(function(){
		jQuery(this).removeClass('sgpb-tab-active');
	});
	var currentLink = wrapper.find('.sgpb-option-tab-'+key).first();
	currentLink.css('display', 'block');
	currentLink.addClass('sgpb-tab-active');
	jQuery('#sgpb-tab-content-wrapper-'+key).css({display: 'block'});
};

SGPBPushNotificationAdmin.prototype.deleteNotification = function()
{
	var deleteButton = jQuery('.sgpb-note-delete-button');

	if (!deleteButton.length) {
		return false;
	}
	var that = this;
	var checkedNotificationsList = [];

	deleteButton.bind('click', function() {
		var data = {};
		data.ajaxNonce = jQuery(this).attr('data-ajaxNonce');
		jQuery('.sgpb-notification-delete-checkbox').each(function() {
			var isChecked = jQuery(this).prop('checked');
			if (isChecked) {
				var subscriberId = jQuery(this).attr('data-delete-id');
				checkedNotificationsList.push(subscriberId);
			}
		});
		if (checkedNotificationsList.length == 0) {
			alert('Please select at least one.');
		}
		else {
			var isSure = confirm(SGPB_NOTIFICATION_LOCALIZATION.areYouSure);
			if (isSure) {
				that.deleteViaAjax(checkedNotificationsList, data);
			}
		}
	})
};

SGPBPushNotificationAdmin.prototype.deleteCampaigns = function()
{
	var deleteButton = jQuery('.sgpb-campaigns-delete-button');

	if (!deleteButton.length) {
		return false;
	}
	var that = this;
	var checkedNotificationsList = [];

	deleteButton.bind('click', function() {
		var data = {};
		data.ajaxNonce = jQuery(this).attr('data-ajaxNonce');
		jQuery('.sgpb-notification-delete-checkbox').each(function() {
			var isChecked = jQuery(this).prop('checked');
			if (isChecked) {
				var subscriberId = jQuery(this).attr('data-delete-id');
				checkedNotificationsList.push(subscriberId);
			}
		});
		if (checkedNotificationsList.length == 0) {
			alert('Please select at least one.');
		}
		else {
			var isSure = confirm(SGPB_NOTIFICATION_LOCALIZATION.areYouSure);
			if (isSure) {
				that.deleteCampaignsViaAjax(checkedNotificationsList, data);
			}
		}
	})
};

SGPBPushNotificationAdmin.prototype.buttonImageUpload = function()
{
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	var custom_uploader;
	jQuery('#js-button-upload-image-button').click(function(e) {
		e.preventDefault();

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert(SGPB_JS_LOCALIZATION.imageSupportAlertMessage);
				return;
			}
			jQuery(".sgpb-show-button-image-container").css({'background-image': 'url("' + attachment.url + '")'});
			jQuery(".sgpb-show-button-image-container").html("");
			jQuery('#js-push-upload-image').val(attachment.url).trigger('change');
			jQuery('.js-sgpb-remove-close-button-image').removeClass('sg-hide');
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* its finish image uploader */
};

SGPBPushNotificationAdmin.prototype.buttonImageRemove = function()
{
	jQuery('#js-button-upload-image-remove-button').click(function(){
		var selectedTheme = jQuery('.js-sgpb-popup-themes:checked').attr('data-popup-theme-number');
		if (typeof selectedTheme == 'undefined') {
			selectedTheme = 6;
		}
		var defaultUrl = jQuery(this).data('default-url');
		jQuery('.sgpb-show-button-image-container').html('');
		jQuery('#js-button-upload-image').attr('value', '');
		jQuery('.sgpb-show-button-image-container').attr('style', 'background-image: url("'+defaultUrl+'")');
		jQuery('.sgpb-custom-image').attr('style', 'background-image: url("'+defaultUrl+'")');
		jQuery('.sgpb-custom-image').attr('src', defaultUrl);
		jQuery('.js-sgpb-remove-close-button-image').addClass('sg-hide');
	});
};

SGPBPushNotificationAdmin.prototype.deleteViaAjax = function(checkedNotificationsList)
{
	var data = {
		action: 'sgpb_notification_delete',
		nonce: SGPB_JS_PARAMS.nonce,
		notificationsId: checkedNotificationsList,
		beforeSend: function() {
			jQuery('.sgpb-notification-remove-spinner').removeClass('sg-hide-element');
		}
	};

	jQuery.post(ajaxurl, data, function(response) {
		window.location.reload();
	});
};

SGPBPushNotificationAdmin.prototype.deleteCampaignsViaAjax = function(checkedCampaignsList)
{
	var data = {
		action: 'sgpb_campaigns_delete',
		nonce: SGPB_JS_PARAMS.nonce,
		campaignsId: checkedCampaignsList,
		beforeSend: function() {
			jQuery('.sgpb-notification-remove-spinner').removeClass('sg-hide-element');
		}
	};

	jQuery.post(ajaxurl, data, function(response) {
		window.location.reload();
	});
};

SGPBPushNotificationAdmin.prototype.toggleCheckedBulkAction = function()
{
	var that = this;
	jQuery('.subs-bulk').each(function() {
		jQuery(this).bind('click', function() {
			var bulkStatus = jQuery(this).prop('checked');
			jQuery('.subs-bulk').prop('checked', bulkStatus);
			that.changeCheckedSubscribers(bulkStatus);
		});
	});
};

SGPBPushNotificationAdmin.prototype.changeCheckedSubscribers = function(bulkStatus)
{
	jQuery('.sgpb-notification-delete-checkbox').each(function() {
		jQuery(this).prop('checked', bulkStatus);
	})
};


jQuery(document).ready(function() {
	var pushNotificationObj = new SGPBPushNotificationAdmin();
});
