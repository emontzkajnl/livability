<?php

use sgpbm\MailchimpApi;
use sgpb\AdminHelper;
use sgpbm\DefaultOptionsData;
use sgpb\MultipleChoiceButton;

$defaultData = DefaultOptionsData::getDefaultData();
$status      = MailchimpApi::isConnected();
if($status) {
	$sgpbMailchimp = MailchimpApi::getInstance();
	$count         = $sgpbMailchimp->getTotalCount();
}
$selectedFormId         = $popupTypeObj->getOptionValue('sgpb-mailchimp-lists');
$mailchimpFormSubPopups = $popupTypeObj->getPopupsIdAndTitle();
$id                     = (int)@$_GET['post'];
?>
<div class="sgpb-display-flex sgpb-padding-10 sgpb-width-100">
	<div class="formItem sgpb-align-item-stretch sgpb-flex-direction-column sgpb-width-50 sgpb-wrapper">
		<div class="formItem">
			<label class="formItem__title sgpb-margin-right-10"><?php _e('Status', SG_POPUP_TEXT_DOMAIN) ?></label>
			<?php if(!$status): ?>
				<span class="sg-mailchimp-connect-status sg-mailchimp-not-connected"><?php _e('Not connected', SG_POPUP_TEXT_DOMAIN) ?></span>
			<?php else : ?>
				<span class="sg-mailchimp-connect-status sg-mailchimp-connected"><?php _e('Connected', SG_POPUP_TEXT_DOMAIN) ?></span>
			<?php endif; ?>
		</div>
		<div class="formItem">
			<label class="formItem__title sgpb-margin-right-10"><?php _e('Your lists', SG_POPUP_TEXT_DOMAIN) ?></label>
			<?php
			if(!$status): ?>
				<a class="sgpb-mailchimp-to-api-link"
				   href="<?php echo admin_url().'./edit.php?post_type='.SG_POPUP_POST_TYPE.'&page='.SGPB_POPUP_TYPE_MAILCHIMP ?>"><?php _e('Setup your Mailchimp Api key') ?></a>
			<?php
			elseif(!$count):
				_e('You don\'t have any lists yet. Please create a list first', SG_POPUP_TEXT_DOMAIN);
			else:
				echo AdminHelper::createSelectBox($sgpbMailchimp->getListsIdAndTitle(), $selectedFormId, array(
					'name'  => 'sgpb-mailchimp-lists',
					'class' => 'sgpb-mailchimp-lists js-sg-select2'
				));
			endif;
			?>
		</div>
		<div class="formItem">
			<label class="formItem__title"><?php _e('General settings', SG_POPUP_TEXT_DOMAIN) ?></label>
		</div>
		<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
			<div class="formItem subFormItem">
				<label for="sgpb-enable-double-optin"
				       class="subFormItem__title sgpb-margin-right-10"><?php _e('Enable double opt-in', SG_POPUP_TEXT_DOMAIN) ?></label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox" id="sgpb-enable-double-optin"
					       name="sgpb-enable-double-optin"
						<?php echo $popupTypeObj->getOptionValue('sgpb-enable-double-optin'); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-enable-double-optin">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
							<span class="infoSelectRepeat samefontStyle sgpb-info-text">
								<?php _e('With single opt-in, new subscribers fill out a signup form and are immediately added to a mailing list, even if their address is invalid or contains a typo. Single opt-in can clog your list with bad addresses, and possibly generate spam complaints from subscribers who don’t remember signing up', SG_POPUP_TEXT_DOMAIN) ?>.
							</span>
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-show-required-fields" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Show only required fields', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox" id="sgpb-show-required-fields"
					       name="sgpb-show-required-fields"
						<?php echo $popupTypeObj->getOptionValue('sgpb-show-required-fields'); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-show-required-fields">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-enable-asterisk-label" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Enable asterisk label', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox js-checkbox-accordion"
					       id="sgpb-enable-asterisk-label" name="sgpb-enable-asterisk-label"
						<?php echo $popupTypeObj->getOptionValue('sgpb-enable-asterisk-label'); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-enable-asterisk-label">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-20">
				<div class="formItem subFormItem">
					<label for="sgpb-enable-asterisk-title" class="sgpb-margin-right-10">
						<?php _e('Asterisk title', SG_POPUP_TEXT_DOMAIN) ?>:
					</label>
					<input type="checkbox" name="sgpb-enable-asterisk-title" class="js-checkbox-accordion"
					       id="sgpb-enable-asterisk-title" <?php echo $popupTypeObj->getOptionValue('sgpb-enable-asterisk-title'); ?>>
				</div>
				<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-20">
					<div class="formItem subFormItem">
						<label for="sgpb-mailchimp-asterisk-label"
						       class="sgpb-margin-right-10">
							<?php _e('Asterisk label', SG_POPUP_TEXT_DOMAIN) ?>:
						</label>
						<input type="text" name="sgpb-mailchimp-asterisk-label"
						       id="sgpb-mailchimp-asterisk-label"
						       style="background-color: #eae9e9!important;"
						       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-asterisk-label')); ?>">
					</div>
				</div>
			</div>
			<!-- GDPR checkbox start -->
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-gdpr-status" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Enable GDPR', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>

				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox"
					       class="sgpb-onOffSwitch-checkbox js-checkbox-accordion js-mailchimp-checkbox-status js-mailchimp-gdpr-status"
					       id="sgpb-mailchimp-gdpr-status" data-mailchimp-field-wrapper="js-gdpr-wrapper"
					       name="sgpb-mailchimp-gdpr-status" <?php echo $popupTypeObj->getOptionValue('sgpb-mailchimp-gdpr-status'); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-mailchimp-gdpr-status">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>

				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
							<span class="infoSelectRepeat samefontStyle sgpb-info-text">
								<?php _e('Added GDPR field is already required', SG_POPUP_TEXT_DOMAIN) ?>.
							</span>
				</div>
			</div>
			<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-20">
				<div class="formItem subFormItem">
					<label for="sgpb-mailchimp-gdpr-label" class="sgpb-margin-right-10">
						<?php _e('Label', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<input type="text" name="sgpb-mailchimp-gdpr-label" id="sgpb-mailchimp-gdpr-label"
					       class="js-subs-field-placeholder"
					       data-subs-rel="js-subs-gdpr-label"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-gdpr-label')); ?>">
				</div>
				<div class="formItem subFormItem">
					<label for="sgpb-mailchimp-gdpr-text" class="sgpb-margin-right-10">
						<?php _e('Confirmation text', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<textarea name="sgpb-mailchimp-gdpr-text"
					          id="sgpb-mailchimp-gdpr-text"><?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-gdpr-text')); ?></textarea>
				</div>
			</div>
			<!-- GDPR checkbox end -->
			<div class="formItem subFormItem">
				<label for="sgpb-form-alignment" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Form alignment', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<?php echo AdminHelper::createSelectBox($defaultData['formAlign'], esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-form-align')), array(
					'name'  => 'sgpb-mailchimp-form-align',
					'class' => 'sgpb-mailchimp-form-align js-sg-select2'
				)); ?>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-label-alignment" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Label alignment', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<?php echo AdminHelper::createSelectBox($defaultData['labelAlign'], esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-label-alignment')), array(
					'name'  => 'sgpb-mailchimp-label-alignment',
					'class' => 'sgpb-mailchimp-label-alignment js-sg-select2'
				)); ?>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-required-message" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Required message', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-required-message"
				       id="sgpb-mailchimp-required-message"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-required-message')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-email-message" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Email message', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-email-message"
				       id="sgpb-mailchimp-email-message"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-email-message')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-email-label" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Email label', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-email-label"
				       id="sgpb-mailchimp-email-label"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-email-label')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-error-message" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Error message', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-error-message"
				       id="sgpb-mailchimp-error-message"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-error-message')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-error-message" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Show form before content', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
					<input type="checkbox" class="sgpb-onOffSwitch-checkbox" id="sgpb-mailchimp-show-form-to-top"
					       name="sgpb-mailchimp-show-form-to-top"
						<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-show-form-to-top')); ?>>
					<label class="sgpb-onOffSwitch__label" for="sgpb-mailchimp-show-form-to-top">
						<span class="sgpb-onOffSwitch-inner"></span>
						<span class="sgpb-onOffSwitch-switch"></span>
					</label>
				</div>
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
							<span class="infoSelectRepeat samefontStyle sgpb-info-text">
								<?php _e('If this option is checked, the form will be displayed before the text', SG_POPUP_TEXT_DOMAIN) ?>.
							</span>
				</div>
			</div>

		</div>

		<div class="formItem">
			<label class="formItem__title"><?php _e('General style', SG_POPUP_TEXT_DOMAIN) ?>:</label>
		</div>
		<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-label-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Label color', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-color-picker-wrapper sgpb-label-color">
					<input class="sgpb-mailchimp-label-color sgpb-color-picker" type="text"
					       name="sgpb-mailchimp-label-color"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-label-color')); ?>">
				</div>
			</div>

		</div>
		<div class="formItem">
			<label class="formItem__title"><?php _e('Inputs style', SG_POPUP_TEXT_DOMAIN) ?>:</label>
		</div>
		<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-input-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Width', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-input-width" class="js-mailchimp-dimension"
				       data-field-type="input" data-style-type="width" id="sgpb-mailchimp-input-width"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-input-width')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-input-height"
				       class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Height', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-input-height" class="js-mailchimp-dimension"
				       data-field-type="input" data-style-type="height" id="sgpb-mailchimp-input-height"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-input-height')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-border-radius" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-border-radius"
				       class="js-mailchimp-dimension"
				       data-field-type="input" data-style-type="border-radius"
				       id="sgpb-mailchimp-border-radius"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-border-radius')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-border-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-border-width"
				       class="js-mailchimp-dimension"
				       data-field-type="input" data-style-type="border-width"
				       id="sgpb-mailchimp-border-width"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-border-width')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-border-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-color-picker-wrapper sgpb-overlay-color">
					<input class="js-sgpb-colors sgpb-color-picker " type="text" name="sgpb-mailchimp-border-color"
					       data-field-type="input" data-style-type="border-color"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-border-color')); ?>">
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-background-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-color-picker-wrapper sgpb-overlay-color">
					<input class="js-sgpb-colors sgpb-color-picker " type="text" name="sgpb-mailchimp-background-color"
					       data-field-type="input" data-style-type="background-color"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-background-color')); ?>">
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-input-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Text color', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-color-picker-wrapper sgpb-overlay-color">
					<input class="js-sgpb-colors sgpb-color-picker " type="text" name="sgpb-mailchimp-input-color"
					       data-field-type="input" data-style-type="color"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-input-color')); ?>">
				</div>
			</div>
		</div>

		<div class="formItem">
			<label class="formItem__title"><?php _e('Submit button style', SG_POPUP_TEXT_DOMAIN) ?>:</label>
		</div>
		<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-title" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Button title', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-submit-title"
				       class="js-mailchimp-submit-title"
				       id="sgpb-mailchimp-submit-title"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-title')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Width', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-submit-width"
				       class="js-mailchimp-dimension"
				       data-field-type="submit" data-style-type="width" id="sgpb-mailchimp-submit-width"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-width')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-height" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Height', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-submit-height"
				       class="js-mailchimp-dimension"
				       data-field-type="submit" data-style-type="height" id="sgpb-mailchimp-submit-height"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-height')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-border-width" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-submit-border-width"
				       class="js-mailchimp-dimension"
				       data-field-type="submit" data-style-type="border-width"
				       id="sgpb-mailchimp-submit-height"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-border-width')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-border-radius"
				       class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="text" name="sgpb-mailchimp-submit-border-radius"
				       class="js-mailchimp-dimension"
				       data-field-type="submit" data-style-type="border-radius"
				       id="sgpb-mailchimp-submit-border-radius"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-border-radius')); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-background-color"
				       class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-color-picker-wrapper sgpb-overlay-color">
					<input class="js-sgpb-colors sgpb-color-picker" type="text"
					       name="sgpb-mailchimp-submit-background-color"
					       data-field-type="submit" data-style-type="background-color"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-background-color')); ?>">
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-border-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-color-picker-wrapper sgpb-overlay-color">
					<input class="js-sgpb-colors sgpb-color-picker" type="text"
					       name="sgpb-mailchimp-submit-border-color"
					       data-field-type="submit" data-style-type="border-color"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-border-color')); ?>">
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-submit-color" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Label color', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="sgpb-color-picker-wrapper sgpb-overlay-color">
					<input class="js-sgpb-colors sgpb-color-picker" type="text" name="sgpb-mailchimp-submit-color"
					       data-field-type="submit" data-style-type="color"
					       value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-mailchimp-submit-color')); ?>">
				</div>
			</div>
		</div>

		<div class="formItem">
			<label class="formItem__title"><?php _e('After successful subscription', SG_POPUP_TEXT_DOMAIN) ?>
				:</label>
		</div>
		<div class="sgpb-bg-black__opacity-02 sgpb-padding-20">
			<?php
			$multipleChoiceButton = new MultipleChoiceButton($defaultData['mailchimpFormSuccessBehavior'], $popupTypeObj->getOptionValue('sgpb-mailchimp-success-behavior'));
			echo $multipleChoiceButton;
			?>
		</div>

		<div class="sg-hide sg-full-width sgpb-padding-x-10" id="mailchimp-show-success-message">
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-success-message" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Success message', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input name="sgpb-mailchimp-success-message"
				       id="sgpb-mailchimp-success-message"
				       type="text"
				       value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-mailchimp-success-message')); ?>">
			</div>
		</div>
		<div class="sg-hide sg-full-width sgpb-padding-x-10" id="mailchimp-redirect-to-URL">
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-success-redirect-URL" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Redirect URL', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="url" name="sgpb-mailchimp-success-redirect-URL"
				       id="sgpb-mailchimp-success-redirect-URL"
				       placeholder="https://www.example.com"
				       value="<?php echo $popupTypeObj->getOptionValue('sgpb-mailchimp-success-redirect-URL'); ?>">
			</div>
			<div class="formItem subFormItem">
				<label for="contact-success-redirect-new-tab" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Redirect to new tab', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<input type="checkbox" name="sgpb-mailchimp-success-redirect-new-tab"
				       id="sgpb-mailchimp-success-redirect-new-tab"
				       placeholder="https://www.example.com" <?php echo $popupTypeObj->getOptionValue('sgpb-mailchimp-success-redirect-new-tab'); ?>>
			</div>
		</div>
		<div class="sg-hide sg-full-width sgpb-padding-x-10" id="mailchimp-open-popup">
			<div class="formItem subFormItem">
				<label for="sgpb-mailchimp-success-popup" class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Select popup', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<?php echo AdminHelper::createSelectBox($mailchimpFormSubPopups, $popupTypeObj->getOptionValue('sgpb-mailchimp-success-popup'), array(
					'name'  => 'sgpb-mailchimp-success-popup',
					'class' => 'js-sg-select2 sgpb-full-width-events'
				)); ?>
			</div>
		</div>

		<div class="formItem">
			<label for="sgpb-mailchimp-close-popup-already-subscribed" class="formItem__title">
				<?php _e('Hide for already subscribed users', SG_POPUP_TEXT_DOMAIN) ?>:
			</label>
			<div class="sgpb-onOffSwitch sgpb-onOffSwitch_smallLeftMargin">
				<input type="checkbox" class="sgpb-onOffSwitch-checkbox"
				       id="sgpb-mailchimp-close-popup-already-subscribed"
				       name="sgpb-mailchimp-close-popup-already-subscribed"
					<?php echo $popupTypeObj->getOptionValue('sgpb-mailchimp-close-popup-already-subscribed'); ?>>
				<label class="sgpb-onOffSwitch__label" for="sgpb-mailchimp-close-popup-already-subscribed">
					<span class="sgpb-onOffSwitch-inner"></span>
					<span class="sgpb-onOffSwitch-switch"></span>
				</label>
			</div>
		</div>
	</div>
	<div class="sgpb-width-50">
		<div class="sgpb-position-sticky sgpb-shadow-black-10 sgpb-border-radius-5px sgpb-bg-white sgpb-padding-20">
			<?php echo $popupTypeObj->renderCss(); ?>
			<?php if($status): ?>
				<h1 class="sgpb-margin-bottom-20 sgpb-margin-auto sgpb-align-item-center sgpb-btn sgpb-btn-gray-light sgpb-btn--rounded sgpb-display-flex sgpb-justify-content-center">
					<img class="sgpb-margin-right-10"
					     src="<?php echo SG_POPUP_PUBLIC_URL.'icons/Black/eye.svg'; ?>" alt="Eye icon">
					<?php _e('Live Preview', SG_POPUP_TEXT_DOMAIN) ?>
				</h1>
				<div class="sgpb-live-preview-wrapper">
					<div class="sgpb-mailchimp-admin-form-wrapper sgpbmMailchimpForm sgpb-mailchimp-<?php echo $id; ?>"></div>
					<div class="sgpb-loader sg-hide-element">
						<div class="sgpb-container">
							<div class="sgpb-banner">
								<?php _e('LOADING', SG_POPUP_TEXT_DOMAIN); ?>
								<div class="sgpb-banner-left"></div>
								<div class="sgpb-banner-right"></div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div>
</div>
