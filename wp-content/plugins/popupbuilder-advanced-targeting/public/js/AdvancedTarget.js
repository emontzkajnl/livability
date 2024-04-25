function SGPBAdvancedTargeting()
{
	this.popupData = {};
	this.id = null;

}

SGPBAdvancedTargeting.prototype.setPopupData = function(popupData)
{
	this.popupData = popupData;
};

SGPBAdvancedTargeting.prototype.getPopupData = function()
{
	return this.popupData;
};

SGPBAdvancedTargeting.prototype.setId = function(id)
{
	this.id = id;
};

SGPBAdvancedTargeting.prototype.getId = function()
{
	return this.id;
};

SGPBAdvancedTargeting.prototype.allowToOpen = function(popupId, popupObj)
{
	var popupData = popupObj.popupData;
	var conditions = popupData['sgpbConditions'];

	this.setId(popupId);
	this.setPopupData(popupData);

	for (var i in conditions) {
		var currentCondition = conditions[i];
		if (currentCondition.param == 'after_x_page') {
			return this.allowAfterXPages(currentCondition.value);
		}
	}

	return true;
};

SGPBAdvancedTargeting.prototype.forceAllowToOpen = function(popupId, popupObj)
{
	return this.allowToOpen(popupId, popupObj);
};

SGPBAdvancedTargeting.prototype.allowAfterXPages = function(afterPagesLimit)
{
	var popupData = this.getPopupData();
	var popupId = this.getId();

	if (afterPagesLimit) {
		this.setVisitedPageByPopupId(popupId);
	}
	var visitedPages = this.getVisitedPageByPopupId(popupId);

	return visitedPages.length > afterPagesLimit;
};

SGPBAdvancedTargeting.prototype.getVisitedPageByPopupId = function(popupId)
{
	var visitedPagesCookie = SGPopup.getCookie('sgpbVisitedPages');
	var visitedPages = [];

	if (!visitedPagesCookie) {
		return visitedPages;
	}
	visitedPagesCookie = jQuery.parseJSON(visitedPagesCookie);

	return visitedPagesCookie[popupId];
};

SGPBAdvancedTargeting.prototype.setVisitedPageByPopupId = function(popupId)
{
	var visitedPagesCookie = SGPopup.getCookie('sgpbVisitedPages');
	var visitedPages = {};
	var currentUrl = window.location.href;

	if (visitedPagesCookie) {
		visitedPages = jQuery.parseJSON(visitedPagesCookie);
	}

	var popupVisitedPages = visitedPages[popupId] || [];

	if (popupVisitedPages.indexOf(currentUrl) == '-1') {
		popupVisitedPages.push(currentUrl);
	}

	visitedPages[popupId] = popupVisitedPages;
	visitedPages = JSON.stringify(visitedPages);

	SGPBPopup.setCookie('sgpbVisitedPages', visitedPages, 365, false);
};
