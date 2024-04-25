function SGPBRecentSalesAdmin()
{

}

SGPBRecentSalesAdmin.prototype.init = function()
{
	this.salesImageUpload();
	this.imageRemove();
	this.imageSelect();
	this.sourceSelect();
};

SGPBRecentSalesAdmin.prototype.imageSelect = function()
{
	jQuery('#sgpb-sales-image-type').on('change', function() {
		jQuery('.sgpb-sales-image-wrapper-row').addClass('sg-hide');
		if (jQuery(this).val() == 'custom') {
			jQuery('.sgpb-sales-image-wrapper-row').removeClass('sg-hide');
		}
	});
};

SGPBRecentSalesAdmin.prototype.sourceSelect = function()
{
	jQuery('#sgpb-sales-source').on('change', function() {
		var selectedSource = jQuery(this).val();
		var data = {
			action: 'sgpb_orders_status_lists',
			nonce: SGPB_JS_PARAMS.nonce,
			source: selectedSource,
			beforeSend: function() {
				jQuery('.sgpb-js-status-lists-spinner').removeClass('sgpb-hide');
			}
		};

		jQuery.post(ajaxurl, data, function(response) {
			options = jQuery('#sgpb-orders-status-lists').find('option').remove();
			result = JSON.parse(response);
			for (row in result) {
				value = row;
				text = result[row];
				jQuery('#sgpb-orders-status-lists').append('<option value = ' + value + '>' + text + '</option>');
			}

			jQuery('.sgpb-js-status-lists-spinner').addClass('sgpb-hide');
		});

	});
};

SGPBRecentSalesAdmin.prototype.salesImageUpload = function()
{
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	if (jQuery('#js-upload-sales-image').val()) {
		jQuery('.sgpb-show-sales-image-container').html('');
		jQuery('.sgpb-show-sales-image-container').css({'background-image': 'url("' + jQuery("#js-upload-sales-image").val() + '")'});
	}
	var custom_uploader;
	jQuery('#js-upload-sales-image-button').click(function(e) {
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
			jQuery('.sgpb-show-sales-image-container').css({'background-image': 'url("' + attachment.url + '")'});
			jQuery('.sgpb-show-sales-image-container').html('');
			jQuery('#js-upload-sales-image').val(attachment.url);
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* its finish image uploader */
};

SGPBRecentSalesAdmin.prototype.imageRemove = function()
{
	jQuery('#js-upload-sales-image-remove-button').click(function(){
		jQuery(".sgpb-show-sales-image-container").html('');
		jQuery("#js-upload-sales-image").attr('value', '');
		jQuery('.sgpb-show-sales-image-container').attr('style', 'background-image: url("' + sgpbRecentSalesPublicUrl + 'img/defaultCustomImage.png")');
		jQuery('.js-sgpb-remove-sales-image').addClass('sg-hide');
	});
};

jQuery(document).ready(function() {
	var recentSales = new SGPBRecentSalesAdmin();
	recentSales.init();
});
