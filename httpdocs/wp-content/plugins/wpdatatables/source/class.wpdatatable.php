<?php

defined('ABSPATH') or die("Cannot access pages directly.");


/**
 * Main engine of wpDataTables plugin
 */
class WPDataTable
{

    protected static $_columnClass = 'WDTColumn';
    protected $_wdtIndexedColumns = array();
    private $_wdtNamedColumns = array();
    private $_defaultSortColumn;
    private $_defaultSortDirection = 'ASC';
    private $_tableContent = '';
    private $_tableType = '';
    private $_title = '';
    private $_interfaceLanguage;
    private $_responsive = false;
    private $_scrollable = false;
    private $_inlineEditing = false;
    private $_popoverTools = false;
    private $_no_data = false;
    private $_filtering_form = false;
    private $_hide_before_load = false;
    public static $wdt_internal_idcount = 0;
    public static $modalRendered = false;
    private $_pagination = true;
    private $_showFilter = true;
    private $_firstOnPage = false;
    private $_groupingEnabled = false;
    private $_wdtColumnGroupIndex = 0;
    private $_showAdvancedFilter = false;
    private $_wdtTableSort = true;
    private $_serverProcessing = false;
    private $_wdtColumnTypes = array();
    private $_dataRows = array();
    public $_cacheHash = '';
    private $_showTT = true;
    private $_lengthDisplay = 10;
    private $_cssClassArray = array();
    private $_style = '';
    private $_editable = false;
    private $_id;
    private $_idColumnKey = '';
    private $_db;
    private $_wpId = '';
    private $_onlyOwnRows = false;
    private $_userIdColumn = 0;
    private $_defaultSearchValue = '';
    protected $_sumColumns = array();
    protected $_avgColumns = array();
    protected $_minColumns = array();
    protected $_maxColumns = array();
    protected $_sumFooterColumns = array();
    protected $_avgFooterColumns = array();
    protected $_minFooterColumns = array();
    protected $_maxFooterColumns = array();
    protected $_columnsDecimalPlaces = array();
    protected $_columnsThousandsSeparator = array();
    protected $_conditionalFormattingColumns = array();
    private $_fixedLayout = false;
    private $_wordWrap = false;
    private $_columnsCSS = '';
    private $_tableToolsConfig = array();
    private $_autoRefreshInterval = 0;
    private $_infoBlock = true;
    private $_globalSearch = true;
    private $_showRowsPerPage = true;
    private $_aggregateFuncsRes = array();
    private $_ajaxReturn = false;
    private $_clearFilters = false;
    public static $allowedTableTypes = array('xls', 'csv', 'manual', 'mysql', 'json', 'google_spreadsheet', 'xml', 'serialized');

    /**
     * @return bool
     */
    public function isClearFilters()
    {
        return $this->_clearFilters;
    }

    /**
     * @param bool $clearFilters
     */
    public function setClearFilters($clearFilters)
    {
        $this->_clearFilters = $clearFilters;
    }

    /**
     * @return bool
     */
    public function isFixedLayout()
    {
        return $this->_fixedLayout;
    }

    /**
     * @param bool $fixedLayout
     */
    public function setFixedLayout($fixedLayout)
    {
        $this->_fixedLayout = $fixedLayout;
    }

    /**
     * @return bool
     */
    public function isWordWrap()
    {
        return $this->_wordWrap;
    }

    /**
     * @param bool $wordWrap
     */
    public function setWordWrap($wordWrap)
    {
        $this->_wordWrap = $wordWrap;
    }

    /**
     * @return bool
     */
    public function isAjaxReturn()
    {
        return $this->_ajaxReturn;
    }

    /**
     * @param bool $ajaxReturn
     */
    public function setAjaxReturn($ajaxReturn)
    {
        $this->_ajaxReturn = $ajaxReturn;
    }

    public function setNoData($no_data)
    {
        $this->_no_data = $no_data;
    }

