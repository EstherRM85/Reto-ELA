<?php

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Add submenus and menu options
 */
function wdtAdminMenu() {
    add_menu_page(
        __('wpDataTables', 'wpdatatables'),
        __('wpDataTables', 'wpdatatables'),
        'manage_options',
        'wpdatatables-administration',
        'wdtBrowseTables',
        'none'
    );
    add_submenu_page(
        'wpdatatables-administration',
        __('Create a Table', 'wpdatatables'),
        __('Create a Table', 'wpdatatables'),
        'manage_options',
        'wpdatatables-constructor',
        'wdtConstructor'
    );
    add_submenu_page(
        'wpdatatables-administration',
        __('wpDataCharts', 'wpdatatables'),
        __('wpDataCharts', 'wpdatatables'),
        'manage_options',
        'wpdatatables-charts',
        'wdtBrowseCharts'
    );
    add_submenu_page(
        'wpdatatables-administration',
        __('Create a Chart', 'wpdatatables'),
        __('Create a Chart', 'wpdatatables'),
        'manage_options',
        'wpdatatables-chart-wizard',
        'wdtChartWizard'
    );
    add_submenu_page(
        'wpdatatables-administration',
        __('Settings', 'wpdatatables'),
        __('Settings', 'wpdatatables'),
        'manage_options',
        'wpdatatables-settings',
        'wdtSettings'
    );
    add_submenu_page(
        'wpdatatables-administration',
        __('Add-ons', 'wpdatatables'),
        '<span style="color: #ff8c00">' . __('Addons', 'wpdatatables') . '</span>',
        'manage_options',
        'wpdatatables-add-ons',
        'wdtAddOns'
    );
}

add_action('admin_menu', 'wdtAdminMenu');

/**
 * Enqueue JS and CSS files for the Admin pages
 *
 * @param $hook
 */
function wdtAdminEnqueue($hook) {
    add_filter('admin_body_class', 'wdtAddBodyClass');

    wp_register_style('wdt-dragula', WDT_CSS_PATH . 'dragula/dragula.min.css');
    wp_register_style('wdt-browse-css', WDT_CSS_PATH . 'admin/browse.css');
    wp_register_style('wdt-wpdatatables', WDT_CSS_PATH . 'wpdatatables.min.css');

    wp_register_script('wdt-jsrender', WDT_JS_PATH . 'jsrender/jsrender.min.js', array(), false, true);
    wp_register_script('wdt-dragula', WDT_JS_PATH . 'dragula/dragula.min.js', array(), false, true);
    wp_register_script('wdt-ace', WDT_JS_PATH . 'ace/ace.js', array(), false, true);
    wp_register_script('wdt-common', WDT_ROOT_URL . 'assets/js/wpdatatables/admin/common.js', array('wdt-waves'), false, true);
    wp_register_script('wdt-funcs-js', WDT_JS_PATH . 'wpdatatables/wdt.funcs.js', array('jquery', 'wdt-common'), false, true);
    wp_register_script('wdt-doc-js', WDT_JS_PATH . 'wpdatatables/admin/doc.js', array('jquery', 'wdt-common'), false, true);

    wp_enqueue_style('wdt-admin', WDT_CSS_PATH . 'admin/admin.css');
    wp_enqueue_style('tms-store-checkout', WDT_STORE_URL . '/static/css/checkout/checkout.css');
    wp_enqueue_style('tms-store-wpdatatables', WDT_STORE_URL . '/static/css/checkout/wpdatatables.css');

    wp_enqueue_script('media-upload');
    wp_enqueue_script('tms-store-checkout-config', WDT_STORE_URL . '/static/js/checkout/config.js', array('jquery'), 1.12, true);
    wp_enqueue_script('tms-store-checkout', WDT_STORE_URL . '/static/js/checkout/checkout.js', array('jquery'), 1.12, true);
    wp_enqueue_script('tms-store-checkout-wpdatatables', WDT_STORE_URL . '/static/js/checkout/wpdatatables.js', array('jquery'), 1.12, true);

    wp_enqueue_script('wdt-rating', WDT_JS_PATH . 'wpdatatables/admin/wdtRating.js', array('jquery'), 1.12, true);

    wp_enqueue_media();

    wp_localize_script('wdt-common', 'wpdatatables_edit_strings', WDTTools::getTranslationStrings());
    wp_localize_script('wdt-common', 'wpdatatables_settings', WDTTools::getDateTimeSettings());
    wp_localize_script('wdt-common', 'wdtDashboard', array('siteUrl' => menu_page_url('wpdatatables-administration', false)));

    switch ($hook) {
        case 'toplevel_page_wpdatatables-administration':
            wdtBrowseTablesEnqueue();
            break;
        case 'wpdatatables_page_wpdatatables-constructor':
            isset($_REQUEST['source']) ? wdtEditEnqueue() : wdtConstructorEnqueue();
            break;
        case 'wpdatatables_page_wpdatatables-charts':
            wdtBrowseChartsEnqueue();
            break;
        case 'wpdatatables_page_wpdatatables-chart-wizard':
            wdtChartWizardEnqueue();
            break;
        case 'wpdatatables_page_wpdatatables-settings':
            wdtSettingsEnqueue();
            break;
        case 'wpdatatables_page_wpdatatables-add-ons':
            wdtAddOnsEnqueue();
            break;
    }
}

