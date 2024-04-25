function SGPBFormBuilder()
{
	this.fieldsJson = {};
	this.designOption = {};
	this.fieldsIndexes = [];
}

SGPBFormBuilder.prototype.init = function()
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
	this.changePadding();
	this.changeMargin();

	/*Live preview*/
	this.livePreview();
	this.sgpbModal = new SGPBModals();
	this.confirmBtn = '<button class="sgpb-btn sgpb-btn-blue" id="sgpbConfirmAddFieldBtn">Save</button>';

};

SGPBFormBuilder.prototype.accordionStyles = function()
{
	var that = this;
	jQuery('.js-checkbox-accordion-style-option').each(function () {
		that.fbCheckboxAccordionStyles(jQuery(this));
	});
	jQuery('.js-checkbox-accordion-style-option').on('change', function () {
		that.fbCheckboxAccordionStyles(jQuery(this));
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

SGPBFormBuilder.prototype.fbCheckboxAccordionStyles = function(element)
{
	if (!element.is(':checked')) {
		element.parents('.formItem').first().nextAll('div').first().css({'display': 'none'});
	}
	else {
		element.parents('.formItem').first().nextAll('div').first().css({'display': 'block'});
	}
};

SGPBFormBuilder.prototype.livePreview = function()
{
	var that = this;

	var fields = that.getFieldsJson();
	var fieldsData = JSON.stringify(fields);
	jQuery('#sgpb-subscription-fields-json').val(fieldsData);

	var designOptions = that.getDesignOption();
	var designOptionsData = JSON.stringify(designOptions);
	jQuery('#sgpb-subscription-fields-design-json').val(designOptionsData);

	if (!fields.length || !Object.keys(designOptions).length) {
		return false;
	}
	jQuery('.sgpb-subscription-plus-form-live-preview').addClass('sgpb-form-loading-hide');
	var popupId = jQuery('#post_ID').val();

	var data = {
		action: 'sgpb_subscription_plus_form_live_preview',
		fields: fields,
		popupId: popupId,
		designOptions: designOptions
	};
	jQuery.post(ajaxurl, data, function(res) {
		jQuery('.sgpb-subscription-plus-form-live-preview').html('<div class="sgpb-inputs-container">'+res);
		jQuery('.sgpb-subs-text-checkbox-gdpr').css('width', '300px');
		jQuery('.sgpb-js-form-loader-spinner').addClass('sgpb-hide');
		jQuery('.sgpb-subscription-plus-form-live-preview').removeClass('sgpb-form-loading-hide');
		that.preventDefaultSubmission();
	});
	/* enable inputs, remove attribute disabled to get the value */
	jQuery('.js-enable-color-picker-inputs').each(function() {
		jQuery(this).removeAttr('disabled');
	});
	SGPBSubscription.prototype.changeColor();
};

SGPBFormBuilder.prototype.changePadding = function()
{
	jQuery('.js-sgpb-form-padding').on('change keydown keyup', function() {
		var padding = jQuery(this).val();
		var paddingDirection = jQuery(this).attr('data-padding-direction');
		jQuery('.sgpb-subscription-admin-wrapper').css('padding-'+paddingDirection, padding + 'px');
	});
};

SGPBFormBuilder.prototype.changeMargin = function()
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
};

SGPBFormBuilder.prototype.preventDefaultSubmission = function()
{
	var formSubmitButton = jQuery('.sgpb-field-submit-wrapper input[type="submit"]');

	if (!formSubmitButton.length) {
		return false;
	}

	formSubmitButton.click(function(e) {
		e.preventDefault();
	});
};

SGPBFormBuilder.prototype.reinit = function()
{
	this.deleteField();
	this.editField();
	this.accordion();
	this.draggableFields();
};

SGPBFormBuilder.prototype.getCurrentJson = function()
{
	var that = this;
	var arrayClasses = [
		'.js-subs-additional-color-picker',
		'.js-enable-color-picker-inputs',
		'.js-subs-color-picker-inputs',
		'.js-subs-color-picker',
		'.js-sgpb-form-padding',
		'.js-sgpb-inputs-margin',
		'.js-sgpb-button-margin',
		'.js-subs-bg-opacity',
		'.js-subs-set-horizontally'
	];
	for (n in arrayClasses) {
		className = arrayClasses[n];
		jQuery(className).each(function() {
				jQuery(this).removeAttr('disabled');
		});
	}
	var fieldsJsonInput = jQuery('#sgpb-subscription-fields-json');
	var designOptionsInput = jQuery('#sgpb-fields-design-json');

	if (fieldsJsonInput.length && designOptionsInput.length) {
		var currentSettings = that.getFieldsJson();
		var designOptions = that.getDesignOption();
		currentSettings = JSON.stringify(currentSettings);
		designOptions = JSON.stringify(designOptions);

		fieldsJsonInput.val(currentSettings);
		designOptionsInput.val(designOptions);
	}
};

SGPBFormBuilder.prototype.getJsonFromInput = function()
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

SGPBFormBuilder.prototype.getDesignOptionsFromInput = function()
{
	var designJsonInput = jQuery('#sgpb-fields-design-json');

	if (!designJsonInput[0]) {
		return false;
	}
	var designOptionsJson = designJsonInput.val();
	designOptionsJson = jQuery.parseJSON(designOptionsJson);

	this.setDesignOption(designOptionsJson);
};

SGPBFormBuilder.prototype.setFieldsJson = function(fieldsJson)
{
	this.fieldsJson = fieldsJson;
};

SGPBFormBuilder.prototype.getFieldsJson = function()
{
	return this.fieldsJson;
};

SGPBFormBuilder.prototype.setDesignOption = function(designOption)
{
	this.designOption = designOption;
	this.livePreview();
};

SGPBFormBuilder.prototype.getDesignOption = function()
{
	return this.designOption;
};

SGPBFormBuilder.prototype.setFieldsIndexesFromJson = function(fieldsJson)
{
	var keys = Object.keys(fieldsJson);

	this.fieldsIndexes = keys;
};

SGPBFormBuilder.prototype.getMaxIndexFormArray = function(indexes)
{
	return Math.max.apply(Math, indexes);
};

SGPBFormBuilder.prototype.getMaxIndexFromSettingsJson = function()
{
	var indexes = this.getFieldsJson().length;

	return parseInt(indexes);
};

SGPBFormBuilder.prototype.editField = function()
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
		var currentEditableTemplate = jQuery('#sgpb-settings-'+type);
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

SGPBFormBuilder.prototype.fillTemplatesFromJson = function(index, settingsFieldsSelectors)
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

SGPBFormBuilder.prototype.changeSettingsValue = function(settingsOption, index)
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

SGPBFormBuilder.prototype.deleteField = function()
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
	});

	that.reindexFields();
};

