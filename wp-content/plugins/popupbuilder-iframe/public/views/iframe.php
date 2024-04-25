<div class="sgpb sgpb-wrapper">
	<div class="row formItem">
		<div class="col-md-8">
			<div class="row">
				<label for="spgb-iframe-url" class="col-md-3 formItem__title sgpb-static-padding-top">
					<?php _e('Enter iframe URL', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<div class="col-md-8">
					<input name="sgpb-iframe-url" id="spgb-iframe-url" type="url" class="sgpb-width-100" required placeholder="http://" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-iframe-url'))?>">
				</div>
			</div>
			<?php
				$iframeInvalidURL = $popupTypeObj->getOptionValue('sgpb-iframe-invalid-url');
				$iframeProtocolWarning = $popupTypeObj->getOptionValue('sgpb-iframe-protocol-warning');
				$sameOriginWarning = $popupTypeObj->getOptionValue('sgpb-iframe-same-origin-warning');
			?>
			<div class="alert alert-warning sgpb-hide sgpb-iframe-warnings sgpb-same-origin-warning"
			     data-invalid-url="<?php echo $iframeInvalidURL; ?>"
			     data-protocol-warning="<?php echo $iframeProtocolWarning; ?>"
			     data-same-origin="<?php echo $sameOriginWarning; ?>"
			>
			</div>
			<!-- .col-md-6 end -->
		</div>
	<!-- main .row end -->
	</div>
<!-- .sgpb-wrapper end -->
</div>
