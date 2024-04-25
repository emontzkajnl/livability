<?php
namespace sgpbageverification;
use sgpbageverification\AdminHelper as AgeverificationAdminHelper;

class AdminHelper
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

	public static function defaultSettings()
	{
		$settings = array();
		$settings['days'] = array(
			'' => '--Day--',
			'01' => '01',
			'02' => '02',
			'03' => '03',
			'04' => '04',
			'05' => '05',
			'06' => '06',
			'07' => '07',
			'08' => '08',
			'09' => '09',
			'10' => '10',
			'11' => '11',
			'12' => '12',
			'13' => '13',
			'14' => '14',
			'15' => '15',
			'16' => '16',
			'17' => '17',
			'18' => '18',
			'19' => '19',
			'20' => '20',
			'21' => '21',
			'22' => '22',
			'23' => '23',
			'24' => '24',
			'25' => '25',
			'26' => '26',
			'27' => '27',
			'28' => '28',
			'29' => '29',
			'30' => '30',
			'31' => '31'
		);

		$settings['months'] = array(
			'' => __('--Month--', SG_POPUP_TEXT_DOMAIN),
			'01' => __('January', SG_POPUP_TEXT_DOMAIN),
			'02' => __('February', SG_POPUP_TEXT_DOMAIN),
			'03' => __('March', SG_POPUP_TEXT_DOMAIN),
			'04' => __('April', SG_POPUP_TEXT_DOMAIN),
			'05' => __('May', SG_POPUP_TEXT_DOMAIN),
			'06' => __('June', SG_POPUP_TEXT_DOMAIN),
			'07' => __('July', SG_POPUP_TEXT_DOMAIN),
			'08' => __('August', SG_POPUP_TEXT_DOMAIN),
			'09' => __('September', SG_POPUP_TEXT_DOMAIN),
			'10' => __('October', SG_POPUP_TEXT_DOMAIN),
			'11' => __('November', SG_POPUP_TEXT_DOMAIN),
			'12' => __('December', SG_POPUP_TEXT_DOMAIN)
		);

		$settings['years'] = array(
			'' => '--Year--',
			'2017' => '2017',
			'2016' => '2016',
			'2015' => '2015',
			'2014' => '2014',
			'2013' => '2013',
			'2011' => '2011',
			'2010' => '2010',
			'2009' => '2009',
			'2008' => '2008',
			'2007' => '2007',
			'2006' => '2006',
			'2005' => '2005',
			'2004' => '2004',
			'2003' => '2003',
			'2002' => '2002',
			'2001' => '2001',
			'2000' => '2000',
			'1999' => '1999',
			'1998' => '1998',
			'1997' => '1997',
			'1996' => '1996',
			'1995' => '1995',
			'1994' => '1994',
			'1993' => '1993',
			'1992' => '1992',
			'1991' => '1991',
			'1990' => '1990',
			'1989' => '1989',
			'1988' => '1988',
			'1987' => '1987',
			'1986' => '1986',
			'1985' => '1985',
			'1984' => '1984',
			'1983' => '1983',
			'1982' => '1982',
			'1981' => '1981',
			'1980' => '1980',
			'1979' => '1979',
			'1978' => '1978',
			'1977' => '1977',
			'1976' => '1976',
			'1975' => '1975',
			'1974' => '1974',
			'1973' => '1973',
			'1972' => '1972',
			'1971' => '1971',
			'1970' => '1970',
			'1969' => '1969',
			'1968' => '1968',
			'1967' => '1967',
			'1966' => '1966',
			'1965' => '1965',
			'1964' => '1964',
			'1963' => '1963',
			'1962' => '1962',
			'1961' => '1961',
			'1960' => '1960',
			'1959' => '1959',
			'1958' => '1958',
			'1957' => '1957',
			'1956' => '1956',
			'1955' => '1955',
			'1954' => '1954',
			'1953' => '1953',
			'1952' => '1952',
			'1951' => '1951',
			'1950' => '1950',
			'1949' => '1949',
			'1948' => '1948',
			'1947' => '1947',
			'1946' => '1946',
			'1945' => '1945',
			'1944' => '1944',
			'1943' => '1943',
			'1942' => '1942',
			'1941' => '1941',
			'1940' => '1940',
			'1939' => '1939',
			'1938' => '1938',
			'1937' => '1937',
			'1936' => '1936',
			'1935' => '1935',
			'1934' => '1934',
			'1933' => '1933',
			'1932' => '1932',
			'1931' => '1931',
			'1930' => '1930',
			'1929' => '1929',
			'1928' => '1928',
			'1927' => '1927',
			'1926' => '1926',
			'1925' => '1925',
			'1924' => '1924',
			'1923' => '1923',
			'1922' => '1922',
			'1921' => '1921',
			'1920' => '1920',
			'1919' => '1919',
			'1918' => '1918',
			'1917' => '1917',
			'1916' => '1916',
			'1915' => '1915',
			'1914' => '1914',
			'1913' => '1913',
			'1912' => '1912',
			'1911' => '1911',
			'1910' => '1910',
			'1909' => '1909',
			'1908' => '1908',
			'1907' => '1907',
			'1906' => '1906',
			'1905' => '1905',
			'1904' => '1904',
			'1903' => '1903',
			'1901' => '1901',
			'1900' => '1900'
		);

		return 	$settings;
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
}
