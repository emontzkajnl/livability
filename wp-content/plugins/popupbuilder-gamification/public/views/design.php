<?php
use \sgpbgamification\AdminHelper;
$savedImage = $popupTypeObj->getOptionValue('sgpb-gamification-gift-image');
$currentImage = AdminHelper::getImageNameFromSavedData($savedImage);
?>
<div class="formItem">
    <label class="formItem__title">
        <?php _e('Input styles', SG_POPUP_TEXT_DOMAIN); ?>:
    </label>
</div>
<div class="sgpb-bg-black__opacity-02 sgpb-padding-y-10">
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="gamification-text-width" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Placeholder', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input type="text" class="js-gamification-dimension" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="width" name="sgpb-gamification-text-placeholder" id="gamification-text-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-placeholder')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="gamification-text-width" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input type="text" class="js-gamification-dimension" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="width" name="sgpb-gamification-text-width" id="gamification-text-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-width')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="gamification-text-height" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input class="js-gamification-dimension" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="height" type="text" name="sgpb-gamification-text-height" id="gamification-text-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-height')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="gamification-text-border-width" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input class="js-gamification-dimension" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="border-width" type="text" name="sgpb-gamification-text-border-width" id="gamification-text-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-border-width')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="sgpb-gamification-text-border-radius" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input class="js-gamification-dimension" data-gamification-rel="js-gamification-submit-btn" data-field-type="text" data-style-type="border-radius" type="text" name="sgpb-gamification-text-border-radius" id="sgpb-gamification-btn-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-border-radius')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<div class="sgpb-color-picker-wrapper">
			<input class="sgpb-color-picker js-gamification-color-picker" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="background-color" type="text" name="sgpb-gamification-text-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-bg-color')); ?>" >
		</div>
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<div class="sgpb-color-picker-wrapper">
			<input class="sgpb-color-picker js-gamification-color-picker" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="border-color" type="text" name="sgpb-gamification-text-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-border-color')); ?>" >
		</div>
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<div class="sgpb-color-picker-wrapper">
			<input class="sgpb-color-picker js-gamification-color-picker" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="color" type="text" name="sgpb-gamification-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-color')); ?>" >
		</div>
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Placeholder color', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<div class="sgpb-color-picker-wrapper">
			<input class="sgpb-color-picker js-gamification-color-picker sgpb-full-width-events" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="placeholder" type="text" name="sgpb-gamification-text-placeholder-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-text-placeholder-color')); ?>" >
		</div>
	</div>
</div>


<!-- Input styles end -->
<div class="formItem">
    <label class="formItem__title">
        <?php _e('Button styles', SG_POPUP_TEXT_DOMAIN); ?>:
    </label>
</div>
<div class="sgpb-bg-black__opacity-02 sgpb-padding-y-10">
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="sgpb-gamification-btn-width" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Width', SG_POPUP_TEXT_DOMAIN)  ?>:
		</label>
		<input name="sgpb-gamification-btn-width" id="sgpb-gamification-btn-width" type="text" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-width'))?>" required>
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="sgpb-gamification-btn-height" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Height', SG_POPUP_TEXT_DOMAIN)  ?>:
		</label>
		<input name="sgpb-gamification-btn-height" id="sgpb-gamification-btn-height" type="text" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-height'))?>" required>
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="sgpb-gamification-btn-border-width" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input class="js-gamification-dimension" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="border-width" type="text" name="sgpb-gamification-btn-border-width" id="sgpb-gamification-btn-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-border-width')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="sgpb-gamification-btn-border-radius" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input class="js-gamification-dimension" data-gamification-rel="js-gamification-submit-btn" data-field-type="submit" data-style-type="border-radius" type="text" name="sgpb-gamification-btn-border-radius" id="sgpb-gamification-btn-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-border-radius')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="sgpb-gamification-btn-border-color" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<div class="sgpb-color-picker-wrapper">
			<input id="sgpb-gamification-btn-border-color" class="sgpb-color-picker js-gamification-color-picker" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="border-color" type="text" name="sgpb-gamification-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-border-color')); ?>" >
		</div>
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="gamification-btn-title" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Title', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input type="text" name="sgpb-gamification-btn-title" id="gamification-btn-title" class="js-gamification-btn-title" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-title')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label for="btn-progress-title" class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Title (in progress)', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<input type="text" name="sgpb-gamification-btn-progress-title" id="btn-progress-title" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-progress-title')); ?>">
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<div class="sgpb-color-picker-wrapper">
			<input class="sgpb-color-picker js-gamification-color-picker" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="background-color" type="text" name="sgpb-gamification-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-bg-color')); ?>" >
		</div>
	</div>
	<div class="subFormItem formItem sgpb-padding-x-20">
		<label class="subFormItem__title sgpb-margin-right-10">
			<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
		</label>
		<div class="sgpb-color-picker-wrapper">
			<input class="sgpb-color-picker js-gamification-color-picker" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="color" type="text" name="sgpb-gamification-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-btn-text-color')); ?>" >
		</div>
	</div>
