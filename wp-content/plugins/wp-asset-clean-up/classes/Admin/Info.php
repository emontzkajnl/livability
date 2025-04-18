<?php
namespace WpAssetCleanUp\Admin;

/**
 * Gets information pages such as "Getting Started", "Help" and "Info"
 * Retrieves specific information about a plugin or a theme
 *
 * Class Info
 * @package WpAssetCleanUp
 */
class Info
{
	/**
	 * Info constructor.
	 */
	public function __construct()
	{
		add_action('wpacu_assets_plugin_notice_table_row', array($this, 'pluginNotice'));
	}

	/**
	 *
	 */
	public function gettingStarted()
	{
		$data = array('for' => 'how-it-works');

		if (isset($_GET['wpacu_for'])) {
			$data['for'] = sanitize_text_field($_GET['wpacu_for']);
		}

		MainAdmin::instance()->parseTemplate('admin-page-getting-started', $data, true);
	}

    /**
     *
     */
    public function help()
    {
        MainAdmin::instance()->parseTemplate('admin-page-get-help', array(), true);
    }

    // [wpacu_lite]
    /**
     *
     */
    public function license()
    {
        MainAdmin::instance()->parseTemplate('admin-page-license', array(), true);
    }
    // [/wpacu_lite]

	/**
	 * @param $locationChild
	 * @param $allPlugins
	 * @param $allActivePluginsIcons
	 *
	 * @return string
     *
     * @noinspection NestedAssignmentsUsageInspection
     */
	public static function getPluginInfo($locationChild, $allPlugins, $allActivePluginsIcons)
	{
		foreach (array_keys($allPlugins) as $pluginFile) {
			if (strpos($pluginFile, $locationChild.'/') === 0) {
				$imageIconStyle = $classIconStyle = '';

				if (isset($allActivePluginsIcons[$locationChild]) && $allActivePluginsIcons[$locationChild]) {
					$classIconStyle = 'has-icon';
					$imageIconStyle = 'style="background: transparent url(\''.$allActivePluginsIcons[$locationChild].'\') no-repeat 0 0; background-size: cover;"';
				}

				return '<div class="icon-plugin-default '.$classIconStyle.'"><div class="icon-area" '.$imageIconStyle.'></div></div> &nbsp; <span class="wpacu-child-location-name">'.$allPlugins[$pluginFile]['Name'].'</span>' . ' <span class="wpacu-child-location-version">v'.$allPlugins[$pluginFile]['Version'].'</span>';
			}
		}

		return $locationChild;
	}

	/**
	 * @param $locationChild
	 * @param $allThemes
	 *
	 * @return array
	 */
	public static function getThemeInfo($locationChild, $allThemes)
	{
		foreach (array_keys($allThemes) as $themeDir) {
			if ($locationChild === $themeDir) {
				$themeInfo = wp_get_theme($themeDir);
                $themeIconHtml = ''; // default
                $hasIcon = false; // default

				$themeIconUrl = $themeInfo->get('Name') ? MiscAdmin::getThemeIcon($themeInfo->get('Name')) : '';

                if ($themeIconUrl === '') {
                    // Check for any screenshot.png from the root of the theme
                    $themeFullDir      = $themeInfo->__get('theme_root');
                    $themeTemplateName = $themeInfo->__get('template');

                    $pathToMaybeImage = $themeFullDir . '/'.$themeTemplateName.'/screenshot.png';

                    if (is_file($pathToMaybeImage)) {
                        $themeIconUrl = site_url() . '/' . str_replace(ABSPATH, '', $pathToMaybeImage);
                    }
                }

				if ($themeIconUrl !== '') {
					$hasIcon = true;
					$imageIconStyle = 'style="background: transparent url(\''.$themeIconUrl.'\') no-repeat 0 0; background-size: cover;"';
					$themeIconHtml  = '<div class="icon-theme has-icon"><div class="icon-area" '.$imageIconStyle.'></div></div>';
				}

				$output = $themeIconHtml;

                if ($themeInfo->get('Name')) {
                    $output .= $themeInfo->get('Name');
                } elseif ($themeInfo->__get('template')) {
                    $output .= $themeInfo->__get('template');
                } else {
                    $output .= $themeDir;
                }

                if ($themeInfo->get('Version')) {
                    $output .= ' <span class="wpacu-child-location-version">v'.$themeInfo->get('Version').'</span>';
                }

				return array('has_icon' => $hasIcon, 'output' => $output);
			}
		}

		return array('has_icon' => false, 'output' => $locationChild);
	}

	/**
	 * Notices about consequences in unloading assets from specific plugins
	 *
	 * @param $plugin
	 */
	public function pluginNotice($plugin)
	{
		// Elementor, Elementor Pro
		if (in_array($plugin, array('elementor', 'elementor-pro'))) {
			$wpacuPluginTitle = WPACU_PLUGIN_TITLE;
		?>
		<tr class="wpacu_asset_row wpacu_notice_row">
			<td valign="top">
				<div class="wpacu-warning">
					<p style="margin: 0 0 4px !important;"><small><span class="dashicons dashicons-warning"></span> Most (if not all) of this plugin's files are linked (child &amp; parent) for maximum compatibility. Unloading one Elementor CSS/JS will likely trigger the unloading of other "children" associated with it.  <strong>To avoid breaking the Elementor editor, <?php echo esc_html($wpacuPluginTitle); ?> is deactivated in the page builder's edit &amp; preview mode. If this page is not edited via Elementor and you don't need any of the plugin's functionality (widgets, templates etc.) here, you can unload the files below making sure to test the page after you updated it.</strong></small></p>
	            </div>
			</td>
		</tr>
		<?php
		}
	}
}
