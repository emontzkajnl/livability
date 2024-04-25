function SGPBContactFormBuilder()
{
	this.fieldsJson = {};
	this.designOption = {};
	this.fieldsIndexes = [];
}

SGPBContactFormBuilder.prototype.init = function()
{
	this.getJsonFromInput();
	this.getDesignOptionsFromInput();
	this.accordion();
	this.accordionStyles();

	this.draggableFields();
	/*js crud*/
	this.openModalToAddNewField();
	this.deleteField();
	this.editField();
	this.getCurrentJson();

	/*js designEdit*/
	this.editDimensions();
	this.changeAdditionalColor();
	this.changeColor();
	this.changePadding();
	this.changeMargin();

	/*Live preview*/
	this.livePreview();
	this.sgpbModal = new SGPBModals();
	this.confirmBtn = '<button class="sgpb-btn sgpb-btn-blue" id="sgpbConfirmAddFieldBtn">Save</button>';

};

SGPBContactFormBuilder.prototype.manageDisallowDeleteToEmailAndPhone = function()
{
	var elementsToCheck = jQuery('div[data-type="phone"], div[data-type="email"], div[data-type="advancedphone"]');
	var elementsToCheckFields = jQuery('div[data-type="email"] .sgpb-delete-field, div[data-type="phone"] .sgpb-delete-field, div[data-type="advancedphone"] .sgpb-delete-field');
	if (elementsToCheck.length > 1){
		elementsToCheckFields.removeClass('sgpb-disallow-to-edit sgpb-tooltip');
		elementsToCheckFields.find('.sgpb-info-wrapper').remove();
	} else {
		elementsToCheckFields.addClass('sgpb-disallow-to-edit sgpb-tooltip');
		elementsToCheckFields.each(function () {
			switch (jQuery(this).closest('.sgpb-field-icon-wrapper').data('type')) {
				case 'email':
					jQuery(this).append('<div class="sgpb-info-wrapper"><span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">Add phone field in order to delete email field. Required for having contact info.</span></div>');
					break;
				default:
					jQuery(this).append('<div class="sgpb-info-wrapper"><span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">Add email field in order to delete phone field. Required for having contact info.</span></div>');
					break;
			}
		});
	}
};

SGPBContactFormBuilder.prototype.accordionStyles = function()
{
	var that = this;
	jQuery('.js-checkbox-accordion-style-option').each(function () {
		that.checkboxAccordionStyles(jQuery(this));
	});
	jQuery('.js-checkbox-accordion-style-option').on('change', function () {
		that.checkboxAccordionStyles(jQuery(this));
		if (this.checked) {
			jQuery(this).next('div').find('.sgpb-arrows').removeClass('sgpb-arrow-down')
		} else {
			jQuery(this).next('div').find('.sgpb-arrows').addClass('sgpb-arrow-down')
		}
	});

	jQuery('.sgpb-range-input').on('change', function () {
		jQuery(this).siblings('span').text(this.value);
	})
};

SGPBContactFormBuilder.prototype.checkboxAccordionStyles = function(element)
{
	if (!element.is(':checked')) {
		element.parents('.formItem').first().nextAll('div').first().css({'display': 'none'});
	}
	else {
		element.parents('.formItem').first().nextAll('div').first().css({'display': 'block'});
	}
};

SGPBContactFormBuilder.prototype.livePreview = function()
{
	var that = this;

	var fields = that.getFieldsJson();
	var fieldsData = JSON.stringify(fields);
	jQuery('#sgpb-contact-fields-json').val(fieldsData);

	var designOptions = that.getDesignOption();

	if (!fields.length || !Object.keys(designOptions).length) {
		return false;
	}
	jQuery('.sgpb-contact-form-live-preview').addClass('sgpb-form-loading-hide');
	var popupId = jQuery('#post_ID').val();

	var data = {
		action: 'sgpb_cf_form_live_preview',
		fields: fields,
		popupId: popupId,
		designOptions: designOptions
	};
	jQuery.post(ajaxurl, data, function(res) {
		jQuery('.sgpb-contact-form-live-preview').html('<div class="sgpb-inputs-container">'+res);
		jQuery('.sgpb-contact-text-checkbox-gdpr').css('width', '300px');
		jQuery('.sgpb-js-form-loader-spinner').addClass('sgpb-hide');
		jQuery('.sgpb-contact-form-live-preview').removeClass('sgpb-form-loading-hide');
		that.preventDefaultSubmission();
	});

	SGPBContactFormBuilder.prototype.changeColor();
};

