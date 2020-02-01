<?php

defined('ABSPATH') or die("Cannot access pages directly.");

class WDTColumn {

    protected $_inputType = '';
    protected $_hiddenOnPhones = false;
    protected $_hiddenOnTablets = false;
    protected $_title;
    protected $_orig_header = '';
    protected $_isVisible = true;
    protected $_cssStyle;
    protected $_width;
    protected $_sorting = true;
    protected $_cssClassArray;
    protected $_dataType;
    protected $_jsDataType = 'html';
    protected $_filterType = 'text';
    protected $_possibleValues = array();
    protected $_filterDefaultValue = '';
    protected $_textBefore = '';
    protected $_textAfter = '';
    protected $_notNull = false;
    protected $_showThousandsSeparator = true;
    protected $_conditionalFormattingData = array();
    protected $_searchable = true;
    protected $_decimalPlaces = -1;
    protected $_exactFiltering;
    protected $_filterLabel;
    protected $_possibleValuesType;
    protected $_possibleValuesAddEmpty = false;
    protected $_foreignKeyRule;
    protected $_editingDefaultValue = null;
    protected $_parentTable = null;
    protected $_linkButtonLabel;

    /**
     * WDTColumn constructor.
     * @param array $properties
     */
    public function __construct($properties = array()) {
        $this->_cssClassArray = WDTTools::defineDefaultValue($properties, 'classes', array());
        $this->_textBefore = WDTTools::defineDefaultValue($properties, 'text_before', '');
        $this->_textAfter = WDTTools::defineDefaultValue($properties, 'text_after', '');
        $this->setSorting(WDTTools::defineDefaultValue($properties, 'sorting', 1));
        $this->_title = WDTTools::defineDefaultValue($properties, 'title', '');
        $this->_isVisible = WDTTools::defineDefaultValue($properties, 'visible', true);
        $this->_width = WDTTools::defineDefaultValue($properties, 'width', '');
        $this->_orig_header = WDTTools::defineDefaultValue($properties, 'orig_header', '');
        $this->_exactFiltering = WDTTools::defineDefaultValue($properties, 'exactFiltering', '');
        $this->setFilterDefaultValue(WDTTools::defineDefaultValue($properties, 'filterDefaultValue', null));
        $this->setFilterLabel(WDTTools::defineDefaultValue($properties, 'filterLabel', null));
        $this->_possibleValuesType = WDTTools::defineDefaultValue($properties, 'possibleValuesType', '');
        $this->setPossibleValuesAddEmpty(WDTTools::defineDefaultValue($properties, 'possibleValuesAddEmpty', false));
        $this->setEditingDefaultValue(WDTTools::defineDefaultValue($properties, 'editingDefaultValue', null));
        $this->setParentTable(WDTTools::defineDefaultValue($properties, 'parentTable', null));
        $this->setLinkButtonLabel(WDTTools::defineDefaultValue($properties, 'linkButtonLabel', null));

    }

    /**
     * @return string
     */
    public function getInputType() {
        return $this->_inputType;
    }

    /**
     * @param string $inputType
     */
    public function setInputType($inputType) {
        $this->_inputType = $inputType;
    }

    /**
     * @return bool
     */
    public function isHiddenOnPhones() {
        return $this->_hiddenOnPhones;
    }

    /**
     * @param bool $hiddenOnPhones
     */
    public function setHiddenOnPhones($hiddenOnPhones) {
        $this->_hiddenOnPhones = $hiddenOnPhones;
    }

    /**
     * @return bool
     */
    public function isHiddenOnTablets() {
        return $this->_hiddenOnTablets;
    }

