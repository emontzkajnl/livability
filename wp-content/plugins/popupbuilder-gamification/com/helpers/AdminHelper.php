<?php
namespace sgpbgamification;

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
			$file = WP_PLUGIN_DIR . '/' . $pbEarlyVersion;
			if (file_exists($file)) {
				$pluginKey = $pbEarlyVersion . '/popup-builderPro.php';
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
				if (is_plugin_active($pluginKey)) {
					$hasOldPlugin = true;
					break;
				}
			}
		}

		if ($hasOldPlugin) {
			$message = __("You're using an old version of Popup Builder plugin. We have a brand-new version that you can download from your popup-builder.com account. Please, install the new version of Popup Builder plugin to be able to use it with the new extensions.", 'popupBuilder') . '.';
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

	public static function getGamificationSettingsTabConfig()
	{
		$settings = array();
		$settings['contents'] = __('Content', SG_POPUP_TEXT_DOMAIN);
		$settings['design'] = __('Design', SG_POPUP_TEXT_DOMAIN);
		$settings['options'] = __('Options', SG_POPUP_TEXT_DOMAIN);

		return apply_filters('sgpbNotificationTabs', $settings);
	}

	public static function getTyneMceArgs()
	{
		$args = array(
			'wpautop' => false,
			'tinymce' => array(
				'width' => '100%'
			),
			'textarea_rows' => '3',
			'media_buttons' => true
		);

		return apply_filters('sgpbGamificationTyneMceArgs', $args);
	}

	public static function winningChance()
	{
		$chance = array(
			0 => '0%',
			10 => '10%',
			20 => '20%',
			30 => '30%',
			40 => '40%',
			50 => '50%',
			60 => '60%',
			70 => '70%',
			80 => '80%',
			90 => '90%',
			100 => '100%'
		);

		return apply_filters('sgpbGamificationWinChance', $chance);
	}

	public static function renderGiftIcons($selectedIcon = '')
	{
		ob_start();
		?>
			<div class="sgpb-gift-icons-wrapper">
				<?php $currentIndex = 1; ?>
				<?php while ($currentIndex <= SGPB_GAMIFICATION_IMAGES_COUNT): ?>
					<?php $currentActiveClassName = ''; ?>
					<?php if ('sgpb-gift-icon-'.$currentIndex == $selectedIcon): ?>
						<?php $currentActiveClassName = 'sgpb-active-gift'; ?>
					<?php endif;?>
					<div class="sgpb-gift-icon sgpb-gift-icon-<?php echo $currentIndex; ?> <?php echo $currentActiveClassName; ?>" data-image-name="sgpb-gift-icon-<?php echo $currentIndex; ?>.png"></div>
					<?php ++$currentIndex; ?>
				<?php endwhile; ?>
			</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public static function getImageNameFromSavedData($savedImage)
	{
		$explodedData = explode('img/', $savedImage);
		$imageName = $explodedData[1];
		$imageName = str_replace('.png', '', $imageName) ;

		return $imageName;
	}
}