SGPBContactFormBuilder.prototype.changeMargin = function()
{
	jQuery('.js-sgpb-inputs-margin').on('change keydown keyup', function() {
		var margin = jQuery(this).val();
		var marginDirection = jQuery(this).attr('data-inputs-margin-direction');
		jQuery('.sgpb-inputs-container .sgpb-each-field-main-wrapper').css('margin-' + marginDirection, margin + 'px');
	});

	jQuery('.js-sgpb-button-margin').on('change keydown keyup', function() {
		var margin = jQuery(this).val();
		var marginDirection = jQuery(this).attr('data-button-margin-direction');
		jQuery('.sgpb-button-container .sgpb-each-field-main-wrapper').css('margin-' + marginDirection, margin + 'px');
	});

	jQuery('.js-sgpb-message-margin').on('change keydown keyup', function() {
		var margin = jQuery(this).val();
		var marginDirection = jQuery(this).attr('data-message-margin-direction');
		jQuery('.sgpb-inputs-container .sgpb-field-textarea-wrapper').css('margin-' + marginDirection, margin + 'px');
	});
}

SGPBContactFormBuilder.prototype.changePadding = function()
{
	var that = this;
	jQuery('.js-sgpb-form-padding').on('change keydown keyup', function() {
		var padding = jQuery(this).val();
		var paddingDirection = jQuery(this).attr('data-padding-direction');
		jQuery('.sgpb-contact-admin-wrapper').css('padding-'+paddingDirection, padding + 'px');
		var designOptions = that.getDesignOption();
		designOptions['formStyles']['padding-'+paddingDirection] = padding;
		that.updateDesignOption(designOptions);
	});
};

SGPBContactFormBuilder.prototype.changeColor = function()
{
	var that = this;

	if (typeof jQuery.wp == 'undefined' || typeof jQuery.wp.wpColorPicker !== 'function') {
		return false;
	}
	jQuery('.js-contact-color-picker').each(function() {
		var currentColorPicker = jQuery(this);
		currentColorPicker.wpColorPicker({
			change: function() {
				that.colorPickerChange(currentColorPicker);
			}
		});
	});
};

SGPBContactFormBuilder.prototype.setupPlaceholderColor = function(element, color)
{
	jQuery('.'+element).each(function() {
		jQuery('.'+element+'-placeholder-color').remove();
		var styleContent = '.'+element+'::-webkit-input-placeholder {color: ' + color + ' !important;}';
		styleContent += '.'+element+'::-moz-placeholder {color: ' + color + ' !important;}';
		styleContent += '.'+element+'::-ms-placeholder {color: ' + color + ' !important;}';
		var styleBlock = '<style class="'+element+'-placeholder-color">' + styleContent + '</style>';
		jQuery('head').append(styleBlock);
	});
};