    public function getNoData()
    {
        return $this->_no_data;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getTableContent()
    {
        return $this->_tableContent;
    }

    /**
     * @param string $tableContent
     */
    public function setTableContent($tableContent)
    {
        $this->_tableContent = $tableContent;
    }

    /**
     * @return string
     */
    public function getTableType()
    {
        return $this->_tableType;
    }

    /**
     * @param string $tableType
     */
    public function setTableType($tableType)
    {
        $this->_tableType = $tableType;
    }

    public function setDefaultSearchValue($value)
    {
        if (!empty($value)) {
            $this->_defaultSearchValue = urlencode($value);
        }
    }

    public function getDefaultSearchValue()
    {
        return urldecode($this->_defaultSearchValue);
    }

    public function sortEnabled()
    {
        return $this->_wdtTableSort;
    }

    public function sortEnable()
    {
        $this->_wdtTableSort = true;
    }

    public function sortDisable()
    {
        $this->_wdtTableSort = false;
    }

    public function addSumColumn($columnKey)
    {
        $this->_sumColumns[] = $columnKey;
    }

    public function setSumColumns($sumColumns)
    {
        $this->_sumColumns = $sumColumns;
    }

    public function getSumColumns()
    {
        return $this->_sumColumns;
    }

    public function addAvgColumn($columnKey)
    {
        $this->_avgColumns[] = $columnKey;
    }

    public function setAvgColumns($avgColumns)
    {
        $this->_avgColumns = $avgColumns;
    }

    public function getAvgColumns()
    {
        return $this->_avgColumns;
    }

    public function addMinColumn($columnKey)
    {
        $this->_minColumns[] = $columnKey;
    }

    public function setMinColumns($minColumns)
    {
        $this->_minColumns = $minColumns;
    }

    public function getMinColumns()
    {
        return $this->_minColumns;
    }

    public function addMaxColumn($columnKey)
    {
        $this->_maxColumns[] = $columnKey;
    }

    public function setMaxColumns($maxColumns)
    {
        $this->_maxColumns = $maxColumns;
    }

    public function getMaxColumns()
    {
        return $this->_maxColumns;
    }

    public function addSumFooterColumn($columnKey)
    {
        $this->_sumFooterColumns[] = $columnKey;
    }

    public function setSumFooterColumns($sumColumns)
    {
        $this->_sumFooterColumns = $sumColumns;
    }

    public function getSumFooterColumns()
    {
        return $this->_sumFooterColumns;
    }

    public function addAvgFooterColumn($columnKey)
    {
        $this->_avgFooterColumns[] = $columnKey;
    }

    public function setAvgFooterColumns($avgColumns)
    {
        $this->_avgFooterColumns = $avgColumns;
    }

    public function getAvgFooterColumns()
    {
        return $this->_avgFooterColumns;
    }

    public function addMinFooterColumn($columnKey)
    {
        $this->_minFooterColumns[] = $columnKey;
    }

    public function setMinFooterColumns($minColumns)
    {
        $this->_minFooterColumns = $minColumns;
    }

    public function getMinFooterColumns()
    {
        return $this->_minFooterColumns;
    }

    public function addMaxFooterColumn($columnKey)
    {
        $this->_maxFooterColumns[] = $columnKey;
    }

    public function setMaxFooterColumns($maxColumns)
    {
        $this->_maxFooterColumns = $maxColumns;
    }

    public function getMaxFooterColumns()
    {
        return $this->_maxFooterColumns;
    }

    public function addColumnsDecimalPlaces($columnKey, $decimalPlaces)
    {
        $this->_columnsDecimalPlaces[$columnKey] = $decimalPlaces;
    }

    public function addColumnsThousandsSeparator($columnKey, $thousandsSeparator)
    {
        $this->_columnsThousandsSeparator[$columnKey] = $thousandsSeparator;
    }

    public function getColumnsCSS()
    {
        return $this->_columnsCSS;
    }

    public function setColumnsCss($css)
    {
        $this->_columnsCSS = $css;
    }

    public function reorderColumns($posArray)
    {
        if (!is_array($posArray)) {
            throw new WDTException('Invalid position data provided!');
        }
        $resultArray = array();
        $resultByKeys = array();

        foreach ($posArray as $pos => $dataColumnIndex) {
            $resultArray[$pos] = $this->_wdtNamedColumns[$dataColumnIndex];
            $resultByKeys[$dataColumnIndex] = $this->_wdtNamedColumns[$dataColumnIndex];
        }
        $this->_wdtIndexedColumns = $resultArray;
        $this->_wdtNamedColumns = $resultByKeys;
    }

    public function getWpId()
    {
        return $this->_wpId;
    }

    public function setWpId($wpId)
    {
        $this->_wpId = $wpId;
    }

    public function getCssClassesArr()
    {
        $classesStr = $this->_cssClassArray;
        $classesStr = apply_filters('wpdatatables_filter_table_cssClassArray', $classesStr, $this->getWpId());
        return $classesStr;
    }

    public function getCSSClasses()
    {
        return implode(' ', $this->_cssClassArray);
    }

    public function addCSSClass($cssClass)
    {
        $this->_cssClassArray[] = $cssClass;
    }

    public function getCSSStyle()
    {
        return $this->_style;
    }

    public function setCSSStyle($style)
    {
        $this->_style = $style;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getName()
    {
        return $this->_title;
    }

    public function setScrollable($scrollable)
    {
        if ($scrollable) {
            $this->_scrollable = true;
        } else {
            $this->_scrollable = false;
        }
    }

    public function isScrollable()
    {
        return $this->_scrollable;
    }

    public function setInterfaceLanguage($lang)
    {
        if (empty($lang)) {
            throw new WDTException('Incorrect language parameter!');
        }
        if (!file_exists(WDT_ROOT_PATH . 'source/lang/' . $lang)) {
            throw new WDTException('Language file not found');
        }
        $this->_interfaceLanguage = WDT_ROOT_PATH . 'source/lang/' . $lang;
    }

    public function getInterfaceLanguage()
    {
        return $this->_interfaceLanguage;
    }

    public function setAutoRefresh($refresh_interval)
    {
        $this->_autoRefreshInterval = (int)$refresh_interval;
    }

    public function getRefreshInterval()
    {
        return (int)$this->_autoRefreshInterval;
    }

    public function paginationEnabled()
    {
        return $this->_pagination;
    }

    public function enablePagination()
    {
        $this->_pagination = true;
    }

    public function disablePagination()
    {
        $this->_pagination = false;
    }

    public function enableTT()
    {
        $this->_showTT = true;
    }

    public function disableTT()
    {
        $this->_showTT = false;
    }

    public function TTEnabled()
    {
        return $this->_showTT;
    }

    public function hideToolbar()
    {
        $this->_toolbar = false;
    }

    public function setDefaultSortColumn($key)
    {
        if (!isset($this->_wdtIndexedColumns[$key])
            && !isset($this->_wdtNamedColumns[$key])
        ) {
            throw new WDTException('Incorrect column index');
        }

        if (!is_numeric($key)) {
            $key = array_search($key, array_keys($this->_wdtNamedColumns));
        }
        $this->_defaultSortColumn = $key;
    }

    public function getDefaultSortColumn()
    {
        return $this->_defaultSortColumn;
    }

    public function setDefaultSortDirection($direction)
    {
        if (
        !in_array(
            $direction,
            array(
                'ASC',
                'DESC'
            )
        )
        ) {
            return false;
        }
        $this->_defaultSortDirection = $direction;
    }

    public function getDefaultSortDirection()
    {
        return $this->_defaultSortDirection;
    }

    public function hideBeforeLoad()
    {
        $this->setCSSStyle('display: none; ');
        $this->_hide_before_load = true;
    }

    public function showBeforeLoad()
    {
        $this->_hide_before_load = false;
    }

    public function doHideBeforeLoad()
    {
        return $this->_hide_before_load;
    }

    public function getDisplayLength()
    {
        return $this->_lengthDisplay;
    }

    public function setDisplayLength($length)
    {
        if (!in_array($length, array(1, 5, 10, 20, 25, 30, 50, 100, 200, -1))) {
            return false;
        }
        $this->_lengthDisplay = $length;
    }

    public function setIdColumnKey($key)
    {
        $this->_idColumnKey = $key;
    }

    public function getIdColumnKey()
    {
        return $this->_idColumnKey;
    }

    /**
     * @return boolean
     */
    public function isInfoBlock()
    {
        return $this->_infoBlock;
    }

    /**
     * @param boolean $infoBlock
     */
    public function setInfoBlock($infoBlock)
    {
        $this->_infoBlock = (bool)$infoBlock;
    }

    /**
     * @return boolean
     */
    public function isGlobalSearch()
    {
        return $this->_globalSearch;
    }

    /**
     * @param boolean $globalSearch
     */
    public function setGlobalSearch($globalSearch)
    {
        $this->_globalSearch = (bool)$globalSearch;
    }

    /**
     * @return boolean
     */
    public function isShowRowsPerPage()
    {
        return $this->_showRowsPerPage;
    }

    /**
     * @param boolean $showRowsPerPage
     */
    public function setShowRowsPerPage($showRowsPerPage)
    {
        $this->_showRowsPerPage = (bool)$showRowsPerPage;
    }

    public function __construct()
    {

        if (self::$wdt_internal_idcount == 0) {
            $this->_firstOnPage = true;
        }
        self::$wdt_internal_idcount++;
        $this->_id = 'table_' . self::$wdt_internal_idcount;
    }

    public function wdtDefineColumnsWidth($widthsArray)
    {
        if (empty($this->_wdtIndexedColumns)) {
            throw new WDTException('wpDataTable reports no columns are defined!');
        }
        if (!is_array($widthsArray)) {
            throw new WDTException('Incorrect parameter passed!');
        }
        if (wdtTools::isArrayAssoc($widthsArray)) {
            foreach ($widthsArray as $name => $value) {
                if (!isset($this->_wdtNamedColumns[$name])) {
                    continue;
                }
                $this->_wdtNamedColumns[$name]->setWidth($value);
            }
        } else {
            // if width is provided in indexed array
            foreach ($widthsArray as $name => $value) {
                $this->_wdtIndexedColumns[$name]->setWidth($value);
            }
        }
    }

    public function setColumnsPossibleValues($valuesArray)
    {
        if (empty($this->_wdtIndexedColumns)) {
            throw new WDTException('No columns in the table!');
        }
        if (!is_array($valuesArray)) {
            throw new WDTException('Valid array of width values is required!');
        }
        if (WDTTools::isArrayAssoc($valuesArray)) {
            foreach ($valuesArray as $key => $value) {
                if (!isset($this->_wdtNamedColumns[$key])) {
                    continue;
                }
                $possibleValues = $this->_wdtNamedColumns[$key]->getPossibleValues();
                if (empty($possibleValues)) {
                    $this->_wdtNamedColumns[$key]->setPossibleValues($value);
                }
            }
        } else {
            foreach ($valuesArray as $key => $value) {
                $this->_wdtIndexedColumns[$key]->setPossibleValues($value);
            }
        }
    }

    public function getHiddenColumnCount()
    {
        $count = 0;
        foreach ($this->_wdtIndexedColumns as $dataColumn) {
            if (!$dataColumn->isVisible()) {
                $count++;
            }
        }
        return $count;
    }


    public function enableGrouping()
    {
        $this->_groupingEnabled = true;
    }

    public function disableGrouping()
    {
        $this->_groupingEnabled = false;
    }

    public function groupingEnabled()
    {
        return $this->_groupingEnabled;
    }

    public function groupByColumn($key)
    {
        if (!isset($this->_wdtIndexedColumns[$key])
            && !isset($this->_wdtNamedColumns[$key])
        ) {
            throw new WDTException('Column not found!');
        }

        if (!is_numeric($key)) {
            $key = array_search(
                $key,
                array_keys($this->_wdtNamedColumns)
            );
        }

        $this->enableGrouping();
        $this->_wdtColumnGroupIndex = $key;
    }

    /**
     * Returns the index of grouping column
     */
    public function groupingColumnIndex()
    {
        return $this->_wdtColumnGroupIndex;
    }

    /**
     * Returns the grouping column index
     */
    public function groupingColumn()
    {
        return $this->_wdtColumnGroupIndex;
    }

    public function countColumns()
    {
        return count($this->_wdtIndexedColumns);
    }

    public function getColumnKeys()
    {
        return array_keys($this->_wdtNamedColumns);
    }

    public function setOnlyOwnRows($ownRows)
    {
        $this->_onlyOwnRows = (bool)$ownRows;
    }

    public function getOnlyOwnRows()
    {
        return $this->_onlyOwnRows;
    }

    public function setUserIdColumn($column)
    {
        $this->_userIdColumn = $column;
    }

    public function getUserIdColumn()
    {
        return $this->_userIdColumn;
    }

    public function getColumns()
    {
        return $this->_wdtIndexedColumns;
    }

    public function getColumnsByHeaders()
    {
        return $this->_wdtNamedColumns;
    }

    public function addConditionalFormattingColumn($column)
    {
        $this->_conditionalFormattingColumns[] = $column;
    }

    public function getConditionalFormattingColumns()
    {
        return $this->_conditionalFormattingColumns;
    }

    public function createColumnsFromArr($headerArr, $wdtParameters, $wdtColumnTypes)
    {
        foreach ($headerArr as $key) {
            $dataColumnProperties = array();
            $dataColumnProperties['title'] = isset($wdtParameters['columnTitles'][$key]) ? $wdtParameters['columnTitles'][$key] : $key;
            $dataColumnProperties['width'] = !empty($wdtParameters['columnWidths'][$key]) ? $wdtParameters['columnWidths'][$key] : '';
            $dataColumnProperties['sorting'] = isset($wdtParameters['sorting'][$key]) ? $wdtParameters['sorting'][$key] : true;
            $dataColumnProperties['decimalPlaces'] = isset($wdtParameters['decimalPlaces'][$key]) ? $wdtParameters['decimalPlaces'][$key] : get_option('wdtDecimalPlaces');
            $dataColumnProperties['orig_header'] = $key;
            $dataColumnProperties['exactFiltering'] = !empty($wdtParameters['exactFiltering'][$key]) ? $wdtParameters['exactFiltering'][$key] : false;
            $dataColumnProperties['filterLabel'] = isset($wdtParameters['filterLabel'][$key]) ? $wdtParameters['filterLabel'][$key] : null;
            $dataColumnProperties['filterDefaultValue'] = isset($wdtParameters['filterDefaultValue'][$key]) ? $wdtParameters['filterDefaultValue'][$key] : null;
            $dataColumnProperties['possibleValuesType'] = !empty($wdtParameters['possibleValuesType'][$key]) ? $wdtParameters['possibleValuesType'][$key] : 'read';
            $dataColumnProperties['possibleValuesAddEmpty'] = !empty($wdtParameters['possibleValuesAddEmpty'][$key]) ? $wdtParameters['possibleValuesAddEmpty'][$key] : false;
            $dataColumnProperties['foreignKeyRule'] = isset($wdtParameters['foreignKeyRule'][$key]) ? $wdtParameters['foreignKeyRule'][$key] : '';
            $dataColumnProperties['editingDefaultValue'] = isset($wdtParameters['editingDefaultValue'][$key]) ? $wdtParameters['editingDefaultValue'][$key] : '';
            $dataColumnProperties['linkTargetAttribute'] = isset($wdtParameters['linkTargetAttribute'][$key]) ? $wdtParameters['linkTargetAttribute'][$key] : '';
            $dataColumnProperties['linkButtonAttribute'] = isset($wdtParameters['linkButtonAttribute'][$key]) ? $wdtParameters['linkButtonAttribute'][$key] : false;
            $dataColumnProperties['linkButtonLabel'] = isset($wdtParameters['linkButtonLabel'][$key]) ? $wdtParameters['linkButtonLabel'][$key] : '';
            $dataColumnProperties['linkButtonClass'] = isset($wdtParameters['linkButtonClass'][$key]) ? $wdtParameters['linkButtonClass'][$key] : '';
            $dataColumnProperties['parentTable'] = $this;

            /** @var WDTColumn $tableColumnClass */
            $tableColumnClass = static::$_columnClass;

            if (isset($wdtColumnTypes[$key])) {
                /** @var WDTColumn $dataColumn */
                $dataColumn = $tableColumnClass::generateColumn($wdtColumnTypes[$key], $dataColumnProperties);

                if ($wdtColumnTypes[$key] === 'formula') {
                    /** @var FormulaWDTColumn $dataColumn */
                    if (!empty($wdtParameters['columnFormulas'][$key])) {
                        $dataColumn->setFormula($wdtParameters['columnFormulas'][$key]);
                        if ($this->serverSide()) {
                            $dataColumn->setSorting(false);
                            $dataColumn->setSearchable(false);
                        }
                    } else {
                        $dataColumn->setFormula('');
                    }
                }
            }

            if ($dataColumn && $dataColumn->getPossibleValuesType() == 'foreignkey' && $dataColumn->getForeignKeyRule() != null) {
                $foreignKeyData = $this->joinWithForeignWpDataTable($dataColumn->getOriginalHeader(), $dataColumn->getForeignKeyRule(), $this->getDataRows());
                $this->_dataRows = $foreignKeyData['dataRows'];
                $dataColumn->setPossibleValues($foreignKeyData['distinctValues']);
            }

            $this->_wdtIndexedColumns[] = $dataColumn;
            $this->_wdtNamedColumns[$key] = &$this->_wdtIndexedColumns[count($this->_wdtIndexedColumns) - 1];
        }

    }

    public function getColumnHeaderOffset($key)
    {
        $keys = $this->getColumnKeys();
        if (!empty($key) && in_array($key, $keys)) {
            return array_search($key, $keys);
        } else {
            return -1;
        }
    }

    public function getColumnDefinitions()
    {
        $defs = array();
        foreach ($this->_wdtIndexedColumns as $key => &$dataColumn) {
            $def = $dataColumn->getColumnJSON($key);
            $def->aTargets = array($key);
            $defs[] = json_encode($def);
        }
        return implode(', ', $defs);
    }

    /**
     * Get column filter definitions
     *
     * @return string
     */
    public function getColumnFilterDefinitions()
    {
        $columnDefinitions = array();
        foreach ($this->_wdtIndexedColumns as $key => $dataColumn) {

            /** @var WDTColumn $dataColumn */
            $columnDefinition = $dataColumn->getJSFilterDefinition();

            if ($this->getFilteringForm()) {
                $columnDefinition->sSelector = '#' . $this->getId() . '_' . $key . '_filter';
            }

            $columnDefinitions[] = json_encode($columnDefinition);
        }
        return implode(', ', $columnDefinitions);
    }

    /**
     * Get column editing definitions
     *
     * @return string
     */
    public function getColumnEditingDefinitions()
    {
        $columnDefinitions = array();
        foreach ($this->_wdtIndexedColumns as $key => $dataColumn) {

            /** @var WDTColumn $dataColumn */
            $columnDefinition = $dataColumn->getJSEditingDefinition();

            $columnDefinitions[] = json_encode($columnDefinition);
        }
        return implode(', ', $columnDefinitions);
    }

    /**
     * Get WDTColumn by column original header
     *
     * @param $originalHeader
     * @return bool|mixed
     */
    public function getColumn($originalHeader)
    {
        if (!isset($originalHeader)
            || (!isset($this->_wdtNamedColumns[$originalHeader])
                && !isset($this->_wdtIndexedColumns[$originalHeader]))
        ) {
            return false;
        }
        if (!is_int($originalHeader)) {
            return $this->_wdtNamedColumns[$originalHeader];
        }

        return $this->_wdtIndexedColumns[$originalHeader];
    }

    /**
     * Generates the structure in memory needed to render the tables
     *
     * @param array $rawDataArr Array of data for the table content
     * @param array $wdtParameters Array of rendering parameters
     * @return bool Result of generation
     */
    public function arrayBasedConstruct($rawDataArr, $wdtParameters)
    {

        if (empty($rawDataArr)) {
            if (!isset($wdtParameters['data_types'])) {
                $rawDataArr = array(0 => array('No data' => 'No data'));
            } else {
                $arrayEntry = array();
                foreach ($wdtParameters['data_types'] as $cKey => $cType) {
                    $arrayEntry[$cKey] = $cKey;
                }
                $rawDataArr[] = $arrayEntry;
            }
            $this->setNoData(true);
        }

        $headerArr = WDTTools::extractHeaders($rawDataArr);

        if (!empty($wdtParameters['columnTitles'])) {
            $headerArr = array_unique(
                array_merge(
                    $headerArr,
                    array_keys($wdtParameters['columnTitles'])
                )
            );
        }

        $wdtColumnTypes = isset($wdtParameters['data_types']) ? $wdtParameters['data_types'] : array();

        if (empty($wdtColumnTypes)) {
            $wdtColumnTypes = WDTTools::detectColumnDataTypes($rawDataArr, $headerArr);
        }

        if (empty($wdtColumnTypes)) {
            foreach ($headerArr as $key) {
                $wdtColumnTypes[$key] = 'string';
            }
        }

        $this->_wdtColumnTypes = $wdtColumnTypes;

        if (!$this->getNoData()) {
            $this->_dataRows = $rawDataArr;
        }

        $this->createColumnsFromArr($headerArr, $wdtParameters, $wdtColumnTypes);

        if (empty($wdtParameters['dates_detected'])
            && count(array_intersect(array('date', 'datetime', 'time'), $wdtColumnTypes))
        ) {
            foreach ($wdtColumnTypes as $key => $columnType) {
                $currentDateFormat = isset($wdtParameters['dateInputFormat'][$key]) ? $wdtParameters['dateInputFormat'][$key] : null;
                if (in_array($columnType, array('date', 'datetime', 'time'))) {
                    foreach ($this->_dataRows as &$dataRow) {
                        $dataRow[$key] = WDTTools::wdtConvertStringToUnixTimestamp($dataRow[$key], $currentDateFormat);
                    }
                }
            }
        }

        if (!in_array($wdtParameters['tableType'], array('mysql', 'manual')) && count(array_intersect(array('float', 'int'), $wdtColumnTypes))) {
            $numberFormat = get_option('wdtNumberFormat') ? get_option('wdtNumberFormat') : 1;
            foreach ($wdtColumnTypes as $key => $columnType) {
                if ($columnType === 'float') {
                    foreach ($this->_dataRows as &$dataRow) {
                        if ($numberFormat == 1) {
                            $dataRow[$key] = str_replace(',', '.', str_replace('.', '', $dataRow[$key]));
                        } else {
                            $dataRow[$key] = str_replace(',', '', $dataRow[$key]);
                        }
                    }
                }
                if ($columnType === 'int') {
                    foreach ($this->_dataRows as &$dataRow) {
                        if ($numberFormat == 1) {
                            $dataRow[$key] = str_replace('.', '', $dataRow[$key]);
                        } else {
                            $dataRow[$key] = str_replace(',', '', $dataRow[$key]);
                        }
                    }
                }
            }
        }


        return true;

    }


    public function hideColumn($dataColumnIndex)
    {
        if (!isset($dataColumnIndex)
            || !isset($this->_wdtNamedColumns[$dataColumnIndex])
        ) {
            throw new WDTException('A column with provided header does not exist.');
        }
        $this->_wdtNamedColumns[$dataColumnIndex]->setIsVisible(false);
    }

    public function showColumn($dataColumnIndex)
    {
        if (!isset($dataColumnIndex)
            || !isset($this->_wdtNamedColumns[$dataColumnIndex])
        ) {
            throw new WDTException('A column with provided header does not exist.');
        }
        $this->_wdtNamedColumns[$dataColumnIndex]->setIsVisible(true);
    }


    public function getCell($dataColumnIndex, $rowKey)
    {
        if (!isset($dataColumnIndex)
            || !isset($rowKey)
        ) {
            throw new WDTException('Please provide the column key and the row key');
        }
        if (!isset($this->_dataRows[$rowKey])) {
            throw new WDTException('Row does not exist.');
        }
        if (!isset($this->_wdtNamedColumns[$dataColumnIndex])
            && !isset($this->_wdtIndexedColumns[$dataColumnIndex])
        ) {
            throw new WDTException('Column does not exist.');
        }
        return $this->_dataRows[$rowKey][$dataColumnIndex];
    }

    public function returnCellValue($cellContent, $wdtColumnIndex)
    {
        if (!isset($wdtColumnIndex)) {
            throw new WDTException('Column index not provided!');
        }
        if (!isset($this->_wdtNamedColumns[$wdtColumnIndex])) {
            throw new WDTException('Column index out of bounds!');
        }
        return $this->_wdtNamedColumns[$wdtColumnIndex]->returnCellValue($cellContent);
    }

    public function getDataRows()
    {
        return $this->_dataRows;
    }

    public function getDataRowsFormatted()
    {
        $dataRowsFormatted = array();
        foreach ($this->_dataRows as $dataRow) {
            $formattedRow = array();
            foreach ($dataRow as $colHeader => $cellValue) {
                $formattedRow[$colHeader] = $this->returnCellValue($cellValue, $colHeader);
            }
            $dataRowsFormatted[] = $formattedRow;
        }
        return $dataRowsFormatted;
    }

    public function getRow($index)
    {
        if (!isset($index) || !isset($this->_dataRows[$index])) {
            throw new WDTException('Invalid row index!');
        }
        $rowArray = &$this->_dataRows[$index];
        apply_filters('wdt_get_row', $rowArray);
        return $rowArray;
    }

    public function addDataColumn(&$dataColumn)
    {
        if (!($dataColumn instanceof WDTColumn)) {
            throw new WDTException('Please provide a wpDataTable column.');
        }
        apply_filters('wdt_add_column', $dataColumn);
        $this->_wdtIndexedColumns[] = &$dataColumn;
        return true;
    }

    public function addColumns(&$dataColumns)
    {
        if (!is_array($dataColumns)) {
            throw new WDTException('Please provide an array of wpDataTable column objects.');
        }
        apply_filters('wdt_add_columns', $dataColumns);
        foreach ($dataColumns as &$dataColumn) {
            $this->addDataColumn($dataColumn);
        }
    }

    /**
     * Helper method to calculate value for the specified column and function
     *
     * @param $columnKey
     * @param $function
     * @return float|int
     */
    public function calcColumnFunction($columnKey, $function)
    {
        $result = null;
        if ($function == 'sum' || $function == 'avg') {
            foreach ($this->getDataRows() as $wdtRowDataArr) {
                $result += $wdtRowDataArr[$columnKey];
            }

            if ($function == 'avg') {
                $result = $result / count($this->getDataRows());

                require_once(WDT_ROOT_PATH . 'source/class.float.wpdatacolumn.php');
                $floatCol = new FloatWDTColumn();
                $floatCol->setParentTable($this);
                return $floatCol->prepareCellOutput($result);
            }

        } else if ($function == 'min') {
            foreach ($this->getDataRows() as $wdtRowDataArr) {
                if (!isset($result) || $wdtRowDataArr[$columnKey] < $result) {
                    $result = $wdtRowDataArr[$columnKey];
                }
            }
        } else if ($function == 'max') {
            foreach ($this->getDataRows() as $wdtRowDataArr) {
                if (!isset($result) || $wdtRowDataArr[$columnKey] > $result) {
                    $result = $wdtRowDataArr[$columnKey];
                }
            }
        }

        return $this->returnCellValue($result, $columnKey);

    }

    /**
     * Helper method to generate values for SUM, MIN, MAX, AVG
     */
    private function calcColumnsAggregateFuncs()
    {
        if (empty($this->_aggregateFuncsRes)) {
            $this->_aggregateFuncsRes = array(
                'sum' => array(),
                'avg' => array(),
                'min' => array(),
                'max' => array()
            );
        }
        foreach ($this->getColumnKeys() as $columnKey) {
            if (
            in_array(
                $columnKey,
                array_unique(
                    array_merge(
                        $this->getSumColumns(),
                        $this->getAvgColumns(),
                        $this->getMinColumns(),
                        $this->getMaxColumns()
                    )
                )
            )
            )
                foreach ($this->getDataRows() as $wdtRowDataArr) {
                    if (
                    in_array(
                        $columnKey,
                        array_unique(
                            array_merge(
                                $this->getSumColumns(),
                                $this->getAvgColumns()
                            )

                        )
                    )
                    ) {
                        if (!isset($this->_aggregateFuncsRes['sum'][$columnKey])) {
                            $this->_aggregateFuncsRes['sum'][$columnKey] = 0;
                        }

                        $this->_aggregateFuncsRes['sum'][$columnKey] += $wdtRowDataArr[$columnKey];
                    }
                    if (
                    in_array(
                        $columnKey,
                        $this->getMinColumns()
                    )
                    ) {
                        if (
                            !isset($this->_aggregateFuncsRes['min'][$columnKey])
                            || ($wdtRowDataArr[$columnKey] < $this->_aggregateFuncsRes['min'][$columnKey])
                        ) {
                            $this->_aggregateFuncsRes['min'][$columnKey] = $wdtRowDataArr[$columnKey];
                        }
                    }

                    if (
                    in_array(
                        $columnKey,
                        $this->getMaxColumns()
                    )
                    ) {
                        if (
                            !isset($this->_aggregateFuncsRes['max'][$columnKey])
                            || ($wdtRowDataArr[$columnKey] > $this->_aggregateFuncsRes['max'][$columnKey])
                        ) {
                            $this->_aggregateFuncsRes['max'][$columnKey] = $wdtRowDataArr[$columnKey];
                        }
                    }
                }

            if (in_array($columnKey, $this->getAvgColumns())) {
                $this->_aggregateFuncsRes['avg'][$columnKey] = $this->_aggregateFuncsRes['sum'][$columnKey] / count($this->getDataRows());
            }
        }
    }

    /**
     * Return aggregate function results
     *
     * @param $columnKey
     * @param $function
     * @return mixed
     */
    public function getColumnsAggregateFuncsResult($columnKey, $function)
    {
        if (!isset($this->_aggregateFuncsRes[$function][$columnKey])) {
            $this->calcColumnsAggregateFuncs();
        }
        return $this->_aggregateFuncsRes[$function][$columnKey];
    }


    /**
     * Formatting row data structure for ajax display table
     * @param $row - key => value pairs as column name and cell value of a row
     * @return array
     */
    protected function formatAjaxQueryResultRow($row)
    {
        return array_values($row);
    }


    public function jsonBasedConstruct($json, $wdtParameters = array())
    {
        $json = WDTTools::applyPlaceholders($json);
        $json = WDTTools::curlGetData($json);
        $json = apply_filters('wpdatatables_filter_json', $json, $this->getWpId());
        return $this->arrayBasedConstruct(json_decode($json, true), $wdtParameters);
    }

    public function XMLBasedConstruct($xml, $wdtParameters = array())
    {
        if (!$xml) {
            throw new WDTException('File you provided cannot be found.');
        }
        if (strpos($xml, '.xml') === false) {
            throw new WDTException('Non-XML file provided!');
        }
        $xml = WDTTools::applyPlaceholders($xml);
        $XMLObject = simplexml_load_file($xml);
        $XMLObject = apply_filters('wpdatatables_filter_simplexml', $XMLObject, $this->getWpId());
        $XMLArray = WDTTools::convertXMLtoArr($XMLObject);
        foreach ($XMLArray as &$xml_el) {
            if (is_array($xml_el) && array_key_exists('attributes', $xml_el)) {
                $xml_el = $xml_el['attributes'];
            }
        }
        return $this->arrayBasedConstruct($XMLArray, $wdtParameters);
    }

    public function excelBasedConstruct($xls_url, $wdtParameters = array())
    {
        ini_set('memory_limit', '2048M');
        if (!$xls_url) {
            throw new WDTException('Excel file not found!');
        }
        if (!file_exists($xls_url)) {
            throw new WDTException('Provided file ' . stripcslashes($xls_url) . ' does not exist!');
        }
        require_once WDT_ROOT_PATH . '/lib/phpExcel/PHPExcel.php';
        $format = 'xls';
        if (strpos(strtolower($xls_url), '.xlsx')) {
            $objReader = new PHPExcel_Reader_Excel2007();
        } elseif (strpos(strtolower($xls_url), '.xls')) {
            $objReader = new PHPExcel_Reader_Excel5();
        } elseif (strpos(strtolower($xls_url), '.ods')) {
            $format = 'ods';
            $objReader = new PHPExcel_Reader_OOCalc();
        } elseif (strpos(strtolower($xls_url), '.csv')) {
            $format = 'csv';
            $objReader = new PHPExcel_Reader_CSV();
            $csvDelimiter = stripcslashes(get_option('wdtCSVDelimiter')) ? stripcslashes(get_option('wdtCSVDelimiter')) : WDTTools::detectCSVDelimiter($xls_url);
            $objReader->setDelimiter($csvDelimiter);
        } else {
            throw new WDTException('File format not supported!');
        }

        $objPHPExcel = $objReader->load($xls_url);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestDataColumn();

        $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
        $headingsArray = array_map('trim', $headingsArray[1]);

        $r = -1;
        $namedDataArray = array();
        $dataRows = $objWorksheet->rangeToArray('A2:' . $highestColumn . $highestRow, null, true, true, true);
        for ($row = 2; $row <= $highestRow; ++$row) {
            if (max($dataRows[$row]) !== null) {
                ++$r;
                foreach ($headingsArray as $dataColumnIndex => $dataColumnHeading) {
                    $namedDataArray[$r][$dataColumnHeading] = $dataRows[$row][$dataColumnIndex];
                    $currentDateFormat = isset($wdtParameters['dateInputFormat'][$dataColumnHeading]) ? $wdtParameters['dateInputFormat'][$dataColumnHeading] : null;
                    if (!empty($wdtParameters['data_types'][$dataColumnHeading]) && in_array($wdtParameters['data_types'][$dataColumnHeading], array('date', 'datetime', 'time'))) {
                        if ($format === 'xls') {
                            $cell = $objPHPExcel->getActiveSheet()->getCell($dataColumnIndex . '' . $row);
                            if (PHPExcel_Shared_Date::isDateTime($cell) && $cell->getValue() !== null) {
                                $namedDataArray[$r][$dataColumnHeading] = PHPExcel_Shared_Date::ExcelToPHP($cell->getValue());
                            } else {
                                $namedDataArray[$r][$dataColumnHeading] = WDTTools::wdtConvertStringToUnixTimestamp($dataRows[$row][$dataColumnIndex], $currentDateFormat);
                            }
                        } elseif ($format === 'csv') {
                            $namedDataArray[$r][$dataColumnHeading] = WDTTools::wdtConvertStringToUnixTimestamp($dataRows[$row][$dataColumnIndex], $currentDateFormat);
                        }
                    }
                }
            }
        }

        // Let arrayBasedConstruct know that dates have been converted to timestamps
        $wdtParameters['dates_detected'] = true;

        $namedDataArray = apply_filters('wpdatatables_filter_excel_array', $namedDataArray, $this->getWpId(), $xls_url);

        return $this->arrayBasedConstruct($namedDataArray, $wdtParameters);
    }

    /**
     * Helper method that renders the modal
     */
    public static function renderModal()
    {
        include_once WDT_TEMPLATE_PATH . 'frontend/modal.inc.php';
        include_once WDT_TEMPLATE_PATH . 'common/delete_modal.inc.php';
    }

    /**
     * Generates table HTML
     * @return string
     */
    public function generateTable()
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $tableContent = $this->renderWithJSAndStyles();

        ob_start();
        include WDT_TEMPLATE_PATH . 'frontend/wrap_template.inc.php';
        if (!self::$modalRendered) {
            if (!is_admin()) {
                add_action('wp_footer', array('WPDataTable', 'renderModal'));
            }
            self::$modalRendered = true;
        }
        $returnData = ob_get_contents();
        ob_end_clean();

        // Generate the style block
        $returnData .= "<style>\n";
        // Columns text before and after
        $returnData .= $this->getColumnsCSS();

        // Table layout
        $customCss = get_option('wdtCustomCss');

        $returnData .= $this->isFixedLayout() ? "table.wpDataTable { table-layout: fixed !important; }\n" : '';
        $returnData .= $this->isWordWrap() ? "table.wpDataTable td, table.wpDataTable th { white-space: normal !important; }\n" : '';

        if ($customCss) {
            $returnData .= stripslashes_deep($customCss);
        }
        if (get_option('wdtNumbersAlign')) {
            $returnData .= "table.wpDataTable td.numdata { text-align: right !important; }\n";
        }
        $returnData .= "</style>\n";


        return $returnData;
    }

    /**
     * Function that return table HTML content and
     * enqueue all necessary JS and CSS files
     * @return string
     */
    protected function renderWithJSAndStyles()
    {

        $this->enqueueJSAndStyles();

        $this->addCSSClass('data-t');

        /** @noinspection PhpUnusedLocalVariableInspection */
        $advancedFilterPosition = get_option('wdtRenderFilter');
        /** @noinspection PhpUnusedLocalVariableInspection */
        $wdtSumFunctionsLabel = get_option('wdtSumFunctionsLabel');
        /** @noinspection PhpUnusedLocalVariableInspection */
        $wdtAvgFunctionsLabel = get_option('wdtAvgFunctionsLabel');
        /** @noinspection PhpUnusedLocalVariableInspection */
        $wdtMinFunctionsLabel = get_option('wdtMinFunctionsLabel');
        /** @noinspection PhpUnusedLocalVariableInspection */
        $wdtMaxFunctionsLabel = get_option('wdtMaxFunctionsLabel');

        ob_start();
        include WDT_TEMPLATE_PATH . 'frontend/table_main.inc.php';
        $tableContent = ob_get_contents();
        ob_end_clean();

        return $tableContent;
    }

    /**
     * Function that enqueue all necessary JS and CSS files for wpDataTable
     */
    protected function enqueueJSAndStyles()
    {

        WDTTools::wdtUIKitEnqueue();

        wp_enqueue_script('wdt-common', WDT_ROOT_URL . 'assets/js/wpdatatables/admin/common.js', array(), false, true);
        if (get_option('wdtMinifiedJs')) {
            wp_enqueue_style('wdt-wpdatatables', WDT_CSS_PATH . 'wdt.frontend.min.css');

            wp_enqueue_script('wdt-wpdatatables', WDT_JS_PATH . 'wpdatatables/wdt.frontend.min.js', array('wdt-common'), false, true);
        } else {
            wp_enqueue_style('wdt-wpdatatables', WDT_CSS_PATH . 'wpdatatables.min.css');
            wp_enqueue_style('wdt-table-tools', WDT_CSS_PATH . 'TableTools.css');


            if (WDT_INCLUDE_DATATABLES_CORE) {
                wp_enqueue_script('wdt-datatables', WDT_JS_PATH . 'jquery-datatables/jquery.dataTables.min.js', array(), false, true);
            }


            if ($this->groupingEnabled()) {
                wp_enqueue_script('wdt-row-grouping', WDT_JS_PATH . 'jquery-datatables/jquery.dataTables.rowGrouping.js', array('jquery', 'wdt-datatables'), false, true);
            }


            wp_enqueue_script('wdt-funcs-js', WDT_JS_PATH . 'wpdatatables/wdt.funcs.js', array('jquery', 'wdt-datatables', 'wdt-common'), false, true);
            wp_enqueue_script('wdt-wpdatatables', WDT_JS_PATH . 'wpdatatables/wpdatatables.js', array('jquery', 'wdt-datatables'), false, true);
        }

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
        wp_enqueue_style('wdt-skin', $renderSkin, array(), WDT_CURRENT_VERSION);
        wp_enqueue_style('dashicons');

        wp_enqueue_script('underscore');
        !empty($this->_tableToolsConfig['excel']) ? wp_enqueue_script('wdt-js-zip', WDT_JS_PATH . 'export-tools/jszip.min.js', array('jquery'), false, true) : null;
        !empty($this->_tableToolsConfig['pdf']) ? wp_enqueue_script('wdt-pdf-make', WDT_JS_PATH . 'export-tools/pdfmake.min.js', array('jquery'), false, true) : null;
        !empty($this->_tableToolsConfig['pdf']) ? wp_enqueue_script('wdt-vfs-fonts', WDT_JS_PATH . 'export-tools/vfs_fonts.js', array('jquery'), false, true) : null;


        wp_localize_script('wdt-common', 'wpdatatables_edit_strings', WDTTools::getTranslationStrings());
        wp_localize_script('wdt-wpdatatables', 'wpdatatables_settings', WDTTools::getDateTimeSettings());
        wp_localize_script('wdt-wpdatatables', 'wpdatatables_frontend_strings', WDTTools::getTranslationStrings());
        wp_localize_script('wdt-advanced-filter', 'wpdatatables_frontend_strings', WDTTools::getTranslationStrings());
    }

    /**
     * * Helper method which prepares the column data from values stored in DB
     * @param $tableData
     * @return array
     */
    public function prepareColumnData($tableData)
    {

        $returnArray = array(
            'dateInputFormat' => array(),
            'columnFormulas' => array(),
            'columnOrder' => array(),
            'columnTitles' => array(),
            'columnTypes' => array(),
            'columnWidths' => array(),
            'decimalPlaces' => array(),
            'editingDefaultValue' => array(),
            'exactFiltering' => array(),
            'filterDefaultValue' => array(),
            'filterLabel' => array(),
            'foreignKeyRule' => array(),
            'possibleValues' => array(),
            'possibleValuesAddEmpty' => array(),
            'possibleValuesType' => array(),
            'sorting' => array(),
            'userIdColumnHeader' => NULL,
            'linkTargetAttribute' => array(),
            'linkButtonAttribute' => array(),
            'linkButtonLabel' => array(),
            'linkButtonClass' => array()
        );

        if ($tableData) {
            foreach ($tableData->columns as $column) {
                $returnArray['columnOrder'][(int)$column->pos] = $column->orig_header;
                if ($column->display_header) {
                    $returnArray['columnTitles'][$column->orig_header] = $column->display_header;
                }
                if ($column->width) {
                    $returnArray['columnWidths'][$column->orig_header] = $column->width;
                }
                if ($column->type != 'autodetect') {
                    $returnArray['columnTypes'][$column->orig_header] = $column->type;
                }
                if ($column->type == 'formula') {
                    $returnArray['columnFormulas'][$column->orig_header] = $column->formula;
                }
                if ($tableData->edit_only_own_rows && $tableData->userid_column_id == $column->id) {
                    $returnArray['userIdColumnHeader'] = $column->orig_header;
                }
                if ($column->filterDefaultValue) {
                    $returnArray['filterDefaultValue'][$column->orig_header] = $column->filterDefaultValue;
                }

                $returnArray['dateInputFormat'][$column->orig_header] = isset($column->dateInputFormat) ? $column->dateInputFormat : null;
                $returnArray['decimalPlaces'][$column->orig_header] = isset($column->decimalPlaces) ? $column->decimalPlaces : null;
                $returnArray['editingDefaultValue'][$column->orig_header] = isset($column->editingDefaultValue) ? $column->editingDefaultValue : null;
                $returnArray['exactFiltering'][$column->orig_header] = isset($column->exactFiltering) ? $column->exactFiltering : null;
                $returnArray['filterLabel'][$column->orig_header] = isset($column->filterLabel) ? $column->filterLabel : null;
                $returnArray['foreignKeyRule'][$column->orig_header] = isset($column->foreignKeyRule) ? $column->foreignKeyRule : null;
                $returnArray['possibleValues'][$column->orig_header] = isset($column->valuesList) ? $column->valuesList : null;
                $returnArray['possibleValuesAddEmpty'][$column->orig_header] = isset($column->possibleValuesAddEmpty) ? $column->possibleValuesAddEmpty : null;
                $returnArray['possibleValuesType'][$column->orig_header] = isset($column->possibleValuesType) ? $column->possibleValuesType : null;
                $returnArray['sorting'][$column->orig_header] = isset($column->sorting) ? $column->sorting : null;
                $returnArray['linkTargetAttribute'][$column->orig_header] = isset($column->linkTargetAttribute) ? $column->linkTargetAttribute : null;
                $returnArray['linkButtonAttribute'][$column->orig_header] = isset($column->linkButtonAttribute) ? $column->linkButtonAttribute : null;
                $returnArray['linkButtonLabel'][$column->orig_header] = isset($column->linkButtonLabel) ? $column->linkButtonLabel : null;
                $returnArray['linkButtonClass'][$column->orig_header] = isset($column->linkButtonClass) ? $column->linkButtonClass : null;

            }
        }
        return $returnArray;
    }


    /**
     * Helper method which populates the wpdatatables object with passed in parameters and data (stored in DB)
     *
     * @param $tableData
     * @param $columnData
     */
    public function fillFromData($tableData, $columnData)
    {
        if (empty($tableData->table_type)) {
            return;
        }
        global $wdtVar1, $wdtVar2, $wdtVar3;

        // Set placeholders
        $wdtVar1 = $wdtVar1 === '' ? $tableData->var1 : $wdtVar1;
        $wdtVar2 = $wdtVar2 === '' ? $tableData->var2 : $wdtVar2;
        $wdtVar3 = $wdtVar3 === '' ? $tableData->var3 : $wdtVar3;

        // Defining column parameters if provided
        $params = array();
        if (isset($tableData->limit)) {
            $params['limit'] = $tableData->limit;
        }
        if (isset($tableData->table_type)) {
            $params['tableType'] = $tableData->table_type;
        }
        if (isset($columnData['columnTypes'])) {
            $params['data_types'] = $columnData['columnTypes'];
        }
        if (isset($columnData['columnTitles'])) {
            $params['columnTitles'] = $columnData['columnTitles'];
        }
        if (isset($columnData['columnFormulas'])) {
            $params['columnFormulas'] = $columnData['columnFormulas'];
        }
        if (isset($columnData['sorting'])) {
            $params['sorting'] = $columnData['sorting'];
        }
        if (isset($columnData['decimalPlaces'])) {
            $params['decimalPlaces'] = $columnData['decimalPlaces'];
        }
        if (isset($columnData['exactFiltering'])) {
            $params['exactFiltering'] = $columnData['exactFiltering'];
        }
        if (isset($columnData['filterDefaultValue'])) {
            $params['filterDefaultValue'] = $columnData['filterDefaultValue'];
        }
        if (isset($columnData['filterLabel'])) {
            $params['filterLabel'] = $columnData['filterLabel'];
        }
        if (isset($columnData['possibleValuesType'])) {
            $params['possibleValuesType'] = $columnData['possibleValuesType'];
        }
        if (isset($columnData['possibleValuesAddEmpty'])) {
            $params['possibleValuesAddEmpty'] = $columnData['possibleValuesAddEmpty'];
        }
        if (isset($columnData['foreignKeyRule'])) {
            $params['foreignKeyRule'] = $columnData['foreignKeyRule'];
        }
        if (isset($columnData['editingDefaultValue'])) {
            $params['editingDefaultValue'] = $columnData['editingDefaultValue'];
        }
        if (isset($columnData['dateInputFormat'])) {
            $params['dateInputFormat'] = $columnData['dateInputFormat'];
        }
        if (isset($columnData['linkTargetAttribute'])) {
            $params['linkTargetAttribute'] = $columnData['linkTargetAttribute'];
        }
        if (isset($columnData['linkButtonAttribute'])) {
            $params['linkButtonAttribute'] = $columnData['linkButtonAttribute'];
        }
        if (isset($columnData['linkButtonLabel'])) {
            $params['linkButtonLabel'] = $columnData['linkButtonLabel'];
        }
        if (isset($columnData['linkButtonClass'])) {
            $params['linkButtonClass'] = $columnData['linkButtonClass'];
        }

        switch ($tableData->table_type) {

            case 'xls':
            case 'csv':
                $this->excelBasedConstruct(
                    $tableData->content,
                    $params
                );
                break;
            case 'xml':
                $this->XMLBasedConstruct(
                    $tableData->content,
                    $params
                );
                break;
            case 'json':
                $this->jsonBasedConstruct(
                    $tableData->content,
                    $params
                );
                break;
            case 'serialized':
                $serialized_content = apply_filters('wpdatatables_filter_serialized', WDTTools::curlGetData($tableData->content), $this->_wpId);
                $array = unserialize($serialized_content);
                $this->arrayBasedConstruct(
                    $array,
                    $params
                );
                break;


            default:
                // Solution for addons
                if (has_action('wpdatatables_generate_' . $tableData->table_type)) {
                    do_action(
                        'wpdatatables_generate_' . $tableData->table_type,
                        $this,
                        $tableData->content,
                        $params
                    );
                } else {
                    throw new WDTException(__('You are trying to load a table of an unknown type. Probably you did not activate the addon which is required to use this table type.', 'wpdatatables'));
                }
                break;
        }
        if (!empty($tableData->content)) {
            $this->setTableContent($tableData->content);
        }
        if (!empty($tableData->table_type)) {
            $this->setTableType($tableData->table_type);
        }
        if (!empty($tableData->title)) {
            $this->setTitle($tableData->title);
        }
        if (!empty($tableData->hide_before_load)) {
            $this->hideBeforeLoad();
        } else {
            $this->showBeforeLoad();
        }
        if (!empty($tableData->fixed_layout)) {
            $this->setFixedLayout(true);
        }
        if (!empty($tableData->word_wrap)) {
            $this->setWordWrap(true);
        }


        if (!empty($tableData->scrollable)) {
            $this->setScrollable(true);
        }
        if (empty($tableData->sorting)) {
            $this->sortDisable();
        }
        if (empty($tableData->tools)) {
            $this->disableTT();
        } else {
            $this->enableTT();
            if (isset($tableData->tabletools_config)) {
                $this->_tableToolsConfig = $tableData->tabletools_config;
            } else {
                $this->_tableToolsConfig = array(
                    'print' => 1,
                    'copy' => 1,
                    'excel' => 1,
                    'csv' => 1,
                    'pdf' => 0
                );
            }
        }
        if (isset($tableData->display_length) && $tableData->display_length != 0) {
            $this->setDisplayLength($tableData->display_length);
        } else {
            $this->disablePagination();
        }
        if (get_option('wdtInterfaceLanguage') != '') {
            $this->setInterfaceLanguage(get_option('wdtInterfaceLanguage'));
        }

        if (!empty($tableData->advanced_settings)) {
            $this->setInfoBlock(json_decode($tableData->advanced_settings)->info_block);
            $this->setGlobalSearch(json_decode($tableData->advanced_settings)->global_search);
            $this->setShowRowsPerPage(json_decode($tableData->advanced_settings)->showRowsPerPage);
        } else {
            $this->setInfoBlock(true);
            $this->setGlobalSearch(true);
            $this->setShowRowsPerPage(true);
        }

        if (!empty($columnData['columnOrder'])) {
            $this->reorderColumns($columnData['columnOrder']);
        }
        if (!empty($columnData['columnWidths'])) {
            $this->wdtDefineColumnsWidth($columnData['columnWidths']);
        }
        if (!empty($columnData['possibleValues'])) {
            $this->setColumnsPossibleValues($columnData['possibleValues']);
        }
        if (!empty($tableData->columns)) {
            $this->prepareRenderingRules($tableData->columns);
        }

    }

    /**
     * Helper method that prepares the rendering rules
     * @param array $columnData
     */
    public function prepareRenderingRules($columnData)
    {
        $columnIndex = 1;
        // Check the search values passed from URL
        if (isset($_GET['wdt_search'])) {
            $this->setDefaultSearchValue($_GET['wdt_search']);
        }

        // Define all column-dependent rendering rules
        foreach ($columnData as $key => $column) {

            $this->column_id = $key;
            // Set filter types
            $this->getColumn($column->orig_header)->setFilterType($column->filter_type);
            // Set CSS class
            $this->getColumn($column->orig_header)->addCSSClass($column->css_class);
            // Set visibility
            if (!$column->visible) {
                $this->getColumn($column->orig_header)->setIsVisible(false);
            }


            // if grouping enabled for this column, passing it to table class
            if ($column->groupColumn) {
                $this->groupByColumn($column->orig_header);
            }
            if ($column->defaultSortingColumn != '0') {
                $this->setDefaultSortColumn($column->orig_header);
                if ($column->defaultSortingColumn == '1') {
                    $this->setDefaultSortDirection('ASC');
                } elseif ($column->defaultSortingColumn == '2') {
                    $this->setDefaultSortDirection('DESC');
                }
            }
            // If thousands separator is disabled or column is "ID column for editing"
            // pass it to the column class instance
            if ($column->type == 'int') {
                if ($column->skip_thousands_separator || $column->id_column) {
                    $this->getColumn($column->orig_header)->setShowThousandsSeparator(false);
                    $this->addColumnsThousandsSeparator($column->orig_header, 0);
                } else {
                    $this->addColumnsThousandsSeparator($column->orig_header, 1);
                }
            }

            // Set ID column if specified
            if ($column->id_column) {
                $this->setIdColumnKey($column->orig_header);
            }
            // Set front-end editor input type
            $this->getColumn($column->orig_header)
                ->setInputType($column->editor_type);
            // Define if input cannot be empty
            $this->getColumn($column->orig_header)
                ->setNotNull((bool)$column->input_mandatory);

            // Get display before/after and color
            if (sanitize_html_class(strtolower(str_replace(' ', '-', $column->orig_header))) === '') {
                $cssColumnHeader = 'column-' . $this->column_id;
            } else {
                $cssColumnHeader = 'column-' . sanitize_html_class(strtolower(str_replace(' ', '-', $column->orig_header)));
            }
            if ($column->text_before != '') {
                $this->_columnsCSS .= "\n#{$this->getId()} > tbody > tr > td.{$cssColumnHeader}:not(:empty):before,
                                       \n#{$this->getId()} > tbody > tr.row-detail ul li.{$cssColumnHeader} span.columnValue:before
                                            { content: '{$column->text_before}' }";
            }
            if ($column->text_after != '') {
                $this->_columnsCSS .= "\n#{$this->getId()} > tbody > tr > td.{$cssColumnHeader}:not(:empty):after,
                                       \n#{$this->getId()} > tbody > tr.row-detail ul li.{$cssColumnHeader} span.columnValue:after
                                            { content: '{$column->text_after}' }";
            }
            if ($column->color != '') {
                $this->_columnsCSS .= "\n#{$this->getId()} > tbody > tr > td.{$cssColumnHeader}, "
                    . "#{$this->getId()} > tbody > tr.row-detail ul li.{$cssColumnHeader}, "
                    . "#{$this->getId()} > thead > tr > th.{$cssColumnHeader}, "
                    . "#{$this->getId()} > tfoot > tr > th.{$cssColumnHeader} { background-color: {$column->color} !important; }";
            }
            $columnIndex++;
        }

        // Check the default values passed from URL
        if (isset($_GET['wdt_column_filter'])) {
            foreach ($_GET['wdt_column_filter'] as $fltColKey => $fltDefVal) {
                $wdtCol = $this->getColumn($fltColKey);
                if (!empty($wdtCol)) {
                    $this->getColumn($fltColKey)->setFilterDefaultValue($fltDefVal);
                }
            }
        }
    }

    /**
     * Returns JSON object for table description
     */
    public function getJsonDescription()
    {

        global $wdtExportFileName;

        $obj = new stdClass();
        $obj->tableId = $this->getId();
        $obj->selector = '#' . $this->getId();
        $obj->infoBlock = $this->isInfoBlock();
        $obj->globalSearch = $this->isGlobalSearch();
        $obj->showRowsPerPage = $this->isShowRowsPerPage();

        $obj->hideBeforeLoad = $this->doHideBeforeLoad();
        $obj->number_format = (int)(get_option('wdtNumberFormat') ? get_option('wdtNumberFormat') : 1);
        $obj->decimalPlaces = (int)(get_option('wdtDecimalPlaces') ? get_option('wdtDecimalPlaces') : 2);

        $obj->spinnerSrc = WDT_ASSETS_PATH . '/img/spinner.gif';
        $obj->groupingEnabled = $this->groupingEnabled();
        if ($this->groupingEnabled()) {
            $obj->groupingColumnIndex = $this->groupingColumn();
        }
        $obj->tableWpId = $this->getWpId();
        $obj->dataTableParams = new StdClass();

        $infoBlock = ($obj->infoBlock == true) ? 'i' : '';
        $globalSearch = ($obj->globalSearch == true) ? 'f' : '';
        $showRowsPerPage = ($obj->showRowsPerPage == true) ? 'l' : '';
        $scrollable = ($this->isScrollable() == true) ? "<'wdtscroll't>" : 't';
        $obj->dataTableParams->sDom = "BT<'clear'>{$showRowsPerPage}{$globalSearch}{$scrollable}{$infoBlock}p";

        $obj->dataTableParams->bSortCellsTop = false;

        if ($this->paginationEnabled()) {
            $obj->dataTableParams->bPaginate = true;
            $obj->dataTableParams->aLengthMenu = json_decode('[[1,5,10,25,50,100,-1],[1,5,10,25,50,100,"' . __('All', 'wpdatatables') . '"]]');
            $obj->dataTableParams->iDisplayLength = (int)$this->getDisplayLength();
        } else {
            $obj->dataTableParams->bPaginate = false;
            if ($this->groupingEnabled()) {
                $obj->dataTableParams->aaSortingFixed = json_decode('[[' . $this->groupingColumn() . ', "asc"]]');
            }
        }
        if (get_option('wdtTabletWidth')) {
            $obj->tabletWidth = get_option('wdtTabletWidth');
        }
        if (get_option('wdtMobileWidth')) {
            $obj->mobileWidth = get_option('wdtMobileWidth');
        }
        $obj->dataTableParams->columnDefs = json_decode('[' . $this->getColumnDefinitions() . ']');
        $obj->dataTableParams->bAutoWidth = false;

        if (!is_null($this->getDefaultSortColumn())) {
            $obj->dataTableParams->order = json_decode('[[' . $this->getDefaultSortColumn() . ', "' . strtolower($this->getDefaultSortDirection()) . '" ]]');
        } else {
            $orderColumn = '';
            foreach ($obj->dataTableParams->columnDefs as $columnKey => $column) {
                if ($column->orderable === true) {
                    $orderColumn = $columnKey;
                    break;
                }
            }
            $obj->dataTableParams->order = json_decode('[[' . $orderColumn . ' ,"asc"]]');
        }

        if ($this->sortEnabled()) {
            $obj->dataTableParams->ordering = true;
        } else {
            $obj->dataTableParams->ordering = false;
        }

        if ($this->getInterfaceLanguage()) {
            $obj->dataTableParams->oLanguage = json_decode(file_get_contents($this->getInterfaceLanguage()));
        }

        if (empty($wdtExportFileName)) {
            if (!empty($this->_title)) {
                $wdtExportFileName = $this->_title;
            } else {
                $wdtExportFileName = 'wpdt_export';
            }
        }

        $currentSkin = get_option('wdtBaseSkin');
        $skinsWithNewTableToolsButtons = ['aqua', 'purple', 'dark'];

        if ($this->TTEnabled()) {
            (!isset($obj->dataTableParams->buttons)) ? $obj->dataTableParams->buttons = array() : '';
            if (in_array($currentSkin, $skinsWithNewTableToolsButtons)) {

                if (!empty($this->_tableToolsConfig['columns'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'colvis',
                            'className' => 'DTTT_button DTTT_button_colvis',
                            'text' => __('Columns', 'wpdatatables')

                        );
                }
                if (!empty($this->_tableToolsConfig['print'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'print',
                            'exportOptions' => array('columns' => ':visible'),
                            'className' => 'DTTT_button DTTT_button_print',
                            'text' => __('Print', 'wpdatatables')
                        );
                }

                if (!empty($this->_tableToolsConfig['excel'])) {
                    $exportButtons[] =
                        array(
                            'extend' => 'excelHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'title' => $wdtExportFileName,
                            'text' => __('Excel', 'wpdatatables')
                        );
                }
                if (!empty($this->_tableToolsConfig['csv'])) {
                    $exportButtons[] =
                        array(
                            'extend' => 'csvHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'title' => $wdtExportFileName,
                            'text' => __('CSV', 'wpdatatables')
                        );
                }
                if (!empty($this->_tableToolsConfig['copy'])) {
                    $exportButtons[] =
                        array(
                            'extend' => 'copyHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'title' => $wdtExportFileName,
                            'text' => __('Copy', 'wpdatatables')
                        );
                }
                if (!empty($this->_tableToolsConfig['pdf'])) {
                    $exportButtons[] =
                        array(
                            'extend' => 'pdfHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'orientation' => 'portrait',
                            'title' => $wdtExportFileName,
                            'text' => __('PDF', 'wpdatatables')
                        );
                }

                if (!empty($exportButtons)) {
                    $obj->dataTableParams->buttons[] = array(
                        'extend' => 'collection',
                        'className' => 'DTTT_button DTTT_button_export',
                        'text' => __('Export', 'wpdatatables'),
                        'buttons' => $exportButtons
                    );
                }

            } else {

                if (!empty($this->_tableToolsConfig['columns'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'colvis',
                            'className' => 'DTTT_button DTTT_button_colvis',
                            'text' => __('Columns', 'wpdatatables')

                        );
                }
                if (!empty($this->_tableToolsConfig['print'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'print',
                            'exportOptions' => array('columns' => ':visible'),
                            'className' => 'DTTT_button DTTT_button_print',
                            'text' => __('Print', 'wpdatatables')
                        );
                }

                if (!empty($this->_tableToolsConfig['excel'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'excelHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'className' => 'DTTT_button DTTT_button_xls',
                            'title' => $wdtExportFileName,
                            'text' => __('Excel', 'wpdatatables')
                        );
                }
                if (!empty($this->_tableToolsConfig['csv'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'csvHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'className' => 'DTTT_button DTTT_button_csv',
                            'title' => $wdtExportFileName,
                            'text' => __('CSV', 'wpdatatables')
                        );
                }
                if (!empty($this->_tableToolsConfig['copy'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'copyHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'className' => 'DTTT_button DTTT_button_copy',
                            'title' => $wdtExportFileName,
                            'text' => __('Copy', 'wpdatatables')
                        );
                }
                if (!empty($this->_tableToolsConfig['pdf'])) {
                    $obj->dataTableParams->buttons[] =
                        array(
                            'extend' => 'pdfHtml5',
                            'exportOptions' => array('columns' => ':visible'),
                            'className' => 'DTTT_button DTTT_button_pdf',
                            'orientation' => 'portrait',
                            'title' => $wdtExportFileName,
                            'text' => __('PDF', 'wpdatatables')
                        );
                }
            }
        }

        if (in_array($currentSkin, $skinsWithNewTableToolsButtons)) {

            if (!isset($obj->dataTableParams->oLanguage)) {
                $obj->dataTableParams->oLanguage = new stdClass();
            }

            $obj->dataTableParams->oLanguage->sSearch = '<span class="wdt-search-icon"></span>';
            $obj->dataTableParams->oLanguage->sSearchPlaceholder = __('Search table', 'wpdatatables');
            $obj->dataTableParams->oLanguage->sLengthMenu = __('Showing _MENU_ Entries', 'wpdatatables');
        }


        if (!isset($obj->dataTableParams->buttons)) {
            $obj->dataTableParams->buttons = array();
        }


        $obj->dataTableParams->sPaginationType = 'full_numbers';
        $obj->columnsFixed = 0;


        $init_format = get_option('wdtDateFormat');
        $datepick_format = str_replace('d', 'dd', $init_format);
        $datepick_format = str_replace('m', 'mm', $datepick_format);
        $datepick_format = str_replace('Y', 'yy', $datepick_format);

        $obj->timeFormat = get_option('wdtTimeFormat');
        $obj->datepickFormat = $datepick_format;

        $obj->dataTableParams->oSearch = array('bSmart' => false, 'bRegex' => false, 'sSearch' => $this->getDefaultSearchValue());

        $obj = apply_filters('wpdatatables_filter_table_description', $obj, $this->getWpId(), $this);

        return json_encode($obj, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
    }


    /**
     * @param $columnKey
     * @param $foreignKeyRule
     * @param $dataRows
     * @return mixed
     */
    public function joinWithForeignWpDataTable($columnKey, $foreignKeyRule, $dataRows)
    {
        $joinedTable = WPDataTable::loadWpDataTable($foreignKeyRule->tableId);
        $distinctValues = $joinedTable->getDistinctValuesForColumns($foreignKeyRule);
        foreach ($dataRows as &$dataRow) {
            $dataRow[$columnKey] = isset($distinctValues[$dataRow[$columnKey]]) ? $distinctValues[$dataRow[$columnKey]] : $dataRow[$columnKey];
        }

        return array(
            'dataRows' => $dataRows,
            'distinctValues' => $distinctValues
        );

    }

    /**
     * Function that returns related values (ID's and strings) for Foreign Key feature
     * by provided foreign key rule
     * @param $foreignKeyRule stdClass that contains tableId, tableName, displayColumnId, displayColumnName,
     * storeColumnId and storeColumnName
     * @return array
     */
    public function getDistinctValuesForColumns($foreignKeyRule)
    {
        $distinctValues = array();
        $storeColumnName = $foreignKeyRule->storeColumnName;
        $displayColumnName = $foreignKeyRule->displayColumnName;
        $tableType = $this->getTableType();

        if ($tableType == 'mysql' || $tableType == 'manual') {
            $tableContent = $this->getTableContent();

            $distValuesQuery = "SELECT(`$storeColumnName`) AS `$storeColumnName`, (`$displayColumnName`) AS `$displayColumnName` FROM ($tableContent) tbl GROUP BY $storeColumnName ORDER BY $displayColumnName";

            if (!get_option('wdtUseSeparateCon')) {
                global $wpdb;
                $mySqlResult = $wpdb->get_results($distValuesQuery);

                foreach ($mySqlResult as $dataRow) {
                    $distinctValues[$dataRow->$storeColumnName] = $dataRow->$displayColumnName;
                }
            } else {
                $sql = new PDTSql(WDT_MYSQL_HOST, WDT_MYSQL_DB, WDT_MYSQL_USER, WDT_MYSQL_PASSWORD, WDT_MYSQL_PORT);
                $mySqlResult = $sql->getArray($distValuesQuery);

                foreach ($mySqlResult as $dataRow) {
                    $distinctValues[$dataRow[$storeColumnName]] = $dataRow[$displayColumnName];
                }
            }
        } else {
            foreach ($this->getDataRows() as $dataRow) {
                $distinctValues[$dataRow[$storeColumnName]] = $dataRow[$displayColumnName];
            }
        }

        return $distinctValues;
    }


    /**
     * Delete table by ID
     * @param $tableId
     * @return bool
     */
    public static function deleteTable($tableId)
    {
        global $wpdb;

        if (!isset($_REQUEST['wdtNonce']) || empty($tableId) || !current_user_can('manage_options') || !wp_verify_nonce($_REQUEST['wdtNonce'], 'wdtDeleteTableNonce')) {
            return false;
        }

        $table = WDTConfigController::loadTableFromDB($tableId);

        if (!$table) {
            return false;
        }

        if (!empty($table->table_type)) {
            if ($table->table_type == 'manual') {
                if (!get_option('wdtUseSeparateCon')) {
                    $wpdb->query("DROP TABLE {$table->mysql_table_name}");
                } else {
                    $sql = new PDTSql(WDT_MYSQL_HOST, WDT_MYSQL_DB, WDT_MYSQL_USER, WDT_MYSQL_PASSWORD, WDT_MYSQL_PORT);
                    $sql->doQuery("DROP TABLE {$table->mysql_table_name}");
                }
            }
        }

        $wpdb->delete("{$wpdb->prefix}wpdatatables", array('id' => (int)$tableId));
        $wpdb->delete("{$wpdb->prefix}wpdatatables_columns", array('table_id' => (int)$tableId));
        $wpdb->delete("{$wpdb->prefix}wpdatacharts", array('wpdatatable_id' => (int)$tableId));

        return true;
    }

    /**
     * Get all tables
     * @return array|null|object
     */
    public static function getAllTables()
    {
        global $wpdb;
        $query = "SELECT id, title, table_type, server_side FROM {$wpdb->prefix}wpdatatables ORDER BY id";
        $allTables = $wpdb->get_results($query, ARRAY_A);
        return $allTables;
    }

    /**
     * Helper method that load wpDataTable object by given table ID
     * and return array with $wpDataTable object and $tableData object
     * @param $tableId
     * @param null $tableView
     * @param bool $disableLimit
     * @return WPDataTable|WPExcelDataTable|bool
     */
    public static function loadWpDataTable($tableId, $tableView = null, $disableLimit = false)
    {
        $tableData = WDTConfigController::loadTableFromDB($tableId);

        if ($tableData) {
            $tableData->disable_limit = $disableLimit;
        }

        $wpDataTable = $tableView == 'excel' ? new WPExcelDataTable() : new self();
        $wpDataTable->setWpId($tableId);

        $columnDataPrepared = $wpDataTable->prepareColumnData($tableData);

        $wpDataTable->fillFromData($tableData, $columnDataPrepared);

        return $wpDataTable;
    }

}
