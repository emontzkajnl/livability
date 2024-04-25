<?php
namespace sgpban;
use sgpb\Functions;

class Ajax
{
	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		$this->action();
	}

	public function action()
	{
		add_action('wp_ajax_nopriv_sgpb_analytics_send_data', array($this, 'sendAnalyticsData'));
		add_action('wp_ajax_sgpb_analytics_send_data', array($this, 'sendAnalyticsData'));

		add_action('wp_ajax_sgpb_analytics_data', array($this, 'getChartSettingsScript'));
	}

	public function sendAnalyticsData()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$params = $_POST['params'];

		$eventId = (int)$params['eventName'];
		$targetId = (int)$params['popupId'];
		$currentDate = Date('Y-m-d');
		$eventPageUrl = $params['eventPageUrl'];

		global $wpdb;
		$data = array(
			'event_id'=> $eventId,
			'target_id'=> $targetId,
			'target_type'=> 1,
			'cdate'=> $currentDate,
			'page_url'=> $eventPageUrl,
			'info'=> ''
		);
		$formats = array('%d','%d','%d','%s','%s','%s');

		$wpdb->insert($wpdb->prefix.SGPB_ANALYTICS_TABLE_NAME, $data, $formats);
		wp_die();
	}

	public function getChartSettingsScript()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$settings = $_POST['settings'];
		$dateRange = $settings['dateRange'];
		$targetId = @$settings['targetId'];
		$showEventLists = $settings['eventLists'];

		$obj = new AnalyticsParameters();

		$obj->setTargetId($targetId);
		$obj->setDateRange($dateRange);
		$obj->setShowEventsList($showEventLists);

		$tableDataScript = $obj->createScriptChart();

		ob_start();

		echo "<script type=\"text/javascript\">
				google.charts.load('current', {'packages':['corechart']});
				google.charts.setOnLoadCallback(sgDrawChart);

				function sgDrawChart() {
					var data = google.visualization.arrayToDataTable(".$tableDataScript.");

					var options = {
						title: 'Popup Events',
						curveType: 'none',
						legend: { position: 'bottom' },
						'chartArea': {'width': '80%', 'height': '60%'},
						vAxis: {
				          viewWindow: {
				            min:0
				          }
				        }

					};

					var chart = new google.visualization.LineChart(document.getElementById('sgpb-curve-chart'));

					chart.draw(data, options);
			  }
		</script>";
		$chartScript = ob_get_contents();
		ob_get_clean();

		$data = array(
			'chartScript' => $chartScript,
			'activity' => $this->sgGetPopupActivity($settings)
		);

		echo json_encode($data);
		wp_die();
	}

	public function sgGetPopupActivity($settings)
	{
		$dateRange = $settings['dateRange'];
		$targetId = @$settings['targetId'];
		$showEventLists = $settings['eventLists'];

		$obj = new AnalyticsParameters();

		$obj->setTargetId($targetId);
		$obj->setDateRange($dateRange);
		$obj->setShowEventsList($showEventLists);

		$popuplarPopupsData = $obj->getPopuplarPopups();

		$dataTable = '<table class="table table-striped">';
		$dataTable .= '<tr>';
		$dataTable .= '<th scope="col">'.__('Popup Name', SG_POPUP_TEXT_DOMAIN).'</th>';
		$dataTable .= '<th>'.__('Count', SG_POPUP_TEXT_DOMAIN).'</th>';
		$dataTable .= '</tr>';

			if (!count($popuplarPopupsData)) {
				$dataTable .= '
					<tr>
						<td colspan="2">'.__('No Analytics Data', SG_POPUP_TEXT_DOMAIN).'</td>
					</tr>
				';
			}
			foreach ($popuplarPopupsData as $value) {

				$popupTitle = get_the_title($value['target_id']);
				$dataTable .= '<tr>';
					$dataTable .= '<td>'.$popupTitle.'</td>';
					$dataTable .= '<td>'.$value['event_id'].'</td>';
				$dataTable .= '</tr>';
			}
		$dataTable .= '</table>';

		return $dataTable;
	}
}

new Ajax();
