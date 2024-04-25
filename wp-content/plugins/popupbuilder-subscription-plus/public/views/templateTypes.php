<?php
use sgpbsubscriptionplus\EmailTemplate;
use sgpbsubscriptionplus\SgpbPopupVersionDetectionSubscriptionPlus;

$templates = EmailTemplate::getAllDefaultTemplatesData();
$versionDetection = new SgpbPopupVersionDetectionSubscriptionPlus();
?>
<?php if ($versionDetection->canLoadView()) : ?>
	<div class="sgpb sgpb-wrapper sgpb-padding-20">
		<div class="formItem">
			<h2 class="sgpb-header-h3"><?php _e('Add Email Template', SG_POPUP_TEXT_DOMAIN); ?></h2>
		</div>
		<div class="sgpb-wrapper sgpb-email-templates">
			<div class="formItem sgpb-flex-direction-column sgpb-align-item-baseline">
				<p class="formItem__title"><?php _e('From scratch', SG_POPUP_TEXT_DOMAIN); ?></p>

				<a class="sgpb-margin-y-20" href="<?php echo EmailTemplate::getCreateTemplateUrl(); ?>">
					<span class="sgpb-icons sgpb-add-email-template">L</span>
				</a>
			</div>
			<div class="formItem">
				<p class="formItem__title"><?php _e('Use template', SG_POPUP_TEXT_DOMAIN); ?></p>
			</div>
			<div class="formItem">
				<?php foreach ($templates as $templateType): ?>
					<?php $index = $templateType['index']; ?>
					<?php $name = $templateType['name']; ?>
					<a class="sgpb-edit-email-template sgpb-<?php echo $name; ?>-div sgpb-margin-right-20" href="<?php echo EmailTemplate::getCreateTemplateUrl($index); ?>">

					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif ?>
