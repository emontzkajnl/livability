function SGPBWoo()
{
	this.savedEvents = [];
	this.cartArgs = [];
	this.popupId = 0;
	this.isOnLoad = false;
}

SGPBWoo.prototype.setId = function(popupId)
{
	this.popupId = popupId;
};

SGPBWoo.prototype.getId = function()
{
	return this.popupId;
};

SGPBWoo.prototype.isWooOptions = function(options){
	if (options.length > 1){
		return true;
	}
	return options[0].operator !== 'select_behavior'
};
SGPBWoo.prototype.setSavedEvents = function()
{
	var popupId = this.getId();
	var options = SGPBPopup.getPopupOptionsById(popupId);
	var events = false;

	if (typeof options['sgpb-woocommerce-special-events'] != 'undefined') {
		if (this.isWooOptions(options['sgpb-woocommerce-special-events'][0])){
			events = options['sgpb-woocommerce-special-events'][0];
		}
	}

	this.savedEvents = events;
};

SGPBWoo.prototype.getSavedEvents = function()
{
	return this.savedEvents;
};

SGPBWoo.prototype.setCartArgs = function()
{
	this.cartArgs = SgpbWooParams;
};

SGPBWoo.prototype.updateWooParams = function(cartArgs)
{
	window['SgpbWooParams'] = cartArgs;
};

SGPBWoo.prototype.getCartArgs = function()
{
	return this.cartArgs;
};

SGPBWoo.prototype.isSatisfy = function()
{
	var cartArgs = this.getCartArgs();
	var savedEvents = this.getSavedEvents();

	if (!Object.keys(savedEvents).length) {
		if (savedEvents == false) {
			return true;
		}

		return false;
	}

	for (var i in savedEvents) {
		var currentEvent = savedEvents[i];
		var condition = currentEvent['operator'];

		if (condition == 'select_behavior') {
			var satisfy = false;
			continue;
		}

		if (condition == 'product-is') {
			var savedProducts = Object.keys(currentEvent['value']);

			for (var key in savedProducts) {
				if (savedProducts.hasOwnProperty(key)) {
					if (cartArgs['products-ids'].indexOf(parseInt(savedProducts[key])) != -1) {
						satisfy = true;
						break;
					}
				}
			}
		}
		else if (condition == 'number-of-product') {
			satisfy = parseInt(cartArgs['number-of-product']) >= parseInt(currentEvent['value']);
		}
		else if (condition == 'number-of-product-lower') {
			satisfy = parseInt(cartArgs['number-of-product']) <= parseInt(currentEvent['value']);
		}
		else if (condition == 'total-price') {
			satisfy = parseInt(cartArgs['total-price']) >= parseInt(currentEvent['value']);
		}
		else if (condition == 'total-price-lower') {
			satisfy = parseInt(cartArgs['total-price']) <= parseInt(currentEvent['value']);

		}
		else if (condition == 'cart-is-empty') {
			satisfy = cartArgs['number-of-product'] == 0;
		}

		if (!satisfy) {
			break;
		}
	}

	return satisfy;
};

SGPBWoo.prototype.allowToOpen = function(popupId)
{
	this.setId(popupId);
	this.setSavedEvents();
	this.setCartArgs();

	return this.isSatisfy();
};

SGPBWoo.getWooPopupTypeByButtonClick = function(eventName)
{
	var popups = [];
	var popupsData = jQuery('.sg-popup-builder-content');
	if (!popupsData) {
		return popups;
	}

	popupsData.each(function() {
		var isWooPopup = false;
		var currentData = jQuery(this);
		var popupId = currentData.data('id');
		var popupData = currentData.data('options');
		var events = currentData.attr('data-events');
		events = jQuery.parseJSON(events);
		for (var i in events) {
			var event = events[i];
			if (event.value == eventName) {
				isWooPopup = true;
				break;
			}
		}

		if (isWooPopup) {
			var popupObj = new SGPBPopup();
			popupObj.setPopupId(popupId);
			popupObj.setPopupData(popupData);
			popups.push(popupObj);
		}

	});

	return popups;
};

