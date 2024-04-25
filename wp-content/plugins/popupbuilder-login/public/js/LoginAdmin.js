function SGPBLoginAdmin()
{

}

SGPBLoginAdmin.prototype.init = function()
{
	this.livePreview();
};

SGPBLoginAdmin.prototype.livePreview = function()
{
	this.binding();
	this.changePlaceholders();
	this.changeLabels();
	this.changeButtonTitle();
	this.changeColor();
	this.changeOpacity();
	this.changePadding();
	this.changeDimension();
	this.preventDefaultSubmission();
	if (typeof SGPBBackend == 'function') {
		SGPBBackend.makeContactAndSubscriptionFieldsRequired();
	}
};

SGPBLoginAdmin.prototype.binding = function()
{
	var that = this;

	jQuery('.js-checkbox-field-status').bind('click', function() {
		var isChecked = jQuery(this).is(':checked');
		var elementClassName = jQuery(this).attr('data-login-field-wrapper');
		var element = jQuery('.'+elementClassName);
		that.toggleVisible(element, isChecked);
	});

	jQuery('.js-checkbox-acordion').each(function() {
		var isChecked = jQuery(this).is(':checked');
		var elementClassName = jQuery(this).attr('data-login-rel');
		var element = jQuery('.'+elementClassName);
		that.toggleVisible(element, isChecked);
	});
};

SGPBLoginAdmin.prototype.toggleVisible = function(toggleElement, elementStatus)
{
	if (elementStatus) {
		toggleElement.css({'display': 'block'});
	}
	else {
		toggleElement.css({'display': 'none'});
	}
};

SGPBLoginAdmin.prototype.changePlaceholders = function()
{
	jQuery('.js-login-field-placeholder').each(function() {
		jQuery(this).bind('input', function() {
			var className = jQuery(this).attr('data-login-rel');
			var placeholderText = jQuery(this).val();
			jQuery('.'+className).attr('placeholder', placeholderText);
		});
	});
};

SGPBLoginAdmin.prototype.changeLabels = function()
{
	jQuery('.js-login-labels').each(function() {
		jQuery(this).bind('input', function() {
			var className = jQuery(this).attr('data-login-rel');
			var labelText = jQuery(this).val();
			jQuery('.'+className).text(labelText);
		});
	});
};

SGPBLoginAdmin.prototype.changeColor = function()
{
	var that = this;

	if (typeof jQuery.wp == 'undefined' || typeof jQuery.wp.wpColorPicker !== 'function') {
		return false;
	}

	jQuery('.js-login-color-picker').each(function() {
		var currentColorPicker = jQuery(this);
		currentColorPicker.wpColorPicker({
			change: function() {
				that.colorPickerChange(jQuery(this));
			}
		});
	});
	jQuery('.wp-picker-holder').mouseover(function() {
		var selectedInput = jQuery(this).prev().find('.js-login-color-picker');
		that.colorPickerChange(selectedInput);
	});
	jQuery('.wp-picker-holder').bind('click', function() {
		var selectedInput = jQuery(this).prev().find('.js-login-color-picker');
		that.colorPickerChange(selectedInput);
	});
};

SGPBLoginAdmin.prototype.changeOpacity = function()
{
	var that = this;
	jQuery('#js-login-bg-opacity').on('change', function() {
		that.colorPickerChange(jQuery('input[name=sgpb-login-form-bg-color]'));
		jQuery(this).siblings('span').text(this.value);
	});
};

SGPBLoginAdmin.prototype.changePadding = function()
{
	jQuery('.js-sgpb-form-padding').on('change keydown keyup', function() {
		var padding = jQuery(this).val();
		jQuery('.sgpb-login-form-admin-wrapper').css('padding', padding + 'px');
	});
};

SGPBLoginAdmin.prototype.colorPickerChange = function(colorPicker)
{
	var that = this;
	var opacity = jQuery('input[name=sgpb-login-form-bg-opacity]').val();

	var colorValue = colorPicker.val();
	colorValue = SGPBBackend.hexToRgba(colorValue, opacity);
	var styleType = colorPicker.attr('data-style-type');
	var selector = colorPicker.attr('data-login-rel');

	if ('placeholder' == styleType) {
		that.setupPlaceholderColor(selector, colorValue);
		return false;
	}

	var styleObj = {};
	styleObj[styleType] = colorValue;
	jQuery('.'+selector).each(function () {
		jQuery(this).css(styleObj);
	})
};

SGPBLoginAdmin.prototype.setupPlaceholderColor = function(element, color)
{
	jQuery('.'+element).each(function() {
		jQuery('#sgpb-placeholder-style').remove();
		var styleContent = '.'+element+'::-webkit-input-placeholder {color: ' + color + ' !important;}';
		styleContent += '.'+element+'::-moz-placeholder {color: ' + color + ' !important;}';
		styleContent += '.'+element+'::-ms-placeholder {color: ' + color + ' !important;}';
		var styleBlock = '<style id="sgpb-placeholder-style">' + styleContent + '</style>';
		jQuery('head').append(styleBlock);
	});
};

SGPBLoginAdmin.prototype.changeDimension = function()
{
	var that = this;
	jQuery('.js-login-dimension').change(function() {
		var element = jQuery(this);
		var dimension = that.changeDimensionMode(element.val());
		var styleType = element.attr('data-style-type');
		var fieldtype = element.attr('data-field-type');
		var selector = element.attr('data-login-rel');
		if (fieldtype == 'input') {
			jQuery('.sgpb-gdpr-label-wrapper').css('width', dimension);
			jQuery('.sgpb-gdpr-info').css('width', dimension);
		}
		var styleObj = {};
		styleObj[styleType] = dimension;

		jQuery('.'+selector).css(styleObj);
	});
};

SGPBLoginAdmin.prototype.changeDimensionMode = function(dimension)
{
	var size;
	size =  parseInt(dimension)+'px';
	/*If user write dimension in px or % we give that dimension to target or we added dimension in px*/
	if (dimension.indexOf('%') != -1 || dimension.indexOf('px') != -1) {
		size = dimension;
	}

	return size;
};

SGPBLoginAdmin.prototype.preventDefaultSubmission = function()
{
	var formSubmitButton = jQuery('.sgpb-login-form-admin-wrapper input[type="submit"]');

	if (!formSubmitButton.length) {
		return false;
	}

	formSubmitButton.bind('click', function(e) {
		e.preventDefault();
	});
};

SGPBLoginAdmin.prototype.changeButtonTitle = function()
{
	jQuery('.js-login-btn-title').bind('input', function() {
		var className = jQuery(this).attr('data-login-rel');
		var val = jQuery(this).val();
		jQuery('.'+className).val(val);
	});
};

jQuery(document).ready(function() {
	var login = new SGPBLoginAdmin();
	login.init();
});
