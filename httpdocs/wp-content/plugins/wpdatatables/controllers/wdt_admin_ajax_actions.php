<?php

defined('ABSPATH') or die("Cannot access pages directly.");



/**
 * Method to save the config for the table and columns
 */
function wdtSaveTableWithColumns() {

    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['wdtNonce'], 'wdtEditNonce')) {
        exit();
    }

    $table = apply_filters(
        'wpdatatables_before_save_table',
        json_decode(
            stripslashes_deep($_POST['table'])
        )
    );

    WDTConfigController::saveTableConfig($table);
}

add_action('wp_ajax_wpdatatables_save_table_config', 'wdtSaveTableWithColumns');

/**
 * Save plugin settings
 */
function wdtSavePluginSettings() {

    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['wdtNonce'], 'wdtSettingsNonce')) {
        exit();
    }

    WDTSettingsController::saveSettings(apply_filters('wpdatatables_before_save_settings', $_POST['settings']));
    exit();
}

add_action('wp_ajax_wpdatatables_save_plugin_settings', 'wdtSavePluginSettings');

/**
 * Duplicate the table
 */
function wdtDuplicateTable() {
    global $wpdb;

    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['wdtNonce'], 'wdtDuplicateTableNonce')) {
        exit();
    }

    $tableId = (int)$_POST['table_id'];
    if (empty($tableId)) {
        return false;
    }
    $manualDuplicateInput = (int)$_POST['manual_duplicate_input'];
    $newTableName = sanitize_text_field($_POST['new_table_name']);

    // Getting the table data
    $tableData = WDTConfigController::loadTableFromDB($tableId);
    $mySqlTableName = $tableData->mysql_table_name;
    $content = $tableData->content;

    // Create duplicate version of input table if checkbox is selected
    if ($manualDuplicateInput) {

        // Generating new input table name
        $cnt = 1;
        $newNameGenerated = false;
        while (!$newNameGenerated) {
            $newName = $tableData->mysql_table_name . '_' . $cnt;
            $checkTableQuery = "SHOW TABLES LIKE '{$newName}'";
            if (!get_option('wdtUseSeparateCon')) {
                $res = $wpdb->get_results($checkTableQuery);
            } else {
                $sql = new PDTSql(WDT_MYSQL_HOST, WDT_MYSQL_DB, WDT_MYSQL_USER, WDT_MYSQL_PASSWORD, WDT_MYSQL_PORT);
                $res = $sql->getRow($checkTableQuery);
            }
            if (!empty($res)) {
                $cnt++;
            } else {
                $newNameGenerated = true;
            }
        }

        // Input table queries
        $query1 = "CREATE TABLE {$newName} LIKE {$tableData->mysql_table_name};";
        $query2 = "INSERT INTO {$newName} SELECT * FROM {$tableData->mysql_table_name};";

        if (!get_option('wdtUseSeparateCon')) {
            $wpdb->query($query1);
            $wpdb->query($query2);
        } else {
            $sql->doQuery($query1);
            $sql->doQuery($query2);
        }
        $mySqlTableName = $newName;
        $content = str_replace($tableData->mysql_table_name, $newName, $tableData->content);
    }

    // Creating new table
    $wpdb->insert(
        $wpdb->prefix . 'wpdatatables',
        array(
            'title' => $newTableName,
            'show_title' => $tableData->show_title,
            'table_type' => $tableData->table_type,
            'content' => $content,
            'filtering' => $tableData->filtering,
            'filtering_form' => $tableData->filtering_form,
            'sorting' => $tableData->sorting,
            'tools' => $tableData->tools,
            'server_side' => $tableData->server_side,
            'editable' => $tableData->editable,
            'inline_editing' => $tableData->inline_editing,
            'popover_tools' => $tableData->popover_tools,
            'editor_roles' => $tableData->editor_roles,
            'mysql_table_name' => $mySqlTableName,
            'edit_only_own_rows' => $tableData->edit_only_own_rows,
            'userid_column_id' => $tableData->userid_column_id,
            'display_length' => $tableData->display_length,
            'auto_refresh' => $tableData->auto_refresh,
            'fixed_columns' => $tableData->fixed_columns,
            'fixed_layout' => $tableData->fixed_layout,
            'responsive' => $tableData->responsive,
            'scrollable' => $tableData->scrollable,
            'word_wrap' => $tableData->word_wrap,
            'hide_before_load' => $tableData->hide_before_load,
            'var1' => $tableData->var1,
            'var2' => $tableData->var2,
            'var3' => $tableData->var3,
            'tabletools_config' => serialize($tableData->tabletools_config),
            'advanced_settings' => $tableData->advanced_settings
        )
    );

    $newTableId = $wpdb->insert_id;

    // Getting the column data
    $columns = WDTConfigController::loadColumnsFromDB($tableId);

    // Creating new columns
    foreach ($columns as $column) {
        $wpdb->insert(
            $wpdb->prefix . 'wpdatatables_columns',
            array(
                'table_id' => $newTableId,
                'orig_header' => $column->orig_header,
                'display_header' => $column->display_header,
                'filter_type' => $column->filter_type,
                'column_type' => $column->column_type,
                'input_type' => $column->input_type,
                'input_mandatory' => $column->input_mandatory,
                'id_column' => $column->id_column,
                'group_column' => $column->group_column,
                'sort_column' => $column->sort_column,
                'hide_on_phones' => $column->hide_on_phones,
                'hide_on_tablets' => $column->hide_on_tablets,
                'visible' => $column->visible,
                'sum_column' => $column->sum_column,
                'skip_thousands_separator' => $column->skip_thousands_separator,
                'width' => $column->width,
                'possible_values' => $column->possible_values,
                'default_value' => $column->default_value,
                'css_class' => $column->css_class,
                'text_before' => $column->text_before,
                'text_after' => $column->text_after,
                'formatting_rules' => $column->formatting_rules,
                'calc_formula' => $column->calc_formula,
                'color' => $column->color,
                'pos' => $column->pos,
                'advanced_settings' => $column->advanced_settings
            )
        );

        if ($column->id == $tableData->userid_column_id) {
            $userIdColumnNewId = $wpdb->insert_id;

            $wpdb->update(
                $wpdb->prefix . 'wpdatatables',
                array('userid_column_id' => $userIdColumnNewId),
                array('id' => $newTableId)
            );
        }

    }

    exit();

}

