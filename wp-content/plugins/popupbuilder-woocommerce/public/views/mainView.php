<?php
namespace sgpb;
use \sgpbwoo\AdminHelper as wooAdminHelper;

if (!wooAdminHelper::isWoocommerceExists()) {
	_e('WooCommerce plugin is not installed on your side. Please, <a href="'.SGPB_WOO_PLUGIN_URL.'" target="__blank">follow the link</a> to install it.', SG_POPUP_TEXT_DOMAIN);
	return;
}

if (!wooAdminHelper::isWoocommerceActive()) {
	_e('WooCommerce plugin is not active on your site. Please, activate it from your Plugins section.', SG_POPUP_TEXT_DOMAIN);
	return;
}
$data = $popupTypeObj->getOptionValue('sgpb-woocommerce-special-events');
$builder = \sgpbwoo\ConditionBuilder::createWooCommerceConditionBuilder($data);
?>

<div class="popup-conditions-wrapper popup-special-conditions-wrapper woocommerce-special-events-wrapper" data-condition-type="woocommerce-special-events">
	<?php
	$creator = new ConditionCreator($builder);
	echo $creator->render();
	?>
</div>
