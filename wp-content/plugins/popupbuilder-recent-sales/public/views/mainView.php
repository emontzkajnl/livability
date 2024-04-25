<?php

use sgpbrs\DefaultOptionsData;
use sgpb\AdminHelper;
use sgpbrs\AdminHelper as AdminHelperRecentSales;
use sgpb\RecentSalesPopup;

$isWooExists = AdminHelperRecentSales::isWoocommerceExists();
$isEddExists = AdminHelperRecentSales::isEddExists();
if ( ! $isWooExists && ! $isEddExists ) {
	_e( SGPB_WOO_DISPLAY_NAME . ' and ' . SGPB_EDD_DISPLAY_NAME . ' plugins are not installed on your website to use the Recent sales popup. Please, follow the links to install either of them: <a href="' . SGPB_WOO_PLUGIN_URL . '" target="__blank">WooCommerce</a> or <a href="' . SGPB_EDD_PLUGIN_URL . '" target="__blank">EDD (Easy Digital Downloads)</a>', SG_POPUP_TEXT_DOMAIN );

	return;
}

$isWooActive = AdminHelperRecentSales::isWoocommerceActive();
$isEddActive = AdminHelperRecentSales::isEddActive();
if ( ! $isEddActive && ! $isWooActive ) {
	_e( SGPB_WOO_DISPLAY_NAME . ' and ' . SGPB_EDD_DISPLAY_NAME . ' plugins are not active on your site. Please, activate either of them in your Plugins section to use the Recent sales popup.', SG_POPUP_TEXT_DOMAIN );

	return;
}

$sales        = DefaultOptionsData::getSource();
$defaultImage = DefaultOptionsData::getDefaultCustomImage();
$imageTypes   = DefaultOptionsData::getImageTypes();

$imageType   = $popupTypeObj->getOptionValue( 'sgpb-sales-image-type' );
$customImage = $popupTypeObj->getOptionValue( 'sgpb-sales-image' );
if ( ! $customImage ) {
	$customImage = $defaultImage;
}
$source              = $popupTypeObj->getOptionValue( 'sgpb-sales-source' );
$contentTemplateInfo = '[name] - buyer full name<br>[firstName] - buyer first name<br>[lastName] - buyer last name<br>[country] - buyer country<br>[city] - buyer city<br>[title] - ordered product title<br>[time] - purchase time<br>';
$allStatuses         = AdminHelperRecentSales::getStatusBySource( $source );
$selectedStatus      = $popupTypeObj->getOptionValue( 'sgpb-orders-status-lists' );

if ( empty( $selectedStatus ) ) {
	$selectedStatus = AdminHelperRecentSales::getDefaultStatusBySource( $source );
}
?>

