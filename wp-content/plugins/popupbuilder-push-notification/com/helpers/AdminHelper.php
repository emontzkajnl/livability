<?php
namespace sgpbpush;
use sgpb\SGPopup;
use \SxGeo;

class AdminHelper
{
	public static function oldPluginDetected()
	{
		$hasOldPlugin = false;
		$message = '';

		if (file_exists(WP_PLUGIN_DIR.'/popup-builder-platinum')) {
			$hasOldPlugin = true;
		}
		else if (file_exists(WP_PLUGIN_DIR.'/popup-builder-gold')) {
			$hasOldPlugin = true;
		}
		else if (file_exists(WP_PLUGIN_DIR.'/popup-builder-silver')) {
			$hasOldPlugin = true;
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
		$hasOldPlugin = AdminHelper::oldPluginDetected();

		if (@$hasOldPlugin['status'] == true) {
			return array('status' => false, 'message' => @$hasOldPlugin['message']);
		}

		return array('status' => true, 'message' => '');
	}

	public static function getPushNotificationsSettingsTabConfig()
	{
		$settings = array();
		$settings['sendPush'] = __('Send Push', SG_POPUP_TEXT_DOMAIN);
		$settings['subscribers'] = __('Subscribers', SG_POPUP_TEXT_DOMAIN);
		$settings['campaigns'] = __('Campaigns', SG_POPUP_TEXT_DOMAIN);

		return apply_filters('sgpbNotificationTabs', $settings);
	}

	public static function getAllNotificationPopups()
	{
		global $wpdb;
		$idAndTitle = array();
		$popupIds = $wpdb->get_results('SELECT popupId from '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_TABLE_NAME, ARRAY_A);

		if (empty($popupIds)) {
			return $popupIds;
		}

		foreach ($popupIds as $popupIdRow) {
			$popupId = (int)$popupIdRow['popupId'];
			$popup = SGPopup::find($popupId);

			if (empty($popup) || !is_object($popup)) {
				continue;
			}
			$idAndTitle[$popupId] = $popup->getTitle();
		}

		return $idAndTitle;
	}

	public static function filterQuery(&$query) {

		$searchQuery = '';

		if (isset($_GET['sgpb-notification-date']) && !empty($_GET['sgpb-notification-date'])) {
			$filterCriteria = esc_sql($_GET['sgpb-notification-date']);
			if ($filterCriteria != 'all') {
				if ($searchQuery != '') {
					$searchQuery .= ' AND ';
				}
				$searchQuery .= " cDate LIKE '$filterCriteria%'";
			}
		}
		if (!empty($_GET['sgpb-notification-popup-id']) && $_GET['sgpb-notification-popup-id'] != 'all') {
			if ($searchQuery != '') {
				$searchQuery .= ' AND ';
			}
			$searchQuery .='popupId = '.$_GET['sgpb-notification-popup-id'];
		}
		if (isset($_GET['s']) && !empty($_GET['s'])) {
			$searchCriteria = esc_sql($_GET['s']);
			$searchQuery .= " (region LIKE '%$searchCriteria%' or browser LIKE '%$searchCriteria%')";
		}

		if (!empty($searchQuery)) {
			$query .= ' WHERE '.$searchQuery;
		}
	}

	public static function filterCampaignsQuery(&$query) {

		$searchQuery = '';

		if (isset($_GET['sgpb-notification-date']) && !empty($_GET['sgpb-notification-date'])) {
			$filterCriteria = esc_sql($_GET['sgpb-notification-date']);
			if ($filterCriteria != 'all') {
				if ($searchQuery != '') {
					$searchQuery .= ' AND ';
				}
				$searchQuery .= " cDate LIKE '$filterCriteria%'";
			}
		}
		if (isset($_GET['s']) && !empty($_GET['s'])) {
			$searchCriteria = esc_sql($_GET['s']);
			$searchQuery .= " (campaignTitle LIKE '%$searchCriteria%')";
		}

		if (!empty($searchQuery)) {
			$query .= ' WHERE '.$searchQuery;
		}
	}

	public static function getFormattedDate($date)
	{
		$date = strtotime($date);
		$month = date('F', $date);
		$year = date('Y', $date);

		return $month.' '.$year;
	}

	public static function getAllSubscribersDate()
	{
		$subsDateList = array();
		global $wpdb;
		$subscriptionPopups = $wpdb->get_results('SELECT id, cDate FROM '.$wpdb->prefix.SGPB_PUSH_NOTIFICATION_TABLE_NAME, ARRAY_A);

		foreach ($subscriptionPopups as $subscriptionForm) {
			$date = substr($subscriptionForm['cDate'], 0, 7);

			$subsDateList[$date] = self::getFormattedDate($date);
		}

		$uniqueDates = array_unique($subsDateList, SORT_REGULAR);

		return $uniqueDates;
	}

	public static function updateOption($optionKey, $optionValue)
	{
		update_option($optionKey, $optionValue);
	}

	public static function getOption($optionKey)
	{
		return get_option($optionKey);
	}

	public static function deleteOption($optionKey)
	{
		delete_option($optionKey);
	}

	public static function getIpAddress()
	{
		$ipAddress = 'UNKNOWN';

		if (getenv('HTTP_CLIENT_IP')) {
			$ipAddress = getenv('HTTP_CLIENT_IP');
		}
		else if (getenv('HTTP_X_FORWARDED_FOR')) {
			$ipAddress = getenv('HTTP_X_FORWARDED_FOR');
		}
		else if (getenv('HTTP_X_FORWARDED')) {
			$ipAddress = getenv('HTTP_X_FORWARDED');
		}
		else if (getenv('HTTP_FORWARDED_FOR')) {
			$ipAddress = getenv('HTTP_FORWARDED_FOR');
		}
		else if (getenv('HTTP_FORWARDED')) {
			$ipAddress = getenv('HTTP_FORWARDED');
		}
		else if (getenv('REMOTE_ADDR')) {
			$ipAddress = getenv('REMOTE_ADDR');
		}

		return $ipAddress;
	}

	public static function getCountryName($ip)
	{
		$country = '';
		$countryName = '';
		if (!file_exists(SGPB_PUSH_NOTIFICATION_LIBS_PATH.'SxGeo/SxGeo.php')) {
			return $country;
		}
		require_once(SGPB_PUSH_NOTIFICATION_LIBS_PATH.'SxGeo/SxGeo.php');

		$SxGeo = new \SxGeo(SGPB_PUSH_NOTIFICATION_LIBS_PATH.'SxGeo/SxGeo.dat');
		$country = $SxGeo->getCountry($ip);
		$isoData = self::countriesIsoData();
		if (empty($isoData[$country])) {
			return $countryName;
		}

		return $isoData[$country];
	}

	public static function getRegion()
	{
		$ip = self::getIpAddress();

		return self::getCountryName($ip);
	}

	public static function countriesIsoData()
	{
		$countries = array (
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua And Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia And Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => "Cote D'Ivoire",
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island & Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Islamic Republic of Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle Of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => "Lao People's Democratic Republic",
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States Of',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts And Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre And Miquelon',
			'VC' => 'Saint Vincent And Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome And Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia And Sandwich Isl.',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard And Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad And Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks And Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis And Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);

		return $countries;
	}

	public static function setPushToBottom($element = '')
	{
		$style = '<style type="text/css">';
		$style .= "$element";
		$style .= '{position: absolute !important;';
		$style .= 'left: 0 !important;';
		$style .= 'right: 0 !important;';
		$style .= 'bottom: 2px !important;}';
		$style .= '</style>';

		return $style;
	}
 }
