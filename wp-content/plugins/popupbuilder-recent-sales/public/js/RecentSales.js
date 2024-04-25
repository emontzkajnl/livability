function SGPBRecentSales()
{
	this.maxCount = 1;
	this.currentCountIndex = 0;
	this.popupId = 0;
	this.popupContent = '';
	this.orders = '';
	this.popup = {};
	this.eventData = {};
}

SGPBRecentSales.prototype.setEventData = function(eventData)
{
	this.eventData = eventData;
};

SGPBRecentSales.prototype.getEventData = function()
{
	return this.eventData;
};

SGPBRecentSales.prototype.setPopup = function(popup)
{
	this.popup = popup;
};

SGPBRecentSales.prototype.getPopup = function()
{
	return this.popup;
};

SGPBRecentSales.prototype.setCurrentCountIndex = function(index)
{
	this.currentCountIndex = index;
};

SGPBRecentSales.prototype.getCurrentCountIndex = function()
{
	return this.currentCountIndex;
};

SGPBRecentSales.prototype.setMaxCount = function(maxCount)
{
	this.maxCount = maxCount;
};

SGPBRecentSales.prototype.getMaxCount = function()
{
	return this.maxCount;
};

SGPBRecentSales.prototype.setOrders = function()
{
	var popupId = this.getPopupId();
	var popupContent = jQuery('.sgpb-recent-sales-popup-data-' + popupId).data('params');
	this.orders = popupContent.orders;
};

SGPBRecentSales.prototype.getOrders = function()
{
	return this.orders;
};

SGPBRecentSales.prototype.setPopupId = function(popupId)
{
	this.popupId = popupId;
};

SGPBRecentSales.prototype.getPopupId = function()
{
	return this.popupId;
};

SGPBRecentSales.prototype.setPopupContent = function()
{
	var popupId = this.getPopupId();
	var popupContent = jQuery('.sgpb-recent-sales-popup-data-' + popupId).data('params');
	this.popupContent = popupContent.content;
};

SGPBRecentSales.prototype.getPopupContent = function()
{
	return this.popupContent;
};

SGPBRecentSales.prototype.init = function()
{
	var that = this;
	var popupObj = this.getPopup();
	if (popupObj.eventName != 'sgpbRecentSales') {
		return false;
	}
	/* first opening delay */
	var initialDelay = that.getEventData();
	initialDelay = parseInt(initialDelay.value)*1000;
	/* first open */
	setTimeout(function() {
		popupObj.prepareOpen();
	}, initialDelay);

	sgAddEvent(window, 'sgpbWillOpen', function(e) {
		var args = e.detail;
		var popupId = parseInt(args.popupId);
		var popupContent = jQuery('.sgpb-recent-sales-popup-data-' + popupId).data('params');
		if (typeof popupContent == 'undefined') {
			return false;
		}
		var options = SGPBPopup.getPopupOptionsById(popupId);
		var definedMaxCount = options['sgpb-sales-popup-count'];
		that.setPopupId(popupId);
		that.setPopupContent();
		that.setMaxCount(definedMaxCount);
		that.setOrders();
		that.hideShowCloseButton();
		var currentIndex = that.getCurrentCountIndex();
		var content = that.getPopupContent();
		content = that.doContentShortcode(content, currentIndex);
		jQuery('.sgpb-popup-builder-content-'+popupId).html(content);
		that.autoClose(e);
	});

	sgAddEvent(window, 'sgpbDidClose', function(e) {
		var args = e.detail;
		var popupId = parseInt(args.popupId);
		var popupContent = jQuery('.sgpb-recent-sales-popup-data-' + popupId).data('params');
		if (typeof popupContent == 'undefined') {
			return false;
		}
		SGPBPopup.prototype.sgpbDontShowPopup(popupId);
		var options = SGPBPopup.getPopupOptionsById(popupId);
		var definedCount = that.getOrders().length;
		var index = that.getCurrentCountIndex();
		var index = parseInt(index+1);
		if (index >= definedCount) {
			index = 0;
		}
		that.setCurrentCountIndex(index);
		that.reopenPopup(popupId);
	});
};