add_action('wp_ajax_wpdatatables_duplicate_table', 'wdtDuplicateTable');



/**
 * Return all columns for a provided table
 */
function wdtGetColumnsDataByTableId() {
    if (!current_user_can('manage_options') ||
        !(wp_verify_nonce($_POST['wdtNonce'], 'wdtChartWizardNonce') ||
            wp_verify_nonce($_POST['wdtNonce'], 'wdtEditNonce'))
    ) {
        exit();
    }

    $tableId = (int)$_POST['table_id'];

    echo json_encode(WDTConfigController::loadColumnsFromDB($tableId));
    exit();
}

add_action('wp_ajax_wpdatatables_get_columns_data_by_table_id', 'wdtGetColumnsDataByTableId');



/**
 * List all tables in JSON
 */
function wdtListAllTables() {
    if (!current_user_can('manage_options')) {
        exit();
    }

    echo json_encode(WPDataTable::getAllTables());
    exit();
}

add_action('wp_ajax_wpdatatable_list_all_tables', 'wdtListAllTables');


function wdtShowChartFromData()
{
    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['wdtNonce'], 'wdtChartWizardNonce')) {
        exit();
    }

    $chartData = $_POST['chart_data'];
    $wpDataChart = WPDataChart::factory($chartData, false);

    echo json_encode($wpDataChart->returnData());
    exit();
}

add_action('wp_ajax_wpdatatable_show_chart_from_data', 'wdtShowChartFromData');

function wdtSaveChart()
{
    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['wdtNonce'], 'wdtChartWizardNonce')) {
        exit();
    }

    $chartData = $_POST['chart_data'];
    $wpDataChart = WPDataChart::factory($chartData, false);
    $wpDataChart->save();

    echo json_encode(array('id' => $wpDataChart->getId(), 'shortcode' => $wpDataChart->getShortCode()));
    exit();
}

add_action('wp_ajax_wpdatatable_save_chart_get_shortcode', 'wdtSaveChart');

/**
 * List all charts in JSON
 */
function wdtListAllCharts()
{
    if (!current_user_can('manage_options')) {
        exit();
    }

    echo json_encode(WPDataChart::getAllCharts());
    exit();
}

add_action('wp_ajax_wpdatatable_list_all_charts', 'wdtListAllCharts');

/**
 * Duplicate the chart
 */

function wdtDuplicateChart()
{
    global $wpdb;

    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['wdtNonce'], 'wdtDuplicateChartNonce')) {
        exit();
    }

    $chartId = (int)$_POST['chart_id'];
    if (empty($chartId)) {
        return false;
    }
    $newChartName = sanitize_text_field($_POST['new_chart_name']);

    $chartQuery = $wpdb->prepare(
        'SELECT * FROM ' . $wpdb->prefix . 'wpdatacharts WHERE id = %d',
        $chartId
    );

    $wpDataChart = $wpdb->get_row($chartQuery);

    // Creating new table
    $wpdb->insert(
        $wpdb->prefix . "wpdatacharts",
        array(
            'wpdatatable_id' => $wpDataChart->wpdatatable_id,
            'title' => $newChartName,
            'engine' => $wpDataChart->engine,
            'type' => $wpDataChart->type,
            'json_render_data' => $wpDataChart->json_render_data
        )
    );

    exit();
}

add_action('wp_ajax_wpdatatables_duplicate_chart', 'wdtDuplicateChart');