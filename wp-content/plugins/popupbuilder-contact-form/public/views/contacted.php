<?php
require_once SGPB_CONTACT_FORM_DATA_TABLE_PATH.'ContactedSubscribers.php';
?>

<div class="sgpb sgpb-padding-20">
	<h1 class="wp-heading-inline"><?php _e('Contacted users', SG_POPUP_TEXT_DOMAIN)?></h1>
	<div class="formItem sgpb-justify-content-between">
		<a href="javascript:void(0)" class="page-title-action sgpb-export-contactform sgpb-btn sgpb-btn-blue sgpb-btn--rounded"><?php _e('Export', SG_POPUP_TEXT_DOMAIN)?></a>
		<div>
			<div style="text-align: right" id="sgpbPostSearch">
				<div class="sgpb--group">
					<input type="text" id="sgpbContactedUsers" placeholder="Search Subscriber" class="sgpb-input">
					<input type="submit" value="GO!" id="sgpbContactedUsersSubmit" class="sgpb-btn sgpb-btn-blue">
				</div>
			</div>
		</div>
	</div>
	<div class="sgpb-contact-list wrap sgpb">
		<?php
		$table = new SGPBContactedSubscribers();
		echo $table;
		?>
	</div>

</div>

<style>
	.sgpb-contact-list .search.search-box{
		display: none;
	}
</style>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		const myForm = $('#posts-filter-sgpbContactedSubscribers');
		const searchValue = $('#sgpbContactedSubscribers-search-input').val();
		$('#posts-filter-sgpbContactedSubscribers .tablenav.top .tablenav-pages').append($('.subsubsub').addClass('show'));
		myForm.append($('#posts-filter-sgpbContactedSubscribers .tablenav.bottom .tablenav-pages:not(.no-pages, .one-page) .pagination-links'));
		$('#sgpbContactedUsers').val(searchValue);
		$('#sgpbContactedUsers').keyup('enter', function (e) {
			if (e.key === 'Enter') {
				$('#sgpbContactedSubscribers-search-input').val(this.value);
				$(myForm).submit();
			}
		});
		$('#sgpbContactedUsersSubmit').on('click', function () {
			$('#sgpbContactedSubscribers-search-input').val($('#sgpbContactedUsers').val());
			$(myForm).submit();
		})
	});
</script>
