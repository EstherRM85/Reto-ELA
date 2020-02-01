<?php
namespace sgpb;
use \DateTime;
use \DateTimeZone;
use \SgpbDataConfig;
use \Elementor;
use sgpbsubscriptionplus\SubscriptionPlusAdminHelper;

class AdminHelper
{
	/**
	 * Get extension options data which are included inside the free version
	 *
	 * @since 3.0.8
	 *
	 * @return assoc array $extensionOptions
	 */
	public static function getExtensionAvaliabilityOptions()
	{
		$extensionOptions = array();
		// advanced closing option
		$extensionOptions[SGPB_POPUP_ADVANCED_CLOSING_PLUGIN_KEY] = array(
			'sgpb-close-after-page-scroll',
			'sgpb-auto-close',
			'sgpb-enable-popup-overlay',
			'sgpb-disable-popup-closing'
		);
		// schedule extension
		$extensionOptions[SGPB_POPUP_SCHEDULING_EXTENSION_KEY] = array(
			'otherConditionsMetaBoxView'
		);
		// geo targeting extension
		$extensionOptions[SGPB_POPUP_GEO_TARGETING_EXTENSION_KEY] = array(
			'popupConditionsSection'
		);
		// advanced targeting extension
		$extensionOptions[SGPB_POPUP_ADVANCED_TARGETING_EXTENSION_KEY] = array(
			'popupConditionsSection'
		);

		return $extensionOptions;
	}

	public static function getPopupTypesPageURL()
	{
		return admin_url('edit.php?post_type='.SG_POPUP_POST_TYPE.'&page='.SG_POPUP_POST_TYPE);
	}

	public static function getSettingsURL($args = array())
	{
		$url = admin_url('/edit.php?post_type='.SG_POPUP_POST_TYPE.'&page='.SG_POPUP_SETTINGS_PAGE);

		return self::addArgsToURl($url, $args);
	}

	public static function getPopupExportURL()
	{
		$exportURL = admin_url('export.php');
		$url = add_query_arg(array(
			'download' => true,
			'content' => SG_POPUP_POST_TYPE,
			'sgpbExportAction' => 1
		), $exportURL);

		return $url;
	}

	public static function addArgsToURl($url, $args = array())
	{
		$resultURl = add_query_arg($args, $url);

		return $resultURl;
	}

	public static function buildCreatePopupUrl($popupType)
	{
		$isAvailable = $popupType->isAvailable();
		$name = $popupType->getName();

		$popupUrl = SG_POPUP_ADMIN_URL.'post-new.php?post_type='.SG_POPUP_POST_TYPE.'&sgpb_type='.$name;

		if (!$isAvailable) {
			$popupUrl = SG_POPUP_PRO_URL;
		}

		return $popupUrl;
	}

	public static function getPopupThumbClass($popupType)
	{
		$isAvailable = $popupType->isAvailable();
		$name = $popupType->getName();

		$popupTypeClassName = $name.'-popup';

		if (!$isAvailable) {
			$popupTypeClassName .= '-pro';
		}

		return $popupTypeClassName;
	}

	public static function getPopupTargetParam($param)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$targetData = $SGPB_DATA_CONFIG_ARRAY['target'];

		if (empty($targetData[$param])) {
			return '';
		}

