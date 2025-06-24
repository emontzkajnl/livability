=== Ajax Load More: Filters ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/filters/
Requires at least: 5.0
Tested up to: 6.8
Stable tag: trunk
Homepage: https://connekthq.com/
Version: 3.0.2

== Copyright ==
Copyright 2025 Darren Cooney & Connekt Media

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

== Description ==

= The Filters add-on provides front-end and admin functionality for building and managing Ajax filters. =

Create custom Ajax Load More filters in seconds.

http://connekthq.com/plugins/ajax-load-more/add-ons/filters/

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-filters.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-filters.zip`.
2. Extract the `ajax-load-more-filters` directory to your computer.
3. Upload the `ajax-load-more-filters` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.


== Changelog ==

= 3.0.2 - June 10, 2025 =
* HOTFIX: Fixed issues with updated CSS for the Range Slider not being applied correctly.
* HOTFIX: Remove PHP function decalrations to maintain backwards compatibility with older PHP versions.

= 3.0.1 - June 9, 2025 =
* FIX: Fixed issue with Filters add-on (Tax and Meta Query) leaving unused data attributes on the ALM instance after filtering.
* FIX: Fixed issue with PHP generation function in the Filter builder tool.
* UPDATE: Added support for using wp_cache instead of transients for chacking facet data
* UPDATE: Improve performance of facet indexing.
* UPDATE: Added `ajax-load-more` as a required plugin and removed activation hooks/notices.
* UPDATE: Various code cleanup and refactoring.

= 3.0.0 - May 25, 2025 =
* NEW: Added background processing functionality for indexing of facets. This new features needs to be enabled in the plugin Filter settings in the WordPress admin.
* NEW: Added facet index status in filter sidebar.
* FIX: Fixed issue with multiple filters and maintaining correct query params when filtering.
* FIX: Fixed issue with post/facet update functionality potentially throwing a fatal error because of auto-draft post status.
* UPDATE: Updated various NPM packagaes and dependencies in project.
* UPDATE: Various code improvements and optimizations.

= 2.3.0.1 - January 22, 2025 =
* HOTFIX: Fixed issue with new feature to parse default Taxonomy and Meta Query parameters.

= 2.3.0 - January 17, 2025 =
* NEW: Added feature to maintain default Taxonomy and Meta query params when filtering. This means you can now have default shortcode parameters and have them maintain when running a tax or meta query. This feature requires Ajax Load More 7.2.0.
* NEW: Added support for filtering multiple instances of Ajax Load More on the same page with a single filter. e.g. `[ajax_load_more_filters id="categories" target="alm_1,alm_2"]`
* UPDATE: Updated facet functionality when update/add/remove an individual post. Facet indexes are now modifed and not recreated after a post has been modified. This will make updating the data much faster after the index has been created.
* UPDATE: Added support for facet filtering without requiring URL rewrites. This also updates how facets are saved and retrieved from the database.
* UPDATE: Refactor how and when facet indexing is handled.
* UDPATE: Updating the admin notices and display.
* FIX: Fixed potential issue with `sanitize_key` in filter target IDs.
* FIX: Normalize the filter target ID to lowercase as Ajax Load More and Filter IDs MUST always be lowercase.
* FIX: Load translations later for WP 6.7.

= 2.2.1 - May 30, 2024 =
* NEW: Added new hook that allows for modifying the query args used when creating the facet index. [View Docs](https://connekthq.com/plugins/ajax-load-more/docs/add-ons/filters/facets/#alm_filters_facets_index_args)
* NEW: Added new `almfilters.getActiveFilters()` public JS function that returns an object of active filters.
* NEW: Added new `alm_filters_redirect_underscore_{filter_id}` hook that is used with the Redirect functionality to remove the underscores in URLs on archive pages.

= 2.2.0 - March 21, 2024 =
* NEW: Added Posts Per Page filter.
* NEW: Added support for Post Type facet filtering.
* NEW: Added Filters WordPress Block for Ajax Load More rendering filters directly to the Block Editor.
* NEW: When "Hide Inactive Filter Options" is enabled with facet filtering, the entire filter group (radio & checkboxes only) will now be hidden if no filter options are returned.
* NEW: Added support for deeplink URLs when using multiple Filter instances.
* NEW: Added new `redirect` parameter for redirecting users to a new page after a filter action. e.g. `[ ajax_load_more_filters redirect="https://website.com/results" ...]`
* UPDATE: Added console warning when filters is missing the core Ajax Load More instance.
* UPDATE: Added support for search with use of archive="true".
* FIX: Fixed issue with Facet checkbox/radios and the Show/Hide More buttons not always functioning correctly.
* FIX: Fixed issue with sortKey not resetting after clearing the sort value.
* FIX: Fixed issue with restoring the default value of a filter after a change event.
* UPDATE: Accessibility updates to filter checkbox/radios.
* UPDATE: Various UI/UX updates throughout plugin admin and frontend.

= 2.1.2 - January 16, 2024 =
* UPGRADE NOTICE: This update is affected by the core Ajax Load More 7.0 release. Updating this plugin will require updating Ajax Load More to 7.0.
* UPDATE: Remove all references and output of `alm-reveal` divs.
* UPDATE: Updated JS parameters to match updates in ALM 7.0.

= 2.1.1 - September 27, 2023 =
* NEW: Added support for facets and Post Type filtering.
* UPDATE: Removed Analytics shortcode parameter as Google Analytics 4 (GA4) now handles pageviews automatically.
* UPDATE: Updated Filters JavaScript to support new Google Analytics 4.
* FIX: Fixed issue with aria-checked attribute being encoded incorrectly on the frontend.
* FIX: Fixed issue where taxonomy filter could return a fatal error if no terms exist on the taxonomy.
* FIX: Fixed issue with category and tag filters on frontpage or homepage not parsing the querystring parameters correctly.
* FIX: Stopped frontpage URLs from being encoded by the core WP `redirect_canonical` hook when using a static homepage. e.g. /?category=design+development was being encoded to /?category=design%20development
* UPDATE: Removed legacy IE support for IE10 and IE11.

= 2.1.0 - July 27, 2023 =
* FIX: Fixed issue with `attachment` post type and facets not returning results due to `post_status` not being set to `inherit`.
* FIX: Updated the facet naming convention to allow facets to be reused with multiple instances of Ajax Load More and queries.
* FIX: Added fix for deep link custom field queries not working if a duplicate `meta_key` has been set for ordering.
* FIX: Allow for decimals in Range Slider input steps.
* FIX: Removed orphaned quote in the Select listing for taxonomy terms.
* FIX: Fixed issue with Default Values being incorrectly added to a query when using Radio field type.
* UPDATE: Adding support for new `sort_key` parameter in Ajax Load More 6.1 that adds better control for ordering results by custom field key.
* UPDATE: Updated admin pages to match new Ajax Load More 6.1 admin layout.
* UPDATE: Various code cleanup tasks and file structure organizations.

= 2.0.2.2 - June 11, 2023 =
* UPDATE: Various security fixes and data escaping.
* FIX: Suppressed php 8.1+ warnings about `FILTER_SANITIZE_STRING` being deprecated.
* Fix: Fixed issue with decimal values in range slider being displayed in URL when not required.

= 2.0.2.1 - March 9, 2023 =
* HOTFIX: Sanitizing filters target parameter with `sanitize_key` to coincide with core ALM `5.6.0.4` release.

= 2.0.2 - February 25, 2023 =
 * FIX: Fixed issue with parsing filters & facets on archive templates/pages.
 * FIX: Fixed querystring params not being passed to query on taxonomy archive pages.
 * FIX: Fixed dyanmic filter values not working on archive pages.
 * FIX: Fixed issue with PHP generator output and sort field.
 * NEW: Added `alm_filters_range_slider_steps` hook to adjust the default input steps when using the Range Slider.
 * NEW: Added `alm_filters_textfield_submit_label` to filter textfield submit button labels.
 * NEW: Added `alm_filters_textfield_placeholder` to filter of the textfield input placeholder.
 * NEW: Added `alm_filters_css_classes` to allow for filtering of container classnames.

= 2.0.1 - February 16, 2023 =
* FIX: Fixed PHP warning that could be displayed in debug log about undefined `facet` array key.
* FIX: Fixed issue with unwanted `]`character being rendered in some instances of select drop menus.
* FIX: Fixed issue with select displaying result count even if not checked in Filter admin.

= 2.0.0 - February 14, 2023 =
UPGRADE NOTICE:
This filters update requires updating core Ajax Load More plugin to 5.6.0

* NEW: Added Facet Filtering.
* NEW: Added duplicate filters functionality that allows for easy duplication of filters.
* NEW: Added ability to sort filter dashboard columns by column headers.
* NEW: Added filter preview functionality.
* NEW: Added support for `include_children` parameter when running a taxonomy query.
* NEW: Added support for passing filter ID to `alm_filters( ID, ALM_ID)` PHP method.
* NEW: Added ability to safely delete filters from WP backend when using the `alm_filters()` PHP method for initiating a filter.
* FIX: Fixed issues with Selected Filters display and item counter.
* FIX: Added checker function to confirm taxonomy exists before attempting to render a tax filter which will prevent frontend PHP warnings.
* FIX: Fixed issue with almFiltersActive callback function not working correctly.
* FIX: Fixed issue with Reset button not hiding in the correct instances.
* FIX: Fixed issue with category__and and tag__and checkboxes not remaining selected on page reload.
* FIX: Fixed bug with `default_values` not being maintained on tax and meta queries in some instances.
* UPDATE: Various admin UI/UX updates.
* UPDATE: Cleaned up Filter builder JavaScript to make it easier for future updates.
* UPDATE: Improved taxonomy and meta query handling on deep linked queries.

= 1.13.0.4 - January 10, 2023 =
* Continued in CHANGELOG.

= 1.0 - February 13, 2018 =
* Initial Release.

== Upgrade Notice ==
