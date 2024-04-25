<div class="sgpb-not-delete-button-wrapper sgpb-display-flex sgpb-justify-content-between">
	<div style="text-align: right" id="sgpbPostSearch">
		<div class="sgpb--group">
			<input type="text" id="sgpbSearchInNotificationSubscribers" placeholder="Search Subscriber" class="sgpb-input">
			<input type="submit" value="GO!" id="sgpbSearchInNotificationSubscribersSubmit" class="sgpb-btn sgpb-btn-blue">
		</div>
	</div>
	<div>
		<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-notification-remove-spinner js-sg-spinner sg-hide-element js-sg-import-gif" width="20px">
		<input type="button" value="<?php _e('Delete', SG_POPUP_TEXT_DOMAIN)?>" class="sgpb-note-delete-button sgpb-btn sgpb-btn-danger" data-ajaxNonce="<?php echo SG_AJAX_NONCE;?>">
	</div>
</div>
<div class="sgpb-notifications-list">
    <?php
    $table = new NotificationSubscribers();
    echo $table;
    ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		const myForm = $('#posts-filter-ngpbNotificationSubscribers');
		const searchValue = $('#ngpbNotificationSubscribers-search-input').val();
		$('#posts-filter-ngpbNotificationSubscribers .tablenav.top .tablenav-pages').append($('.subsubsub').addClass('show'));
		myForm.append($('#posts-filter-ngpbNotificationSubscribers .tablenav.bottom .tablenav-pages:not(.no-pages, .one-page) .pagination-links'));
		$('#sgpbSearchInNotificationSubscribers').val(searchValue);
		$('#sgpbSearchInNotificationSubscribers').keyup('enter', function (e) {
			if (e.key === 'Enter') {
				$('#ngpbNotificationSubscribers-search-input').val(this.value);
				$(myForm).submit();
			}
		});
		$('#sgpbSearchInNotificationSubscribersSubmit').on('click', function () {
			$('#ngpbNotificationSubscribers-search-input').val($('#sgpbSearchInNotificationSubscribers').val());
			$(myForm).submit();
		})
	});
</script>
