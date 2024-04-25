<?php
namespace sgpbcontactform;
use sgpb\AdminHelper as MainAdminHelper;

class Actions
{
	public function __construct()
	{
		add_action('admin_menu', array($this, 'addSubMenu'));
        add_action('admin_init', array($this, 'adminInit'));
        add_action('admin_post_cf_csv_file', array($this, 'getContactedUsersCsvFile'));
	}

    public function adminInit()
    {
        if (!get_option('sgpb-contacted-table-create')) {
            Installer::install();
            update_option('sgpb-contacted-table-create', 1);
        }
    }

	public function addSubMenu()
	{
		add_submenu_page(
			'edit.php?post_type='.SG_POPUP_POST_TYPE,
			__('Contacted Users', SG_POPUP_TEXT_DOMAIN),
			__('Contacted Users', SG_POPUP_TEXT_DOMAIN),
			'sgpb_manage_options',
			SGPB_CONTACT_CONTACTED_PAGE,
			array($this, 'contactedUsers')
		);
	}

	public function contactedUsers()
	{
		require_once(SGPB_CONTACT_FORM_VIEWS_PATH.'contacted.php');
	}

	public function getContactedUsersCsvFile()
	{
		global $wpdb;
		$allowToAction = MainAdminHelper::userCanAccessTo();
		if (!$allowToAction) {
			return false;
		}
		$query = AdminHelper::contactersRelatedQuery();
		$fields = array('id', 'email', 'cDate');
		if (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
			$orderBy = sanitize_text_field($_GET['orderby']);
			if (!in_array($orderBy, $fields)){
				wp_redirect(get_home_url());
				exit();
			}
			if (isset($_GET['order']) && !empty($_GET['order'])) {
				$order = array('ASC', 'DESC');
				if (!in_array(sanitize_text_field($_GET['order']), $order)){
					wp_redirect(get_home_url());
					exit();
				}
				$query .= ' ORDER BY '.esc_sql($_GET['orderby']).' '.esc_sql($_GET['order']);
			}
		}
		$content = '';
		$rows = array('email', 'date', 'popup');
		foreach ($rows as $value) {
			$content .= $value;
			if ($value != 'popup') {
				$content .= ',';
			}
		}
		$content .= "\n";
		$subscribers = $wpdb->get_results($query, ARRAY_A);

		$subscribers = apply_filters('sgpbContactersCsv', $subscribers);

		foreach($subscribers as $values) {
			foreach ($values as $key => $value) {
				$content .= $value;
				if ($key != 'contactFormTitle') {
					$content .= ',';
				}
			}
			$content .= "\n";
		}

		$content = apply_filters('sgpbContactersContent', $content);

		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=contactedUsersList.csv;');
		header('Content-Transfer-Encoding: binary');
		echo $content;
	}
}
