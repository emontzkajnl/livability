<?php
	use sgpbaw\SGPBAWeberApi;
	$aweberObj = new SGPBAWeberApi();
	$aweber = $aweberObj->getAweberObj();
?>
<div class="sgpb">
	<div class="sgpb-wrapper sgpb-padding-20">
		<h2 class="sgpb-header-h2"><?php _e('AWeber Settings', SG_POPUP_TEXT_DOMAIN); ?></h2>
		<div class="formItem">
			<div class="handlediv js-special-title" title="Click to toggle"><br></div>
			<h3 class="formItem__title">
				<?php _e('API Settings', SG_POPUP_TEXT_DOMAIN); ?>
			</h3>
		</div>
		<div class="formItem subFormItem">
			<label class="subFormItem__title sgpb-margin-right-10"><?php _e('Status', SG_POPUP_TEXT_DOMAIN); ?></label>
			<?php if(!get_option('sgpbAccessToken')): ?>
				<span class="sgpb-aweber-connect-status sgpb-aweber-not-connected btn-sm"><?php _e('Not Connected', SG_POPUP_TEXT_DOMAIN); ?></span>
			<?php else : ?>
				<span class="sgpb-aweber-connect-status sgpb-aweber-connected btn-sm"><?php _e('Connected', SG_POPUP_TEXT_DOMAIN); ?></span>
			<?php endif;?>
		</div>
		<div class="formItem">
			<?php if (!get_option('sgpbAccessToken')): ?>
				<?php

				if (empty($_GET['oauth_token'])) {
					$aweberObj->saveRequestSecretToken();
					$authUrl = $aweber->getAuthorizeUrl();
					?>
					<div class="sg-aweber-settings-wrapper">
						<span class="sgpb-aweber-connect-button sgpb-btn sgpb-btn-blue" data-auth-url="<?php echo $authUrl;?>"><?php _e('Connect Account', SG_POPUP_TEXT_DOMAIN); ?></span>
					</div>
					<?php
					exit();
				}
				$aweberObj->saveTokensFromGetRequest();
				echo "<script type=\"text/javascript\">
				window.close();
				window.opener.location.reload();
			</script>";
				exit();
				?>

			<?php else: ?>
				<span class="sgpb-btn sgpb-btn-danger sg-aweber-disconnect"><?php _e('Disconnect', SG_POPUP_TEXT_DOMAIN); ?></span>
				<img src="<?php echo plugins_url('img/wpAjax.gif', dirname(__FILE__).'../'); ?>" alt="gif" class="spinner-aweber-disconnect sg-hide-element js-sg-spinner js-sg-import-gif">
			<?php endif; ?>
		</div>

	</div>

</div>
