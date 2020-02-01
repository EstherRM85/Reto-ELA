<?php
class membershipModelCfs extends modelCfs {
	protected $_memberShipClassName;

	public function __construct() {
		$this->_memberShipClassName = 'SupsysticMembership';
		$this->_setTbl('membership_presets');
	}

	public function isPluginActive() {
		if(class_exists($this->_memberShipClassName)) {
			$tableExistsQuery =  "SHOW TABLES LIKE '@__" . $this->_tbl . "'";
			$results = dbCfs::get($tableExistsQuery);
			if(count($results)) {
				return true;
			}
		}
		return false;
	}

	public function getPluginInstallUrl() {
		return add_query_arg(
			array(
				's' => 'Membership by Supsystic',
				'tab' => 'search',
				'type' => 'term',
			),
			admin_url( 'plugin-install.php' )
		);
	}

	public function updateRow($params) {
		if(isset($params['form_id']) && isset($params['allow_use'])) {
			$allowUse = (int)$params['allow_use'];
			$formId = (int)$params['form_id'];

			if($formId && isset($allowUse)) {
				$query = "INSERT INTO `@__" . $this->_tbl . "`(`form_id`, `allow_use`)"
					. " VALUES (" . $formId . ", " . $allowUse . ") "
					. "ON DUPLICATE KEY UPDATE `allow_use`=" . $allowUse;

				$res = dbCfs::query($query);
				return $res;
			}
		}
		return false;
	}
}