add_action('admin_enqueue_scripts', 'wdtAdminEnqueue');

/**
 * Enqueue JS and CSS files for the Browse (wpDataTables) Page
 */
function wdtBrowseTablesEnqueue() {
    WDTTools::wdtUIKitEnqueue();

    wp_enqueue_style('wdt-browse-css');

    wp_enqueue_script('wdt-common');
    wp_enqueue_script('wdt-browse-js', WDT_JS_PATH . 'wpdatatables/admin/browse/wdt.browse.js', array(), false, true);
    wp_enqueue_script('wdt-doc-js');

    wp_localize_script('wdt-browse-js', 'wpdatatablesStrings', WDTTools::getTranslationStrings());
}

/**
 * Enqueue JS and CSS files for the Edit (Add from data source) Page
 */
function wdtEditEnqueue() {

    WDTTools::wdtUIKitEnqueue();

    wp_enqueue_style('wdt-wpdatatables');
    wp_enqueue_style('wdt-edit-table-css', WDT_CSS_PATH . 'admin/edit_table.css');
    wp_enqueue_style('wdt-table-tools', WDT_CSS_PATH . 'TableTools.css');


    wp_enqueue_style('wdt-dragula');

    $skin = get_option('wdtBaseSkin');
    if (empty($skin)) {
        $skin = 'skin1';
    }
    switch ($skin) {
        case "skin0":
            $renderSkin = WDT_ASSETS_PATH . 'css/wdt-skins/material.css';
            break;
        case "skin1":
            $renderSkin = WDT_ASSETS_PATH . 'css/wdt-skins/light.css';
            break;
        case "skin2":
            $renderSkin = WDT_ASSETS_PATH . 'css/wdt-skins/graphite.css';
            break;
        case "aqua":
            $renderSkin = WDT_ASSETS_PATH . 'css/wdt-skins/aqua.css';
            break;
        case "purple":
            $renderSkin = WDT_ASSETS_PATH . 'css/wdt-skins/purple.css';
            break;
        case "dark":
            $renderSkin = WDT_ASSETS_PATH . 'css/wdt-skins/dark.css';
            break;
        default:
            $renderSkin = WDT_ASSETS_PATH . 'css/wdt-skins/material.css';
            break;
    }
    wp_enqueue_style('wdt-skin', $renderSkin, array(),WDT_CURRENT_VERSION);

    wp_enqueue_script('wdt-datatables', WDT_JS_PATH . 'jquery-datatables/jquery.dataTables.min.js', array(), false, true);



    wp_enqueue_script('wdt-row-grouping', WDT_JS_PATH . 'jquery-datatables/jquery.dataTables.rowGrouping.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-buttons', WDT_JS_PATH . 'export-tools/dataTables.buttons.min.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-buttons-html5', WDT_JS_PATH . 'export-tools/buttons.html5.min.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-js-zip', WDT_JS_PATH . 'export-tools/jszip.min.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-pdf-make', WDT_JS_PATH . 'export-tools/pdfmake.min.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-vfs-fonts', WDT_JS_PATH . 'export-tools/vfs_fonts.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-button-print', WDT_JS_PATH . 'export-tools/buttons.print.min.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-button-vis', WDT_JS_PATH . 'export-tools/buttons.colVis.min.js', array('jquery', 'wdt-datatables'), false, true);
    wp_enqueue_script('wdt-funcs-js');



    wp_enqueue_script('wdt-wpdatatables', WDT_JS_PATH . 'wpdatatables/wpdatatables.js', array('jquery', 'wdt-datatables'), false, true);



    wp_enqueue_script('wdt-common');
    wp_enqueue_script('wdt-column-config', WDT_JS_PATH . 'wpdatatables/admin/table-settings/column_config_object.js', array(), false, true);
    wp_enqueue_script('wdt-table-config', WDT_JS_PATH . 'wpdatatables/admin/table-settings/table_config_object.js', array(), false, true);
    wp_enqueue_script('wdt-edit-main-js', WDT_JS_PATH . 'wpdatatables/admin/table-settings/main.js', array(), false, true);



    wp_enqueue_script('wdt-add-remove-column', WDT_JS_PATH . 'wpdatatables/wdt.addRemoveColumn.js', array(), false, true);
    wp_enqueue_script('jquery-effects-core');
    wp_enqueue_script('jquery-effects-fade');
    wp_enqueue_script('wdt-dragula');
    wp_enqueue_script('wdt-ace');
    wp_enqueue_script('wdt-doc-js');

    wp_localize_script('wdt-add-remove-column', 'wdtFrontendStrings', WDTTools::getTranslationStrings());
    wp_localize_script('wdt-wpdatatables', 'wpdatatables_frontend_strings', WDTTools::getTranslationStrings());
    wp_localize_script('wdt-advanced-filter', 'wpdatatables_frontend_strings', WDTTools::getTranslationStrings());

    do_action('wdt_enqueue_on_edit_page');
}

