<?php

use sgpbrs\DefaultOptionsData;
use sgpb\AdminHelper;
use sgpb\MultipleChoiceButton;
use sgpb\Functions;
use sgpbregistration\AdminHelper as AdminHelperRegistration;

$popupId               = 0;
$defaultData           = AdminHelperRegistration::defaultData();
$registrationSubPopups = $popupTypeObj->getPopupsIdAndTitle();
$successPopup          = $popupTypeObj->getOptionValue( 'sgpb-subs-success-popup' );

if ( ! empty( $_GET['post'] ) ) {
	$popupId = (int) $_GET['post'];
}

$forceRtlClass = '';
$forceRtl      = $popupTypeObj->getOptionValue( 'sgpb-force-rtl' );
if ( $forceRtl ) {
	$forceRtlClass = ' sgpb-forms-preview-direction';
}
?>

<div class="sgpb-wrapper">
	<div class="sgpb-wrapper formItem">
		<div class="sgpb-display-flex sgpb-padding-10 sgpb-width-100">
			<div class="sgpb-width-60 sgpb-padding-x-20">
				<!-- form background options start -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e( 'Form background options', SG_POPUP_TEXT_DOMAIN ); ?>
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Form background color', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-registration-color-picker"
							       data-registration-rel="sgpb-registration-form-admin-wrapper"
							       data-style-type="background-color" type="text" name="sgpb-registration-form-bg-color"
							       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-form-bg-color' ) ); ?>"
							       autocomplete="off">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="content-padding" class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Form background opacity', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-slider-wrapper">
							<div class="slider-wrapper sgpb-display-inline-flex">
								<input type="range" class="sgpb-range-input js-subs-bg-opacity sgpb-margin-right-10 "
								       name="sgpb-registration-form-bg-opacity"
								       id="js-registration-bg-opacity" min="0.0" step="0.1" max="1"
								       value="<?php echo $popupTypeObj->getOptionValue( 'sgpb-registration-form-bg-opacity' ); ?>"
								       rel="<?php echo $popupTypeObj->getOptionValue( 'sgpb-registration-form-bg-opacity' ); ?>">
								<span class="js-registration-bg-opacity"><?php echo $popupTypeObj->getOptionValue('sgpb-registration-form-bg-opacity')?></span>
							</div>
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-registration-form-padding" class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Form padding', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input type="number" min="0"
							       data-default="<?php echo esc_attr( $popupTypeObj->getOptionDefaultValue( 'sgpb-registration-form-padding' ) ) ?>"
							       class="sgpb-margin-x-10 js-sgpb-form-padding"
							       id="sgpb-registration-form-padding" name="sgpb-registration-form-padding"
							       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-registration-form-padding' ) ) ?>"
							       autocomplete="off">
						</div>
						<span class="sgpb-restriction-unit">px</span>
					</div>
				</div>

				<!-- username field -->
				<div class="formItem">
					<label for="sgpb-username-label" class="formItem__title">
						<?php _e( 'Username', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-username-label"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Label', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-username-label"
						       class="js-registration-username-label js-registration-labels"
						       data-registration-rel="js-registration-username-label-edit" type="text"
						       name="sgpb-username-label"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-username-label' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-username-placeholder"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Placeholder', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-username-placeholder"
						       class="js-registration-field-placeholder js-registration-username-input"
						       data-registration-rel="js-registration-username-input" type="text"
						       name="sgpb-username-placeholder"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-username-placeholder' ) ); ?>">
					</div>
				</div>

				<!-- email field -->
				<div class="formItem">
					<label for="sgpb-username-label" class="formItem__title">
						<?php _e( 'Email Address', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-username-label"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Label', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-email-label"
						       class="js-registration-email-label js-registration-labels"
						       data-registration-rel="js-registration-email-label-edit" type="text"
						       name="sgpb-email-label"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-email-label' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-email-placeholder"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Placeholder', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-email-placeholder"
						       class="js-registration-field-placeholder js-registration-email-input"
						       data-registration-rel="js-registration-email-input" type="text"
						       name="sgpb-email-placeholder"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-email-placeholder' ) ); ?>">
					</div>
				</div>

				<!-- password field -->
				<div class="formItem">
					<label for="sgpb-password-label" class="formItem__title">
						<?php _e( 'Password', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-password-label"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Label', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-password-label"
						       class="js-registration-password-label js-registration-labels"
						       type="text" name="sgpb-password-label"
						       data-registration-rel="js-registration-password-label-edit"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-password-label' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-password-placeholder"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Placeholder', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-password-placeholder"
						       class="js-registration-field-placeholder js-registration-password-input"
						       data-registration-rel="js-registration-password-input" type="text"
						       name="sgpb-password-placeholder"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-password-placeholder' ) ); ?>">
					</div>
				</div>

				<!-- confirm confirm-password field -->
				<div class="formItem">
					<label for="sgpb-confirm-confirm-password-label"
					       class="formItem__title">
						<?php _e( 'Confirm password', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 formItem">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-confirm-confirm-password-label"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Label', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-confirm-password-label"
						       class="js-registration-confirm-password-label js-registration-labels"
						       type="text" name="sgpb-confirm-password-label"
						       data-registration-rel="js-registration-confirm-password-label-edit"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-confirm-password-label' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-confirm-password-placeholder"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Placeholder', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-confirm-password-placeholder"
						       class="js-registration-field-placeholder js-registration-confirm-password-input"
						       data-registration-rel="js-registration-confirm-password-input" type="text"
						       name="sgpb-confirm-password-placeholder"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-confirm-password-placeholder' ) ); ?>">
					</div>
				</div>

				<!-- input styles -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e( 'Inputs\' style', SG_POPUP_TEXT_DOMAIN ); ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 sgpb-padding-y-10">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-registration-text-width"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Width', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<input type="text" class="js-registration-dimension"
						       data-field-type="input" data-registration-rel="js-registration-text-inputs"
						       data-style-type="width" name="sgpb-registration-text-width"
						       id="sgpb-registration-text-width"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-text-width' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-registration-text-height"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Height', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<input class="js-registration-dimension"
						       data-registration-rel="js-registration-text-inputs" data-style-type="height" type="text"
						       name="sgpb-registration-text-height" id="sgpb-registration-text-height"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-text-height' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-registration-text-border-width"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Border width', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<input class="js-registration-dimension"
						       data-registration-rel="js-registration-text-inputs" data-style-type="border-width"
						       type="text" name="sgpb-registration-text-border-width"
						       id="sgpb-registration-text-border-width"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-text-border-width' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Background color', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-registration-color-picker"
							       data-registration-rel="js-registration-text-inputs"
							       data-style-type="background-color" type="text" name="sgpb-registration-text-bg-color"
							       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-text-bg-color' ) ); ?>">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Border color', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-registration-color-picker"
							       data-registration-rel="js-registration-text-inputs" data-style-type="border-color"
							       type="text" name="sgpb-registration-text-border-color"
							       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-text-border-color' ) ); ?>">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Text color', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-registration-color-picker"
							       data-registration-rel="js-registration-text-inputs" data-style-type="color"
							       type="text" name="sgpb-registration-text-color"
							       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-text-color' ) ); ?>">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Placeholder color', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-registration-color-picker sgpb-full-width-events"
							       data-registration-rel="js-registration-text-inputs" data-style-type="placeholder"
							       type="text" name="sgpb-registration-text-placeholder-color"
							       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-text-placeholder-color' ) ); ?>">
						</div>
					</div>
				</div>

				<!-- error messages -->
				<div class="formItem">
					<label for="sgpb-registration-required-error"
					       class="formItem__title sgpb-margin-right-10">
						<?php _e( 'Required field message', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
					<input id="sgpb-registration-required-error"
					       type="text" name="sgpb-registration-required-error"
					       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-required-error' ) ); ?>">
				</div>
				<div class="formItem">
					<label for="sgpb-registration-error-message" class="formItem__title sgpb-margin-right-10">
						<?php _e( 'Error message', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
					<input id="sgpb-registration-error-message"
					       type="text" name="sgpb-registration-error-message"
					       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-error-message' ) ); ?>">
				</div>
				<!-- submit styles -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e( 'Registration button styles', SG_POPUP_TEXT_DOMAIN ); ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 sgpb-padding-y-10">
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-registration-btn-width"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Width', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<input class="js-registration-dimension"
						       data-registration-rel="js-registration-submit-btn" data-style-type="width" type="text"
						       name="sgpb-registration-btn-width" id="sgpb-registration-btn-width"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-btn-width' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-registration-btn-height"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Height', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<input class="js-registration-dimension"
						       data-registration-rel="js-registration-submit-btn" data-style-type="height" type="text"
						       name="sgpb-registration-btn-height" id="sgpb-registration-btn-height"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-btn-height' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="sgpb-registration-btn-title"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Title', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<input type="text" name="sgpb-registration-btn-title" id="sgpb-registration-btn-title"
						       class="js-registration-btn-title"
						       data-registration-rel="js-registration-submit-btn"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-btn-title' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label for="btn-progress-title"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Title (in progress)', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<input type="text" name="sgpb-registration-btn-progress-title"
						       id="sgpb-registration-btn-progress-title"
						       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-btn-progress-title' ) ); ?>">
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Background color', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-registration-color-picker"
							       data-registration-rel="js-registration-submit-btn" data-style-type="background-color"
							       type="text" name="sgpb-registration-btn-bg-color"
							       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-btn-bg-color' ) ); ?>">
						</div>
					</div>
					<div class="subFormItem formItem sgpb-padding-x-20">
						<label class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Text color', SG_POPUP_TEXT_DOMAIN ); ?>:
						</label>
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker js-registration-color-picker"
							       data-registration-rel="js-registration-submit-btn" data-style-type="color"
							       type="text" name="sgpb-registration-btn-text-color"
							       value="<?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-registration-btn-text-color' ) ); ?>">
						</div>
					</div>
				</div>

				<!-- after successful registration -->
				<div class="formItem">
					<label class="formItem__title">
						<?php _e( 'After successful registration', SG_POPUP_TEXT_DOMAIN ); ?>:
					</label>
				</div>
				<div class="sgpb-padding-20 sgpb-bg-black__opacity-02">
					<?php
					$multipleChoiceButton = new MultipleChoiceButton( $defaultData['registrationSuccessBehavior'], $popupTypeObj->getOptionValue( 'sgpb-registration-success-behavior' ) );
					echo $multipleChoiceButton;
					?>
				</div>

				<div class="sg-hide sg-full-width sgpb-padding-x-20" id="registration-redirect-to-URL">
					<div class="formItem subFormItem">
						<label for="sgpb-registration-success-redirect-URL"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Redirect URL', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="url" name="sgpb-registration-success-redirect-URL"
						       id="sgpb-registration-success-redirect-URL"
						       placeholder="https://www.example.com"
						       value="<?php echo $popupTypeObj->getOptionValue( 'sgpb-registration-success-redirect-URL' ); ?>">
					</div>
					<div class="formItem subFormItem">
						<label for="registration-success-redirect-new-tab"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Redirect to new tab', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="checkbox" name="sgpb-registration-success-redirect-new-tab"
						       id="registration-success-redirect-new-tab"
						       placeholder="https://www.example.com" <?php echo $popupTypeObj->getOptionValue( 'sgpb-registration-success-redirect-new-tab' ); ?>>
					</div>
				</div>
				<div class="sg-hide sg-full-width sgpb-padding-x-20" id="registration-open-popup">
					<div class="formItem subFormItem">
						<label for="sgpb-registration-success-redirect-URL"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Select popup', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<div >
							<?php echo AdminHelper::createSelectBox( $registrationSubPopups, $successPopup, array( 'name'  => 'sgpb-registration-success-popup',
							                                                                                       'class' => 'js-sg-select2 sgpb-full-width-events'
							) ); ?>
						</div>
					</div>
					<!-- after successful registration -->
				</div>
			</div>
			<div class="sgpb-width-40 sgpb-padding-x-10">
				<div class="sgpb-position-sticky sgpb-overflow-auto sgpb-shadow-black-10 sgpb-border-radius-5px sgpb-bg-white sgpb-padding-20">
					<h1 class="sgpb-margin-bottom-20 sgpb-margin-auto sgpb-align-item-center sgpb-btn sgpb-btn-gray-light sgpb-btn--rounded sgpb-display-flex sgpb-justify-content-center">
						<img class="sgpb-margin-right-10" src="<?php echo SG_POPUP_PUBLIC_URL.'icons/Black/eye.svg'; ?>" alt="Eye icon">
						<?php _e('Live Preview', SG_POPUP_TEXT_DOMAIN)?>
					</h1>
					<?php
					$popupTypeObj->setRegistrationFormData( @$_GET['post'] );
					$formData = $popupTypeObj->createFormFieldsData();
					?>
					<div class="sgpb-registration-form-<?php echo $popupId; ?> sgpb-registration-form-admin-wrapper<?php echo $forceRtlClass; ?>">
						<?php echo AdminHelperRegistration::renderForm( @$formData ); ?>
					</div>
					<?php
					$styleData = array(
						'placeholderColor' => $popupTypeObj->getOptionValue( 'sgpb-registration-text-placeholder-color' )
					);
					echo $popupTypeObj->getFormCustomStyles( @$styleData )
					?>
				</div>
			</div>
		</div>

	</div>
</div>
