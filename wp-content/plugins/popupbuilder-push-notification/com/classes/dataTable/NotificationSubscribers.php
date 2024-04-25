<?php
require_once(SG_POPUP_HELPERS_PATH.'AdminHelper.php');

use sgpb\AdminHelper;
use sgpbDataTable\SGPBTable;
use sgpbpush\AdminHelper as PushNotAdminHelper;

class NotificationSubscribers extends SGPBTable
{
    public function __construct()
    {
        global $wpdb;
        parent::__construct('ngpbNotificationSubscribers');

        $this->setRowsPerPage(SGPB_APP_POPUP_TABLE_LIMIT);
        $this->setTablename($wpdb->prefix.SGPB_PUSH_NOTIFICATION_TABLE_NAME);

        $columns = array(
            $this->tablename.'.id',
            'region',
            'browser',
            'cDate',
            'popupId'
        );

        $displayColumns = array(
            'bulk'=>'<input class="subs-bulk" type="checkbox" autocomplete="off">',
            'id' => 'ID',
            'region' => __('Region', SG_POPUP_TEXT_DOMAIN),
            'browser' => __('Browser', SG_POPUP_TEXT_DOMAIN),
            'cDate' => __('Date', SG_POPUP_TEXT_DOMAIN),
            'popupId' => __('Popup Title', SG_POPUP_TEXT_DOMAIN),
        );

        $filterColumnsDisplaySettings = array(
            'columns' => $columns,
            'displayColumns' => $displayColumns
        );

        $this->setColumns(@$filterColumnsDisplaySettings['columns']);
        $this->setDisplayColumns(@$filterColumnsDisplaySettings['displayColumns']);
        $this->setSortableColumns(array(
            'id' => array('id', false),
            'region' => array('region', true),
            'browser' => array('browser', true),
            'date' => array('Date', true),
            $this->setInitialSort(array(
                'id' => 'DESC'
            ))
        ));
    }

    public function customizeRow(&$row)
    {
        $popupId = (int)$row[4];
        $row[5] = get_the_title($popupId);
        $row[4] = $row[3];
        $row[3] = $row[2];
        $row[2] = $row[1];
        $row[1] = $row[0];

        $id = $row[0];
        $row[0] = '<input type="checkbox" class="sgpb-notification-delete-checkbox" data-delete-id="'.esc_attr($id).'">';
    }

    public function customizeQuery(&$query)
    {
        PushNotAdminHelper::filterQuery($query);
    }

    public function getNavPopupsConditions()
    {
        $popups = PushNotAdminHelper::getAllNotificationPopups();
        $defaultList = array('all' => __('All Popups', SG_POPUP_TEXT_DOMAIN));

        $allList = $defaultList+$popups;
        $content = AdminHelper::createSelectBox($allList, @(int)$_GET['sgpb-notification-popup-id'], array('name' => 'sgpb-notification-popup', 'id' => 'sgpb-notification-popup', 'class' => 'sgpb-margin-right-10'));

        $content .= '<input type="hidden" name="page" value="'.SGPB_PUSH_NOTIFICATION_PAGE_KEY.'">';
        // it's for the tabs
        $content .= '<input type="hidden" name="sgpbPageKeyTab" value="subscribers">';
        $content .= '<input type="hidden" class="sgpb-notification-popup-id" name="sgpb-notification-popup-id" value="'.esc_attr(@$_GET['sgpb-notification-popup-id']).'">';

        return $content;
    }

    public function getNavDateConditions() {
        $dates = PushNotAdminHelper::getAllSubscribersDate();
        $defaultList = array('all' => __('All Dates', SG_POPUP_TEXT_DOMAIN));
        $allDates = $defaultList+$dates;

        $selectedDate = '';

        if (isset($_GET['sgpb-notification-date'])) {
            $selectedDate = esc_sql($_GET['sgpb-notification-date']);
        }

        ob_start();
        ?>
        <input type="hidden" class="sgpb-notification-date" name="sgpb-notification-date" value="<?php echo $selectedDate;?>">
        <?php echo $content = AdminHelper::createSelectBox($allDates,$selectedDate, array('id' => 'sgpb-notification-date-list', 'class' => 'sgpb-margin-right-10')); ?>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