/**
 * Enqueue JS and CSS files for the Constructor (wpDataTable Constructor) Page
 */
function wdtConstructorEnqueue() {
    WDTTools::wdtUIKitEnqueue();

    wp_enqueue_style('wdt-wpdatatables');
    wp_enqueue_style('wdt-constructor-css', WDT_CSS_PATH . 'admin/constructor.css');
    wp_enqueue_style('wdt-dragula');

    wp_enqueue_script('wdt-ace');
    wp_enqueue_script('wdt-jsrender');
    wp_enqueue_script('wdt-dragula');
    wp_enqueue_script('wdt-common');
    wp_enqueue_script('wdt-funcs-js');
    wp_enqueue_script('wdt-constructor-main-js', WDT_JS_PATH . 'wpdatatables/admin/constructor/wdt.constructor.js', array(), false, true);
    wp_enqueue_script('wdt-doc-js');

    wp_localize_script('wdt-constructor-main-js', 'wdtConstructorStrings', WDTTools::getTranslationStrings());
}

/**
 * Enqueue JS and CSS files for the Browse Charts (wpDataCharts) Page
 */
function wdtBrowseChartsEnqueue() {
    WDTTools::wdtUIKitEnqueue();

    wp_enqueue_style('wdt-browse-css');

    wp_enqueue_script('wdt-common');
    wp_enqueue_script('wdt-browse-js', WDT_JS_PATH . 'wpdatatables/admin/browse/wdt.browse.js', array(), WDT_CURRENT_VERSION, true);
    wp_enqueue_script('wdt-doc-js');

    wp_localize_script('wdt-browse-js', 'wpdatatablesStrings', WDTTools::getTranslationStrings());
}

/**
 * Enqueue JS and CSS files for the Chart Wizard (Create Chart Wizard) Page
 */
