<?php

use sgpbaw\SGPBAWeberApi;
use sgpb\AdminHelper;
use sgpb\MultipleChoiceButton;
use sgpbaw\DefaultOptionsData;

$sucessBehavior   = DefaultOptionsData::getSuccessBehavior();
$accessToken      = get_option( 'sgpbAccessToken' );
$aweberSttingsUrl = admin_url( 'edit.php?post_type=' . SG_POPUP_POST_TYPE . '&page=' . SGPB_POPUP_TYPE_AWEBER );
$aweberObj        = new SGPBAWeberApi();
if ( $accessToken ) {
	$listIdTitle     = $aweberObj->createIdAndTitles();
	$selectedList    = $popupTypeObj->getOptionValue( 'sgpb-aweber-list' );
	$signupsIdTitles = $aweberObj->getListIdAndTitles( $selectedList );
	$signupFormId    = $popupTypeObj->getOptionValue( 'sgpb-aweber-signup-form' );
}
$aweberSuccessPopups = $popupTypeObj->getPopupsIdAndTitle();
$forceRtlClass       = '';
$forceRtl            = $popupTypeObj->getOptionValue( 'sgpb-force-rtl' );
if ( $forceRtl ) {
	$forceRtlClass = ' sgpb-forms-preview-direction';
}
?>

<div class="sgpb-wrapper sgpb-aweber-form-loading-wrapper sgpb-aweber-spinner-js">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-2">
			<img src="<?php echo SGPB_AWEBER_IMG_URL . 'aweber-logo.png' ?>">
		</div>
		<div class="col-md-1">
			<div class="item">
				<div class="loader09"></div>
			</div>
		</div>
	</div>
