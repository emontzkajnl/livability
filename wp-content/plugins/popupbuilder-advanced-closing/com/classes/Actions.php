<?php
namespace sgpbadvancedclosing;
use sgpb\AdminHelper as MainAdminHelper;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	public function  init()
	{
		add_action('autoCloseOptions', array($this, 'autoCloseOptions'), 10, 1);
	}

	public function autoCloseOptions($popupTypeObj)
	{
		$defaultData = ConfigDataHelper::defaultData();
		$defaultCountdownPositions = $defaultData['countdownPositions'];
		$countdownPosition = $popupTypeObj->getOptionValue('sgpb-closing-countdown-position');
		?>

		<div class="sg-full-width sgpb-bg-black__opacity-02 sgpb-padding-x-20 formItem">
			<div class="subFormItem formItem">
				<label class="subFormItem__title sgpb-margin-right-10">
					<?php _e('Auto close after', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<input type="number" min="0" name="sgpb-auto-close-time" value="<?php echo $popupTypeObj->getOptionValue('sgpb-auto-close-time'); ?>">
				<span class="sgpb-margin-left-10 sgpb-restriction-unit">
					<?php _e('Second(s)', SG_POPUP_TEXT_DOMAIN) ?>
				</span>
			</div>
			<div class="subFormItem formItem">
				<label class="subFormItem__title" for="sgpb-add-closing-countdown">
					<?php _e('Show countdown', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-2">
					<input class="js-checkbox-accordion" type="checkbox" id="sgpb-add-closing-countdown" class="" name="sgpb-add-closing-countdown" <?php echo $popupTypeObj->getOptionValue('sgpb-add-closing-countdown'); ?>>
				</div>
			</div>
			<div class="sgpb-width-100">
				<div class="row form-group">
					<label for="sgpb-closing-countdown-position" class="col-md-3 control-label sgpb-double-sub-option">
						<?php _e('Countdown position', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<?php echo MainAdminHelper::createSelectBox($defaultCountdownPositions, $countdownPosition, array('name' => 'sgpb-closing-countdown-position', 'class'=>'js-sg-select2 sgpb-closing-countdown-position')); ?>
					</div>
				</div>
				<div class="row form-group">
					<label for="sgpb-closing-countdown-position-top" class="col-md-3 control-label sgpb-double-sub-option">
						<?php _e('top/bottom', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-5">
						<input id="sgpb-closing-countdown-position-top" class="sgpb-full-width sgpb-full-width-events" step="1" type="number" name="sgpb-closing-countdown-position-top" value="<?php echo $popupTypeObj->getOptionValue('sgpb-closing-countdown-position-top'); ?>">
						<span class="sgpb-margin-left-10 sgpb-restriction-unit">px</span>
					</div>
				</div>
				<div class="row form-group">
					<label for="sgpb-closing-countdown-position-right" class="col-md-3 control-label sgpb-double-sub-option">
						<?php _e('right/left', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-5">
						<input id="sgpb-closing-countdown-position-right" class="sgpb-full-width sgpb-full-width-events" step="1" type="number" name="sgpb-closing-countdown-position-right" value="<?php echo $popupTypeObj->getOptionValue('sgpb-closing-countdown-position-right'); ?>">
						<span class="sgpb-margin-left-10 sgpb-restriction-unit">px</span>
					</div>
				</div>
				<div class="row form-group subFormItem">
					<label for="sgpb-closing-countdown-digits-color" class="col-md-3 control-label sgpb-double-sub-option">
						<?php _e('Digits color', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<div class="sgpb-color-picker-wrapper sgpb-closing-countdown-digits-color">
							<input class="sgpb-color-picker sgpb-closing-countdown-digits-color" type="text" name="sgpb-closing-countdown-digits-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-closing-countdown-digits-color')); ?>" >
						</div>
					</div>
				</div>
				<div class="row form-group subFormItem">
					<label for="sgpb-closing-countdown-bg-color" class="col-md-3 control-label sgpb-double-sub-option">
						<?php _e('Background color', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<div class="sgpb-color-picker-wrapper sgpb-closing-countdown-bg-color">
							<input class="sgpb-color-picker sgpb-closing-countdown-bg-color" type="text" name="sgpb-closing-countdown-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-closing-countdown-bg-color')); ?>" >
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php }
}
