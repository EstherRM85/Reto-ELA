<?php

defined('ABSPATH') or die("Cannot access pages directly.");

class WDTTools
{

    public static $jsVars = array();

    /**
     * Helper function that returns array of possible column types
     * @return array
     */
    public static function getPossibleColumnTypes()
    {
        return array(
            'input' => __('One line string', 'wpdatatables'),
            'memo' => __('Multi-line string', 'wpdatatables'),
            'select' => __('One-line selectbox', 'wpdatatables'),
            'multiselect' => __('Multi-line selectbox', 'wpdatatables'),
            'int' => __('Integer', 'wpdatatables'),
            'float' => __('Float', 'wpdatatables'),
            'date' => __('Date', 'wpdatatables'),
            'datetime' => __('Datetime', 'wpdatatables'),
            'time' => __('Time', 'wpdatatables'),
            'link' => __('URL Link', 'wpdatatables'),
            'email' => __('E-mail', 'wpdatatables'),
            'image' => __('Image', 'wpdatatables'),
            'file' => __('Attachment', 'wpdatatables')
        );
    }

    /**
     * Helper function that sanitize column header
     * @param $header
     * @return mixed
     */
    public static function sanitizeHeader($header)
    {
        return
            str_replace(
                range('0', '9'),
                range('a', 'j'),
                str_replace(
                    array('$', '_', '&', ' '),
                    '',
                    $header
                )
            );
    }

    /**
     * Helper function for applying placeholders(variables)
     * @param $string
     * @return mixed
     */
    public static function applyPlaceholders($string)
    {
        global $wdtVar1, $wdtVar2, $wdtVar3, $wpdb;

        $table = isset($_POST['table']) ? json_decode(stripslashes($_POST['table'])) : null;

        // Placeholders
        if (strpos($string, '%CURRENT_USER_ID%') !== false) {
            if (isset($table->currentUserIdPlaceholder)) {
                $currentUserIdPlaceholder = $table->currentUserIdPlaceholder;
            } elseif (isset($_POST['currentUserId'])) {
                $currentUserIdPlaceholder = $_POST['currentUserId'];
            }

            $wdtCurUserId = isset($currentUserIdPlaceholder) ?
                $currentUserIdPlaceholder : get_current_user_id();

            $string = str_replace('%CURRENT_USER_ID%', $wdtCurUserId, $string);
        }
        if (strpos($string, '%CURRENT_USER_LOGIN%') !== false) {
            if (isset($table->currentUserLoginPlaceholder)) {
                $currentUserLoginPlaceholder = $table->currentUserLoginPlaceholder;
            } elseif (isset($_POST['currentUserLogin'])) {
                $currentUserLoginPlaceholder = $_POST['currentUserLogin'];
            }

            $wdtCurUserLogin = isset($currentUserLoginPlaceholder) ?
                $currentUserLoginPlaceholder : wp_get_current_user()->user_login;

            $string = str_replace('%CURRENT_USER_LOGIN%', "'{$wdtCurUserLogin}'", $string);
        }
        if (strpos($string, '%CURRENT_POST_ID%') !== false) {
            if (isset($table->currentPostIdPlaceholder)) {
                $currentPostIdPlaceholder = $table->currentPostIdPlaceholder;
            } elseif (isset($_POST['currentPostIdPlaceholder'])) {
                $currentPostIdPlaceholder = $_POST['currentPostIdPlaceholder'];
            }

            $post = get_post();

            $wdtCurPostId = isset($currentPostIdPlaceholder) ?
                $currentPostIdPlaceholder : (!empty($post) ? $post->ID : false);

            $string = str_replace('%CURRENT_POST_ID%', $wdtCurPostId, $string);
        }
        if (strpos($string, '%WPDB%') !== false) {
            if (isset($table->wpdbPlaceholder)) {
                $wpdbPlaceholder = $table->wpdbPlaceholder;
            } elseif (isset($_POST['wpdbPlaceholder'])) {
                $wpdbPlaceholder = $_POST['wpdbPlaceholder'];
            }

            $wpdbPrefix = isset($wpdbPlaceholder) ?
                $wpdbPlaceholder : $wpdb->prefix;

            $string = str_replace('%WPDB%', $wpdbPrefix, $string);
        }

        // Shortcode VAR1
        if (strpos($string, '%VAR1%') !== false) {
            $string = str_replace('%VAR1%', $wdtVar1, $string);
        }

        // Shortcode VAR2
        if (strpos($string, '%VAR2%') !== false) {
            $string = str_replace('%VAR2%', $wdtVar2, $string);
        }

        // Shortcode VAR3
        if (strpos($string, '%VAR3%') !== false) {
            $string = str_replace('%VAR3%', $wdtVar3, $string);
        }

        return $string;

    }

