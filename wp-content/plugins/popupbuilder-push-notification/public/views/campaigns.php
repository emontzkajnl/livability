<div class="sgpb-not-delete-button-wrapper sgpb-display-flex sgpb-justify-content-between">
	<div style="text-align: right" id="sgpbPostSearch">
		<div class="sgpb--group">
			<input type="text" id="sgpbSearchInCampaigns" placeholder="Search Campaign" class="sgpb-input">
			<input type="submit" value="GO!" id="sgpbSearchInCampaignsSubmit" class="sgpb-btn sgpb-btn-blue">
		</div>
	</div>
	<div>
		<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-notification-remove-spinner js-sg-spinner sg-hide-element js-sg-import-gif" width="20px">
		<input type="button" value="<?php _e('Delete', SG_POPUP_TEXT_DOMAIN)?>" class="sgpb-campaigns-delete-button sgpb-btn sgpb-btn-danger" data-ajaxNonce="<?php echo SG_AJAX_NONCE;?>">
	</div>
</div>
<div class="sgpb-notifications-list">
    <?php
    $table = new NotificationCampaigns();
    echo $table;
    ?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		const myForm = $('#posts-filter-ngpbNotificationCampaign');
		const searchValue = $('#ngpbNotificationCampaign-search-input').val();
		$('#posts-filter-ngpbNotificationCampaign .tablenav.top .tablenav-pages').append($('.subsubsub').addClass('show'));
		myForm.append($('#posts-filter-ngpbNotificationCampaign .tablenav.bottom .tablenav-pages:not(.no-pages, .one-page) .pagination-links'));
		$('#sgpbSearchInCampaigns').val(searchValue);
		$('#sgpbSearchInCampaigns').keyup('enter', function (e) {
			if (e.key === 'Enter') {
				$('#ngpbNotificationCampaign-search-input').val(this.value);
				$(myForm).submit();
			}
		});
		$('#sgpbSearchInCampaignsSubmit').on('click', function () {
			$('#ngpbNotificationCampaign-search-input').val($('#sgpbSearchInCampaigns').val());
			$(myForm).submit();
		})
	});
</script>
