<?php
class formsCfs extends moduleCfs {
	private $_assetsUrl = '';
	private $_fieldTypes = array();

	public function init() {
		dispatcherCfs::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		add_shortcode(CFS_SHORTCODE, array($this, 'showForm'));
		add_shortcode(CFS_SHORTCODE_SUBMITTED, array($this, 'showFormSubmittedData'));
		// Add to admin bar new item
		add_action('admin_bar_menu', array($this, 'addAdminBarNewItem'), 300);
		add_action('wp_loaded', array($this, 'checkRemoveExpiredContacts'));
		dispatcherCfs::addFilter('formCss', array($this, 'addFormCss'), 10, 2);
		dispatcherCfs::addFilter('formsChangeTpl', array($this, 'formsChangeTpl'), 10, 2);
	}
	public function addFormCss($css, $form) {
		$css = str_replace('input[type="submit"]', 'input[type="submit"]:not([type="checkbox"]):not([type="radio"])', $css);
		$css = str_replace('input[type="reset"]', 'input[type="reset"]:not([type="checkbox"]):not([type="radio"])', $css);
		return $css;
	}
	public function formsChangeTpl($newTpl, $currentForm) {
		if($newTpl['unique_id'] == 'uwi23o') {
			$newTpl['css'] .= '
#[SHELL_ID] .cfsFileList { color: {{adjust_brightness("[bg_color_1]", 109)}}; }';
		}
		return $newTpl;
	}
	public function addAdminTab($tabs) {
		$tabs[ $this->getCode(). '_add_new' ] = array(
			'label' => __('Add New Form', CFS_LANG_CODE), 'callback' => array($this, 'getAddNewTabContent'), 'fa_icon' => 'fa-plus-circle', 'sort_order' => 10, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode(). '_edit' ] = array(
			'label' => __('Edit', CFS_LANG_CODE), 'callback' => array($this, 'getEditTabContent'), 'sort_order' => 20, 'child_of' => $this->getCode(), 'hidden' => 1, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode() ] = array(
			'label' => __('Show All Forms', CFS_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-list', 'sort_order' => 20, //'is_main' => true,
		);
		$tabs[ $this->getCode(). '_contacts' ] = array(
			'label' => __('Contacts', CFS_LANG_CODE), 'callback' => array($this, 'getContactsTabContent'), 'fa_icon' => 'fa-users', 'sort_order' => 25, //'is_main' => true,
		);
		return $tabs;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getContactsTabContent() {
		$id = (int) reqCfs::getVar('id', 'get');
		return $this->getView()->getContactsTabContent( $id );
	}
	public function getAddNewTabContent() {
		return $this->getView()->getAddNewTabContent();
	}
	public function getEditTabContent() {
		$id = (int) reqCfs::getVar('id', 'get');
		return $this->getView()->getEditTabContent( $id );
	}
	public function getEditLink($id, $formsTab = '') {
		$link = frameCfs::_()->getModule('options')->getTabUrl( $this->getCode(). '_edit' );
		$link .= '&id='. $id;
		if(!empty($formsTab)) {
			$link .= '#'. $formsTab;
		}
		return $link;
	}
	public function getAssetsUrl() {
		if(empty($this->_assetsUrl)) {
			$this->_assetsUrl = frameCfs::_()->getModule('templates')->getCdnUrl(). '_assets/forms/';
		}
		return $this->_assetsUrl;
	}
	public function addAdminBarNewItem( $wp_admin_bar ) {
		$mainCap = frameCfs::_()->getModule('adminmenu')->getMainCap();
		if(!current_user_can( $mainCap) || !$wp_admin_bar || !is_object($wp_admin_bar)) {
			return;
		}
		$wp_admin_bar->add_menu(array(
			'parent'    => 'new-content',
			'id'        => CFS_CODE. '-admin-bar-new-item',
			'title'     => __('Form', CFS_LANG_CODE),
			'href'      => frameCfs::_()->getModule('options')->getTabUrl( $this->getCode(). '_add_new' ),
		));
	}
	public function getFieldTypes() {
		if(empty($this->_fieldTypes)) {
			$this->_fieldTypes = dispatcherCfs::applyFilters('fieldTypes', array(
				'text' => array('label' => __('Text', CFS_LANG_CODE), 'icon' => 'fa-font'),
				'email' => array('label' => __('Email', CFS_LANG_CODE), 'icon' => 'fa-envelope-o'),
				'selectbox' => array('label' => __('Select Box', CFS_LANG_CODE), 'icon' => 'fa-list-ul'),
				'selectlist' => array('label' => __('Select List', CFS_LANG_CODE), 'icon' => 'fa-th-list'),
				'textarea' => array('label' => __('Textarea', CFS_LANG_CODE), 'icon' => 'fa-font'),
				'wptextarea' => array('label' => __('WordPress Editor', CFS_LANG_CODE), 'icon' => 'fa-wordpress', 'pro' => ''),
				'wpcategories' => array('label' => __('WordPress Categories', CFS_LANG_CODE), 'icon' => 'fa-wordpress', 'pro' => ''),
				'wooattrs' => array('label' => __('Woo Product Attribute', CFS_LANG_CODE), 'icon' => 'fa-wordpress', 'pro' => ''),
				'radiobutton' => array('label' => __('Radiobutton', CFS_LANG_CODE), 'icon' => 'fa-dot-circle-o'),
				'radiobuttons' => array('label' => __('Radiobuttons List', CFS_LANG_CODE), 'icon' => 'fa-dot-circle-o'),
				'checkbox' => array('label' => __('Checkbox', CFS_LANG_CODE), 'icon' => 'fa-check-square-o'),
				'checkboxlist' => array('label' => __('Checkbox List', CFS_LANG_CODE), 'icon' => 'fa-check-square-o'),
				'checkboxsubscribe' => array('label' => __('Subscribe Checkbox', CFS_LANG_CODE), 'icon' => 'fa-user-plus', 'pro' => ''),
				'countryList' => array('label' => __('Country List', CFS_LANG_CODE), 'icon' => 'fa-globe'),
				'countryListMultiple' => array('label' => __('Country List Multiple', CFS_LANG_CODE), 'icon' => 'fa-globe'),

				'number' => array('label' => __('Number', CFS_LANG_CODE), 'icon' => 'fa-sort-numeric-asc'),

				'date' => array('label' => __('Date', CFS_LANG_CODE), 'icon' => 'fa-calendar'),
				'month' => array('label' => __('Month', CFS_LANG_CODE), 'icon' => 'fa-calendar'),
				'week' => array('label' => __('Week', CFS_LANG_CODE), 'icon' => 'fa-calendar'),
				'time' => array('label' => __('Time', CFS_LANG_CODE), 'icon' => 'fa-clock-o'),

				'color' => array('label' => __('Color', CFS_LANG_CODE), 'icon' => 'fa-paint-brush'),
				'range' => array('label' => __('Range', CFS_LANG_CODE), 'icon' => 'fa-magic'),
				'url' => array('label' => __('URL', CFS_LANG_CODE), 'icon' => 'fa-link'),

				'file' => array('label' => __('File Upload', CFS_LANG_CODE), 'icon' => 'fa-upload', 'pro' => ''),
				'rating' => array('label' => __('Rating', CFS_LANG_CODE), 'icon' => 'fa-star', 'pro' => ''),
				'recaptcha' => array('label' => __('reCaptcha', CFS_LANG_CODE), 'icon' => 'fa-unlock-alt'),
				
				'hidden' => array('label' => __('Hidden Field', CFS_LANG_CODE), 'icon' => 'fa-eye-slash'),
				'submit' => array('label' => __('Submit Button', CFS_LANG_CODE), 'icon' => 'fa-paper-plane-o'),
				'reset' => array('label' => __('Reset Button', CFS_LANG_CODE), 'icon' => 'fa-repeat'),
				
				'htmldelim' => array('label' => __('HTML / Text Delimiter', CFS_LANG_CODE), 'icon' => 'fa-code'),
				
				'googlemap' => array('label' => __('Google Map', CFS_LANG_CODE), 'icon' => 'fa-globe'),
				'address' => array('label' => __('Address Search', CFS_LANG_CODE), 'icon' => 'fa-map-marker', 'pro' => ''),
			));
			$isPro = frameCfs::_()->getModule('supsystic_promo')->isPro();
			foreach($this->_fieldTypes as $code => $f) {
				if(isset($f['pro']) && !$isPro) {
					$this->_fieldTypes[ $code ]['pro'] = frameCfs::_()->getModule('supsystic_promo')->generateMainLink('utm_source=plugin&utm_medium=field_'. $code. '&utm_campaign=forms');
				}
			}
		}
		return $this->_fieldTypes;
	}
	public function getFieldTypeByCode( $htmlCode ) {
		$this->getFieldTypes();
		return isset( $this->_fieldTypes[ $htmlCode ] ) ? $this->_fieldTypes[ $htmlCode ] : false;
	}
	public function isFieldListSupported( $htmlCode ) {
		return $htmlCode && in_array($htmlCode, array('selectbox', 'selectlist', 'radiobuttons', 'checkboxlist'));
	}
	public function showForm($params) {
		$id = isset($params['id']) ? (int) $params['id'] : 0;
		if(!$id && isset($params[0]) && !empty($params[0])) {	// For some reason - for some cases it convert space in shortcode - to %20 im this place
			$id = explode('=', $params[0]);
			$id = isset($id[1]) ? (int) $id[1] : 0;
		}
		if($id) {
			$params['id'] = $id;
			return $this->getView()->showForm( $params );
		}
	}
	public function getAssetsforPrevStr($form) {
		$frontendStyles = $this->getView()->getFrontendStyles();
		$stylesStr = '';
		foreach($frontendStyles as $sKey => $sUrl) {
			$stylesStr .= '<link rel="stylesheet" href="'. $sUrl. '" type="text/css" media="all" />';
		}
		$stylesStr .= '<style type="text/css">
				.cfsFormPreloadImg {
					width: 1px !important;
					height: 1px !important;
					position: absolute !important;
					top: -9999px !important;
					left: -9999px !important;
					opacity: 0 !important;
				}
			</style>';
		$stylesStr = dispatcherCfs::applyFilters('assetsForPrevStr', $stylesStr, $form);
		return $stylesStr;
	}
	public function showFormSubmittedData() {
		$fid = (int) reqCfs::getVar('fid');
		$cid = (int) reqCfs::getVar('cid');
		$hash = reqCfs::getVar('hash');
		if($fid && $cid && $hash) {
			if($hash == md5(AUTH_KEY. $fid. $cid)) {
				$form = $this->getModel()->getById( $fid );
				$contact = $this->getModel('contacts')->getById( $cid );
				if($form && $contact) {
					return $this->getModel()->generateSendFormDataFull($contact['fields'], $form);
				}
			}
		}
		return '';
	}
	public function getListAvailableTerms() {
		return array('category', 'post_tag', 'products_categories', 'product_cat');
	}
	public function checkRemoveExpiredContacts() {
		$removeContacts = (int)frameCfs::_()->getModule('options')->get('remove_expire_contacts');
		if($removeContacts && $removeContacts > 0) {
			$lastCheck = (int) frameCfs::_()->getModule('options')->get('expire_contacts_last_check');
			$time = time();
			$checkFreq = 5;	// Each 5 hours
			if(!$lastCheck || (($time - $lastCheck) / (60 * 60)) > $checkFreq) {
				dbCfs::query('DELETE FROM @__contacts WHERE DATEDIFF(CURRENT_DATE, date_created) > '. $removeContacts);
				frameCfs::_()->getModule('options')->getModel()->save('expire_contacts_last_check', $time);
			}
		}
	}
}

