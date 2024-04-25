<?php

namespace sgpbgeotargeting;

use sgpb\PopupLoader;
use sgpb\SGPopup;
use \WP_Query;

class Ajax
{
	public function __construct()
	{
		$this->actions();
	}

	public function actions()
	{
		add_action('wp_ajax_sgpb_geo_targeting_ajax', array($this, 'checkGeoAjaxMode'));
		add_action('wp_ajax_nopriv_sgpb_geo_targeting_ajax', array($this, 'checkGeoAjaxMode'));
	}

	public function checkGeoAjaxMode()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$foundPopup = array();
		$pageID = 0;
		if (isset($_POST['page_id'])){
			$pageID = intval(base64_decode($_POST['page_id']));
		}
		if (!is_numeric($pageID)){
			echo  json_encode([], JSON_UNESCAPED_SLASHES);
			wp_die();
		}
		$popupBuilderPosts = new WP_Query(
			array(
				'post_type'      => SG_POPUP_POST_TYPE,
				'posts_per_page' => -1,
				'post_status'    => 'publish'
			)
		);
		$popupLoaderObj = PopupLoader::instance();

		while($popupBuilderPosts->have_posts()) {
			$popupBuilderPosts->next_post();
			$popupPost = $popupBuilderPosts->post;
			$popup = SGPopup::find($popupPost);
			if(empty($popup) || !is_object($popup)) {
				continue;
			}
			if($popup->getOptionValue('sgpb-enable-geo-ajax-mode') && !empty($popup->getOptionValue('sgpb-is-active'))) {
				$popup->setCurrentPageIdForAjax($pageID);
				if($popup->allowToLoadAJAX()) {
					$foundPopup[] = $popupLoaderObj->loadPopupAjax($popup);
				}
			}
		}
		$fin = [];
		if(!empty($foundPopup)) {
			foreach($foundPopup as $popup) {
				$fin[] = array (
					'scripts' => $this->getScriptsUrl($popup['scriptsAndStyles']['scripts']),
					'styles' => $this->getStylesUrl($popup['scriptsAndStyles']['styles']),
					'footerContent' => $popup['footerContent']
				);
			}
		}
		echo  json_encode($fin, JSON_UNESCAPED_SLASHES);
		wp_die();
	}

	private function getScriptsUrl($scripts)
	{
		$dataForScript = array(
			'mainScripts' => [],
			'scriptsData' => [],
		);
		foreach($scripts as $k => $script) {
			if(empty($script['jsFiles'])) {
				continue;
			}
			foreach($script['jsFiles'] as $jsFile) {
				if(empty($jsFile['folderUrl'])) {
					continue;
				}
				$dirUrl = $jsFile['folderUrl'];
				$ver = (!empty($jsFile['ver'])) ? $jsFile['ver'] : '';
				$dep = (!empty($jsFile['dep'])) ? $jsFile['dep'] : '';
				$dataForScript['mainScripts'][] = array(
					'fileUrl' => $dirUrl.$jsFile['filename'],
					'version' => $ver,
					'$dep' => $dep,
					'name' => $jsFile['filename'],
				);
			}

			if(empty($script['localizeData'])) {
				continue;
			}

			$localizeData = $script['localizeData'];

			if(!empty($localizeData[0])) {
				foreach($localizeData as $valueData) {
					if(empty($valueData)) {
						continue;
					}

					$dataForScript['scriptsData'][] = array(
						'handle' => $valueData['handle'],
						'name' => $valueData['name'],
						'data' => $valueData['data']
					);
				}
			}
		}
		return $dataForScript;
	}

	private function getStylesUrl($styles)
	{
		$styleUrls = [];
		foreach ($styles as $style) {

			if (empty($style['cssFiles'])) {
				continue;
			}

			foreach ($style['cssFiles'] as $cssFile) {

				if (empty($cssFile['folderUrl'])) {
					continue;
				}
				$dirUrl = $cssFile['folderUrl'];
				$dep = (!empty($cssFile['dep'])) ? $cssFile['dep'] : '';
				$ver = (!empty($cssFile['ver'])) ? $cssFile['ver'] : '';
				$styleUrls[] = array(
					'fileUrl' => $dirUrl.$cssFile['filename'],
					'version' => $ver,
					'$dep' => $dep,
					'name' => $cssFile['filename'],
				);
			}
		}
		return $styleUrls;
	}
}
