function SGPBAnalyticsBackend()
{

}

SGPBAnalyticsBackend.prototype.init = function()
{
	this.bindings();
};

SGPBAnalyticsBackend.prototype.bindings = function()
{
	this.analyticsChartSettings();
};

SGPBAnalyticsBackend.prototype.setEventListInCookie = function(list)
{
	var expirationDate = new Date();
	expirationDate.setDate(parseInt(expirationDate.getDate() + 60));
	var cookieExpirationData = expirationDate.toUTCString();
	var value = JSON.stringify(list)+ ';expires=' + cookieExpirationData + ';SameSite=Lax';
	document.cookie = 'sgpbAnalyticsEventList=' + value;
};

SGPBAnalyticsBackend.prototype.getEventsCheckboxesValues = function()
{
	var eventLists = [];

	jQuery('.sgpb-events-list-checkbox:checked').each(function() {
		var currentValue = jQuery(this).val();
		eventLists.push(currentValue);
	});

	return eventLists;
};

SGPBAnalyticsBackend.prototype.getLiveSettings = function()
{
	var settings = {};
	settings.dateRange = jQuery('.sgpb-date-range option:selected').val();
	settings.targetId = jQuery('.sgpb-targets option:selected').val();
	settings.eventLists = this.getEventsCheckboxesValues();

	return settings;
};

SGPBAnalyticsBackend.prototype.analyticsChartSettings = function()
{
	var that = this;
	var settings = this.getLiveSettings();

	this.getChartSettingsScriptsViaAjax(settings);

	jQuery('.sgpb-chart-change').bind('change', function() {
		var settings = that.getLiveSettings();
		that.getChartSettingsScriptsViaAjax(settings);
	});
	jQuery('.sgpb-events-list-checkbox').bind('change', function() {
		if (!jQuery('.sgpb-events-list-checkbox:checked').length) {
			jQuery(this).prop('checked', 'checked');
			alert('At least one event must be checked');
			return;
		}
		var settings = that.getLiveSettings();
		that.setEventListInCookie(settings.eventLists);
		that.getChartSettingsScriptsViaAjax(settings);
	});
};

SGPBAnalyticsBackend.prototype.getChartSettingsScriptsViaAjax = function(settings)
{
	var data = {
		action: 'sgpb_analytics_data',
		nonce: SGPB_ANALYTICS_PARAMS.nonce,
		settings: settings,
		beforSend: function() {
			jQuery('.sgpb-analytics-loading').removeClass('sg-hide-element');
		}
	};

	jQuery.post(ajaxurl, data, function(response) {
		var responseData = jQuery.parseJSON(response);
		jQuery('body').append(responseData['chartScript']);
		jQuery('#sgpb-popupular-popups').empty();
		jQuery('#sgpb-popupular-popups').append(responseData['activity']);
		jQuery('#sgpb-popupular-popups').removeClass('sgpb-display-none');
		jQuery('.sgpb-analytics-loading').addClass('sg-hide-element');
	});
};

jQuery(document).ready(function() {
	var obj = new SGPBAnalyticsBackend();
	obj.init();
});