SGPBContactFormBuilder.prototype.colorPickerChange = function(colorPicker)
{
	var that = this;
	var opacity = jQuery('input[name=sgpb-contact-form-bg-opacity]').val();

	var colorValue = colorPicker.val();
	if (typeof colorValue == 'undefined') {
		return false;
	}
	colorValue = SGPBBackend.hexToRgba(colorValue, opacity);
	var styleType = colorPicker.attr('data-style-type');
	var selector = colorPicker.attr('data-contact-rel');

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

SGPBContactFormBuilder.prototype.preventDefaultSubmission = function()
{
	var formSubmitButton = jQuery('.sgpb-field-submit-wrapper input[type="submit"]');

	if (!formSubmitButton.length) {
		return false;
	}

	formSubmitButton.click(function(e) {
		e.preventDefault();
	});
};

SGPBContactFormBuilder.prototype.reinit = function()
{
	this.deleteField();
	this.editField();
	this.accordion();
	this.draggableFields();
};

SGPBContactFormBuilder.prototype.getCurrentJson = function()
{
	var that = this;
	var arrayClasses = [
		'.js-contact-additional-color-picker',
		'.js-enable-color-picker-inputs',
		'.js-contact-color-picker-inputs',
		'.js-contact-color-picker',
		'.js-sgpb-form-padding',
		'.js-contact-bg-opacity',
		'.js-sgpb-inputs-margin',
		'.js-sgpb-message-margin',
		'.js-sgpb-button-margin',
		'.js-contact-set-horizontally'
	];
	for (n in arrayClasses) {
		className = arrayClasses[n];
		jQuery(className).each(function() {
				jQuery(this).removeAttr('disabled');
		});
	}
	var fieldsJsonInput = jQuery('#sgpb-contact-fields-json');
	var designOptionsInput = jQuery('#sgpb-contact-fields-design-json');

	if (fieldsJsonInput.length && designOptionsInput.length) {
		var currentSettings = that.getFieldsJson();
		var designOptions = that.getDesignOption();
		currentSettings = JSON.stringify(currentSettings);
		designOptions = JSON.stringify(designOptions);

		fieldsJsonInput.val(currentSettings);
		designOptionsInput.val(designOptions);
	}
};

SGPBContactFormBuilder.prototype.getJsonFromInput = function()
{
	var jsonHiddenInput = jQuery('.sgpb-fields-json');

	if (!jsonHiddenInput.length) {
		return false;
	}

	var fieldsjson = jsonHiddenInput.val();
	fieldsjson = jQuery.parseJSON(fieldsjson);

	this.setFieldsJson(fieldsjson);
	this.setFieldsIndexesFromJson(fieldsjson);
};

SGPBContactFormBuilder.prototype.getDesignOptionsFromInput = function()
{
	var designJsonInput = jQuery('#sgpb-contact-fields-design-json');

	if (!designJsonInput[0]) {
		return false;
	}
	var designOptionsJson = designJsonInput.val();
	designOptionsJson = jQuery.parseJSON(designOptionsJson);

	this.setDesignOption(designOptionsJson);
};

SGPBContactFormBuilder.prototype.setFieldsJson = function(fieldsJson)
{
	this.fieldsJson = fieldsJson;
};

SGPBContactFormBuilder.prototype.getFieldsJson = function()
{
	return this.fieldsJson;
};

SGPBContactFormBuilder.prototype.setDesignOption = function(designOption)
{
	this.designOption = designOption;
	this.livePreview();
};

SGPBContactFormBuilder.prototype.updateDesignOption = function(designOption)
{
	this.designOption = designOption;
};

SGPBContactFormBuilder.prototype.getDesignOption = function()
{
	return this.designOption;
};

SGPBContactFormBuilder.prototype.setFieldsIndexesFromJson = function(fieldsJson)
{
	var keys = Object.keys(fieldsJson);

	this.fieldsIndexes = keys;
};

SGPBContactFormBuilder.prototype.getMaxIndexFormArray = function(indexes)
{
	return Math.max.apply(Math, indexes);
};

SGPBContactFormBuilder.prototype.getMaxIndexFromSettingsJson = function()
{
	var indexes = this.getFieldsJson().length;

	return parseInt(indexes);
};

SGPBContactFormBuilder.prototype.editField = function()
{
	var editSettings = jQuery('.sgpb-field-config .sgpb-edit-settings');

	if (!editSettings.length) {
		return false;
	}
	var that = this;
	editSettings.unbind('click').bind('click', function() {
		var currentElementWrapper = jQuery(this).parents('.sgpb-field-icon-wrapper').parent();
		var currentElementWrapperOld = currentElementWrapper.find('.sgpb-field-icon-wrapper');
		var type = currentElementWrapperOld.data('type');
		var index = currentElementWrapper.attr('data-order-index');
		var currentEditableTemplate = jQuery('.sgpb-settings-'+type);
		var settingsJson = that.getFieldsJson();
		var currentSettings = settingsJson[index];
		if (typeof currentSettings == 'undefined') {
			return false;
		}

		if (typeof currentSettings.choices != 'undefined') {
			var choicesHtml = that.renderChoices(currentSettings.choices);
			currentEditableTemplate.find('.sgpb-choices-wrapper').first().html(' ').append(choicesHtml);

			that.choicesConfigReinit(currentSettings);
		}

		that.fillTemplatesFromJson(index, currentElementWrapper);
		that.accordion();
		that.fillHtmlValues(currentEditableTemplate, currentSettings);
		jQuery(that.sgpbModal.modalContent(currentEditableTemplate.attr('id'),'Edit '+currentSettings.fieldName, currentEditableTemplate.children(), that.confirmBtn)).appendTo(document.body);
		that.sgpbModal.actionsCloseModal();
		that.modalConfirmationAction('', index);
	});
};

SGPBContactFormBuilder.prototype.fillTemplatesFromJson = function(index, settingsFieldsSelectors)
{
	var settingsFields = settingsFieldsSelectors.find('.sgpb-settings-field');

	if (!settingsFields.length) {
		return false;
	}
	var that = this;
	var settingsJson = this.getFieldsJson();
	var currentSettings = settingsJson[index];

	settingsFields.each(function() {
		that.changeSettingsValue(jQuery(this), index);
		var currentFieldType = jQuery(this).attr('type');
		var currentFieldKey = jQuery(this).data('key');
		var settingsValue = currentSettings[currentFieldKey];

		if (currentFieldType == 'checkbox') {
			jQuery(this).prop('checked', false);
			if (settingsValue) {
				jQuery(this).prop('checked', true);
			}
			return true;
		}

		jQuery(this).val(settingsValue);
	});
};

SGPBContactFormBuilder.prototype.changeSettingsValue = function(settingsOption, index)
{
	var that = this;
	settingsOption.each(function(e) {
		var settingsJson = that.getFieldsJson();
		var currentFieldType = jQuery(this).attr('type');
		var value = '';

		if (currentFieldType == 'checkbox') {
			value = 0;
			if (jQuery(this).is(':checked')) {
				value = 1;
			}
		}
		else {
			value =  jQuery(this).val();
		}

		var currentFieldKey = jQuery(this).data('key');
		settingsJson[index][currentFieldKey] = value;
		that.fieldsJson = settingsJson;
	});
};

SGPBContactFormBuilder.prototype.deleteField = function()
{
	var deleteButtons = jQuery('.sgpb-delete-field');

	if (!deleteButtons.length) {
		return false;
	}
	var that = this;

	deleteButtons.unbind('click').bind('click', function(e) {
		if (jQuery(this).hasClass('sgpb-disallow-to-edit')) {
			return false;
		}
		var settings = that.getFieldsJson();
		var currentElementWrapper = jQuery(this).parents('.sgpb-field-icon-wrapper').parent();
		var mustBeDeleted = currentElementWrapper.attr('data-order-index');
		delete settings[mustBeDeleted];
		var helperObj = [];
		for (var i in settings) {
			if (!settings[i]) {
				continue;
			}
			helperObj.push(settings[i]);
		}
		settings = helperObj;
		that.setFieldsJson(settings);
		that.livePreview();
		if (currentElementWrapper.hasClass('sgpb-disallow-to-edit')) {
			return false;
		}
		jQuery(currentElementWrapper).remove();

		var existElementCloseButton = jQuery('.sgpb-edit-settings-area .sgpb-field-close-current-settings');

		/*for close already exists element settings*/
		if (existElementCloseButton.length) {
			existElementCloseButton.click();
		}
		that.reindexFields();
		that.manageDisallowDeleteToEmailAndPhone();
	});
	that.reindexFields();
};

SGPBContactFormBuilder.prototype.getSubmitButtonIndex = function()
{
	var submitButtonContainer = jQuery('div[data-type="submit"]');
	
	return submitButtonContainer.parent().attr('data-order-index');
};

SGPBContactFormBuilder.prototype.openModalToAddNewField = function()
{
	var fieldsList = jQuery('#sgpbContactFormFieldsListShortHtml');
	var addButton = jQuery('.sgpb-contact-form-add-field-js');
	var that = this;
	var nextBtn = '<button class="sgpb-btn sgpb-btn-blue" id="sgpbNextFieldBtn">next</button>';

	this.manageDisallowDeleteToEmailAndPhone();
	addButton.bind('click', function() {
		jQuery(that.sgpbModal.modalContent('sgpbContactFormFieldsListShortHtml', 'Select input', fieldsList.children(), nextBtn)).appendTo(document.body);
		that.sgpbModal.actionsCloseModal();
		that.fieldsBtnsEventsHandler();
	});
};
/* handle this function after opening the modal
* to show fields and for to select the field */
SGPBContactFormBuilder.prototype.fieldsBtnsEventsHandler = function()
{
	var fieldsBtn = jQuery('.sgpb-fields-buttons');
	fieldsBtn.on('click', function() {
		if (jQuery(this).hasClass('active')){
			return
		}
		if (jQuery(this).parent().find('.active').length) {
			jQuery(this).parent().find('.active').removeClass('active')
		}
		jQuery(this).addClass('active')
	});
	this.modalNextSTepEvHandler();
};
SGPBContactFormBuilder.prototype.modalNextSTepEvHandler = function(){
	var that = this;
	var nextBtn = jQuery('#sgpbNextFieldBtn');
	nextBtn.on('click', function () {
		var activeField = jQuery('.sgpb-fields-buttons.active');
		if (!activeField.length) {
			return
		}
		var type = activeField.data('type');
		var settings = activeField.data('settings');
		var currentIndex = that.getSubmitButtonIndex();
		settings = Object.assign({}, settings);
		var newItemData = {
			type: type,
			settings: settings,
			currentIndex: currentIndex
		};
		var currentSettingsTemplate = jQuery('.sgpb-field-settings-wrapper#sgpb-settings-'+type);
		that.fillHtmlValues(currentSettingsTemplate, settings);

		that.sgpbModal.changeModalContentAdvanced(
			jQuery('.sgpb-modal'),
			newItemData.settings.fieldName,
			jQuery(currentSettingsTemplate.children()),
			that.confirmBtn,
			jQuery('.sgpb-modal').data('target'),
			`sgpb-settings-${type}`
		);

		if (typeof settings.choices != 'undefined') {
			var choicesHtml = that.renderChoices(settings.choices);
			jQuery('.sgpb-modal').find('.sgpb-modal-body').find('.sgpb-choices-wrapper').first().html(' ').append(choicesHtml);
			that.choicesConfigReinit(settings);
		}
		that.modalConfirmationAction(newItemData, currentIndex, settings);
	})
};
SGPBContactFormBuilder.prototype.fillHtmlValues = function(element, values) {

	jQuery(element).find('.sgpb-settings-field').each(function () {
		var dataKey = jQuery(this).data('key');
		var currentFieldType = jQuery(this).attr('type');

		if (currentFieldType == 'checkbox') {
			if (values[dataKey].toString().length) {
				jQuery(this).attr('checked', 'checked')
			} else {
				jQuery(this).removeAttr('checked');
			}
		} else {
			jQuery(this).val(values[dataKey]);
		}
	});
	this.accordion();

};
SGPBContactFormBuilder.prototype.modalConfirmationAction = function(newItemData = {}, currentIndex, settings = '')
{
	var that = this;
	var confirmationAction = jQuery('#sgpbConfirmAddFieldBtn');
	confirmationAction.on('click', function () {
		that.setFieldsIndexesFromJson(that.getFieldsJson());
		if (Object.keys(newItemData).length) {
			that.addNewFieldToListHtml(newItemData);
			that.addSettingToFieldsJson(settings, currentIndex);
		}
		that.changeSettingsValue(jQuery('.sgpb-modal').find('.sgpb-modal-body').find('.sgpb-settings-field'), currentIndex);

		that.reinit();
		that.reindexFields();
		that.changeIconLabel(currentIndex);
		that.sgpbModal.moveContentBeforeModalDestroy(jQuery('.sgpb-modal').data('target'), jQuery('.sgpb-modal'));
		that.sgpbModal.destroyModal('confirm');
		jQuery(document.body).removeClass('sgpb-overflow-hidden');
		if (newItemData.type === 'phone' || newItemData.type === 'email'|| newItemData.type === 'advancedphone') {
			that.manageDisallowDeleteToEmailAndPhone();
		}
		that.livePreview();
	})
};

SGPBContactFormBuilder.prototype.addSettingToFieldsJson = function(settings, currentIndex)
{
	var currentJson = this.getFieldsJson();
	currentJson.splice(currentIndex, 0, settings);
	this.setFieldsJson(currentJson);
	this.setFieldsIndexesFromJson(currentJson);
};

SGPBContactFormBuilder.prototype.addNewFieldToListHtml = function(newItemData)
{
	var fieldTemplate = jQuery('.sgpb-admin-current-field-template').html();

	fieldTemplate = fieldTemplate.replace(/sgTypeShortcode/g, newItemData.type);
	fieldTemplate = fieldTemplate.replace(/sgIndexShortcode/g, newItemData.currentIndex);
	fieldTemplate = fieldTemplate.replace(/sgDisplayNameShortcode/g, newItemData.settings.fieldName);
	var submitButtonContainer = jQuery('div[data-type="submit"]');
	submitButtonContainer.attr('data-index', newItemData.currentIndex + 1);
	submitButtonContainer.parent().before(fieldTemplate);
	setTimeout(function(){
		jQuery('.sgpb-field-icon-wrapper').each(function(){
			if (jQuery(this).attr('data-index') == newItemData.currentIndex) {
				jQuery(this).removeClass('sgpb-bbounce');
			}
		});
	}, 1000);
};

/*Choices Crud*/
SGPBContactFormBuilder.prototype.renderChoices = function(choices)
{
	var choicesHtml = '';

	for (var i in choices) {
		var choice = choices[i];
		choicesHtml += this.getChoiceHtml(i, choice);
	}
	choicesHtml += '<button class="sgpb-btn sgpb-add-choice sgpb-border-radius-50 sgpb-padding-0"><i class="sgpb-icons icons_blue">L</i></button>';

	return choicesHtml;
};

SGPBContactFormBuilder.prototype.getChoiceHtml = function(i, choice)
{
	if (choice == null) {
		return '';
	}
	var choiceHtml = '<div class="formItem sgpb-choice-wrapper" data-index="'+i+'">';
	choiceHtml += '<div class="sgpb-choice-input-wrapper sgpb-flex-auto"><input type="text" class="sgpb-current-choice sgpb-width-100 sgpb-current-choice'+i+' sgpb-full-width-events" data-index="'+i+' sgpb-full-width-events" value="'+choice+'"></div>';
	choiceHtml += '<div class="sgpb-choice-config">';
	choiceHtml += '<button class="sgpb-btn sgpb-delete-choice sgpb-margin-left-10 sgpb-padding-0" data-index="'+i+'"><i class="sgpb-icons  icons_pink" data-id="">I</i></button>';
	choiceHtml += '</div>';
	choiceHtml += '</div>';

	return choiceHtml;
};

SGPBContactFormBuilder.prototype.addChoice = function(settings)
{
	var addChoices = jQuery('.sgpb-add-choice');

	if (!addChoices.length) {
		return false;
	}
	var that = this;
	var settingsJson = that.getFieldsJson();

	addChoices.unbind('click').bind('click', function(e) {
		e.preventDefault();
		var currentIndex = jQuery(this).parent('.sgpb-choices-wrapper').children('.sgpb-choice-wrapper').last().data('index');
		currentIndex += 1;
		var choiceValue = 'Choice';

		settings['choices'][currentIndex] = choiceValue;

		var currentChoiceHtml = that.getChoiceHtml(currentIndex, choiceValue);
		jQuery(this).before(currentChoiceHtml);
		that.setFieldsJson(settingsJson);
		that.choicesConfigReinit(settings);
	});
};

SGPBContactFormBuilder.prototype.updateChoice = function(settings)
{
	var choiceInput = jQuery('.sgpb-choices-wrapper .sgpb-current-choice');

	if (!choiceInput.length) {
		return false;
	}
	var that = this;

	choiceInput.bind('change', function() {
		var currentChoiceIndex = jQuery(this).parents('.sgpb-choice-wrapper').first().data('index');
		settings['choices'][currentChoiceIndex] = jQuery(this).val();
		that.choicesConfigReinit(settings);
	});
};

SGPBContactFormBuilder.prototype.choicesConfigReinit = function(settings)
{
	this.addChoice(settings);
	this.deleteChoice(settings);
	this.updateChoice(settings);
};

SGPBContactFormBuilder.prototype.deleteChoice = function(settings)
{
	var choicesDelete = jQuery('.sgpb-choice-wrapper .sgpb-delete-choice');

	if (!choicesDelete.length) {
		return false;
	}

	choicesDelete.bind('click', function() {
		/*we will not allow to user delete the last choice*/
		if (jQuery(this).parents('.sgpb-choices-wrapper').first().find('.sgpb-choice-wrapper').length == 1) {
			return false;
		}
		var currentChoiceIndex = jQuery(this).data('index');
		delete settings['choices'][currentChoiceIndex];
		var currentChoiceWrapper = jQuery(this).closest('.sgpb-choice-wrapper').first();
		currentChoiceWrapper.remove();
	});
};

SGPBContactFormBuilder.prototype.editDimensions = function()
{
	var dimension = jQuery('.js-contact-dimension');

	if (!dimension) {
		return false;
	}
	var that = this;
	var designOptions = this.getDesignOption();
	var changeColorIntoJson = function (field) {
		var val = field.val();
		if (val.indexOf('#')) {
			val = that.getCSSSafeSize(val);
		}
		var fieldType = field.data('field-type');
		var styleType = field.data('style-type');

		if (typeof fieldType == 'undefined') {
			return false;
		}

		designOptions[fieldType+'Styles'][styleType] = val;
		that.setDesignOption(designOptions);
	};

	dimension.bind('change', function () {
		changeColorIntoJson(jQuery(this));
	});
	var colorPicker = jQuery('.js-contact-color-picker');

	if (!colorPicker.length) {
		return false;
	}

	colorPicker.wpColorPicker({
		change: function() {
			changeColorIntoJson(jQuery(this));
		}
	});
	jQuery('.wp-picker-holder').bind('click', function() {
		var selectedInput = jQuery(this).prev().find('.js-contact-color-picker');
		that.colorPickerChange(selectedInput);
		changeColorIntoJson(selectedInput);
	});
};

SGPBContactFormBuilder.prototype.getCSSSafeSize = function(dimension)
{
	var size;
	size =  parseInt(dimension)+'px';
	/*
		If user write dimension in px or % we give that dimension to target,
		or we added dimension in px
	 */
	if (dimension.indexOf('%') != -1 || dimension.indexOf('px') != -1) {
		size = dimension;
	}

	return size;
};

SGPBContactFormBuilder.prototype.accordion = function()
{
	var that = this;
	var element = jQuery('.js-form-enable-settings');
	element.each(function() {
		that.checkboxAccordion(jQuery(this));
	});

	element.click(function() {
		var elements = jQuery(this);
		that.checkboxAccordion(jQuery(this));
	});
};

SGPBContactFormBuilder.prototype.checkboxAccordion = function(element)
{
	if (!element.is(':checked')) {
		element.parents('.sgpb-row-wrapper').first().nextAll('div').first().css({'display':'none'});
	}
	else {
		element.parents('.sgpb-row-wrapper').first().nextAll('div').first().css({'display':'inline-block'});
	}
};

SGPBContactFormBuilder.prototype.completeReordering = function()
{
	var that = this;
	var current = that.getFieldsJson();
	var reordered = [];
	var items = jQuery('.sgpb-form-fields-main-wrapper');

	items.each(function() {
		var currentId = jQuery(this).attr('data-order-index');
		if (isNaN(parseInt(currentId))) {
			return true;
		}
		reordered.push(currentId);
	});
	var findishedSortedJson = [];
	for(var i in reordered) {
		var index = reordered[i];
		findishedSortedJson.push(current[index]);
	}
	var readyJson = JSON.stringify(findishedSortedJson);
	that.reindexFields();
	that.setFieldsJson(findishedSortedJson);
	that.setFieldsIndexesFromJson(findishedSortedJson);
	that.livePreview();
};

SGPBContactFormBuilder.prototype.draggableFields = function(element)
{
	var that = this;
	jQuery('.sgpb-current-fields-wrapper').sortable();

	jQuery('.sgpb-form-fields-main-wrapper').each(function() {
		jQuery(this).draggable({
			containment: 'parent',
			revert: 'invalid',
			connectToSortable: '.sgpb-current-fields-wrapper',
			axis: 'y',
			start: function() {
				jQuery('.sgpb-contact-form-live-preview').css('opacity', '0.5');
			},
			stop: function() {
				that.completeReordering();
				jQuery('.sgpb-contact-form-live-preview').css('opacity', '1');
			}
		});
	});
};

SGPBContactFormBuilder.prototype.reindexFields = function()
{
	var fieldsWrapper = jQuery('.sgpb-current-fields-wrapper');
	var index = 0;
	fieldsWrapper.find('.sgpb-form-fields-main-wrapper').each(function() {
		/* remove unnecessary styles which the jQuery UI Draggable has set */
		jQuery(this).removeAttr('style');
		var currentIndex = jQuery(this).attr('data-order-index');
		if (isNaN(parseInt(currentIndex))) {
			return true;
		}
		jQuery(this).attr('data-order-index', index);
		index++;
	});
};

SGPBContactFormBuilder.prototype.changeTab = function(evt)
{
	jQuery('#sgpb-contact-form-options-tab-content-wrapper-'+evt).css('display', 'none');
	var tabcontent, tablinks;
		tabcontent = jQuery('.sgpb-contact-form-options-tab-content-wrapper');
	tabcontent.each(function(){
		jQuery(this).css('display', 'none');
	});
	tablinks = jQuery('.sgpb-tab-contact-form-link');
	tablinks.each(function(){
		jQuery(this).removeClass('sgpb-tab-active');
	});
	jQuery('#sgpb-contact-form-options-tab-content-wrapper-'+evt).css('display', 'block');
	jQuery('.sgpb-contact-form-tab-'+evt).addClass('sgpb-tab-active');
	this.formBackgroundRangeSliderInit();
	SGPBContactForm.prototype.changeOpacity();
	SGPBContactForm.prototype.changeDimension();
};

SGPBContactFormBuilder.prototype.changeAdditionalColor = function()
{
	var that = this;
	var colorPicker = jQuery('.js-contact-additional-color-picker');
	if (!colorPicker.length) {
		return false;
	}

	colorPicker.wpColorPicker({
		change: function(event, ui) {
			var selectedColor = ui.color.toString();
			jQuery(this).val(selectedColor);
			that.setAdditionalColors();
		}
	});
};

SGPBContactFormBuilder.prototype.setAdditionalColors = function()
{
	var that = this;
	var fields = jQuery('.js-contact-additional-color-picker');
	if (typeof fields == 'undefined') {
		return false;
	}

	var styleContent = '';
	var styleBlock = '';
	jQuery('#sgpb-additional-styles').remove();
	var designOptions = this.getDesignOption();
	fields.each(function() {
		var elementClass = jQuery(this).attr('data-contact-rel');
		var styleType = jQuery(this).attr('data-style-type');
		var fieldType = jQuery(this).attr('data-field-type');
		var color = jQuery(this).val();

		if (fieldType == 'submit') {
			if (styleType == 'hover-background-color') {
				var opacity = jQuery('input[name=sgpb-contact-form-bg-opacity]').val();
				color = SGPBBackend.hexToRgba(color, opacity);
				styleContent += '.'+elementClass+':hover {background-color: ' + color + ' !important;}';
			}
		}
		if (fieldType == 'form') {
			if (styleType == 'background-color') {
				styleContent += '.'+elementClass+' {background-color: ' + color + ';}';
				SGPBContactForm.prototype.changeOpacity();
			}
		}
		if (fieldType == 'input') {
			if (styleType == 'active-border-color') {
				styleContent += '.'+elementClass+':active,.'+elementClass+':focus {border-color: ' + color + ' !important;}';
			}
			if (styleType == 'label-color') {
				styleContent += '.sgpb-label-wrapper label {color: ' + color + ';}';
			}
			else {
				styleContent += '.'+elementClass+':active {border-color: ' + color + ' !important;}';
			}
		}
		if (fieldType == 'message') {
			console.log(styleType);
			if (styleType == 'message-label-color') {
				styleContent += '.sgpb-label-textarea-wrapper label {color: ' + color + ';}';
			}
			if (styleType == 'message-active-border-color') {
				styleContent += '.'+elementClass+':active,.'+elementClass+':focus {border-color: ' + color + ' !important;}';
			}
			else {
				/*to do*/
				styleContent += '.'+elementClass+':active {border-color: ' + color + ' !important;}';
			}
		}
		designOptions[fieldType+'Styles'][styleType] = color;
		that.setDesignOption(designOptions);
		that.updateDesignOption(designOptions);
	});
	styleBlock = '<style id="sgpb-additional-styles">' + styleContent + '</style>';
	jQuery('body').append(styleBlock);
};

SGPBContactFormBuilder.prototype.changeIconLabel = function(index)
{
	var settingsJson = this.getFieldsJson();
	var currentSettings = settingsJson[index];
	jQuery('[data-order-index="'+index+'"] .sgpb-field-icon-wrapper .sgpb-field-display-name').html(currentSettings.fieldName);
};
SGPBContactFormBuilder.prototype.formBackgroundRangeSliderInit = function()
{
	SGPBBackend.prototype.rangeSlider();
};

jQuery(document).ready(function() {
	obj = new SGPBContactFormBuilder();
	obj.init();
	jQuery('#publish, #post-preview').click(function(e) {
		obj.getCurrentJson();
	});
});

/* we need do this action when page load is completed, because we need to gutenberg publish button to be loaded */
jQuery(window).on('load', function(){
	if (document.getElementsByClassName("editor-post-publish-button__button")[0]) {
		document.getElementsByClassName("editor-post-publish-button__button")[0].addEventListener('click', () => {
			obj.getCurrentJson();
		});
	}
});
