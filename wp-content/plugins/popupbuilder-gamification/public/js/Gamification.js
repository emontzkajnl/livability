function SGPBGamification() {}

SGPBGamification.prototype.init = function()
{
	this.validateForms();
	this.animationAfterOpenPopup();
};

SGPBGamification.expTime = 365;
SGPBGamification.popupHeight = 0;

SGPBGamification.prototype.animationAfterOpenPopup = function() {
	sgAddEvent(window, 'sgpbDidOpen', function(e) {
		var args = e.detail;
		var popupId = args.popupId;
		SGPBGamification.popupHeight = jQuery('.sgpb-content-'+popupId).height();
		var gifts = jQuery('.sgpb-gifts-'+popupId+' .sgpb-gift');
		gifts.hide();
		var i = 0;
		gifts.each(function () {
			var current = jQuery(this);
			setTimeout(function () {
				current.show();
				current.addClass('sg-animated sgpb-bounceInUp');
			}, i*100);
			++i;
		});

		setTimeout(function(){
			window.dispatchEvent(new Event('resize'));
		}, 600);
	});
};

SGPBGamification.prototype.validateForms = function()
{
	var forms = jQuery('.sgpb-gamification-form');

	if (!forms.length) {
		return false;
	}
	var that = this;

	var validateObj = {
		rules: {
			'sgpb-subs-email': {
				email: true,
				required: true
			}
		},
		messages: {
		},
		success: function () {
			jQuery('.sgpb-email-validate-message').html('');
		}
	};

	forms.each(function () {
		var form = jQuery(this);
		var popupId = form.data('id');
		var message = jQuery(this).data('required-message');
		var emailMessage = jQuery(this).data('email-message');

		that.shakeForm(jQuery(this));
		validateObj['sgpb-subs-email'] = {
			required: message,
			email: emailMessage
		};
		validateObj.submitHandler = function() {
			that.submitForm(form, popupId);
		};
		form.validate(validateObj);
	});
};

SGPBGamification.prototype.getRandomPercentage = function () {
	return Math.random()*100;
};

SGPBGamification.prototype.playGame = function(popupId)
{
	var randomPercentage = this.getRandomPercentage();
	jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-gifts').addClass('sgpb-bigger');

	var i = 0;
	var gifts = jQuery('.sgpb-gifts-'+popupId+' .sgpb-gift');
	gifts.removeClass('sgpb-bounceInUp');

	gifts.each(function () {
		var current = jQuery(this);
		setTimeout(function () {
			current.show();
			current.addClass('sgpb-tada');
		}, i*100);
		++i;
	});

	jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-gifts').animate({
		marginTop: '-15%'
	}, 1000);
	jQuery('.sgpb-gift').bind('click', function() {
		if (jQuery(this).hasClass('sgpb-animate-double')) {
			return false;
		}
		if (randomPercentage <= SGPBPopup.getPopupOptionsById(popupId)['sgpb-gamification-win-chance']) {
			var giftsWrapper = jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-gifts');
			var selectedGift = jQuery(this);
			jQuery('.sgpb-gamification-play-text').animate(1000, function () {
				jQuery(this).css('visibility', 'hidden');
				var notSelectedGifts = jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-gift').not(selectedGift);

				jQuery('.sgpb-gifts-'+popupId).css({'margin-top': 0});

				/*where 20 is static margin from top*/
				var top = giftsWrapper.position().top+parseInt(giftsWrapper.css('margin-top'))-selectedGift.height()/2 - 200;
				selectedGift.addClass('sgpb-animate-double');

				var wrapper = jQuery('.sgpb-gamification-content-wrapper').width();
				/*Half width of gifts wrapper*/
				var wrapperWidth = wrapper/2;

				/*Initial position center of the current scaled gift*/
				var positionCenter = selectedGift.position().left+parseInt(selectedGift.css('margin-left'))+selectedGift.width()/2;

				notSelectedGifts.animate({ opacity: 0 }, 0);
				giftsWrapper.removeClass('sgpb-bigger');

				selectedGift.animate({
					'left': (wrapperWidth - positionCenter),
					'top': -top
				}, 1000, function () {
					setTimeout(function() {
						selectedGift.parent().after(jQuery('.sgpb-gamification-win-text'));
						selectedGift.parent().next('.sgpb-gamification-win-text').fadeIn(500);
					}, 500);
				});
				jQuery('.sgpb-gamification-form-'+popupId).css({'display': 'none'});
				jQuery('.sgpb-popup-builder-content-'+popupId+' .sgpb-gamification-start-header').css({'padding-top': '50px;'});
				jQuery(this).css({'position': 'relative'});
			});
		}
		else {
			/*Lose*/
			jQuery('.sgpb-gamification-play-text').fadeOut(1000);
			jQuery('.sgpb-gifts').fadeOut(1000, function () {
				jQuery('.sgpb-gamification-lose-text').fadeIn(800);
			});
		}
	});
};

SGPBGamification.prototype.shakeForm = function(form)
{
	jQuery('.sgpb-gift').bind('click', function () {
		jQuery(form).removeClass('sg-animated sgpb-shake');
		setTimeout(function () {
			jQuery(form).addClass('sg-animated sgpb-shake');
		}, 0)
	});
};

SGPBGamification.prototype.allowToOpen = function(id)
{
	var cookieObject = SGPopup.getCookie('SGPBGamification' + id);

	if (cookieObject) {
		return false;
	}

	return true;
};

SGPBGamification.prototype.submitForm = function(form, popupId)
{
	var that = this;
	jQuery('.sgpb-content-'+popupId).css('height', SGPBGamification.popupHeight+'px');

	var formData = form.serialize();
	var submitButton = jQuery(form).find('.js-gamification-submit-btn');
	var ajaxData = {
		action: 'sgpb_subscription_submission',
		nonce: SGPB_JS_PARAMS.nonce,
		beforeSend: function () {
			submitButton.val(submitButton.attr('data-progress-title'));
			submitButton.prop('disabled', true);
		},
		formData: formData,
		popupPostId: popupId
	};
	var cookieName = 'SGPBGamification' + popupId;
	var popupData = SGPBPopup.getPopupOptionsById(popupId);
	var alreadySubscribed = popupData['sgpb-gamification-already-subscribed'];
	jQuery.post(SGPB_JS_PARAMS.ajaxUrl, ajaxData, function (res) {
		if (jQuery('.sgpb-hide-form').length) {
			jQuery('.sgpb-gamification-win-text .sgpb-gamification-start-header').css('margin-top', '43px');
		}
		submitButton.prop('disabled', false);
		jQuery(form).animate({ opacity: 0 },1000);
		jQuery('.sgpb-gamification-gdpr-text').animate({ opacity: 0 },1000);
		jQuery('.sgpb-gamification-start-text').fadeOut(1000, function () {
			jQuery('.sgpb-gamification-play-text').fadeIn(1000);
		});

		jQuery(form).nextAll('.sgpb-gifts').first().addClass('sgpb-bigger');
		jQuery('#sg-popup-content-wrapper-'+popupId).addClass('sgpb-overflow-hidden');

		if (typeof alreadySubscribed != 'undefined' && alreadySubscribed) {
			SGPBPopup.setCookie(cookieName, 1, SGPBGamification.expTime);
		}

		that.playGame(popupId);
	});
};

jQuery(document).ready(function() {
	var obj = new  SGPBGamification();
	obj.init();
});