    /**
     * Helper function that returns curl data
     * @param $url
     * @return mixed|null
     * @throws Exception
     */
    public static function curlGetData($url)
    {
        $ch = curl_init();
        $timeout = 5;
        $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_REFERER, site_url());
        $data = curl_exec($ch);
        if (curl_error($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new Exception($error);
        }
        if (strpos($data, '<TITLE>Moved Temporarily</TITLE>')) {
            throw new Exception(__('wpDataTables was unable to read your Google Spreadsheet, probably it is not published correctly. <br/> You can publish it by going to <b>File -> Publish to the web</b> ', 'wpdatatables'));
        }
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($info['http_code'] !== 404) {
            return $data;
        } else {
            return NULL;
        }
    }


    /**
     * Helper function to find CSV delimiter
     * @param $csv_url
     * @return string
     */
    public static function detectCSVDelimiter($csv_url)
    {

        if (!file_exists($csv_url) || !is_readable($csv_url)) {
            throw new WDTException('Could not open ' . $csv_url . ' for reading! File does not exist.');
        }
        $fileResurce = fopen($csv_url, 'r');

        $delimiterList = [',', ':', ';', "\t", '|'];
        $counts = [];
        foreach ($delimiterList as $delimiter) {
            $counts[$delimiter] = [];
        }

        $lineNumber = 0;
        while (($line = fgets($fileResurce)) !== false && (++$lineNumber < 1000)) {
            $lineCount = [];
            for ($i = strlen($line) - 1; $i >= 0; --$i) {
                $character = $line[$i];
                if (isset($counts[$character])) {
                    if (!isset($lineCount[$character])) {
                        $lineCount[$character] = 0;
                    }
                    ++$lineCount[$character];
                }
            }
            foreach ($delimiterList as $delimiter) {
                $counts[$delimiter][] = isset($lineCount[$delimiter])
                    ? $lineCount[$delimiter]
                    : 0;
            }
        }

        $RMSD = [];
        $middleIdx = floor(($lineNumber - 1) / 2);

        foreach ($delimiterList as $delimiter) {
            $series = $counts[$delimiter];
            sort($series);

            $median = ($lineNumber % 2)
                ? $series[$middleIdx]
                : ($series[$middleIdx] + $series[$middleIdx + 1]) / 2;

            if ($median === 0) {
                continue;
            }

            $RMSD[$delimiter] = array_reduce(
                    $series,
                    function ($sum, $value) use ($median) {
                        return $sum + pow($value - $median, 2);
                    }
                ) / count($series);
        }

        $min = INF;
        foreach ($delimiterList as $delimiter) {
            if (!isset($RMSD[$delimiter])) {
                continue;
            }

            if ($RMSD[$delimiter] < $min) {
                $min = $RMSD[$delimiter];
                $finalDelimiter = $delimiter;
            }
        }

        if ($delimiter === null) {
            $finalDelimiter = reset($delimiterList);
        }

        return $finalDelimiter;
    }


