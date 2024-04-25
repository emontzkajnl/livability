<?php
namespace sgpbsubscriptionplus;

class Installer
{
	public static function install()
	{
		$mustNotDoAlter = get_option('sgpbDontAlterSubmittedDataColumnSet');
		$mustNotDoAlterForEmailStatus = get_option('sgpbDontAlterSubmittedEmailStatusSet');

		if (!$mustNotDoAlter) {
			self::addSubmissionDataColumn();
			update_option('sgpbDontAlterSubmittedDataColumnSet', 1);
		}
		if (!$mustNotDoAlterForEmailStatus) {
			self::addEmailStatusColumn();
			update_option('sgpbDontAlterSubmittedEmailStatusSet', 1);
		}
	}

	public static function uninstall()
	{
		delete_option('sgpb-new-subscriber');
		delete_option('sgpbDontAlterSubmittedDataColumn');
		delete_option('sgpbDontAlterSubmittedDataColumnSet');
		delete_option('sgpbDontAlterSubmittedEmailStatus');
		delete_option('sgpbDontAlterSubmittedEmailStatusSet');
		delete_option(SGPB_DEFAULT_EMAIL_TEMPLATES_HEADER);
		delete_option(SGPB_DEFAULT_EMAIL_TEMPLATES_FOOTER);
	}

	public static function addSubmissionDataColumn()
	{
		global $wpdb;

		$sql = 'ALTER TABLE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' ADD COLUMN submittedData TEXT NOT NULL ';
		$wpdb->query($sql);
	}

	public static function addEmailStatusColumn()
	{
		global $wpdb;

		$sql = 'ALTER TABLE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' ADD COLUMN emailStatus INT(11) DEFAULT 1 AFTER email';
		$wpdb->query($sql);
	}
}
