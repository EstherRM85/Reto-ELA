/** New JS controller for wpDataTables **/

var wpDataTables = {};
var wpDataTablesSelRows = {};
var wpDataTablesFunctions = {};
var wpDataTablesUpdatingFlags = {};
var wpDataTablesResponsiveHelpers = {};
var wdtBreakpointDefinition = {
    tablet: 1024,
    phone: 480
};
var wdtCustomUploader = null;

var wdtRenderDataTable = null;

(function ($) {
    $(function () {

        /**
         * Helper function to render a DataTable
         *
         * @param $table jQuery link to the container table object
         * @param tableDescription JSON with the table description
         */
        wdtRenderDataTable = function ($table, tableDescription) {

            // Parse the DataTable init options
            var dataTableOptions = tableDescription.dataTableParams;

            

            /**
             * If aggregate functions shortcode exists on the page add that column to the ajax data
             */
            if ($('.wdt-column-sum[data-table-id="' + tableDescription.tableWpId + '"]').length) {
                var sumColumns = [];
                $('.wdt-column-sum[data-table-id="' + tableDescription.tableWpId + '"]').each(function () {
                    sumColumns.push($(this).data('column-orig-header'));
                });
            }

            if ($('.wdt-column-avg[data-table-id="' + tableDescription.tableWpId + '"]').length) {
                var avgColumns = [];
                $('.wdt-column-avg[data-table-id="' + tableDescription.tableWpId + '"]').each(function () {
                    avgColumns.push($(this).data('column-orig-header'));
                });
            }

            if ($('.wdt-column-min[data-table-id="' + tableDescription.tableWpId + '"]').length) {
                var minColumns = [];
                $('.wdt-column-min[data-table-id="' + tableDescription.tableWpId + '"]').each(function () {
                    minColumns.push($(this).data('column-orig-header'));
                });
            }

            if ($('.wdt-column-max[data-table-id="' + tableDescription.tableWpId + '"]').length) {
                var maxColumns = [];
                $('.wdt-column-max[data-table-id="' + tableDescription.tableWpId + '"]').each(function () {
                    maxColumns.push($(this).data('column-orig-header'));
                });
            }

            if (tableDescription.serverSide) {
                dataTableOptions.ajax.data = function (data) {
                    data.sumColumns = sumColumns;
                    data.avgColumns = avgColumns;
                    data.minColumns = minColumns;
                    data.maxColumns = maxColumns;
                    data.currentUserId = $('#wdt-user-id-placeholder').val();
                    data.currentUserLogin = $('#wdt-user-login-placeholder').val();
                    data.currentPostIdPlaceholder = $('#wdt-post-id-placeholder').val();
                    data.wpdbPlaceholder = $('#wdt-wpdb-placeholder').val();
                };
            }

            /**
             * Show after load if configured
             */
            if (tableDescription.hideBeforeLoad) {
                dataTableOptions.fnInitComplete = function () {
                    $(tableDescription.selector).animateFadeIn();
                }
            }

            /**
             * Init the DataTable itself
             */
            wpDataTables[tableDescription.tableId] = $(tableDescription.selector).dataTable(dataTableOptions);

            /**
             * Remove pagination when "Default rows per page" is set to "All"
             */
            if(wpDataTables[tableDescription.tableId].fnSettings()._iDisplayLength >= wpDataTables[tableDescription.tableId].fnSettings().fnRecordsTotal() || dataTableOptions.iDisplayLength === -1){
                $('.dataTables_paginate').hide();
            }

            /**
             * Remove pagination when "All" is selected from length menu or
             * if value length menu is greater than total records
             */
            wpDataTables[tableDescription.tableId].fnSettings().aoDrawCallback.push({
                sName: 'removePaginate',
                fn: function (oSettings) {
                    var api = oSettings.oInstance.api();

                    if (api.page.len() >= api.page.info().recordsDisplay || api.data().page.len() === -1) {
                        $('.dataTables_paginate').hide();
                    } else {
                        $('.dataTables_paginate').show();
                    }
                }
            });

            /**
             * Add the draw callback
             * @param callback
             */
            wpDataTables[tableDescription.tableId].addOnDrawCallback = function (callback) {
                if (typeof callback !== 'function') {
                    return;
                }

                var index = wpDataTables[tableDescription.tableId].fnSettings().aoDrawCallback.length + 1;

                wpDataTables[tableDescription.tableId].fnSettings().aoDrawCallback.push({
                    sName: 'user_callback_' + index,
                    fn: callback
                });

            };

            

            /**
             * Init row grouping if enabled
             */
            if ((tableDescription.columnsFixed == 0) && (tableDescription.groupingEnabled)) {
                wpDataTables[tableDescription.tableId].rowGrouping({iGroupingColumnIndex: tableDescription.groupingColumnIndex});
            }

            

            return wpDataTables[tableDescription.tableId];

        };

        /**
         * Loop through all tables on the page and render the wpDataTables elements
         */
        $('table.wpDataTable').each(function () {
            var tableDescription = JSON.parse($('#' + $(this).data('described-by')).val());
            wdtRenderDataTable($(this), tableDescription);
        });

    });

})(jQuery);

/**
 * Apply cell action for conditional formatting rule
 *
 * @param $cell
 * @param action
 * @param setVal
 */
