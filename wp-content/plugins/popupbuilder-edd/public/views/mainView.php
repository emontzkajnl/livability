<?php
namespace sgpb;
use \sgpbedd\AdminHelper as eddAdminHelper;

if (!eddAdminHelper::isEddExists()) {
	_e('Easy Digital Downloads plugin is not installed on your side. Please, <a href="'.SGPB_MAIN_EDD_PLUGIN_URL.'" target="__blank">follow the link</a> to install it.', SG_POPUP_TEXT_DOMAIN);
	return;
}

if (!eddAdminHelper::isEddActive()) {
	_e('Easy Digital Downloads plugin is not active on your site. Please, activate it from your Plugins section.', SG_POPUP_TEXT_DOMAIN);
	return;
}
$data = $popupTypeObj->getOptionValue('sgpb-edd-special-events');
$builder = \sgpbedd\ConditionBuilder::createEddConditionBuilder($data);
?>

<div class="popup-conditions-wrapper popup-special-conditions-wrapper edd-special-events-wrapper" data-condition-type="edd-special-events">
	<?php
	$creator = new ConditionCreator($builder);
	echo $creator->render();
	?>
</div>
