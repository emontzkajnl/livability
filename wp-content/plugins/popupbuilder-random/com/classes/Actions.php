<?php

namespace sgpbrandom;

class Actions
{
	public function __construct()
	{
		add_action('init', array($this, 'insertTaxonomy'), 999999);
		add_filter(SG_POPUP_CATEGORY_TAXONOMY.'_row_actions', array($this, 'taxonomyRowActions'), 2, 2);
		new RandomAjax();
	}

	public function taxonomyRowActions($actions, $row)
	{
		if($row->slug == SG_RANDOM_TAXONOMY_SLUG){
			return array();
		}

		return $actions;
	}

	public function insertTaxonomy()
	{
		wp_insert_term(
			__('Random popups', SG_POPUP_TEXT_DOMAIN),
			SG_POPUP_CATEGORY_TAXONOMY, // the taxonomy
			array(
				'description'       => __('Random popups', SG_POPUP_TEXT_DOMAIN),
				'slug'              => SG_RANDOM_TAXONOMY_SLUG,
				'parent'            => '',
				'can_disable_terms' => false,
			)
		);
		/* we need register taxonomy for to not handle custom queries to get terms and categories for popup posts */
		register_taxonomy(
			SG_POPUP_CATEGORY_TAXONOMY,
			SG_POPUP_POST_TYPE,
			array(
				'hierarchical'          => false,
				'query_var'             => 'random_popup',
				'rewrite'               => false,
				'public'                => true,
				'show_ui'               => false,
				'show_admin_column'     => false,
				'_builtin'              => true,
				'capabilities'          => array(
					'manage_terms' => 'manage_categories',
					'edit_terms'   => 'edit_categories',
					'delete_terms' => 'delete_categories',
					'assign_terms' => 'assign_categories',
				),
				'show_in_rest'          => true,
				'rest_base'             => SG_POPUP_CATEGORY_TAXONOMY,
				'rest_controller_class' => 'WP_REST_Terms_Controller',
			)
		);
		register_taxonomy_for_object_type(SG_POPUP_CATEGORY_TAXONOMY, SG_POPUP_POST_TYPE);
	}
}
