=== Ajax Load More: Paging ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/plugins/ajax-load-more/paging/
Requires at least: 5.0
Tested up to: 6.8
Stable tag: trunk
Homepage: https://connekthq.com/ajax-load-more/
Version: 2.0.2


== Copyright ==
Copyright 2025 DConnekt Media
This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Description ==

= Extend Ajax Load More with a numbered paging navigation! =

Replace the default Ajax Load More lazy load/infinite scrolling functionality with an Ajax powered paging navigation system.
http://connekthq.com/plugins/ajax-load-more/paging/

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-paging.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-paging.zip`.
2. Extract the `ajax-load-more-paging` directory to your computer.
3. Upload the `ajax-load-more-paging` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the Plugin dashboard.


== Upgrade Notice ==


== Changelog ==

= 2.0.2 - June 9, 2025 =
* UPDATE: Updated load_text_domain action to remove PHP warning.
* UPDATE: Added `ajax-load-more` as a required plugin and removed activation hooks/notices.

= 2.0.1 - May 25, 2025 =
* NEW: Added support for table layouts.
* UPDATE: Code cleanup and various UI/UX updates.

= 2.0.0 - January 16, 2024 =
* UPGRADE NOTICE: This update is affected by the core Ajax Load More 7.0 release. Updating this plugin will require updating Ajax Load More to 7.0.
* UPDATE: Reduced Ajax requests on initial page load. Previously Paging add-on dispatched 2 requests and we have reduced this to a single request.
* UPDATE: Pagination container is now created on the server side and not with Javascript on page load. This reduces the CLS (Cumulative Layout Shift) of the functionality.
* UPDATE: Adding required changes for Ajax Load More 7.0 and removal of `alm-reveal` wrapper.
* UPDATE: Improved loading animation and timing of pagination display.
* UPDATE: Code cleanup, CSS updates and organization.
* FIX: Fixed issue where first page link in paging naviagtion could result in a JS error.

= 1.6.0 - September 27, 2023 =
* FIX: Fixed issue with paging links not matching final URL in Filters add-on.
* UPDATE: Added support for new Next Page add-on URLs via querystring `pg` parameters.
* UPDATE: Updated paging resize functionality to use ResizeObserver.
* UPDATE: Code cleanup and organization.

= 1.5.6.2 - February 14, 2023 =
* FIX: Fixed potential CSS issue with double loading icon when using Paging and Filters add-ons together.

= 1.5.6.1 - March 7, 2022 =
* Update: Improved initial loading animations.
* Update: Code cleanup and re-organization.


= 1.5.6 - January 10, 2022 =
* NEW - Added new `almPagingComplete` callback that is dispatched once the Paging DOM elements have completed any transiations following an Ajax request.
```
window.almPagingComplete = function(){
  console.log( 'Callback: almPagingComplete tiggered' );
};
```

= 1.5.5 - April 20, 2021 =
* FIX - Fixed issue with the loading of the paging navigation for users who have disabled the Paging CSS. On page load, all page number would be shown when CSS was disabled.

= 1.5.4 - September 10, 2020 =
* UPDATE - Updated `First`/`Last` navigation buttons to only display when required. Previously, First and Last may show even though users can access the first and last pages via button navigation.

= 1.5.3 - May 1, 2020 =
* NEW - Added new Scroll to Top functionality for after a paging click event.
* FIX - Resolved issue with loading transition not displaying properly in some instances.
* FIX - Added CSS fix for loading issue when Paging CSS is not loaded inline.

= 1.5.2 - October 1, 2019 =
* FIX - Fixed issue with pagination not showing in some instances when used with Filters Addon.
* FIX - Fixed issue with Filters and Paging adding paged URLs(?pg=3) even if set to `false` in the shortcode.

= 1.5.1 - August 16, 2019 =
* FIX - Fixed issue where posts would not appear if `posts_per_page` value was greater than the amount of posts returned.

= 1.5.0 - August 6, 2019 =
* NEW - Added Last and First page buttons.
* NEW - Added controls (shortcode parameters) to change the Next and Prev button label.
* UPDATE - Added links URLs to Next, Prev, Last and First buttons.
* UPDATE - Smoother transitions between pages.
* FIX - Fixed issue where addon would throw an error when filtering with zero results.

= 1.4.2 - May 2, 2019 =
* FIX - Fixed issue with zero paging results failing to complete Ajax request or trigger callback functions.
* FIX - Fixed issue with undefined variable in paging URLs when using SEO add-on.

= 1.4.1.1 - March 20, 2019 =
* FIX - Fixed another issue with ALM failing to load if only one page of results was returned.

= 1.4.1 - March 19, 2019 =
* FIX - Fixed issue with ALM failing to load if only page of results was returned.

= 1.4.0 - March 8, 2019 =

## UPGRADE NOTICE
When updating to Paging 1.3.0 you must also update core (Ajax Load More)[https://wordpress.org/plugins/ajax-load-more/] to version 5.0.

#### What's New
* NEW - Plugin completely re-written in vanilla JS.
* UPDATE - Updated callback function to be window scoped.
* NEW - Adding translation file.
* UPDATE - Improved scroll to element functionality and URL updates.

= 1.3.6 - December 6, 2018 =
* UPDATE - Fixed potential issue with plugin updater script.
* UPDATE - Added support for Filters add-on users to scroll user to top of post listing on pagination click.
* UPDATE - Improved JS comments.

= 1.3.5 - October 3, 2018 =
* FIX - Bug fix for using Preloaded addon with Paging. Pagination links were unable to be clicked do to a loading class not being removed.
* FIX - Fixed issue where loading animation could get stuck when using Filters addon and Paging together.

= 1.3.4 - March 8, 2018 =
* NEW - Added support for new Next Page filters for customized URLS.
* NEW - Added support for SEO `alm_seo_remove_trailing_slash` URL filter.

= 1.3.3 - February 22, 2018 =
* UPDATE - Fixed compatibility issues with Filtering
* UPDATE - Code clean up

= 1.3.2 - December 7, 2017 =
* NEW - Added support for new Ajax Load More setting to allow for inlining pagination CSS.
* UPDATE - Updated add-on directory structure to mimic Ajax Load More core.

= 1.3.1 - February 16, 2017 =
* FIX - bug fix for paging_show_at_most parameter not working after last update do to JS error.
* FIX - Correct page not selected on initial load using the Preloaded add-on.

= 1.3 - February 14, 2017 =
* NEW - Added support for Paging + Preloaded + SEO on the same ALM instance
* NEW - Added support for Next Page add-on
* UPDATE - Updating callback functions and timing of animations
* UPDATE - Updating plugin updater script

= 1.2 - May 8, 2016 =
* UPDATE REQUIREMENT - This update requires Ajax Load More v2.10.1
* UPDATE - Updated functions to allow for multiple instances on the same page.
* UPDATE - Updated CSS name from alm-paging.css to ajax-load-more-paging.css
* NEW - Adding .min css
* NEW - Load ajax-load-more-paging.css from your current theme directory (add CSS file to {/alm} directory)

= 1.1.1 - January 12, 2016 =
* FIX - Fixed php warning that would appear upon plugin activation.

= 1.1 =
* UPDATE REQUIREMENT - This update requires Ajax Load More v2.8.3.
* NEW - Adding canonical urls to paging navigation if SEO is enabled.
* NEW - Adding minified paging js file.
* UPDATE - Enqueue Paging JS only if Ajax Load More shortcode ([ajax_load_more]) is active on current page.

= 1.0 =
* Initial Release.
