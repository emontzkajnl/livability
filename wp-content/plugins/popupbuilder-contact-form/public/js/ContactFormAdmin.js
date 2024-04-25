function SGPBContactFormAdmin()
{
    this.init();
}

SGPBContactFormAdmin.prototype.init = function()
{
    this.deleteSubscribers();
    this.toggleCheckedSubscribers();
    this.deleteButtonHideShow();
    this.moreDetailPopup();
    this.filtersChange();
    this.exportContactedUsers();
};

SGPBContactFormAdmin.prototype.filtersChange = function()
{
    jQuery('.sgpb-contact-popup').on('change', function() {
        jQuery('.sgpb-contact-popup-id').val(jQuery(this).val());
    });
    jQuery('.sgpb-contact-date-list').on('change', function() {
        jQuery('.sgpb-contact-date').val(jQuery(this).val());
    })
};

SGPBContactFormAdmin.prototype.moreDetailPopup = function()
{
    var eachSubscriberDataBtn = jQuery('.sgpb-show-subscribers-additional-data-js');
    if (!eachSubscriberDataBtn.length) {
        return false;
    }
    var that = this;

    eachSubscriberDataBtn.click(function() {
        var subscriberData = jQuery(this).data('attr-subscriber-data');
        if (Object.keys(subscriberData).length) {
            that.showSubmittedDetailsPopup(subscriberData);
        }
    });
};

SGPBContactFormAdmin.prototype.showSubmittedDetailsPopup = function(submittedData)
{
    var sgpbModal = new SGPBModals();

    var header = 'Subscriber submitted data';
    var content = '';
    for (var fieldName in submittedData) {
        var label = fieldName;
        var value = submittedData[fieldName];
        if (!isNaN(parseInt(fieldName))) {
            var savedData = submittedData[fieldName];
            value = savedData.value;
            label = savedData.label;
        }
        content += '<div class="formItem "><div class="formItem__title  sgpb-flex-100 sgpb-margin-0 sgpb-text-capitalize"><b>'+label+'</b></div><div>'+value+'</div></div>';
    }

    sgpbModal.openModal(sgpbModal.modalContent('', header, content));
    sgpbModal.actionsCloseModal(true)
};


SGPBContactFormAdmin.prototype.deleteSubscribers = function()
{
    var checkedSubscribersList = [];
    var that = this;
    jQuery('.sgpb-contact-delete-button').bind('click', function() {
        var data = {};
        data.ajaxNonce = jQuery(this).attr('data-ajaxNonce');
        jQuery('.subs-delete-checkbox').each(function() {
            var isChecked = jQuery(this).prop('checked');
            if (isChecked) {
                var subscriberId = jQuery(this).attr('data-delete-id');
                checkedSubscribersList.push(subscriberId);
            }
        });
        if (checkedSubscribersList.length == 0) {
            alert(SGPB_CONTACT_JS_LOCALIZATION.selectLastOne);
        }
        else {
            var isSure = confirm(SGPB_CONTACT_JS_LOCALIZATION.areYouSure);
            if (isSure) {
                that.deleteSubscribersAjax(checkedSubscribersList, data);
            }
        }
    });
};

SGPBContactFormAdmin.prototype.changeCheckedSubscribers = function(bulkStatus)
{
    jQuery('.subs-delete-checkbox').each(function() {
        jQuery(this).prop('checked', bulkStatus);
        jQuery('.subs-bulk').prop('checked', bulkStatus);
        jQuery('.sgpb-contact-delete-button').removeClass('sgpb-btn-disabled');
        if (!bulkStatus) {
            jQuery('.sgpb-contact-delete-button').addClass('sgpb-btn-disabled');
        }
    });
};
SGPBContactFormAdmin.prototype.deleteButtonHideShow = function()
{
    if (!jQuery('.subs-delete-checkbox').length) {
        return false;
    }
    jQuery('.subs-delete-checkbox').on('click', function(){
        jQuery('.sgpb-contact-delete-button').removeClass('sgpb-btn-disabled');
        if (!jQuery('.subs-delete-checkbox').is(':checked')) {
            jQuery('.sgpb-contact-delete-button').addClass('sgpb-btn-disabled');
        }
    });
};
SGPBContactFormAdmin.prototype.toggleCheckedSubscribers = function()
{
    var that = this;
    jQuery('.subs-bulk').each(function() {
        jQuery(this).bind('click', function() {
            var bulkStatus = jQuery(this).prop('checked');
            that.changeCheckedSubscribers(bulkStatus);
            jQuery('.subs-bulk').prop('checked', bulkStatus);
        });
    });
};


SGPBContactFormAdmin.prototype.deleteSubscribersAjax = function(checkedSubscribersList)
{
    var data = {
        action: 'sgpb_sontacted_subscribers_delete',
        nonce: SGPB_JS_PARAMS.nonce,
        subscribersId: checkedSubscribersList,
        beforeSend: function() {
            jQuery('.sgpb-subscribers-remove-spinner').removeClass('sg-hide-element');
        }
    };

    jQuery.post(ajaxurl, data, function(response) {
        jQuery('.sgpb-subscribers-remove-spinner').addClass('sg-hide-element');
        jQuery('.subs-delete-checkbox').prop('checked', '');
        window.location.reload();
    });
};

SGPBContactFormAdmin.prototype.exportContactedUsers = function()
{
    var that = this;

    jQuery('.sgpb-contact-popup').on('change', function() {
        jQuery('.sgpb-contact-popup-id').val(jQuery(this).val());
    })
    jQuery('.sgpb-contact-date-list').on('change', function() {
        jQuery('.sgpb-contact-date').val(jQuery(this).val());
    })
    jQuery('.sgpb-export-contactform').bind('click', function() {
        var parameters = '';
        var params = {};
            params['sgpb-contact-popup-id'] = that.getUrlParameter('sgpb-contact-popup-id');
            params['s'] = that.getUrlParameter('s');
            params['sgpb-contact-date'] = that.getUrlParameter('sgpb-contact-date');
            params['orderby'] = that.getUrlParameter('orderby');
            params['order'] = that.getUrlParameter('order');
        for (var i in params) {
            if (typeof params[i] != 'undefined' && params[i] != '') {
                parameters += '&' + i + '=' + params[i];
            }
        }
        window.location.href = SGPB_JS_ADMIN_URL.url+'?action=cf_csv_file'+parameters;
    });
};

SGPBContactFormAdmin.prototype.getUrlParameter = function(key)
{
    var pageUrl = window.location.search.substring(1);
    var urlVariables = pageUrl.split('&');
    for (var i = 0; i < urlVariables.length; i++) {
        var param = urlVariables[i].split('=');
        if (param[0] == key) {
            if (typeof param[1] != 'undefined') {
                return param[1];
            }
            else {
                return '';
            }
        }
    }
};

jQuery(document).ready(function () {
    new SGPBContactFormAdmin();
});