function wdtApplyCellAction($cell, action, setVal) {
    switch (action) {
        case 'setCellColor':
            $cell.attr('style', 'background-color: ' + setVal + ' !important');
            break;
        case 'defaultCellColor':
            $cell.attr('style', 'background-color: "" !important');
            break;
        case 'setCellContent':
            $cell.html(setVal);
            break;
        case 'setCellClass':
            $cell.addClass(setVal);
            break;
        case 'removeCellClass':
            $cell.removeClass(setVal);
            break;
        case 'setRowColor':
            $cell.closest('tr').find('td').attr('style', 'background-color: ' + setVal + ' !important');
            break;
        case 'defaultRowColor':
            $cell.closest('tr').find('td').attr('style', 'background-color: "" !important');
            break;
        case 'setRowClass':
            $cell.closest('tr').addClass(setVal);
            break;
        case 'addColumnClass':
            var index = $cell.index() + 1;
            $cell
                .closest('table.wpDataTable')
                .find('tbody td:nth-child(' + index + ')')
                .addClass(setVal);
            break;
        case 'setColumnColor':
            var index = $cell.index() + 1;
            $cell
                .closest('table.wpDataTable')
                .find('tbody td:nth-child(' + index + ')')
                .attr('style', 'background-color: ' + setVal + ' !important');
            break;
    }
}

function wdtDialog(str, title) {
    var dialogId = Math.floor((Math.random() * 1000) + 1);
    var editModal = jQuery('.wdt-frontend-modal').clone();

    editModal.attr('id', 'remodal-' + dialogId);
    editModal.find('.modal-title').html(title);
    editModal.find('.modal-header').append(str);

    return editModal;
}

function wdtAddOverlay(table_selector) {
    jQuery(table_selector).addClass('overlayed');
}

function wdtRemoveOverlay(table_selector) {
    jQuery(table_selector).removeClass('overlayed');
}



/**
 * Get cell value cleared from neighbour html tags
 * @param element
 * @param responsive
 * @returns {*}
 */
function getPurifiedValue(element, responsive) {
    if (responsive) {
        var cellVal = element.children('.columnValue').html();
    } else {
        cellVal = element.clone().children().remove().end().html();
    }

    return cellVal;
}

/**
 * Conditional formatting
 * @param conditionalFormattingRules
 * @param params
 * @param element
 * @param responsive
 */
function wdtCheckConditionalFormatting(conditionalFormattingRules, params, element, responsive) {

    var cellVal = '';
    var ruleVal = '';
    var ruleMatched = false;
    if (( params.columnType == 'int' ) || ( params.columnType == 'float' )) {
        // Process numeric comparison
        cellVal = parseFloat(wdtUnformatNumber(getPurifiedValue(element, responsive), params.thousandsSeparator, params.decimalSeparator, true))
        ruleVal = conditionalFormattingRules.cellVal;
    } else if (params.columnType == 'date') {
        cellVal = moment(getPurifiedValue(element, responsive), params.momentDateFormat).toDate();
        if (conditionalFormattingRules.cellVal == '%TODAY%') {
            ruleVal = moment().startOf('day').toDate();
        } else {
            ruleVal = moment(conditionalFormattingRules.cellVal, params.momentDateFormat).toDate();
        }
    } else if (params.columnType == 'datetime') {
        if (conditionalFormattingRules.cellVal == '%TODAY%') {
            cellVal = moment(getPurifiedValue(element, responsive), params.momentDateFormat + ' ' + params.momentTimeFormat).startOf('day').toDate();
            ruleVal = moment().startOf('day').toDate();
        } else {
            cellVal = moment(getPurifiedValue(element, responsive), params.momentDateFormat + ' ' + params.momentTimeFormat).toDate();
            ruleVal = moment(conditionalFormattingRules.cellVal, params.momentDateFormat + ' ' + params.momentTimeFormat).toDate();
        }
    } else if (params.columnType == 'time') {
        cellVal = moment(getPurifiedValue(element, responsive), params.momentTimeFormat).toDate();
        ruleVal = moment(conditionalFormattingRules.cellVal, params.momentTimeFormat).toDate();
    } else {
        // Process string comparison
        cellVal = getPurifiedValue(element, responsive);
        ruleVal = conditionalFormattingRules.cellVal;
    }

    switch (conditionalFormattingRules.ifClause) {
        case 'lt':
            ruleMatched = cellVal < ruleVal;
            break;
        case 'lteq':
            ruleMatched = cellVal <= ruleVal;
            break;
        case 'eq':
            if (params.columnType == 'date'
                || params.columnType == 'datetime'
                || params.columnType == 'time') {
                cellVal = cellVal != null ? cellVal.getTime() : null;
                ruleVal = ruleVal != null ? ruleVal.getTime() : null;
            }
            ruleMatched = cellVal == ruleVal;
            break;
        case 'neq':
            if (params.columnType == 'date' || params.columnType == 'datetime') {
                cellVal = cellVal != null ? cellVal.getTime() : null;
                ruleVal = ruleVal != null ? ruleVal.getTime() : null;
            }
            ruleMatched = cellVal != ruleVal;
            break;
        case 'gteq':
            ruleMatched = cellVal >= ruleVal;
            break;
        case 'gt':
            ruleMatched = cellVal > ruleVal;
            break;
        case 'contains':
            ruleMatched = cellVal.indexOf(ruleVal) !== -1;
            break;
        case 'contains_not':
            ruleMatched = cellVal.indexOf(ruleVal) == -1;
            break;
    }

    if (ruleMatched) {
        wdtApplyCellAction(element, conditionalFormattingRules.action, conditionalFormattingRules.setVal);
    }
}

jQuery.fn.dataTableExt.oStdClasses.sWrapper = "wpDataTables wpDataTablesWrapper";
jQuery.fn.dataTable.ext.classes.sLengthSelect = 'selectpicker length_menu';
jQuery.fn.dataTable.ext.classes.sFilterInput = 'form-control';
