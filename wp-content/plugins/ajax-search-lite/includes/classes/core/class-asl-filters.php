<?php
if (!defined('ABSPATH')) die('-1');

if ( !class_exists("WD_ASL_Filters") ) {
    /**
     * Class WD_ASL_Filters
     *
     * Registers the plugin Filters, with the proper handler classes.
     * Handling is passed to the handle() method of the specified class.
     * Handlers defined in /classes/filters/class-asp-{handler}.php
     *
     * @class         WD_ASL_Filters
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Core
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_Filters {

        /**
         * Array of internal known filters
         *
         * @var array
         */
        private static $filters = array(
            array(
                "filter" => "posts_results",
                "handler" => array("SearchOverride", "override"),
                "priority"    => 999999999,
                "args"  => 2
            ),
            array(
                "filter" => "posts_request",
                "handler" => array("SearchOverride", "maybeCancelWPQuery"),
                "priority"    => 999999999,
                "args"  => 2
            ),
            array(
                "filter" => "page_link",
                "handler" => array("SearchOverride", "fixUrls"),
                "priority"    => 999999999,
                "args"  => 2
            ),
            array(
                "filter" => "post_link",
                "handler" => array("SearchOverride", "fixUrls"),
                "priority"    => 999999999,
                "args"  => 2
            ),
            array(
                "filter" => "post_type_link",
                "handler" => array("SearchOverride", "fixUrls"),
                "priority"    => 999999999,
                "args"  => 2
            ),
	        array(
		        "filter" => "asl_query_args",
		        "handler" => "Plugin_SearchExclude",
		        "priority"    => 20,
		        "args"  => 1
	        ),
            array(
                "filter" => "get_search_form",
                "handler" => "FormOverride",
                "priority"    => 999999999,
                "args"  => 1
            ),
            array(
                "filter" => "get_product_search_form",
                "handler" => "WooFormOverride",
                "priority"    => 999999999,
                "args"  => 1
            ),
            /* ALLOW SHORTCODE AS MENU TITLE */
            array(
                "filter" => "wp_nav_menu_objects",
                "handler" => array("EtcFixes", "allowShortcodeInMenus"),
                "priority"    => 10,
                "args"  => 1
            ),
            array(
                "filter" => "asl_load_js",
                "handler" => array("EtcFixes", "fixOxygenEditorJS"),
                "priority"    => 999,
                "args"  => 1
            ),
			array(
				"filter" => "asl_save_search_instance_options",
				"handler" => array("EtcFixes", "switchToNewScriptsOnLiveLoader"),
				"priority"    => 999,
				"args"  => 1
			)
        );

        /**
         * Array of already registered handlers
         *
         * @var array
         */
        private static $registered = array();

        /**
         * Registers all the handlers from the $actions variable
         */
        public static function registerAll() {

            foreach (self::$filters as $data)
                self::register($data['filter'], $data['handler'], $data['priority'], $data['args']);
        }

        /**
         * Get all the queued handlers
         *
         * @return array
         */
        public static function getAll( ) {
            return array_keys(self::$filters);
        }

        /**
         * Get all the already registered handlers
         *
         * @return array
         */
        public static function getRegistered() {
            return self::$registered;
        }

        /**
         * Registers a filter with the handler class name.
         *
         * @param $filter
         * @param $handler string|array
         * @param int $priority
         * @param int $accepted_args
         * @return bool
         */
        public static function register( $filter, $handler, $priority = 10, $accepted_args = 0) {

            if ( is_array($handler) ) {
                $class = "WD_ASL_" . $handler[0] . "_Filter";
                $handle = $handler[1];
            } else {
                $class = "WD_ASL_" . $handler . "_Filter";
                $handle = "handle";
            }

            if ( !class_exists($class) ) return false;

            add_action($filter, array(call_user_func(array($class, 'getInstance')), $handle), $priority, $accepted_args);

            self::$registered[] = $filter;

            return true;
        }

        /**
         * Deregisters an action handler.
         *
         * @param $filter
         * @param $handler
         */
        public static function deregister( $filter, $handler ) {

            remove_filter($filter, array(call_user_func(array($handler, 'getInstance')), 'handle'));

        }

    }
}