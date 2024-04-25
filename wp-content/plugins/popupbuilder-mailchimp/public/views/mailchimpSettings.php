<?php
use sgpbm\MailchimpApi;
$apiKey = get_option('SGPB_MAILCHIMP_API_KEY');
$status = MailchimpApi::isConnected();
?>
<div class="sgpb">
	<div class="sgpb-wrapper sgpb-padding-20">
		<h2 class="sgpb-header-h2"><?php _e( 'Mailchimp Settings', SG_POPUP_TEXT_DOMAIN ); ?></h2>
		<div class="formItem">
			<div class="handlediv js-special-title" title="Click to toggle"><br></div>
			<h3 class="formItem__title">
				<span><?php _e( 'API Settings', SG_POPUP_TEXT_DOMAIN ); ?></span>
			</h3>
		</div>
		<div class="formItem subFormItem">
			<label class="subFormItem__title sgpb-margin-right-10"><?php _e( 'Status', SG_POPUP_TEXT_DOMAIN ); ?></label>
			<?php if ( ! $status ): ?>
				<span class="sg-mailchimp-connect-status sg-mailchimp-not-connected"><?php _e( 'Not connected', SG_POPUP_TEXT_DOMAIN ) ?></span>
			<?php else : ?>
				<span class="sg-mailchimp-connect-status sg-mailchimp-connected"><?php _e( 'Connected', SG_POPUP_TEXT_DOMAIN ) ?></span>
			<?php endif; ?>
		</div>
		<form action="<?php echo SG_POPUP_ADMIN_URL; ?>admin-post.php?action=sgpb_save_mailchimp_api_key"
		      method="POST">
			<?php
			if ( function_exists( 'wp_nonce_field' ) ) {
				wp_nonce_field( 'sgpbPopupBuilderMailchimpApiKeySave' );
			}
			?>
			<div class="formItem subFormItem sgpb-align-item-baseline">
				<label id="sgpb-api-key-label"
				       class="subFormItem__title sgpb-margin-right-10"
				       for="sgpb-mailchimp-api-key">
					<?php _e( 'API Key', SG_POPUP_TEXT_DOMAIN ) ?>
				</label>
				<div class="sg-apikey-input-div">
					<input type="password" class="widefat" placeholder="Your Mailchimp API key"
					       id="sgpb-mailchimp-api-key" name="mailchimp-api-key"
					       value="<?php echo esc_attr( $apiKey ); ?>" autocomplete="off">
					<p class="sgpb-mailchimp-help">
						<span><?php _e( 'The API key for connecting with your Mailchimp account', SG_POPUP_TEXT_DOMAIN ); ?>.</span>
						<br>
						<a class="sgpb-mailchimp-to-api-link" target="_blank" href="https://admin.mailchimp.com/account/api">
							<?php _e( 'Get your API key here', SG_POPUP_TEXT_DOMAIN ) ?>.
						</a>
					</p>
				</div>
			</div>
			<div class="formItem subFormItem">
				<label for="sg-show-mailchimp-apikey" class="subFormItem__title"><?php _e( 'Show', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<input type="checkbox" id="sg-show-mailchimp-apikey">
			</div>
			<div class="formItem">
				<input type="submit" class="sgpb-btn sgpb-btn-blue"
				       value="<?php _e( 'Save Changes', SG_POPUP_TEXT_DOMAIN ) ?>">
			</div>
		</form>
	</div>
</div>
