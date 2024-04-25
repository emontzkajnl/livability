function SGPBAgeRestriction()
{this.SgpbAgeRestrictionParams=[];}
SGPBAgeRestriction.prototype.init=function()
{var that=this;that.declineButtonInit();that.confirmButtonInit();jQuery('html, body').addClass('sgpb-overflow-hidden');};SGPBAgeRestriction.prototype.allowToOpen=function(id)
{var canBeOpened=true;var cookieObject=SGPopup.getCookie('ageRestriction'+id);var SgpbAgeRestrictionParams=eval('SgpbAgeRestrictionParams'+id);if(cookieObject==''){return canBeOpened;}
var currentCookie=JSON.parse(cookieObject);if(typeof currentCookie==='undefined'){return canBeOpened;}
if(SgpbAgeRestrictionParams.cookieLevel){var currentUrl=window.location.href;if(currentCookie.length&&currentCookie.indexOf(currentUrl)!=-1){canBeOpened=false;}}
else{canBeOpened=false;}
return canBeOpened;};SGPBAgeRestriction.prototype.confirmButtonInit=function()
{var id=this.SgpbAgeRestrictionParams.popupId;var pageCookieLevel=this.SgpbAgeRestrictionParams.cookieLevel;var expTime=parseInt(this.SgpbAgeRestrictionParams.expirationTime);var saveChoice=this.SgpbAgeRestrictionParams.saveChoice;var currentUrl=location.href;var cookieName='ageRestriction'+id;jQuery('.sgpb-content-'+id+' #sgpb-yes-button').click(function(){if(saveChoice){if(SGPopup.getCookie(cookieName)==''){var cookieObject=[];cookieObject.push(currentUrl);SGPBPopup.setCookie(cookieName,JSON.stringify(cookieObject),expTime,pageCookieLevel);}
else{var cookieObject=SGPopup.getCookie(cookieName);var currentPopupCookieObject=JSON.parse(cookieObject);if(pageCookieLevel){if(currentPopupCookieObject.length&&currentPopupCookieObject.indexOf(currentUrl)==-1){currentPopupCookieObject.push(currentUrl);}
SGPBPopup.setCookie(cookieName,JSON.stringify(currentPopupCookieObject),expTime,pageCookieLevel);}
else{if(typeof currentPopupCookieObject!='undefined'){currentPopupCookieObject.push(currentUrl);SGPBPopup.setCookie(cookieName,JSON.stringify(cookieObject),expTime,pageCookieLevel);}}}}
jQuery('html, body').removeClass('sgpb-overflow-hidden');SGPBPopup.closePopupById(id);});};SGPBAgeRestriction.prototype.declineButtonInit=function()
{var SgpbAgeRestrictionParams=this.SgpbAgeRestrictionParams;var id=SgpbAgeRestrictionParams.popupId;jQuery('#sgpb-popup-dialog-main-div #sgpb-no-button').click(function(e){e.preventDefault();jQuery('html').css({overflow:'inherit'});if(SgpbAgeRestrictionParams.restrictionUrl==''){SGPBPopup.closePopupById(id);}
else{window.location.href=SgpbAgeRestrictionParams.restrictionUrl;}});};jQuery(document).ready(function(){sgAddEvent(window,'sgpbDidOpen',function(e){var args=e.detail;var popupId=args.popupId;try{var SgpbAgeRestrictionParams=eval('SgpbAgeRestrictionParams'+popupId);if(args.popupData['sgpb-type']==SgpbAgeRestrictionParams.ageRestrictionType){var objRestriction=new SGPBAgeRestriction();objRestriction.SgpbAgeRestrictionParams=SgpbAgeRestrictionParams;objRestriction.init();}}
catch(e){}});});