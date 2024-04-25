<?php
require_once( SG_POPUP_CLASSES_POPUPS_PATH . 'SGPopup.php' );

use sgpbsubscriptionplus\SubscriptionPlusAdminHelper as SubscriptionPlusAdminHelper;
use sgpbsubscriptionplus\EmailTemplate as EmailTemplate;
use sgpb\SGPopup;
use sgpb\AdminHelper;

$allTemplates     = EmailTemplate::getTemplatesIdAndTitle();
$selectedEvent    = SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-response-event' );
$selectedTemplate = SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-email-template' );
$allLists         = SubscriptionPlusAdminHelper::getListsIdAndTitle( array( 'type' => 'subscription' ) );
$selectedLists    = SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-lists' );
?>

<div class="sgpb">
	<div class="sgpb-wrapper">
		<div class="formItem">
			<label for="sgpb-autoresponder-subject" class="formItem__title">
				<?php _e( 'Email subject', SG_POPUP_TEXT_DOMAIN ); ?>:
			</label>
			<input type="text" id="sgpb-autoresponder-subject" name="sgpb-autoresponder-subject"
			       value="<?php echo SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-subject' ); ?>">
		</div>
		<div class="formItem">
			<label for="sgpb-autoresponder-from-name" class="formItem__title">
				<?php _e( 'From name', SG_POPUP_TEXT_DOMAIN ); ?>:
			</label>
			<input type="text" id="sgpb-autoresponder-from-name" name="sgpb-autoresponder-from-name"
			       value="<?php echo SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-from-name' ); ?>">
		</div>
		<div class="formItem">
			<label for="sgpb-autoresponder-from-email" class="formItem__title">
				<?php _e( 'From email', SG_POPUP_TEXT_DOMAIN ); ?>:
			</label>
			<input type="text" id="sgpb-autoresponder-from-email" name="sgpb-autoresponder-from-email"
			       value="<?php echo SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-from-email' ); ?>">
		</div>
		<div class="formItem">
			<label for="sgpb-autoresponder-reply-to" class="formItem__title">
				<?php _e( 'Reply to', SG_POPUP_TEXT_DOMAIN ); ?>:
			</label>
			<input type="text" id="sgpb-autoresponder-reply-to" name="sgpb-autoresponder-reply-to"
			       value="<?php echo SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-reply-to' ); ?>">
		</div>
		<div class="formItem">
			<label for="sgpb-autoresponder-email-template"
			       class="formItem__title">
				<?php _e( 'Email template', SG_POPUP_TEXT_DOMAIN ) ?>:
			</label>
			<?php if ( ! empty( $allTemplates ) ): ?>
				<?php echo AdminHelper::createSelectBox( $allTemplates, $selectedTemplate, array( 'name'  => 'sgpb-autoresponder-email-template',
				                                                                                  'class' => 'js-sg-select2'
				) ); ?>
			<?php else: ?>
				<input type="text" class="sg-hide" required="">
				<p style="color: #ff0000;"><?php _e( 'You don\'t have an email template, please, <a href="' . SG_SUBSCRIPTION_PLUS_EMAIL_CREATION_PAGE_URL . '">create one</a> to continue.', SG_POPUP_TEXT_DOMAIN ); ?></p>
			<?php endif; ?>
		</div>
		<div class="formItem">
			<label for="sgpb-autoresponder-lists" class="formItem__title">
				<?php _e( 'Subscribers list', SG_POPUP_TEXT_DOMAIN ); ?>:

			</label>
			<?php echo AdminHelper::createSelectBox( $allLists, $selectedLists, array( 'name'     => 'sgpb-autoresponder-lists[]',
			                                                                           'id'       => 'sgpb-autoresponder-lists',
			                                                                           'class'    => 'js-sg-select2 sgpb-response-lists-js',
			                                                                           'multiple' => 'multiple',
			                                                                           'required' => 'required'
			) ); ?>
			<span class="question-mark sgpb-info-icon">B</span>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text">
					<?php _e( 'Select the subscribers collected via popup.', SG_POPUP_TEXT_DOMAIN ) ?>.
				</span>
			</div>
		</div>
		<?php

		$data    = SubscriptionPlusAdminHelper::getOption( 'sgpb-autoresponder-events' );
		$builder = sgpb\ConditionBuilder::additionalConditionBuilder( $data );
		?>

		<div class="popup-conditions-wrapper popup-special-conditions-wrapper behavior-after-special-events-wrapper"
		     data-condition-type="autoresponder-events">
			<?php
			$condition = $builder['autoresponder-events'];
			$creator   = new sgpb\ConditionCreator( $condition );
			echo $creator->render();
			?>
		</div>

	</div>

</div>
