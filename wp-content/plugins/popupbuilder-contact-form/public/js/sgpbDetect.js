function SGPBDetect()
{
}

SGPBDetect.prototype.init = function ()
{
	this.modalDetectionStyles();
	this.modalDetection(SGPB_JS_DETECTION_EXTENSION.header, SGPB_JS_DETECTION_EXTENSION.logo, SGPB_JS_DETECTION_EXTENSION.message, SGPB_JS_DETECTION_EXTENSION.url);
	this.checkIfModalClosed();
};

SGPBDetect.prototype.modalDetectionStyles = function ()
{
	var css = '<style id="sgpb-modal-detection-styles">.sgpb-overflow-hidden{overflow: hidden!important;}.sgpb.sgpb-modal-detection {position: fixed;top: 0;bottom: 0;left: 0;right: 0;display: flex;justify-content: center;align-items: center;background: #00000082;z-index: 999; -webkit-backdrop-filter: blur(7px);backdrop-filter: blur(7px);}';
	css += '.sgpb.sgpb-modal-detection .sgpb-modal-detection-main {min-width: 600px;min-height: 500px;background: #FFFFFF 0 0 no-repeat padding-box;box-shadow: 0 3px 20px #00000029;border-radius: 12px;padding: 20px;position: relative;display: flex;flex-direction: column;justify-content: space-around;align-items: center;}';
	css += '.sgpb.sgpb-modal-detection .sgpb-modal-logo {font-weight: bold; font-size: 35px; width: 200px;}';
	css += '.sgpb.sgpb-modal-detection .sgpb-modal-body p{font-size: 16px; text-align: center;}';
	css += '.sgpb.sgpb-modal-detection .sgpb-modal-body a{font-weight: bold; color: #2873EB;}';
	css += '</style>';
	jQuery(css).appendTo(document.body);
};
SGPBDetect.prototype.modalDetection = function (header, logo, body, url)
{
	jQuery(document.body).addClass('sgpb-overflow-hidden');
	var modal = jQuery('<div/>', {
		"class": 'sgpb sgpb-modal-detection',
		html: jQuery('<div/>', {
			"class": 'sgpb-modal-detection-main',
			html: [
				jQuery(header),
				jQuery('<img />', {
					"class": 'sgpb-modal-logo',
					src: logo,
					alt: 'logo'
				}),
				jQuery('<div/>', {
					"class": 'sgpb-modal-body',
					html: body
				})
			]
		})
	});
	jQuery(modal).appendTo(document.body);
	jQuery('.sgpb-modal-detection-main').on('click', function () {
		window.location.replace(url);
	});
};
SGPBDetect.prototype.checkIfModalClosed = function ()
{
	var that = this;
	setInterval(function () {
		if (!jQuery('.sgpb.sgpb-modal-detection').length || !jQuery('#sgpb-modal-detection-styles').length) {
			that.init();
		}
	}, 800)
};

jQuery(document).ready(function () {
	sgpbDetect = new SGPBDetect();
	sgpbDetect.init();
});