		return $targetData[$param];
	}

	public static function getPopupTargetParamType($param)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$targetDataTypes = $SGPB_DATA_CONFIG_ARRAY['target']['types'];

		if (empty($targetDataTypes[$param])) {
			return '';
		}

		return $targetDataTypes[$param];
	}

	public static function createSelectBox($data, $selectedValue, $attrs)
	{
		$attrString = '';
		$selected = '';
		$selectBoxCloseTag = '</select>';

		if (!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$selectBox = '<select '.$attrString.'>';
		if (empty($data) || !is_array($data)) {
			$selectBox .= $selectBoxCloseTag;
			return $selectBox;
		}

		foreach ($data as $value => $label) {
			// When is multiSelect
			if (is_array($selectedValue)) {
				$isSelected = in_array($value, $selectedValue);
				if ($isSelected) {
					$selected = 'selected';
				}
			}
			else if ($selectedValue == $value) {
				$selected = 'selected';
			}
			else if (is_array($value) && in_array($selectedValue, $value)) {
				$selected = 'selected';
			}

			if (is_array($label)) {
				$selectBox .= '<optgroup label="'.$value.'">';
				foreach ($label as $key => $optionLabel) {
					$selected = '';
					if (is_array($selectedValue)) {
						$isSelected = in_array($key, $selectedValue);
						if ($isSelected) {
							$selected = 'selected';
						}
					}
					else if ($selectedValue == $key) {
						$selected = 'selected';
					}
					else if (is_array($key) && in_array($selectedValue, $key)) {
						$selected = 'selected';
					}

					$selectBox .= '<option value="'.$key.'" '.$selected.'>'.$optionLabel.'</option>';
				}
				$selectBox .= '</optgroup>';
			}
			else {
				$selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
			}

			$selected = '';
		}

		$selectBox .= $selectBoxCloseTag;

		return $selectBox;
	}

	public static function createInput($data, $selectedValue, $attrs)
	{
		$attrString = '';
		$savedData = $data;

		if (isset($selectedValue)) {
			$savedData = $selectedValue;
		}
		if (empty($savedData)) {
			$savedData = '';
		}

		if (!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				if ($attrName == 'class') {
					$attrValue .= ' sgpb-full-width-events form-control';
				}
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$input = "<input $attrString value=\"".esc_attr($savedData)."\">";

		return $input;
	}

	public static function createCheckBox($data, $selectedValue, $attrs)
	{
		$attrString = '';
		$checked = '';

		if (!empty($selectedValue)) {
			$checked = 'checked';
		}
		if (!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$input = "<input $attrString $checked>";

		return $input;
	}

	public static function createRadioButtons($elements, $name, $selectedInput, $lineMode = false)
	{
		$str = '';

		foreach ($elements as $key => $element) {;
			$value = '';
			$checked = '';

			if (isset($element['value'])) {
				$value = $element['value'];
			}
			if ($element['value'] == $selectedInput) {
				$checked = 'checked';
			}
			$attrStr = '';
			if (isset($element['data-attributes'])) {
				foreach ($element['data-attributes'] as $attrKey => $dataValue) {
					$attrStr .= $attrKey.'="'.esc_attr($dataValue).'" ';
				}
			}

			if ($lineMode) {
				$str .= '<input type="radio" name="'.esc_attr($name).'" value="'.esc_attr($value).'" '.$checked.' '.$attrStr.'>';
			}
			else {
				$str .= '<div class="row form-group">';
				$str .= '<label class="col-md-5 control-label">'.__($element['title'], SG_POPUP_TEXT_DOMAIN).'</label>';
				$str .= '<div class="col-sm-7"><input type="radio" name="'.esc_attr($name).'" value="'.esc_attr($value).'" '.$checked.' autocomplete="off"></div>';
				$str .= '</div>';
			}
		}

		echo $str;
	}

	public static function getDateObjFromDate($dueDate, $timezone = 'America/Los_Angeles', $format = 'Y-m-d H:i:s')
	{
		$dateObj = new DateTime($dueDate, new DateTimeZone($timezone));
		$dateObj->format($format);

		return $dateObj;
	}

	/**
	 * Serialize data
	 *
	 * @since 1.0.0
	 *
	 * @param array $data
	 *
	 * @return string $serializedData
	 */
	public static function serializeData($data = array())
	{
		$serializedData = serialize($data);

		return $serializedData;
	}

	/**
	 * Get correct size to use it safely inside CSS rules
	 *
	 * @since 1.0.0
	 *
	 * @param string $dimension
	 *
	 * @return string $size
	 */
	public static function getCSSSafeSize($dimension)
	{
		if (empty($dimension)) {
			return 'inherit';
		}

		$size = (int)$dimension.'px';
		// If user write dimension in px or % we give that dimension to target otherwise the default value will be px
		if (strpos($dimension, '%') || strpos($dimension, 'px')) {
			$size = $dimension;
		}

		return $size;
	}

	public static function deleteSubscriptionPopupSubscribers($popupId)
	{
		global $wpdb;

		$prepareSql = $wpdb->prepare('DELETE FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE subscriptionType = %s', $popupId);
		$wpdb->query($prepareSql);
	}

	public static function subscribersRelatedQuery($query = '', $additionalColumn = '')
	{
		global $wpdb;
		$subscribersTablename = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;
		$postsTablename = $wpdb->prefix.SGPB_POSTS_TABLE_NAME;

		if ($query == '') {
			$query = 'SELECT firstName, lastName, email, cDate, '.$additionalColumn.' '.$postsTablename.'.post_title AS subscriptionTitle FROM '.$subscribersTablename.' ';
		}
		$searchQuery = ' unsubscribed <> 1';
		$filterCriteria = '';

		$query .= ' LEFT JOIN '.$postsTablename.' ON '.$postsTablename.'.ID='.$subscribersTablename.'.subscriptionType';

		if (isset($_GET['sgpb-subscription-popup-id']) && !empty($_GET['sgpb-subscription-popup-id'])) {
			$filterCriteria = esc_sql($_GET['sgpb-subscription-popup-id']);
			if ($filterCriteria != 'all') {
				$searchQuery .= " AND (subscriptionType = $filterCriteria)";
			}
		}
		if ($filterCriteria != '' && $filterCriteria != 'all' && isset($_GET['s']) && !empty($_GET['s'])) {
			$searchQuery .= ' AND ';
		}
		if (isset($_GET['s']) && !empty($_GET['s'])) {
			$searchCriteria = esc_sql($_GET['s']);
			$searchQuery .= " (firstName LIKE '%$searchCriteria%' or lastName LIKE '%$searchCriteria%' or email LIKE '%$searchCriteria%' or $postsTablename.post_title LIKE '%$searchCriteria%')";
		}
		if (isset($_GET['sgpb-subscribers-date']) && !empty($_GET['sgpb-subscribers-date'])) {
			$filterCriteria = esc_sql($_GET['sgpb-subscribers-date']);
			if ($filterCriteria != 'all') {
				if ($searchQuery != '') {
					$searchQuery .= ' AND ';
				}
				$searchQuery .= " cDate LIKE '$filterCriteria%'";
			}
		}
		if ($searchQuery != '') {
			$query .= " WHERE $searchQuery";
		}

		return $query;
	}

	public static function themeRelatedSettings($popupId, $buttonPosition, $theme)
	{
		if ($popupId) {
			if ($theme == 'sgpb-theme-1' || $theme == 'sgpb-theme-4' || $theme == 'sgpb-theme-5') {
				if (isset($buttonPosition)) {
					$buttonPosition = $buttonPosition;
				}
				else {
					$buttonPosition = 'bottomRight';
				}
			}
			else if ($theme == 'sgpb-theme-2' || $theme == 'sgpb-theme-3' || $theme == 'sgpb-theme-6') {
				if (isset($buttonPosition)) {
					$buttonPosition = $buttonPosition;
				}
				else {
					$buttonPosition = 'topRight';
				}
			}
		}
		else {
			if (isset($theme)) {
				if ($theme == 'sgpb-theme-1' || $theme == 'sgpb-theme-4' || $theme == 'sgpb-theme-5') {
					$buttonPosition = 'bottomRight';
				}
				else if ($theme == 'sgpb-theme-2' || $theme == 'sgpb-theme-3' || $theme == 'sgpb-theme-6') {
					$buttonPosition = 'topRight';
				}
			}
			else {
				/* by default set position for the first theme */
				$buttonPosition = 'bottomRight';
			}
		}

		return $buttonPosition;
	}

	/**
	 * Create html attrs
	 *
	 * @since 1.0.0
	 *
	 * @param array $attrs
	 *
	 * @return string $attrStr
	 */
	public static function createAttrs($attrs)
	{
		$attrStr = '';

		if (empty($attrs)) {
			return $attrStr;
		}

		foreach ($attrs as $attrKey => $attrValue) {
			$attrStr .= $attrKey.'="'.$attrValue.'" ';
		}

		return $attrStr;
	}

	public static function getFormattedDate($date)
	{
		$date = strtotime($date);
		$month = date('F', $date);
		$year = date('Y', $date);

		return $month.' '.$year;
	}

	public static function defaultButtonImage($theme, $closeImage = '')
	{
		$currentPostType = self::getCurrentPopupType();
		if (defined('SGPB_POPUP_TYPE_RECENT_SALES') && $currentPostType == SGPB_POPUP_TYPE_RECENT_SALES) {
			$theme = 'sgpb-theme-6';
		}
		// if no image, set default by theme
		if ($closeImage == '') {
			if ($theme == 'sgpb-theme-1' || !$theme) {
				$closeImage = 'iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAADHElEQVQ4jaWVQU8bRxiGHxYHrwdbHW8gFhU5IKurWM0plTiQC1SqUFD6C7hEorfmSv9ChBC39NZDFCm3xrkl6gWoKjBCizgRoS0uh0Y1BjQ7ks3sGkK2B2MLY4dE7Xvd93v0zXzfvNsXxzG9VKlUprXWk8aYceAOkAOqwK4QYlNKuToyMvJbr9q+q1Ct9bflcvkRMCuEsNKZDIn+/vb39+fn1Gs1jDEfgBf5fP6ZlHL5o9D9/f0flFLzQgjXGf4SkRrgRn8ftm23PVEUcXYeY8JT1NE/GGN8x3EWx8bGfml5EleAT27lckOZdBopv+h5LbZtYwOZwRTJREytXncPq9UnAC1wonVkpdT8rVxuSGZvkhlM9QRelZSS/htJgKHDanU+m83+JaVctgDK5fIjIYSbSac/G9hSZjBFJp1GCOFezIJEpVKZBmallEgp2+a/373jT98H4CvX5fboKABr6+s0ooikbXN/YqLdcRiGGGNmK5XKC0trPSmEsFKpzg5l9iZ7e3uUSiXevH5N7SRkbX2d1ZUVSqUSTjbb4b+ot7TWk5YxZjydyXRMuHWsBzMzABwdHfHH7yusr60BUCgUKBQKHX7btnEcB2PMuEVzsXvq9ugok1NTAGxvb3N6esrw8DDfTT/o8l5q6o5F86V0ddrSN/fuMTAwQL1eZ2dnh6/v3v3UMHMWUH1/dvZRR7H4io2NDXzfp9Fo8PPTp2itr4NWLWAXmi/lqra2tnj58lcA5ubmSCaTNBoNFhYWuryX6nctIcSmUqoLqrVmaWkJANd1mZmZ4cfHjwHwfZ9isdgFVUohhNhMSClXjTE/hWFoXd7TnbdvefjwewCmpiYBuD8xQfXggChqEEWNDmgYhgAfpJSrxHGM53nPPc+LgyCI/4uCIIg9z4s9z3sexzEWQD6ffwb4QRBQOwmvG0KXaichQRAA+BecJlRKuew4zqJS6vj48OBT021La83x4QFKqWPHcRZbudqOvlZsKaXmoyhywzAklUo1o+5KnkZRRBiGaK175um1yQ9YjuMAzcfR2hClFMDnJf9l/Z9/1L81r78oUzK1YgAAAABJRU5ErkJggg==';
			}
			else if ($theme == 'sgpb-theme-2') {
				$closeImage = 'iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAAXNSR0IArs4c6QAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABWWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS40LjAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyI+CiAgICAgICAgIDx0aWZmOk9yaWVudGF0aW9uPjE8L3RpZmY6T3JpZW50YXRpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgpMwidZAAABWUlEQVQ4Ee2SS46CQBCGf8gAcgHFG+jGtfEWXIBHOBcKR+EScAQfG90RSGCGvyY9MtIuTFzMYippuqur6qO6qozPQfBGMd/IEtQ/8F7R2+0GLorqm9qv16vYlH6PAj7Gijqfz2fs93sYhoEwDLFcLsVE/XQ6Icsy0eM4xmKxUGGya5tSVRWappHFYEIoCkZb27Yoy1Luxx8tcLVaYTabSYYMJrQoCtmpU2zbxnq9HrPkbAx1mAw2ry6XC9I0Rdd16Pv+23l4Ms/8WRRF8DxvAtRmyFrN53MkSQLLsn41hbZnMNK1QBoYyEy5THPqpnkYw54Dj8cj8jyX4vOZYwBryknQyfTXgxedCavrWmJc14Xv+3AcR3Q25nA4/HR/DNYC1djw2YQEQYDNZiO1U1CODf0eRQvcbrfSFNVNDjbh7CobQigHerfbPfKgHZuJ1wsX2gxfiJ+4/n3gF7OOrAbt6WEPAAAAAElFTkSuQmCC';
			}
			else if ($theme == 'sgpb-theme-3') {
				$closeImage = 'iVBORw0KGgoAAAANSUhEUgAAACcAAAAUCAMAAAA5k9QEAAAAllBMVEVHcEwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC0tbUhISHFxcYICAitr7EqKipKSkrMzMzHyMqIiouQkZO9vb0QEBB7e3tDRESlpaapq61qamvBw8Tk5ebe3t/R0tMZGRo6Ojp/gIGgoqRSUlLq6uuZmZnW1te3ubt2d3hf8fIfAAAAEXRSTlMAzFVEMRFmd7vumd2qbCKID4nBdwUAAADPSURBVCjPhdLXEoIwFATQBSIIVkKxACrdXv7/50RCDQj7krk7hwyTBERa6KNZEIDIwhjTxDV+EdUhJRCUIQNQmKCOVNVntmzKedZkwLyoj1Eu3Me9KOQWg8haw9yZGXTjmLJCBZfi8+0+g06S0AOb57wTapimL7tgusK7qV7B/a7cbch9vMg0t6MupO+n7XmnERcGtmsZAaWnQWfcfMfKFt92eo8PUFh/uVq5P1psXvJu0nvFU+DPhu2QroPWZTL6suKUKqI/ktb4yZnSfFFfkB8jKwCptUAAAAAASUVORK5CYII=';
			}
			else if ($theme == 'sgpb-theme-5') {
				$closeImage = 'iVBORw0KGgoAAAANSUhEUgAAABEAAAARCAYAAAA7bUf6AAAAAXNSR0IArs4c6QAAAVlpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDUuNC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KTMInWQAAAVRJREFUOBGtVDuugzAQXBwhIA1UUKWgoOMAnIETcJwchxNwEgokSiqQUtAEEL/HWNongwJ6T8oWxti7w+CZtfZ6vdZ5nilNU2rblpIkIdd1qXu/aVkW4hBCkHW/U9M0MjcIAorjmMZxJMEA2ByGQSZgjgIUIo4AyMvznLIsI13X6fZ4PJ4o4gBoURTk+z45jkPrxsa0rF8GAOBAHdgLDMdQGZ0BcE1ZlqRtw4rzUL/ACYZhyPOpqoqXdk/s4wy1jckKWmdAuyrlhQEggoAKmAARG38JFQD1AjL+B+gIgHqpISZD35PneZLVFRv4A3nIZx9JEPjAME2q61pKeQUCNZCHfMVHeyd+UkkFVeVnQ35HnSt5cYiboz+qpjK6bfI+P/0CqxBFkWwBtAJaQg1uEWHbtrou5wwA//Rdd+kjqCW49RlJBYB/pmk69VEYhvI60L5xn/wAjtgb/fA0ZZYAAAAASUVORK5CYII=';
			}
			else if ($theme == 'sgpb-theme-6') {
				$closeImage = 'iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAA6HWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxMzggNzkuMTU5ODI0LCAyMDE2LzA5LzE0LTAxOjA5OjAxICAgICAgICAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgICAgICAgICAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIKICAgICAgICAgICAgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIgogICAgICAgICAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgICAgICAgICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iCiAgICAgICAgICAgIHhtbG5zOmV4aWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vZXhpZi8xLjAvIj4KICAgICAgICAgPHhtcDpDcmVhdGVEYXRlPjIwMTgtMDItMjdUMTQ6MTQ6MzgrMDQ6MDA8L3htcDpDcmVhdGVEYXRlPgogICAgICAgICA8eG1wOk1vZGlmeURhdGU+MjAxOC0wMi0yN1QxNDoxNjoxMSswNDowMDwveG1wOk1vZGlmeURhdGU+CiAgICAgICAgIDx4bXA6TWV0YWRhdGFEYXRlPjIwMTgtMDItMjdUMTQ6MTY6MTErMDQ6MDA8L3htcDpNZXRhZGF0YURhdGU+CiAgICAgICAgIDx4bXA6Q3JlYXRvclRvb2w+QWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKFdpbmRvd3MpPC94bXA6Q3JlYXRvclRvb2w+CiAgICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2UvcG5nPC9kYzpmb3JtYXQ+CiAgICAgICAgIDxwaG90b3Nob3A6Q29sb3JNb2RlPjM8L3Bob3Rvc2hvcDpDb2xvck1vZGU+CiAgICAgICAgIDx4bXBNTTpJbnN0YW5jZUlEPnhtcC5paWQ6MWNlZWE3YWMtNTIxMC02MjQ2LWFiMDQtZTA1YmEwYjljOTQ1PC94bXBNTTpJbnN0YW5jZUlEPgogICAgICAgICA8eG1wTU06RG9jdW1lbnRJRD5hZG9iZTpkb2NpZDpwaG90b3Nob3A6MzFlZDk3OGEtMWJhNy0xMWU4LTg0YTctZjA4OTdlNjEzNGM0PC94bXBNTTpEb2N1bWVudElEPgogICAgICAgICA8eG1wTU06T3JpZ2luYWxEb2N1bWVudElEPnhtcC5kaWQ6OWFmYzkxOTgtOWNlNC1lZDQ4LThlNjYtNmFkMzdiNGFmOTQxPC94bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ+CiAgICAgICAgIDx4bXBNTTpIaXN0b3J5PgogICAgICAgICAgICA8cmRmOlNlcT4KICAgICAgICAgICAgICAgPHJkZjpsaSByZGY6cGFyc2VUeXBlPSJSZXNvdXJjZSI+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDphY3Rpb24+c2F2ZWQ8L3N0RXZ0OmFjdGlvbj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0Omluc3RhbmNlSUQ+eG1wLmlpZDo5YWZjOTE5OC05Y2U0LWVkNDgtOGU2Ni02YWQzN2I0YWY5NDE8L3N0RXZ0Omluc3RhbmNlSUQ+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDp3aGVuPjIwMTgtMDItMjdUMTQ6MTY6MTErMDQ6MDA8L3N0RXZ0OndoZW4+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDpzb2Z0d2FyZUFnZW50PkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE3IChXaW5kb3dzKTwvc3RFdnQ6c29mdHdhcmVBZ2VudD4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmNoYW5nZWQ+Lzwvc3RFdnQ6Y2hhbmdlZD4KICAgICAgICAgICAgICAgPC9yZGY6bGk+CiAgICAgICAgICAgICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0iUmVzb3VyY2UiPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6YWN0aW9uPnNhdmVkPC9zdEV2dDphY3Rpb24+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDppbnN0YW5jZUlEPnhtcC5paWQ6MWNlZWE3YWMtNTIxMC02MjQ2LWFiMDQtZTA1YmEwYjljOTQ1PC9zdEV2dDppbnN0YW5jZUlEPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6d2hlbj4yMDE4LTAyLTI3VDE0OjE2OjExKzA0OjAwPC9zdEV2dDp3aGVuPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6c29mdHdhcmVBZ2VudD5BZG9iZSBQaG90b3Nob3AgQ0MgMjAxNyAoV2luZG93cyk8L3N0RXZ0OnNvZnR3YXJlQWdlbnQ+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDpjaGFuZ2VkPi88L3N0RXZ0OmNoYW5nZWQ+CiAgICAgICAgICAgICAgIDwvcmRmOmxpPgogICAgICAgICAgICA8L3JkZjpTZXE+CiAgICAgICAgIDwveG1wTU06SGlzdG9yeT4KICAgICAgICAgPHRpZmY6T3JpZW50YXRpb24+MTwvdGlmZjpPcmllbnRhdGlvbj4KICAgICAgICAgPHRpZmY6WFJlc29sdXRpb24+NzIwMDAwLzEwMDAwPC90aWZmOlhSZXNvbHV0aW9uPgogICAgICAgICA8dGlmZjpZUmVzb2x1dGlvbj43MjAwMDAvMTAwMDA8L3RpZmY6WVJlc29sdXRpb24+CiAgICAgICAgIDx0aWZmOlJlc29sdXRpb25Vbml0PjI8L3RpZmY6UmVzb2x1dGlvblVuaXQ+CiAgICAgICAgIDxleGlmOkNvbG9yU3BhY2U+NjU1MzU8L2V4aWY6Q29sb3JTcGFjZT4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjMwPC9leGlmOlBpeGVsWERpbWVuc2lvbj4KICAgICAgICAgPGV4aWY6UGl4ZWxZRGltZW5zaW9uPjMwPC9leGlmOlBpeGVsWURpbWVuc2lvbj4KICAgICAgPC9yZGY6RGVzY3JpcHRpb24+CiAgIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgCjw/eHBhY2tldCBlbmQ9InciPz7HmtNXAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAjWSURBVHjanFd9bFPXFf/d9/zsZ/v5ObZfAnbIUpI0fCxMhGlJkxaNKpFAKkqntUIIaYRJJR9FiEGoJqoOov1RMVE1UxvSsoIqbVJVCoJuomVlypYh8aERWEoZ6dwSlYJxkmcndj7s5/d19k9s4TZM64509a7ufff+zjn3nHN/l+HRwhhjICIAIAAIBoNuv9/v1XVdBCAA4Fwul+F2u/XZ2VnNNM2ZiYkJ27Is/F/CGGMcxzHGGHM6nS5FUb5XWVnZuHPnzhey2ewJIjpPRKNEdJeI/kVEf83lcr+bn5//6c6dO6uXL1/u5TjuO2MyQRB4nuedHo9naWtra9ulS5fO0oJkMhlSVZXi8TjFYjGKx+Okqipls9n8Lyki+k1XV9fampqaPDr7FtA3+zzPcwAckUjk+z09PZ179uzZAcD54MEDOxqN2oODg45bt25hamoKuq7D5XIhGAyivr4ejY2NZl1dHR+JRBiADBHt37Jlyx9Onz49t7A/LaYAA+AAEFyzZk3LtWvXPiYimp6eppMnTxotLS3k8/koHA5TRUUFVVZWFlpFRQUtXbqUZFmmTZs20ZkzZ/RUKpX3QP/WrVuVR1rO87wAwBeJRDZcuHDhBhHR119/Tdu3bydJkigSiVB1dTVVVVU9slVXV1MkEiFZlqmjo4NisVge/PiWLVskp9NZfKZOp5MnIpdlWSuOHj36i87Ozp89ePAA3d3duHDhAsrLy/PR/T9LPB5HW1sb+vr6EA6HAeClcDjcNz4+bgEAD4C5XC53LpdbsmHDhrY33nhjXyaTsXt6etiHH36IioqK7wwKAH6/H8PDw0gmk1i/fj253e6GL7/88tytW7cmLctinCAIfCaTkWVZXnfgwIHNAHDmzBnr5MmTWLZsGWzbBsdxME0TiUQCqqpC13Xk04XjOORyOUxOTiKRSMA0TTDGYNs2IpEI3nvvPZw7d44AlLz99tsvSJIkAiBeFEUvEZU3Nze39Pb2botGo+zQoUN8JpOBw+EAABiGAZ/Ph40bN2Lt2rWIxWKYmpqCJEmYnp6Goihoa2tDbW0tVFWFpmkFxQRBwMTEBGtubkYgEPjh/fv3/zQyMhIHgDJFUX5y6tSpvxERvf766yQIAtXU1BSCxuv1Und3dz5QaGBggMLhMImiSEuWLKFjx44V5trb20mW5aKA83g8dOzYMbIsi4joV5WVlW4OgOx2u6uam5t/MDs7i88++wyhUAi2bRfOi4gK1gNAd3c3enp6oCgK9u/fj46OjsJ/PM8XnTURIRAIYGRkBHNzc7Asq9Xr9fodAEoFQSiNRCKusbExXLt2DS6Xq2hxMBjERx99hJqaGuzevRuMMXR2dqK5uRn19fUAANM00dfXh6GhIfj9/qL1DocDw8PDmJ2dhSzL6zRN8zgACBzHuQF4NU3D5OQkvF5v0UJRFJFOp3H48GHkcjns2LEDpaWlaGpqAgCMj4/j+PHj6O/vBxFBluUij/E8j/v370PXdQCQNE1zcwAEp9MpAIBt24umjm3bCAQCSCQSePPNN3H37t2i+Tt37uDo0aNIp9MoKSkpAs2LpmmwLIsWMoHjAIg8z3vzqbHYrcJxHKamphAMBrFnzx5UV1cXza9cuRK7d++GLMtIpVKL7uFwOMBYoWKSAwBpmjYDICMIgsfr9cKyrKIgyeVy8Pv92Lt3L3bt2lUY+/zzz7F69WqEQiG8/PLLcLlceOutt5DL5SAIQpHHFEWB0+lkAFIALA6Akc1mM6qqpgOBAFavXg3DMIq0TSaT2Lx5cwEUAAYGBvD888/jnXfeKYz19PTg6aefRiqVKlpvWRaqqqrgdrsxMzMzbZqmwQGYm5mZyV66dGkmFArhySefRCqVetgtYIxB07Qi0CNHjmBsbAyvvvoqTpw4UeSdh13NGMPc3BwaGhrg9/uh6/q/LcvK8oyxkK7rwVwuV7J169aVbrcbn3zyCUzTLGwgiiLGx8cRjUYxNDSEd999F3Nzc1i2bBnS6TQ+/fRTxGIxnD17FpcvXy6KFdu2IUkSXnnlFZSXl2Pfvn29w8PD/2QAKgH8SFGUpqtXr25/7LHHlL1792JgYADLly8v1Gpd15FIJArnJYpiYS6bzSKZTILneYRCIQiCACICx3G4d+8edu3ahSNHjoDjuIQkST+en5+/zXs8HrIsS9Y0zZ9MJtlzzz234vHHH8fo6Cii0SgkSSoASJIEn88HnucLaZevaj6fD5IkFSxljCGRSKC1tRW9vb0IBALo6Oj49cjIyN9N09R4APZCDZVHR0edq1atWvrUU08Famtrcf78eaiqCp/P952uRo7joKoqysvL0d/fjxUrVuDmzZt/6erqOmwYxgQAxtu2TR6PxzAMg7dt2zs8PGw0NjaWPvHEE3JDQwO++OIL3LhxA5IkfasOL8IUYZom7t27h9bWVvT19WHdunWIx+NT7e3tB8bHx0eIyCIixjPGGBHZRKQzxrjp6WnH7du3cy0tLUvXrFnjbWhogG3b+OqrrxCPx+FwOMDzfCHq89xb0zQkEgkoioJt27bh4MGDqKurg6qq0+3t7b1DQ0Mf27adyReQh4mek+O4CgAtAF6qq6v7/fvvv3+HiEjXdbp48SIdPHiQNm7cSCUlJSSKIkmSRKIoUiAQoGeeeYYOHTpEV65cIV3XiYhocHBwpKmp6ecAyvJsJ0/42EPsjwA4GWNlRFQLYFUwGKzatGlTzWuvvbY+HA4HACAWi2FychKGYdiWZVkcx/Eul4srKytDJBIBAMzOzqovvvjiB4ODg3+Ox+P/ADAFwPxvFBcAnAsaNgDYBuCXVVVVv3322Wf/ePHixZtElKTFJXH9+vWxzs7OD2pra7sA1AMoWdiPfZPaskXAyeFwOJxOpzuTyZQu5LkCoLSsrEwpLS1dIoqijzFGjDHONE3dMIyUZVnJdDqtxuPxO0QUdblcKcuycqZpGotZyh71WFsgbAIACYAPQACAZ6EvALABcAB0AGkA8wCmF75ZjuN0Iip69D0s/xkAalh5iwp88nkAAAAASUVORK5CYII=';
			}
		}
		else {
			$closeImage = self::getImageDataFromUrl($closeImage);
		}

		return $closeImage;
	}

	public static function getPopupPostAllowedUserRoles()
	{
		$userSavedRoles = get_option('sgpb-user-roles');

		if (empty($userSavedRoles) || !is_array($userSavedRoles)) {
			$userSavedRoles = array('administrator');
		}
		else {
			array_push($userSavedRoles, 'administrator');
		}

		return $userSavedRoles;
	}

	public static function showMenuForCurrentUser()
	{
		if (!is_admin()) {
			return true;
		}

		$savedUserRoles = self::getPopupPostAllowedUserRoles();
		$currentUserRole = self::getCurrentUserRole();
		$userSavedRoles = get_option('sgpb-user-roles');
		if (!is_array($savedUserRoles) || !is_array($currentUserRole) || empty($userSavedRoles)) {
			return true;
		}

		return array_intersect($currentUserRole, $savedUserRoles);
	}

	public static function getPopupsIdAndTitle($excludesPopups = array())
	{
		$allPopups = SGPopup::getAllPopups();
		$popupIdTitles = array();

		if (empty($allPopups)) {
			return $popupIdTitles;
		}

		foreach ($allPopups as $popup) {
			if (empty($popup)) {
				continue;
			}

			$id = $popup->getId();
			$title = $popup->getTitle();
			$type = $popup->getType();

			if (!empty($excludesPopups)) {
				foreach ($excludesPopups as $excludesPopupId) {
					if ($excludesPopupId != $id) {
						$popupIdTitles[$id] = $title.' - '.$type;
					}
				}
			}
			else {
				$popupIdTitles[$id] = $title.' - '.$type;
			}
		}

		return $popupIdTitles;
	}

	/**
	 * Merge two array and merge same key values to same array
	 *
	 * @since 1.0.0
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array|bool
	 *
	 */
	public static function arrayMergeSameKeys($array1, $array2)
	{
		if (empty($array1)) {
			return array();
		}

		$modified = false;
		$array3 = array();
		foreach ($array1 as $key => $value) {
			if (isset($array2[$key]) && is_array($array2[$key])) {
				$arrDifference = array_diff($array2[$key], $array1[$key]);
				if (empty($arrDifference)) {
					continue;
				}

				$modified = true;
				$array3[$key] = array_merge($array2[$key], $array1[$key]);
				unset($array2[$key]);
				continue;
			}

			$modified = true;
			$array3[$key] = $value;
		}

		// when there are no values
		if (!$modified) {
			return $modified;
		}

		return $array2 + $array3;
	}

	public static function getCurrentUserRole()
	{
		$role = array('administrator');

		if (is_multisite()) {

			$getUsersObj = get_users(
				array(
					'blog_id' => get_current_blog_id(),
					'search' => get_current_user_id()
				)
			);

			if (!empty($getUsersObj[0])) {
				$roles = $getUsersObj[0]->roles;

				if (is_array($roles) && !empty($roles)) {
					$role = array_merge($role, $getUsersObj[0]->roles);
				}
			}

			return $role;
		}

		global $current_user;
		$userRoleName = $current_user->roles;

		if (!empty($userRoleName)) {
			$role = $userRoleName;
		}

		return $role;
	}

	public static function hexToRgba($color, $opacity = false)
	{
		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if (empty($color)) {
			return $default;
		}

		//Sanitize $color if "#" is provided
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
			$hex = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
		}
		else if (strlen($color) == 3) {
			$hex = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		}
		else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb = array_map('hexdec', $hex);

		//Check if opacity is set(rgba or rgb)
		if ($opacity !== false) {
			if (abs($opacity) > 1) {
				$opacity = 1.0;
			}
			$output = 'rgba('.implode(',', $rgb).','.$opacity.')';
		}
		else {
			$output = 'rgb('.implode(',', $rgb).')';
		}

		//Return rgb(a) color string
		return $output;
	}

	public static function getAllActiveExtensions()
	{
		$extensions = SgpbDataConfig::getOldExtensionsInfo();
		$labels = array();

		foreach ($extensions as $extension) {
			if (file_exists(WP_PLUGIN_DIR.'/'.$extension['folderName'])) {
				$labels[] = $extension['label'];
			}
		}

		return $labels;
	}

	public static function renderExtensionsContent()
	{
		$extensions = self::getAllActiveExtensions();
		ob_start();
		?>
		<p class="sgpb-extension-notice-close">x</p>
		<div class="sgpb-extensions-list-wrapper">
			<div class="sgpb-notice-header">
				<h3><?php _e('Popup Builder plugin has been successfully updated', SG_POPUP_TEXT_DOMAIN); ?></h3>
				<h4><?php _e('The following extensions need to be updated manually', SG_POPUP_TEXT_DOMAIN); ?></h4>
			</div>
			<ul class="sgpb-extensions-list">
				<?php foreach ($extensions as $extensionName): ?>
					<a target="_blank" href="https://popup-builder.com/forms/control-panel/"><li><?php echo $extensionName; ?></li></a>
				<?php endforeach; ?>
			</ul>
		</div>
		<p class="sgpb-extension-notice-dont-show"><?php _e('Don\'t show again', SG_POPUP_TEXT_DOMAIN)?></p>
		<?php
		$content = ob_get_contents();
		ob_get_clean();

		return $content;
	}

	public static function getReverseConvertIds()
	{
		$idsMappingSaved = get_option('sgpbConvertedIds');
		$ids = array();

		if ($idsMappingSaved) {
			$ids = $idsMappingSaved;
		}

		return array_flip($ids);
	}

	public static function getAllFreeExtensions()
	{
		$allExtensions = SgpbDataConfig::allFreeExtensionsKeys();

		$notActiveExtensions = array();
		$activeExtensions = array();

		foreach ($allExtensions as $extension) {
			if (!is_plugin_active($extension['pluginKey'])) {
				$notActiveExtensions[] = $extension;
			}
			else {
				$activeExtensions[] = $extension;
			}
		}

		$divideExtension = array(
			'noActive' => $notActiveExtensions,
			'active' => $activeExtensions
		);

		return $divideExtension;
	}

	public static function getAllExtensions()
	{
		$allExtensions = SgpbDataConfig::allExtensionsKeys();

		$notActiveExtensions = array();
		$activeExtensions = array();

		foreach ($allExtensions as $extension) {
			if (!is_plugin_active($extension['pluginKey'])) {
				$notActiveExtensions[] = $extension;
			}
			else {
				$activeExtensions[] = $extension;
			}
		}

		$divideExtension = array(
			'noActive' => $notActiveExtensions,
			'active' => $activeExtensions
		);

		return $divideExtension;
	}

	public static function renderAlertProblem()
	{
		ob_start();
		?>
		<div id="welcome-panel" class="update-nag sgpb-alert-problem">
			<div class="welcome-panel-content">
				<p class="sgpb-problem-notice-close">x</p>
				<div class="sgpb-alert-problem-text-wrapper">
					<h3><?php _e('Popup Builder plugin has been updated to the new version 3.', SG_POPUP_TEXT_DOMAIN); ?></h3>
					<h5><?php _e('A lot of changes and improvements have been made.', SG_POPUP_TEXT_DOMAIN); ?></h5>
					<h5><?php _e('In case of any issues, please contact us <a href="<?php echo SG_POPUP_TICKET_URL; ?>" target="_blank">here</a>.', SG_POPUP_TEXT_DOMAIN); ?></h5>
				</div>
				<p class="sgpb-problem-notice-dont-show"><?php _e('Don\'t show again', SG_POPUP_TEXT_DOMAIN); ?></p>
			</div>
		</div>
		<?php
		$content = ob_get_clean();

		return $content;
	}

	public static function getTaxonomyBySlug($slug = '')
	{
		$allTerms = get_terms(array('hide_empty' => false));

		$result = array();
		if (empty($allTerms)) {
			return $result;
		}
		if ($slug == '') {
			return $allTerms;
		}
		foreach ($allTerms as $term) {
			if ($term->slug == $slug) {
				return $term;
			}
		}
	}

	public static function getCurrentPopupType()
	{
		$type = '';
		if (!empty($_GET['sgpb_type'])) {
			$type  = $_GET['sgpb_type'];
		}

		$currentPostType = self::getCurrentPostType();

		if ($currentPostType == SG_POPUP_POST_TYPE && !empty($_GET['post'])) {
			$popupObj = SGPopup::find($_GET['post']);
			if (is_object($popupObj)) {
				$type = $popupObj->getType();
			}
		}

		return $type;
	}

	public static function getCurrentPostType()
	{
		global $post_type;
		global $post;
		$currentPostType = '';

		if (is_object($post)) {
			$currentPostType = @$post->post_type;
		}

		// in some themes global $post returns null
		if (empty($currentPostType)) {
			$currentPostType = $post_type;
		}

		if (empty($currentPostType) && !empty($_GET['post'])) {
			$currentPostType = get_post_type($_GET['post']);
		}

		return $currentPostType;
	}

	/**
	 * Get image encoded data from URL
	 *
	 * @param $imageUrl
	 * @param $shouldNotConvertBase64
	 *
	 * @return string
	 */

	public static function getImageDataFromUrl($imageUrl, $shouldNotConvertBase64 = false)
	{
		$remoteData = wp_remote_get($imageUrl);
		$headers = wp_remote_retrieve_headers($remoteData);
		if ((empty($headers) || (isset($headers['content-type']) && strpos($headers['content-type'], 'image/') === false))
			|| (is_wp_error($remoteData) && $shouldNotConvertBase64)) {
			return SG_POPUP_IMG_URL.'NoImage.png';
		}

		if (!$shouldNotConvertBase64) {
			$imageData = wp_remote_retrieve_body($remoteData);
			$imageUrl = base64_encode($imageData);
		}

		return $imageUrl;
	}

	public static function deleteUserFromSubscribers($params = array())
	{
		global $wpdb;

		$token = '';
		$email = '';
		$popup = '';
		$noSubscriber = true;

		if (isset($params['token'])) {
			$token = $params['token'];
		}
		if (isset($params['email'])) {
			$email = $params['email'];
		}
		if (isset($params['popup'])) {
			$popup = $params['popup'];
		}

		$prepareSql = $wpdb->prepare('SELECT id FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE email = %s && subscriptionType = %s', $email, $popup);
		$res = $wpdb->get_row($prepareSql, ARRAY_A);
		if (!isset($res['id'])) {
			$noSubscriber = false;
		}
		$params['subscriberId'] = $res['id'];

		$subscriber = self::subscriberExists($params);
		if ($subscriber && $noSubscriber) {
			self::deleteSubscriber($params);
		}
		else if (!$noSubscriber) {
			_e('<span>Oops, something went wrong, please try again or contact the administrator to check more info.</span>', SG_POPUP_TEXT_DOMAIN);
			wp_die();
		}
	}

	public static function subscriberExists($params = array())
	{
		if (empty($params)) {
			return false;
		}

		$receivedToken = $params['token'];
		$realToken = md5($params['subscriberId'].$params['email']);
		if ($receivedToken == $realToken) {
			return true;
		}

	}

	public static function deleteSubscriber($params = array())
	{
		global $wpdb;
		$homeUrl = get_home_url();

		if (empty($params)) {
			return false;
		}
		// send email to admin about user unsubscription
		self::sendEmailAboutUnsubscribe($params);

		$prepareSql = $wpdb->prepare('UPDATE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' SET unsubscribed = 1 WHERE id = %s ', $params['subscriberId']);
		$wpdb->query($prepareSql);

		_e('<span>You have successfully unsubscribed. <a href="'.esc_attr($homeUrl).'">click here</a> to go to the home page.</span>', SG_POPUP_TEXT_DOMAIN);
		wp_die();
	}

	public static function sendEmailAboutUnsubscribe($params = array())
	{
		if (empty($params)) {
			return false;
		}

		$newsletterOptions = get_option('SGPB_NEWSLETTER_DATA');
		$receiverEmail = get_bloginfo('admin_email');
		$userEmail = $params['email'];
		$emailTitle = __('Unsubscription', SG_POPUP_TEXT_DOMAIN);
		$subscriptionFormId = (int)$newsletterOptions['subscriptionFormId'];
		$subscriptionFormTitle = get_the_title($subscriptionFormId);

		$message = __('User with '.$userEmail.' email has unsubscribed from '.$subscriptionFormTitle.' mail list', SG_POPUP_TEXT_DOMAIN);

		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'From: WordPress Popup Builder'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n"; //set UTF-8

		$sendStatus = wp_mail($receiverEmail, $emailTitle, $message, $headers);
	}

	public static function addUnsubscribeColumn()
	{
		global $wpdb;

		$sql = 'ALTER TABLE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' ADD COLUMN unsubscribed INT NOT NULL DEFAULT 0 ';
		$wpdb->query($sql);
	}

	public static function isPluginActive($key)
	{
		$allExtensions = SgpbDataConfig::allExtensionsKeys();
		$isActive = false;
		foreach ($allExtensions as $extension) {
			if (isset($extension['key']) && $extension['key'] == $key) {
				if (is_plugin_active($extension['pluginKey'])) {
					$isActive = true;
					return $isActive;
				}
			}
		}

		return $isActive;
	}

	public static function getMaxCountPopup()
	{
		$allPopups = SGPopup::getAllPopups();
		$dontShowPopup = get_option('sgpbDontShowAskReviewBanner');
		if ($dontShowPopup) {
			return false;
		}
		$result = array();

		if (empty($allPopups)) {
			return false;
		}
		foreach ($allPopups as $popup) {
			if (empty($popup)) {
				continue;
			}
			$popupId = $popup->getId();
			$count = SGPopup::getPopupOpeningCountById($popupId);

			$title = $popup->getTitle();
			$result['title'] = $title;
			$result['count'] = $count;
		}

		return $result;
	}

	public static function supportBannerNotification()
	{
		$content = '<div class="sgpb-support-notification-wrapper sgpb-wrapper"><h4 class="sgpb-support-notification-title">'.__('Need some help?', SG_POPUP_TEXT_DOMAIN).'</h4>';
		$content .= '<h4 class="sgpb-support-notification-title">'.__('Let us know what you think.', SG_POPUP_TEXT_DOMAIN).'</h4>';
		$content .= '<a class="btn btn-info" target="_blank" href="'.SG_POPUP_RATE_US_URL.'"><span class="dashicons sgpb-dashicons-heart sgpb-info-text-white"></span><span class="sg-info-text">'.__('Rate Us', SG_POPUP_TEXT_DOMAIN).'</span></a>';
		$content .= '<a class="btn btn-info" target="_blank" href="'.SG_POPUP_TICKET_URL.'"><span class="dashicons sgpb-dashicons-megaphone sgpb-info-text-white"></span>'.__('Support Potal', SG_POPUP_TEXT_DOMAIN).'</a>';
		$content .= '<a class="btn btn-info" target="_blank" href="https://wordpress.org/support/plugin/popup-builder"><span class="dashicons sgpb-dashicons-admin-plugins sgpb-info-text-white"></span>'.__('Support Forum', SG_POPUP_TEXT_DOMAIN).'</a>';
		$content .= '<a class="btn btn-info" target="_blank" href="'.SG_POPUP_STORE_URL.'"><span class="dashicons sgpb-dashicons-editor-help sgpb-info-text-white"></span>'.__('LIVE chat', SG_POPUP_TEXT_DOMAIN).'</a>';
		$content .= '<a class="btn btn-info" target="_blank" href="mailto:support@popup-builder.com?subject=Hello"><span class="dashicons sgpb-dashicons-email-alt sgpb-info-text-white"></span>'.__('Email', SG_POPUP_TEXT_DOMAIN).'</a></div>';
		$content .= '<div class="sgpb-support-notification-dont-show">'.__('Bored of this?').'<a class="sgpb-dont-show-again-support-notification" href="javascript:void(0)">'.__(' Press here ').'</a>'.__('and we will not show it again!').'</div>';

		return $content;
	}

	public static function getMaxOpenDaysMessage()
	{
		$getUsageDays = self::getPopupUsageDays();
		$firstHeader = __('<h1 class="sgpb-review-h1"><strong class="sgrb-review-strong">This is great!</strong> We have noticed that you are using Popup Builder plugin on your site for '.$getUsageDays.' days, we are thankful for that.</h1>', SG_POPUP_TEXT_DOMAIN);
		$popupContent = self::getMaxOpenPopupContent($firstHeader, 'days');

		return $popupContent;
	}

	public static function getPopupUsageDays()
	{
		$installDate = get_option('SGPBInstallDate');

		$timeDate = new \DateTime('now');
		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
		$diff = $timeNow-$installDate;
		$days  = floor($diff/(60*60*24));

		return $days;
	}

	public static function getMaxOpenPopupContent($firstHeader, $type)
	{
		ob_start();
		?>
		<style>
			.sgpb-buttons-wrapper .press{
				box-sizing:border-box;
				cursor:pointer;
				display:inline-block;
				font-size:1em;
				margin:0;
				padding:0.5em 0.75em;
				text-decoration:none;
				transition:background 0.15s linear
			}
			.sgpb-buttons-wrapper .press-grey {
				background-color:#9E9E9E;
				border:2px solid #9E9E9E;
				color: #FFF;
			}
			.sgpb-buttons-wrapper .press-lightblue {
				background-color:#03A9F4;
				border:2px solid #03A9F4;
				color: #FFF;
			}
			.sgpb-buttons-wrapper {
				text-align: center;
			}
			.sgpb-review-wrapper{
				text-align: center;
				padding: 20px;
			}
			.sgpb-review-wrapper p {
				color: black;
			}
			.sgpb-review-h1 {
				font-size: 22px;
				font-weight: normal;
				line-height: 1.384;
			}
			.sgrb-review-h2{
				font-size: 20px;
				font-weight: normal;
			}
			:root {
				--main-bg-color: #1ac6ff;
			}
			.sgrb-review-strong{
				color: var(--main-bg-color);
			}
			.sgrb-review-mt20{
				margin-top: 20px
			}
		</style>
		<div class="sgpb-review-wrapper">
			<div class="sgpb-review-description">
				<?php echo $firstHeader; ?>
				<h2 class="sgrb-review-h2"><?php _e('This is really great for your website score.', SG_POPUP_TEXT_DOMAIN); ?></h2>
				<p class="sgrb-review-mt20"><?php _e('Have your input in the development of our plugin, and we’ll provide better conversions for your site!<br /> Leave your 5-star positive review and help us go further to the perfection!', SG_POPUP_TEXT_DOMAIN); ?></p>
			</div>
			<div class="sgpb-buttons-wrapper">
				<button class="press press-grey sgpb-button-1 sgpb-close-promo-notification" data-action="sg-already-did-review"><?php _e('I already did', SG_POPUP_TEXT_DOMAIN); ?></button>
				<button class="press press-lightblue sgpb-button-3 sgpb-close-promo-notification" data-action="sg-you-worth-it"><?php _e('You worth it!', SG_POPUP_TEXT_DOMAIN); ?></button>
				<button class="press press-grey sgpb-button-2 sgpb-close-promo-notification" data-action="sg-show-popup-period" data-message-type="<?php echo $type; ?>"><?php _e('Maybe later', SG_POPUP_TEXT_DOMAIN); ?></button></div>
			<div> </div>
		</div>
		<?php
		$popupContent = ob_get_clean();

		return $popupContent;
	}

	public static function shouldOpenReviewPopupForDays()
	{
		$shouldOpen = true;
		$dontShowAgain = get_option('SGPBCloseReviewPopup-notification');
		$periodNextTime = get_option('SGPBOpenNextTime');
		/*if (!$dontShowAgain) {
			return true;
		}
		else {
			return false;
		}*/
		// When period next time does not exits it means the user is old
		if (!$periodNextTime) {
			$usageDays = self::getPopupMainTableCreationDate();
			update_option('SGPBUsageDays', $usageDays);
			if (!defined('SGPB_REVIEW_POPUP_PERIOD')) {
				define('SGPB_REVIEW_POPUP_PERIOD', '500');
			}
			// For old users
			if (defined('SGPB_REVIEW_POPUP_PERIOD') && $usageDays > SGPB_REVIEW_POPUP_PERIOD && !$dontShowAgain) {
				return $shouldOpen;
			}
			$remainingDays = SGPB_REVIEW_POPUP_PERIOD - $usageDays;

			$popupTimeZone = \ConfigDataHelper::getDefaultTimezone();
			$timeDate = new DateTime('now', new DateTimeZone($popupTimeZone));
			$timeDate->modify('+'.$remainingDays.' day');

			$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
			update_option('SGPBOpenNextTime', $timeNow);

			return false;
		}

		$currentData = new \DateTime('now');
		$timeNow = $currentData->format('Y-m-d H:i:s');
		$timeNow = strtotime($timeNow);

		if ($periodNextTime > $timeNow) {
			$shouldOpen = false;
		}

		return $shouldOpen;
	}

	public static function getPopupMainTableCreationDate()
	{
		global $wpdb;

		$query = $wpdb->prepare('SELECT table_name, create_time FROM information_schema.tables WHERE table_schema="%s" AND table_name="%s"', DB_NAME, $wpdb->prefix.'sgpb_subscribers');
		$results = $wpdb->get_results($query, ARRAY_A);
		if (empty($results)) {
			return 0;
		}

		$createTime = $results[0]['create_time'];
		$createTime = strtotime($createTime);
		update_option('SGPBInstallDate', $createTime);
		$diff = time() - $createTime;
		$days = floor($diff/(60*60*24));

		return $days;
	}

	public static function shouldOpenForMaxOpenPopupMessage()
	{
		$counterMaxPopup = self::getMaxOpenPopupId();

		if (empty($counterMaxPopup)) {
			return false;
		}
		$dontShowAgain = get_option('SGPBCloseReviewPopup-notification');
		$maxCountDefine = get_option('SGPBMaxOpenCount');

		if (!$maxCountDefine) {
			$maxCountDefine = SGPB_ASK_REVIEW_POPUP_COUNT;
		}

		return $counterMaxPopup['maxCount'] >= $maxCountDefine && !$dontShowAgain;
	}

	public static function getMaxOpenPopupId()
	{
		$popupsCounterData = get_option('SgpbCounter');
		if (!$popupsCounterData) {
			return 0;
		}

		$counters = array_values($popupsCounterData);
		$maxCount = max($counters);
		$popupId  = array_search($maxCount, $popupsCounterData);

		$maxPopupData = array(
			'popupId' => $popupId,
			'maxCount' => $maxCount
		);

		return $maxPopupData;
	}

	public static function getMaxOpenPopupsMessage()
	{
		$counterMaxPopup = self::getMaxOpenPopupId();
		$popupTitle = '';
		$maxCountDefine = get_option('SGPBMaxOpenCount');
		$popupTitle = get_the_title($counterMaxPopup['popupId']);

		if (!empty($counterMaxPopup['maxCount'])) {
			$maxCountDefine = $counterMaxPopup['maxCount'];
		}

		$firstHeader = __('<h1 class="sgpb-review-h1"><strong class="sgrb-review-strong">Awesome news!</strong> <b>Popup Builder</b> plugin helped you to share your message via <strong class="sgrb-review-strong">'.$popupTitle.'</strong> popup with your visitors for <strong class="sgrb-review-strong">'.$maxCountDefine.' times!</strong></h1>', SG_POPUP_TEXT_DOMAIN);
		$popupContent = self::getMaxOpenPopupContent($firstHeader, 'count');

		return $popupContent;
	}

	/**
	 * Get email headers
	 *
	 * @since 3.1.0
	 *
	 * @param email $fromEmail
	 * @param array $args
	 *
	 * @return string $headers
	 */
	public static function getEmailHeader($fromEmail, $args = array())
	{
		$contentType = 'text/html';
		$charset = 'UTF-8';

		if (!empty($args['contentType'])) {
			$contentType = $args['contentType'];
		}
		if (!empty($args['charset'])) {
			$charset = $args['charset'];
		}
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'From: '.$fromEmail."\r\n";
		$headers .= 'Content-type: '.$contentType.'; charset='.$charset.''."\r\n"; //set UTF-8

		return $headers;
	}

	/**
	 * Get file content from URL
	 *
	 * @since 3.1.0
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public static function getFileFromURL($url)
	{
		$data = '';
		$remoteData = wp_remote_get($url);

		if (is_wp_error($remoteData)) {
			return $data;
		}

		$data = wp_remote_retrieve_body($remoteData);

		return $data;
	}

	public static function getBannerText()
	{
		$bannerText = get_option('sgpb-banner-remote-get');

		return $bannerText;
	}

	public static function getRightMetaboxBannerText()
	{
		$bannerText = get_option('sgpb-metabox-banner-remote-get');

		return $bannerText;
	}

	public static function getGutenbergPopupsIdAndTitle($excludesPopups = array())
	{
		$allPopups = SGPopup::getAllPopups();
		$popupIdTitles = array();

		if (empty($allPopups)) {
			return $popupIdTitles;
		}

		foreach ($allPopups as $popup) {
			if (empty($popup)) {
				continue;
			}

			$id = $popup->getId();
			$title = $popup->getTitle();
			$type = $popup->getType();

			if (!empty($excludesPopups)) {
				foreach ($excludesPopups as $excludesPopupId) {
					if ($excludesPopupId != $id) {
						$array = array();
						$array['id'] = $id;
						$array['title'] = $title.' - '.$type;
						$popupIdTitles[] = $array;
					}
				}
			}
			else {
				$array = array();
				$array['id'] = $id;
				$array['title'] = $title.' - '.$type;
				$popupIdTitles[] = $array;
			}
		}

		return $popupIdTitles;
	}

	public static function getGutenbergPopupsEvents()
	{
		$data =  array(
			array('value' => '', 'title' => __('Select Event', SG_POPUP_TEXT_DOMAIN)),
			array('value' => 'inherit', 'title' => __('Inherit', SG_POPUP_TEXT_DOMAIN)),
			array('value' => 'onLoad', 'title' => __('On load', SG_POPUP_TEXT_DOMAIN)),
			array('value' => 'click', 'title' => __('On click', SG_POPUP_TEXT_DOMAIN)),
			array('value' => 'hover', 'title' => __('On hover', SG_POPUP_TEXT_DOMAIN))
		);

		return $data;
	}

	public static function checkEditorByPopupId($popupId)
	{
		$popupContent = '';
		if (class_exists('\Elementor\Plugin')) {
			$elementorContent = get_post_meta($popupId, '_elementor_edit_mode', true);
			if (!empty($elementorContent) && $elementorContent == 'builder') {
				$popupContent = Elementor\Plugin::instance()->frontend->get_builder_content_for_display($popupId);
			}
		}
		else if (class_exists('Vc_Manager')) {
			$stylesAndScripts = self::renderWPBakeryScriptsAndStyles($popupId);
			$popupContent .= '<style>'.$stylesAndScripts.'</style>';
		}

		return $popupContent;
	}

	public static function renderWPBakeryScriptsAndStyles($popupId = 0)
	{
		return get_post_meta($popupId, '_wpb_shortcodes_custom_css', true);
	}

	// countdown popup
	public static function renderCountdownStyles($popupId = 0, $countdownBgColor, $countdownTextColor)
	{
		return  "<style type='text/css'>
			.sgpb-counts-content.sgpb-flipclock-js-$popupId.flip-clock-wrapper ul li a div div.inn {
				background-color: $countdownBgColor;
				color: $countdownTextColor;
			}
			.sgpb-countdown-wrapper {
				width: 446px;
				height: 130px;
				padding-top: 22px;
				box-sizing: border-box;
				margin: 0 auto;
			}
			.sgpb-counts-content {
				display: inline-block;
			}
			.sgpb-counts-content > ul.flip {
				width: 40px;
				margin: 4px;
			}
		</style>";
	}

	// countdown popup scripts and params
	public static function renderCountdownScript($id, $seconds, $type, $language, $timezone, $autoclose)
	{
		$params = array(
			'id'        => $id,
			'seconds'   => $seconds,
			'type'      => $type,
			'language'  => $language,
			'timezone'  => $timezone,
			'autoclose' => $autoclose
		);

		return $params;
	}

	// countdown popup, convert date to seconds
	public static function dateToSeconds($dueDate, $timezone)
	{
		if (empty($timezone)) {
			return '';
		}

		$dateObj = self::getDateObjFromDate('now', $timezone);
		$timeNow = @strtotime($dateObj);
		$seconds = @strtotime($dueDate)-$timeNow;
		if ($seconds < 0) {
			$seconds = 0;
		}

		return $seconds;
	}

	/**
	 * Get site protocol
	 *
	 * @since 1.0.0
	 *
	 * @return string $protocol
	 *
	 */
	public static function getSiteProtocol()
	{
		$protocol = 'http';

		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
			$protocol = 'https';
		}

		return $protocol;
	}

	public static function getCurrentUrl()
	{
		$protocol = self::getSiteProtocol();
		$currentUrl = $protocol."://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

		return $currentUrl;
	}

	public static function isAppleMobileDevice()
	{
		$isIOS = false;

		$useragent = @$_SERVER['HTTP_USER_AGENT'];
		preg_match('/iPhone|Android|iPad|iPod|webOS/', $useragent, $matches);

		$os = current($matches);
		if ($os == 'iPad' || $os == 'iPhone' || $os == 'iPod') {
			$isIOS = true;
		}

		return $isIOS;
	}

	public static function setPushToBottom($element = '')
	{
		$style = '<style type="text/css">';
		$style .= "$element";
		$style .= '{position: absolute !important;';
		$style .= 'left: 0 !important;';
		$style .= 'right: 0 !important;';
		$style .= 'bottom: 2px !important;}';
		$style .= '</style>';

		return $style;
	}

	public static function findSubscribersByEmail($subscriberEmail = '', $list = 0)
	{
		global $wpdb;
		$subscriber = array();

		$prepareSql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE email = %s AND subscriptionType = %d ', $subscriberEmail, $list);
		$subscriber = $wpdb->get_row($prepareSql, ARRAY_A);
		if (!$list) {
			$prepareSql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE email = %s ', $subscriberEmail);
			$subscriber = $wpdb->get_results($prepareSql, ARRAY_A);
		}

		return $subscriber;
	}

	/**
	 * Update options
	 *
	 * @since 3.1.9
	 *
	 * @return void
	 */
	public static function updateOption($optionKey, $optionValue)
	{
		if (is_multisite()) {
			update_site_option($optionKey, $optionValue);
		}
		else {
			update_option($optionKey, $optionValue);
		}
	}

	public static function getOption($optionKey)
	{
		if (is_multisite()) {
			return get_site_option($optionKey);
		}
		return get_option($optionKey);
	}

	public static function deleteOption($optionKey)
	{
		if (is_multisite()) {
			delete_site_option($optionKey);
		}
		else {
			delete_option($optionKey);
		}
	}

	/**
	 * It's change popup registered plugins static paths to dynamic
	 *
	 * @since 3.1.9
	 *
	 * @return bool where true mean modified false mean there is not need modification
	 */
	public static function makeRegisteredPluginsStaticPathsToDynamic()
	{
		$hasModifiedPaths = AdminHelper::getOption('SGPB_REGISTERED_PLUGINS_PATHS_MODIFIED');

		if ($hasModifiedPaths) {
			return false;
		}
		// remove old outdated option sgpbModifiedRegisteredPluginsPaths
		delete_option('sgpbModifiedRegisteredPluginsPaths');
		update_option(SGPB_REGISTERED_PLUGINS_PATHS_MODIFIED, 1);

		$registeredPlugins = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');

		if (empty($registeredPlugins)) {
			return false;
		}

		$registeredPlugins = json_decode($registeredPlugins, true);

		if (empty($registeredPlugins)) {
			return false;
		}

		foreach ($registeredPlugins as $key => $registeredPlugin) {
			if (empty($registeredPlugin['classPath'])) {
				continue;
			}
			$registeredPlugins[$key]['classPath'] = str_replace(WP_PLUGIN_DIR, '', $registeredPlugin['classPath']);
			if (!empty($registeredPlugin['options']['licence']['file'])) {
				$registeredPlugins[$key]['options']['licence']['file'] = $registeredPlugin['options']['licence']['file'];
			}
		}
		$registeredPlugins = json_encode($registeredPlugins);

		update_option('SG_POPUP_BUILDER_REGISTERED_PLUGINS', $registeredPlugins);
		return true;
	}

	public static function hasInactiveExtensions()
	{
		$hasInactiveExtensions = false;
		$allRegiseredPBPlugins = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');
		$allRegiseredPBPlugins = @json_decode($allRegiseredPBPlugins, true);
		if (empty($allRegiseredPBPlugins)) {
			return $hasInactiveExtensions;
		}

		foreach ($allRegiseredPBPlugins as $pluginPath => $registeredPlugin) {
			if (!isset($registeredPlugin['options']['licence']['key'])) {
				continue;
			}
			if (!isset($registeredPlugin['options']['licence']['file'])) {
				continue;
			}
			$extensionKey = $registeredPlugin['options']['licence']['file'];
			$isPluginActive = is_plugin_active($extensionKey);
			$pluginKey = $registeredPlugin['options']['licence']['key'];
			$isValidLicense = get_option('sgpb-license-status-'.$pluginKey);

			// if we even have at least one inactive extension, we don't need to check remaining extensions
			if ($isValidLicense != 'valid' && $isPluginActive) {
				$hasInactiveExtensions = true;
				break;
			}
		}

		return $hasInactiveExtensions;
	}

	public static function getSubscriberDataById($id)
	{
		global $wpdb;
		$result = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE id='.$id, ARRAY_A);

		return $result;
	}

	public static function getSubscriptionColumnsById($id)
	{
		$popup = SGPopup::find($id);
		if (empty($popup) || !is_object($popup)) {
			return array();
		}
		$freeSavedOptions = $popup->getOptionValue('sgpb-subs-fields');

		if (!empty($freeSavedOptions)) {
			return array('firstName' => 'First name','lastName' => 'Last name', 'email' => 'Email', 'date' => 'Date');
		}
		$formFieldsJson = $popup->getOptionValue('sgpb-subscription-fields-json');
		if (!empty($formFieldsJson)) {
			$data = apply_filters('sgpbGetSubscriptionLabels', array(), $popup);
			$data['date'] = 'Date';
			return $data;
		}

		return array();
	}

	public static function getCustomFormFieldsByPopupId($popupId)
	{
		if (!class_exists('sgpbsubscriptionplus\SubscriptionPlusAdminHelper')) {
			return array();
		}

		if (method_exists('sgpbsubscriptionplus\SubscriptionPlusAdminHelper', 'getCustomFormFieldsByPopupId')) {
			return SubscriptionPlusAdminHelper::getCustomFormFieldsByPopupId($popupId);
		}

		return array();
	}

	public static function removeAllNonPrintableCharacters($title, $defaultValue)
	{
		$titleRes = $title;
		$pattern  = '/[\\\^£$%&*()}{@#~?><>,|=_+¬-]/';
		$title = preg_replace($pattern, '', $title);
		$title = mb_ereg_replace($pattern, '', $title);
		$title = htmlspecialchars($title, ENT_IGNORE, 'UTF-8');
		$result = str_replace(' ', '', $title);
		if (empty($result)) {
			$titleRes = $defaultValue;
		}

		return $titleRes;
	}

	public static function renderCustomScripts($popupId)
	{
		$finalResult = '';
		$postMeta = get_post_meta($popupId, 'sg_popup_scripts', true);
		if (empty($postMeta)) {
			return '';
		}
		$defaultData = \ConfigDataHelper::defaultData();

		// get scripts
		$jsPostMeta = @$postMeta['js'];
		$jsDefaultData = $defaultData['customEditorContent']['js']['helperText'];
		$finalContent = '';
		if (!empty($jsPostMeta)) {
			$customScripts = '<script id="sgpb-custom-script-'.$popupId.'">';
			foreach ($jsDefaultData as $key => $value) {
				$eventName = 'sgpb'.$key;
				if ((!isset($jsPostMeta['sgpb-'.$key]) || empty($jsPostMeta['sgpb-'.$key])) || $key == 'ShouldOpen' || $key == 'ShouldClose') {
					continue;
				}
				$content = @$jsPostMeta['sgpb-'.$key];
				$content = str_replace('popupId', $popupId, $content);
				$content = html_entity_decode($content);

				$finalContent .= 'sgAddEvent(window, "'.$eventName.'", function(e) {';
				$finalContent .= 'if (e.detail.popupId == "'.$popupId.'") {';
				$finalContent .= $content;
				$finalContent .= '};';
				$finalContent .= '});';
			}
			$customScripts .= $finalContent;
			$customScripts .= '</script>';
			$finalResult .= $customScripts;
		}

		// get styles
		$cssPostMeta = @$postMeta['css'];

		$finalContent = '';
		if (!empty($cssPostMeta)) {
			$customStyles = '<style id="sgpb-custom-style-'.$popupId.'">';
			$finalContent = str_replace('popupId', $popupId, $cssPostMeta);
			$finalContent = html_entity_decode($finalContent);

			$customStyles .= $finalContent;
			$customStyles .= '</style>';
			$finalResult .= $customStyles;
		}


		return $finalResult;
	}

	public static function removeSelectedTypeOptions($type)
	{
		switch ($type) {
			case 'cron':
				$crons = _get_cron_array();
				foreach ($crons as $key => $value) {
					foreach ($value as $key => $body) {
						if (strstr($key, 'sgpb')) {
							wp_clear_scheduled_hook($key);
						}
					}
				}
				break;
		}
	}

	public static function getSystemInfoText() {
		global $wpdb;

		$browser = self::getBrowser();

		// Get theme info
		if (get_bloginfo('version') < '3.4') {
			$themeData = get_theme_data(get_stylesheet_directory().'/style.css');
			$theme = $themeData['Name'].' '.$themeData['Version'];
		}
		else {
			$themeData = wp_get_theme();
			$theme = $themeData->Name.' '.$themeData->Version;
		}

		// Try to identify the hosting provider
		$host = self::getHost();

		$systemInfoContent = '### Start System Info ###'."\n\n";

		// Start with the basics...
		$systemInfoContent .= '-- Site Info'."\n\n";
		$systemInfoContent .= 'Site URL:                 '.site_url()."\n";
		$systemInfoContent .= 'Home URL:                 '.home_url()."\n";
		$systemInfoContent .= 'Multisite:                '.(is_multisite() ? 'Yes' : 'No')."\n";

		// Can we determine the site's host?
		if ($host) {
			$systemInfoContent .= "\n".'-- Hosting Provider'."\n\n";
			$systemInfoContent .= 'Host:                     '.$host."\n";
		}

		// The local users' browser information, handled by the Browser class
		$systemInfoContent .= "\n".'-- User Browser'."\n\n";
		$systemInfoContent .= $browser;

		// WordPress configuration
		$systemInfoContent .= "\n".'-- WordPress Configuration'."\n\n";
		$systemInfoContent .= 'Version:                  '.get_bloginfo('version')."\n";
		$systemInfoContent .= 'Language:                 '.(defined('WPLANG') && WPLANG ? WPLANG : 'en_US')."\n";
		$systemInfoContent .= 'Permalink Structure:      '.(get_option('permalink_structure') ? get_option('permalink_structure') : 'Default')."\n";
		$systemInfoContent .= 'Active Theme:             '.$theme."\n";
		$systemInfoContent .= 'Show On Front:            '.get_option('show_on_front')."\n";

		// Only show page specs if frontpage is set to 'page'
		if (get_option('show_on_front') == 'page') {
			$frontPageId = get_option('page_on_front');
			$blogPageId  = get_option('page_for_posts');

			$systemInfoContent .= 'Page On Front:            '.($frontPageId != 0 ? get_the_title($frontPageId).' (#'.$frontPageId.')' : 'Unset')."\n";
			$systemInfoContent .= 'Page For Posts:           '.($blogPageId != 0 ? get_the_title($blogPageId).' (#'.$blogPageId.')' : 'Unset')."\n";
		}

		$systemInfoContent .= 'Table Prefix:             '.'Prefix: '.$wpdb->prefix.'  Length: '.strlen($wpdb->prefix ).'   Status: '.( strlen($wpdb->prefix) > 16 ? 'ERROR: Too long' : 'Acceptable')."\n";
		$systemInfoContent .= 'WP_DEBUG:                 '.(defined('WP_DEBUG') ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set')."\n";
		$systemInfoContent .= 'Memory Limit:             '.WP_MEMORY_LIMIT."\n";
		$systemInfoContent .= 'Registered Post Stati:    '.implode(', ', get_post_stati())."\n";

		// Must-use plugins
		$muplugins = get_mu_plugins();
		if ($muplugins && count($muplugins)) {
			$systemInfoContent .= "\n".'-- Must-Use Plugins'."\n\n";

			foreach ($muplugins as $plugin => $plugin_data) {
				$systemInfoContent .= $plugin_data['Name'].': '.$plugin_data['Version']."\n";
			}
		}

		$registered = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');
		$registered = json_decode($registered, true);

		if (empty($registered)) {
			return false;
		}
		// remove free package data, we don't need it
		array_shift($registered);

		$systemInfoContent .= "\n".'-- Popup Builder License Data'."\n\n";
		if (!empty($registered)) {
			foreach ($registered as $singleExntensionData) {
				$key = $singleExntensionData['options']['licence']['key'];
				$name = $singleExntensionData['options']['licence']['itemName'];
				$licenseKey = __('No license');
				if (!empty(self::getOption('sgpb-license-key-'.$key))) {
					$licenseKey = self::getOption('sgpb-license-key-'.$key);
				}
				$licenseStatus = 'Inactive';
				if (self::getOption('sgpb-license-status-'.$key) == 'valid') {
					$licenseStatus = 'Active';
				}

				$systemInfoContent .= 'Name:             '.$name."\n";
				$systemInfoContent .= 'License key:      '.$licenseKey."\n";
				$systemInfoContent .= 'License status:  '.$licenseStatus."\n";
				$systemInfoContent .= "\n";
			}
		}

		$systemInfoContent .= "\n".'-- All created Popups'."\n\n";
		$allPopups = self::getPopupsIdAndTitle();
		$args['status'] = array('publish', 'draft', 'pending', 'private', 'trash');
		foreach ($allPopups as $id => $popupTitleType) {
			$popup = SGPopup::find($id, $args);
			$popupStatus = ($popup->getOptionValue('sgpb-is-active')) ? 'Enabled' : 'Disabled';
			$systemInfoContent .= 'Id:     '.$id."\n";
			$systemInfoContent .= 'Title:  '.get_the_title($id)."\n";
			$systemInfoContent .= 'Type:   '.$popup->getOptionValue('sgpb-type')."\n";
			$systemInfoContent .= 'Status: '.$popupStatus."\n";
			$systemInfoContent .= "\n";
		}

		// WordPress active plugins
		$systemInfoContent .= "\n".'-- WordPress Active Plugins'."\n\n";

		$plugins        = get_plugins();
		$activePlugins = get_option('active_plugins', array());
		foreach ($plugins as $pluginPath => $plugin) {

			if (! in_array($pluginPath, $activePlugins)) {
				continue;
			}

			$systemInfoContent .= $plugin['Name'].': '.$plugin['Version']."\n";
		}
		// WordPress inactive plugins
		$systemInfoContent .= "\n".'-- WordPress Inactive Plugins'."\n\n";

		foreach ($plugins as $pluginPath => $plugin) {
			if (in_array($pluginPath, $activePlugins)) {
				continue;
			}
			$systemInfoContent .= $plugin['Name'].': '.$plugin['Version']."\n";
		}

		if (is_multisite()) {
			// WordPress Multisite active plugins
			$systemInfoContent .= "\n".'-- Network Active Plugins'."\n\n";

			$plugins        = wp_get_active_network_plugins();
			$activePlugins = get_site_option('active_sitewide_plugins', array());

			foreach ($plugins as $pluginPath) {
				$plugin_base = plugin_basename($pluginPath);

				if (! array_key_exists($plugin_base, $activePlugins)) {
					continue;
				}

				$plugin = get_plugin_data($pluginPath);
				$systemInfoContent .= $plugin['Name'].': '.$plugin['Version']."\n";
			}
		}

		// Server configuration (really just versioning)
		$systemInfoContent .= "\n".'-- Webserver Configuration'."\n\n";
		$systemInfoContent .= 'PHP Version:              '.PHP_VERSION."\n";
		$systemInfoContent .= 'MySQL Version:            '.$wpdb->db_version()."\n";
		$systemInfoContent .= 'Webserver Info:           '.$_SERVER['SERVER_SOFTWARE']."\n";

		// PHP configs... now we're getting to the important stuff
		$systemInfoContent .= "\n".'-- PHP Configuration'."\n\n";
		$systemInfoContent .= 'Memory Limit:             '.ini_get('memory_limit')."\n";
		$systemInfoContent .= 'Upload Max Size:          '.ini_get('upload_max_filesize')."\n";
		$systemInfoContent .= 'Post Max Size:            '.ini_get('post_max_size')."\n";
		$systemInfoContent .= 'Upload Max Filesize:      '.ini_get('upload_max_filesize')."\n";
		$systemInfoContent .= 'Time Limit:               '.ini_get('max_execution_time')."\n";
		$systemInfoContent .= 'Max Input Vars:           '.ini_get('max_input_vars')."\n";
		$systemInfoContent .= 'Display Errors:           '.(ini_get('display_errors') ? 'On ('.ini_get('display_errors').')' : 'N/A')."\n";

		// PHP extensions and such
		$systemInfoContent .= "\n".'-- PHP Extensions'."\n\n";
		$systemInfoContent .= 'cURL:                     '.(function_exists('curl_init') ? 'Supported' : 'Not Supported')."\n";
		$systemInfoContent .= 'fsockopen:                '.(function_exists('fsockopen') ? 'Supported' : 'Not Supported')."\n";
		$systemInfoContent .= 'SOAP Client:              '.(class_exists('SoapClient') ? 'Installed' : 'Not Installed')."\n";
		$systemInfoContent .= 'Suhosin:                  '.(extension_loaded('suhosin') ? 'Installed' : 'Not Installed')."\n";

		// Session stuff
		$systemInfoContent .= "\n".'-- Session Configuration'."\n\n";
		$systemInfoContent .= 'Session:                  '.(isset($_SESSION ) ? 'Enabled' : 'Disabled')."\n";

		// The rest of this is only relevant is session is enabled
		if (isset($_SESSION)) {
			$systemInfoContent .= 'Session Name:             '.esc_html( ini_get('session.name'))."\n";
			$systemInfoContent .= 'Cookie Path:              '.esc_html( ini_get('session.cookie_path'))."\n";
			$systemInfoContent .= 'Save Path:                '.esc_html( ini_get('session.save_path'))."\n";
			$systemInfoContent .= 'Use Cookies:              '.(ini_get('session.use_cookies') ? 'On' : 'Off')."\n";
			$systemInfoContent .= 'Use Only Cookies:         '.(ini_get('session.use_only_cookies') ? 'On' : 'Off')."\n";
		}

		$systemInfoContent = apply_filters('sgpbSystemInformation', $systemInfoContent);

		$systemInfoContent .= "\n".'### End System Info ###';

		return $systemInfoContent;
	}

	public static function getHost()
	{
		if (defined('WPE_APIKEY')) {
			return 'WP Engine';
		}
		else if (defined('PAGELYBIN')) {
			return 'Pagely';
		}
		else if (DB_HOST == 'localhost:/tmp/mysql5.sock') {
			return 'ICDSoft';
		}
		else if (DB_HOST == 'mysqlv5') {
			return 'NetworkSolutions';
		}
		else if (strpos(DB_HOST, 'ipagemysql.com') !== false) {
			return 'iPage';
		}
		else if (strpos(DB_HOST, 'ipowermysql.com') !== false) {
			return 'IPower';
		}
		else if (strpos(DB_HOST, '.gridserver.com') !== false) {
			return 'MediaTemple Grid';
		}
		else if (strpos(DB_HOST, '.pair.com') !== false) {
			return 'pair Networks';
		}
		else if (strpos(DB_HOST, '.stabletransit.com') !== false) {
			return 'Rackspace Cloud';
		}
		else if (strpos(DB_HOST, '.sysfix.eu') !== false) {
			return 'SysFix.eu Power Hosting';
		}
		else if (strpos($_SERVER['SERVER_NAME'], 'Flywheel') !== false) {
			return 'Flywheel';
		}
		else {
			// Adding a general fallback for data gathering
			return 'DBH: '.DB_HOST.', SRV: '.$_SERVER['SERVER_NAME'];
		}
	}

	public function getBrowser()
	{
		$uAgent = 'Unknown';
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$uAgent = $_SERVER['HTTP_USER_AGENT'];
		}
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= '';
		$browserInfoContent = '';

		//First get the platform?
		if (preg_match('/linux/i', $uAgent)) {
			$platform = 'Linux';
		}
		else if (preg_match('/macintosh|mac os x/i', $uAgent)) {
			$platform = 'Apple';
		}
		else if (preg_match('/windows|win32/i', $uAgent)) {
			$platform = 'Windows';
		}

		if (preg_match('/MSIE/i',$uAgent) && !preg_match('/Opera/i',$uAgent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		else if (preg_match('/Firefox/i',$uAgent)) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		else if (preg_match('/OPR/i',$uAgent)) {
			$bname = 'Opera';
			$ub = "Opera";
		}
		else if (preg_match('/Chrome/i',$uAgent) && !preg_match('/Edge/i',$uAgent)) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		else if (preg_match('/Safari/i',$uAgent) && !preg_match('/Edge/i',$uAgent)) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		else if (preg_match('/Netscape/i',$uAgent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		else if (preg_match('/Edge/i',$uAgent)) {
			$bname = 'Edge';
			$ub = "Edge";
		}
		else if (preg_match('/Trident/i',$uAgent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>'.join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $uAgent, $matches)) {
			// we have no matching number just continue
		}
		// see how many we have
		$i = count($matches['browser']);
		//we will have two since we are not using 'other' argument yet
		if ($i != 1) {
			//see if version is before or after the name
			if (strripos($uAgent,"Version") < strripos($uAgent,$ub)) {
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}

		  // check if we have a number
		if ($version == null || $version == "") {$version = "?" ;}

		$browserInfoContent .= 'Platform:           '.$platform."\n";
		$browserInfoContent .= 'Browser Name:       '.$bname."\n";
		$browserInfoContent .= 'Browser Version:    '.$version."\n";
		$browserInfoContent .= 'User Agent:         '.$uAgent."\n";

		return $browserInfoContent;
	}
}
