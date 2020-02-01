<?php

defined('ABSPATH') or die('Access denied.');

/**
 * Chart engine of wpDataTables plugin
 */
class WPDataChart
{

    private $_id = NULL;
    private $_wpdatatable_id = NULL;
    private $_engine = '';
    private $_type = '';
    private $_range_type = 'all_rows';
    private $_selected_columns = array();
    private $_row_range = array();
    private $_wpdatatable = NULL;
    // Chart
    private $_width = 400;
    private $_height = 400;
    private $_background_color = '#FFFFFF';
    private $_border_width = 0;
    private $_border_color = '#4572A7';
    private $_border_radius = 0;
    private $_plot_background_color = 'undefined';
    private $_plot_border_width = 0;
    private $_plot_border_color = '#C0C0C0';
    // Series
    private $_series = array();
    // Axes
    private $_show_grid = true;
    private $_axes = array(
        'major' => array(
            'label' => ''
        ),
        'minor' => array(
            'label' => ''
        )
    );
    // Title
    private $_title = '';
    private $_show_title = true;
    private $_title_floating = false;

    // Legend
    private $_legend_position = 'bottom';
    private $_legend_vertical_align = 'bottom';

    private $_user_defined_series_data = array();
    private $_render_data = NULL;

    private $_type_counters;