    /**
     * @param bool $hiddenOnTablets
     */
    public function setHiddenOnTablets($hiddenOnTablets) {
        $this->_hiddenOnTablets = $hiddenOnTablets;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getOriginalHeader() {
        return $this->_orig_header;
    }

    /**
     * @param string $orig_header
     */
    public function setOriginalHeader($orig_header) {
        $this->_orig_header = $orig_header;
    }

    /**
     * @return bool|string
     */
    public function isVisible() {
        return $this->_isVisible;
    }

    /**
     * @return bool
     */
    public function isVisibleOnMobiles() {
        return ($this->_isVisible && !$this->_hiddenOnPhones && !$this->_hiddenOnTablets);
    }

    /**
     * @param bool|string $isVisible
     */
    public function setIsVisible($isVisible) {
        $this->_isVisible = $isVisible;
    }

    /**
     * @return mixed
     */
    public function getCssStyle() {
        return $this->_cssStyle;
    }

    /**
     * @param mixed $cssStyle
     */
    public function setCssStyle($cssStyle) {
        $this->_cssStyle = $cssStyle;
    }

    /**
     * @return string
     */
    public function getWidth() {
        return $this->_width ? $this->_width : 'auto';
    }

    /**
     * @param string $width
     */
    public function setWidth($width) {
        $this->_width = $width;
    }

    /**
     * @return bool
     */
    public function getSorting() {
        return $this->_sorting;
    }

    /**
     * @param int $sorting
     */
    public function setSorting($sorting) {
        $this->_sorting = (bool)$sorting;
    }

    /**
     * @return mixed
     */
    public function getCSSClasses() {
        $classesStr = implode(' ', $this->_cssClassArray);
        $classesStr = apply_filters('wpdatatables_filter_column_cssClassArray', $classesStr, $this->_title);
        return $classesStr;
    }

    /**
     * @param $class
     */
    public function addCSSClass($class) {
        $this->_cssClassArray[] = $class;
    }

    /**
     * @return mixed
     */
    public function getDataType() {
        return $this->_dataType;
    }

    /**
     * @param mixed $dataType
     */
    public function setDataType($dataType) {
        $this->_dataType = $dataType;
    }

    /**
     * @return string
     */
    public function getFilterType() {
        return $this->_filterType;
    }

    /**
     * @param $filterType
     * @throws WDTException
     */
    public function setFilterType($filterType) {
        if (!in_array($filterType,
            array(
                'none',
                '',
                'text',
                'number',
                'select',
                'null',
                'number-range',
                'date-range',
                'checkbox',
                'datetime-range',
                'time-range'
            )
        )
        ) {
            throw new WDTException('Unknown column filter type!');
        }
        if (($filterType == 'none') || ($filterType == '')) {
            $filterType = 'null';
        }
        $this->_filterType = $filterType;
    }

    /**
     * @return array
     */
    public function getPossibleValues() {
        return $this->_possibleValues;
    }

    /**
     * @param array $possibleValues
     */
    public function setPossibleValues($possibleValues) {
        if (!empty($possibleValues)) {
            if (!is_array($possibleValues)) {
                $possibleValues = explode('|', $possibleValues);
            }
            $this->_possibleValues = $possibleValues;
        } else {
            $this->_possibleValues = array();
        }
    }

    /**
     * @return string
     */
    public function getFilterDefaultValue() {
        $value = $this->_filterDefaultValue;
        if (is_array($value)) {
            foreach ($value as &$singleValue) {
                $singleValue = $this->applyPlaceholders($singleValue);
            }
        } else {
            $value = $this->applyPlaceholders($value);
        }
        return $value;
    }

    /**
     * @param string $defaultValue
     */
    public function setFilterDefaultValue($defaultValue) {
        if (strpos($defaultValue, '|') !== false) {
            $defaultValue = explode('|', $defaultValue);
        }
        $this->_filterDefaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getTextBefore() {
        return $this->_textBefore;
    }

    /**
     * @param string $textBefore
     */
    public function setTextBefore($textBefore) {
        $this->_textBefore = $textBefore;
    }

    /**
     * @return string
     */
    public function getTextAfter() {
        return $this->_textAfter;
    }

    /**
     * @param string $textAfter
     */
    public function setTextAfter($textAfter) {
        $this->_textAfter = $textAfter;
    }

    /**
     * @return bool
     */
    public function isNotNull() {
        return $this->_notNull;
    }

    /**
     * @param bool $notNull
     */
    public function setNotNull($notNull) {
        $this->_notNull = (bool)$notNull;
    }

    /**
     * @return bool
     */
    public function isShowThousandsSeparator() {
        return $this->_showThousandsSeparator;
    }

    /**
     * @param bool $showThousandsSeparator
     */
    public function setShowThousandsSeparator($showThousandsSeparator) {
        $this->_showThousandsSeparator = $showThousandsSeparator;
    }

    /**
     * @return array
     */
    public function getConditionalFormattingData() {
        return $this->_conditionalFormattingData;
    }

    /**
     * Set conditional formatting data for column and set
     * conditional formatting cell value to today's date if %TODAY%
     * placeholder is used
     * @param array $conditionalFormattingData
     */
    public function setConditionalFormattingData($conditionalFormattingData) {
        $this->_conditionalFormattingData = $conditionalFormattingData;
    }

    /**
     * @return bool
     */
    public function isSearchable() {
        return $this->_searchable;
    }

    /**
     * @param bool $searchable
     */
    public function setSearchable($searchable) {
        $this->_searchable = $searchable;
    }

    /**
     * @return int
     */
    public function getDecimalPlaces() {
        return $this->_decimalPlaces;
    }

    /**
     * @param int $decimalPlaces
     */
    public function setDecimalPlaces($decimalPlaces) {
        $this->_decimalPlaces = $decimalPlaces;
    }

    /**
     * @return string
     */
    public function getExactFiltering() {
        return $this->_exactFiltering;
    }

    /**
     * @param string $exactFiltering
     */
    public function setExactFiltering($exactFiltering) {
        $this->_exactFiltering = $exactFiltering;
    }

    /**
     * @return string
     */
    public function getFilterLabel() {
        return $this->_filterLabel;
    }

    /**
     * @param string $filterLabel
     */
    public function setFilterLabel($filterLabel) {
        $this->_filterLabel = $filterLabel;
    }
    /**
     * @return string
     */
    public function getLinkButtonLabel(){
        return $this->_linkButtonLabel;
    }

    /**
     * @param string $linkButtonLabel
     */
    public function setLinkButtonLabel($linkButtonLabel){
        $this->_linkButtonLabel = $linkButtonLabel;
    }

    /**
     * @return string
     */
    public function getPossibleValuesType() {
        return $this->_possibleValuesType;
    }

    /**
     * @param string $possibleValuesType
     */
    public function setPossibleValuesType($possibleValuesType) {
        $this->_possibleValuesType = $possibleValuesType;
    }

    /**
     * @return mixed
     */
    public function getPossibleValuesAddEmpty() {
        return $this->_possibleValuesAddEmpty;
    }

    /**
     * @param mixed $possibleValuesAddEmpty
     */
    public function setPossibleValuesAddEmpty($possibleValuesAddEmpty) {
        $this->_possibleValuesAddEmpty = (bool)$possibleValuesAddEmpty;
    }

    /**
     * @return mixed
     */
    public function getForeignKeyRule() {
        return $this->_foreignKeyRule;
    }

    /**
     * @param mixed $foreignKeyRule
     */
    public function setForeignKeyRule($foreignKeyRule) {
        $this->_foreignKeyRule = $foreignKeyRule;
    }

    /**
     * @return string
     */
    public function getEditingDefaultValue() {
        return $this->_editingDefaultValue;
    }

    /**
     * @param string $editingDefaultValue
     */
    public function setEditingDefaultValue($editingDefaultValue) {
        $this->_editingDefaultValue = $editingDefaultValue;
    }

    /**
     * @return null
     */
    public function getParentTable() {
        return $this->_parentTable;
    }

    /**
     * @param null $parentTable
     */
    public function setParentTable($parentTable) {
        $this->_parentTable = $parentTable;
    }

    /**
     * @param $cellContent
     * @return mixed
     */
    public function returnCellValue($cellContent) {
        $cellValue = $this->prepareCellOutput($cellContent);
        $cellValue = apply_filters('wpdatatables_filter_cell_val', $cellValue, $this->getParentTable()->getWpId());
        return $cellValue;
    }

    /**
     * Get column type for Google Charts
     * @return string
     */
    public function getGoogleChartColumnType() {
        return 'string';
    }

	public function hideOnTablets(){
		$this->_hiddenOnTablets = true;
	}

	public function showOnTablets(){
		$this->_hiddenOnTablets = false;
	}

	public function getHiddenAttr(){
		$hidden = array();
		if($this->_hiddenOnPhones){
			$hidden[] = 'phone';
		}
		if($this->_hiddenOnTablets){
			$hidden[] = 'tablet';
		}
		return implode(',',$hidden);
	}

    /**
     * @param $content
     * @return mixed
     */
    public function prepareCellOutput($content) {
        if (is_array($content)) {
            return $content['value'];
        } else {
            return $content;
        }
    }

    /**
     * Apply placeholders
     *
     * @param $value
     * @return mixed
     */
    private function applyPlaceholders($value) {
        global $wdtVar1, $wdtVar2, $wdtVar3;

        // Current user ID
        if (strpos($value, '%CURRENT_USER_ID%') !== false) {
            $value = str_replace('%CURRENT_USER_ID%', get_current_user_id(), $value);
        }

        // Current user login
        if (strpos($value, '%CURRENT_USER_LOGIN%') !== false) {
            $value = str_replace('%CURRENT_USER_LOGIN%', wp_get_current_user()->user_login, $value);
        }

        // Current post id
        if (strpos($value, '%CURRENT_POST_ID%') !== false) {
            $value = str_replace('%CURRENT_POST_ID%', get_post()->ID, $value);
        }

        // Shortcode VAR1
        if (strpos($value, '%VAR1%') !== false) {
            $value = str_replace('%VAR1%', $wdtVar1, $value);
        }

        // Shortcode VAR2
        if (strpos($value, '%VAR2%') !== false) {
            $value = str_replace('%VAR2%', $wdtVar2, $value);
        }

        // Shortcode VAR3
        if (strpos($value, '%VAR3%') !== false) {
            $value = str_replace('%VAR3%', $wdtVar3, $value);
        }

        return $value;
    }

    /**
     * Generates column object based on column type
     * @param string $wdtColumnType
     * @param array $properties
     * @return mixed
     */
    public static function generateColumn($wdtColumnType = 'string', $properties = array()) {
        if (!$wdtColumnType) {
            $wdtColumnType = 'string';
        }
        $columnObj = ucfirst($wdtColumnType) . 'WDTColumn';
        $columnFormatterFileName = 'class.' . strtolower($wdtColumnType) . '.wpdatacolumn.php';
        require_once($columnFormatterFileName);
        return new $columnObj($properties);
    }

    /**
     * Get JSON for a column
     * @return StdClass
     */
    public function getColumnJSON($columnID) {
        $colJsDefinition = new StdClass();
        $colJsDefinition->sType = $this->_jsDataType;
        $colJsDefinition->wdtType = $this->_dataType;
        $colJsDefinition->className = $this->getCSSClasses() . ' column-' . sanitize_html_class(strtolower(str_replace(' ', '-', $this->_orig_header)));
        $colJsDefinition->bVisible = $this->isVisible();
        $colJsDefinition->orderable = $this->getSorting();
        $colJsDefinition->searchable = $this->_searchable;
        $colJsDefinition->InputType = $this->_inputType;
        $colJsDefinition->name = $this->_orig_header;
        $colJsDefinition->origHeader = $this->_orig_header;
        $colJsDefinition->notNull = $this->_notNull;
        $colJsDefinition->conditionalFormattingRules = $this->getConditionalFormattingData();
        if (sanitize_html_class(strtolower(str_replace(' ', '-', $this->_orig_header)))) {
            $colJsDefinition->className = $this->getCSSClasses() . ' column-' . sanitize_html_class( strtolower( str_replace( ' ', '-', $this->_orig_header ) ) );
        } else {
            $colJsDefinition->className = $this->getCSSClasses() . ' column-' . $columnID;
        }
        if ($this->_width != '') {
            $colJsDefinition->sWidth = $this->_width;
        }
        $colJsDefinition = apply_filters('wpdatatables_filter_column_js_definition', $colJsDefinition, $this->_title);
        return $colJsDefinition;
    }

    /**
     * Get Filter definition for a column
     * @return stdClass
     */
    public function getJSFilterDefinition() {
        /** @var WPDataTable $parentTable */
        $parentTable = $this->getParentTable();
        $jsFilterDef = new stdClass();

        $jsFilterDef->type = $this->getFilterType();
        $jsFilterDef->possibleValuesType = $this->getPossibleValuesType();

        $jsFilterDef->values = null;
        if (in_array($this->getFilterType(), array('select', 'checkbox')) || in_array($this->getInputType(), array('selectbox', 'multi-selectbox'))) {
            if ($this->_possibleValuesType == 'read' && $parentTable->serverSide()) {
                $jsFilterDef->values = WDTColumn::getColumnDistinctValues($this);
            } elseif ($this->_possibleValuesType == 'list') {
                $jsFilterDef->values = $this->getPossibleValues();
            } elseif ($this->_possibleValuesType == 'foreignkey' && $parentTable->serverSide()) {
                foreach ($this->getPossibleValues() as $value => $label) {
                    $distinctValue['value'] = $value;
                    $distinctValue['label'] = $label;
                    $jsFilterDef->values[] = $distinctValue;
                }
            }
        }

        if ($this->getFilterType() == 'select' && $parentTable->serverSide() && $this->getPossibleValuesAddEmpty()) {
            array_unshift($jsFilterDef->values, 'possibleValuesAddEmpty');
        }

        $jsFilterDef->origHeader = $this->getOriginalHeader();
        $jsFilterDef->possibleValuesAddEmpty = $this->getPossibleValuesAddEmpty();
        $jsFilterDef->defaultValue = $this->getFilterDefaultValue();
        $jsFilterDef->exactFiltering = $this->getExactFiltering();
        $jsFilterDef->filterLabel = $this->getFilterLabel();
        $jsFilterDef->linkButtonLabel = $this->getLinkButtonLabel();

        return $jsFilterDef;
    }

    /**
     * Get Editing definition for a column
     * @return stdClass
     */
    public function getJSEditingDefinition() {
        $jsEditingDef = new stdClass();

        $jsEditingDef->editorInputType = $this->getInputType();
        $jsEditingDef->defaultValue = $this->getEditingDefaultValue();
        $jsEditingDef->defaultValue = $this->applyPlaceholders($jsEditingDef->defaultValue);

        return $jsEditingDef;
    }

    /**
     * Get values that will be used in the in the Default Value Inputs
     * @return array
     */
    public function getDefaultValues() {
        /** @var WPDataTable $parentTable */
        $parentTable = $this->getParentTable();
        $values = array();

        if (!in_array($this->getDataType(), array('date', 'datetime', 'time', 'formula'), true) && $this->getDataType() != '' && empty($this->_formula)) {

            

                foreach ($parentTable->getDataRows() as $row) {
                    $values[] = $row[$this->getOriginalHeader()];
                }
                $values = array_unique($values);

                
        }

        return $values;
    }

    
}