function wdtChartWizardEnqueue() {
    WDTTools::wdtUIKitEnqueue();

    wp_enqueue_style('wdt-dragula');
    wp_enqueue_style('wdt-chart-wizard-css', WDT_CSS_PATH . 'admin/chart_wizard.css');

    wp_enqueue_script('wdt-jsrender');
    wp_enqueue_script('wdt-dragula');

    wp_enqueue_script('wdt-google-charts', '//www.gstatic.com/charts/loader.js', array(), WDT_CURRENT_VERSION, true);

    wp_enqueue_script('wdt-common');
    wp_enqueue_script('wdt-chart-wizard', WDT_JS_PATH . 'wpdatatables/wdt.chartWizard.js', array(), false, true);
    wp_enqueue_script('wdt-wp-google-chart', WDT_JS_PATH . 'wpdatatables/wdt.googleCharts.js', array(), WDT_CURRENT_VERSION, true);
    wp_enqueue_script('wdt-doc-js');

    wp_localize_script('wdt-chart-wizard', 'wpdatatablesEditStrings', WDTTools::getTranslationStrings());
}

/**
 * Enqueue JS and CSS files for the Settings Page
 */
function wdtSettingsEnqueue() {
    WDTTools::wdtUIKitEnqueue();

    wp_enqueue_style('wdt-settings-css', WDT_CSS_PATH . 'admin/settings.css');

    wp_enqueue_script('wdt-common');
    wp_enqueue_script('wdt-plugin-config', WDT_ROOT_URL . 'assets/js/wpdatatables/admin/plugin-settings/plugin_config_object.js', array(), false, true);
    wp_enqueue_script('wdt-settings-main-js', WDT_ROOT_URL . 'assets/js/wpdatatables/admin/plugin-settings/main.js', array(), false, true);
    wp_enqueue_script('wdt-doc-js');

    wp_localize_script('wdt-plugin-config', 'wdt_current_config', WDTSettingsController::getCurrentPluginConfig());
}

/**
 * Enqueue JS and CSS files for the Addons Page
 */
function wdtAddOnsEnqueue() {
    WDTTools::wdtUIKitEnqueue();

    wp_enqueue_style('wdt-add-ons-css', WDT_CSS_PATH . 'admin/addons.css');

    wp_enqueue_script('wdt-common');
    wp_enqueue_script('wdt-doc-js');

}

/**
 * Renders Browse Tables (wpDataTables) Page and handle wpDataTable delete
 */
function wdtBrowseTables() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $action = '';
    if (isset($_REQUEST['action']) && -1 != $_REQUEST['action']) {
        $action = $_REQUEST['action'];
    }
    if (isset($_REQUEST['action2']) && -1 != $_REQUEST['action2']) {
        $action = $_REQUEST['action2'];
    }

    if ($action === 'delete') {
        $tableId = $_REQUEST['table_id'];

        if (!is_array($tableId)) {
            WPDataTable::deleteTable((int)$tableId);
        } else {
            foreach ($tableId as $singleTableId) {
                WPDataTable::deleteTable((int)$singleTableId);
            }
        }
    }

    $wdtBrowseTable = new WDTBrowseTable();
    $wdtBrowseTable->prepare_items();

    ob_start();
    $wdtBrowseTable->display();
    /** @noinspection PhpUnusedLocalVariableInspection */
    $tableHTML = ob_get_contents();
    ob_end_clean();

    ob_start();
    include WDT_ROOT_PATH . 'templates/admin/browse/table/browse.inc.php';
    $browseTablesPage = ob_get_contents();
    ob_end_clean();

    $browseTablesPage = apply_filters('wpdatatables_filter_browse_page', $browseTablesPage);

    echo $browseTablesPage;

    do_action('wpdatatables_browse_page');
}

/**
 * Render Edit (Add from data source) Page
 */