    public function __construct()
    {

    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    // Chart

    public function setWidth($width)
    {
        $this->_width = $width;
    }

    public function getWidth()
    {
        return $this->_width;
    }


    public function setHeight($height)
    {
        $this->_height = $height;
    }

    public function getHeight()
    {
        return $this->_height;
    }


    /**
     * @param $background_color
     */
    public function setBackgroundColor($background_color)
    {
        $this->_background_color = $background_color;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->_background_color;
    }

    /**
     * @param $border_width
     */
    public function setBorderWidth($border_width)
    {
        $this->_border_width = $border_width;
    }

    /**
     * @return int
     */
    public function getBorderWidth()
    {
        return $this->_border_width;
    }

    /**
     * @param $border_color
     */
    public function setBorderColor($border_color)
    {
        $this->_border_color = $border_color;
    }

    /**
     * @return string
     */
    public function getBorderColor()
    {
        return $this->_border_color;
    }

    /**
     * @param $border_radius
     */
    public function setBorderRadius($border_radius)
    {
        $this->_border_radius = $border_radius;
    }

    /**
     * @return int
     */
    public function getBorderRadius()
    {
        return $this->_border_radius;
    }


    /**
     * @param $plot_background_color
     */
    public function setPlotBackgroundColor($plot_background_color)
    {
        $this->_plot_background_color = $plot_background_color;
    }

    /**
     * @return string
     */
    public function getPlotBackgroundColor()
    {
        return $this->_plot_background_color;
    }


    /**
     * @param $plot_border_width
     */
    public function setPlotBorderWidth($plot_border_width)
    {
        $this->_plot_border_width = $plot_border_width;
    }

    /**
     * @return int
     */
    public function getPlotBorderWidth()
    {
        return $this->_plot_border_width;
    }

    /**
     * @param $plot_border_color
     */
    public function setPlotBorderColor($plot_border_color)
    {
        $this->_plot_border_color = $plot_border_color;
    }

    /**
     * @return string
     */
    public function getPlotBorderColor()
    {
        return $this->_plot_border_color;
    }




    // Axes

    /**
     * @param $show_grid
     */
    public function setShowGrid($show_grid)
    {
        $this->_show_grid = (bool)$show_grid;
    }

    /**
     * @return bool
     */
    public function getShowGrid()
    {
        return $this->_show_grid;
    }

    /**
     * @param $label
     */
    public function setMajorAxisLabel($label)
    {
        $this->_axes['major']['label'] = $label;
    }

    /**
     * @return mixed
     */
    public function getMajorAxisLabel()
    {
        return $this->_axes['major']['label'];
    }


    /**
     * @param $label
     */
    public function setMinorAxisLabel($label)
    {
        $this->_axes['minor']['label'] = $label;
    }

    /**
     * @return mixed
     */
    public function getMinorAxisLabel()
    {
        return $this->_axes['minor']['label'];
    }

    // Title

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param boolean $show_title
     */
    public function setShowTitle($show_title)
    {
        $this->_show_title = $show_title;
    }

    /**
     * @return boolean
     */
    public function isShowTitle()
    {
        return $this->_show_title;
    }

    /**
     * @param boolean $title_floating
     */
    public function setTitleFloating($title_floating)
    {
        $this->_title_floating = (bool)$title_floating;
    }

    /**
     * @return boolean
     */
    public function isTitleFloating()
    {
        return $this->_title_floating;
    }

    // Legend


    /**
     * @return string
     */
    public function getLegendPosition()
    {
        return $this->_legend_position;
    }

    /**
     * @param string $legend_position
     */
    public function setLegendPosition($legend_position)
    {
        $this->_legend_position = $legend_position;
    }


    public function setLegendVerticalAlign($legend_vertical_align)
    {
        $this->_legend_vertical_align = $legend_vertical_align;
    }

    public function getLegendVerticalAlign()
    {
        return $this->_legend_vertical_align;
    }


    /**
     * @param $series_data
     */
    public function setUserDefinedSeriesData($series_data)
    {
        if (is_array($series_data)) {
            $this->_user_defined_series_data = $series_data;
        }
    }

    /**
     * @return array
     */
    public function getUserDefinedSeriesData()
    {
        return $this->_user_defined_series_data;
    }


    /**
     * @param $engine
     */
    public function setEngine($engine)
    {
        $this->_engine = $engine;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->_engine;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param $row_range
     */
    public function setRowRange($row_range)
    {
        $this->_row_range = $row_range;
    }

    /**
     * @return array
     */
    public function getRowRange()
    {
        return $this->_row_range;
    }

    /**
     * @param $selected_columns
     */
    public function setSelectedColumns($selected_columns)
    {
        $this->_selected_columns = $selected_columns;
    }

    /**
     * @return array
     */
    public function getSelectedColumns()
    {
        return $this->_selected_columns;
    }

    /**
     * @param $wdt_id
     */
    public function setwpDataTableId($wdt_id)
    {
        $this->_wpdatatable_id = $wdt_id;
    }

    /**
     * @return null
     */
    public function getwpDataTableId()
    {
        return $this->_wpdatatable_id;
    }

    /**
     * @param $range_type
     */
    public function setRangeType($range_type)
    {
        $this->_range_type = $range_type;
    }

    /**
     * @return string
     */
    public function getRangeType()
    {
        return $this->_range_type;
    }

    /**
     * @param $constructedChartData
     * @param bool $loadFromDB
     * @return WPDataChart
     */
    public static function factory($constructedChartData, $loadFromDB = true)
    {
        $chartObj = new self();

        if (isset($constructedChartData['chart_id'])) {
            $chartObj->setId((int)$constructedChartData['chart_id']);
            if ($loadFromDB) {
                $chartObj->loadFromDB();
                $chartObj->prepareData();
                $chartObj->shiftStringColumnUp();
            }
        }

        // Main data (steps 1-3 of chart constructor)
        $chartObj->setwpDataTableId((int)$constructedChartData['wpdatatable_id']);
        $chartObj->setTitle(sanitize_text_field($constructedChartData['chart_title']));
        $chartObj->setEngine(sanitize_text_field($constructedChartData['chart_engine']));
        $chartObj->setType(sanitize_text_field($constructedChartData['chart_type']));
        $chartObj->setSelectedColumns(array_map('sanitize_text_field', $constructedChartData['selected_columns']));


        // Render data (step 4 or chart constructor)
        // Chart
        $chartObj->setWidth(WDTTools::defineDefaultValue($constructedChartData, 'width', 0));
        $chartObj->setHeight(WDTTools::defineDefaultValue($constructedChartData, 'height', 400));
        $chartObj->setBackgroundColor(WDTTools::defineDefaultValue($constructedChartData, 'background_color', '#FFFFFF'));
        $chartObj->setBorderWidth(WDTTools::defineDefaultValue($constructedChartData, 'border_width', 0));
        $chartObj->setBorderColor(WDTTools::defineDefaultValue($constructedChartData, 'border_color', '#FFFFFF'));
        $chartObj->setBorderRadius(WDTTools::defineDefaultValue($constructedChartData, 'border_radius', 0));
        $chartObj->setPlotBackgroundColor(WDTTools::defineDefaultValue($constructedChartData, 'plot_background_color', '#FFFFFF'));
        $chartObj->setPlotBorderWidth(WDTTools::defineDefaultValue($constructedChartData, 'plot_border_width', 0));
        $chartObj->setPlotBorderColor(WDTTools::defineDefaultValue($constructedChartData, 'plot_border_color', '#C0C0C0'));

        // Series
        if (!empty($constructedChartData['series_data'])) {
            array_walk_recursive(
                $constructedChartData['series_data'],
                function ($value, $key) {
                    sanitize_text_field($value);
                }
            );
            $chartObj->setUserDefinedSeriesData($constructedChartData['series_data']);
        }

        // Axes
        $chartObj->setShowGrid(WDTTools::defineDefaultValue($constructedChartData, 'show_grid', true));
        $chartObj->setMajorAxisLabel(WDTTools::defineDefaultValue($constructedChartData, 'horizontal_axis_label', ''));
        $chartObj->setMinorAxisLabel(WDTTools::defineDefaultValue($constructedChartData, 'vertical_axis_label', ''));


        // Title
        $chartObj->setShowTitle(WDTTools::defineDefaultValue($constructedChartData, 'show_title', true));
        $chartObj->setTitleFloating(WDTTools::defineDefaultValue($constructedChartData, 'title_floating', false));

        // Legend

        $chartObj->setLegendPosition(WDTTools::defineDefaultValue($constructedChartData, 'legend_position', 'bottom'));
        $chartObj->setLegendVerticalAlign(WDTTools::defineDefaultValue($constructedChartData, 'legend_vertical_align', 'bottom'));


        $chartObj->loadChildWPDataTable();

        return $chartObj;
    }

    public function loadChildWPDataTable()
    {
        if (empty($this->_wpdatatable_id)) {
            return false;
        }

        $this->_wpdatatable = WPDataTable::loadWpDataTable($this->_wpdatatable_id, null, empty($this->_follow_filtering));
    }

    public function shiftStringColumnUp()
    {
        /**
         * Check if the string column is not in the beginning and move it up
         */
        if (count($this->_render_data['columns']) > 1) {
            $shiftNeeded = false;
            $shiftIndex = 0;
            for ($i = 1; $i < count($this->_render_data['columns']); $i++) {
                if ($this->_render_data['columns'][$i]['type'] == 'string') {
                    $shiftNeeded = true;
                    $shiftIndex = $i;
                    break;
                }
            }

            if ($shiftNeeded) {
                // Shift columns
                $strColumn = $this->_render_data['columns'][$shiftIndex];
                unset($this->_render_data['columns'][$shiftIndex]);
                array_unshift($this->_render_data['columns'], $strColumn);
                // Shift rows
                for ($j = 0; $j < count($this->_render_data['rows']); $j++) {
                    $strCell = $this->_render_data['rows'][$j][$shiftIndex];
                    unset($this->_render_data['rows'][$j][$shiftIndex]);
                    array_unshift($this->_render_data['rows'][$j], $strCell);
                }
                // Shift column indexes
                if (isset($this->_render_data['column_indexes'])) {
                    $shiftedIndex = $this->_render_data['column_indexes'][$shiftIndex];
                    unset($this->_render_data['column_indexes'][$shiftIndex]);
                    array_unshift($this->_render_data['column_indexes'], $shiftedIndex);
                }
            }
        }

        // Format axes
        $this->_render_data['axes']['major'] = array(
            'type' => $this->_render_data['columns'][0]['type'],
            'label' => !empty($this->_render_data['hAxis']['title']) ?
                $this->_render_data['hAxis']['title'] : $this->_render_data['columns'][0]['label']
        );
        $this->_render_data['axes']['minor'] = array(
            'type' => $this->_render_data['columns'][1]['type'],
            'label' => !empty($this->_render_data['vAxis']['title']) ?
                $this->_render_data['vAxis']['title'] : ''
        );

        // Get all series names
        if (empty($this->_render_data['series'])) {
            for ($i = 1; $i < count($this->_render_data['columns']); $i++) {
                $this->_render_data['series'][] = array(
                    'label' => $this->_render_data['columns'][$i]['label'],
                    'color' => '',
                    'orig_header' => $this->_render_data['columns'][$i]['orig_header']
                );
            }
        }

    }

    public function prepareSeriesData()
    {
        // Init render data if it is empty
        if (empty($this->_render_data)) {
            $this->_render_data = array(
                'columns' => array(),
                'rows' => array(),
                'axes' => array(),
                'options' => array(
                    'title' => $this->_show_title ? $this->_title : '',
                    'series' => array(),
                    'width' => $this->_width,
                    'height' => $this->_height
                ),
                'vAxis' => array(),
                'hAxis' => array(),
                'errors' => array(),
                'series' => array()
            );
        }


        $this->_type_counters = array(
            'date' => 0,
            'string' => 0,
            'number' => 0
        );

        // Define columns
        foreach ($this->getSelectedColumns() as $columnKey) {
            $columnType = $this->_wpdatatable->getColumn($columnKey)->getGoogleChartColumnType();
            $this->_render_data['columns'][] = array(
                'type' => $columnType,
                'label' => isset($this->_user_defined_series_data[$columnKey]['label']) ?
                    $this->_user_defined_series_data[$columnKey]['label'] : $this->_wpdatatable->getColumn($columnKey)->getTitle(),
                'orig_header' => $columnKey
            );
            $this->_type_counters[$columnType]++;
        }

        // Define axes titles
        if (isset($this->_axes['major']['label'])) {
            $this->_render_data['options']['hAxis']['title'] = $this->_axes['major']['label'];
        }
        if (isset($this->_axes['minor']['label'])) {
            $this->_render_data['options']['vAxis']['title'] = $this->_axes['minor']['label'];
        }

        // Define series colors,type and yaxis

        if (!empty($this->_user_defined_series_data)) {
            $seriesIndex = 0;
            foreach ($this->_user_defined_series_data as $series_data) {
                if (!empty($series_data['color']) || !empty($series_data['type'])) {
                    $this->_render_data['options']['series'][(int)$seriesIndex] = array(
                        'color' => $series_data['color'],
                        'label' => $series_data['label']
                    );
                }
                $seriesIndex++;
            }
        }


        // Define grid settings
        if (!$this->_show_grid) {
            if (!isset($this->_render_data['options']['hAxis'])) {
                $this->_render_data['options']['hAxis'] = array();
            }
            $this->_render_data['options']['hAxis']['gridlines'] = array(
                'color' => 'transparent'
            );
            if (!isset($this->_render_data['options']['vAxis'])) {
                $this->_render_data['options']['vAxis'] = array();
            }
            $this->_render_data['options']['vAxis']['gridlines'] = array(
                'color' => 'transparent'
            );
            $this->_render_data['show_grid'] = false;
        } else {
            $this->_render_data['show_grid'] = true;
        }

        // Detect errors
        if ($this->_type_counters['string'] > 1) {
            $this->_render_data['errors'][] = __('Only one column can be of type String', 'wpdatatables');
        }
        if (($this->_type_counters['number'] > 1) && ($this->_type_counters['date'] > 1)) {
            $this->_render_data['errors'][] = __('You are mixing data types (several date axes and several number)', 'wpdatatables');
        }

        if (empty($this->_highcharts_render_data)) {
            $this->_highcharts_render_data = array();
        }

        if ($this->_engine == 'google') {
            // Chart
            $this->_render_data['width'] = $this->getWidth();
            $this->_render_data['options']['backgroundColor']['fill'] = $this->getBackgroundColor();
            $this->_render_data['options']['backgroundColor']['strokeWidth'] = $this->getBorderWidth();
            $this->_render_data['options']['backgroundColor']['stroke'] = $this->getBorderColor();
            $this->_render_data['options']['backgroundColor']['rx'] = $this->getBorderRadius();
            $this->_render_data['options']['chartArea']['backgroundColor']['fill'] = $this->getPlotBackgroundColor();
            $this->_render_data['options']['chartArea']['backgroundColor']['strokeWidth'] = $this->getPlotBorderWidth();
            $this->_render_data['options']['chartArea']['backgroundColor']['stroke'] = $this->getPlotBorderColor();

            // Title
            if ($this->isTitleFloating()) {
                $this->_render_data['options']['titlePosition'] = 'in';
            } else {
                $this->_render_data['options']['titlePosition'] = 'out';
            }


            // Legend
            $this->_render_data['options']['legend']['position'] = $this->getLegendPosition();
            if ($this->getLegendVerticalAlign() == 'bottom') {
                $this->_render_data['options']['legend']['alignment'] = 'end';
            } elseif ($this->getLegendVerticalAlign() == 'middle') {
                $this->_render_data['options']['legend']['alignment'] = 'center';
            } else {
                $this->_render_data['options']['legend']['alignment'] = 'start';
            }

        }


    }


    /**
     * Prepares the data for Google charts format
     */
    public function prepareData()
    {

        // Prepare series and columns
        if (empty($this->_render_data['columns'])) {
            $this->prepareSeriesData();
        }

        $dateFormat = ($this->getEngine() == 'google') ? DateTime::RFC2822 : get_option('wdtDateFormat');
        $timeFormat = get_option('wdtTimeFormat');

        // The data itself
        if (empty($this->_render_data['rows'])) {
            if ($this->getRangeType() == 'all_rows') {
                foreach ($this->_wpdatatable->getDataRows() as $row) {
                    $return_data_row = array();
                    foreach ($this->getSelectedColumns() as $columnKey) {
                        $dataType = $this->_wpdatatable->getColumn($columnKey)->getDataType();
                        switch ($dataType) {
                            case 'date':
                                $timestamp = is_int($row[$columnKey]) ? $row[$columnKey] : strtotime(str_replace('/', '-', $row[$columnKey]));
                                $return_data_row[] = date(
                                    $dateFormat,
                                    $timestamp
                                );
                                break;
                            case 'datetime':
                                $timestamp = is_int($row[$columnKey]) ? $row[$columnKey] : strtotime(str_replace('/', '-', $row[$columnKey]));
                                if ($this->getEngine() == 'google') {
                                    $return_data_row[] = date(
                                        $dateFormat,
                                        $timestamp
                                    );
                                } else {
                                    $return_data_row[] = date(
                                        $dateFormat . ' ' . $timeFormat,
                                        $timestamp
                                    );
                                }
                                break;
                            case 'time':
                                $timestamp = $row[$columnKey];
                                $return_data_row[] = date(
                                    $timeFormat,
                                    $timestamp
                                );
                                break;
                            case 'int':
                                $return_data_row[] = (float)$row[$columnKey];
                                break;
                            case 'float':
                                $return_data_row[] = (float)$row[$columnKey];
                                break;
                            case 'string':
                            default:
                                $return_data_row[] = $row[$columnKey];
                                break;
                        }
                    }
                    $this->_render_data['rows'][] = $return_data_row;
                }
            } else {
                foreach ($this->_row_range as $rowIndex) {
                    $return_data_row = array();
                    foreach ($this->getSelectedColumns() as $columnKey) {

                        $dataType = $this->_wpdatatable->getColumn($columnKey)->getDataType();
                        switch ($dataType) {
                            case 'date':
                                $timestamp = is_int($this->_wpdatatable->getCell($columnKey, $rowIndex)) ?
                                    $this->_wpdatatable->getCell($columnKey, $rowIndex)
                                    : strtotime(str_replace('/', '-', $this->_wpdatatable->getCell($columnKey, $rowIndex)));
                                $return_data_row[] = date(
                                    $dateFormat,
                                    $timestamp
                                );
                                break;
                            case 'datetime':
                                $timestamp = is_int($this->_wpdatatable->getCell($columnKey, $rowIndex)) ?
                                    $this->_wpdatatable->getCell($columnKey, $rowIndex) : strtotime(str_replace('/', '-', $this->_wpdatatable->getCell($columnKey, $rowIndex)));
                                if ($this->getEngine() == 'google') {
                                    $return_data_row[] = date(
                                        $dateFormat,
                                        $timestamp
                                    );
                                } else {
                                    $return_data_row[] = date(
                                        $dateFormat . ' ' . $timeFormat,
                                        $timestamp
                                    );
                                }
                                break;
                            case 'time':
                                $timestamp = $this->_wpdatatable->getCell($columnKey, $rowIndex);
                                $return_data_row[] = date(
                                    $timeFormat,
                                    $timestamp
                                );
                                break;
                            case 'int':
                                $return_data_row[] = (float)$this->_wpdatatable->getCell($columnKey, $rowIndex);
                                break;
                            case 'float':
                                $return_data_row[] = (float)$this->_wpdatatable->getCell($columnKey, $rowIndex);
                                break;
                            case 'string':
                            default:
                                $return_data_row[] = $this->_wpdatatable->getCell($columnKey, $rowIndex);
                                break;
                        }

                    }
                    $this->_render_data['rows'][] = $return_data_row;
                }
            }

        }

        $this->_render_data['type'] = $this->_type;
        return $this->_render_data;
    }


    public function getAxesAndSeries()
    {
        if (empty($this->_render_data['columns'])) {
            $this->prepareSeriesData();
            $this->shiftStringColumnUp();
        }
        return $this->_render_data;
    }


    public function returnGoogleChartData()
    {
        $this->prepareData();
        $this->shiftStringColumnUp();
        return $this->_render_data;
    }


    public function returnData()
    {
        return $this->returnGoogleChartData();
    }


    /**
     * Saves the chart data to DB
     * @global WPDB $wpdb
     */
    public function save()
    {
        global $wpdb;

        $this->prepareSeriesData();
        $this->shiftStringColumnUp();


        $render_data = array(
            'selected_columns' => $this->getSelectedColumns(),
            'range_type' => $this->getRangeType(),
            'row_range' => $this->getRowRange(),
            'render_data' => $this->_render_data,
            'show_grid' => $this->_show_grid,
            'show_title' => $this->_show_title
        );


        if (empty($this->_id)) {
            // This is a new chart

            $wpdb->insert(
                $wpdb->prefix . "wpdatacharts",
                array(
                    'wpdatatable_id' => $this->_wpdatatable_id,
                    'title' => $this->_title,
                    'engine' => $this->_engine,
                    'type' => $this->_type,
                    'json_render_data' => json_encode($render_data)
                )
            );

            $this->_id = $wpdb->insert_id;

        } else {
            // Updating the chart
            $wpdb->update(
                $wpdb->prefix . "wpdatacharts",
                array(
                    'wpdatatable_id' => $this->_wpdatatable_id,
                    'title' => $this->_title,
                    'engine' => $this->_engine,
                    'type' => $this->_type,
                    'json_render_data' => json_encode($render_data)
                ),
                array(
                    'id' => $this->_id
                )
            );

        }

    }

    public function getColumnIndexes()
    {
        foreach ($this->getSelectedColumns() as $columnKey) {
            $this->_render_data['column_indexes'][] = $this->_wpdatatable->getColumnHeaderOffset($columnKey);
        }
    }

    /**
     * Return the shortcode
     */
    public function getShortCode()
    {
        if (!empty($this->_id)) {
            return '[wpdatachart id=' . $this->_id . ']';
        } else {
            return '';
        }
    }

    /**
     * Load from DB
     * @return bool
     */
    public function loadFromDB()
    {
        global $wpdb;

        if (empty($this->_id)) {
            return false;
        }

        // Load json data from DB
        $chartQuery = $wpdb->prepare(
            "SELECT * 
                        FROM " . $wpdb->prefix . "wpdatacharts 
                        WHERE id = %d",
            $this->_id
        );
        $chartData = $wpdb->get_row($chartQuery);

        if ($chartData === null) {
            return false;
        }

        $renderData = json_decode($chartData->json_render_data, true);
        $this->_render_data = $renderData['render_data'];

        $this->setTitle($chartData->title);
        $this->setEngine($chartData->engine);
        $this->setwpDataTableId($chartData->wpdatatable_id);
        $this->setType($chartData->type);
        $this->setSelectedColumns($renderData['selected_columns']);
        $this->setRangeType($renderData['range_type']);
        $this->setRowRange($renderData['row_range']);
        $this->setShowGrid(isset($renderData['show_grid']) ? $renderData['show_grid'] : false);
        $this->setShowTitle(isset($renderData['show_title']) ? $renderData['show_title'] : false);
        if (!empty($renderData['render_data']['options']['width'])) {
            $this->setWidth($renderData['render_data']['options']['width']);
        }
        $this->setHeight($renderData['render_data']['options']['height']);


        // Chart
        $this->setBackgroundColor(isset($renderData['render_data']['options']['backgroundColor']['fill']) ? $renderData['render_data']['options']['backgroundColor']['fill'] : '');
        $this->setBorderWidth(isset($renderData['render_data']['options']['backgroundColor']['strokeWidth']));
        $this->setBorderColor(isset($renderData['render_data']['options']['backgroundColor']['stroke']));
        $this->setBorderRadius(isset($renderData['render_data']['options']['backgroundColor']['rx']));
        $this->setPlotBackgroundColor(isset($renderData['render_data']['options']['chartArea']['backgroundColor']['fill']));
        $this->setPlotBorderWidth(isset($renderData['render_data']['options']['chartArea']['backgroundColor']['strokeWidth']));
        $this->setPlotBorderColor(isset($renderData['render_data']['options']['chartArea']['backgroundColor']['stroke']) ? $renderData['render_data']['options']['chartArea']['backgroundColor']['stroke'] : '');


        // Title
        if ($this->isTitleFloating()) {
            $renderData['render_data']['options']['titlePosition'] = 'in';
        } else {
            $renderData['render_data']['options']['titlePosition'] = 'out';
        }


        // Legend
        $this->setLegendPosition(isset($renderData['render_data']['options']['legend']['position']));
        $this->setLegendVerticalAlign(isset($renderData['render_data']['options']['legend']['alignment']));


        $this->loadChildWPDataTable();

    }

    /**
     * Render Chart
     */
    public function renderChart()
    {
        $minified_js = get_option('wdtMinifiedJs');

        $this->prepareData();

        $this->shiftStringColumnUp();

        $js_ext = $minified_js ? '.min.js' : '.js';
        if (!(is_admin() && function_exists('register_block_type')) ) {
            wp_enqueue_script('wpdatatables-render-chart', WDT_JS_PATH . 'wpdatatables/wdt.chartsRender' . $js_ext, array(), WDT_CURRENT_VERSION);
        }
        wp_enqueue_script('wdt_google_charts', '//www.gstatic.com/charts/loader.js', array(), WDT_CURRENT_VERSION);
        wp_enqueue_script('wpdatatables-google-chart', WDT_JS_PATH . 'wpdatatables/wdt.googleCharts' . $js_ext, array(), WDT_CURRENT_VERSION);
        $json_chart_render_data = json_encode($this->_render_data);


        $chart_id = $this->_id;
        ob_start();
        include(WDT_TEMPLATE_PATH . 'wpdatachart.inc.php');
        $chart_html = ob_get_contents();
        ob_end_clean();
        return $chart_html;

    }

    /**
     * Return render data
     */
    public function getRenderData()
    {
        return $this->_render_data;
    }


    /**
     * Delete chart by ID
     * @param $chartId
     * @return bool
     */
    public static function deleteChart($chartId)
    {
        global $wpdb;

        if (!isset($_REQUEST['wdtNonce']) || empty($chartId) || !current_user_can('manage_options') || !wp_verify_nonce($_REQUEST['wdtNonce'], 'wdtDeleteChartNonce')) {
            return false;
        }

        $wpdb->delete(
            $wpdb->prefix . "wpdatacharts",
            array(
                'id' => (int)$chartId
            )
        );

        return true;

    }

    /**
     * Get all charts non-paged for the MCE editor
     * @return array|null|object
     */
    public static function getAllCharts()
    {
        global $wpdb;
        $query = "SELECT id, title FROM {$wpdb->prefix}wpdatacharts ";
        $allCharts = $wpdb->get_results($query, ARRAY_A);
        return $allCharts;
    }

}

?>
