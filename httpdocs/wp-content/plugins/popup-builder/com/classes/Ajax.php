<?php
namespace sgpb;
use \ConfigDataHelper;

class Ajax
{
	private $postData;

	public function __construct()
	{
		$this->actions();
	}

	public function setPostData($postData)
	{
		$this->postData = $postData;
	}

	public function getPostData()
	{
		return $this->postData;
	}

	/**
	 * Return ajax param form post data by key
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 *
	 * @return string $value
	 */
	public function getValueFromPost($key)
	{
		$postData = $this->getPostData();
		$value = '';

		if (!empty($postData[$key])) {
			$value = $postData[$key];
		}

		return $value;
	}

	public function actions()
	{
		add_action('wp_ajax_add_condition_group_row', array($this, 'addConditionGroupRow'));
		add_action('wp_ajax_add_condition_rule_row', array($this, 'addConditionRuleRow'));
		add_action('wp_ajax_change_condition_rule_row', array($this, 'changeConditionRuleRow'));
		add_action('wp_ajax_select2_search_data', array($this, 'select2SearchData'));
		add_action('wp_ajax_sgpb_subscription_submission', array($this, 'subscriptionSubmission'));
		add_action('wp_ajax_nopriv_sgpb_subscription_submission', array($this, 'subscriptionSubmission'));
		add_action('wp_ajax_sgpb_process_after_submission', array($this, 'sgpbSubsciptionFormSubmittedAction'));
		add_action('wp_ajax_nopriv_sgpb_process_after_submission', array($this, 'sgpbSubsciptionFormSubmittedAction'));
		add_action('wp_ajax_change_popup_status', array($this, 'changePopupStatus'));
		// proStartGold
		add_action('wp_ajax_check_same_origin', array($this, 'checkSameOrigin'));
		// proEndGold
		add_action('wp_ajax_sgpb_subscribers_delete', array($this, 'deleteSubscribers'));
		add_action('wp_ajax_sgpb_add_subscribers', array($this, 'addSubscribers'));
		add_action('wp_ajax_sgpb_import_subscribers', array($this, 'importSubscribers'));
		add_action('wp_ajax_sgpb_save_imported_subscribers', array($this, 'saveImportedSubscribers'));
		add_action('wp_ajax_sgpb_send_newsletter', array($this, 'sendNewsletter'));
		add_action('wp_ajax_sgpb_send_to_open_counter', array($this, 'addToCounter'));
		add_action('wp_ajax_sgpb_change_review_popup_show_period', array($this, 'changeReviewPopupPeriod'));
		add_action('wp_ajax_nopriv_sgpb_change_review_popup_show_period', array($this, 'changeReviewPopupPeriod'));
		add_action('wp_ajax_sgpb_dont_show_review_popup', array($this, 'dontShowReviewPopup'));
		add_action('wp_ajax_nopriv_sgpb_dont_show_review_popup', array($this, 'dontShowReviewPopup'));
		add_action('wp_ajax_nopriv_sgpb_send_to_open_counter', array($this, 'addToCounter'));
		add_action('wp_ajax_sgpb_close_banner', array($this, 'closeMainRateUsBanner'));
		add_action('wp_ajax_sgpb_close_license_notice', array($this, 'closeLicenseNoticeBanner'));
		add_action('wp_ajax_sgpb_hide_ask_review_popup', array($this, 'dontShowAskReviewBanner'));
		add_action('wp_ajax_sgpb_reset_popup_opening_count', array($this, 'resetPopupOpeningCount'));
		/*Extension notification panel*/
		add_action('wp_ajax_sgpb_dont_show_extension_panel', array($this, 'extensionNotificationPanel'));
		add_action('wp_ajax_sgpb_dont_show_problem_alert', array($this, 'dontShowProblemAlert'));
		// autosave
		add_action('wp_ajax_sgpb_autosave', array($this, 'sgpbAutosave'));
		add_action('wp_ajax_nopriv_sgpb_autosave', array($this, 'sgpbAutosave'));
	}

