function SGPBGamificationBackend()
{

}

SGPBGamificationBackend.tabCookieName = 'SGPBGamificationActiveTab';

SGPBGamificationBackend.prototype.init = function()
{
    this.buttonImageUpload();
    this.buttonImageRemove();
    this.tabsLinks();
    this.changeGiftImage();
};

SGPBGamificationBackend.prototype.buttonImageUpload = function()
{
    var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
    var custom_uploader;
    jQuery('#js-gamification-upload-image-button').click(function(e) {
        e.preventDefault();

        /* If the uploader object has already been created, reopen the dialog */
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        /* Extend the wp.media object */
        custom_uploader = wp.media.frames.file_frame = wp.media({
            titleFF: SGPB_GAMIFICATION_ADMIN_PARAMS.chooseImage,
            button: {
                text: SGPB_GAMIFICATION_ADMIN_PARAMS.chooseImage
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        /* When a file is selected, grab the URL and set it as the text field's value */
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            if (supportedImageTypes.indexOf(attachment.mime) === -1) {
                alert(SGPB_JS_LOCALIZATION.imageSupportAlertMessage);
                return false;
            }
            jQuery('.sgpb-show-gamification-image-container').css({'background-image': 'url("' + attachment.url + '")'});
            jQuery('#sgpb-gamification-gift-image').val(attachment.url);
            jQuery('.js-sgpb-remove-gamification-image').removeClass('sg-hide-remove-button');
            jQuery('.sgpb-gift-icon').removeClass('sgpb-active-gift');
        });
        /* Open the uploader dialog */
        custom_uploader.open();
    });

    /* its finish image uploader */
};

SGPBGamificationBackend.prototype.buttonImageRemove = function()
{
    jQuery('#js-gamification-upload-image-remove-button').click(function() {
        var defaultImageURL = jQuery(this).data('default-image-url');

        jQuery('#sgpb-gamification-gift-image').val(defaultImageURL);
        jQuery(".sgpb-show-gamification-image-container").attr('style', 'background-image: url("' +defaultImageURL+ '")');
        jQuery('.js-sgpb-remove-gamification-image').addClass('sg-hide-remove-button');
        jQuery('.sgpb-gift-icon').removeClass('sgpb-active-gift');
        jQuery('.sgpb-gift-icon-1').addClass('sgpb-active-gift');
    });
};


SGPBGamificationBackend.prototype.tabsLinks = function()
{
    var tabs = jQuery('.sgpb-tab-link');

    if (!tabs) {
        return false;
    }
    var that = this;

    tabs.bind('click', function() {
        var currentKey = jQuery(this).data('key');
        var wrapper = jQuery(this).parents('.sgpb-tabs-content-wrapper').first();
        that.changeTab(currentKey, wrapper);
        that.setActiveTab(currentKey);
    });

    var wrapper = tabs.parents('.sgpb-tabs-content-wrapper').first();
    var key = jQuery('.sgpb-active-tab-name').val();
    if (!key) {
        key = wrapper.find('.sgpb-tab-link').first().data('key');
    }

    that.changeTab(key, wrapper);
    that.hideShowActiveTab();
};

SGPBGamificationBackend.prototype.setActiveTab = function(key)
{
    SGPopup.setCookie(SGPBGamificationBackend.tabCookieName, key);
};

SGPBGamificationBackend.prototype.getActiveTab = function()
{
    return SGPopup.getCookie(SGPBGamificationBackend.tabCookieName);
};

SGPBGamificationBackend.prototype.hideShowActiveTab = function()
{
    var activeTab = this.getActiveTab();
    if (!activeTab) {
        this.setActiveTab('contents');
        activeTab = 'contents';
    }
    jQuery('.sgpb-tab-link').each(function(){
        jQuery(this).removeClass('sgpb-tab-active');
    });
    jQuery('.sgpb-tab-content-wrapper').each(function(){
        jQuery(this).css({display: 'none'});
    });

    jQuery('#sgpb-tab-content-wrapper-'+activeTab).css({display: 'block'});
    jQuery('.sgpb-option-tab-' + activeTab).addClass('sgpb-tab-active');
};

SGPBGamificationBackend.prototype.changeTab = function(key, wrapper)
{
    var tabsContent = wrapper.find('.sgpb-tab-content-wrapper');
    tabsContent.each(function(){
        jQuery(this).css('display', 'none');
    });
    tablinks = wrapper.find('.sgpb-tab-link');
    tablinks.each(function(){
        jQuery(this).removeClass('sgpb-tab-active');
    });
    var currentLink = wrapper.find('.sgpb-option-tab-'+key).first();
    currentLink.css('display', 'block');
    currentLink.addClass('sgpb-tab-active');
    jQuery('#sgpb-tab-content-wrapper-'+key).css({display: 'block'});
};

SGPBGamificationBackend.prototype.changeGiftImage = function()
{
    var giftIcon = jQuery('.sgpb-gift-icon');

    if (!giftIcon.length) {
        return false;
    }

    giftIcon.bind('click', function() {
        jQuery('.sgpb-gift-icon').removeClass('sgpb-active-gift');
        jQuery(this).addClass('sgpb-active-gift');

        var currentImage = jQuery(this).data('image-name');
        if (currentImage != SGPB_GAMIFICATION_ADMIN_PARAMS.defaultImagename) {
            jQuery('.js-sgpb-remove-gamification-image').removeClass('sg-hide-remove-button');
        }
        else {
            jQuery('.js-sgpb-remove-gamification-image').addClass('sg-hide-remove-button');
        }
        var currentImageURL = SGPB_GAMIFICATION_ADMIN_PARAMS.imgURL+currentImage;
        jQuery('.sgpb-show-gamification-image-container').css({'background-image': 'url("' + currentImageURL + '")'});
        jQuery('#sgpb-gamification-gift-image').val(currentImageURL);
    });
}
;

jQuery(document).ready(function () {
    var obj = new  SGPBGamificationBackend();
    obj.init();
});
