function SGPBRandomAdmin()
{
	this.init();
}

SGPBRandomAdmin.prototype.init = function()
{
	var data = {
		action: 'sgpb_set_popup_random',
		nonce: SGPB_RANDOM_POPUP.nonce,
	};

	jQuery('.sgpb-popup-random-js').on('change', function () {
		data.id = jQuery(this).data('switch-id');
		jQuery.post(ajaxurl, data, function(response) {});
	})
};


jQuery('document').ready(function() {
	new SGPBRandomAdmin();
});
