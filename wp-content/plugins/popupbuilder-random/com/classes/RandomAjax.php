<?php

namespace sgpbrandom;

use sgpb\AdminHelper;

class RandomAjax
{
	public function __construct()
	{
		if (!class_exists('sgpb\AdminHelper')) {
			return false;
		}
		$allowToAction = AdminHelper::userCanAccessTo();
		if ($allowToAction) {
			$this->actions();
		}
	}
	public function actions()
	{
		add_action('wp_ajax_sgpb_set_popup_random', array($this, 'setRandomPopup'));
	}

	public function setRandomPopup()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$postId = (int)$_POST['id'];
		if (!has_term( SG_RANDOM_TAXONOMY_SLUG, SG_POPUP_CATEGORY_TAXONOMY, $postId )){
			wp_set_post_terms($postId, SG_RANDOM_TAXONOMY_SLUG, SG_POPUP_CATEGORY_TAXONOMY);
		} else {
			wp_remove_object_terms( $postId, SG_RANDOM_TAXONOMY_SLUG, SG_POPUP_CATEGORY_TAXONOMY);
		}
		$getTermId = term_exists(SG_RANDOM_TAXONOMY_SLUG, SG_POPUP_CATEGORY_TAXONOMY);
		wp_update_term_count_now([$getTermId['term_id']], SG_POPUP_CATEGORY_TAXONOMY);
		wp_die();
	}

}
