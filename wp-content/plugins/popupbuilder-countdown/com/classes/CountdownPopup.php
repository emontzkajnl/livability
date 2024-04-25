<?php
namespace sgpb;
use sgpbcountdown\ConfigDataHelper;
use sgpbcountdown\CountdownAdminHelper;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'/SGPopup.php');

class CountdownPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'), 1, 100);
	}

	public function adminJsInit()
	{
		add_filter('sgpbAdminCssFiles', array($this, 'popupAdminCssFilter'), 1, 1);
		add_filter('sgpbAdminJs', array($this, 'popupAdminJs'), 1, 1);
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendCssFiles', array($this, 'popupFrontCssFilter'), 1, 1);
		add_filter('sgpbFrontendJs', array($this, 'popupFrontJsFilter'), 1, 1);
	}

	public function popupFrontCssFilter($cssFiles)
	{
		$cssFiles[] = array(
			'folderUrl'=> SGPB_COUNTDOWN_CSS_URL,
			'filename' => 'sgpbFlipclock.css',
			'inFooter' => false
		);

		return $cssFiles;
	}

	public function popupAdminCssFilter($cssFiles)
	{
		$cssFiles[] = array(
			'folderUrl'=> SGPB_COUNTDOWN_CSS_URL,
			'filename' => 'sgpbFlipclock.css',
			'inFooter' => false
		);

		return $cssFiles;
	}

	public function popupFrontJsFilter($jsFiles)
	{
		$popupId = (int)$this->getId();
		$params = $this->getCountdownParamsById($popupId);
		$params['init'] = 'init';
		$options = $this->getOptions();

		$jsFiles['jsFiles'][] = array('folderUrl'=> SGPB_COUNTDOWN_JS_URL, 'filename' => 'sgpbFlipclock.js');
		$jsFiles['jsFiles'][] = array('folderUrl'=> SGPB_COUNTDOWN_JS_URL, 'filename' => 'Countdown.js');
		if (self::allowToOpen($options)) {
			$jsFiles['localizeData'][] = array(
				'handle' => 'Countdown.js',
				'name' => 'SgpbCountdownParams',
				'data' => $params
			);
		}

		return $jsFiles;
	}

	public function popupAdminJs($jsFiles)
	{
		$popupId = (int)$this->getId();
		$params = $this->getCountdownParamsById($popupId, true);

		$jsFiles['jsFiles'][] = array('folderUrl' => SGPB_COUNTDOWN_JS_URL, 'filename' => 'jquery.datetimepicker.full.min.js');
		$jsFiles['jsFiles'][] = array('folderUrl' => SGPB_COUNTDOWN_JS_URL, 'filename' => 'sgpbFlipclock.js');
		$jsFiles['jsFiles'][] = array('folderUrl' => SGPB_COUNTDOWN_JS_URL, 'filename' => 'Countdown.js');
		$jsFiles['localizeData'][] = array(
			'handle' => 'Countdown.js',
			'name' => 'SgpbCountdownParams',
			'data' => $params
		);

		return $jsFiles;
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$defaultOptions[] = array('name' => 'sgpb-countdown-repetitive-timer', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-counter-background-color', 'type' => 'text', 'defaultValue' => '#333333');
		$defaultOptions[] = array('name' => 'sgpb-counter-text-color', 'type' => 'text', 'defaultValue' => '#cccccc');
		$defaultOptions[] = array('name' => 'sgpb-countdown-timezone', 'type' => 'text', 'defaultValue' => ConfigDataHelper::getDefaultTimezone());
		$defaultOptions[] = array('name' => 'sgpb-countdown-due-date', 'type' => 'text', 'defaultValue' => ConfigDataHelper::getCurrentDateTime());
		$defaultOptions[] = array('name' => 'sgpb-countdown-date-format', 'type' => 'text', 'defaultValue' => 'date');
		$defaultOptions[] = array('name' => 'sgpb-countdown-repetitive-seconds', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-countdown-date-days', 'type' => 'text', 'defaultValue' => '1');
		$defaultOptions[] = array('name' => 'sgpb-countdown-date-hours', 'type' => 'number', 'defaultValue' => '0');
		$defaultOptions[] = array('name' => 'sgpb-countdown-date-minutes', 'type' => 'number', 'defaultValue' => '0');
		$defaultOptions[] = array('name' => 'sgpb-counter-labels-color', 'type' => 'text', 'defaultValue' => '#000000');
		$defaultOptions[] = array('name' => 'sgpb-counter-divider-color', 'type' => 'text', 'defaultValue' => '#323434');
		$defaultOptions[] = array('name' => 'sgpb-countdown-location', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-countdown-fixed-position', 'type' => 'text', 'defaultValue' => '2');

		return $defaultOptions;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeOptionsView()
	{
		return array();
	}

	public function getPopupTypeMainView()
	{
		$this->adminJsInit();

		return array(
			'filePath' => SGPB_COUNTDOWN_VIEWS_PATH.'countdown.php',
			'metaboxTitle' => __('Countdown Settings', SG_POPUP_TEXT_DOMAIN),
			'short_description' => 'Create urgency with countdown, you can use either due date or timer functionality'
		);
	}

	public function save()
	{
		$data = $this->getSanitizedData();

		$mustUpdateDate = false;
		$dataFormat = $data['sgpb-countdown-date-format'];
		$addedDays = (int)$data['sgpb-countdown-date-days'];
		$addedHours = (int)$data['sgpb-countdown-date-hours'];
		$addedMinutes = (int)$data['sgpb-countdown-date-minutes'];
		$timezone = $data['sgpb-countdown-timezone'];
		$savedPopup = $this->getSavedPopup();
		$savedDays = '';
		$savedHours = '';
		$savedMinutes = '';

		if (is_object($savedPopup)) {
			$savedDays = (int)$savedPopup->getOptionValue('sgpb-countdown-date-days');
			$savedHours = (int)$savedPopup->getOptionValue('sgpb-countdown-date-hours');
			$savedMinutes = (int)$savedPopup->getOptionValue('sgpb-countdown-date-minutes');
		}

		if (is_object($savedPopup)
			&& $dataFormat != 'date'
			&& ($addedDays != $savedDays|| $addedHours != $savedHours|| $addedMinutes != $savedMinutes)) {

			$mustUpdateDate = true;
		}

		if (!is_object($savedPopup)) {
			$mustUpdateDate = true;
		}
		if (isset($data['sgpb-countdown-date-format']) && $data['sgpb-countdown-date-format'] == 'date') {
			$mustUpdateDate = false;
		}

		if ($mustUpdateDate) {
			$date = CountdownAdminHelper::getDateObjFromDate('now', $timezone);
			$date->modify('+'.$addedDays.' day');
			$date->modify('+'.$addedHours.' hour');
			$date->modify('+'.$addedMinutes.' minute');
			$dueDate = $date->format('Y-m-d H:i');

			$data['sgpb-countdown-due-date'] = $dueDate;
			$this->setSanitizedData($data);
		}

		parent::save();
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$flipClockContent = '';
		$popupId = $this->getId();
		CountdownAdminHelper::resetTimerCounter($popupId);
		$params = $this->getCountdownParamsById($popupId);
		$popupContent = $this->getContent();
		$params = json_encode($params);
		ob_start();
		?>
			<div class="sgpb-countdown-wrapper sgpb-countdown-js-<?php echo $popupId; ?>" id="sgpb-clear-countdown" data-params='<?php echo $params; ?>'>
		<?php
		$flipClockContent .= ob_get_contents();
		ob_end_clean();
		$flipClockContent .= '<div class="sgpb-counts-content sgpb-flipclock-js-'.$popupId.'"></div>';
		$flipClockContent .= '</div>';
		$countdownPosition = $this->getOptionValue('sgpb-countdown-fixed-position');

		if ($countdownPosition != SG_COUNTDOWN_COUNTER_LOCATION_BOTTOM) {
			$popupContent = $flipClockContent.$popupContent;
		}
		else {
			$popupContent .= $flipClockContent;
		}

		$dueDate = $this->getOptionValue('sgpb-countdown-due-date');
		$type = $this->getOptionValue('sgpb-countdown-type');
		$language = $this->getOptionValue('sgpb-countdown-language');

		$args = array(
			'popupId' => $popupId,
			'countdownBgColor' => $this->getOptionValue('sgpb-counter-background-color'),
			'countdownTextColor' => $this->getOptionValue('sgpb-counter-text-color'),
			'countdownLabelsColor' => $this->getOptionValue('sgpb-counter-labels-color'),
			'countdownDividerColor' => $this->getOptionValue('sgpb-counter-divider-color'),
			'countdownPosition' => $countdownPosition,
		);
		$popupContent .= CountdownAdminHelper::renderCountdownStyles($args);

		//time returns current time in seconds
		$diff = strtotime($dueDate) - time();
		//seconds/minute*minutes/hour*hours/day)
		$daysLeft = floor($diff / (60 * 60 * 24));

		/*
		 * if countdown counter type is equal to SG_COUNTDOWN_COUNTER_SECONDS_HIDE
		 * change counter wrapper width to smaller
		 */
		if ($type == SG_COUNTDOWN_COUNTER_SECONDS_HIDE) {
			$popupContent .= '<style type="text/css">';
			$popupContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId {";
			$popupContent .= 'width: 328px;';
			$popupContent .= '}</style>';
		}

		/*
		 * if days count bigger than 99
		 * change counter wrapper width to bigger
		 */
		if ($daysLeft > 99) {
			$popupContent .= '<style type="text/css">';
			$popupContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId {";
			$popupContent .= 'width: 506px;';
			$popupContent .= '}</style>';
		}

		$popupContent .= $this->labelsAlignment($popupId, $language);

		return $popupContent;
	}

	public function getExtraRenderOptions()
	{
		return array();
	}

	/*
	 * getCountdownParamsById() helper function for current popup type,
	 * which get and then prepare them to use in js
	 */
	public function getCountdownParamsById($id, $adminSide = false)
	{
		$seconds = '';
		$repetitiveTimer = false;
		// we need to get directly from wp_postmeta table, to get already updated results
		$options = get_post_meta($id, 'sg_popup_options', true);
		$days         = (int)$this->getOptionValue('sgpb-countdown-date-days');
		$hours        = (int)$this->getOptionValue('sgpb-countdown-date-hours');
		$minutes      = (int)$this->getOptionValue('sgpb-countdown-date-minutes');

		$type         = $this->getOptionValue('sgpb-countdown-type');
		$language     = $this->getOptionValue('sgpb-countdown-language');
		$timezone     = $this->getOptionValue('sgpb-countdown-timezone');
		$dueDate      = $this->getOptionValue('sgpb-countdown-due-date');
		$timer        = $this->getOptionValue('sgpb-countdown-date-format');
		$timerSeconds = $this->getOptionValue('sgpb-countdown-repetitive-seconds');
		if ($timezone) {
			$seconds = CountdownAdminHelper::dateToSeconds($dueDate, $timezone);
		}
		if ($timer && $timer == 'input') {
			$repetitiveTimer = $this->getOptionValue('sgpb-countdown-repetitive-timer');
			if ($repetitiveTimer) {
				$dueDate = $options['sgpb-countdown-due-date'];
				$timerRepetitiveSeconds = ($days * 86400) + ($hours * 3600) + ($minutes * 60);
				$repetitiveTimer = $timerRepetitiveSeconds;
			}
		}
		$params = CountdownAdminHelper::renderCountdownScript(
			$id,
			$seconds,
			$type,
			$language,
			$timezone
		);
		$params['dueDate'] = $dueDate;
		$params['timezone'] = $timezone;
		$params['repetitiveTimer'] = $repetitiveTimer;
		$params['repetitiveTimerSeconds'] = $timerSeconds;
		// init function name for js (adminInit/init), set init by default
		$params['init'] = 'init';
		if ($adminSide) {
			$params['init'] = 'adminInit';
		}

		return $params;
	}

	public static function allowToOpen($options)
	{
		$allowToOpen = true;
		$currentDateTime = date('Y-m-d H:i');
		// check if countdown is not expired
		if ($currentDateTime > $options['sgpb-countdown-due-date'] && $options['sgpb-countdown-date-format'] != 'input' && (isset($options['sgpb-countdown-repetitive-timer']) && $options['sgpb-countdown-repetitive-timer'])) {
			$allowToOpen = false;
		}

		return $allowToOpen;
	}

	private function labelsAlignment($popupId = 0, $language = '')
	{
		$countdownContent = '';

		if ($language == 'German') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.hours .flip-clock-label {";
			$countdownContent .= 'right: -69px !important;}';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.days .flip-clock-label {";
			$countdownContent .= 'right: -60px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Spanish') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.hours .flip-clock-label {";
			$countdownContent .= 'right: -62px !important;}';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.days .flip-clock-label {";
			$countdownContent .= 'right: -65px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Arabic') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.hours .flip-clock-label {";
			$countdownContent .= 'right: -62px !important;}';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.days .flip-clock-label {";
			$countdownContent .= 'right: -53px !important;}';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.minutes .flip-clock-label,";
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.seconds .flip-clock-label {";
			$countdownContent .= 'right: -56px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Italian') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.hours .flip-clock-label {";
			$countdownContent .= 'right: -56px !important;}';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.minutes .flip-clock-label,";
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.days .flip-clock-label {";
			$countdownContent .= 'right: -62px !important;}';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.seconds .flip-clock-label {";
			$countdownContent .= 'right: -68px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Dutch') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.days .flip-clock-label {";
			$countdownContent .= 'right: -63px !important;}';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.seconds .flip-clock-label {";
			$countdownContent .= 'right: -73px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Portuguese') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.days .flip-clock-label {";
			$countdownContent .= 'right: -58px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Russian') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.hours .flip-clock-label {";
			$countdownContent .= 'right: -62px !important;';
			$countdownContent .= "}.sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.minutes .flip-clock-label,";
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.seconds .flip-clock-label {";
			$countdownContent .= 'right: -64px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Swedish') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.hours .flip-clock-label {";
			$countdownContent .= 'right: -66px !important;';
			$countdownContent .= "}.sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.days .flip-clock-label {";
			$countdownContent .= 'right: -63px !important;';
			$countdownContent .= "}.sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.minutes .flip-clock-label {";
			$countdownContent .= 'right: -64px !important;';
			$countdownContent .= "}.sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider.seconds .flip-clock-label {";
			$countdownContent .= 'right: -72px !important;';
			$countdownContent .= '}</style>';
		}

		if ($language == 'Chinese') {
			$countdownContent .= '<style type="text/css">';
			$countdownContent .= ".sgpb-countdown-wrapper.sgpb-countdown-js-$popupId .flip-clock-divider .flip-clock-label {";
			$countdownContent .= 'right: -53px !important;';
			$countdownContent .= '}</style>';
		}

		return $countdownContent;
	}
}
