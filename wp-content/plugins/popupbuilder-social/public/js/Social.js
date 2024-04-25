function SGPBSocial()
{this.id=0;this.socialConfig={};if(typeof SGPB_SOCIAL!='undefined'){this.allSocials=SGPB_SOCIAL.socialNetworks;}}
SGPBSocial.prototype.setSocialConfig=function(socialConfig)
{this.socialConfig=socialConfig;};SGPBSocial.prototype.getSocialConfig=function()
{return this.socialConfig;};SGPBSocial.prototype.setId=function(id)
{this.id=parseInt(id);};SGPBSocial.prototype.getId=function()
{return parseInt(this.id);};SGPBSocial.prototype.init=function(id)
{this.setId(id);this.setConfig();this.changes();this.setupTheme();this.preventClickForAdminPreview();};SGPBSocial.prototype.preventClickForAdminPreview=function()
{if(!jQuery('.sgpb-socials-admin-wrapper').length){return false;}
jQuery('.sgpb-socials-admin-wrapper .jssocials-shares').bind('click',function(e){e.preventDefault();})};SGPBSocial.prototype.setConfig=function()
{var id=this.getId();var socialWrapper=jQuery('#sgpb-share-btns-container-'+id);var socialConfig=socialWrapper.attr('data-social-conf');socialConfig=jQuery.parseJSON(socialConfig);this.setSocialConfig(socialConfig);};SGPBSocial.prototype.reinitButtons=function()
{var id=this.getId();var config=this.getSocialConfig();var socialWrapper=jQuery('#sgpb-share-btns-container-'+id);socialWrapper.attr('data-social-conf',JSON.stringify(config)).jsSocials(config);jQuery('#sgpb-social-round-buttons').change();};SGPBSocial.prototype.setupTheme=function()
{if(!jQuery('.js-social-share-theme').length){return true;}
jQuery('#sgpb-social-theme').remove();var themeName=jQuery('.js-social-share-theme:checked').val();var jsSocialUrl=jQuery('.js-social-share-theme').attr('data-jssocial-url');var themeUrl=jsSocialUrl+'jssocials-theme-'+themeName+'.css';var themeCssLink=jQuery('<link>',{rel:'stylesheet',type:'text/css',id:'sgpb-social-theme',href:themeUrl});jQuery('head').append(themeCssLink);};SGPBSocial.prototype.changes=function()
{this.changeLabels();this.changeActiveNetworks();this.changeSocialTheme();this.changeButtonsSize();this.changeShareUrlType();this.changeShareUrl();this.changeShowLabel();this.changeShowCount();this.changeToRoundButtons();};SGPBSocial.prototype.changeToRoundButtons=function()
{if(!jQuery('#sgpb-social-round-buttons').length){return false;}
var id=this.getId();jQuery('#sgpb-social-round-buttons').change(function(){var isChecked=jQuery(this).is(':checked');if(isChecked){jQuery('#sgpb-share-btns-container-'+id+' .jssocials-share-link').addClass('js-social-round-btn');}
else{jQuery('#sgpb-share-btns-container-'+id+' .jssocials-share-link').removeClass('js-social-round-btn');}});};SGPBSocial.prototype.changeShowCount=function()
{if(!jQuery('.js-sgpb-social-share-count').length){return false;}
var that=this;var socialConfig=this.getSocialConfig();jQuery('.js-sgpb-social-share-count').change(function(){var val=jQuery(this).val();if(val=='false'){val=0;}
socialConfig.showCount=val;that.setSocialConfig(socialConfig);that.reinitButtons();});};SGPBSocial.prototype.changeShowLabel=function()
{if(!jQuery('#sgpb-social-show-labels').length){return false;}
var that=this;var socialConfig=this.getSocialConfig();jQuery('#sgpb-social-show-labels').change(function(){socialConfig.showLabel=jQuery(this).is(':checked');that.setSocialConfig(socialConfig);that.reinitButtons();});};SGPBSocial.prototype.changeShareUrlType=function(){if(!jQuery('.sgpb-share-url-type').length){return false;}
var that=this;var socialConfig=this.getSocialConfig();jQuery('.sgpb-share-url-type').each(function(){jQuery(this).change(function(){var val=jQuery(this).val();var url=window.location;if(val=='shareUrl'){url=jQuery('#sgpb-social-share-url').val();}
socialConfig.url=url;that.setSocialConfig(socialConfig);that.reinitButtons();})})};SGPBSocial.prototype.changeShareUrl=function()
{if(!jQuery('#sgpb-social-share-url').length){return false;}
var that=this;var socialConfig=this.getSocialConfig();jQuery('#sgpb-social-share-url').change(function(){socialConfig.url=jQuery(this).val();that.setSocialConfig(socialConfig);that.reinitButtons();});};SGPBSocial.prototype.changeButtonsSize=function()
{if(!jQuery('.js-sgpb-social-theme-size').length){return false;}
var id=this.getId();jQuery('.js-sgpb-social-theme-size').change(function(){var size=jQuery(this).val();size=parseInt(size)+'px';jQuery('#sgpb-share-btns-container-'+id).css({'font-size':size})});};SGPBSocial.prototype.changeSocialTheme=function()
{if(!jQuery('.js-social-share-theme').length){return false;}
var that=this;jQuery('.js-social-share-theme').change(function(){that.setupTheme();});};SGPBSocial.prototype.changeActiveNetworks=function()
{if(!jQuery('.js-social-network')){return false;}
var that=this;jQuery('.js-social-network').each(function(){jQuery(this).change(function(){var currentSocial={};var config=that.getSocialConfig();var socialName=jQuery(this).attr('data-social-name');var socialLabel=jQuery('#sgpb-social-label-'+socialName).val();currentSocial.share=socialName;currentSocial.label=socialLabel;if(config==null){var id=that.getId();var socialWrapper=jQuery('#sgpb-share-btns-container-'+id);var socialConfig=socialWrapper.attr('data-social-conf');config=jQuery.parseJSON(socialConfig);}
if(jQuery(this).is(':checked')){config.shares.push(currentSocial);config.shares=that.orderList(config.shares);that.setSocialConfig(config);that.reinitButtons();}
else{config=that.removeFormShareListByName(socialName);that.setSocialConfig(config);that.reinitButtons();}});});};SGPBSocial.prototype.getSocialIndexByName=function(socialName)
{var config=this.getSocialConfig();var shares=config.shares;var index=null;for(var socialIndex in shares){if(!shares.hasOwnProperty(socialIndex)){continue;}
if(shares[socialIndex]['share']==socialName){index=socialIndex;break;}}
index=parseInt(index);return index;};SGPBSocial.prototype.removeFormShareListByName=function(socialName)
{var config=this.getSocialConfig();var removedSocialIndex=this.getSocialIndexByName(socialName);if(typeof removedSocialIndex!='number'){return config;}
config.shares.splice(removedSocialIndex,1);return config;};SGPBSocial.prototype.orderList=function(shares)
{var networksList=this.allSocials;var orderList=[];for(var socialId in networksList){if(!networksList.hasOwnProperty(socialId)){continue;}
var name=networksList[socialId];for(var i in shares){if(!shares.hasOwnProperty(i)){continue;}
if(shares[i]['share']==name){orderList.push(shares[i]);break;}}}
return orderList;};SGPBSocial.prototype.changeLabels=function()
{if(!jQuery('.js-sgpb-social-label').length){return false;}
jQuery('.js-sgpb-social-label').each(function(){jQuery(this).on('input',function(){var label=jQuery(this).val();var socialName=jQuery(this).attr('data-social-name');jQuery('.jssocials-share-'+socialName+' .jssocials-share-label').html(label);});})};