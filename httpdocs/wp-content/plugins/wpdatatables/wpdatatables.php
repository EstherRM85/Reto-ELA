<?php


/*
Plugin Name: wpDataTables - Tables & Table Charts
Plugin URI: https://tms-outsource.com
Description: Create responsive, sortable tables & charts from Excel, CSV or PHP. Add tables & charts to any post in minutes with DataTables.
Version: 2.0.16
Author: TMS-Plugins
Author URI: https://tms-outsource.com
Text Domain: wpdatatables
Domain Path: /languages
*/

?>
<?php

defined('ABSPATH') or die("Cannot access pages directly.");

/******************************
 * Includes and configuration *
 ******************************/

define('WDT_ROOT_PATH', plugin_dir_path(__FILE__)); // full path to the wpDataTables root directory
define('WDT_ROOT_URL', plugin_dir_url(__FILE__)); // URL of wpDataTables plugin
if (!defined('WDT_BASENAME')) {
    define('WDT_BASENAME', plugin_basename(__FILE__));
}
// Config file
require_once(WDT_ROOT_PATH . '/config/config.inc.php');


// Plugin functions
require_once(WDT_ROOT_PATH . 'controllers/wdt_functions.php');

function wpdatatables_load()
{
    if (is_admin()) {
        // Admin panel controller
        require_once(WDT_ROOT_PATH . 'controllers/wdt_admin.php');
        // Admin panel AJAX actions
        require_once(WDT_ROOT_PATH . 'controllers/wdt_admin_ajax_actions.php');

    }
    require_once(WDT_ROOT_PATH . 'source/class.wdttools.php');
    require_once(WDT_ROOT_PATH . 'source/class.wdtconfigcontroller.php');
    require_once(WDT_ROOT_PATH . 'source/class.wdtsettingscontroller.php');
    require_once(WDT_ROOT_PATH . 'source/class.wdtexception.php');
    require_once(WDT_ROOT_PATH . 'source/class.sql.php');
    require_once(WDT_ROOT_PATH . 'source/class.wpdatatable.php');
    require_once(WDT_ROOT_PATH . 'source/class.wpdatacolumn.php');
    require_once(WDT_ROOT_PATH . 'source/class.wpdatachart.php');
    require_once(WDT_ROOT_PATH . 'source/class.wdtbrowsetable.php');
    require_once(WDT_ROOT_PATH . 'source/class.wdtbrowsechartstable.php');
    require_once(WDT_ROOT_PATH . 'source/GutenbergBlock.php');
    require_once(WDT_ROOT_PATH . 'source/WpDataTablesGutenbergBlock.php');
    require_once(WDT_ROOT_PATH . 'source/WpDataChartsGutenbergBlock.php');

    add_action('plugins_loaded', 'wdtLoadTextdomain');
}


/********
 * Hooks *
 ********/
register_activation_hook(__FILE__, 'wdtActivation');
register_deactivation_hook(__FILE__, 'wdtDeactivation');
register_uninstall_hook(__FILE__, 'wdtUninstall');

add_shortcode('wpdatatable', 'wdtWpDataTableShortcodeHandler');
add_shortcode('wpdatachart', 'wdtWpDataChartShortcodeHandler');
add_shortcode('wpdatatable_sum', 'wdtFuncsShortcodeHandler');
add_shortcode('wpdatatable_avg', 'wdtFuncsShortcodeHandler');
add_shortcode('wpdatatable_min', 'wdtFuncsShortcodeHandler');
add_shortcode('wpdatatable_max', 'wdtFuncsShortcodeHandler');


wpdatatables_load();
?>