function wdtEdit() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_GET['table_id'])) {
        if (isset($_GET['table_view'])) {
            $tableData = WDTConfigController::loadTableConfig((int)$_GET['table_id'], $_GET['table_view']);
        } else {
            $tableData = WDTConfigController::loadTableConfig((int)$_GET['table_id']);
        }
        if (isset($tableData->error)) {
            echo WDTTools::wdtShowError($tableData->error);
            return;
        }
        WDTTools::exportJSVar('wpdatatable_init_config', $tableData->table);
    }

    ob_start();
    include WDT_ROOT_PATH . 'templates/admin/table-settings/edit_table.inc.php';
    $editPage = ob_get_contents();
    ob_end_clean();

    $editPage = apply_filters('wpdatatables_filter_edit_page', $editPage);
    echo $editPage;
}

/**
 * Render Constructor (Create a Table) Page
 */
function wdtConstructor() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_GET['source'])) {
        wdtEdit();
    } else {
        ob_start();
        include WDT_ROOT_PATH . 'templates/admin/constructor/constructor.inc.php';
        $constructorPage = ob_get_contents();
        ob_end_clean();

        $constructorPage = apply_filters('wpdatatables_filter_constructor_page', $constructorPage);
        echo $constructorPage;
    }
}

/**
 * Render Browse Charts (wpDataTables Charts) Page
 */
function wdtBrowseCharts() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $action = '';
    if (isset($_REQUEST['action']) && -1 != $_REQUEST['action']) {
        $action = $_REQUEST['action'];
    }
    if (isset($_REQUEST['action2']) && -1 != $_REQUEST['action2']) {
        $action = $_REQUEST['action2'];
    }

    if ($action === 'delete') {
        $chartId = $_REQUEST['chart_id'];

        if (!is_array($chartId)) {
            WPDataChart::deleteChart((int)$chartId);
        } else {
            foreach ($chartId as $singleChartId) {
                WPDataChart::deleteChart((int)$singleChartId);
            }
        }
    }

    $wdtBrowseChartsTable = new WDTBrowseChartsTable();
    $wdtBrowseChartsTable->prepare_items();

    ob_start();
    $wdtBrowseChartsTable->display();
    /** @noinspection PhpUnusedLocalVariableInspection */
    $tableHTML = ob_get_contents();
    ob_end_clean();

    ob_start();
    include WDT_ROOT_PATH . 'templates/admin/browse/chart/browse.inc.php';
    $browseChartsPage = ob_get_contents();
    ob_end_clean();

    $browseChartsPage = apply_filters('wpdatatables_filter_charts_table_page', $browseChartsPage);

    echo $browseChartsPage;
}

/**
 * Render Chart Wizard (Create a Chart) Page
 */
function wdtChartWizard() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $chartId = isset($_GET['chart_id']) ? (int)$_GET['chart_id'] : false;
    if (!empty($chartId)) {
        $chartObj = new WPDataChart();
        $chartObj->setId($chartId);
        $chartObj->loadFromDB();
        $chartObj->prepareData();
        $chartObj->shiftStringColumnUp();
    }

    ob_start();
    include WDT_ROOT_PATH . 'templates/admin/chart_wizard/chart_wizard.inc.php';
    $chartWizardPage = ob_get_contents();
    ob_end_clean();

    $chartWizardPage = apply_filters('wpdatatables_filter_chart_wizard_page', $chartWizardPage);
    echo $chartWizardPage;
}

/**
 * Render Settings page
 */
function wdtSettings() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    ob_start();
    include WDT_ROOT_PATH . 'templates/admin/settings/settings.inc.php';
    $settingsPage = ob_get_contents();
    ob_end_clean();

    $settingsPage = apply_filters('wpdatatables_filter_settings_page', $settingsPage);
    echo $settingsPage;

    do_action('wpdatatables_settings_page');
}

/**
 * Render Addons page
 */
function wdtAddOns() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    ob_start();
    include WDT_ROOT_PATH . 'templates/admin/addons/addons.inc.php';
    $addonsPage = ob_get_contents();
    ob_end_clean();

    $addonsPage = apply_filters('wpdatatables_filter_addons_page', $addonsPage);
    echo $addonsPage;
}
