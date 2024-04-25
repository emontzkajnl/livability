<?php
	use sgpb\AdminHelper;
	use sgpbpdf\ConfigDataHelper;
	$defaultData = ConfigDataHelper::defaultData();
	$subOptionClass = '';
?>

<div class="sgpb sgpb-wrapper sg-wp-editor-container">
	<div class="row form-group formItem">
		<label for="js-upload-pdf" class="col-md-3 formItem__title <?php echo $subOptionClass; ?>">
			<?php _e('Upload .pdf file', SG_POPUP_TEXT_DOMAIN) ?>:
		</label>
		<div class="col-md-5">
			<input class="sgpb-width-100" id="js-upload-pdf" type="text" size="36" name="sgpb-pdf-url" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-pdf-url')); ?>" required>
		</div>
		<div class="col-md-1">
			<div id="js-upload-pdf-button" class="sgpb-icons icons_blue">K</div>
		</div>
	</div>
	<div class="row form-group formItem">
		<label for="content-click" class="col-md-3 formItem__title sgpb-static-padding-top">
			<?php _e('PDF Zoom Level', SG_POPUP_TEXT_DOMAIN) ?>:
		</label>
		<div class="col-md-5">
			<?php echo AdminHelper::createSelectBox($defaultData['zoomLevel'], $popupTypeObj->getOptionValue('sgpb-pdf-zoom-level'), array('name' => 'sgpb-pdf-zoom-level', 'class'=>'js-sg-select2')); ?>
		</div>
		<div class="col-md-1 sgpb-info-wrapper sgpb-align-item-center sgpb-justify-content-around sgpb-display-inline-flex">
			<div class="question-mark sgpb-info-icon">B</div>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text">
					<?php _e('Choose the zoom level for the file.', SG_POPUP_TEXT_DOMAIN);?>
				</span>
			</div>
		</div>
	</div>
	<div class="row form-group formItem">
		<label for="sgpb-pdf-selected-page" class="col-md-3 formItem__title sgpb-static-padding-top">
			<?php _e('Default selected page', SG_POPUP_TEXT_DOMAIN) ?>:
		</label>
		<div class="col-md-5">
			<input type="number" min="1" step="1" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-pdf-selected-page'))?>" class="sgpb-width-100" id="sgpb-pdf-selected-page" name="sgpb-pdf-selected-page" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-pdf-selected-page'))?>">
			<span class="sgpb-info-span"><?php _e('Note: For exact page selection it\'s recommended to select the "Automatic" mode in the "PDF Zoom Level"', SG_POPUP_TEXT_DOMAIN);?></span>
		</div>
		<div class="col-md-1 sgpb-info-wrapper sgpb-align-item-center sgpb-justify-content-around sgpb-display-inline-flex">
			<div class="question-mark sgpb-info-icon">B</div>
			<div class="sgpb-info-wrapper">
				<span class="infoSelectRepeat samefontStyle sgpb-info-text">
					<?php _e('Mention the page that will be selected by default.', SG_POPUP_TEXT_DOMAIN);?>
				</span>
			</div>

		</div>
	</div>
</div>
