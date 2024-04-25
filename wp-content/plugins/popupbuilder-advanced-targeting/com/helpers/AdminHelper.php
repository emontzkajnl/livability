<?php
namespace sgpbadvancedtargeting;

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

	public static function getUserDevice()
	{
		$deviceName = 'Not found any device';
		if (empty($_SERVER["HTTP_USER_AGENT"])) {
			return $deviceName;
		}

		$userAgent = $_SERVER["HTTP_USER_AGENT"];
		$devicesTypes = array(
			"is_desktop" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "macintosh.*safari", "opera"),
			"is_tablet"   => array("tablet", "android", "ipad", "tablet.*firefox"),
			"is_mobile"   => array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
			"is_bot"      => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
		);

		foreach ($devicesTypes as $deviceType => $devices) {
			foreach ($devices as $device) {
				if (preg_match('/'.$device.'/i', $userAgent)) {
					$deviceName = $deviceType;
				}
			}
		}

		return $deviceName;
	}
	public static function getUserOS()
	{
		$osName = __('Not found any OS', SG_POPUP_TEXT_DOMAIN);
		if (empty($_SERVER['HTTP_USER_AGENT'])) {
			return $osName;
		}

		$osTypes = array(
			'is_windows' => array(
				'/windows nt 10/i',
				'/windows nt 6.3/i',
				'/windows nt 6.2/i',
				'/windows nt 6.1/i',
				'/windows nt 6.0/i',
				'/windows nt 5.2/i',
				'/windows nt 5.1/i',
				'/windows xp/i',
				'/windows nt 5.0/i',
				'/windows me/i',
				'/win98/i',
				'/win95/i',
				'/win16/i'
				),
			'is_linux' => array(
				'/linux/i',
				'/ubuntu/i'
				),
			'is_macos' => array(
				'/macintosh|mac os x/i',
				'/mac_powerpc/i'
				),
			'is_android' => array(
				'/android/i'
				),
			'is_ios' => array(
				'/iphone/i',
				'/ipod/i',
				'/ipad/i'
				),
			'is_blackberry' => array(
				'/blackberry/i'
			)
		);

		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		foreach ($osTypes as $key => $values) {
			foreach ($values as $regex) {
			 	if (preg_match($regex, $userAgent)) {
					$osName = $key;
				}
			}
		}

		return $osName;
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

	public static function getDevices()
	{
		$devices = array();

		$devices['is_desktop'] = __('Desktop', SG_POPUP_TEXT_DOMAIN);
		$devices['is_tablet'] = __('Tablet', SG_POPUP_TEXT_DOMAIN);
		$devices['is_mobile'] = __('Mobile', SG_POPUP_TEXT_DOMAIN);
		$devices['is_bot'] = __('Bots', SG_POPUP_TEXT_DOMAIN);

		return $devices;
	}

	public static function getOperationSystems()
	{
		$osArray = array();

		$osArray['is_windows'] = __('Windows', SG_POPUP_TEXT_DOMAIN);
		$osArray['is_linux'] = __('Linux', SG_POPUP_TEXT_DOMAIN);
		$osArray['is_macos'] = __('Mac OS', SG_POPUP_TEXT_DOMAIN);
		$osArray['is_android'] = __('Android', SG_POPUP_TEXT_DOMAIN);
		$osArray['is_ios'] = __('iOS', SG_POPUP_TEXT_DOMAIN);
		$osArray['is_blackberry'] = __('BlackBerry', SG_POPUP_TEXT_DOMAIN);

		return $osArray;
	}

	public static function getWebBrowsers()
	{
		$browsers = array();

		$browsers['chrome'] = __('Chrome', SG_POPUP_TEXT_DOMAIN);
		$browsers['firefox'] = __('Firefox', SG_POPUP_TEXT_DOMAIN);
		$browsers['safari'] = __('Safari', SG_POPUP_TEXT_DOMAIN);
		$browsers['msie'] = __('Internet Explorer', SG_POPUP_TEXT_DOMAIN);
		$browsers['edge'] = __('Microsoft Edge', SG_POPUP_TEXT_DOMAIN);
		$browsers['opera'] = __('Opera', SG_POPUP_TEXT_DOMAIN);
		$browsers['netscape'] = __('Netscape', SG_POPUP_TEXT_DOMAIN);
		$browsers['yabrowser'] = __('Yandex', SG_POPUP_TEXT_DOMAIN);

		return $browsers;
	}

	public static function getUserRoles()
	{
		$rulesArray = array();
		if (!function_exists('get_editable_roles')){
			require_once(ABSPATH.'/wp-admin/includes/user.php');
		}

		$roles = \get_editable_roles();
		foreach ($roles as $roleName => $roleInfo) {
			$rulesArray[$roleName] = $roleName;
		}

		return $rulesArray;
	}

	public static function getCurrentUserRole()
	{
		$role = array();

		if (is_multisite()) {

			$getUsersObj = get_users(
				array(
					'blog_id' => get_current_blog_id()
				)
			);
			if (is_array($getUsersObj)) {
				foreach ($getUsersObj as $key => $userData) {
					if ($userData->ID == get_current_user_id()) {
						$roles = $userData->roles;
						if (is_array($roles) && !empty($roles)) {
							$role[] = $roles[0];
						}
					}
				}
			}

			return $role;
		}

		global $current_user;
		$userRoleName = $current_user->roles;

		if (!empty($userRoleName)) {
			$role = $userRoleName;
		}

		return $role;
	}

	public static function getPageTypes()
	{
		$postTypes = array();

		$postTypes['is_home_page'] = __('Home Page', SG_POPUP_TEXT_DOMAIN);
		$postTypes['is_home'] = __('Posts Page', SG_POPUP_TEXT_DOMAIN);
		$postTypes['is_search'] = __('Search Pages', SG_POPUP_TEXT_DOMAIN);
		$postTypes['is_404'] = __('404 Pages', SG_POPUP_TEXT_DOMAIN);

		return $postTypes;
	}

	public static function getReferalUrl()
	{
		$url = self::wpGetRawReferer();
		$url = self::filterUrl($url);

		return $url;
	}

	public static function wpGetRawReferer()
	{
		if (!empty($_REQUEST['_wp_http_referer'])) {
			return wp_unslash($_REQUEST['_wp_http_referer']);
		}
		else if (!empty($_SERVER['HTTP_REFERER'])) {
			return wp_unslash($_SERVER['HTTP_REFERER']);
		}
		else if (function_exists('wp_get_raw_referer')) {
			$url = wp_get_raw_referer();
			if (!empty($url)) {
				return $url;
			}
		}

		return false;
	}

	public static function filterUrl($url = '')
	{
		if ($url != '') {
			$url = str_replace('www.', '', $url);
			$count = (int)strlen($url);
			$index = $count - 1;
			if (isset($url[$index]) && $url[$index] == '/') {
				$url = substr_replace($url, '', $index, 1);
			}
		}

		return $url;
	}

	public static function getCookieByName($cookieName = '')
	{
		$cookie = false;
		if (empty($_COOKIE)) {
			return $cookie;
		}

		foreach ($_COOKIE as $name => $value) {
			if (strpos($name, $cookieName) !== false) {
				$cookie = $_COOKIE[$name];
				return $cookie;
			}
		}

		return $cookie;
	}

	public static function getWebBrowser()
	{
		$uAgent = 'Unknown';
		$ub = 'Unknown';
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$uAgent = $_SERVER['HTTP_USER_AGENT'];
		}

		if (preg_match('/MSIE/i', $uAgent) && !preg_match('/Opera/i', $uAgent)) {
			$bname = 'Internet Explorer';
			$ub = 'Msie';
		}
		else if (preg_match('/Firefox/i', $uAgent)) {
			$bname = 'Mozilla Firefox';
			$ub = 'Firefox';
		}
		else if (preg_match('/OPR/i', $uAgent)) {
			$bname = 'Opera';
			$ub = 'Opera';
		}
		else if (preg_match('/Chrome/i', $uAgent) && !preg_match('/Edge/i', $uAgent)) {
			$bname = 'Google Chrome';
			$ub = 'Chrome';
		}
		else if (preg_match('/Safari/i', $uAgent) && !preg_match('/Edge/i', $uAgent)) {
			$bname = 'Apple Safari';
			$ub = 'Safari';
		}
		else if (preg_match('/Netscape/i', $uAgent)) {
			$bname = 'Netscape';
			$ub = 'Netscape';
		}
		else if (preg_match('/Edge/i', $uAgent)) {
			$bname = 'Edge';
			$ub = 'Edge';
		}
		else if (preg_match('/YaBrowser/i', $uAgent)) {
			$bname = 'Yandex Browser';
			$ub = 'Yabrowser';
		}
		else if (preg_match('/Trident/i', $uAgent)) {
			$bname = 'Internet Explorer';
			$ub = 'Msie';
		}

		return $ub;

	}
}
