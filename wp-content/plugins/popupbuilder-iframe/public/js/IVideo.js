function SGPBIVideo()
{

}

SGPBIVideo.prototype.getAllSupportedDomains = function()
{
	var supportedDomains = [
		'www.youtube.com',
		'youtube.com',
		'www.youtube-nocookie.com',
		'youtube-nocookie.com',
		'youtu.be',
		'www.youtu.be',
		'vimeo.com',
		'www.vimeo.com',
		'player.vimeo.com',
		'www.player.vimeo.com',
		'www.dailymotion.com',
		'dailymotion.com',
		'dai.ly',
		'www.dai.ly'
	];

	return supportedDomains;
};

SGPBIVideo.prototype.validateVideoUrl = function()
{
	var videoInput = jQuery('#sgpb-video-url');

	if (!videoInput.length) {
		return false;
	}
	var that = this;

	videoInput.bind('input', function() {
		var warnings = jQuery('.sgpb-video-warnings');
		warnings.addClass('sgpb-hide');
		var val = jQuery(this).val();

		if (!val) {
			return false;
		}
		var isUlrValid = that.isUrlValid(val);

		if (!isUlrValid) {
			warnings.removeClass('sgpb-hide');
			var invalidMessage = warnings.data('invalid-url');
			warnings.html(invalidMessage);
			return true;
		}

		if (!that.isSupportedDomains(val)) {
			warnings.removeClass('sgpb-hide');
			var notSupportedMessage = warnings.data('not-supported');
			warnings.html(notSupportedMessage)
		}
	});
	/*For page load validation*/
	videoInput.trigger('input');

	return true;
};

SGPBIVideo.prototype.isSupportedDomains = function(url) {

	var isSupported = false;
	var allDomains = this.getAllSupportedDomains();
	var hostName = this.extractHostname(url);

	for (var i in allDomains) {

		if (hostName == allDomains[i]) {
			isSupported = true;
			break;
		}
	}

	return isSupported;
};

SGPBIVideo.prototype.extractHostname = function (url)
{
	var hostname;
	/* find & remove protocol (http, ftp, etc.) and get hostname */
	if (url.indexOf('://') > -1) {
		hostname = url.split('/')[2];
	}
	else {
		hostname = url.split('/')[0];
	}

	/* find & remove port number */
	hostname = hostname.split(':')[0];
	/*  find & remove "?" */
	hostname = hostname.split('?')[0];

	return hostname;
};

SGPBIVideo.prototype.isUrlValid = function(url)
{
	/* Add https if it's not started with http or https */
	if (!url.match(/(?:https:\/\/?|http:\/\/)/g)) {
		url = 'https://'+url;
	}

	var match = url.match(/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i);

	if (match) {
		var match = this.isUrlTwice(url);
	}


	return match;
};

SGPBIVideo.prototype.isUrlTwice = function(url)
{
	var match = url.match(/(?:https:\/\/?|http:\/\/)/g);

	if (match != null && match.length > 1) {
		return false;
	}

	return true;
};

SGPBIVideo.prototype.toggleVideo = function(id, state, popupOptions)
{
	jQuery('.sgpb-popup-dialog-main-div-wrapper iframe').each(function() {
		var iframe = jQuery(this);
		var correctPopupClass = jQuery(this).attr('class');
		if (correctPopupClass != 'sgpb-iframe-'+id) {
			return false;
		}
		if (state == 'play') {
			var iframeUrl = iframe.attr('data-attr-src');
			iframe.attr('src', iframeUrl);
			iframe.data('attr-src', '');
		}
		else if (state == 'stop') {
			var iframeUrl = iframe.attr('src');
			var type = popupOptions['sgpb-type'];
			var url = popupOptions['sgpb-' + type + '-url'];

			if (type == 'iframe' || type == 'video') {
				iframe.data('attr-src', iframeUrl);
			}
			else {
				iframe.data('attr-src', url);
			}
			iframe.attr('src', '');
		}
	});
};

SGPBIVideo.prototype.eventListeners = function()
{
	if (typeof sgAddEvent == 'undefined') {
		return false;
	}
	sgAddEvent(window, 'sgpbDidOpen', function(e) {
		if ('iframe' !== e.detail.popupData['sgpb-type']){
			return
		}
		var agrs = e.detail;
		var popupId = agrs['popupId'];
		var popupOptions = agrs['popupData'];
		SGPBIVideo.prototype.toggleVideo(popupId, 'play', popupOptions);
	});

	sgAddEvent(window, 'sgpbDidClose', function(e) {
		if ('iframe' !== e.detail.popupData['sgpb-type']){
			return
		}
		var agrs = e.detail;
		var popupId = agrs['popupId'];
		var popupOptions = agrs['popupData'];
		SGPBIVideo.prototype.toggleVideo(popupId, 'stop', popupOptions);
	});
};

SGPBIVideo.prototype.hideShowSpinner = function(listenerObj, eventData)
{
	sgAddEvent(window, 'sgpbDidOpen', function(e) {
		var args = e.detail;
		var popupId = parseInt(args.popupId);

		var iframeTag = jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-iframe-'+popupId);
		if (iframeTag.length) {
			iframeTag.load(function() {
				var popupData = SGPBPopup.getPopupWindowDataById(popupId);
				/* remove spinner class when popup is open*/
				if (typeof popupData != 'undefined' && popupData.isOpen) {
					jQuery(this).removeClass('sgpb-iframe-spiner');
				}
			});
		}
	});
};

SgpbEventListener.prototype.sgpbIframe = function(listenerObj, eventData)
{
	var that = listenerObj;
	var popupIds = [];
	var popupObj = that.getPopupObj();
	var popupId = parseInt(popupObj.id);
	popupIds.push(popupId);
	var mapId = listenerObj.filterPopupId(popupId);
	popupIds.push(mapId);
	var popupOptions = popupObj.getPopupData();
	var iframeCount = 1;
	var delay = parseInt(popupOptions['sgpb-popup-delay']) * 1000;

	for(var key in popupIds) {
		popupId = popupIds[key];

		jQuery('.sg-iframe-popup-' + popupId).each(function() {
			jQuery(this).bind('click', function(e) {

				var link = jQuery(this).attr('href');

				if (typeof link == 'undefined') {
					var childLinkTag = jQuery(this).find('a');
					link = childLinkTag.attr('href');

					if (typeof link == 'undefined') {
						return false;
					}
				}

				if (iframeCount > 1) {
					return false;
				}
				var allowToOpen = popupObj.forceCheckCurrentPopupType(popupObj);

				if (!allowToOpen) {
					return true;
				}
				++iframeCount;
				jQuery(window).trigger('sgpbIframeEvent', popupOptions);
				setTimeout(function() {
					popupOptions['sgpb-iframe-' + popupId] = link;
					popupObj.setPopupData(popupOptions);

					popupObj.prepareOpen();
					var currentIframe = jQuery('.sgpb-popup-builder-content-' + popupId).find('iframe');
					currentIframe.attr('data-attr-src', link);
					iframeCount = 1;
					return false;
				}, delay);

				return false;
			});
		});

	}
};

jQuery(document).ready(function() {
	var videoObj = new SGPBIVideo();
	videoObj.eventListeners();
	videoObj.validateVideoUrl();
	videoObj.hideShowSpinner();
});
