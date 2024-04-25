<?php
namespace sgpbcountdown;
use \DateTime;
use \DateTimeZone;

class CountdownAdminHelper
{
	public static function oldPluginDetected()
	{
		$hasOldPlugin = false;
		$message = '';

		$pbEarlyVersions = array(
			'popup-builder-silver',
			'popup-builder-gold',
			'popup-builder-platinum',
			'sg-popup-builder-silver',
			'sg-popup-builder-gold',
			'sg-popup-builder-platinum'
		);
		foreach ($pbEarlyVersions as $pbEarlyVersion) {
			$file = WP_PLUGIN_DIR.'/'.$pbEarlyVersion;
			if (file_exists($file)) {
				$pluginKey = $pbEarlyVersion.'/popup-builderPro.php';
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
				if (is_plugin_active($pluginKey)) {
					$hasOldPlugin = true;
					break;
				}
			}
		}

		if ($hasOldPlugin) {
			$message = __("You're using an old version of Popup Builder plugin. We have a brand-new version that you can download from your popup-builder.com account. Please, install the new version of Popup Builder plugin to be able to use it with the new extensions.", 'popupBuilder').'.';
		}

		$result = array(
			'status' => $hasOldPlugin,
			'message' => $message
		);

		return $result;
	}
	/*
	 * check allow to install current extension
	 */
	public static function isSatisfyParameters()
	{
		$hasOldPlugin = self::oldPluginDetected();

		if (@$hasOldPlugin['status'] == true) {
			return array('status' => false, 'message' => @$hasOldPlugin['message']);
		}

		return array('status' => true, 'message' => '');
	}

	// countdown popup (number) styles
	public static function renderCountdownStyles($args = array())
	{
		$popupId = $args['popupId'];
		$countdownBgColor = $args['countdownBgColor'];
		$countdownTextColor = $args['countdownTextColor'];
		$countdownLabelsColor = $args['countdownLabelsColor'];
		$countdownDividerColor = $args['countdownDividerColor'];
		$countdownPosition = $args['countdownPosition'];
		$float = '';
		if ($countdownPosition == SG_COUNTDOWN_COUNTER_LOCATION_TOP_LEFT) {
			$float = '.sgpb-content-'.$popupId.' .sgpb-countdown-js-'.$popupId.' {float: left;}';
		}
		else if ($countdownPosition == SG_COUNTDOWN_COUNTER_LOCATION_TOP_RIGHT) {
			$float = '.sgpb-content-'.$popupId.' .sgpb-countdown-js-'.$popupId.' {float: right;}';
		}

		$styles = "<style type='text/css'>";
		$styles .= ".sgpb-counts-content.sgpb-flipclock-js-$popupId.flip-clock-wrapper ul li a div div.inn {
					background-color: $countdownBgColor;
					color: $countdownTextColor;
				}
				.sgpb-countdown-js-$popupId {
					width: 450px;
					height: 130px;
					padding-top: 22px;
					box-sizing: border-box;
					margin: 0 auto;
				}
				.sgpb-counts-content {
					display: inline-block;
				}
				.sgpb-counts-content ul.flip {
					width: 40px;
					margin: 4px;
					padding: 0!important;
				}
				.flip-clock-wrapper .flip-clock-divider .flip-clock-label {
					color: $countdownLabelsColor;
				}
				.flip-clock-wrapper .flip-clock-divider .flip-clock-dot {
					background-color: $countdownDividerColor;
				}";
		$styles .= $float;
		$styles .= '</style>';

		return $styles;
	}

	// countdown popup scripts and params
	public static function renderCountdownScript($id, $seconds, $type, $language, $timezone)
	{
		$params = array(
			'id'        => $id,
			'seconds'   => $seconds,
			'type'      => $type,
			'language'  => $language,
			'timezone'  => $timezone
		);

		return $params;
	}

	// convert date to seconds
	public static function dateToSeconds($dueDate, $timezone)
	{
		if (empty($timezone)) {
			return '';
		}

		$dateObj = self::getDateObjFromDate('now', $timezone);
		$timeNow = @strtotime($dateObj);
		$seconds = @strtotime($dueDate)-$timeNow;
		if ($seconds < 0) {
			$seconds = 0;
		}

		return $seconds;
	}

	public static function getDateObjFromDate($dueDate, $timezone = 'America/Los_Angeles', $format = 'Y-m-d H:i:s')
	{
		$dateObj = new DateTime($dueDate, new DateTimeZone($timezone));
		$dateObj->format($format);

		return $dateObj;
	}

	public static function resetTimerCounter($popupId = 0)
	{
		$options = get_post_meta($popupId, 'sg_popup_options', true);
		$seconds = 0;

		$savedDueDate = $options['sgpb-countdown-due-date'];
		$timezone = $options['sgpb-countdown-timezone'];
		$dateObj = self::getDateObjFromDate('now', $timezone);
		$currentDatetime = $dateObj->format('Y-m-d H:i');

		$currentDatetimeSeconds = self::dateToSeconds($currentDatetime, $timezone);
		$savedDueDateSeconds = self::dateToSeconds($savedDueDate, $timezone);
		$difference = $savedDueDateSeconds - $currentDatetimeSeconds;
		$timerSeconds = (int)$options['sgpb-countdown-repetitive-seconds'];
		if (!empty($options['sgpb-countdown-repetitive-timer']) && (isset($options['sgpb-countdown-date-format']) && $options['sgpb-countdown-date-format'] != 'date')) {
			while ($difference <= 0) {
				$seconds += $timerSeconds;
				$difference += $timerSeconds;
			}
			if ($seconds == 0) {
				$seconds = $timerSeconds;
			}

			$newDueDate = date('Y-m-d H:i', $currentDatetimeSeconds + $seconds);
			$options['sgpb-countdown-due-date'] = $newDueDate;

			update_post_meta($popupId, 'sg_popup_options', $options);
		}
	}
}
