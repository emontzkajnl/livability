<?php
namespace sgpbcountdown;
use sgpbcountdown\ConfigDataHelper;
use sgpbcountdown\CountdownAdminHelper;

class Ajax
{
	public function __construct()
	{
		$this->actions();
	}

	public function actions()
	{
		add_action('wp_ajax_sgpb_countdown_reset_counter', array($this, 'resetCounter'));
		add_action('wp_ajax_nopriv_sgpb_countdown_reset_counter', array($this, 'resetCounter'));
	}

	public function resetCounter()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$popupId = (int)$_POST['popupId'];
		$options = get_post_meta($popupId, 'sg_popup_options', true);
		$seconds = 0;

		$savedDueDate = $options['sgpb-countdown-due-date'];
		$timezone = $options['sgpb-countdown-timezone'];
		$dateObj = CountdownAdminHelper::getDateObjFromDate('now', $timezone);
		$currentDatetime = $dateObj->format('Y-m-d H:i');

		$currentDatetimeSeconds = CountdownAdminHelper::dateToSeconds($currentDatetime, $timezone);
		$savedDueDateSeconds = CountdownAdminHelper::dateToSeconds($savedDueDate, $timezone);
		$difference = $savedDueDateSeconds - $currentDatetimeSeconds;
		$timerSeconds = $options['sgpb-countdown-repetitive-seconds'];

		while ($difference < 0) {
			$seconds += $timerSeconds;
			$difference += $timerSeconds;
		}
		if ($seconds == 0) {
			$seconds = $timerSeconds;
		}

		$newDueDate = date('Y-m-d H:i', $currentDatetimeSeconds + $seconds);
		$options['sgpb-countdown-due-date'] = $newDueDate;

		update_post_meta($popupId, 'sg_popup_options', $options);

		wp_die($options['sgpb-countdown-repetitive-seconds']);
	}
}
