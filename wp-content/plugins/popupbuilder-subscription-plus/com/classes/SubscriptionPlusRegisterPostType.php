<?php
namespace sgpbsubscriptionplus;

class SubscriptionPlusRegisterPostType
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$postType = SG_POPUP_TEMPLATE_POST_TYPE;
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
			'supports'            => apply_filters('sgpbPostTypeSupport', array('title', 'editor')),
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
			'name'               => _x('Email Templates', 'post type general name', SG_POPUP_TEXT_DOMAIN),
			'singular_name'      => _x('Email Template', 'post type singular name', SG_POPUP_TEXT_DOMAIN),
			'menu_name'          => _x('Email Templates', 'admin menu', SG_POPUP_TEXT_DOMAIN),
			'name_admin_bar'     => _x('Email Template', 'add new on admin bar', SG_POPUP_TEXT_DOMAIN),
			'add_new'            => _x('Add New Email Template', SG_POPUP_TEXT_DOMAIN),
			'add_new_item'       => __('Add New Email Template', SG_POPUP_TEXT_DOMAIN),
			'new_item'           => __('New Template', SG_POPUP_TEXT_DOMAIN),
			'edit_item'          => __('Edit Template', SG_POPUP_TEXT_DOMAIN),
			'view_item'          => __('View Template', SG_POPUP_TEXT_DOMAIN),
			'all_items'          => __('All Email Templates', SG_POPUP_TEXT_DOMAIN),
			'search_items'       => __('Search Email Templates', SG_POPUP_TEXT_DOMAIN),
			'parent_item_colon'  => __('Parent Email Templates:', SG_POPUP_TEXT_DOMAIN),
			'not_found'          => __('No email templates found.', SG_POPUP_TEXT_DOMAIN),
			'not_found_in_trash' => __('No email templates found in Trash.', SG_POPUP_TEXT_DOMAIN)
		);

		return $labels;
	}

	public function addSubMenu()
	{
		add_submenu_page(
			'edit.php?post_type='.SG_POPUP_POST_TYPE,
			__('Add New', SG_POPUP_TEXT_DOMAIN),
			__('Add New Template', SG_POPUP_TEXT_DOMAIN),
			'sgpb_manage_options',
			SG_POPUP_TEMPLATE_POST_TYPE,
			array($this, 'templateTypesPage')
		);
		add_submenu_page(
			'edit.php?post_type='.SG_POPUP_POST_TYPE,
			__('Email Integrations', SG_POPUP_TEXT_DOMAIN),
			__('Email Integrations', SG_POPUP_TEXT_DOMAIN),
			'sgpb_manage_options',
			SG_POPUP_EMAIL_INTEGRATIONS_SCREEN,
			array($this, 'integrationsPage')
		);
	}

	public function addTemplatesShortcodeMetaboxes()
	{
		add_meta_box(
			'templateShortcodesView',
			__('Shortcodes', SG_POPUP_TEXT_DOMAIN),
			array($this, 'templateShortcodesView'),
			SG_POPUP_TEMPLATE_POST_TYPE,
			'side',
			'low'
		);
	}

	public function templateShortcodesView()
	{
		if (file_exists(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'shortcodes.php')) {
			require_once(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'shortcodes.php');
		}
	}

	public function templateTypesPage()
	{
		if (file_exists(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'templateTypes.php')) {
			require_once(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'templateTypes.php');
		}
	}

	public function integrationsPage()
	{
		if (file_exists(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'emailintegrations.php')) {
			require_once(SGPB_SUBSCRIPTION_PLUS_VIEWS_PATH.'emailintegrations.php');
		}
	}
}

