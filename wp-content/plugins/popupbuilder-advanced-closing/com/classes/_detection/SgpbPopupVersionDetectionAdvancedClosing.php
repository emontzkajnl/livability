<?php

namespace sgpbadvancedclosing;

use sgpb\AdminHelper;

class SgpbPopupVersionDetectionAdvancedClosing
{
	public function __construct()
	{
		$this->compareVersions();
	}

	public function compareVersions()
	{
		if(!$this->checkIfIsOnPopupPage()) {
			return;
		}
		$pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$this->popupBuilderFreeStableVersion()['pluginKey']);
		if(version_compare($this->popupBuilderFreeStableVersion()['stable_version'], $pluginData['Version'], '>')) {
			$this->sgpbDetectJs();
		}
	}

	public function sgpbDetectJs()
	{
		$jsFile = array('fileUrl' => SGPB_ADVANCED_CLOSING_JS_URL.'/sgpbDetect.js', 'filename' => 'sgpbDetect.js');
		$data   = array(
			'message' => "<p>As you use the newly updated versions of our extensions </p><p> please update the <a href='".admin_url('plugins.php')."'><b>Popup Builder Plugin</b></a> in order to use </p><p> the plugin properly and not to have any issues in the future.</p>",
			'header' => "<h2 style='font-size: 30px'>We had a major version update</h2>",
			'logo'    => SGPB_ADVANCED_CLOSING_PUBLIC_URL.'/img/sgpbLogo.png',
			'url'     => admin_url('plugins.php')
		);
		wp_register_script($jsFile['filename'], $jsFile['fileUrl'], '', SG_VERSION_POPUP_ADVANCED_CLOSING, true);
		wp_localize_script($jsFile['filename'], 'SGPB_JS_DETECTION_EXTENSION', $data);
		wp_enqueue_script($jsFile['filename']);
	}

	public static function popupBuilderFreeStableVersion()
	{
		return array(
			'label'          => __('Popup Builder', SG_POPUP_TEXT_DOMAIN),
			'version'        => '4.0',
			'stable_version' => '4.0',
			'pluginKey'      => 'popup-builder/popup-builder.php',
			'key'            => 'sgpbPopupBuilder',
			'url'            => SGPB_ADVANCED_CLOSING_STORE_URL
		);
	}

	public function checkIfIsOnPopupPage()
	{
		if(!is_admin()) {
			return false;
		}
		if (!class_exists('sgpb\AdminHelper')) {
			return false;
		}
		if (AdminHelper::getCurrentPostType() === SG_POPUP_POST_TYPE){
			return true;
		}
		if (!isset($_GET['post_type'])) {
			return false;
		}
		switch($_GET['post_type']) {
			case SG_POPUP_POST_TYPE:
			case 'sgpbtemplate':
			case 'sgpbautoresponder':
				return true;
			default:
				return false;
		}
	}

}