SGPBRecentSales.prototype.autoClose = function(e)
{
	var args = e.detail;
	var popupId = parseInt(args['popupId']);
	var popupData = args.popupData;

	var autoCloseStatus = SGPBPopup.varToBool(popupData['sgpb-auto-close-recent-sales']);
	if (autoCloseStatus) {
		var autoCloseTime = parseInt(popupData['sgpb-auto-close-time-recent-sales'])*1000;
		setTimeout(function() {
			SGPBPopup.closePopupById(popupId);
		}, autoCloseTime);
	}
};

SGPBRecentSales.prototype.reopenPopup = function(popupId)
{
	var allowToOpen = this.allowToOpen(popupId);
	if (allowToOpen == false) {
		return false;
	}
	var popupObj = this.getPopup();
	var popupOptions = SGPBPopup.getPopupOptionsById(popupId);
	var delayBetweenPopups = popupOptions['sgpb-sales-between-popup-delay'];
	var openingCount = popupOptions['sgpb-sales-popup-count'];
	popupObj.setPopupId(popupId);
	popupObj.setPopupData(popupOptions);
	this.openPopup(popupObj, delayBetweenPopups);
};

SGPBRecentSales.prototype.openPopup = function(popup, delay)
{
	var args = {countPopupOpen: false};
	delay = parseInt(delay) * 1000;
	setTimeout(function() {
		popup.open(args);
	}, delay);
};

SGPBRecentSales.prototype.allowToOpen = function(popupId)
{
	var dontShowPopupCookieName = 'sgDontShowPopup' + popupId;
	var dontShowPopup = SGPBPopup.getCookie(dontShowPopupCookieName);
	if (dontShowPopup != '') {
		return false;
	}
	return true;
};

SGPBRecentSales.prototype.hideShowCloseButton = function()
{
	popupId = this.getPopupId();
	var button = jQuery('.sgpb-content-'+popupId).parent().find('img').first();
	button.addClass('sg-popup-dont-show-1');
	button.hide();
	jQuery('.sgpb-content-'+popupId).on('mouseover', function() {
		button.show();
	});
	jQuery('.sgpb-content-'+popupId).on('mouseleave', function() {
		setTimeout(function(){
			button.hide();
		}, 2000);
	});
};

SGPBRecentSales.prototype.doContentShortcode = function(content, index)
{
	var supportedShortcodes = SgpbRecentSalesShortcodes;
	var newContent = '';
	var image = '';
	var replacedString = '';
	var popupId = this.getPopupId();
	var orders = this.getOrders();
	if (!orders.length) {
		return false;
	}

	for (var i = 0; i < SgpbRecentSalesShortcodes.length; i++) {
		if (SgpbRecentSalesShortcodes[i] != 'image') {
			replacedString = orders[index][SgpbRecentSalesShortcodes[i]];

			replacedString = this.getElementHtml(SgpbRecentSalesShortcodes[i], replacedString);
			content = content.replace('['+SgpbRecentSalesShortcodes[i]+']', replacedString);
		}
	}
	image = this.getImageHtml(orders[index]['image']);

	content = '<div class="sgpb-sales-main-wrapper">'+ image + '<div class="sgpb-sales-content-wrapper">' + content + '</div></div>';

	return content;
};

SGPBRecentSales.prototype.getElementHtml = function(index, value)
{
	value = this.filterValue(index, value);
	var htmlWrapperClass = 'sgpb-sales-' + index + '-wrapper';
	var html = '<div class="' + htmlWrapperClass + '">';
	html += value;
	html += '</div>';
	if (index == 'image') {
		html = this.getImageHtml(value);
	}

	return html;
};

SGPBRecentSales.prototype.getImageHtml = function(value)
{
	var html = '<div class="sgpb-sales-popup-image-wrapper">';
	html += '<img src="' + value + '">';
	html += '</div>';

	return html;
};

SGPBRecentSales.prototype.filterValue = function(index, value)
{
	if (index == 'time') {
		return SgpbRecentSalesTextLocalization.about+' ' + value + ' '+SgpbRecentSalesTextLocalization.ago;
	}

	return value;
};

SgpbEventListener.prototype.sgpbRecentSales = function(listenerObj, eventData)
{
	var recentSales = new SGPBRecentSales();
	if (typeof SgpbRecentSalesPopupData != 'undefined') {
		var popupObj = listenerObj.getPopupObj();
		recentSales.setPopup(popupObj);
		recentSales.setEventData(eventData);
		recentSales.init();
	}
};