</div>

<div class="formItem">
    <label for="sgpb-gamification-gift-image" class="formItem__title">
        <?php _e('Gift image', SG_POPUP_TEXT_DOMAIN)?>:
    </label>
</div>
<div class="row form-group">
    <div class="col-md-11">
        <?php echo AdminHelper::renderGiftIcons($currentImage); ?>
        <div class="sgpb-gift-conging-wrapper">
            <div class="sgpb-gift-btn-image-wrapper sgpb-gift-config-margin">
                <div class="sgpb-display-inline-block sgpb-show-gamification-image-container" style="background-image: url(<?php echo $popupTypeObj->getOptionValue('sgpb-gamification-gift-image'); ?>);">
                    <span class="sgpb-no-image"></span>
                </div>
            </div>
            <div class="sgpb-display-inline-block sgpb-gift-config-margin">
                <input id="js-gamification-upload-image-button" class="sgpb-btn sgpb-btn-blue sgpb-w-full sgpb-btn-small" type="button" value="<?php _e('Custom image', SG_POPUP_TEXT_DOMAIN);?>">
            </div>
            <div class="sgpb-gift-config-margin sgpb-display-inline-block js-sgpb-remove-gamification-image <?php echo ($savedImage == SGPB_GAMIFICATION_IMAGE_URL) ? ' sg-hide-remove-button' : '';?>">
                <input id="js-gamification-upload-image-remove-button" data-default-image-url="<?php echo SGPB_GAMIFICATION_IMAGE_URL; ?>" class="sgpb-btn sgpb-btn-danger sgpb-w-full sgpb-btn-small" type="button" value="<?php _e('Remove', SG_POPUP_TEXT_DOMAIN);?>">
            </div>
        </div>
    </div>
</div>
<div class="row form-group">
    <div>
        <div class="sgpb-button-image-uploader-wrapper">
            <input class="sg-hide" id="sgpb-gamification-gift-image" type="text" name="sgpb-gamification-gift-image" value="<?php echo esc_attr($savedImage); ?>">
        </div>
    </div>
    <div class="col-md-7">

    </div>
</div>
<div class="formItem">
    <label class="formItem__title sgpb-margin-right-10">
        <?php _e('Error message', SG_POPUP_TEXT_DOMAIN); ?>:
    </label>
    <input type="text" name="sgpb-gamification-error-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-gamification-error-message')); ?>">
</div>
<div class="formItem">
    <label class="formItem__title sgpb-margin-right-10">
        <?php _e('Invalid email message', SG_POPUP_TEXT_DOMAIN); ?>:
    </label>
    <input type="text" name="sgpb-gamification-invalid-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-gamification-invalid-message')); ?>">
</div>
<div class="formItem">
    <label for="gamification-validation-message" class="formItem__title sgpb-margin-right-10">
        <?php _e('Required field message', SG_POPUP_TEXT_DOMAIN); ?>:
    </label>
    <input type="text" name="sgpb-gamification-validation-message" id="gamification-validation-message" maxlength="90" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-validation-message')); ?>">
</div>
<div class="formItem">
    <label for="sgpb-gamification-gdpr-term" class="formItem__title sgpb-margin-right-10">
        <?php _e('GDPR terms', SG_POPUP_TEXT_DOMAIN); ?>:
    </label>
    <input type="text" name="sgpb-gamification-gdpr-terms" id="sgpb-gamification-gdpr-term" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-gamification-gdpr-term')); ?>">
</div>