SGPBWoo.openPopup = function(popup, delay)
{
	setTimeout(function() {
		popup.prepareOpen();
	}, delay);
};

SgpbEventListener.prototype.sgpbAddToCart = function(listenerObj, eventData)
{
	var popupId = listenerObj.popupObj.id;
	var wooPopupObj = new SGPBWoo();
	var value = eventData.value;

	if (value == SgpbWooGeneralParams.addToCartKey) {
		wooPopupObj.sgpbAddToCartEvent(popupId);
	}
	if (value == SgpbWooGeneralParams.removeFromCartKey) {
		wooPopupObj.sgpbRemoveFromCartEvent();
	}
};


SGPBWoo.prototype.sgpbAddToCartEvent = function(popupId)
{
	var that = this;
	that.isOnLoad = true;
	if (typeof sgpbNotAjaxAddedToCart != 'undefined') {
		if (that.allowToOpen(popupId)) {
			SGPBWoo.openByAddedToCart(sgpbNotAjaxAddedToCart);
		}
	}
	jQuery(document.body).bind('added_to_cart', function(e, cartArgs) {
			SGPBWoo.openByAddedToCart(cartArgs);
	});
};


SGPBWoo.openByAddedToCart = function(cartArgs)
{
	/*There is/are all popup(s) with the event add to cart*/
	var popups = SGPBWoo.getWooPopupTypeByButtonClick(SgpbWooGeneralParams.addToCartKey);
	var currentCartArgs = {
		'total-price': cartArgs['total-price'],
		'number-of-product': cartArgs['number-of-product'],
		'products-ids': cartArgs['products-ids']
	};

	for (var i in popups) {
		var popup = popups[i];
		var popupId = popup.getPopupId();
		var popupOptions = popup.getPopupData();
		var delay = parseInt(popupOptions['sgpb-popup-delay']) * 1000;

		var wooObj = new SGPBWoo();
		wooObj.updateWooParams(currentCartArgs);
		if (wooObj.allowToOpen(popupId)) {
			SGPBWoo.openPopup(popup, delay)
		}
	}
};

SGPBWoo.prototype.sgpbRemoveFromCartEvent = function()
{
	this.isOnLoad = true;
	jQuery(document.body).bind('updated_cart_totals', function() {
		SGPBWoo.openByRemovedFromCart();
	});
};

SGPBWoo.afterRemovingAllowToOpen = function()
{
	var popups = SGPBWoo.getWooPopupTypeByButtonClick(SgpbWooGeneralParams.removeFromCartKey);
	for (var i in popups) {
		var popup = popups[i];
		var popupId = popup.getPopupId();
		var popupOptions = popup.getPopupData();
		var delay = parseInt(popupOptions['sgpb-popup-delay']) * 1000;
		var wooObj = new SGPBWoo();
		if (wooObj.allowToOpen(popupId)) {
			SGPBWoo.openPopup(popup, delay)
		}
	}
};

SGPBWoo.openByRemovedFromCart = function()
{
	var ajaxData = {
			action : 'sgpb_woo_get_cart_items',
			nonce : SGPB_WOO_JS_PARAMS.nonce,
			data : 'remove'
		};

	jQuery.post(SGPB_WOO_JS_PARAMS.ajaxUrl, ajaxData, function(response) {
		window['SgpbWooParams'] = JSON.parse(response);
		SGPBWoo.afterRemovingAllowToOpen();
	});
}

jQuery(window).on('load', function() {
	var obj = new SGPBWoo();
	jQuery(document.body).bind('updated_cart_totals', function() {
		if (!obj.isOnLoad) {
			SGPBWoo.openByRemovedFromCart();
		}
	});

	jQuery(document.body).bind('wc_cart_emptied', function() {
		SGPBWoo.openByRemovedFromCart();
	})

	jQuery(document.body).bind('added_to_cart', function(e, cartArgs) {
		if (!obj.isOnLoad) {
			SGPBWoo.openByAddedToCart(cartArgs);
		}
	});
});
