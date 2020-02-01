<?php
class contactsModelCfs extends modelCfs {
	public function __construct() {
		$this->_setTbl('contacts');
	}
	protected function _afterGetFromTbl($row) {
		if(isset($row['fields']) && !empty($row['fields'])) {
			$row['fields'] = utilsCfs::decodeArrayTxt($row['fields']);
		}
		return $row;
	}
	public function getSimpleList($where = array(), $params = array()) {
		if($where)
			$this->setWhere ($where);
		return $this->setSelectFields('*')->getFromTbl( $params );
	}
	public function setSimpleGetFields() {
		$this->setSelectFields('*');
		return parent::setSimpleGetFields();
	}
}
