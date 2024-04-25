<?php
namespace sgpban;
use \SGPBAnalyticsConfig;

class AnalyticsParameters
{
	private $data;
	private $countries;
	private $targetEvents;
	private $targetId;
	private $dateRange;
	private $showEventsList;

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setTargetId($targetId)
	{
		$this->targetId = (int)$targetId;
	}

	public function getTargetId()
	{
		return $this->targetId;
	}

	public function setDateRange($dateRange)
	{
		$this->dateRange = (int)$dateRange;
	}

	public function getDateRange()
	{
		return $this->dateRange;
	}

	public function getEventsList()
	{
		return SGPBAnalyticsConfig::sgpbEvents();
	}

	public function setShowEventsList($eventsLis)
	{
		$this->showEventsList = $eventsLis;
	}

	public function getShowEventsList()
	{
		return $this->showEventsList;
	}

	public function getDefaultsEvents()
	{
		$events = $this->getEventsList();
		$defaults = array();

		foreach ($events as $eventName => $event) {
			$key = $event['key'];
			$defaults[$key] = 0;
		}

		return $defaults;
	}

	public static function getCheckboxesData()
	{
		$obj = new self();
		$events = $obj->getEventsList();

		$getCheckboxesData = array();

		foreach ($events as $eventName => $value) {
			$currentDate = array();
			$hasOnWord = strpos($eventName, 'on');

			if ($hasOnWord !== false) {
				$eventName = str_replace('on', 'On ', $eventName);
			}
			else {
				$eventName = substr_replace($eventName, 'On ', 0, 0);
			}
			$currentDate['title'] = $eventName;
			$currentDate['value'] = $value;
			$currentDate['info'] = '';
			$currentDate['data-attributes'] = array('class' => 'sg-events-list');
			array_push($getCheckboxesData, $currentDate);
		}

		return $getCheckboxesData;
	}

	public function getDataFromDb()
	{
		global $wpdb;

		$targetId = $this->getTargetId();
		$dateRange = $this->getDateRange();
		$startDate = Date('Y-m-d',strtotime('-'.$dateRange.' days'));
		$endDate = Date('Y-m-d');

		$st = $wpdb->prepare('SELECT event_id, target_id, target_type, cdate, page_url, info FROM '.$wpdb->prefix.SGPB_ANALYTICS_TABLE_NAME.' WHERE target_id = %d and cdate BETWEEN %s AND %s', $targetId, $startDate, $endDate);
		$arr = $wpdb->get_results($st, ARRAY_A);

		$this->setData($arr);
	}

	public function dataIntersectEvents($allEventsData)
	{
		$showEventsList = $this->getShowEventsList();

		foreach ($allEventsData as $key => $value) {
			if (!in_array($key, $showEventsList)) {
				unset($allEventsData[$key]);
			}
		}

		return $allEventsData;
	}

	public function addRestEvents($events)
	{
		$allDefaultEvents = $this->getDefaultsEvents();

		$diffEvents = array_diff_key($allDefaultEvents, $events);
		$allEventsData = $diffEvents + $events;

		$allEventsData = $this->dataIntersectEvents($allEventsData);
		ksort($allEventsData);

		return $allEventsData;
	}

	public function getOrderingData()
	{
		$dates = $this->getData();
		$dateRange = $this->getDateRange();
		$targetId = $this->getTargetId();
		$gettingData = array();
		$endDate = Date('Y-m-d');

		for ($dateDay = 0; $dateDay <= $dateRange; ++$dateDay) {
			$currentEvents = array();
			$currentDate = Date('Y-m-d',strtotime('-'.$dateDay.' days'));
			foreach ($dates as $date) {
				if ($date['target_id'] == $targetId && $date['cdate'] == $currentDate) {
					array_push($currentEvents, $date['event_id']);
				}
			}

			$currentEvents = $this->addRestEvents(array_count_values($currentEvents));
			$gettingData[$currentDate] = $currentEvents;
			$currentEvents = array();
		}

		return $gettingData;
	}

	public function getSortedData()
	{
		$this->getDataFromDb();
		$data = $this->getOrderingData();

		return $data;
	}

	public function getLabelIntersectEvents()
	{
		$eventsList = $this->getEventsList();
		$eventsListKeys = array_keys($eventsList);

		return $eventsListKeys;
	}

	public function getEventByKey($key)
	{
		$defaultEvents = $this->getEventsList();
		$event = false;

		foreach ($defaultEvents as $defaultEvent) {
			if ($defaultEvent['key'] == $key) {
				$event = $defaultEvent;
				break;
			}
		}

		return $event;
	}

	public function createScriptChart()
	{
		$daylyData = '';
		$dataLabel = "[ 'date', ";
		$allEvents = $this->getShowEventsList();

		$allData = $this->getSortedData();

		foreach ($allEvents as $key => $value) {
			$event = $this->getEventByKey($value);

			if ($event == false) {
				continue;
			}

			$dataLabel .= "'{$event['label']}', ";
		}
		$dataLabel = rtrim($dataLabel, ', ');
		$dataLabel .= '], ';

		$allData = array_reverse($allData);

		foreach ($allData as $day => $eventsCounting) {
			$perDayData = "['".$day."', ";
			foreach ($eventsCounting as $key => $value) {
				$perDayData .= $value.", ";
			}
			$perDayData = rtrim($perDayData, ', ');
			$perDayData .= '], ';
			$daylyData .= $perDayData;
		}

		$daylyData = rtrim($daylyData, ', ');

		$dataLabel =  $dataLabel.$daylyData;

		$tableData = "tableData = [
			$dataLabel
		]";

		return $tableData;
	}

	public function getEventsIdListInString()
	{
		$eventsId = $this->getShowEventsList();
		$listString = '';

		foreach ($eventsId as $eventId => $eventName) {
			$listString .= (int)$eventId.', ';
		}
		$listString = rtrim($listString, ', ');

		return $listString;
	}

	public function getPopuplarPopups()
	{
		global $wpdb;

		$dateRange = $this->getDateRange();
		$startDate = Date('Y-m-d',strtotime('-'.$dateRange.' days'));
		$endDate = Date('Y-m-d');
		$eventsId = $this->getEventsIdListInString();

		$st = $wpdb->prepare('SELECT target_id, count(event_id) as event_id
			FROM '.$wpdb->prefix .SGPB_ANALYTICS_TABLE_NAME.'
			WHERE event_id IN ('.$eventsId.')
			AND cdate
			BETWEEN %s AND %s
			GROUP BY target_id
			ORDER BY event_id
			DESC LIMIT %d',
			$startDate, $endDate, SGPB_ANALYTICS_POPULAR_LIMIT);

		$arr = $wpdb->get_results($st, ARRAY_A);

		return $arr;
	}

	public function getPopupNameFromId($id)
	{
		$infoTargets = GetAnalyticsOptionsData::getPopupIdInfo();
		foreach ($infoTargets as $taregt) {
			if ($taregt['id'] == $id) {
				return $taregt['title'];
			}
		}

		return '';
	}
}