SGPBFormBuilder.prototype.openModalToAddNewField = function()
{
	var fieldsList = jQuery('#sgpbSubscriptionPlusFieldsListShortHtml');
	var addButton = jQuery('.sgpb-subscription-plus-add-field-js');
	var that = this;
	var nextBtn = '<button class="sgpb-btn sgpb-btn-blue" id="sgpbNextFieldBtn">next</button>';

	addButton.bind('click', function() {
		jQuery(that.sgpbModal.modalContent('sgpbSubscriptionPlusFieldsListShortHtml', 'Select input', fieldsList.children(), nextBtn)).appendTo(document.body);
		that.sgpbModal.actionsCloseModal();
		that.fieldsBtnsEventsHandler();
	});
};

/* handle this function after opening the modal
* to show fields and for to select the field */
SGPBFormBuilder.prototype.fieldsBtnsEventsHandler = function()
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

SGPBFormBuilder.prototype.modalNextSTepEvHandler = function()
{
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
SGPBFormBuilder.prototype.modalConfirmationAction = function(newItemData = {}, currentIndex, settings = '')
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
		that.livePreview();
	})
};
SGPBFormBuilder.prototype.fillHtmlValues = function(element, values) {

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
SGPBFormBuilder.prototype.getSubmitButtonIndex = function()
{
	var submitButtonContainer = jQuery('div[data-type="submit"]');

	return submitButtonContainer.parent().attr('data-order-index');
};

SGPBFormBuilder.prototype.addSettingToFieldsJson = function(settings, currentIndex)
{
	var currentJson = this.getFieldsJson();
	currentJson.splice(currentIndex, 0, settings);
	this.setFieldsJson(currentJson);
};

SGPBFormBuilder.prototype.addNewFieldToListHtml = function(newItemData)
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
SGPBFormBuilder.prototype.renderChoices = function(choices)
{
	var choicesHtml = '';

	for (var i in choices) {
		var choice = choices[i];
		choicesHtml += this.getChoiceHtml(i, choice);
	}
	choicesHtml += '<button class="sgpb-btn sgpb-add-choice sgpb-border-radius-50 sgpb-padding-0"><i class="sgpb-icons icons_blue">L</i></button>';

	return choicesHtml;
};

SGPBFormBuilder.prototype.getChoiceHtml = function(i, choice)
{
	if (choice == null) {
		return '';
	}
	var choiceHtml = '<div class="formItem sgpb-choice-wrapper" data-index="'+i+'">';
	choiceHtml += '<div class="sgpb-choice-input-wrapper sgpb-flex-auto"><input type="text" class="sgpb-current-choice sgpb-width-100 sgpb-current-choice'+i+' sgpb-full-width-events " data-index="'+i+' sgpb-full-width-events" value="'+choice+'"></div>';
	choiceHtml += '<div class="sgpb-choice-config ">';
	choiceHtml += '<button class="sgpb-btn sgpb-delete-choice sgpb-margin-left-10 sgpb-padding-0" data-index="'+i+'"><i class="sgpb-icons  icons_pink" data-id="">I</i></button>';
	choiceHtml += '</div>';
	choiceHtml += '</div>';

	return choiceHtml;
};

SGPBFormBuilder.prototype.addChoice = function(settings)
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
		var choiceValue = 'choice';

		settings['choices'][currentIndex] = choiceValue;

		var currentChoiceHtml = that.getChoiceHtml(currentIndex, choiceValue);
		jQuery(this).before(currentChoiceHtml);
		that.setFieldsJson(settingsJson);
		that.choicesConfigReinit(settings);
	});
};