<div class="sgpb">
	<div class="sgpb-wrapper formItem sgpb-recentSales">
		<div class="sgpb-width-100">
			<div class="formItem">
				<label for="sgpb-sales-source"
				       class="formItem__title"><?php _e( 'Source', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<?php echo AdminHelper::createSelectBox( $sales, $popupTypeObj->getOptionValue( 'sgpb-sales-source' ), array( 'name'  => 'sgpb-sales-source',
				                                                                                                              'class' => 'js-sg-select2',
				                                                                                                              'id'    => 'sgpb-sales-source'
				) ); ?>
			</div>
			<div class="formItem">
				<label for="sgpb-sales-content"
				       class="formItem__title"><?php _e( 'Content Template', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<textarea id="sgpb-sales-content"
				          name="sgpb-sales-content"><?php echo esc_html( $popupTypeObj->getOptionValue( 'sgpb-sales-content' ) ) ?></textarea>
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e( $contentTemplateInfo, SG_POPUP_TEXT_DOMAIN ) ?>
					</span>
				</div>
			</div>
			<div class="formItem sg-hide">
				<label for="sgpb-sales-source"
				       class="formItem__title"><?php _e( 'Purchases', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<?php echo AdminHelper::createSelectBox( array( 'All Products' ), 'All Products', array( 'name'  => '',
				                                                                                         'class' => 'js-sg-select2 form-control',
				                                                                                         'id'    => 'sgpb-sales-source'
				) ); ?>
			</div>
			<div class="formItem">
				<label for="sgpb-sales-popup-count"
				       class="formItem__title"><?php _e( 'Number to show', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<input id="sgpb-sales-popup-count" type="number" name="sgpb-sales-popup-count" min="1"
				       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-sales-popup-count' ) ); ?>">
			</div>
			<div class="formItem">
				<label for="sgpb-sales-initial-delay"
				       class="formItem__title"><?php _e( 'Initial delay', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<input id="sgpb-sales-initial-delay" type="number" name="sgpb-sales-initial-delay" min="1"
				       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-sales-initial-delay' ) ); ?>">
			</div>
			<div class="formItem">
				<label for="sgpb-sales-each-popup-delay"
				       class="formItem__title"><?php _e( 'Display time', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<input type="checkbox" name="sgpb-auto-close-recent-sales" class="sg-hide" checked="">
				<input id="sgpb-sales-each-popup-delay" type="number" name="sgpb-auto-close-time-recent-sales" min="1"
				       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-auto-close-time-recent-sales' ) ); ?>">
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e( 'Set how long each item will be visible (seconds).', SG_POPUP_TEXT_DOMAIN ) ?>
					</span>
				</div>
			</div>
			<div class="formItem">
				<label for="sgpb-sales-between-popup-delay"
				       class="formItem__title"><?php _e( 'Delay between', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<input id="sgpb-sales-between-popup-delay" type="number" name="sgpb-sales-between-popup-delay" min="1"
				       value="<?php echo esc_attr( $popupTypeObj->getOptionValue( 'sgpb-sales-between-popup-delay' ) ); ?>">
				<span class="question-mark sgpb-info-icon">B</span>
				<div class="sgpb-info-wrapper">
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e( 'Set the delay between popups (seconds).', SG_POPUP_TEXT_DOMAIN ) ?>
					</span>
				</div>
			</div>
			<div class="formItem">
				<label for="sgpb-sales-image-type"
				       class="formItem__title"><?php _e( 'Show image', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<?php echo AdminHelper::createSelectBox( $imageTypes, $popupTypeObj->getOptionValue( 'sgpb-sales-image-type' ), array( 'name'  => 'sgpb-sales-image-type',
				                                                                                                                       'class' => 'js-sg-select2',
				                                                                                                                       'id'    => 'sgpb-sales-image-type'
				) ); ?>
			</div>
			<div class="formItem">
				<label for="sgpb-orders-status-lists"
				       class="formItem__title"><?php _e( 'Orders Status', SG_POPUP_TEXT_DOMAIN ); ?></label>
				<img src="<?php echo SG_POPUP_IMG_URL . 'ajaxSpinner.gif'; ?>" width="20px"
				     class="sgpb-hide sgpb-js-status-lists-spinner">
				<?php echo AdminHelper::createSelectBox( $allStatuses, $selectedStatus, array( 'name'     => 'sgpb-orders-status-lists[]',
				                                                                               'id'       => 'sgpb-orders-status-lists',
				                                                                               'class'    => 'js-sg-select2 sgpb-response-lists-js sgpb-orders-status-lists',
				                                                                               'multiple' => 'multiple',
				                                                                               'required' => 'required'
				) ); ?>
			</div>
			<div class="sgpb-sales-image-wrapper-row row form-group<?php echo ( $imageType == 'custom' ) ? '' : ' sg-hide'; ?>">
				<div class="sgpb-button-image-uploader-wrapper">
					<input class="sg-hide" id="js-upload-sales-image" type="text" size="36" name="sgpb-sales-image"
					       value="<?php echo ( esc_attr( $popupTypeObj->getOptionValue( 'sgpb-sales-image' ) ) ) ? esc_attr( $popupTypeObj->getOptionValue( 'sgpb-sales-image' ) ) : ''; ?>">
				</div>
				<div class="formItem">
					<div class="sgpb-close-btn-image-wrapper">
						<div class="sgpb-show-sales-image-container"
						     style="background-image: url(<?php echo $customImage; ?>);">
							<span class="sgpb-no-image"></span>
						</div>
					</div>
					<div class="sgpb-close-btn-change-image-wrapper sgpb-margin-x-10">
						<input id="js-upload-sales-image-button" class="sgpb-btn sgpb-btn-blue" type="button"
						       value="<?php _e( 'Change image', SG_POPUP_TEXT_DOMAIN ); ?>">
					</div>
					<div class="js-sgpb-remove-sales-image<?php echo ( ! $popupTypeObj->getOptionValue( 'sgpb-sales-image' ) ) ? ' sg-hide' : ''; ?>">
						<input id="js-upload-sales-image-remove-button" class="sgpb-btn sgpb-btn-danger" type="button"
						       value="<?php _e( 'Remove', SG_POPUP_TEXT_DOMAIN ); ?>">
					</div>
				</div>

			</div>

		</div>
	</div>
</div>
