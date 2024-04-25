<?php
use sgpbrs\DefaultOptionsData;
use sgpb\AdminHelper;
use sgpb\MultipleChoiceButton;
use sgpb\Functions;
use sgpblogin\AdminHelper as AdminHelperLogin;

$popupId = 0;
$defaultData = AdminHelperLogin::defaultData();
$loginSubPopups = $popupTypeObj->getPopupsIdAndTitle();
$successPopup = $popupTypeObj->getOptionValue('sgpb-subs-success-popup');

if (!empty($_GET['post'])) {
	$popupId = (int)$_GET['post'];
}

$forceRtlClass = '';
$forceRtl = $popupTypeObj->getOptionValue('sgpb-force-rtl');
if ($forceRtl) {
	$forceRtlClass = ' sgpb-forms-preview-direction';
}
?>
<style>
	.sgpb-wrapper input[type=checkbox]:checked:before,
	.sgpb-wrapper input[type=checkbox]:after {
		content: none;
	}
</style>
<div class="sgpb ">
	<div class="sgpb-wrapper formItem">
		<div class="sgpb-display-flex sgpb-padding-10 sgpb-width-100">
			<div class="sgpb-width-60 sgpb-padding-x-20">
				<!-- form background options start -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e('Form background options', SG_POPUP_TEXT_DOMAIN); ?>
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Form background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-login-color-picker" data-login-rel="sgpb-login-form-admin-wrapper" data-style-type="background-color" type="text" name="sgpb-login-form-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-form-bg-color')); ?>" autocomplete="off">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="content-padding" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Form background opacity', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-slider-wrapper">
							<div class="slider-wrapper sgpb-display-inline-flex">
								<input type="range" class="sgpb-range-input js-subs-bg-opacity sgpb-margin-right-10 "
								       name="sgpb-login-form-bg-opacity"
								       id="js-login-bg-opacity" min="0.0" step="0.1" max="1" value="<?php echo $popupTypeObj->getOptionValue('sgpb-login-form-bg-opacity'); ?>" rel="<?php echo $popupTypeObj->getOptionValue('sgpb-login-form-bg-opacity'); ?>">
								<span class="js-login-bg-opacity"><?php echo $popupTypeObj->getOptionValue('sgpb-login-form-bg-opacity')?></span>
							</div>
						</div>

					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-form-padding" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Form padding', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-login-form-padding'))?>" class="sgpb-margin-x-10 js-sgpb-form-padding" id="sgpb-login-form-padding" name="sgpb-login-form-padding" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-login-form-padding'))?>" autocomplete="off">
						<span class="sgpb-restriction-unit">px</span>
					</div>
				</div>

				<!-- username field -->
				<div class="formItem">
					<label for="sgpb-username-label" class="formItem__title">
						<?php _e('Username', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-username-label" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Label', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input id="sgpb-username-label" class="js-login-username-label js-login-labels" data-login-rel="js-login-username-label-edit" type="text" name="sgpb-username-label" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-username-label')); ?>" >
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-username-placeholder" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Placeholder', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input id="sgpb-username-placeholder" class="js-login-field-placeholder js-login-username-input" data-login-rel="js-login-username-input" type="text" name="sgpb-username-placeholder" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-username-placeholder')); ?>" >
					</div>
				</div>

				<!-- password field -->
				<div class="formItem">
					<label for="sgpb-password-label" class="formItem__title">
						<?php _e('Password', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-password-label" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Label', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input id="sgpb-password-label" class="js-login-password-label js-login-labels" type="text" name="sgpb-password-label" data-login-rel="js-login-password-label-edit" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-password-label')); ?>" >
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-password-placeholder" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Placeholder', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input id="sgpb-password-placeholder" class="js-login-field-placeholder js-login-password-input" data-login-rel="js-login-password-input"  type="text" name="sgpb-password-placeholder" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-password-placeholder')); ?>" >
					</div>
				</div>

				<!-- remember me -->
				<div class="formItem">
					<label for="sgpb-remember-me-status" class="formItem__title sgpb-margin-right-10">
						<?php _e('Remember me', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
						<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-checkbox-field-status" id="sgpb-remember-me-status" name="sgpb-remember-me-status"
						       data-login-field-wrapper="js-remember-me-wrapper"
							<?php echo $popupTypeObj->getOptionValue('sgpb-remember-me-status'); ?>>
						<label class="sgpb-onOffSwitch__label" for="sgpb-remember-me-status">
							<span class="sgpb-onOffSwitch-inner"></span>
							<span class="sgpb-onOffSwitch-switch"></span>
						</label>
					</div>
				</div>
				<div class="sg-full-width sgpb-padding-x-20 sgpb-bg-black__opacity-02">
					<div class="formItem">
						<label for="sgpb-remember-me-label" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Label', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input id="sgpb-remember-me-label" class="js-login-remember-me-label js-login-labels" data-login-rel="js-login-remember-me-label-edit" type="text" name="sgpb-remember-me-label" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-remember-me-label')); ?>" >
					</div>
				</div>
				<!-- input styles -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e('Inputs\' style', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 sgpb-padding-y-10">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-text-width" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input type="text" class="js-login-dimension" data-field-type="input" data-login-rel="js-login-text-inputs" data-style-type="width" name="sgpb-login-text-width" id="sgpb-login-text-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-text-width')); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-text-height" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input class="js-login-dimension" data-login-rel="js-login-text-inputs" data-style-type="height" type="text" name="sgpb-login-text-height" id="sgpb-login-text-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-text-height')); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-text-border-width" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input class="js-login-dimension" data-login-rel="js-login-text-inputs" data-style-type="border-width" type="text" name="sgpb-login-text-border-width" id="sgpb-login-text-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-text-border-width')); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-login-color-picker" data-login-rel="js-login-text-inputs" data-style-type="background-color" type="text" name="sgpb-login-text-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-text-bg-color')); ?>">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-login-color-picker" data-login-rel="js-login-text-inputs" data-style-type="border-color" type="text" name="sgpb-login-text-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-text-border-color')); ?>">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-login-color-picker" data-login-rel="js-login-text-inputs" data-style-type="color" type="text" name="sgpb-login-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-text-color')); ?>" >
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Placeholder color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-login-color-picker sgpb-full-width-events" data-login-rel="js-login-text-inputs" data-style-type="placeholder" type="text" name="sgpb-login-text-placeholder-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-text-placeholder-color')); ?>" >
						</div>
					</div>
				</div>

				<!-- error messages -->
				<div class="formItem">
					<label for="sgpb-login-required-error" class="formItem__title">
						<?php _e('Required field message', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<input id="sgpb-login-required-error" type="text" name="sgpb-login-required-error" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-required-error')); ?>" >
				</div>

				<div class="formItem">
					<label for="sgpb-login-error-message" class="formItem__title">
						<?php _e('Custom error message', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
						<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion" id="sgpb-custom-error-message" name="sgpb-custom-error-message"
						       data-login-field-wrapper="js-remember-me-wrapper"
							<?php echo $popupTypeObj->getOptionValue('sgpb-custom-error-message'); ?>>
						<label class="sgpb-onOffSwitch__label" for="sgpb-custom-error-message">
							<span class="sgpb-onOffSwitch-inner"></span>
							<span class="sgpb-onOffSwitch-switch"></span>
						</label>
					</div>
				</div>
				<div class="sg-full-width sgpb-padding-x-20 sgpb-bg-black__opacity-02">
					<div class="formItem">
						<label for="sgpb-custom-login-error-message" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Message', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input id="sgpb-custom-login-error-message" type="text" name="sgpb-custom-login-error-message" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-custom-login-error-message')); ?>" >
					</div>
				</div>

				<!-- submit styles -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e('Login button styles', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 sgpb-padding-y-10">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-btn-width" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input class="js-login-dimension" data-login-rel="js-login-submit-btn" data-style-type="width" type="text" name="sgpb-login-btn-width" id="sgpb-login-btn-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-btn-width')); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-btn-height" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input class="js-login-dimension" data-login-rel="js-login-submit-btn" data-style-type="height" type="text" name="sgpb-login-btn-height" id="sgpb-login-btn-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-btn-height')); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-btn-title" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Title', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input type="text" name="sgpb-login-btn-title" id="sgpb-login-btn-title" class="js-login-btn-title" data-login-rel="js-login-submit-btn" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-btn-title')); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-login-btn-progress-title" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Title (in progress)', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<input type="text" name="sgpb-login-btn-progress-title" id="sgpb-login-btn-progress-title" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-btn-progress-title')); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-login-color-picker" data-login-rel="js-login-submit-btn" data-style-type="background-color" type="text" name="sgpb-login-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-btn-bg-color')); ?>" >
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-login-color-picker" data-login-rel="js-login-submit-btn" data-style-type="color" type="text" name="sgpb-login-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-login-btn-text-color')); ?>" >
						</div>
					</div>
				</div>

				<!-- after successful login -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e('After successful login', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
					<?php
					$multipleChoiceButton = new MultipleChoiceButton($defaultData['loginSuccessBehavior'], $popupTypeObj->getOptionValue('sgpb-login-success-behavior'));
					echo $multipleChoiceButton;
					?>
				</div>

				<div class="formItem">
					<label for="sgpb-already-login-message" class="formItem__title sgpb-margin-right-10">
						<?php _e('Already logged in message', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<input id="sgpb-already-login-message" type="text" name="sgpb-already-login-message" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-already-login-message')); ?>">
				</div>
				<div class="sg-hide sg-full-width sgpb-padding-x-20" id="login-redirect-to-URL">
					<div class="formItem subFormItem">
						<label for="sgpb-login-success-redirect-URL" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Redirect URL', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input type="url" name="sgpb-login-success-redirect-URL" id="sgpb-login-success-redirect-URL" placeholder="https://www.example.com" value="<?php echo $popupTypeObj->getOptionValue('sgpb-login-success-redirect-URL'); ?>">
					</div>
					<div class="formItem subFormItem">
						<label for="login-success-redirect-new-tab" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Redirect to new tab', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<input type="checkbox" name="sgpb-login-success-redirect-new-tab" id="login-success-redirect-new-tab" placeholder="https://www.example.com" <?php echo $popupTypeObj->getOptionValue('sgpb-login-success-redirect-new-tab'); ?>>
					</div>
				</div>
				<div class="sg-hide sg-full-width sgpb-padding-x-20" id="login-open-popup">
					<div class="formItem subFormItem">
						<label for="sgpb-login-success-redirect-URL" class="subFormItem__title sgpb-margin-right-10">
							<?php _e('Select popup', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div >
							<?php echo AdminHelper::createSelectBox($loginSubPopups, $successPopup, array('name' => 'sgpb-login-success-popup', 'class'=>'js-sg-select2 sgpb-full-width-events')); ?>
						</div>
					</div>
					<!-- after successful login -->
				</div>
			</div>
			<div class="sgpb-width-40 sgpb-padding-x-10">
				<div class="sgpb-position-sticky sgpb-overflow-auto sgpb-shadow-black-10 sgpb-border-radius-5px sgpb-bg-white sgpb-padding-20">
					<h1 class="sgpb-margin-bottom-20 sgpb-margin-auto sgpb-align-item-center sgpb-btn sgpb-btn-gray-light sgpb-btn--rounded sgpb-display-flex sgpb-justify-content-center">
						<img class="sgpb-margin-right-10" src="<?php echo SG_POPUP_PUBLIC_URL.'icons/Black/eye.svg'; ?>" alt="Eye icon">
						<?php _e('Live Preview', SG_POPUP_TEXT_DOMAIN)?>
					</h1>
					<?php
					$popupTypeObj->setLoginFormData(@$_GET['post']);
					$formData = $popupTypeObj->createFormFieldsData();
					?>
					<div class="sgpb-login-form-<?php echo $popupId; ?> sgpb-login-form-admin-wrapper<?php echo $forceRtlClass; ?>">
						<?php echo Functions::renderForm(@$formData); ?>
					</div>
					<?php
					$styleData = array(
						'placeholderColor' => $popupTypeObj->getOptionValue('sgpb-login-text-placeholder-color')
					);
					echo $popupTypeObj->getFormCustomStyles(@$styleData)
					?>
				</div>
			</div>
		</div>
	</div>

</div>