SGPBFormBuilder.prototype.updateChoice = function(settings)
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

SGPBFormBuilder.prototype.choicesConfigReinit = function(settings)
{
	this.addChoice(settings);
	this.deleteChoice(settings);
	this.updateChoice(settings);
};

SGPBFormBuilder.prototype.deleteChoice = function(settings)
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

SGPBFormBuilder.prototype.editDimensions = function()
{
	var dimension = jQuery('.js-subs-dimension');

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
	var colorPicker = jQuery('.js-subs-color-picker');

	if (!colorPicker.length) {
		return false;
	}

	colorPicker.wpColorPicker({
		change: function() {
			changeColorIntoJson(jQuery(this));
		}
	});
	jQuery('.wp-picker-holder').mouseover(function() {
		var selectedInput = jQuery(this).prev().find('.js-subs-color-picker');
		changeColorIntoJson(selectedInput);
	});
};

SGPBFormBuilder.prototype.getCSSSafeSize = function(dimension)
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

SGPBFormBuilder.prototype.accordion = function()
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

SGPBFormBuilder.prototype.checkboxAccordion = function(element)
{
	if (!element.is(':checked')) {
		element.parents('.sgpb-row-wrapper').first().nextAll('div').first().css({'display':'none'});
	}
	else {
		element.parents('.sgpb-row-wrapper').first().nextAll('div').first().css({'display':'inline-block'});
	}
};

SGPBFormBuilder.prototype.completeReordering = function()
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

SGPBFormBuilder.prototype.draggableFields = function(element)
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
				jQuery('.sgpb-subscription-plus-form-live-preview').css('opacity', '0.5');
			},
			stop: function() {
				that.completeReordering();
				jQuery('.sgpb-subscription-plus-form-live-preview').css('opacity', '1');
			}
		});
	});
};

SGPBFormBuilder.prototype.reindexFields = function()
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

SGPBFormBuilder.prototype.changeTab = function(evt)
{
	jQuery('#sgpb-subscription-plus-options-tab-content-wrapper-'+evt).css('display', 'none');
	var tabcontent, tablinks;
		tabcontent = jQuery('.sgpb-subscription-plus-options-tab-content-wrapper');
	tabcontent.each(function(){
		jQuery(this).css('display', 'none');
	});
	tablinks = jQuery('.sgpb-tab-subscription-plus-link');
	tablinks.each(function(){
		jQuery(this).removeClass('sgpb-tab-active');
	});
	jQuery('#sgpb-subscription-plus-options-tab-content-wrapper-'+evt).css('display', 'block');
	jQuery('.sgpb-subscription-plus-tab-'+evt).addClass('sgpb-tab-active');
	this.formBackgroundRangeSliderInit();
	SGPBSubscription.prototype.changeOpacity();
	SGPBSubscription.prototype.changeDimension();
};

SGPBFormBuilder.prototype.changeAdditionalColor = function()
{
	var that = this;
	var colorPicker = jQuery('.js-subs-additional-color-picker');
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

SGPBFormBuilder.prototype.setAdditionalColors = function()
{
	var that = this;
	var fields = jQuery('.js-subs-additional-color-picker');
	if (typeof fields == 'undefined') {
		return false;
	}

	var styleContent = '';
	jQuery('#sgpb-additional-styles').remove();
	var designOptions = this.getDesignOption();
	fields.each(function() {
		var elementClass = jQuery(this).attr('data-subs-rel');
		var styleType = jQuery(this).attr('data-style-type');
		var fieldType = jQuery(this).attr('data-field-type');
		var color = jQuery(this).val();

		if (fieldType == 'submit') {
			if (styleType == 'hover-background-color') {
				styleContent += '.'+elementClass+':hover {background-color: ' + color + ' !important;}';
			}
		}
		if (fieldType == 'input') {
			if (styleType == 'active-border-color') {
				styleContent += '.'+elementClass+':active,.'+elementClass+':focus {border-color: ' + color + ' !important;}';
			}
			if (styleType == 'label-color') {
				styleContent += '.sgpb-label-wrapper {color: ' + color + ' !important;}';
			}
			else {
				styleContent += '.'+elementClass+':active {border-color: ' + color + ' !important;}';
			}
		}
		designOptions[fieldType+'Styles'][styleType] = color;
		that.setDesignOption(designOptions);
	});
	jQuery('body').append('<style id="sgpb-additional-styles">' + styleContent + '</style>');
};

SGPBFormBuilder.prototype.formBackgroundRangeSliderInit = function()
{
	SGPBBackend.prototype.rangeSlider();
};

SGPBFormBuilder.prototype.changeIconLabel = function(index)
{
	var settingsJson = this.getFieldsJson();
	var currentSettings = settingsJson[index];
	jQuery('[data-order-index="'+index+'"] .sgpb-field-icon-wrapper .sgpb-field-display-name').html(currentSettings.fieldName);
};
jQuery(document).ready(function() {
	obj = new SGPBFormBuilder();
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


