<?php
use sgpbsubscriptionplus\SubscriptionPlusConfigDataHelper as helper;
?>

<div class="sgpb sgpb-header">
	<h1 class="sgpb-header-h1 sgpb-margin-bottom-30"><?php _e( 'Autoresponders', SG_POPUP_TEXT_DOMAIN ) ?></h1>
	<div class="sgpb-margin-bottom-50 sgpb-display-flex sgpb-justify-content-between">
		<div>
			<a class="page-title-action sgpb-display-inline-block sgpb-btn sgpb-btn--rounded sgpb-btn-blue--outline sgpb-padding-x-30" href="<?php echo helper::addNewAutoresponderUrl(); ?>">
				<?php _e( 'Add New Autoresponder', SG_POPUP_TEXT_DOMAIN ); ?>
			</a>
		</div>
		<div style="text-align: right" id="sgpbPostSearch">
			<div class="sgpb--group">
				<input type="text" id="sgpbSearchInPosts" placeholder="Search Autoresponders" class="sgpb-input">
				<input type="submit" value="GO!" id="sgpbSearchInPostsSubmit" class="sgpb-btn sgpb-btn-blue">
			</div>
		</div>
	</div>
</div>
<style>
	#wpbody-content > div.wrap > h1,
	.notice,
	#wpbody-content > div.wrap > a {
		display: none !important;
	}
	.sgpb-table .search-box {
		display: none;
	}
</style>
