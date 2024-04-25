<?php
namespace sgpbrs;
use sgpbrs\AdminHelper as AdminHelperRecentSales;
use sgpbrs\AjaxRecentSales as AjaxRecentSales;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		global $post;
		global $post_type;
		$allowToPublish = AdminHelperRecentSales::allowToPublishPopup();

		if (!$allowToPublish) {
			add_action('admin_head', array($this, 'hidePublishButtonUntil'));
		}

		new AjaxRecentSales();
	}

	public function hidePublishButtonUntil()
	{
		$isWooActive = AdminHelperRecentSales::isWoocommerceActive();
		$isEddActive = AdminHelperRecentSales::isEddActive();
		if (!$isWooActive && !$isEddActive) {
			echo '<style>
				#post-preview, #save-post, #publishing-action {
					display:none !important;
				}
			</style>';
		}
	}
}