</div>
<div class="sgpb-wrapper sgpb-settings sgpb-aweber-options-wrapper-js sg-hide-element">
	<div class="sgpb-wrapper formItem">
		<div class="sgpb-display-flex sgpb-padding-10 sgpb-width-100">
			<div class="sgpb-width-50 sgpb-padding-x-20">
				<div class="formItem">
					<label class="formItem__title sgpb-margin-right-10"><?php _e( 'Status', SG_POPUP_TEXT_DOMAIN ); ?></label>
					<?php if ( ! get_option( 'sgpbAccessToken' ) ): ?>
						<span class="sgpb-aweber-connect-status sgpb-aweber-not-connected btn-sm"><?php _e( 'Not Connected', SG_POPUP_TEXT_DOMAIN ); ?></span>
					<?php else : ?>
						<span class="sgpb-aweber-connect-status sgpb-aweber-connected btn-sm"><?php _e( 'Connected', SG_POPUP_TEXT_DOMAIN ); ?></span>
					<?php endif; ?>
				</div>
				<?php if ( ! $accessToken ): ?>
					<div class="formItem">
						<label class="formItem__title sgpb-margin-right-10"><?php _e( 'Your lists', SG_POPUP_TEXT_DOMAIN ); ?></label>
						<a href="<?php echo $aweberSttingsUrl; ?>"><?php _e( 'Authenticate AWeber account', SG_POPUP_TEXT_DOMAIN ); ?></a>
					</div>
				<?php else: ?>
					<div class="formItem">
						<label for="sgpb-aweber-lists"
						       class="formItem__title"><?php _e( 'Your lists', SG_POPUP_TEXT_DOMAIN ); ?></label>
						<div class="sgpb-margin-x-10">
							<?php echo AdminHelper::createSelectBox( $listIdTitle, $selectedList, array( 'name'         => 'sgpb-aweber-list',
							                                                                             'class'        => 'js-sg-select2 js-sgpb-aweber-lists',
							                                                                             'id'           => 'sgpb-aweber-lists',
							                                                                             'data-form-id' => $signupFormId
							) ); ?>
						</div>
						<div class="sgpb-aweber-spinner-column">
							<img src="<?php echo SGPB_AWEBER_IMG_URL . 'wpAjax.gif'; ?>" alt="gif"
							     class="sgpb-spinner-aweber-lists sg-hide-element js-sg-spinner js-sg-import-gif">
						</div>
					</div>
					<div class="formItem">
						<label for="sgpb-aweber-forms"
						       class="formItem__title"><?php _e( 'Sign up forms', SG_POPUP_TEXT_DOMAIN ); ?></label>
						<div class="sgpb-margin-x-10">
							<?php echo AdminHelper::createSelectBox( $signupsIdTitles, $signupFormId, array( 'name'  => 'sgpb-aweber-signup-form',
							                                                                                 'class' => 'js-sg-select2 js-sgpb-aweber-signup-forms',
							                                                                                 'id'    => 'sgpb-aweber-forms'
							) ); ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="formItem">
					<label for="sgpb-invalid-email" class="formItem__title sgpb-margin-right-10">
						<?php _e( 'Unexpected error messages', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
					<input class="js-checkbox-accordion" type="checkbox" id="sgpb-invalid-email"
					       name="sgpb-aweber-invalid-email" <?php echo $popupTypeObj->getOptionValue( 'sgpb-aweber-invalid-email' ); ?>>
				</div>
				<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-x-20">
					<div class="formItem subFormItem">
						<label for="sgpb-aweber-invalid-email-message" class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Text', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input id="sgpb-aweber-invalid-email-message"
						       name="sgpb-aweber-invalid-email-message"
						       type="text"
						       value="<?php echo $popupTypeObj->getOptionValue( 'sgpb-aweber-invalid-email-message' ); ?>">
					</div>
				</div>
				<div class="formItem">
					<label for="sgpb-aweber-custom-subscribed-message" class="formItem__title sgpb-margin-right-10">
						<?php _e( 'Already subscribed message', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
					<input id="sgpb-aweber-custom-subscribed-message"
					       type="text"
					       name="sgpb-aweber-custom-subscribed-message"
					       value="<?php echo $popupTypeObj->getOptionValue( 'sgpb-aweber-custom-subscribed-message' ); ?>">
				</div>
				<div class="formItem">
					<label class="formItem__title sgpb-margin-right-10">
						<?php _e( 'Validation messages', SG_POPUP_TEXT_DOMAIN ) ?>
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
					<div class="formItem subFormItem">
						<label for="sgpb-aweber-required-message"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Required message', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="text" id="sgpb-aweber-required-message"
						       name="sgpb-aweber-required-message"
						       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-aweber-required-message' ) ); ?>">
					</div>
					<div class="formItem subFormItem">
						<label for="sgpb-aweber-validate-email-message"
						       class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Invalid email message', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="text"
						       id="sgpb-aweber-validate-email-message" name="sgpb-aweber-validate-email-message"
						       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-aweber-validate-email-message' ) ); ?>">
					</div>
				</div>

				<div class="formItem">
					<label class="formItem__title">
						<?php _e( 'After successful subscription', SG_POPUP_TEXT_DOMAIN ) ?>:
					</label>
				</div>
				<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
					<?php
					$multipleChoiceButton = new MultipleChoiceButton( $sucessBehavior, $popupTypeObj->getOptionValue( 'sgpb-aweber-success-behavior' ) );
					echo $multipleChoiceButton;
					?>
				</div>

				<div class="sg-hide sg-full-width sgpb-padding-x-10" id="aweber-show-success-message">
					<div class="formItem subFormItem">
						<label for="sgpb-aweber-success-message" class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Text', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input name="sgpb-aweber-success-message" type="text" id="sgpb-aweber-success-message"
						       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-aweber-success-message' ) ); ?>">
					</div>
				</div>
				<div class="sg-hide sg-full-width sgpb-padding-x-10" id="aweber-redirect-to-URL">
					<div class="formItem subFormItem">
						<label for="sgpb-aweber-success-redirect-URL" class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'URL', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="url" placeholder="https://www.example.com" name="sgpb-aweber-success-redirect-URL"
						       id="sgpb-aweber-success-redirect-URL" placeholder="https://www.example.com"
						       value="<?php echo $popupTypeObj->getOptionValue( 'sgpb-aweber-success-redirect-URL' ); ?>">
					</div>
					<div class="formItem subFormItem">
						<label for="aweber-success-redirect-new-tab" class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Redirect to new tab', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<input type="checkbox" name="sgpb-aweber-success-redirect-new-tab"
						       id="aweber-success-redirect-new-tab"
						       placeholder="https://www.example.com" <?php echo $popupTypeObj->getOptionValue( 'sgpb-aweber-success-redirect-new-tab' ); ?>>
					</div>
				</div>
				<div class="sg-hide sg-full-width sgpb-padding-x-10" id="aweber-open-popup">
					<div class="formItem subFormItem">
						<label for="sgpb-aweber-success-popup" class="subFormItem__title sgpb-margin-right-10">
							<?php _e( 'Select popup', SG_POPUP_TEXT_DOMAIN ) ?>:
						</label>
						<div class="col-md-5">
							<?php echo AdminHelper::createSelectBox( $aweberSuccessPopups, $popupTypeObj->getOptionValue( 'sgpb-aweber-success-popup' ), array( 'name'  => 'sgpb-aweber-success-popup',
							                                                                                                                                    'class' => 'js-sg-select2 sgpb-full-width-events',
							                                                                                                                                    'id'    => 'sgpb-aweber-success-popup'
							) ); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="sgpb-width-50">
				<div class="sgpb-position-sticky sgpb-shadow-black-10 sgpb-border-radius-5px sgpb-bg-white sgpb-padding-20">
					<h1 class="sgpb-margin-bottom-20 sgpb-margin-auto sgpb-align-item-center sgpb-btn sgpb-btn-gray-light sgpb-btn--rounded sgpb-display-flex sgpb-justify-content-center">
						<img class="sgpb-margin-right-10" src="<?php echo SG_POPUP_PUBLIC_URL.'icons/Black/eye.svg'; ?>" alt="Eye icon">
						<?php _e('Live Preview', SG_POPUP_TEXT_DOMAIN)?>
					</h1>
					<?php if ( $accessToken ): ?>
						<div id="sgpb-aweber-webform-wrapper"
						     class="row form-group sgpb-pointer-events-none<?php echo $forceRtlClass; ?>">
							<div class="col-md-12">
								<?php echo $aweberObj->getWebformHtml( $selectedList, $signupFormId ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</div>

</div>
