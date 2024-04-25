function SGPBPdfFrontend()
{this.init();};SGPBPdfFrontend.prototype.init=function()
{sgAddEvent(window,'sgpbDidOpen',function(e){var args=e.detail;var popupId=args.popupId;var iframeUrl=jQuery('.sgpb-pdf-iframe-'+popupId).attr('src');if(iframeUrl==''){iframeUrl=jQuery('.sgpb-pdf-iframe-'+popupId).attr('data-attr-src');}
jQuery('.sgpb-pdf-iframe-'+popupId).attr('src','');setTimeout(function(){jQuery('.sgpb-pdf-iframe-'+popupId).attr('src',iframeUrl);},500);});};jQuery('document').ready(function(){new SGPBPdfFrontend();});