	public function sgpbAutosave()
	{
		$popupId = @(int)$_POST['post_ID'];
		$postStatus = get_post_status($popupId);
		if ($postStatus == 'publish') {
			echo '';
			wp_die();
		}

		if (!isset($_POST['allPopupData'])) {
			echo true;
			wp_die();
		}
		$popupData = SGPopup::parsePopupDataFromData($_POST['allPopupData']);
		do_action('save_post_popupbuilder');
		$popupType = $popupData['sgpb-type'];
		$popupClassName = SGPopup::getPopupClassNameFormType($popupType);
		$popupClassPath = SGPopup::getPopupTypeClassPath($popupType);
		if (file_exists($popupClassPath.$popupClassName.'.php')) {
			require_once($popupClassPath.$popupClassName.'.php');
			$popupClassName = __NAMESPACE__.'\\'.$popupClassName;
			$popupClassName::create($popupData, '_preview', 1);
		}

		wp_die();
	}

	public function dontShowReviewPopup()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		update_option('SGPBCloseReviewPopup-notification', true);
		do_action('sgpbGetNotifications');
		wp_die();
	}

	public function changeReviewPopupPeriod()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$messageType = sanitize_text_field($_POST['messageType']);

		if ($messageType == 'count') {
			$maxPopupCount = get_option('SGPBMaxOpenCount');
			if (!$maxPopupCount) {
				$maxPopupCount = SGPB_ASK_REVIEW_POPUP_COUNT;
			}
			$maxPopupData = AdminHelper::getMaxOpenPopupId();
			if (!empty($maxPopupData['maxCount'])) {
				$maxPopupCount = $maxPopupData['maxCount'];
			}

			$maxPopupCount += SGPB_ASK_REVIEW_POPUP_COUNT;
			update_option('SGPBMaxOpenCount', $maxPopupCount);
			wp_die();
		}

		$popupTimeZone = get_option('timezone_string');
		if (!$popupTimeZone) {
			$popupTimeZone = SG_POPUP_DEFAULT_TIME_ZONE;
		}
		$timeDate = new \DateTime('now', new \DateTimeZone($popupTimeZone));
		$timeDate->modify('+'.SGPB_REVIEW_POPUP_PERIOD.' day');

		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
		update_option('SGPBOpenNextTime', $timeNow);
		$usageDays = get_option('SGPBUsageDays');
		$usageDays += SGPB_REVIEW_POPUP_PERIOD;
		update_option('SGPBUsageDays', $usageDays);
		wp_die();
	}

	public function resetPopupOpeningCount()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		global $wpdb;
		$popupId = (int)$_POST['popupId'];
		$allPopupsCount = get_option('SgpbCounter');
		if (empty($allPopupsCount)) {
			echo SGPB_AJAX_STATUS_FALSE;
			wp_die();
		}
		if (isset($allPopupsCount[$popupId])) {
			$allPopupsCount[$popupId] = 0;
		}
		// 7, 12, 13 => exclude close, subscription success, contact success events
		$stmt = $wpdb->prepare(' DELETE FROM '.$wpdb->prefix.'sgpb_analytics WHERE target_id = %d AND event_id NOT IN (7, 12, 13)', $popupId);
		$popupAnalyticsData = $wpdb->get_var($stmt);

		update_option('SgpbCounter', $allPopupsCount);

	}

	public function dontShowAskReviewBanner()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		update_option('sgpbDontShowAskReviewBanner', 1);
		echo SGPB_AJAX_STATUS_TRUE;
		wp_die();
	}

	public function dontShowProblemAlert()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		update_option('sgpb_alert_problems', 1);
		echo SGPB_AJAX_STATUS_TRUE;
		wp_die();
	}

	public function extensionNotificationPanel()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		update_option('sgpb_extensions_updated', 1);
		echo SGPB_AJAX_STATUS_TRUE;
		wp_die();
	}

	public function closeMainRateUsBanner()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		update_option('sgpb-hide-support-banner', 1);
		do_action('sgpbGetNotifications');
		wp_die();
	}

	public function closeLicenseNoticeBanner()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		update_option('sgpb-hide-license-notice-banner', 1);
		wp_die();
	}

	public function addToCounter()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$popupParams = $_POST['params'];
		if (isset($_GET['sg_popup_preview_id'])) {
			wp_die();
		}
		$popupsIdCollection = $popupParams['popupsIdCollection'];

		$popupsCounterData = get_option('SgpbCounter');

		if ($popupsCounterData === false) {
			$popupsCounterData = array();
		}

		if (!empty($popupsIdCollection)) {
			foreach ($popupsIdCollection as $popupId => $popupCount) {
				if (empty($popupsCounterData[$popupId])) {
					$popupsCounterData[$popupId] = 0;
				}
				$popupsCounterData[$popupId] += $popupCount;
			}
		}

		update_option('SgpbCounter', $popupsCounterData);
		wp_die();
	}

	public function deleteSubscribers()
	{
		global $wpdb;

		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$subscribersId = array_map('sanitize_text_field', $_POST['subscribersId']);

		foreach ($subscribersId as $subscriberId) {
			$prepareSql = $wpdb->prepare('DELETE FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE id = %d', $subscriberId);
			$wpdb->query($prepareSql);
		}
	}

	public function addSubscribers()
	{
		global $wpdb;

		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$status = SGPB_AJAX_STATUS_FALSE;
		$firstName = sanitize_text_field($_POST['firstName']);
		$lastName = sanitize_text_field($_POST['lastName']);
		$email = sanitize_text_field($_POST['email']);
		$date = date('Y-m-d');
		$subscriptionPopupsId = array_map('sanitize_text_field', $_POST['popups']);

		foreach ($subscriptionPopupsId as $subscriptionPopupId) {
			$selectSql = $wpdb->prepare('SELECT id FROM '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' WHERE email = %s AND subscriptionType = %d', $email, $subscriptionPopupId);
			$res = $wpdb->get_row($selectSql, ARRAY_A);
			// add new subscriber
			if (empty($res)) {
				$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' (firstName, lastName, email, cDate, subscriptionType) VALUES (%s, %s, %s, %s, %d) ', $firstName, $lastName, $email, $date, $subscriptionPopupId);
				$res = $wpdb->query($sql);
			}
			// edit existing
			else {
				$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' SET firstName = %s, lastName = %s, email = %s, cDate = %s, subscriptionType = %d, unsubscribered = 0 WHERE id = %d', $firstName, $lastName, $email, $date, $subscriptionPopupId, $res['id']);
				$wpdb->query($sql);
				$res = 1;
			}

			if ($res) {
				$status = SGPB_AJAX_STATUS_TRUE;
			}
		}

		echo $status;
		wp_die();
	}

	public function importSubscribers()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$formId = (int)sanitize_text_field($_POST['popupSubscriptionList']);
		$fileURL = sanitize_text_field($_POST['importListURL']);

		ob_start();
		require_once SG_POPUP_VIEWS_PATH.'importConfigView.php';
		$content = ob_get_contents();
		ob_end_clean();

		echo $content;
		wp_die();
	}

	public function saveImportedSubscribers()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$formId = (int)sanitize_text_field($_POST['popupSubscriptionList']);
		$fileURL = sanitize_text_field($_POST['importListURL']);
		$mapping = $_POST['namesMapping'];

		$fileContent = AdminHelper::getFileFromURL($fileURL);
		$csvFileArray = array_map('str_getcsv', file($fileURL));

		$header = $csvFileArray[0];
		unset($csvFileArray[0]);
		$subscriptionPlusContent = apply_filters('sgpbImportToSubscriptionList', $csvFileArray, $mapping, $formId);

		// -1 it's mean saved from Subscription Plus
		if ($subscriptionPlusContent != -1) {
			foreach ($csvFileArray as $csvData) {
				global $wpdb;
				$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;
				$sql = $wpdb->prepare('SELECT submittedData FROM '.$subscribersTableName);
				if ($sql) {
					$sql = $wpdb->prepare('INSERT INTO '.$subscribersTableName.' (firstName, lastName, email, cDate, subscriptionType, status, unsubscribed) VALUES (%s, %s, %s, %s, %d, %d, %d) ', $csvData[$mapping['firstName']], $csvData[$mapping['lastName']], $csvData[$mapping['email']], $csvData[$mapping['date']], $formId, 0, 0);
				}
				else {
					$sql = $wpdb->prepare('INSERT INTO '.$subscribersTableName.' (firstName, lastName, email, cDate, subscriptionType, status, unsubscribed, submittedData) VALUES (%s, %s, %s, %s, %d, %d, %d, %s) ', $csvData[$mapping['firstName']], $csvData[$mapping['lastName']], $csvData[$mapping['email']], $csvData[$mapping['date']], $formId, 0, 0, '');
				}

				$wpdb->query($sql);
			}
		}

		echo SGPB_AJAX_STATUS_TRUE;
		wp_die();
	}

	public function sendNewsletter()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		global $wpdb;

		$newsletterData = stripslashes_deep($_POST['newsletterData']);
		$subscriptionFormId = (int)$newsletterData['subscriptionFormId'];

		$updateStatusQuery = $wpdb->prepare('UPDATE '.$wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME.' SET status = 0 WHERE subscriptionType = %d', $subscriptionFormId);
		$wpdb->query($updateStatusQuery);
		$newsletterData['blogname'] = get_bloginfo('name');
		$newsletterData['username'] = wp_get_current_user()->user_login;
		update_option('SGPB_NEWSLETTER_DATA', $newsletterData);

		wp_schedule_event(time(), 'sgpb_newsletter_send_every_minute', 'sgpb_send_newsletter');
		wp_die();
	}

	// proStartGold
	public function checkSameOrigin()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$url = esc_url($_POST['iframeUrl']);
		$status = SGPB_AJAX_STATUS_FALSE;

		$remoteGet = wp_remote_get($url);

		if (is_array($remoteGet) && !empty($remoteGet['headers']['x-frame-options'])) {
			$siteUrl = esc_url($_POST['siteUrl']);
			$xFrameOptions = $remoteGet['headers']['x-frame-options'];
			$mayNotShow = false;

			if ($xFrameOptions == 'deny') {
				$mayNotShow = true;
			}
			else if ($xFrameOptions == 'SAMEORIGIN') {
				if (strpos($url, $siteUrl) === false) {
					$mayNotShow = true;
				}
			}
			else {
				if (strpos($xFrameOptions, $siteUrl) === false) {
					$mayNotShow = true;;
				}
			}

			if ($mayNotShow) {
				echo $status;
				wp_die();
			}
		}

		// $remoteGet['response']['code'] < 400 it's mean correct status
		if (is_array($remoteGet) && isset($remoteGet['response']['code']) && $remoteGet['response']['code'] < 400) {
			$status = SGPB_AJAX_STATUS_TRUE;
		}

		echo $status;
		wp_die();
	}
	// proEndGold

	public function changePopupStatus()
	{
		$popupId = (int)$_POST['popupId'];
		$obj = SGPopup::find($popupId);
		$isDraft = '';
		$postStatus = get_post_status($popupId);
		if ($postStatus == 'draft') {
			$isDraft = '_preview';
		}

		if (!$obj) {
			wp_die(SGPB_AJAX_STATUS_FALSE);
		}
		$options = $obj->getOptions();
		$options['sgpb-is-active'] = sanitize_text_field($_POST['popupStatus']);

		unset($options['sgpb-conditions']);
		update_post_meta($popupId, 'sg_popup_options'.$isDraft, $options);

		wp_die($popupId);
	}

	public function subscriptionSubmission()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		$this->setPostData($_POST);
		$submissionData = $this->getValueFromPost('formData');
		$popupPostId = (int)$this->getValueFromPost('popupPostId');

		parse_str($submissionData, $formData);

		if (empty($formData)) {
			echo SGPB_AJAX_STATUS_FALSE;
			wp_die();
		}

		$hiddenChecker = sanitize_text_field($formData['sgpb-subs-hidden-checker']);

		// this check is made to protect ourselves from bot
		if (!empty($hiddenChecker)) {
			echo 'Bot';
			wp_die();
		}
		global $wpdb;

		$status = SGPB_AJAX_STATUS_FALSE;
		$date = date('Y-m-d');
		$email = sanitize_email($formData['sgpb-subs-email']);
		$firstName = sanitize_text_field($formData['sgpb-subs-first-name']);
		$lastName = sanitize_text_field($formData['sgpb-subs-last-name']);

		$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;

		$getSubscriberQuery = $wpdb->prepare('SELECT id FROM '.$subscribersTableName.' WHERE email = %s AND subscriptionType = %d', $email, $popupPostId);
		$list = $wpdb->get_row($getSubscriberQuery, ARRAY_A);

		// When subscriber does not exist we insert to subscribers table otherwise we update user info
		if (empty($list['id'])) {
			$sql = $wpdb->prepare('INSERT INTO '.$subscribersTableName.' (firstName, lastName, email, cDate, subscriptionType) VALUES (%s, %s, %s, %s, %d) ', $firstName, $lastName, $email, $date, $popupPostId);
			$res = $wpdb->query($sql);
		}
		else {
			$sql = $wpdb->prepare('UPDATE '.$subscribersTableName.' SET firstName = %s, lastName = %s, email = %s, cDate = %s, subscriptionType = %d WHERE id = %d', $firstName, $lastName, $email, $date, $popupPostId, $list['id']);
			$wpdb->query($sql);
			$res = 1;
		}
		if ($res) {
			$status = SGPB_AJAX_STATUS_TRUE;
		}

		echo $status;
		wp_die();
	}

	public function sgpbSubsciptionFormSubmittedAction()
    {
        check_ajax_referer(SG_AJAX_NONCE, 'nonce');
        $this->setPostData($_POST);

        $submissionData = $this->getValueFromPost('formData');
        $popupPostId = (int)$this->getValueFromPost('popupPostId');
        parse_str($submissionData, $formData);
        if (empty($_POST)) {
            echo SGPB_AJAX_STATUS_FALSE;
            wp_die();
        }
        $email = sanitize_email($_POST['emailValue']);
        $firstName = sanitize_text_field($_POST['firstNameValue']);
        $lastName = sanitize_text_field($_POST['lastNameValue']);
        $userData = array(
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName
        );
        $this->sendSuccessEmails($popupPostId, $userData);
        do_action('sgpbProcessAfterSuccessfulSubmission', $popupPostId, $userData);
    }

	public function sendSuccessEmails($popupPostId, $subscriptionDetails)
	{
		global $wpdb;
		$popup = SGPopup::find($popupPostId);

		if (!is_object($popup)) {
			return false;
		}
		$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;

		$getSubscriberCountQuery = $wpdb->prepare('SELECT COUNT(id) as countIds FROM '.$subscribersTableName.' WHERE subscriptionType = %d', $popupPostId);
		$count = $wpdb->get_row($getSubscriberCountQuery, ARRAY_A);

		$popupOptions = $popup->getOptions();
		$adminUserName = 'admin';

		$adminEmail = get_option('admin_email');
		$userData = @get_user_by_email($adminEmail);

		if (!empty($userData)) {
			$adminUserName = $userData->display_name;
		}

		$newSubscriberEmailHeader = AdminHelper::getEmailHeader($adminEmail);
		$takeReviewAfterFirstSubscription = get_option('sgpb-new-subscriber');

		if ($count['countIds'] == 1 && !$takeReviewAfterFirstSubscription) {
			// take review
			update_option('sgpb-new-subscriber', 1);
			$newSubscriberEmailTitle = __('Congrats! You have already 1 subscriber!', SG_POPUP_TEXT_DOMAIN);
			$reviewEmailTemplate = AdminHelper::getFileFromURL(SG_POPUP_EMAIL_TEMPLATES_URL.'takeReviewAfterSubscribe.html');
			$reviewEmailTemplate = preg_replace('/\[adminUserName]/', $adminUserName, $reviewEmailTemplate);
			$sendStatus = wp_mail($adminEmail, $newSubscriberEmailTitle, $reviewEmailTemplate, $newSubscriberEmailHeader); //return true or false
		}
	}

	public function select2SearchData()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');

		$postTypeName = sanitize_text_field($_POST['searchKey']);
		$search = sanitize_text_field($_POST['searchTerm']);
		$searchResults = $this->selectFromPost($postTypeName, $search);

		if (isset($_POST['searchCallback'])) {
			$searchCallback = sanitize_text_field($_POST['searchCallback']);
			$searchResults = apply_filters('sgpbSearchAdditionalData', $search, array());
		}

		if (empty($searchResults)) {
			$results['items'] = array();
		}

		/*Selected custom post type convert for select2 format*/
		foreach ($searchResults as $id => $name) {
			$results['items'][] = array(
				'id'   => $id,
				'text' => $name
			);
		}

		echo json_encode($results);
		wp_die();
	}

	private function selectFromPost($postTypeName, $search)
	{
		$args      = array(
			's'              => $search,
			'post__in'       => ! empty( $_REQUEST['include'] ) ? array_map( 'intval', $_REQUEST['include'] ) : null,
			'page'           => ! empty( $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : null,
			'posts_per_page' => 100,
			'post_type'      => $postTypeName
		);
		$searchResults = ConfigDataHelper::getPostTypeData($args);

		return $searchResults;
	}

	public function addConditionGroupRow()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');
		global $SGPB_DATA_CONFIG_ARRAY;

		$groupId = (int)$_POST['groupId'];
		$targetType = sanitize_text_field($_POST['conditionName']);
		$addedObj = array();

		$builderObj = new ConditionBuilder();

		$builderObj->setGroupId($groupId);
		$builderObj->setRuleId(SG_CONDITION_FIRST_RULE);
		$builderObj->setSavedData($SGPB_DATA_CONFIG_ARRAY[$targetType]['initialData'][0]);
		$builderObj->setConditionName($targetType);
		$addedObj[] = $builderObj;

		$creator = new ConditionCreator($addedObj);
		echo $creator->render();
		wp_die();
	}

	public function addConditionRuleRow()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');
		$data = '';
		global $SGPB_DATA_CONFIG_ARRAY;
		$targetType = sanitize_text_field($_POST['conditionName']);
		$builderObj = new ConditionBuilder();

		$groupId = (int)$_POST['groupId'];
		$ruleId = (int)$_POST['ruleId'];

		$builderObj->setGroupId($groupId);
		$builderObj->setRuleId($ruleId);
		$builderObj->setSavedData($SGPB_DATA_CONFIG_ARRAY[$targetType]['initialData'][0]);
		$builderObj->setConditionName($targetType);

		$data .= ConditionCreator::createConditionRuleRow($builderObj);

		echo $data;
		wp_die();
	}

	public function changeConditionRuleRow()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');
		$data = '';
		global $SGPB_DATA_CONFIG_ARRAY;

		$targetType = sanitize_text_field($_POST['conditionName']);
		$builderObj = new ConditionBuilder();
		$conditionConfig = $SGPB_DATA_CONFIG_ARRAY[$targetType];
		$groupId = (int)$_POST['groupId'];
		$ruleId = (int)$_POST['ruleId'];
		$popupId = (int)$_POST['popupId'];
		$paramName = sanitize_text_field($_POST['paramName']);

		$savedData = array(
			'param' => 	$paramName
		);

		if ($targetType == 'target' || $targetType == 'conditions') {
			$savedData['operator'] = '==';
		}
		else if ($conditionConfig['specialDefaultOperator']) {
			$savedData['operator'] = $paramName;
		}

		if (!empty($_POST['paramValue'])) {
			$savedData['tempParam'] = sanitize_text_field($_POST['paramValue']);
			$savedData['operator'] = $paramName;
		}
		// change operator value related to condition value
		if (!empty($conditionConfig['operatorAllowInConditions']) && in_array($paramName, $conditionConfig['operatorAllowInConditions'])) {
			$conditionConfig['paramsData']['operator'] = array();

			if (!empty($conditionConfig['paramsData'][$paramName.'Operator'])) {
				$operatorData = $conditionConfig['paramsData'][$paramName.'Operator'];
				$SGPB_DATA_CONFIG_ARRAY[$targetType]['paramsData']['operator'] = $operatorData;
				// change take value related to condition value
				$operatorDataKeys = array_keys($operatorData);
				if (!empty($operatorDataKeys[0])) {
					$savedData['operator'] = $operatorDataKeys[0];
					$builderObj->setTakeValueFrom('operator');
				}
			}
		}
		// by default set empty value for users' role (adv. tar.)
		$savedData['value'] = array();
		$savedData['hiddenOption'] = @$conditionConfig['hiddenOptionData'][$paramName];

		$builderObj->setPopupId($popupId);
		$builderObj->setGroupId($groupId);
		$builderObj->setRuleId($ruleId);
		$builderObj->setSavedData($savedData);
		$builderObj->setConditionName($targetType);

		$data .= ConditionCreator::createConditionRuleRow($builderObj);

		echo $data;
		wp_die();
	}
}
