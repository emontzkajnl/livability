function SGPBGeoTargeting()
{
}

SGPBGeoTargeting.prototype.init = function ()
{
	this.doAjax();
};
SGPBGeoTargeting.prototype.readAndWrite = function(response){
	var data = '';
	var mainScripts = '';
	var mainCss = '';
	response.map(i => {
		jQuery(i.footerContent).appendTo(document.body);
		i.scripts.scriptsData.map(d => {
			data += `<script id="${d.handle}-js-extra">var ${d.name} = ${JSON.stringify(d.data)}</script>`
		});
		i.scripts.mainScripts.map(url => {
			mainScripts += `<script src="${url.fileUrl}?${url.version}" id="${url.name}-js"></script>`
		});
		i.styles.map(url => {
			mainCss += `<link rel="stylesheet" href="${url.fileUrl}?${url.version}" id="${url.name}-style-css">`
		})
	});
	jQuery(mainCss).appendTo(document.head);
	jQuery(data).appendTo(document.body);
	jQuery(mainScripts).appendTo(document.body);

};
SGPBGeoTargeting.prototype.doAjax = function()
{
	var data = {
		nonce: SGPB_GEO_TARGETING_DATA.nonce,
		action: 'sgpb_geo_targeting_ajax',
		page_id: SGPB_GEO_TARGETING_DATA.page_id
	};
	var that = this;
	jQuery.post(SGPB_GEO_TARGETING_DATA.url, data, function(response) {
		response = JSON.parse(response);
		if (response && response.length) {
			that.readAndWrite(response)
		}
	});
};

jQuery(document).ready(function () {
	sgpbGeoTargeting = new SGPBGeoTargeting();
	sgpbGeoTargeting.init();
});
