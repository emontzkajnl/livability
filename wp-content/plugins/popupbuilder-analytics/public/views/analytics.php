<?php

use sgpban\AnalyticsParameters;
use sgpban\DefaultOptionsData;
use sgpb\AdminHelper;

$popups = AdminHelper::getPopupsIdAndTitle();
$dataRanges = DefaultOptionsData::getDateRanges();
$eventsCheckboxData = DefaultOptionsData::getEventsCheckboxData();
$savedData = isset($_COOKIE['sgpbAnalyticsEventList']) ? json_decode(stripslashes($_COOKIE['sgpbAnalyticsEventList'])) : null;

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="sgpb sgpb-wrapper sgpb-padding-30">
	<div class="sgpb-analytics">
		<h2 class="sgpb-header-h1">Analytics</h2>

		<div class="sgpb-position-relative sgpb-padding-y-20 sgpb-margin-y-30">
			<div class="sgpb-analytics-loading sg-hide-element">
				<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-analytics-ajax-spinner spinner-loading-data js-sg-spinner js-sg-import-gif">
			</div>
			<div class="sgpb-chart-wrapper sgpb-width-60  sgpb-shadow-black-10 sgpb-margin-10">
				<div id="sgpb-curve-chart"  class="sg-curve-chart"></div>
			</div>
		</div>
		<div id="post-body" class="metabox-holder">

			<h3 class="formItem">
				<label class="formItem__title"><?php _e('Analytics Settings', SG_POPUP_TEXT_DOMAIN); ?></label>
			</h3>

			<div class="formItem">
				<div class="sgpb-display-flex sgpb-flex-direction-column sgpb-margin-right-30">
					<label class="subFormItem__title sgpb-margin-bottom-20"><?php _e('Date Range', SG_POPUP_TEXT_DOMAIN)?></label>
					<?php echo AdminHelper::createSelectBox($dataRanges, '', array('class' => 'sgpbp-analytic-date-ranges js-sg-select2 sgpb-chart-change sgpb-date-range'))?>
				</div>
				<div class="sgpb-display-flex sgpb-flex-direction-column">
					<label class="subFormItem__title sgpb-margin-bottom-20"><?php _e('Popups', SG_POPUP_TEXT_DOMAIN)?></label>
					<?php echo AdminHelper::createSelectBox($popups, '', array('class' => 'sgpbp-analytic-popups-list js-sg-select2 sgpb-chart-change sgpb-targets'))?>
				</div>
			</div>

			<div class="sgpb-analytics-content">
				<h3 class="formItem">
					<label class="formItem__title"><?php _e('Events', SG_POPUP_TEXT_DOMAIN);?></label>
				</h3>
				<div class="row">
					<?php foreach ($eventsCheckboxData['fields'] as $field): ?>
						<?php
						$checked = 'checked';
						if (!empty($savedData)){
							if (array_search($field['attr']['value'],$savedData) === false){
								$checked = '';
							}
						}
						?>
						<div class="col-md-3 formItem sgpb-justify-content-between">
							<span class="subFormItem__title col-md-7 col-lg-6 sgpb-nowrap"><?php echo $field['label']['name']; ?></span>
							<div class="sgpb-onOffSwitch">
								<input type="checkbox" value="<?php echo $field['attr']['value']; ?>" name="<?php echo $field['attr']['name']; ?>" class="sgpb-onOffSwitch-checkbox sgpb-events-list-checkbox" id="sgpb-analytics-event-<?php echo $field['attr']['id']; ?>" tabindex="0" <?php echo esc_attr($checked)?>>
								<label class="sgpb-onOffSwitch__label" for="sgpb-analytics-event-<?php echo $field['attr']['id']; ?>">
									<span class="sgpb-onOffSwitch-inner"></span>
									<span class="sgpb-onOffSwitch-switch"></span>
								</label>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="row sgpb-margin-y-20 ">
					<div class="col-md-6 formItem">
						<h3 class="formItem__title"><?php _e('Most Popular Popups', SG_POPUP_TEXT_DOMAIN);?></h3>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div id="sgpb-popupular-popups" class="sgpb-border-radius-5px sgpb-shadow-black-10 sgpb-padding-10 sgpb-display-none"></div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
