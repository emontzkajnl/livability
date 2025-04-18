<?php
/*
 * No direct access to this file
 */

use WpAssetCleanUp\AssetsManager;
use WpAssetCleanUp\MetaBoxes;

if (! isset($data)) {
    exit;
}
?>
    <div style="margin: 18px 0 0;" class="clearfix"></div>
<?php
if ( ! AssetsManager::instance()->currentUserCanViewAssetsList() ) {
	?>
    <div class="wpacu-error" style="padding: 10px;">
		<?php echo sprintf(__('Only the administrators listed here can manage CSS/JS assets: %s"Settings" &#10141; "Plugin Usage Preferences" &#10141; "Allow managing assets to:"%s. If you believe you should have access to managing CSS/JS assets, you can add yourself to that list.', 'wp-asset-clean-up'), '<a target="_blank" href="'.esc_url(admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-plugin-usage-settings')).'">', '</a>'); ?></div>
	<?php
	return;
}

$data['dashboard_edit_not_allowed'] = false;

require_once __DIR__.'/_common/_is-dashboard-edit-allowed.php';

if ($data['dashboard_edit_not_allowed']) {
	return; // stop here as the message about the restricted access has been printed
}

$wpacuNoLoadInTargetPage = false;

if ($data['show_on_front'] === 'page' && $data['page_on_front']) {
	/*
	* Case 1: A "page" (post type) set as the homepage
	*/
	?>
	<p><?php echo sprintf( __( 'To read more about creating a static front page in WordPress, %scheck the Codex%s.', 'wp-asset-clean-up' ), '<a target="_blank" href="https://codex.wordpress.org/Creating_a_Static_Front_Page">', '</a>' ); ?></p>
	<?php
    $data['is_homepage_tab'] = true;
    $data['post_id'] = $data['page_on_front'];
	$data['post_type'] = get_post_type($data['post_id']);
	do_action('wpacu_admin_notices');
	require_once __DIR__.'/_singular-page.php';
} else {
	/*
	* Case 2: Home as latest posts (default)
	*/
    $strAdminUrl = 'admin.php?page='.WPACU_PLUGIN_ID.'_assets_manager&wpacu_rand='.uniqid(time(), true);

    if ( isset($_GET['wpacu_manage_dash']) || isset($_GET['force_manage_dash']) ) { // For debugging purposes
        $strAdminUrl .= '&wpacu_manage_dash';
    }

    do_action('wpacu_admin_notices');
    ?>
    <form id="wpacu_dash_assets_manager_form" method="post" action="<?php echo esc_url(admin_url($strAdminUrl)); ?>">
        <input type="hidden"
               name="wpacu_manage_home_page_assets"
               value="1" />
        <input type="hidden"
               id="wpacu_ajax_fetch_assets_list_dashboard_view"
               name="wpacu_ajax_fetch_assets_list_dashboard_view"
               value="1" />

        <?php
        if ( isset($data['external_srcs_ref']) && $data['external_srcs_ref'] ) {
            ?>
                <span data-wpacu-external-srcs-ref="<?php echo esc_attr($data['external_srcs_ref']); ?>" style="display: none;"></span>
            <?php
        }
        ?>

        <p><span class="dashicons dashicons-admin-home"></span> <?php _e('Here you can unload files loaded on the home page. "Front page displays" (from "Settings" &#187; "Reading") is set to either "Your latest posts" (in "Settings" &#187; "Reading") OR a special layout (from a theme or plugin) was enabled.', 'wp-asset-clean-up'); ?> <?php echo sprintf(__('Changes will also apply to pages such as %s etc. in case the latest blog posts are paginated.', 'wp-asset-clean-up'), '<code>/page/2</code> <code>page/3</code>'); ?></p>
        <?php
        $wpacuNoLoadMatchesStatus = assetCleanUpHasNoLoadMatches($data['site_url'], true);
        if ($wpacuNoLoadInTargetPage = in_array($wpacuNoLoadMatchesStatus, array('is_set_in_settings', 'is_set_in_page'))) {
	        if ($wpacuNoLoadMatchesStatus === 'is_set_in_settings') { // Asset CleanUp Pro is set not to load for the front-page
		        ?>
                <p class="wpacu_verified">
                    <strong>Target URL:</strong> <a target="_blank" href="<?php echo esc_url($data['site_url']); ?>"><span><?php echo esc_url($data['site_url']); ?></span></a>
                </p>
                <p class="wpacu-warning"
                   style="margin: 15px 0 0; padding: 10px; font-size: inherit; width: 99%;">
                <span style="color: red;"
                      class="dashicons dashicons-info"></span> <?php echo sprintf(__('This homepage\'s URI is matched by one of the RegEx rules you have in <strong>"Settings"</strong> -&gt; <strong>"Plugin Usage Preferences"</strong> -&gt; <strong>"Do not load the plugin on certain pages"</strong>, thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please remove the matching RegEx rule and reload this page.', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?>
                </p>
		        <?php
	        } elseif ($wpacuNoLoadMatchesStatus === 'is_set_in_page') {
		        ?>
                <p class="wpacu_verified">
                    <strong>Target URL:</strong> <a target="_blank" href="<?php echo esc_url($data['site_url']); ?>"><span><?php echo esc_url($data['site_url']); ?></span></a>
                </p>
                <p class="wpacu-warning"
                   style="margin: 15px 0 0; padding: 10px; font-size: inherit; width: 99%;">
                <span style="color: red;"
                      class="dashicons dashicons-info"></span> <?php echo sprintf(__('This homepage\'s URI is matched by the rule you have in the "Page Options", thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please uncheck the option and reload this page.', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?>
                </p>
		        <?php
	        }

            $data['show_page_options'] = true;

            if (isset($data['post_id']) && $data['post_id'] > 0) {
                $pageOptionsType = 'post';
            } else {
                $pageOptionsType = 'front_page';
                $data['post_id'] = 0;
            }

            $data['page_options'] = MetaBoxes::getPageOptions($data['post_id'], $pageOptionsType);

            $data['page_options_with_assets_manager_no_load'] = true;
            include dirname(__DIR__).'/meta-box-loaded-assets/_page-options.php';
        } else {
        ?>
            <div id="wpacu_meta_box_content">
                <?php
                // "Select a retrieval way:" is set to "Direct" (default one) in "Plugin Usage Preferences" -> "Manage in the Dashboard"
                if ($data['wpacu_settings']['dom_get_type'] === 'direct') {
                ?>
                    <div id="wpacu-list-step-default-status" style="display: none;"><img src="<?php echo esc_url(admin_url('images/spinner.gif')); ?>" align="top" width="20" height="20" alt="" />&nbsp; Please wait...</div>
                    <div id="wpacu-list-step-completed-status" style="display: none;"><span style="color: green;" class="dashicons dashicons-yes-alt"></span> Completed</div>
                    <div>
                        <ul class="wpacu_meta_box_content_fetch_steps">
                            <li id="wpacu-fetch-list-step-1-wrap"><strong>Step 1</strong>: Fetch the assets from the home page... <span id="wpacu-fetch-list-step-1-status"><img src="<?php echo esc_url(admin_url('images/spinner.gif')); ?>" align="top" width="20" height="20" alt="" />&nbsp; Please wait...</li>
                            <li id="wpacu-fetch-list-step-2-wrap"><strong>Step 2</strong>: Build the list of the fetched assets and print it... <span id="wpacu-fetch-list-step-2-status"></span></li>
                        </ul>
                    </div>
                <?php
                } else {
                    // "Select a retrieval way:" is set to "WP Remote POST" (one AJAX call) in "Plugin Usage Preferences" -> "Manage in the Dashboard"
                    ?>
                    <img src="<?php echo esc_url(admin_url('images/spinner.gif')); ?>" align="top" width="20" height="20" alt="" />
                    <?php _e('Retrieving the loaded scripts and styles for the home page. Please wait...', 'wp-asset-clean-up');
                }
                ?>

                <p><?php echo sprintf(
                        __('If you believe fetching the page takes too long and the assets should have loaded by now, I suggest you go to "Settings", make sure "Manage in front-end" is checked and then %smanage the assets in the front-end%s.', 'wp-asset-clean-up'),
                        '<a href="'.$data['site_url'].'#wpacu_wrap_assets">',
                        '</a>'
                    ); ?></p>
            </div>
        <?php
        }

        wp_nonce_field($data['nonce_action'], $data['nonce_name']);
        ?>
        <div id="wpacu-update-button-area" class="no-left-margin">
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary <?php if ( ! $wpacuNoLoadInTargetPage ) { ?> hidden <?php } ?>" value="<?php esc_attr_e('Update', 'wp-asset-clean-up'); ?>">
            </p>
            <div id="wpacu-updating-settings" style="margin-left: 100px;">
                <img src="<?php echo esc_url(admin_url('images/spinner.gif')); ?>" align="top" width="20" height="20" alt="" />
            </div>
        </div>
    </form>
<?php
}
