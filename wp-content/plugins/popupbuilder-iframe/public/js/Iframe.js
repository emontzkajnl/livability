function SGPBIframe()
{
	this.siteProtocol = window.location.protocol;
	this.currentUrl = '';
	this.init();
}

SGPBIframe.prototype.init = function()
{
	if (!jQuery('#spgb-iframe-url').length) {
		return false;
	}
	var that = this;

	jQuery('#spgb-iframe-url').bind('input', function() {
		var currentUrl = jQuery(this).val();

		if (!currentUrl.length) {
			return false;
		}
		that.currentUrl = currentUrl;
		jQuery('.sgpb-iframe-warnings').addClass('sgpb-hide');

		if (!that.isUrlValid(currentUrl)) {
			var invalidURLMessage = jQuery('.sgpb-iframe-warnings').attr('data-invalid-url');
			jQuery('.sgpb-iframe-warnings').html(invalidURLMessage);
			jQuery('.sgpb-iframe-warnings').attr('data-message-type', 'invalidURLMessage');
			jQuery('.sgpb-iframe-warnings').removeClass('sgpb-hide');
			return false;
		}

		if (!that.isCompatibleWithProtocol(currentUrl)) {
			var protocolMessage = jQuery('.sgpb-iframe-warnings').attr('data-protocol-warning');
			jQuery('.sgpb-iframe-warnings').html(protocolMessage);
			jQuery('.sgpb-iframe-warnings').attr('data-message-type', 'protocolMessage');
			jQuery('.sgpb-iframe-warnings').removeClass('sgpb-hide');
			return false;
		}

		var iframeCheckData = {
			action: 'check_same_origin',
			nonce: SGPB_JS_PARAMS.nonce,
			siteUrl: window.location.origin,
			iframeUrl: currentUrl
		};
		jQuery.ajax({
			url: ajaxurl,
			data: iframeCheckData,
			method: 'post',
			success: function(response) {

				if (!(that.isUrlValid(that.currentUrl) && that.isCompatibleWithProtocol(that.currentUrl))) {
					return false;
				}

				if (response == 0) {
					var sameOrigin = jQuery('.sgpb-iframe-warnings').attr('data-same-origin');
					jQuery('.sgpb-iframe-warnings').html(sameOrigin);
					jQuery('.sgpb-iframe-warnings').attr('data-message-type', 'sameOrigin');
					jQuery('.sgpb-iframe-warnings').removeClass('sgpb-hide');
					return false;
				}
				jQuery('.sgpb-iframe-warnings').addClass('sgpb-hide');
			}
		});
	});
	jQuery('#spgb-iframe-url').trigger('input');
};

SGPBIframe.prototype.isUrlValid = function(iframeURL)
{
	var match = iframeURL.match(/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i);

	if (match == null) {
		return false;
	}

	return true;
};

SGPBIframe.prototype.isCompatibleWithProtocol = function(iframeURL)
{
	if (this.siteProtocol != 'https:') {
		return true;
	}

	var matchProtocol = iframeURL.indexOf(this.siteProtocol);

	if (matchProtocol == '-1') {
		return false;
	}

	return true;
};

jQuery('document').ready(function() {
	new SGPBIframe();
});
