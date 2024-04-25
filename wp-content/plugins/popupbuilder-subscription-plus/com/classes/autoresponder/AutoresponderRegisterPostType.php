<?php
namespace sgpbsubscriptionplus;

class AutoresponderRegisterPostType
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$postType = SG_POPUP_AUTORESPONDER_POST_TYPE;
		$args = $this->getPostTypeArgs();

		register_post_type($postType, $args);
	}

	public function getPostTypeArgs()
	{
		$labels = $this->getPostTypeLabels();

		$args = array(
			'labels'              => $labels,
			'description'         => __('Description.', 'your-plugin-textdomain'),
			// Exclude_from_search
			'exclude_from_search' => true,
			'public'              => false,
			'has_archive'         => false,
			// Where to show the post type in the admin menu
			'show_ui'             => true,
			'supports'            => apply_filters('sgpbPostTypeSupport', array('title')),
			'show_in_menu'        => 'edit.php?post_type='.SG_POPUP_POST_TYPE,
			'map_meta_cap'        => true,
			'query_var'           => false,
			'capability_type'     => array('sgpb_popup', 'sgpb_popups')
		);

		if (is_admin()) {
			$args['capability_type'] = 'post';
		}

		return $args;
	}

	public function getPostTypeLabels()
	{
		$labels = array(
			'name'               => _x('Autoresponders', 'post type general name', SG_POPUP_TEXT_DOMAIN),
			'singular_name'      => _x('Autoresponder', 'post type singular name', SG_POPUP_TEXT_DOMAIN),
			'menu_name'          => _x('Autoresponders', 'admin menu', SG_POPUP_TEXT_DOMAIN),
			'name_admin_bar'     => _x('Autoresponder', 'add new on admin bar', SG_POPUP_TEXT_DOMAIN),
			'add_new'            => _x('Add New Autoresponder', SG_POPUP_TEXT_DOMAIN),
			'add_new_item'       => __('Add New Autoresponder', SG_POPUP_TEXT_DOMAIN),
			'new_item'           => __('New Autoresponder', SG_POPUP_TEXT_DOMAIN),
			'edit_item'          => __('Edit Autoresponder', SG_POPUP_TEXT_DOMAIN),
			'view_item'          => __('View Autoresponder', SG_POPUP_TEXT_DOMAIN),
			'all_items'          => __('Autoresponder', SG_POPUP_TEXT_DOMAIN),
			'search_items'       => __('Search Autoresponders', SG_POPUP_TEXT_DOMAIN),
			'parent_item_colon'  => __('Parent Autoresponders:', SG_POPUP_TEXT_DOMAIN),
			'not_found'          => __('No autoresponders found.', SG_POPUP_TEXT_DOMAIN),
			'not_found_in_trash' => __('No autoresponders found in Trash.', SG_POPUP_TEXT_DOMAIN)
		);

		return $labels;
	}

	public function addPopupMetaboxes()
	{
		add_meta_box(
			'autoresponderMetabox',
			__('Autoresponder options', SG_POPUP_TEXT_DOMAIN),
			array($this, 'mainOptionMetabox'),
			SG_POPUP_AUTORESPONDER_POST_TYPE,
			'normal',
			'high'
		);
	}

	public function mainOptionMetabox()
	{
		$autoresponder = Autoresponder::getData();
		if (file_exists(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'autoresponder/mainOptions.php')) {
			require_once(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'autoresponder/mainOptions.php');
		}
	}
}
