<?php
require_once(SG_POPUP_HELPERS_PATH.'AdminHelper.php');

use sgpb\AdminHelper;
use sgpbDataTable\SGPBTable;
use sgpbcontactform\AdminHelper as ContactAdminHelper;

class SGPBContactedSubscribers extends SGPBTable
{
    public function __construct()
    {
        global $wpdb;
        parent::__construct('sgpbContactedSubscribers');

        $this->setRowsPerPage(SGPB_APP_POPUP_TABLE_LIMIT);
        $this->setTablename($wpdb->prefix.SGPB_CONTACTED_SUBSCRIBERS_TABLE_NAME);

        $columns = array(
            $this->tablename.'.id',
            'email',
            'cDate',
            'popupId',
            'submittedData'
        );

        $displayColumns = array(
            'bulk'=>'<input class="subs-bulk" type="checkbox" autocomplete="off">',
            'id' => __('ID', SG_POPUP_TEXT_DOMAIN),
            'email' => __('Email', SG_POPUP_TEXT_DOMAIN),
            'cDate' => __('Date', SG_POPUP_TEXT_DOMAIN),
            'popupId' => __('Popup', SG_POPUP_TEXT_DOMAIN),
            'submittedData' => __('Additional Data', SG_POPUP_TEXT_DOMAIN),
        );

        $filterColumnsDisplaySettings = array(
            'columns' => $columns,
            'displayColumns' => $displayColumns
        );

        $this->setColumns(@$filterColumnsDisplaySettings['columns']);
        $this->setDisplayColumns(@$filterColumnsDisplaySettings['displayColumns']);
        $this->setSortableColumns(array(
            'id' => array('id', false),
            'email' => array('email', true),
            'cDate' => array('cDate', true),
            $this->setInitialSort(array(
                'id' => 'DESC'
            ))
        ));
    }

    public function customizeRow(&$row)
    {
        $popupId = (int)$row[3];

        $row[3] = get_the_title($popupId);
        $row[4] = $this->getMoreButton($row);
        $row[5] = $row[4];
        $row[4] = $row[3];
        $row[3] = $row[2];
        $row[2] = $row[1];
        $row[1] = $row[0];

        // show date more user friendly
        $row[3] = date('d F Y', strtotime($row[3]));

        $id = $row[0];
        $row[0] = '<input type="checkbox" style="margin-left: 8px;" class="subs-delete-checkbox" data-delete-id="'.esc_attr($id).'">';
    }

    public function getMoreButton($row)
    {
        $popupId = (int)$row[3];
        return '<input type="button" data-attr-subscriber-data="'.esc_attr($row[4]).'" data-subscriber-id="'.esc_attr($popupId).'" class="sgpb-btn sgpb-btn-dark-outline sgpb-show-subscribers-additional-data-js" value="'.__('More details', SG_POPUP_TEXT_DOMAIN).'">';
    }
    public function customizeQuery(&$query)
    {
        ContactAdminHelper::filterQuery($query);
    }

    public function getNavPopupsConditions()
    {
        $popups = ContactAdminHelper::getAllContactForms();
        $defaultList = array('all' => __('All', SG_POPUP_TEXT_DOMAIN));

        $allList = $defaultList+$popups;
        $content = AdminHelper::createSelectBox($allList, @(int)$_GET['sgpb-contact-popup-id'], array('name' => 'sgpb-contact-popup', 'class' => 'sgpb-contact-popup sgpb-margin-right-10'));

        $content .= '<input type="hidden" name="page" value="'.SGPB_CONTACT_CONTACTED_PAGE.'">';
        // it's for the tabs
        $content .= '<input type="hidden" name="sgpbPageKeyTab" value="subscribers">';
        $content .= '<input type="hidden" class="sgpb-contact-popup-id" name="sgpb-contact-popup-id" value="'.esc_attr(@$_GET['sgpb-contact-popup-id']).'">';

        return $content;
    }

    public function getNavDateConditions() {
        $dates = ContactAdminHelper::getAllContactedDate();
        $defaultList = array('all' => __('All', SG_POPUP_TEXT_DOMAIN));
        $allDates = $defaultList+$dates;
        $selectedDate = '';

        if (isset($_GET['sgpb-contact-date'])) {
            $selectedDate = esc_sql($_GET['sgpb-contact-date']);
        }
        ob_start();
        ?>
        <input type="hidden" class="sgpb-contact-date" name="sgpb-contact-date" value="<?php echo $selectedDate;?>">
        <?php echo $content = AdminHelper::createSelectBox($allDates,$selectedDate, array('class' => 'sgpb-contact-date-list sgpb-margin-right-10')); ?>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
	public function extra_tablenav($which)
	{
		$isVisibleExtraNav = $this->getIsVisibleExtraNav();

		if (!$isVisibleExtraNav) {
			return '';
		}
		?>
		<div class="sgpb-display-flex sgpb-justify-content-between actions">
			<div>
				<label class="screen-reader-text" for="sgpb-subscription-popup"><?php _e('Filter by popup', SG_POPUP_TEXT_DOMAIN)?></label>
				<?php echo $this->getNavPopupsConditions(); ?>
				<label class="screen-reader-text" for="sgpb-subscribers-dates"><?php _e('Filter by date', SG_POPUP_TEXT_DOMAIN)?></label>
				<?php  echo $this->getNavDateConditions(); ?>
				<input name="filter_action" id="post-query-submit" class="buttonGroup__button buttonGroup__button_blueBg buttonGroup__button_unrounded" value="<?php _e('Filter', SG_POPUP_TEXT_DOMAIN)?>" type="submit">
			</div>
			<div>
				<?php
				if ($which == 'top') {
					?>
					<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" style="border-radius: 39px;" class="sgpb-contact-remove-spinner js-sg-spinner sg-hide-element js-sg-import-gif" width="20px">
					<buutton type="button"
					         class="sgpb-contact-delete-button sgpb-btn sgpb-btn-danger sgpb-btn--rounded sgpb-btn-disabled"
					         data-ajaxNonce="<?php echo SG_AJAX_NONCE;?>">
						<?php _e('Delete user(s)', SG_POPUP_TEXT_DOMAIN)?>
					</buutton>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
}
