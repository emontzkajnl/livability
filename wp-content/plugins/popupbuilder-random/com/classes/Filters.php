<?php

namespace sgpbrandom;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbLoadablePopups', array($this, 'loadblePopup'), 1, 1);
		add_filter('sgpbAddRandomColumnIntoAllPopupsViewTable', array(
			$this,
			'addRandomColumnIntoAllPopupsViewTable'
		), 10, 1);
		add_filter('sgpbAddRandomTableColumnValues', array($this, 'addRandomTableColumnValues'), 10, 1);
		add_filter('sgpbRandomAdminJsFiles', array($this, 'adminJsFiles'), 10, 1);
		add_filter('sgpbRandomAdminJsLocalizedData', array($this, 'adminJsLocalizedData'), 10, 1);
	}

	public function loadblePopup($popups)
	{
		$randomFilter = new RandomFilter();
		$randomFilter->setPopups($popups);
		$popups = $randomFilter->filter();

		return $popups;
	}

	public function addRandomColumnIntoAllPopupsViewTable($filterColumnsSettings = array())
	{
		if(empty($filterColumnsSettings)) {
			return $filterColumnsSettings;
		}
		$filterColumnsSettings['sgpbIsRandomEnabled'] = __('Random', SG_POPUP_TEXT_DOMAIN);
		return $filterColumnsSettings;
	}

	public function addRandomTableColumnValues($postId)
	{
		$checked = '';
		if(has_term(SG_RANDOM_TAXONOMY_SLUG, SG_POPUP_CATEGORY_TAXONOMY, $postId)) {
			$checked = 'checked';
		}
		return '<label class="sgpb-switch">
                    <input class="sg-switch-checkbox sgpb-popup-random-js" value="1" data-switch-id="'.$postId.'" type="checkbox" '.$checked.'>
                    <div class="sgpb-slider sgpb-round"></div>
                </label>';
	}

	public function adminJsFiles($jsFiles)
	{
		$jsFiles[] = array('folderUrl' => SGPB_RANDOM_JS_URL, 'filename' => 'adminRandomPopup.js');
		return $jsFiles;
	}

	public function adminJsLocalizedData($localizeData)
	{
		$localizeData[] = array(
			'handle' => 'adminRandomPopup.js',
			'name'   => 'SGPB_RANDOM_POPUP',
			'data'   => array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => wp_create_nonce(SG_AJAX_NONCE)
			)
		);
		return $localizeData;
	}
}
