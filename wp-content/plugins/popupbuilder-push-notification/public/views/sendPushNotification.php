<?php
use sgpbpush\AdminHelper;
use sgpb\AdminHelper as PopupAdminHelper;
$defaultImageUrl = SGPB_PUSH_NOTIFICATION_IMAGE_URL.SGPB_PUSH_NOTIFICATION_DEFAULT_IMAGE_NAME;
$chromeImageUrl = SGPB_PUSH_NOTIFICATION_IMAGE_URL.SGPB_PUSH_NOTIFICATION_CHROME_IMAGE_NAME;
$titleDefaultText = __('Hi, it\'s a title', SG_POPUP_TEXT_DOMAIN);
$titleDefaultMessage = __('Your message content', SG_POPUP_TEXT_DOMAIN);
$popupIdAndTitles = AdminHelper::getAllNotificationPopups();
?>
<div class="sgpb-wrapper sgpb-settings formItem sgpb-align-item-start">
	<div class="sgpb-width-40 sgpb-padding-x-20">
		<div class="formItem">
			<label for="sgpb-push-notification-popups" class="formItem__title sgpb-margin-right-10"><?php _e('List of recipients', SG_POPUP_TEXT_DOMAIN); ?>:</label>
			<?php echo PopupAdminHelper::createSelectBox($popupIdAndTitles, '', array('name' => 'sgpb-push-notification-popups', 'id' => 'sgpb-push-notification-popups', 'class' => 'sgpb-push-notification-popups js-sg-select2')); ?>
		</div>
		<div class="formItem">
			<label for="sgpb-notification-title" class="formItem__title sgpb-margin-right-10"><?php _e('Title', SG_POPUP_TEXT_DOMAIN); ?>:</label>
			<input name="sgpb-notification-title" type="text" id="sgpb-notification-title" class="sgpb-notification-title" placeholder="<?php _e('up to 50 characters', SG_POPUP_TEXT_DOMAIN); ?>" value="<?php _e('Hi, it\'s a title', SG_POPUP_TEXT_DOMAIN); ?>" required>
		</div>
		<div class="formItem">
			<label for="sgpb-notification-text" class="formItem__title sgpb-margin-bottom-10"><?php _e('Text', SG_POPUP_TEXT_DOMAIN); ?>:</label>
			<textarea name="sgpb-notification-text" id="sgpb-notification-text" class="sgpb-notification-title" required><?php _e('Your message content', SG_POPUP_TEXT_DOMAIN); ?></textarea>
		</div>
		<div class="formItem">
			<label for="sgpb-notification-custom-link" class="formItem__title sgpb-margin-right-10"><?php _e('Custom URL', SG_POPUP_TEXT_DOMAIN); ?>:</label>
			<input name="sgpb-notification-custom-link" type="text" id="sgpb-notification-custom-link" class="sgpb-notification-title" value="<?php echo get_site_url(); ?>" required>
		</div>
		<div class="formItem">
			<label for="redirect-to-url" class="formItem__title sgpb-margin-right-10">
				<?php _e('Icon', SG_POPUP_TEXT_DOMAIN)?>:
			</label>
			<div>
				<div class="sgpb-button-image-uploader-wrapper">
					<input class="sg-hide" id="js-push-upload-image" type="text" size="36" name="sgpb-button-image" value="<?php echo esc_attr($defaultImageUrl); ?>">
				</div>
			</div>
			<div class="formItem">
				<div class="sgpb-show-button-image-container" style="background-image: url(<?php echo $defaultImageUrl;?>);">
					<span class="sgpb-no-image"></span>
				</div>
				<div class="sgpb-close-btn-change-image-wrapper sgpb-margin-x-20">
					<div id="js-button-upload-image-button" class="sgpb-icons icons_blue">K</div>
				</div>
				<div class="js-sgpb-remove-close-button-image sg-hide">
					<input id="js-button-upload-image-remove-button" data-default-url="<?php echo esc_attr($defaultImageUrl); ?>" class="sgpb-btn sgpb-btn-danger" type="button" value="<?php _e('Remove', SG_POPUP_TEXT_DOMAIN);?>">
				</div>
			</div>
		</div>
		<div class="formItem">
			<input type="button" value="<?php echo _e('Send', SG_POPUP_TEXT_DOMAIN); ?>" class="sgpb-btn sgpb-btn-blue sgpb-send-notification">
			<div class="sgpb-notification-send-success-js alert alert-success sg-hide sgpb-margin-x-20">
				<?php echo _e('Successfully sent', SG_POPUP_TEXT_DOMAIN); ?>
			</div>
			<div class="sgpb-notification-send-error-js alert alert-warning sg-hide">
				<?php echo _e('Oops, something went wrong', SG_POPUP_TEXT_DOMAIN); ?>
			</div>
		</div>
	</div>
	<div class="sgpb-width-60 sgpb-padding-x-20">
		<div class="sgpb-push-notification-preview-wrapper sgpb-position-relative">
			<img src="<?= SGPB_PUSH_NOTIFICATION_PUBLIC_URL ?>/img/livepreviewBg.jpg" alt="livePreviewBg" class="sgpb-push-notification-preview-wrapper-img">
			<p class="sgpb-browser-name">
				<?php _e('Chrome, Windows', SG_POPUP_TEXT_DOMAIN); ?>
			</p>
			<div class="sgpb-preview-push sgpb-preview-push-chrome-win">
				<div class="sgpb-notify-big-image" id="sgpb-chrome-big-image"></div>
				<div class="sgpb-preview-push-flex-wrap">
					<div class="sgpb-icon-side">
						<div class="sgpb-icon-wrapper">
							<img class="cog-icon sgpb-custom-image" width="42" height="42" src="<?php echo esc_attr($defaultImageUrl); ?>">
						</div>
					</div>
					<div class="sgpb-message-wrapper">
						<div class="sgpb-notify-title">
							<?php echo $titleDefaultText; ?>
						</div>
						<div class="sgpb-notify-message">
							<?php echo $titleDefaultMessage; ?>
						</div>
						<div class="sgpb-notify-site-wrap">
							Google Chrome
						</div>
					</div>
				</div>
				<div class="sgpb-preview-push-flex-wrap pr-buttons-wrapper">
					<div class="sgpb-pr-notify-btn-1"></div>
					<div class="sgpb-pr-notify-btn-2"></div>
				</div>
			</div>

			<p class="sgpb-browser-name">
				<?php _e('Firefox, Windows', SG_POPUP_TEXT_DOMAIN); ?>
			</p>
			<div class="sgpb-preview-push sgpb-preview-push-firefox">
				<span class="sgpb-close-notify">×</span>
				<div class="sgpb-preview-push-flex-wrap">
					<div class="sgpb-icon-side">
						<div class="sgpb-icon-wrapper">
							<img id="sgpb-preview-push-firefox" width="80" height="80" src="<?php echo esc_attr($defaultImageUrl); ?>" class="sgpb-icon sgpb-custom-image">
						</div>
					</div>
					<div class="sgpb-message-wrapper">
						<div class="sgpb-notify-title">
							<?php echo $titleDefaultText; ?>
						</div>
						<div class="sgpb-notify-message">
							<?php echo $titleDefaultMessage; ?>
						</div>
						<img class="sgpb-cog-icon" width="14" height="14" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDM2OS43OTMgMzY5Ljc5MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzY5Ljc5MyAzNjkuNzkyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPGc+CgkJPGc+CgkJCTxwYXRoIGQ9Ik0zMjAuODMsMTQwLjQzNGwtMS43NTktMC42MjdsLTYuODctMTYuMzk5bDAuNzQ1LTEuNjg1YzIwLjgxMi00Ny4yMDEsMTkuMzc3LTQ4LjYwOSwxNS45MjUtNTIuMDMxTDMwMS4xMSw0Mi42MSAgICAgYy0xLjEzNS0xLjEyNi0zLjEyOC0xLjkxOC00Ljg0Ni0xLjkxOGMtMS41NjIsMC02LjI5MywwLTQ3LjI5NCwxOC41N0wyNDcuMzI2LDYwbC0xNi45MTYtNi44MTJsLTAuNjc5LTEuNjg0ICAgICBDMjEwLjQ1LDMuNzYyLDIwOC40NzUsMy43NjIsMjAzLjY3NywzLjc2MmgtMzkuMjA1Yy00Ljc4LDAtNi45NTcsMC0yNC44MzYsNDcuODI1bC0wLjY3MywxLjc0MWwtMTYuODI4LDYuODZsLTEuNjA5LTAuNjY5ICAgICBDOTIuNzc0LDQ3LjgxOSw3Ni41Nyw0MS44ODYsNzIuMzQ2LDQxLjg4NmMtMS43MTQsMC0zLjcxNCwwLjc2OS00Ljg1NCwxLjg5MmwtMjcuNzg3LDI3LjE2ICAgICBjLTMuNTI1LDMuNDc3LTQuOTg3LDQuOTMzLDE2LjkxNSw1MS4xNDlsMC44MDUsMS43MTRsLTYuODgxLDE2LjM4MWwtMS42ODQsMC42NTFDMCwxNTkuNzE1LDAsMTYxLjU1NiwwLDE2Ni40NzR2MzguNDE4ICAgICBjMCw0LjkzMSwwLDYuOTc5LDQ4Ljk1NywyNC41MjRsMS43NSwwLjYxOGw2Ljg4MiwxNi4zMzNsLTAuNzM5LDEuNjY5Yy0yMC44MTIsNDcuMjIzLTE5LjQ5Miw0OC41MDEtMTUuOTQ5LDUyLjAyNUw2OC42MiwzMjcuMTggICAgIGMxLjE2MiwxLjExNywzLjE3MywxLjkxNSw0Ljg4OCwxLjkxNWMxLjU1MiwwLDYuMjcyLDAsNDcuMy0xOC41NjFsMS42NDMtMC43NjlsMTYuOTI3LDYuODQ2bDAuNjU4LDEuNjkzICAgICBjMTkuMjkzLDQ3LjcyNiwyMS4yNzUsNDcuNzI2LDI2LjA3Niw0Ny43MjZoMzkuMjE3YzQuOTI0LDAsNi45NjYsMCwyNC44NTktNDcuODU3bDAuNjY3LTEuNzQybDE2Ljg1NS02LjgxNGwxLjYwNCwwLjY1NCAgICAgYzI3LjcyOSwxMS43MzMsNDMuOTI1LDE3LjY1NCw0OC4xMjIsMTcuNjU0YzEuNjk5LDAsMy43MTctMC43NDUsNC44NzYtMS44OTNsMjcuODMyLTI3LjIxOSAgICAgYzMuNTAxLTMuNDk1LDQuOTYtNC45MjQtMTYuOTgxLTUxLjA5NmwtMC44MTYtMS43MzRsNi44NjktMTYuMzFsMS42NC0wLjY0M2M0OC45MzgtMTguOTgxLDQ4LjkzOC0yMC44MzEsNDguOTM4LTI1Ljc1NXYtMzguMzk1ICAgICBDMzY5Ljc5MywxNTkuOTUsMzY5Ljc5MywxNTcuOTE0LDMyMC44MywxNDAuNDM0eiBNMTg0Ljg5NiwyNDcuMjAzYy0zNS4wMzgsMC02My41NDItMjcuOTU5LTYzLjU0Mi02Mi4zICAgICBjMC0zNC4zNDIsMjguNTA1LTYyLjI2NCw2My41NDItNjIuMjY0YzM1LjAyMywwLDYzLjUyMiwyNy45MjgsNjMuNTIyLDYyLjI2NEMyNDguNDE5LDIxOS4yMzgsMjE5LjkyLDI0Ny4yMDMsMTg0Ljg5NiwyNDcuMjAzeiIgZmlsbD0iIzRkNGQ0ZCIvPgoJCTwvZz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K">
					</div>
				</div>
			</div>

			<p class="sgpb-browser-name">
				<?php _e('Chrome, macOS', SG_POPUP_TEXT_DOMAIN); ?>
			</p>

			<div class="sgpb-preview-push sgpb-preview-push-chrome-mac">
				<div class="sgpb-icon-side">
					<div class="sgpb-icon-wrapper">
						<img src="<?php echo esc_attr($chromeImageUrl); ?>"  width="28" height="28" class="sgpb-icon-chrome">
					</div>
				</div>
				<div class="sgpb-message-wrapper">
					<div class="sgpb-notify-title">
						<?php echo $titleDefaultText; ?>
					</div>
					<div class="sgpb-notify-message">
						<?php echo $titleDefaultMessage; ?>
					</div>
				</div>
				<div class="sgpb-icon-side sgpb-chrome">
					<div class="sgpb-icon-wrapper sgpb-icon-wrapper-left">
						<img id="sgpb-preview-push-chrome-mac" width="42" height="42" src="<?php echo esc_attr($defaultImageUrl); ?>" class="sgpb-icon sgpb-custom-image">
					</div>
				</div>
				<div class="sgpb-icon-side text-center">
					<div class="sgpb-system-btn"><?php _e('Close', SG_POPUP_TEXT_DOMAIN); ?></div>
					<div class="sgpb-system-btn"><?php _e('Settings', SG_POPUP_TEXT_DOMAIN); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
