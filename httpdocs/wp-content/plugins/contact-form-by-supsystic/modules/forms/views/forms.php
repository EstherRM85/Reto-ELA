<?php
class formsViewCfs extends viewCfs {
	protected $_twig;
	private $_renderFormIter = 0;
	private $_lastForm = null;
	private $_saveLastForm = false;

	public function getTabContent() {
		frameCfs::_()->getModule('templates')->loadJqGrid();
		frameCfs::_()->addScript('admin.forms', $this->getModule()->getModPath(). 'js/admin.forms.js');
		frameCfs::_()->addScript('admin.forms.list', $this->getModule()->getModPath(). 'js/admin.forms.list.js');
		frameCfs::_()->addJSVar('admin.forms.list', 'cfsTblDataUrl', uriCfs::mod('forms', 'getListForTbl', array('reqType' => 'ajax')));
		
		$this->assign('addNewLink', frameCfs::_()->getModule('options')->getTabUrl('forms_add_new'));
		return parent::getContent('formsAdmin');
	}
	public function getAddNewTabContent() {
		frameCfs::_()->getModule('templates')->loadJqueryUi();
		frameCfs::_()->addStyle('admin.forms', $this->getModule()->getModPath(). 'css/admin.forms.css');
		frameCfs::_()->addScript('admin.forms', $this->getModule()->getModPath(). 'js/admin.forms.js');
		frameCfs::_()->getModule('templates')->loadMagicAnims();
		
		$changeFor = (int) reqCfs::getVar('change_for', 'get');
		//frameCfs::_()->addJSVar('admin.forms', 'cfsChangeFor', array($changeFor));
		if($changeFor) {
			$originalForm = $this->getModel()->getById( $changeFor );
			$editLink = $this->getModule()->getEditLink( $changeFor );
			$this->assign('originalForm', $originalForm);
			$this->assign('editLink', $editLink);
			frameCfs::_()->addJSVar('admin.forms', 'cfsOriginalForm', $originalForm);
			dispatcherCfs::addFilter('mainBreadcrumbs', array($this, 'modifyBreadcrumbsForChangeTpl'));
		}
		$this->assign('list', dispatcherCfs::applyFilters('showTplsList', $this->getModel()
			->setOrderBy('sort_order')
			->setSortOrder('ASC')
			->getSimpleList(array('active' => 1, 'original_id' => 0))));
		$this->assign('changeFor', $changeFor);
		
		return parent::getContent('formsAddNewAdmin');
	}
	public function modifyBreadcrumbsForChangeTpl($crumbs) {
		$crumbs[ count($crumbs) - 1 ]['label'] = __('Modify Form Template', CFS_LANG_CODE);
		return $crumbs;
	}
	public function adminBreadcrumbsClassAdd() {
		echo ' supsystic-sticky';
	}
	public function getEditTabContent($id) {
		$form = $this->getModel()->getById($id);
		if(empty($form)) {
			return __('Cannot find required Form', CFS_LANG_CODE);
		}
		dispatcherCfs::doAction('beforeFormEdit', $form);
		
		dispatcherCfs::addAction('afterAdminBreadcrumbs', array($this, 'showEditFormFormControls'));
		dispatcherCfs::addAction('adminBreadcrumbsClassAdd', array($this, 'adminBreadcrumbsClassAdd'));
		if(empty($form['ab_id'])) {
			dispatcherCfs::addFilter('mainBreadcrumbs', array($this, 'changeMainBreadCrumbsClb'));
		}
		
		// !remove this!!!!
		//$form['params']['opts_attrs']['bg_number'] = 2;
		/*$form['params']['opts_attrs'] = array(
			'bg_number' => 4,
			'txt_block_number' => 1,
		);*/
		/*$form['params']['opts_attrs']['txt_block_number'] = 0;
		$form['params']['opts_attrs']['video_width_as_forms'] = 1;
		$form['params']['opts_attrs']['video_height_as_forms'] = 1;*/
		// !remove this!!!!
		if(!is_array($form['params']))
			$form['params'] = array();
		
		frameCfs::_()->getModule('templates')->loadJqueryUi();
		frameCfs::_()->getModule('templates')->loadSortable();
		frameCfs::_()->getModule('templates')->loadCodemirror();
		frameCfs::_()->getModule('templates')->loadBootstrapPartialOnlyCss();
		//frameCfs::_()->getModule('templates')->loadSerializeJson();
		if ( ! class_exists( '_WP_Editors', false ) )
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		
		$fieldTypes = $this->getModule()->getFieldTypes();
		$cfsAddNewUrl = frameCfs::_()->getModule('options')->getTabUrl('forms_add_new');
		frameCfs::_()->addStyle('admin.forms', $this->getModule()->getModPath(). 'css/admin.forms.css');
		frameCfs::_()->addScript('admin.forms.fields', $this->getModule()->getModPath(). 'js/admin.forms.fields.js');
		frameCfs::_()->addJSVar('admin.forms.fields', 'cfsFormTypes', $fieldTypes);
		frameCfs::_()->addJSVar('admin.forms.fields', 'cfsAllCountries', fieldAdapterCfs::getCountries());
		
		frameCfs::_()->addScript('admin.forms.submit', $this->getModule()->getModPath(). 'js/admin.forms.submit.js');
		frameCfs::_()->addScript('admin.forms', $this->getModule()->getModPath(). 'js/admin.forms.js');
		frameCfs::_()->addScript('admin.forms.edit', $this->getModule()->getModPath(). 'js/admin.forms.edit.js');
		/*var_dump($form);
		exit();*/
		frameCfs::_()->addJSVar('admin.forms.edit', 'cfsForm', $form);
		frameCfs::_()->addJSVar('admin.forms.edit', 'cfsAddNewUrl', $cfsAddNewUrl);
		
		frameCfs::_()->addScript('wp.tabs', CFS_JS_PATH. 'wp.tabs.js');
		
		$bgType = array(
			'none' => __('None', CFS_LANG_CODE),
			'img' => __('Image', CFS_LANG_CODE),
			'color' => __('Color', CFS_LANG_CODE),
		);

		$this->assign('csvExportUrl', uriCfs::mod('forms', 'exportCsv', array('id' => $id)));
		
		$this->assign('adminEmail', get_bloginfo('admin_email'));
		$this->assign('isPro', frameCfs::_()->getModule('supsystic_promo')->isPro());
		$this->assign('mainLink', frameCfs::_()->getModule('supsystic_promo')->getMainLink());
		$this->assign('promoModPath', frameCfs::_()->getModule('supsystic_promo')->getAssetsUrl());

		$this->assign('cfsAddNewUrl', $cfsAddNewUrl);
		$this->assign('bgTypes', $bgType);
		$this->assign('previewUrl', uriCfs::mod('forms', 'getPreviewHtml', array('id' => $id)));
		$this->assign('form', $form);
		$this->assign('fieldTypes', $fieldTypes);

		$this->assign('bgNames', $this->getModel()->getBgNamesForForm( $form['unique_id'] ));

		$tabs = array(
			'cfsFormTpl' => array(
				'title' => __('Design', CFS_LANG_CODE), 
				'content' => $this->getMainFormTplTab(),
				'fa_icon' => 'fa-picture-o',
				'sort_order' => 0),
			'cfsFormFields' => array(
				'title' => __('Fields', CFS_LANG_CODE), 
				'content' => $this->getMainFormFieldsTab(),
				'fa_icon' => 'fa-list',
				'sort_order' => 10),
			'cfsSubmitOpts' => array(
				'title' => __('Submit Options', CFS_LANG_CODE), 
				'content' => $this->getMainFormSubmitOptsTab(),
				'fa_icon' => 'fa-envelope-o',
				'sort_order' => 20),
			'cfsFormStatistics' => array(
				'title' => __('Statistics', CFS_LANG_CODE), 
				'content' => $this->getMainFormStatisticsOptsTab(),
				'fa_icon' => 'fa-line-chart',
				'sort_order' => 100,
			),
			'cfsFormEditors' => array(
				'title' => __('CSS / HTML Code', CFS_LANG_CODE), 
				'content' => $this->getMainFormCodeTab(),
				'fa_icon' => 'fa-code',
				'sort_order' => 999),
		);
		$tabs = dispatcherCfs::applyFilters('formsEditTabs', $tabs, $form);
		uasort($tabs, array($this, 'sortEditFormTabsClb'));
		$this->assign('tabs', $tabs);
		dispatcherCfs::doAction('beforeFormEditRender', $form);
		return parent::getContent('formsEditAdmin');
	}
	public function changeMainBreadCrumbsClb($crumbs) {
		return array( $crumbs[ count($crumbs) - 1 ] );	// Get rid of all other breadcrumbs - leave space on this page for other important things (buttons, etc.)
	}
	public function showEditFormFormControls() {
		$popupSupported = false;
		if(class_exists('framePps')) {	//PopUp is supported
			$this->assign('popupSelectUrl', framePps::_()->getModule('options')->getTabUrl('popup'));
			$popupSupported = true;
		}

		$membershipPluginActive = false;
		$membershipModel = frameCfs::_()->getModule('membership')->getModel();
		if($membershipModel) {
			$membershipPluginActive = $membershipModel->isPluginActive();
		}

		$this->assign('pluginInstallUrl', add_query_arg( array( 's' => 'Membership by Supsystic', 'tab' => 'search', 'type' => 'term', ), admin_url( 'plugin-install.php' )));
		$this->assign('membershipPluginActive', $membershipPluginActive);
		$this->assign('popupSupported', $popupSupported);
		parent::display('formsEditFormControls');
	}
	public function sortEditFormTabsClb($a, $b) {
		if($a['sort_order'] > $b['sort_order'])
			return 1;
		if($a['sort_order'] < $b['sort_order'])
			return -1;
		return 0;
	}
	public function getMainFormSubmitOptsTab() {
		global $wpdb;
		$postTypes = get_post_types('', 'objects');
		$postTypesForSelect = array();
		foreach($postTypes as $key => $value) {
			if(!in_array($key, array('attachment', 'revision', 'nav_menu_item'))) {
				$postTypesForSelect[$key] = $value->labels->name;
			}
		}
		// We are not using wp methods here - as list can be very large - and it can take too much memory
		$postTypesForCategoriesList = $this->getModule()->getListAvailableTerms();
		$allCategories = dbCfs::get("SELECT t.term_id, t.name FROM $wpdb->terms t "
			. "INNER JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id "
			. "WHERE tt.taxonomy IN ('". implode("','", $postTypesForCategoriesList). "') ORDER BY t.name");
		$allCategoriesForSelect = array();
		if(!empty($allCategories)) {
			foreach($allCategories as $c) {
				$allCategoriesForSelect[ $c['term_id'] ] = $c['name'];
			}
		}
		$this->assign('postTypesForSelect', $postTypesForSelect);
		$this->assign('postStatusesForSelect', get_post_statuses());
		$this->assign('allCategoriesForSelect', $allCategoriesForSelect);
		$this->assign('allContactsListUrl', frameCfs::_()->getModule('options')->getTabUrl( $this->getCode(). '_contacts', array('id' => $this->form['id']) ));
		$this->assign('regRolesForSelect', $this->getAvailableUserRolesForSelect());
		$this->assign('submitOptsAddTabs', array(
			'cfsFormSubmitEditTabPublish' => array(
				'title' => __('Publish Content', CFS_LANG_CODE), 
				'content' => $this->_getMainFormPublishContentTab(),
				'fa_icon' => 'fa-thumb-tack',
				'sort_order' => 0,
			),
			
			'cfsFormSubmitEditTabRegistration' => array(
				'title' => __('Registration', CFS_LANG_CODE), 
				'content' => $this->_getMainFormRegistrationTab(),
				'fa_icon' => 'fa-user-plus',
				'sort_order' => 10,
			),
		));
		return parent::getContent('formsEditFormSubmitOpts');
	}
	public function getAvailableUserRolesForSelect() {
		global $wp_roles;
		$res = array();
		$allRoles = $wp_roles->roles;
		$editableRoles = apply_filters('editable_roles', $allRoles);
		
		if(!empty($editableRoles)) {
			foreach($editableRoles as $role => $data) {
				if(in_array($role, array('administrator', 'editor'))) continue;
				if($role == 'subscriber') {	// Subscriber - at the begining of array
					$res = array($role => $data['name']) + $res;
				} else {
					$res[ $role ] = $data['name'];
				}
			}
		}
		return $res;
	}
	private function _getMainFormPublishContentTab() {
		return parent::getContent('formsEditAdminPublishContentOpts');
	}
	private function _getMainFormRegistrationTab() {
		return parent::getContent('formsEditAdminRegistrationOpts');
	}
	public function getMainFormTplTab() {
		return parent::getContent('formsEditAdminTplOpts');
	}
	public function getMainFormFieldsTab() {
		frameCfs::_()->getModule('templates')->loadBxSlider();
		$isGoogleMapsAvailable = class_exists('frameGmp');
		$mapId = reqCfs::getVar('map_id', 'get');
		if($isGoogleMapsAvailable) {
			$allGoogleMaps = frameGmp::_()->getModule('gmap')->getModel()->getAllMaps(array('simple' => true));
			$allGoogleMapsForSelect = array();
			if(!empty($allGoogleMaps)) {
				foreach($allGoogleMaps as $m) {
					$allGoogleMapsForSelect[ $m['id'] ] = $m['title'];
				} 
			}
			$this->assign('allGoogleMapsForSelect', $allGoogleMapsForSelect);
		}
		$this->assign('isGoogleMapsAvailable', $isGoogleMapsAvailable);
		frameCfs::_()->addJSVar('admin.forms.fields', 'cfsAddMapField', $mapId);
		return parent::getContent('formsEditFormFields');
	}
	public function getMainFormStatisticsOptsTab() {
		frameCfs::_()->addScript('google.charts', 'https://www.gstatic.com/charts/loader.js');
		frameCfs::_()->getModule('templates')->loadJqGrid();
		frameCfs::_()->getModule('templates')->loadDatePicker();
		frameCfs::_()->addScript('admin.forms.statistics', $this->getModule()->getModPath(). 'js/admin.forms.statistics.js');
		// Total stats
		$group = reqCfs::getVar('cfsChartGroup_cfsMainStats', 'cookie');
		// Get stats only for default engine for now
		$statModel = frameCfs::_()->getModule('statistics')->getModel();
		$stats = $statModel->getAllForFormSorted($this->form['id'], array('group' => $group));
		if(!empty($stats)) {
			frameCfs::_()->addJSVar('admin.forms.statistics', 'cfsAllStats', $stats);
		}
		if($this->isPro) {
			if(frameCfs::_()->getModule('add_fields') && method_exists(frameCfs::_()->getModule('add_fields'), 'getRatingStatsTab')) {
				$this->assign('ratingStats', frameCfs::_()->getModule('add_fields')->getRatingStatsTab( $this->form ));
			}
		}
		return parent::getContent('formsEditFormStatistics');
	}
	public function getFrontendStyles() {
		return array(
			CFS_CODE. '.frontend.bootstrap.partial' => frameCfs::_()->getModule('forms')->getAssetsUrl(). 'css/frontend.bootstrap.partial.min.css',
			CFS_CODE. '.frontend.forms' => $this->getModule()->getModPath(). 'css/frontend.forms.css',
		);
	}
	public function showForm($params) {
		frameCfs::_()->getModule('templates')->loadCoreJs();
		$id = $params['id'];
		
		$id = dispatcherCfs::applyFilters('formIdBeforeShow', $id);
		
		$form = $this->getModel()->getById( $id );
		if(!empty($form)) {
			dispatcherCfs::doAction('formBeforeShow', $form);
			$form['connect_hash'] = md5(date('m-d-Y'). $id. NONCE_KEY);
			if(!empty($_GET['cfsPreFill']) && !empty($form['params']['fields'])) {
				foreach($form['params']['fields'] as &$field) {
					$fieldValue = isset($_GET['cfs_'.$field['name']]) ? $_GET['cfs_'.$field['name']] : (isset($_GET[$field['name']]) ? $_GET[$field['name']] : false);
					if(isset($field['value']) && isset($field['name']) && $fieldValue !== false) {
						$field['value'] = $fieldValue;
					}
				}
			}
			$frontendStyles = $this->getFrontendStyles();
			foreach($frontendStyles as $sKey => $sUrl) {
				frameCfs::_()->addStyle($sKey, $sUrl);
			}
			frameCfs::_()->addScript(CFS_CODE. '.modernizr', $this->getModule()->getModPath(). 'js/forms.modernizr.min.js');
			frameCfs::_()->addScript(CFS_CODE. '.frontend.forms', $this->getModule()->getModPath(). 'js/frontend.forms.js');
			
			//frameCfs::_()->addJSVar(CFS_CODE. '.frontend.forms', 'cfsForms_'. $this->_renderFormIter, $this->_prepareForFront($form));
			//frameCfs::_()->addJSVar(CFS_CODE. '.frontend.forms', 'cfsFormsRenderFormIter', array('lastIter' => $this->_renderFormIter));
			$this->_renderFormIter++;
			$this->_checkLoadFieldsAssets( $form['params']['fields'] );
			if($this->_saveLastForm) {
				$this->_lastForm = $form;
			}
			return $this->generateHtml( $form, array('frontend' => true) )
				. '<span style="display: none !important;" class="cfsFormDesc">'. base64_encode(json_encode($this->_prepareForFront($form))). '</span>';
		}
		return 'Can not find Form in database';
	}
	public function getLastForm() {
		return $this->_lastForm;
	}
	public function saveLastForm( $val ) {
		$this->_saveLastForm = $val;
	}
	/**
	 * Exclude unvanted for frontend data from form
	 * @param array $form Form data to be rendered
	 * @return array Form data without parameters for frontend
	 */
	private function _prepareForFront( $form ) {
		unset($form['css']);
		unset($form['html']);
		unset($form['params']['submit']);
		if(isset($form['params']['fields'])) {
			foreach($form['params']['fields'] as $i => $f) {
				if($f['html'] == 'recaptcha') {
					unset($form['params']['fields'][ $i ]['recap-sitekey']);
					unset($form['params']['fields'][ $i ]['recap-secret']);
				}
			}
		}
		$removeParamsKeys = array('sub_aweber_listname', 'sub_aweber_adtracking', 'sub_mailchimp_api_key', 'sub_mailchimp_lists', 'sub_ar_form_action',
			'sub_sga_id', 'sub_sga_list_id', 'sub_sga_activate_code', 'sub_gr_api_key', 'sub_ac_api_url', 'sub_ac_api_key', 
			'sub_ac_lists', 'sub_mr_lists', 'sub_gr_api_key', 'sub_gr_lists', 'cycle_day', 'sub_ic_app_id', 'sub_ic_app_user', 'sub_ic_app_pass', 'sub_ic_lists',
			'sub_ck_api_key', 'sub_mem_acc_id', 'sub_mem_pud_key', 'sub_mem_priv_key', 'test_email', 'sub_sb_api_key',
			'sub_aw_c_key', 'sub_aw_c_secret');
		foreach($removeParamsKeys as $unKey) {
			if(isset($form[ $i ]['params']['tpl'][ $unKey ]))
				unset($form[ $i ]['params']['tpl'][ $unKey ]);
		}
		return $form;
	}
	private function _checkLoadFieldsAssets( $fields ) {
		foreach($fields as $f) {
			switch( $f['html'] ) {
				case 'date': case 'month': case 'week':
					frameCfs::_()->getModule('templates')->loadDatePicker();
					frameCfs::_()->getModule('templates')->loadJqueryUi();
					frameCfs::_()->getModule('templates')->loadFontAwesome();
					break;
				case 'time':
					frameCfs::_()->getModule('templates')->loadTimePicker();
					break;
			}
		}
	}
	public function getMainFormCodeTab() {
		return parent::getContent('formsEditAdminCodeOpts');
	}
	public function adjustOpacity($color, $alpha) {
		$alpha = max(0, min(1, $alpha));
		$rgbColor = utilsCfs::hexToRgb( $color );
		$rgbColor[] = $alpha;
		return 'rgba('. implode(',', $rgbColor). ')';
	}
	public function adjustBrightness($hex, $steps) {
		 // Steps should be between -255 and 255. Negative = darker, positive = lighter
		$steps = max(-255, min(255, $steps));

		// Normalize into a six character long hex string
		$hex = str_replace('#', '', $hex);
		if (strlen($hex) == 3) {
			$hex = str_repeat(substr($hex, 0, 1), 2). str_repeat(substr($hex, 1, 1), 2). str_repeat(substr($hex, 2, 1), 2);
		}

		// Split into three parts: R, G and B
		$color_parts = str_split($hex, 2);
		$return = '#';

		foreach ($color_parts as $color) {
			$color   = hexdec($color); // Convert to decimal
			$color   = max(0, min(255, $color + $steps)); // Adjust color
			$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
		}

		return $return;
	}
	public function generateHtml($form, $params = array()) {
		$replaceStyleTag = isset($params['replace_style_tag']) ? $params['replace_style_tag'] : false;
		if(is_numeric($form)) {
			$form = $this->getModel()->getById($form);
		}
		$this->_initTwig();
		
		$form = dispatcherCfs::applyFilters('beforeFormRender', $form);

		$form['params']['tpl']['form_start'] = $this->generateFormStart( $form );
		$form['params']['tpl']['fields'] = $this->generateFields( $form );
		$form['params']['tpl']['form_end'] = $this->generateFormEnd( $form );
			
		$form['css'] .= $this->_generateCommonFormCss( $form );
		
		$form['css'] = $this->_replaceTagsWithTwig( $form['css'], $form );
		$form['html'] = $this->_replaceTagsWithTwig( $form['html'], $form );
		
		$form['html'] .= $this->_generateImgsPreload( $form );
		
		$form['css'] = dispatcherCfs::applyFilters('formCss', $form['css'], $form, $params);
		$form['html'] = dispatcherCfs::applyFilters('formHtml', $form['html'], $form, $params);
		// $replaceStyleTag can be used for compability with other plugins minify functionality: 
		// it will not recognize css in js data as style whye rendering on server side, 
		// but will be replaced back to normal <style> tag in JS, @see js/frontend.forms.js
		return $this->_twig->render(
				($replaceStyleTag ? '<span style="display: none;" id="cfsFormStylesHidden_'. $form['view_id']. '">' : '<style type="text/css" id="'. $form['view_html_id']. '_style">')
					. $form['css']
				. ($replaceStyleTag ? '</span>' : '</style>')
				. '<div id="'. $form['view_html_id']. '" class="cfsFormShell">'. $form['html']. '</div>',
			array('forms' => $form)
		);
	}
	private function _generateCommonFormCss( $form ) {
		$res = '';
		$res .= '#[SHELL_ID] { width: '. $form['params']['tpl']['width']. $form['params']['tpl']['width_measure']. '}';
		if(isset($form['params'], $form['params']['tpl'], $form['params']['tpl']['form_sent_msg_color'])
			&& !empty($form['params']['tpl']['form_sent_msg_color']) 
		) {
			$res .= '#[SHELL_ID] .cfsSuccessMsg { color: '. $form['params']['tpl']['form_sent_msg_color']. ' !important}';
		}
		return $res;
	}
	public function generateFormStart( $form ) {
		return '<form class="csfForm" method="post" action="'. CFS_SITE_URL. '">';
	}
	public function generateFormEnd( $form ) {
		$res = '';
		$res .= htmlCfs::hidden('mod', array('value' => 'forms'));
		$res .= htmlCfs::hidden('action', array('value' => 'contact'));
		$res .= htmlCfs::hidden('id', array('value' => $form['id']));
		$res .= htmlCfs::hidden('_wpnonce', array('value' => wp_create_nonce('contact-'. $form['id'])));
		$res .= '<div class="cfsContactMsg"></div>';
		$res .= '</form>';
		return $res;
	}
	/*private function _generateFieldClasses( $field ) {
		return '';
	}
	private function _generateFieldStyles( $field ) {
		return '';
	}*/
	public function generateFields( $form ) {
		$resHtml = '';
		if(isset($form['params']['fields']) && !empty($form['params']['fields'])) {
			$mod = $this->getModule();
			$fieldWrapper = $form['params']['tpl']['field_wrapper'];
			if(strpos($fieldWrapper, '[field]') === false) {
				$fieldWrapper = '[field]';
			}
			$fieldCommonClasses = 'cfsFieldShell';
			$rows = array();
			$addFieldsMod = frameCfs::_()->getModule('add_fields');	// it can be NULL!!!
			$htmlDelims = $googeMaps = 0;
			foreach($form['params']['fields'] as $f) {
				$htmlType = $f['html'];
				$name = isset($f['name']) ? trim($f['name']) : '';
				$isHtmlDelim = $htmlType == 'htmldelim';
				$isGoogleMap = $htmlType == 'googlemap';
				$isHidden = $htmlType == 'hidden';
				if(empty($name) && !$isHtmlDelim && !$isGoogleMap) continue;
				$id = '';
				$insertLabelInternal = false;
				if($isHtmlDelim) {
					$htmlDelims++;
					$inputHtml = $f['value'];
					$name = 'htmldelim_'. $htmlDelims;
				} elseif($isGoogleMap) {
					$googeMaps++;
					$inputHtml = '';
					$name = 'googlemap_'. $googeMaps;
					if(class_exists('frameGmp')) {
						$mapId = (int) $f['value'];
						if($mapId) {
							$inputHtml = frameGmp::_()->getModule('gmap')->drawMapFromShortcode(array('id' => $mapId));
						}
					} elseif(frameCfs::_()->getModule('user')->isAdmin()) {	// Show errors - only if user is admin, for usual visitors there will be just no field
						$inputHtml = sprintf(__('To use this field type you need to have installed and activated <a href="%s" target="_blank">Google Maps Easy</a> plugin - it\'s Free! Just install it <a class="button" target="_blank" href="%s">here.</a>', CFS_LANG_CODE), 'https://wordpress.org/plugins/google-maps-easy/', admin_url('plugin-install.php?tab=search&s=Google+Maps+Easy'));
					}
				} else {
					$htmlParams = array('attrs' => 'data-name="'. $name. '"');
					$label = $f['label'];
					$placeholder = isset($f['placeholder']) ? $f['placeholder'] : '';
					$fieldListSupported = $mod->isFieldListSupported($htmlType);
					// Additional html types, that does not have options selection, but will be displayed in same way as other lists
					$showAsList = $fieldListSupported || in_array($htmlType, array('countryList', 'countryListMultiple', 'wooattrs', 'wpcategories'));
					$showAsOneCheck = in_array($htmlType, array('checkbox', 'radiobutton', 'checkboxsubscribe'));
					$isButton = in_array($htmlType, array('submit', 'reset', 'button'));
					$isRadioCheckList = in_array($htmlType, array('radiobuttons', 'checkboxlist'));
					if($fieldListSupported && isset($f['options']) && !empty($f['options'])) {
						$htmlParams['options'] = array();
						foreach($f['options'] as $opt) {
							$htmlParams['options'][ $opt['name'] ] = isset($opt['label']) ? $opt['label'] : $opt['name'];
						}
					}
					if(!empty($placeholder)) {
						$htmlParams['placeholder'] = $placeholder;
					}
					if($isButton) {
						$f['value'] = $label;	// To not confuse user
					}
					if(isset($f['value']) && (!empty($f['value']) || strlen($f['value']) > 0)) {
						if(isset($f['value_preset'])
							&& $f['value_preset']
							&& $addFieldsMod
							&& method_exists($addFieldsMod, 'generateValuePreset')
						) {
							$f['value'] = $addFieldsMod->generateValuePreset( $f['value_preset'] );
						}
						$htmlParams['value'] = $f['value'];
					}
					if(isset($f['mandatory']) && !empty($f['mandatory']) && (int)$f['mandatory']) {
						$htmlParams['required'] = true;
					}
					if(in_array($htmlType, array('checkbox'))) {
						$htmlParams['attrs'] .= ' style="height: auto; width: auto; margin: 0; padding: 0;"';
					}
					if(isset($f['display']) && !empty($f['display'])) {
						$htmlParams['display'] = $f['display'];
					}
					if(isset($f['min_size']) && !empty($f['min_size'])) {
						$htmlParams['min'] = $f['min_size'];
					}
					if(isset($f['max_size']) && !empty($f['max_size'])) {
						$htmlParams['max'] = $f['max_size'];
					}
					if(isset($f['add_classes']) && !empty($f['add_classes'])) {
						$htmlParams['attrs'] .= ' class="'. $f['add_classes']. '"';
					}
					if(isset($f['add_styles']) && !empty($f['add_styles'])) {
						$htmlParams['attrs'] .= ' style="'. $f['add_styles']. '"';
					}
					if(isset($f['add_attr']) && !empty($f['add_attr'])) {
						$htmlParams['attrs'] .= ' '. $f['add_attr'];
					}
					if(isset($f['vn_pattern']) && !empty($f['vn_pattern'])) {
						$htmlParams['pattern'] = $f['vn_pattern'];
					}
					if(isset($f['vn_equal']) && !empty($f['vn_equal'])) {
						$htmlParams['attrs'] .= ' data-equal-to="'. $f['vn_equal']. '"';
					}
					if(isset($f['def_checked']) && !empty($f['def_checked'])) {
						$htmlParams['checked'] = 1;
					}
					if($htmlType == 'recaptcha') {
						foreach($f as $fParamKey => $fParam) {
							if(strpos($fParamKey, 'recap-') === 0 &&  strpos($fParamKey, 'secret') === false) {
								$htmlParams[ str_replace('recap-', '', $fParamKey) ] = $fParam;
							}
						}
					}
					
					// $isRadioCheckList fields have multiple labeled selections inside - them simply does not need main label selection
					// For all other - generate unique ID if it's required - here
					if(strpos($fieldWrapper, '[field_id]') !== false && !$isRadioCheckList) {	// Need field ID
						$id = htmlCfs::nameToClassId($name. mt_rand(1, 99999), $htmlParams);
						if(strpos($htmlParams['attrs'], $id) === false) {
							$htmlParams['attrs'] .= ' id="'. $id. '"';
						}
					}
					$fullName = 'fields['. $name. ']';
					$fieldTypeData = $this->getModule()->getFieldTypeByCode( $htmlType );
					// Really rare situation - most for developing
					if(empty($fieldTypeData)) continue;
					// Generate input field itself
					if($fieldTypeData && isset($fieldTypeData['pro'])) {
						if(!$addFieldsMod) continue;
						$inputHtml = $addFieldsMod->generateFieldHtml($htmlType, $fullName, $htmlParams, $form, $f);
					} else {
						$inputHtml = htmlCfs::$htmlType($fullName, $htmlParams);
					}
					$inputHtml = dispatcherCfs::applyFilters('formInputHtml', $inputHtml, $f, $htmlParams);
					// Generate it's html shell wrapper
					if(!$isHidden) {
						$insertLabelInternal = strpos($fieldWrapper, '[label]') === false;
						if($showAsList) {
							$labelDelimiter = isset($f['label_delim']) ? $f['label_delim'] : ':';
							$txtLabel = $insertLabelInternal ? '<span class="cfsListSelectLabel">'. $label. $labelDelimiter. ' </span>' : '';
							$inputHtml = '<label>'. $txtLabel. '<span class="cfsListSelect">'. $inputHtml. '</span></label>';
						} elseif($showAsOneCheck) {
							$txtLabel = $insertLabelInternal ? '&nbsp;'. $label : '';
							$inputHtml = '<label class="cfsCheck">'. $inputHtml. $txtLabel. '</label>';
						} elseif(!$isButton && !empty($label)) {
							$txtLabel = $insertLabelInternal ? '<span class="cfsInputLabel">'. $label. '&nbsp;</span>' : '';
							$inputHtml = '<label>'. $txtLabel. $inputHtml. '</label>';
						}
						// Wrap it
						$classes = array($fieldCommonClasses, 'cfsField_'. $htmlType);
						$replaceFrom = array('[field]', '[field_shell_classes]', '[field_shell_styles]', '[field_html]', '[field_id]');
						$replaceTo = array($inputHtml, 'class="'. implode(' ', $classes). '"', '', $htmlType, $id);
						if(!$insertLabelInternal) {
							$replaceFrom[] = '[label]';
							$replaceTo[] = $isButton ? '' : $label;
						}

						$inputHtml = str_replace(
							$replaceFrom, 
							$replaceTo, 
							$fieldWrapper);
					}
				}
				$bsClassId = isset($f['bs_class_id']) && !empty($f['bs_class_id']) ? (int) $f['bs_class_id'] : 12;
				$added = false;
				if(!$isHidden) {
					$inputHtml = '<div class="col-sm-'. $bsClassId. ' cfsFieldCol" data-name="'. $name. '" data-type="'. $htmlType. '">'. $inputHtml. '</div>';	// Bootstrap col wrapper
					if($bsClassId < 12) {	// Try to add it to prev. row
						$prevRowI = count( $rows ) - 1;
						if($prevRowI >= 0) {
							if($rows[ $prevRowI ]['id'] < 12) {
								$rows[ $prevRowI ]['id'] += $bsClassId;
								$rows[ $prevRowI ]['cols'][] = $inputHtml;
								$added = true;
							}
						}
					}
				}
				if(!$added) {	// New row
					$rows[] = array('id' => $bsClassId, 'cols' => array( $inputHtml ), 'is_hidden' => $isHidden);
				}
			}
			foreach($rows as $r) {
				$cols = implode('', $r['cols']);
				if(isset($r['is_hidden']) && $r['is_hidden']) {	// No wrappers for hidden fields
					$resHtml .= $cols;
				} else {
					$resHtml .= '<div class="row cfsFieldsRow">'. $cols. '</div>';
				}
			}
		}
		return $resHtml;
	}
	private function _generateImgsPreload( $form ) {
		$res = '';
		if(isset($form['params']['opts_attrs']['bg_number']) && !empty($form['params']['opts_attrs']['bg_number'])) {
			for($i = 0; $i < $form['params']['opts_attrs']['bg_number']; $i++) {
				if($form['params']['tpl']['bg_type_'. $i] == 'img' && !empty($form['params']['tpl']['bg_img_'. $i])) {
					$res .= '<img class="cfsFormPreloadImg cfsFormPreloadImg_'. $form['view_id']. '" src="'. $form['params']['tpl']['bg_img_'. $i]. '" />';
				}
			}
		}
		return $res;
	}
	private function _replaceTagsWithTwig($string, $form) {
		$string = preg_replace('/\[if (.+)\]/iU', '{% if forms.params.tpl.$1 %}', $string);
		$string = preg_replace('/\[elseif (.+)\]/iU', '{% elseif forms.params.tpl.$1 %}', $string);
		
		$replaceFrom = array('SHELL_ID', 'ID', 'endif', 'else');
		$replaceTo = array($form['view_html_id'], $form['view_id'], '{% endif %}', '{% else %}');
		// Standard shortcode processor didn't worked for us here - as it is developed for posts, 
		// not for direct "do_shortcode" call, so we created own embed shortcode processor
		if(isset($form['params']) && isset($form['params']['tpl'])) {
			foreach($form['params']['tpl'] as $key => $val) {
				if(is_array($val)) {
					foreach($val as $key2 => $val2) {
						if(is_array($val2)) {
							foreach($val2 as $key3 => $val3) {
								// Here should be some recursive and not 3 circles, but have not time for this right now, maybe you will do this?:)
								if(is_array($val3)) continue;
								$replaceFrom[] = $key. '_'. $key2. '_'. $key3;
								$replaceTo[] = $val3;
							}
						} else {
							$replaceFrom[] = $key. '_'. $key2;
							$replaceTo[] = $val2;
						}
					}
				} else {
					// Do shortcodes for all text type data in forms
					if(strpos($key, 'txt_') === 0 || strpos($key, 'label') === 0 || strpos($key, 'foot_note')) {
						$val = do_shortcode( $val );
					}
					$replaceFrom[] = $key;
					$replaceTo[] = $val;
				}
			}
		}
		foreach($replaceFrom as $i => $v) {
			$replaceFrom[ $i ] = '['. $v. ']';
		}
		return str_replace($replaceFrom, $replaceTo, $string);
	}
	protected function _initTwig() {
		if(!$this->_twig) {
			if(!class_exists('Twig_Autoloader')) {
				require_once(CFS_CLASSES_DIR. 'Twig'. DS. 'Autoloader.php');
			}
			Twig_Autoloader::register();
			$this->_twig = new Twig_Environment(new Twig_Loader_String(), array('debug' => 0));
			$this->_twig->addFunction(
				new Twig_SimpleFunction('adjust_brightness', array(
						$this,
						'adjustBrightness'
					)
				)
			);
			$this->_twig->addFunction(
				new Twig_SimpleFunction('adjust_opacity', array(
						$this,
						'adjustOpacity'
					)
				)
			);
			$this->_twig->addFunction(
				new Twig_SimpleFunction('hex_to_rgba_str', 'utilsCfs::hexToRgbaStr')
			);
		}
	}
	public function getContactsTabContent( $fid = 0 ) {
		frameCfs::_()->getModule('templates')->loadJqGrid();
		frameCfs::_()->getModule('templates')->loadBootstrapPartialOnlyCss();
		frameCfs::_()->addScript('admin.forms', $this->getModule()->getModPath(). 'js/admin.forms.js');
		frameCfs::_()->addScript('admin.forms.contacts.list', $this->getModule()->getModPath(). 'js/admin.forms.contacts.list.js');
		frameCfs::_()->addJSVar('admin.forms.contacts.list', 'cfsTblDataUrl', uriCfs::mod('forms', 'getContactsListForTbl', array('reqType' => 'ajax', 'fid' => $fid)));
		
		$allForms = $this->getModel()->getFromTbl();
		$allFormsForSelect = array();
		$formsFields = array();
		if(!empty($allForms)) {
			$allFormsForSelect[ 0 ] = __('All', CFS_LANG_CODE);
			foreach($allForms as $form) {
				$allFormsForSelect[ $form['id'] ] = $form['label'];
				if(isset($form['params']['fields']) && !empty($form['params']['fields'])) {
					if(count( $formsFields ) < 5) {	// More fields will look like very unpreatty
						foreach($form['params']['fields'] as $f) {
							$htmlType = $f['html'];
							if(in_array($htmlType, array('submit', 'reset', 'button', 'recaptcha', 'htmldelim',
								'googlemap', 'textarea', 'selectlist', 'radiobutton', 'radiobuttons', 'checkbox',
								'checkboxlist', 'checkboxsubscribe', 'countryList', 'countryListMultiple'))) continue;
							if(in_array($htmlType, array('text', 'textarea'))) continue;
							$sendName = $f['name'];
							$html = $f['html'];
							if(empty($sendName)) continue;
							$formsFields[ $sendName ] = array('label' => $f['label'] ? $f['label'] : $f['placeholder'], 'html' => $html);
						}
					}
				}
			}
		}
		frameCfs::_()->addJSVar('admin.forms.contacts.list', 'cfsFormFields', $formsFields);
		
		$this->assign('addNewLink', frameCfs::_()->getModule('options')->getTabUrl('forms_add_new'));
		$this->assign('allFormsForSelect', $allFormsForSelect);
		$this->assign('fid', $fid);
		return parent::getContent('formsContacts');
	}
}
