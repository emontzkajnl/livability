<?php
require_once(SG_POPUP_HELPERS_PATH.'AdminHelper.php');

use sgpb\AdminHelper;
use sgpbDataTable\SGPBTable;
use sgpbpush\AdminHelper as PushNotAdminHelper;

class NotificationCampaigns extends SGPBTable
{
    public function __construct()
    {
        global $wpdb;
        parent::__construct('ngpbNotificationCampaign');

        $this->setRowsPerPage(SGPB_APP_POPUP_TABLE_LIMIT);
        $this->setTablename($wpdb->prefix.SGPB_PUSH_NOTIFICATION_CAMPAIGNS_TABLE_NAME);

        $columns = array(
            $this->tablename.'.id',
            'cDate',
            'campaignTitle',
            'sent',
            'click',
            'delivered'
        );

        $displayColumns = array(
            'bulk'=>'<input class="subs-bulk" type="checkbox" autocomplete="off">',
            'id' => 'ID',
            'cDate' => __('Date', SG_POPUP_TEXT_DOMAIN),
            'campaignTitle' => __('Campaign title', SG_POPUP_TEXT_DOMAIN),
            'sent' => __('Sent', SG_POPUP_TEXT_DOMAIN),
            'click' => __('Click', SG_POPUP_TEXT_DOMAIN),
            'delivered' => __('Delivered', SG_POPUP_TEXT_DOMAIN)
        );

        $filterColumnsDisplaySettings = array(
            'columns' => $columns,
            'displayColumns' => $displayColumns
        );

        $this->setColumns(@$filterColumnsDisplaySettings['columns']);
        $this->setDisplayColumns(@$filterColumnsDisplaySettings['displayColumns']);
        $this->setSortableColumns(array(
            'id' => array('id', false),
            'cDate' => array('cDate', true),
            'campaignTitle' => array('campaignTitle', true),
            $this->setInitialSort(array(
                'id' => 'DESC'
            ))
        ));
    }

    public function customizeRow(&$row)
    {
        $row[6] = $row[5];
        $row[5] = $row[4];
        $row[4] = $row[3];
        $row[3] = $row[2];
        $row[2] = date('d F Y', strtotime($row[1]));
        $row[1] = $row[0];

        $id = $row[0];
        $row[0] = '<input type="checkbox" class="sgpb-notification-delete-checkbox" data-delete-id="'.esc_attr($id).'">';
    }

    public function customizeQuery(&$query)
    {
        PushNotAdminHelper::filterCampaignsQuery($query);
    }

    public function getNavPopupsConditions()
    {
        $content = '<input type="hidden" name="page" value="'.SGPB_PUSH_NOTIFICATION_PAGE_KEY.'">';
        return $content;
    }

    public function getNavDateConditions() {
        $dates = PushNotAdminHelper::getAllSubscribersDate();
        $defaultList = array('all' => __('All Dates', SG_POPUP_TEXT_DOMAIN));
        $allDates = $defaultList+$dates;

        $selectedDate = '';

        if (isset($_GET['sgpb-campaigns-date'])) {
            $selectedDate = esc_sql($_GET['sgpb-campaigns-date']);
        }

        ob_start();
        ?>
        <input type="hidden" class="sgpb-campaigns-date-list" name="sgpb-campaigns-date" value="<?php echo $selectedDate;?>">
        <?php echo $content = AdminHelper::createSelectBox($allDates,$selectedDate, array('id' => 'sgpb-campaigns-date-list', 'class' => 'sgpb-margin-right-10')); ?>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
