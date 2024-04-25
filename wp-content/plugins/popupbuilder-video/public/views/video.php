<div class="sgpb sgpb-wrapper">
	<div class="row formItem">
		<div class="col-md-12">
			<div class="row form-group">
				<label for="sgpb-video-url" class="col-md-4 formItem__title">
					<?php _e('Enter video URL or Custom video', SG_POPUP_TEXT_DOMAIN);?>:
				</label>
				<div class="col-md-5">
					<input class="sgpb-video-url-input sgpb-width-100" id="sgpb-video-url" placeholder='https://...' type="text" name="sgpb-video-url" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-video-url')); ?>" required>
				</div>
				<div class="col-md-1">
					<div id="js-upload-video-button" class="sgpb-icons icons_blue">K</div>
				</div>
			</div>
			<?php
				$videoInvalidURL = $popupTypeObj->getOptionValue('sgpb-video-invalid-url');
				$notSupportedUrl = $popupTypeObj->getOptionValue('sgpb-video-not-supported-url');
			?>
			<div class="alert alert-warning sgpb-hide sgpb-video-warnings sgpb-same-origin-warning"
			     data-invalid-url="<?php echo $videoInvalidURL; ?>"
			     data-not-supported="<?php echo $notSupportedUrl; ?>"
			>
			</div>
		</div>
		<div class="col-md-12">
			<div class="row form-group">
				<label for="sgpb-autoplay" class="col-md-4 formItem__title">
					<?php _e('Autoplay', SG_POPUP_TEXT_DOMAIN);?>:
				</label>
				<div class="col-md-6">
					<input id="sgpb-autoplay" name="sgpb-video-autoplay" type="checkbox" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-video-autoplay')); ?>>
				</div>
			</div>
		</div>
	</div>
</div>
