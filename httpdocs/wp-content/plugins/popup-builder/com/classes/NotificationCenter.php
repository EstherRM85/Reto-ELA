<?php
namespace sgpb;

class SGPBNotificationCenter
{
	private $notificationsCount = 0;
	private $requestUrl = SG_POPUP_BUILDER_NOTIFICATIONS_URL;
	private $cronTimeout = 'daily';

	public function __construct()
	{
		$this->addActions();
		$this->activateCron();
	}

	public function addActions()
	{
		add_filter('sgpbCronTimeoutSettings', array($this, 'cronAddMinutes'), 10, 1);
		add_action('sgpbGetNotifications', array($this, 'updateNotificationsArray'));
		add_action('wp_ajax_sgpb_dismiss_notification', array($this, 'dismissNotification'));
		add_action('wp_ajax_sgpb_reactivate_notification', array($this, 'reactivateNotification'));
		add_action('admin_head', array($this, 'menuItemCounter'));
	}

	public function menuItemCounter()
	{
		$count = count(self::getAllActiveNotifications(true));
		$hidden = '';
		if (empty($count)) {
			$hidden = ' sgpb-hide-add-button';
		}
		echo "<script>
				jQuery(document).ready(function() {
					jQuery('.sgpb-menu-item-notification').remove();
					jQuery('.dashicons-menu-icon-sgpb').next().append('<span class=\"sgpb-menu-item-notification".$hidden."\">".$count."</span>');
				});
			</script>";
	}

	public function setCronTimeout($cronTimeout)
	{
		$this->cronTimeout = $cronTimeout;
	}

	public function getCronTimeout()
	{
		return $this->cronTimeout;
	}

	public function setRequestUrl($requestUrl)
	{
		$this->requestUrl = $requestUrl;
	}

	public function getRequestUrl()
	{
		return $this->requestUrl;
	}

	public function updateNotificationsArray()
	{
		$requestUrl = $this->getRequestUrl();
		$content = AdminHelper::getFileFromURL($requestUrl);
		$content = json_decode($content, true);
		$content = apply_filters('sgpbExtraNotifications', $content);
		// check later
		/*if (empty($content)) {
			update_option('sgpb-all-dismissed-notifications', array());
		}*/
		$content = json_encode($content);
		update_option('sgpb-all-notifications-data', $content);
	}

	public function sortNotifications($allNotifications)
	{
		$allNotifications = json_decode($allNotifications, true);
		if (empty($allNotifications)) {
			$allNotifications = array();
		}
		$dismissed = self::getAllDismissedNotifications();
		// for the first time dismissed and active arrays should be empty
		if (empty($dismissed) && empty($active)) {
			$notifications = array();
			foreach ($allNotifications as $notification) {
				$id = $notification['id'];
				$notifications[$id] = $id;
			}
			update_option('sgpb-all-active-notifications', json_encode($notifications));
		}
	}

	public function cronAddMinutes($schedules)
	{
		$schedules['sgpb_notifications'] = array(
			'interval' => SGPB_NOTIFICATIONS_CRON_REPEAT_INTERVAL * 60,
			'display' => __('Once a day', SG_POPUP_TEXT_DOMAIN)
		);

		return $schedules;
	}

	public static function getAllActiveNotifications($hideDismissed = false)
	{
		$activeNotifications = array();
		$notifications = get_option('sgpb-all-notifications-data');
		$notifications = json_decode($notifications, true);
		if (empty($notifications)) {
			return array();
		}
		asort($notifications);
		$dismissedNotifications = get_option('sgpb-all-dismissed-notifications');
		$dismissedNotifications = json_decode($dismissedNotifications, true);
		foreach ($notifications as $notification) {
			$id = @$notification['id'];
			if ($hideDismissed) {
				if (isset($dismissedNotifications[$id])) {
					continue;
				}
			}
			$activeNotifications[] = $notification;
		}

		return $activeNotifications;
	}

	public static function getAllDismissedNotifications()
	{
		$notifications = get_option('sgpb-all-dismissed-notifications');
		if (empty($notifications)) {
			$notifications = '';
		}

		return json_decode($notifications, true);
	}

	public static function displayNotifications($withoutWrapper = false)
	{
		$content = '';
		$allNotifications = self::getAllActiveNotifications();
		if (empty($allNotifications)) {
			return $content;
		}

		$count = count(self::getAllActiveNotifications(true));

		foreach ($allNotifications as $notification) {
			$newNotification = new Notification();
			$newNotification->setId($notification['id']);
			$newNotification->setType($notification['type']);
			$newNotification->setPriority($notification['priority']);
			$newNotification->setMessage($notification['message']);
			$content .= $newNotification->render();
		}
		$count = '(<span class="sgpb-notifications-count-span">'.$count.'</span>)';

		if ($withoutWrapper) {
			return $content;
		}

		$content = self::prepareHtml($content, $count);

		return $content;
	}

	public static function prepareHtml($content = '', $count = 0)
	{
		$content = '<div class="sgpb-each-notification-wrapper-js">'.$content.'</div>';
		$content = '<div class="sgpb-notification-center-wrapper">
						<h3><span class="dashicons dashicons-flag"></span> Notifications '.$count.'</h3>'.$content.'
					</div>';

		return $content;
	}

	public function dismissNotification()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$notificationId = sanitize_text_field($_POST['id']);
		$allDismissedNotifications = self::getAllDismissedNotifications();
		$allDismissedNotifications[$notificationId] = $notificationId;
		$allDismissedNotifications = json_encode($allDismissedNotifications);

		update_option('sgpb-all-dismissed-notifications', $allDismissedNotifications);
		$result = array();
		$result['content'] = self::displayNotifications(true);
		$result['count'] = count(self::getAllActiveNotifications(true));

		echo json_encode($result);
		wp_die();
	}

	public function reactivateNotification()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$notificationId = sanitize_text_field($_POST['id']);
		$allDismissedNotifications = self::getAllDismissedNotifications();
		if (isset($allDismissedNotifications[$notificationId])) {
			unset($allDismissedNotifications[$notificationId]);
		}
		$allDismissedNotifications = json_encode($allDismissedNotifications);

		update_option('sgpb-all-dismissed-notifications', $allDismissedNotifications);
		$result = array();
		$result['content'] = self::displayNotifications(true);
		$result['count'] = count(self::getAllActiveNotifications(true));

		echo json_encode($result);
		wp_die();
	}

	public function activateCron()
	{
		if (!wp_next_scheduled('sgpbGetNotifications')) {
			wp_schedule_event(time(), 'daily', 'sgpbGetNotifications');
		}
	}
}

new SGPBNotificationCenter();
