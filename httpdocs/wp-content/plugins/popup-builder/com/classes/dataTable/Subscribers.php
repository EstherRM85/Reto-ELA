<?php
require_once(SG_POPUP_CLASSES_PATH.'/Ajax.php');
require_once(SG_POPUP_HELPERS_PATH.'AdminHelper.php');

use sgpb\SGPopup;
use sgpb\AdminHelper;
use sgpbDataTable\SGPBTable;
use sgpb\SubscriptionPopup;

class Subscribers extends SGPBTable
{
	public function __construct()
	{
		global $wpdb;
		parent::__construct('');

		$this->setRowsPerPage(SGPB_APP_POPUP_TABLE_LIMIT);
		$this->setTablename($wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME);

		$columns = array(
			$this->tablename.'.id',
			'firstName',
			'lastName',
			'email',
			'cDate',
			'subscriptionType'
		);

		$displayColumns = array(
			'bulk'=>'<input class="subs-bulk" type="checkbox" autocomplete="off">',
			'id' => 'ID',
			'firstName' => __('First name', SG_POPUP_TEXT_DOMAIN),
			'lastName' => __('Last name', SG_POPUP_TEXT_DOMAIN),
			'email' => __('Email', SG_POPUP_TEXT_DOMAIN),
			'cDate' => __('Date', SG_POPUP_TEXT_DOMAIN),
			'subscriptionType' => __('Popup', SG_POPUP_TEXT_DOMAIN)
		);

		$filterColumnsDisplaySettings = array(
			'columns' => $columns,
			'displayColumns' => $displayColumns
		);

		$filterColumnsDisplaySettings = apply_filters('sgpbAlterColumnIntoSubscribers', $filterColumnsDisplaySettings);

		$this->setColumns(@$filterColumnsDisplaySettings['columns']);
		$this->setDisplayColumns(@$filterColumnsDisplaySettings['displayColumns']);
		$this->setSortableColumns(array(
			'id' => array('id', false),
			'firstName' => array('firstName', true),
			'lastName' => array('lastName', true),
			'email' => array('email', true),
			'cDate' => array('cDate', true),
			'subscriptionType' => array('subscriptionType', true),
			$this->setInitialSort(array(
				'id' => 'DESC'
			))
		));
	}

	public function customizeRow(&$row)
	{
		$popupId = (int)$row[5];
		$row = apply_filters('sgpbEditSubscribersTableRowValues', $row, $popupId);
		$row[6] = get_the_title($popupId);
		$row[5] = $row[4];
		$row[4] = $row[3];
		$row[3] = $row[2];
		$row[2] = $row[1];
		$row[1] = $row[0];

		// show date more user friendly
		$row[5] = date('d F Y', strtotime($row[5]));

		$id = $row[0];
		$row[0] = '<input type="checkbox" class="subs-delete-checkbox" data-delete-id="'.esc_attr($id).'">';
	}

	public function customizeQuery(&$query)
	{
		$query = AdminHelper::subscribersRelatedQuery($query);
	}

	public function getNavPopupsConditions()
	{
		$subscriptionPopups = SubscriptionPopup::getAllSubscriptionForms();
		$list = '';
		$selectedPopup = '';

		if (isset($_GET['sgpb-subscription-popup-id'])) {
			$selectedPopup = (int)$_GET['sgpb-subscription-popup-id'];
		}

		ob_start();
		?>
		<input type="hidden" class="sgpb-subscription-popup-id" name="sgpb-subscription-popup-id" value="<?php echo $selectedPopup;?>">
		<input type="hidden" name="page" value="<?php echo SG_POPUP_SUBSCRIBERS_PAGE; ?>" >

		<select name="sgpb-subscription-popup" id="sgpb-subscription-popup">
			<?php
			$list .= '<option value="all">'.__('All', SG_POPUP_TEXT_DOMAIN).'</option>';
			foreach ($subscriptionPopups as $popupId => $popupTitle) {
				if ($selectedPopup == $popupId) {
					$selected = ' selected';
				}
				else {
					$selected = '';
				}
				$list .= '<option value="'.esc_attr($popupId).'"'.$selected.'>'.$popupTitle.'</option>';
			}
			echo $list;
			?>
		</select>

		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public function getNavDateConditions() {
		$subscribersDates = SubscriptionPopup::getAllSubscribersDate();
		$uniqueDates = array();

		foreach ($subscribersDates as $arr) {
			$uniqueDates[] = $arr;
		}
		$uniqueDates = array_unique($uniqueDates, SORT_REGULAR);

		$selectedDate = '';
		$dateList = '';
		$selected = '';

		if (isset($_GET['sgpb-subscribers-date'])) {
			$selectedDate = esc_sql($_GET['sgpb-subscribers-date']);
		}

		ob_start();
		?>
		<input type="hidden" class="sgpb-subscribers-date" name="sgpb-subscribers-date" value="<?php echo $selectedDate;?>">
		<select name="sgpb-subscribers-dates" id="sgpb-subscribers-dates">
			<?php
			$gotDateList = '<option value="all">'.__('All dates', SG_POPUP_TEXT_DOMAIN).'</option>';
			foreach ($uniqueDates as $date) {
				if ($selectedDate == $date['date-value']) {
					$selected = ' selected';
				}
				else {
					$selected = '';
				}
				$gotDateList .= '<option value="'.$date['date-value'].'"'.$selected.'>'.$date['date-title'].'</option>';
			}
			if (empty($subscribersDates)) {
				$gotDateList = '<option value="'.@$date['date-value'].'"'.$selected.'>'.__('Date', SG_POPUP_TEXT_DOMAIN).'</option>';
			}
			echo $dateList.$gotDateList;
			?>
		</select>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
