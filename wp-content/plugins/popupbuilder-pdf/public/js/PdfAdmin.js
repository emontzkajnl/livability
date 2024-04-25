function SGPBPdfAdmin()
{this.init();}
SGPBPdfAdmin.prototype.init=function()
{this.pdfUpload();};SGPBPdfAdmin.prototype.pdfUpload=function()
{var supportedImageTypes=['application/pdf'];if(jQuery('#js-upload-pdf').val()){jQuery('.sgpb-show-pdf-container').html('');jQuery('.sgpb-show-pdf-container').css({'background-image':'url("'+jQuery("#js-upload-pdf").val()+'")'});}
var custom_uploader;jQuery('#js-upload-pdf-button').click(function(e){e.preventDefault();if(custom_uploader){custom_uploader.open();return;}
custom_uploader=wp.media.frames.file_frame=wp.media({titleFF:'Choose file',button:{text:'Choose file'},multiple:false,library:{type:'application/pdf'}});custom_uploader.on('select',function(){var attachment=custom_uploader.state().get('selection').first().toJSON();if(supportedImageTypes.indexOf(attachment.mime)===-1){alert(SGPB_JS_LOCALIZATION.pdfSupportAlertMessage);return;}
jQuery('.sgpb-show-pdf-container').css({'background-image':'url("'+attachment.url+'")'});jQuery('.sgpb-show-pdf-container').html('');jQuery('#js-upload-pdf').val(attachment.url);});custom_uploader.open();});};SGPBPdfAdmin.prototype.isUrlValid=function(iframeURL)
{var match=iframeURL.match(/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i);if(match==null){return false;}
return true;};SGPBPdfAdmin.prototype.isCompatibleWithProtocol=function(iframeURL)
{if(this.siteProtocol!='https:'){return true;}
var matchProtocol=iframeURL.indexOf(this.siteProtocol);if(matchProtocol=='-1'){return false;}
return true;};jQuery('document').ready(function(){new SGPBPdfAdmin();});