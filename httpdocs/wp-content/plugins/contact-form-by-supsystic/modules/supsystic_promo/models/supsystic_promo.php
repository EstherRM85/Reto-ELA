<?php
class supsystic_promoModelCfs extends modelCfs {
	private $_apiUrl = '';
	private function _getApiUrl() {
		if(empty($this->_apiUrl)) {
			$this->_initApiUrl();
		}
		return $this->_apiUrl;
	}
	public function welcomePageSaveInfo($d = array()) {
		$reqUrl = $this->_getApiUrl(). '?mod=options&action=saveWelcomePageInquirer&pl=rcs';
		$d['where_find_us'] = (int) 5;	// Hardcode for now
		wp_remote_post($reqUrl, array(
			'body' => array(
				'site_url' => get_bloginfo('wpurl'),
				'site_name' => get_bloginfo('name'),
				'where_find_us' => $d['where_find_us'],
				'plugin_code' => CFS_CODE,
			)
		));
		// In any case - give user posibility to move futher
		return true;
	}
	public function saveUsageStat($code, $unique = false) {
		if($unique && $this->_checkUniqueStat($code)) {
			return;
		}
		$query = 'INSERT INTO @__usage_stat SET code = "'. dbCfs::escape($code). '", visits = 1
			ON DUPLICATE KEY UPDATE visits = visits + 1';
		return dbCfs::query($query);
	}
	private function _checkUniqueStat($code) {
		$uniqueStats = get_option(CFS_CODE. '_unique_stats');
		if(empty($uniqueStats))
			$uniqueStats = array();
		if(in_array($code, $uniqueStats)) {
			return true;
		}
		$uniqueStats[] = $code;
		update_option(CFS_CODE. '_unique_stats', $uniqueStats);
		return false;
	}
	public function saveSpentTime($code, $spent) {
		$spent = (int) $spent;
		$query = 'UPDATE @__usage_stat SET spent_time = spent_time + '. $spent. ' WHERE code = "'. $code. '"';
		return dbCfs::query($query);
	}
	public function getAllUsageStat() {
		$query = 'SELECT * FROM @__usage_stat';
		return dbCfs::get($query);
	}
	public function sendUsageStat() {
		$allStat = $this->getAllUsageStat();
		if(empty($allStat)) return;
		$reqUrl = $this->_getApiUrl(). '?mod=options&action=saveUsageStat&pl=rcs';
		$res = wp_remote_post($reqUrl, array(
			'body' => array(
				'site_url' => get_bloginfo('wpurl'),
				'site_name' => get_bloginfo('name'),
				'plugin_code' => CFS_CODE,
				'all_stat' => $allStat
			)
		));
		$this->clearUsageStat();
		// In any case - give user posibility to move futher
		return true;
	}
	public function clearUsageStat() {
		$query = 'DELETE FROM @__usage_stat';
		return dbCfs::query($query);
	}
	public function getUserStatsCount() {
		$query = 'SELECT SUM(visits) AS total FROM @__usage_stat';
		return (int) dbCfs::get($query, 'one');
	}
	public function checkAndSend($force = false){
		$statCount = $this->getUserStatsCount();
		if($statCount >= $this->getModule()->getMinStatSend() || $force) {
			$this->sendUsageStat();
		}
	}
	protected function _initApiUrl() {
		$this->_apiUrl = implode('', array('','h','t','tp',':','/','/u','p','da','t','e','s.','s','u','ps','y','st','i','c.','c','o','m',''));
	}
	public function getTourHst() {
		$hst = get_user_meta(get_current_user_id(), CFS_CODE . '-tour-hst', true);
		if(empty($hst))
			$hst = array();
		if(!isset($hst['passed']))
			$hst['passed'] = array();
		return $hst;
	}
	public function setTourHst( $hst ) {
		update_user_meta(get_current_user_id(), CFS_CODE . '-tour-hst', $hst);
	}
	public function clearTourHst() {
		delete_user_meta(get_current_user_id(), CFS_CODE . '-tour-hst');
	}
	public function addTourStep($d = array()) {
		$hst = $this->getTourHst();
		$pointKey = $d['tourId']. '-'. $d['pointId'];
		$hst['passed'][ $pointKey ] = 1;
		$this->setTourHst( $hst );
		$this->saveUsageStat('tour_pass_'. $pointKey);
	}
	public function closeTour($d = array()) {
		$hst = $this->getTourHst();
		$pointKey = $d['tourId']. '-'. $d['pointId'];
		$hst['closed'] = 1;
		$this->setTourHst( $hst );
		$this->saveUsageStat('tour_closed_on_'. $pointKey);
	}
	public function addTourFinish($d = array()) {
		$hst = $this->getTourHst();
		$pointKey = $d['tourId']. '-'. $d['pointId'];
		$hst['finished'] = 1;
		$this->setTourHst( $hst );
		$this->saveUsageStat('tour_finished_on_'. $pointKey);
	}
}
