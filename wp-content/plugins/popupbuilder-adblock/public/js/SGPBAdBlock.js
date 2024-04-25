function SGPBAdBlock()
{
	this.delay = 0;
	this.popupObj;
}

SGPBAdBlock.prototype.setDelay = function(delay)
{
	/*convert to milliseconds*/
	this.delay = parseInt(delay)*1000;
};

SGPBAdBlock.prototype.getDelay = function()
{
	return this.delay;
};

SGPBAdBlock.prototype.setPopupObj = function(popupObj)
{
	this.popupObj = popupObj;
};

SGPBAdBlock.prototype.getPopupObj = function()
{
	return this.popupObj;
};

SGPBAdBlock.prototype.openAdBlockPopup = function()
{
	var delay = parseInt(this.getDelay());
	var popupObj = this.getPopupObj();

	setTimeout(function() {
		popupObj.prepareOpen();
	}, delay)
};

SgpbEventListener.prototype.sgpbAdBlock = function(listenerObj, eventData)
{
	if( window.sgpbCanRunAds !== undefined ){
		return false;
	}
	var that = listenerObj;
	var delay = eventData.value;
	var popupObj = that.getPopupObj();

	var adBlock = new SGPBAdBlock();
	adBlock.setDelay(delay);
	adBlock.setPopupObj(popupObj);
	adBlock.openAdBlockPopup();
};