    /**
     * Helper function that convert CSV file to Array
     * @param $csv
     * @return array
     */
    public static function csvToArray($csv)
    {
        $arr = array();
        $lines = explode("\n", $csv);
        foreach ($lines as $row) {
            $arr[] = str_getcsv($row, ",");
        }
        $count = count($arr) - 1;
        $labels = array_shift($arr);
        $keys = array();
        foreach ($labels as $label) {
            $keys[] = $label;
        }
        $keys = array_map('trim', $keys);
        $returnArray = array();
        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $arr[$j]);
            $returnArray[$j] = $d;
        }
        return $returnArray;
    }


    /**
     * Helper function that returns array of translation strings used for localization of JavaScript files
     * @return array
     */
    public static function getTranslationStrings()
    {
        return array(
            'back_to_date' => __('Back to date', 'wpdatatables'),
            'browse_file' => __('Browse', 'wpdatatables'),
            'cancel' => __('Cancel', 'wpdatatables'),
            'cannot_be_empty' => __(' field cannot be empty!', 'wpdatatables'),
            'choose_file' => __('Use selected file', 'wpdatatables'),
            'chooseFile' => __('Choose file', 'wpdatatables'),
            'close' => __('Close', 'wpdatatables'),
            'columnAdded' => __('Column has been added!', 'wpdatatables'),
            'columnHeaderEmpty' => __('Column header cannot be empty!', 'wpdatatables'),
            'columnRemoveConfirm' => __('Please confirm column deletion!', 'wpdatatables'),
            'columnRemoved' => __('Column has been removed!', 'wpdatatables'),
            'columnsEmpty' => __('Please select columns that you want to use in table', 'wpdatatables'),
            'copy' => __('Copy', 'wpdatatables'),
            'databaseInsertError' => __('There was an error trying to insert a new row!', 'wpdatatables'),
            'dataSaved' => __('Data has been saved!', 'wpdatatables'),
            'detach_file' => __('detach', 'wpdatatables'),
            'error' => __('Error!', 'wpdatatables'),
            'fileUploadEmptyFile' => __('Please upload or choose a file from Media Library!', 'wpdatatables'),
            'from' => __('From', 'wpdatatables'),
            'invalid_email' => __('Please provide a valid e-mail address for field', 'wpdatatables'),
            'invalid_link' => __('Please provide a valid URL link for field', 'wpdatatables'),
            'invalid_value' => __('You have entered invalid value. Press ESC to cancel.', 'wpdatatables'),
            'lengthMenu' => __('Show _MENU_ entries', 'wpdatatables'),
            'merge' => __('Merge', 'wpdatatables'),
            'newColumnName' => __('New column', 'wpdatatables'),
            'oAria' => array(
                'sSortAscending' => __(': activate to sort column ascending', 'wpdatatables'),
                'sSortDescending' => __(': activate to sort column descending', 'wpdatatables')
            ),
            'ok' => __('Ok', 'wpdatatables'),
            'oPaginate' => array(
                'sFirst' => __('First', 'wpdatatables'),
                'sLast' => __('Last', 'wpdatatables'),
                'sNext' => __('Next', 'wpdatatables'),
                'sPrevious' => __('Previous', 'wpdatatables')
            ),
            'replace' => __('Replace', 'wpdatatables'),
            'rowDeleted' => __('Row has been deleted!', 'wpdatatables'),
            'select_upload_file' => __('Select a file to use in table', 'wpdatatables'),
            'selectExcelCsv' => __('Select an Excel or CSV file', 'wpdatatables'),
            'sEmptyTable' => __('No data available in table', 'wpdatatables'),
            'settings_saved_successful' => __('Plugin settings saved successfully', 'wpdatatables'),
            'shortcodeSaved' => __('Shortcode has been copied to the clipboard.', 'wpdatatables'),
            'sInfo' => __('Showing _START_ to _END_ of _TOTAL_ entries', 'wpdatatables'),
            'sInfoEmpty' => __('Showing 0 to 0 of 0 entries', 'wpdatatables'),
            'sInfoFiltered' => __('(filtered from _MAX_ total entries)', 'wpdatatables'),
            'sInfoPostFix' => '',
            'sInfoThousands' => __(',', 'wpdatatables'),
            'sLengthMenu' => __('Show _MENU_ entries', 'wpdatatables'),
            'sLoadingRecords' => __('Loading...', 'wpdatatables'),
            'sProcessing' => __('Processing...', 'wpdatatables'),
            'sqlError' => __('SQL error', 'wpdatatables'),
            'sSearch' => __('Search: ', 'wpdatatables'),
            'success' => __('Success!', 'wpdatatables'),
            'sZeroRecords' => __('No matching records found', 'wpdatatables'),
            'tableSaved' => __('Table saved successfully!', 'wpdatatables'),
            'to' => __('To', 'wpdatatables')
        );
    }

    /**
     * Helper function that returns an array with date and time settings from wp_options
     * @return array
     */
    public static function getDateTimeSettings()
    {
        return array(
            'wdtDateFormat' => get_option('wdtDateFormat'),
            'wdtTimeFormat' => get_option('wdtTimeFormat')
        );
    }

    /**
     * Helper function that define default value
     * @param $possible
     * @param $index
     * @param string $default
     * @return string
     */
    public static function defineDefaultValue($possible, $index, $default = '')
    {
        return isset($possible[$index]) ? $possible[$index] : $default;
    }

    /**
     * Helper function that extract column headers in array
     * @param $rawDataArr
     * @return array
     * @throws WDTException
     */
    public static function extractHeaders($rawDataArr)
    {
        reset($rawDataArr);
        if (!is_array($rawDataArr[key($rawDataArr)])) {
            throw new WDTException('Please provide a valid 2-dimensional array.');
        }
        return array_keys($rawDataArr[key($rawDataArr)]);
    }

    /**
     * Helper function that detect columns data type
     * @param $rawDataArr
     * @param $headerArr
     * @return array
     * @throws WDTException
     */
    public static function detectColumnDataTypes($rawDataArr, $headerArr)
    {
        $autodetectData = array();
        $autodetectRowsCount = (10 > count($rawDataArr)) ? count($rawDataArr) - 1 : 9;
        $wdtColumnTypes = array();
        for ($i = 0; $i <= $autodetectRowsCount; $i++) {
            foreach ($headerArr as $key) {
                $cur_val = current($rawDataArr);
                if (!is_array($cur_val[$key])) {
                    $autodetectData[$key][] = $cur_val[$key];
                } else {
                    if (array_key_exists('value', $cur_val[$key])) {
                        $autodetectData[$key][] = $cur_val[$key]['value'];
                    } else {
                        throw new WDTException('Please provide a correct format for the cell.');
                    }
                }
            }
            next($rawDataArr);
        }
        foreach ($headerArr as $key) {
            $wdtColumnTypes[$key] = self::wdtDetectColumnType($autodetectData[$key]);
        }
        return $wdtColumnTypes;
    }

    /**
     * Helper function that convert XML to Array
     * @param $xml SimpleXMLElement
     * @param bool $root
     * @return array|string
     */
    public static function convertXMLtoArr($xml, $root = true)
    {
        if (!$xml->children()) {
            return (string)$xml;
        }

        $array = array();
        foreach ($xml->children() as $element => $node) {
            $totalElement = count($xml->{$element});

            // Has attributes
            if ($attributes = $node->attributes()) {
                $data = array(
                    'attributes' => array(),
                    'value' => (count($node) > 0) ? self::xmlToArray($node, false) : (string)$node
                );

                foreach ($attributes as $attr => $value) {
                    $data['attributes'][$attr] = (string)$value;
                }

                $array[] = $data['attributes'];
            } else {
                if ($totalElement > 1) {
                    $array[][] = self::convertXMLtoArr($node, false);
                } else {
                    $array[$element] = self::convertXMLtoArr($node, false);
                }
            }
        }

        return $array;
    }

    /**
     * Helper function that check if the array is associative
     * @param $arr
     * @return bool
     */
    public static function isArrayAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Helper function that detect single column type
     * @param $values
     * @return string
     */
    private static function wdtDetectColumnType($values)
    {
        if (self::_detect($values, 'self::wdtIsInteger')) {
            return 'int';
        }
        if (self::_detect($values, 'preg_match', WDT_TIME_12H_REGEX) || self::_detect($values, 'preg_match', WDT_TIME_24H_REGEX)) {
            return 'time';
        }
        if (self::_detect($values, 'self::wdtIsDateTime')) {
            return 'datetime';
        }
        if (self::_detect($values, 'self::wdtIsDate')) {
            return 'date';
        }
        if (self::_detect($values, 'preg_match', WDT_CURRENCY_REGEX) || self::wdtIsFloat($values)) {
            return 'float';
        }
        if (self::_detect($values, 'preg_match', WDT_EMAIL_REGEX)) {
            return 'email';
        }
        if (self::_detect($values, 'preg_match', WDT_URL_REGEX)) {
            return 'link';
        }
        return 'string';
    }


    /** @noinspection PhpUnusedPrivateMethodInspection
     * Function that checks if the passed value is integer
     * wdtIsInteger(23); //bool(true)
     * wdtIsInteger("23"); //bool(true)
     * @param $input
     * @return bool
     */
    private static function wdtIsInteger($input)
    {
        return ctype_digit((string)$input);
    }

    /**
     * Function that checks if the passed values are float
     * @param $values
     * @return bool
     */
    private static function wdtIsFloat($values)
    {
        $count = 0;
        for ($i = 0; $i < count($values); $i++) {
            if (is_numeric(str_replace(array('.', ','), '', $values[$i]))) {
                $count++;
            }
        }

        return $count == count($values);
    }


    /** @noinspection PhpUnusedPrivateMethodInspection
     * Function that checks if the passed value is date
     * @param $input
     * @return bool
     */
    private static function wdtIsDate($input)
    {
        return strlen($input) > 5 &&
            (
                strtotime($input) ||
                strtotime(str_replace('/', '-', $input)) ||
                strtotime(str_replace(array('.', '-'), '/', $input))
            );
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * Function that checks if the passed values is datetime
     * @param $input
     * @return bool
     */
    private static function wdtIsDateTime($input)
    {
        return (
                strtotime($input) ||
                strtotime(str_replace('/', '-', $input)) ||
                strtotime(str_replace(array('.', '-'), '/', $input))
            ) &&
            (
                call_user_func('preg_match', WDT_TIME_12H_REGEX, substr($input, strpos($input, ':') - 2, 5)) ||
                call_user_func('preg_match', WDT_TIME_24H_REGEX, substr($input, strpos($input, ':') - 2, 5))

            );
    }

    /**
     * @param $valuesArray
     * @param $checkFunction
     * @param string $regularExpression
     * @return bool
     * @throws WDTException
     */
    private static function _detect($valuesArray, $checkFunction, $regularExpression = '')
    {
        if (!is_callable($checkFunction)) {
            throw new WDTException('Please provide a valid type detection function for wpDataTables');
        }
        $count = 0;
        for ($i = 0; $i < count($valuesArray); $i++) {
            if ($regularExpression != '') {
                if (call_user_func($checkFunction, $regularExpression, $valuesArray[$i]) || $valuesArray[$i] == null) {
                    $count++;
                } else {
                    return false;
                }
            } else {
                if (call_user_func($checkFunction, $valuesArray[$i]) || $valuesArray[$i] == null) {
                    $count++;
                } else {
                    return false;
                }
            }
        }
        if ($count == count($valuesArray)) {
            return true;
        }
        return false;
    }

    /**
     * Helper function that converts PHP to Moment Date Format
     * @param $dateFormat
     * @return string
     */
    public static function convertPhpToMomentDateFormat($dateFormat)
    {
        $replacements = array(
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        );

        return strtr($dateFormat, $replacements);
    }

    /**
     * Helper method to wrap values in quotes for DB
     */
    public static function wrapQuotes($value)
    {
        $valueQuote = get_option('wdtUseSeparateCon') ? "'" : '';
        return $valueQuote . $value . $valueQuote;
    }

    /**
     * Helper method to detect the headers that are present in formula
     * @param $formula
     * @param $headers
     * @return array
     */
    public static function getColHeadersInFormula($formula, $headers)
    {
        $headersInFormula = array();
        foreach ($headers as $header) {
            if (strpos($formula, $header) !== false) {
                $headersInFormula[] = $header;
            }
        }
        return $headersInFormula;
    }

    /**
     * Helper function which converts WP upload URL to Path
     * @param $uploadUrl
     * @return mixed
     */
    public static function urlToPath($uploadUrl)
    {
        $uploadsDir = wp_upload_dir();
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $uploadPath = str_replace($uploadsDir['baseurl'], str_replace('\\', '/', $uploadsDir['basedir']), $uploadUrl);
        } else {
            $uploadPath = str_replace($uploadsDir['baseurl'], $uploadsDir['basedir'], $uploadUrl);
        }
        return $uploadPath;
    }

    /**
     * Helper function which converts upload path to URL
     * @param $uploadPath
     * @return mixed
     */
    public static function pathToUrl($uploadPath)
    {
        $uploadsDir = wp_upload_dir();
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $uploadUrl = str_replace(str_replace('\\', '/', $uploadsDir['basedir']), $uploadsDir['baseurl'], $uploadPath);
        } else {
            $uploadUrl = str_replace($uploadsDir['basedir'], $uploadsDir['baseurl'], $uploadPath);
        }
        return $uploadUrl;
    }


    /**
     * Helper function that convert hex color to rgba
     * @param $color
     * @param bool $opacity
     * @return string
     */
    public static function hex2rgba($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    /**
     * Sanitizes the cell string and wraps it with quotes
     * @param $string
     *
     * @return string
     */
    public static function prepareStringCell($string)
    {

        if (self::isHtml($string)) {
            $string = self::stripJsAttributes($string);
        }
        $string = self::wrapQuotes($string);
        return $string;
    }

    /**
     * Check if passed string is HTML element
     * @param $string
     * @return bool
     */
    public static function isHtml($string)
    {
        return preg_match("/<[^<]+>/", $string, $m) != 0;
    }

    /**
     * Function that strip JS attributes to prevent XSS attacks
     * @param $htmlString
     * @return bool|string
     */
    public static function stripJsAttributes($htmlString)
    {
        $htmlString = stripcslashes($htmlString);
        $htmlString = '<div>' . $htmlString . '</div>';
        $domd = new DOMDocument();
        $domd->loadHTML(mb_convert_encoding($htmlString, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        foreach ($domd->getElementsByTagName('*') as $node) {
            $remove = array();
            foreach ($node->attributes as $attributeName => $attribute) {
                if (substr($attributeName, 0, 2) == 'on') {
                    $remove[] = $attributeName;
                }
            }
            foreach ($remove as $i) {
                $node->removeAttribute($i);
            }
        }
        return substr($domd->saveHTML($domd->documentElement), 5, -6);
    }

    /**
     * Enqueue JS and CSS UI Kit files
     */
    public static function wdtUIKitEnqueue()
    {
        wp_enqueue_style('wdt-bootstrap', WDT_CSS_PATH . 'bootstrap/wpdatatables-bootstrap.css');
        wp_enqueue_style('wdt-bootstrap-select', WDT_CSS_PATH . 'bootstrap/bootstrap-select/bootstrap-select.min.css');
        wp_enqueue_style('wdt-bootstrap-tagsinput', WDT_CSS_PATH . 'bootstrap/bootstrap-tagsinput/bootstrap-tagsinput.css');
        wp_enqueue_style('wdt-bootstrap-datetimepicker', WDT_CSS_PATH . 'bootstrap/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css');
        wp_enqueue_style('wdt-wp-bootstrap-datetimepicker', WDT_CSS_PATH . 'bootstrap/bootstrap-datetimepicker/wdt-bootstrap-datetimepicker.css');
        wp_enqueue_style('wdt-bootstrap-colorpicker', WDT_CSS_PATH . 'bootstrap/bootstrap-colorpicker/bootstrap-colorpicker.min.css');
        wp_enqueue_style('wdt-animate', WDT_CSS_PATH . 'animate/animate.min.css');
        wp_enqueue_style('wdt-uikit', WDT_CSS_PATH . 'uikit/uikit.css');
        wp_enqueue_style('wdt-waves', WDT_CSS_PATH . 'waves/waves.min.css');
        wp_enqueue_style('wdt-iconic-font', WDT_CSS_PATH . 'material-design-iconic-font/css/material-design-iconic-font.min.css');

        if (!is_admin() && get_option('wdtIncludeBootstrap') == 1) {
            wp_enqueue_script('wdt-bootstrap', WDT_JS_PATH . 'bootstrap/bootstrap.min.js', array('jquery', 'wdt-bootstrap-select'), WDT_CURRENT_VERSION, true);
        } else if (is_admin() && get_option('wdtIncludeBootstrapBackEnd') == 1) {
            wp_enqueue_script('wdt-bootstrap', WDT_JS_PATH . 'bootstrap/bootstrap.min.js', array('jquery', 'wdt-bootstrap-select'), WDT_CURRENT_VERSION, true);
        } else {
            wp_enqueue_script('wdt-bootstrap', WDT_JS_PATH . 'bootstrap/noconf.bootstrap.min.js', array('jquery', 'wdt-bootstrap-select'), WDT_CURRENT_VERSION, true);
        }

        wp_enqueue_script('wdt-bootstrap-select', WDT_JS_PATH . 'bootstrap/bootstrap-select/bootstrap-select.min.js', array(), false, true);
        wp_enqueue_script('wdt-bootstrap-tagsinput', WDT_JS_PATH . 'bootstrap/bootstrap-tagsinput/bootstrap-tagsinput.js', array(), false, true);
        wp_enqueue_script('wdt-moment', WDT_JS_PATH . 'moment/moment.js', array(), false, true);
        wp_enqueue_script('wdt-bootstrap-datetimepicker', WDT_JS_PATH . 'bootstrap/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js', array(), false, true);
        wp_enqueue_script('wdt-bootstrap-colorpicker', WDT_JS_PATH . 'bootstrap/bootstrap-colorpicker/bootstrap-colorpicker.min.js', array(), false, true);
        wp_enqueue_script('wdt-bootstrap-growl', WDT_JS_PATH . 'bootstrap/bootstrap-growl/bootstrap-growl.min.js', array(), false, true);
        wp_enqueue_script('wdt-waves', WDT_JS_PATH . 'waves/waves.min.js', array(), false, true);
    }

    /**
     * Helper method to add PHP vars to JS vars
     * @param $varName
     * @param $phpVar
     */
    public static function exportJSVar($varName, $phpVar)
    {
        self::$jsVars[$varName] = $phpVar;
    }

    /**
     * Helper method to print PHP vars to JS vars
     */
    public static function printJSVars()
    {
        if (!empty(self::$jsVars)) {
            $jsBlock = '<script type="text/javascript">';
            foreach (self::$jsVars as $varName => $jsVar) {
                $jsBlock .= "var {$varName} = " . json_encode($jsVar) . ";";
            }
            $jsBlock .= '</script>';
            echo $jsBlock;
        }
    }

    /**
     * Helper method that converts provided String to Unix Timestamp
     * based on provided date format
     * @param $dateString
     * @param $dateFormat
     * @return false|int
     */
    public static function wdtConvertStringToUnixTimestamp($dateString, $dateFormat)
    {
        if (null !== $dateFormat && $dateFormat == 'd/m/Y') {
            $returnDate = strtotime(str_replace('/', '-', $dateString));
        } else if (null !== $dateFormat && in_array($dateFormat, ['m.d.Y', 'm-d-Y'])) {
            $returnDate = strtotime(str_replace(['.', '-'], '/', $dateString));
        } else {
            $returnDate = strtotime($dateString);
        }

        return $returnDate;
    }

    /**
     * Show error message
     * @param $errorMessage
     * @return string
     */
    public static function wdtShowError($errorMessage)
    {
        self::wdtUIKitEnqueue();
        ob_start();
        include WDT_ROOT_PATH . 'templates/common/error.inc.php';
        $errorBlock = ob_get_contents();
        ob_end_clean();
        return $errorBlock;
    }

    /**
     * Helper function to generate unique MySQL column headers
     * @param $header
     * @param $existing_headers
     * @return mixed|string
     */
    public static function generateMySQLColumnName($header, $existing_headers)
    {
        // Prepare the column MySQL title
        $column_header = self::slugify($header);

        // Add index until column header becomes unique
        if (in_array($column_header, $existing_headers)) {
            $index = 0;
            do {
                $index++;
                $try_column_header = $column_header . $index;
            } while (in_array($try_column_header, $existing_headers));
            $column_header = $try_column_header;
        }

        return $column_header;
    }

    /**
     * Helper function to translate special UTF-8 to latin for MySQL
     * @param $text
     * @return mixed|string
     */
    private static function slugify($text)
    {
        // replace non letter or digits by _
        $text = preg_replace('#[^\\pL\d]+#u', '_', $text);

        // trim
        $text = trim($text, '_');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        // WP sanitize
        $text = str_replace(array('-', '_'), '', sanitize_title($text));

        if (empty($text) || is_numeric($text)) {
            return 'wdtcolumn';
        }

        return $text;
    }


}

add_action('admin_footer', array('WDTTools', 'printJSVars'), 100);
