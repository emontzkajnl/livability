<?php
namespace sgpbscheduling;
use sgpb\ConfigDataHelper;
use \DateTime;
use \DateTimeZone;

class Actions
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_filter('sgpbOtherConditions', array($this ,'conditionsSatisfy'), 10, 1);
	}

	public function conditionsSatisfy($args)
	{
		if (isset($args['status']) && $args['status'] === false) {
			return $args;
		}
		$args['status'] = true;
		if (empty($args['id']) || empty($args['popupOptions'])) {
			return $args;
		}

		$popupOptions = $args['popupOptions'];
		//schedule checking
		if (!empty($popupOptions['sgpb-schedule-status'])) {
			$isInSchedule = self::popupInSchedule($popupOptions);

			if ($isInSchedule === false) {
				$args['status'] = false;
				return $args;
			}
		}

		// Date range checking
		if (!empty($popupOptions['sgpb-popup-timer-status'])) {
			$inTimeRange = self::popupInTimeRange($popupOptions);

			if ($inTimeRange === false) {
				$args['status'] = false;
				return $args;
			}
		}

		return $args;
	}

	public static function popupInSchedule($popupOptions)
	{
		$scheduleStartWeeks = $popupOptions['sgpb-schedule-weeks'];
		$outInSchedule = false;

		$scheduleStartTime = $popupOptions['sgpb-schedule-start-time'];
		$scheduleEndTime = $popupOptions['sgpb-schedule-end-time'];

		$timezone = AdminHelper::getWpTimezone();
		if (!$timezone) {
			$timezone = SG_POPUP_DEFAULT_TIME_ZONE;
		}

		$date = new DateTime('now', new DateTimeZone($timezone));
		$currentWeekDayName = $date->format('D');

		if (@in_array($currentWeekDayName, $scheduleStartWeeks)) {

			$currentHour =  $date->format('H:i');

			$currentHour = strtotime($currentHour);
			$startTime = strtotime($scheduleStartTime);
			$endTime = strtotime($scheduleEndTime);

			if (empty($scheduleEndTime)) {
				$endTime = strtotime('23:59:59');
			}

			if ($currentHour >= $startTime && $currentHour <= $endTime) {
				return true;
			}
		}

		return $outInSchedule;
	}

	public static function popupInTimeRange($popupOptions)
	{
		$finishDate = false;

		$startDate = strtotime($popupOptions['sgpb-popup-start-timer']);

		if (!empty($popupOptions['sgpb-popup-end-timer'])) {
			$finishDate = strtotime($popupOptions['sgpb-popup-end-timer']);
		}

		$timezone = AdminHelper::getWpTimezone();
		if (!$timezone) {
			$timezone = SG_POPUP_DEFAULT_TIME_ZONE;
		}

		$timeDate = new \DateTime('now', new \DateTimeZone($timezone));
		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));

		if ($finishDate != false && $timeNow > $startDate && $timeNow < $finishDate) {
			return true;
		}
		else if ($finishDate == false && $timeNow > $startDate) {
			return true;
		}

		return false;
	}
}
