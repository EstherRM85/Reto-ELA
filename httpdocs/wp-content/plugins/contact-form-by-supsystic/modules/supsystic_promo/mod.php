<?php
class supsystic_promoCfs extends moduleCfs {
	private $_mainLink = '';
	private $_minDataInStatToSend = 20;	// At least 20 points in table shuld be present before send stats
	private $_assetsUrl = '';
	public function __construct($d) {
		parent::__construct($d);
		$this->getMainLink();
		dispatcherCfs::addFilter('jsInitVariables', array($this, 'addMainOpts'));
	}
	public function init() {
		parent::init();
		add_action('admin_footer', array($this, 'displayAdminFooter'), 9);
		if(is_admin()) {
			add_action('init', array($this, 'checkWelcome'));
			add_action('init', array($this, 'checkStatisticStatus'));
		}
		$this->weLoveYou();
		dispatcherCfs::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		dispatcherCfs::addAction('beforeSaveOpts', array($this, 'checkSaveOpts'));
		dispatcherCfs::addFilter('showTplsList', array($this, 'checkProTpls'));
		dispatcherCfs::addAction('discountMsg', array($this, 'getDiscountMsg'));
		add_action('admin_notices', array($this, 'checkAdminPromoNotices'));
		// Admin tutorial
		add_action('admin_enqueue_scripts', array( $this, 'loadTutorial'));
		dispatcherCfs::addFilter('formsEditTabs', array($this, 'addEditTab'));
	}
	public function addEditTab( $tabs ) {
		if(!$this->isPro()) {
			$tabs['cfsFormConditionalLogic'] = array(
				'title' => __('Conditional Logic', CFS_LANG_CODE),
				'content' => '
				<label>
				Conditional Logic <i title="A feature allows to show certain fields, depend on the value of some other fields. It provides a set of rules that apply to fields that dynamically change the form layout. It’s a great way to make complex forms more compact, and present the users with only the information they are interested in. <a href=\'https://supsystic.com/documentation/contact-form-publish-content/\' target=\'_blank\'>https://supsystic.com/documentation/contact-form-publish-content/</a>" class="fa fa-question supsystic-tooltip tooltipstered"></i>
				</label>
				<br>
				<a style="margin-top:15px;" href="'. $this->generateMainLink('utm_source=plugin&utm_medium=conditional_logic&utm_campaign=forms'). '" target="_blank"><img style="max-width:800px; width: 100%; height: auto;" src="'. frameCfs::_()->getModule('templates')->getCdnUrl(). '_assets/contact-form/img/supsystic_promo/logic.gif" /></a>',
				'fa_icon' => 'fa-flask',
				'sort_order' => 90,
			);
		}
		return $tabs;
	}
	public function checkAdminPromoNotices() {
		if(!frameCfs::_()->isAdminPlugOptsPage())	// Our notices - only for our plugin pages for now
			return;
		$notices = array();
		// Start usage
		$startUsage = (int) frameCfs::_()->getModule('options')->get('start_usage');
		$currTime = time();
		$day = 24 * 3600;
		if($startUsage) {	// Already saved
			$rateMsg = sprintf(__("<h3>Hey, I noticed you just use %s over a week – that’s awesome!</h3><p>Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.</p>", CFS_LANG_CODE), CFS_WP_PLUGIN_NAME);
			$rateMsg .= '<p><a href="https://wordpress.org/support/view/plugin-reviews/contact-form-by-supsystic?rate=5#postform" target="_blank" class="button button-primary" data-statistic-code="done">'. __('Ok, you deserve it', CFS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="later">'. __('Nope, maybe later', CFS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="hide">'. __('I already did', CFS_LANG_CODE). '</a></p>';
			$enbPromoLinkMsg = sprintf(__("<h3>More then eleven days with our %s plugin - Congratulations!</h3>", CFS_LANG_CODE), CFS_WP_PLUGIN_NAME);;
			$enbPromoLinkMsg .= __('<p>On behalf of the entire <a href="https://supsystic.com/" target="_blank">supsystic.com</a> company I would like to thank you for been with us, and I really hope that our software helped you.</p>', CFS_LANG_CODE);
			$enbPromoLinkMsg .= __('<p>And today, if you want, - you can help us. This is really simple - you can just add small promo link to our site under your Forms. This is small step for you, but a big help for us! Sure, if you don\'t want - just skip this and continue enjoy our software!</p>', CFS_LANG_CODE);
			$enbPromoLinkMsg .= '<p><a href="#" class="button button-primary" data-statistic-code="done">'. __('Ok, you deserve it', CFS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="later">'. __('Nope, maybe later', CFS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="hide">'. __('Skip', CFS_LANG_CODE). '</a></p>';
			$checkOtherPlugins = '<p>'
				. sprintf(__('Check out <a href="%s" target="_blank" class="button button-primary" data-statistic-code="hide">our other Plugins</a>! Years of experience in WordPress plugins developers made those list unbreakable!', CFS_LANG_CODE), frameCfs::_()->getModule('options')->getTabUrl('featured-plugins'))
			. '</p>';
			$notices = array(
				'rate_msg' => array('html' => $rateMsg, 'show_after' => 7 * $day),
				'enb_promo_link_msg' => array('html' => $enbPromoLinkMsg, 'show_after' => 11 * $day),
				'check_other_plugs_msg' => array('html' => $checkOtherPlugins, 'show_after' => 1 * $day),
			);
			foreach($notices as $nKey => $n) {
				if($currTime - $startUsage <= $n['show_after']) {
					unset($notices[ $nKey ]);
					continue;
				}
				$done = (int) frameCfs::_()->getModule('options')->get('done_'. $nKey);
				if($done) {
					unset($notices[ $nKey ]);
					continue;
				}
				$hide = (int) frameCfs::_()->getModule('options')->get('hide_'. $nKey);
				if($hide) {
					unset($notices[ $nKey ]);
					continue;
				}
				$later = (int) frameCfs::_()->getModule('options')->get('later_'. $nKey);
				if($later && ($currTime - $later) <= 2 * $day) {	// remember each 2 days
					unset($notices[ $nKey ]);
					continue;
				}
				if($nKey == 'enb_promo_link_msg' && (int)frameCfs::_()->getModule('options')->get('add_love_link')) {
					unset($notices[ $nKey ]);
					continue;
				}
			}
		} else {
			frameCfs::_()->getModule('options')->getModel()->save('start_usage', $currTime);
		}
		if(!empty($notices)) {
			if(isset($notices['rate_msg']) && isset($notices['enb_promo_link_msg']) && !empty($notices['enb_promo_link_msg'])) {
				unset($notices['rate_msg']);	// Show only one from those messages
			}
			$html = '';
			foreach($notices as $nKey => $n) {
				$this->getModel()->saveUsageStat($nKey. '.'. 'show', true);
				$html .= '<div class="updated notice is-dismissible supsystic-admin-notice" data-code="'. $nKey. '">'. $n['html']. '</div>';
			}
			echo $html;
		}
	}
	public function addAdminTab($tabs) {
		$tabs['overview'] = array(
			'label' => __('Overview', CFS_LANG_CODE), 'callback' => array($this, 'getOverviewTabContent'), 'fa_icon' => 'fa-info', 'sort_order' => 5,
		);
		$tabs['featured-plugins'] = array(
			'label' => __('Featured Plugins', CFS_LANG_CODE), 'callback' => array($this, 'showFeaturedPluginsPage'), 'fa_icon' => 'fa-heart', 'sort_order' => 99,
		);
		return $tabs;
	}
	public function addSubDestList($subDestList) {
		if(!$this->isPro()) {
			$subDestList = array_merge($subDestList, array(
				'constantcontact' => array('label' => __('Constant Contact - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'campaignmonitor' => array('label' => __('Campaign Monitor - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'verticalresponse' => array('label' => __('Vertical Response - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'sendgrid' => array('label' => __('SendGrid - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'get_response' => array('label' => __('GetResponse - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'icontact' => array('label' => __('iContact - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'activecampaign' => array('label' => __('Active Campaign - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'mailrelay' => array('label' => __('Mailrelay - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'arpreach' => array('label' => __('arpReach - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'sgautorepondeur' => array('label' => __('SG Autorepondeur - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'benchmarkemail' => array('label' => __('Benchmark - PRO', CFS_LANG_CODE), 'require_confirm' => true),
				'infusionsoft' => array('label' => __('InfusionSoft - PRO', CFS_LANG_CODE), 'require_confirm' => false),
				'salesforce' => array('label' => __('SalesForce - Web-to-Lead - PRO', CFS_LANG_CODE), 'require_confirm' => false),
				'convertkit' => array('label' => __('ConvertKit - PRO', CFS_LANG_CODE), 'require_confirm' => false),
				'myemma' => array('label' => __('Emma - PRO', CFS_LANG_CODE), 'require_confirm' => false),
			));
		}
		return $subDestList;
	}
	public function getOverviewTabContent() {
		return $this->getView()->getOverviewTabContent();
	}
	public function showWelcomePage() {
		$this->getView()->showWelcomePage();
	}
	public function displayAdminFooter() {
		if(frameCfs::_()->isAdminPlugPage()) {
			$this->getView()->displayAdminFooter();
		}
	}
	private function _preparePromoLink($link, $ref = '') {
		if(empty($ref))
			$ref = 'user';
		return $link;
	}
	public function weLoveYou() {
		if(!$this->isPro()) {
			dispatcherCfs::addFilter('formsEditTabs', array($this, 'addUserExp'), 10, 2);
		}
	}
	public function showAdditionalmainAdminShowOnOptions($forms) {
		$this->getView()->showAdditionalmainAdminShowOnOptions($forms);
	}
	public function addUserExp($tabs, $forms) {
		$modPath = $this->getAssetsUrl();
		if(!frameCfs::_()->getModule('ab_testing')) {
			$tabs['cfsFormAbTesting'] = array(
				'title' => __('Testing', CFS_LANG_CODE),
				'content' => '<a href="'. $this->generateMainLink('utm_source=plugin&utm_medium=abtesting&utm_campaign=forms'). '" target="_blank" class="button button-primary">'
					. __('Get PRO', CFS_LANG_CODE). '</a><br /><a href="'. $this->generateMainLink('utm_source=plugin&utm_medium=abtesting&utm_campaign=forms'). '" target="_blank">'
					. '<img style="max-width: 100%;" src="'. $modPath. 'img/AB-testing-pro.jpg" />'
				. '</a>',
				'icon_content' => '<b>A/B</b>',
				'avoid_hide_icon' => true,
				'sort_order' => 60,
			);
		}
		if(!frameCfs::_()->getModule('subscribe')) {
			$tabs['cfsFormSubscribe'] = array(
				'title' => __('Subscribe', CFS_LANG_CODE),
				'content' => '<a href="'. $this->generateMainLink('utm_source=plugin&utm_medium=subscribe&utm_campaign=forms'). '" target="_blank" class="button button-primary">'
					. __('Get PRO', CFS_LANG_CODE). '</a><br /><a href="'. $this->generateMainLink('utm_source=plugin&utm_medium=subscribe&utm_campaign=forms'). '" target="_blank">'
					. '<img style="max-width: 100%;" src="'. $modPath. 'img/subscribe-pro.gif" />'
				. '</a>',
				'fa_icon' => 'fa-users',
				'sort_order' => 50,
			);
		}
		return $tabs;
	}
	public function addUserExpDesign($tabs) {
		$tabs['cfsFormLayeredForm'] = array(
			'title' => __('Form Location', CFS_LANG_CODE),
			'content' => $this->getView()->getLayeredStylePromo(),
			'fa_icon' => 'fa-arrows',
			'sort_order' => 15,
		);
		return $tabs;
	}
	/**
	 * Public shell for private method
	 */
	public function preparePromoLink($link, $ref = '') {
		return $this->_preparePromoLink($link, $ref);
	}
	public function checkStatisticStatus(){
		$canSend = (int) frameCfs::_()->getModule('options')->get('send_stats');
		if($canSend && frameCfs::_()->getModule('user')->isAdmin()) {
			// Before this version we had many wrong data collected taht we don't need at all. Let's clear them.
			if(CFS_VERSION == '1.3.5') {
				$clearedTrashStatData = (int) get_option(CFS_DB_PREF. 'cleared_trash_stat_data');
				if(!$clearedTrashStatData) {
					$this->getModel()->clearUsageStat();
					update_option(CFS_DB_PREF. 'cleared_trash_stat_data', 1);
					return;	// We just cleared whole data - so don't need to even check send stats
				}
			}
			$this->getModel()->checkAndSend();
		}
	}
	public function getMinStatSend() {
		return $this->_minDataInStatToSend;
	}
	public function getMainLink() {
		if(empty($this->_mainLink)) {
			$affiliateQueryString = '';
			$this->_mainLink = 'https://supsystic.com/plugins/contact-form-plugin/' . $affiliateQueryString;
		}
		return $this->_mainLink ;
	}
	public function generateMainLink($params = '') {
		$mainLink = $this->getMainLink();
		if(!empty($params)) {
			return $mainLink. (strpos($mainLink , '?') ? '&' : '?'). $params;
		}
		return $mainLink;
	}
	public function getContactFormFields() {
		$fields = array(
            'name' => array('label' => __('Name', CFS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'text'),
			'email' => array('label' => __('Email', CFS_LANG_CODE), 'html' => 'email', 'valid' => array('notEmpty', 'email'), 'placeholder' => 'example@mail.com', 'def' => get_bloginfo('admin_email')),
			'website' => array('label' => __('Website', CFS_LANG_CODE), 'html' => 'text', 'placeholder' => 'http://example.com', 'def' => get_bloginfo('url')),
			'subject' => array('label' => __('Subject', CFS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'text'),
            'category' => array('label' => __('Topic', CFS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'selectbox', 'options' => array(
				'plugins_options' => __('Plugin options', CFS_LANG_CODE),
				'bug' => __('Report a bug', CFS_LANG_CODE),
				'functionality_request' => __('Require a new functionality', CFS_LANG_CODE),
				'other' => __('Other', CFS_LANG_CODE),
			)),
			'message' => array('label' => __('Message', CFS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'textarea', 'placeholder' => __('Hello Supsystic Team!', CFS_LANG_CODE)),
        );
		foreach($fields as $k => $v) {
			if(isset($fields[ $k ]['valid']) && !is_array($fields[ $k ]['valid']))
				$fields[ $k ]['valid'] = array( $fields[ $k ]['valid'] );
		}
		return $fields;
	}
	public function isPro() {
		static $isPro;
		if(is_null($isPro)) {
			// license is always active with PRO - even if license key was not entered,
			$isPro = frameCfs::_()->getModule('license') ? true : false;
		}
		return $isPro;
	}
	public function getAssetsUrl() {
		if(empty($this->_assetsUrl)) {
			$this->_assetsUrl = frameCfs::_()->getModule('forms')->getAssetsUrl(). 'promo/';
		}
		return $this->_assetsUrl;
	}
	public function checkWelcome() {
		$from = reqCfs::getVar('from', 'get');
		$pl = reqCfs::getVar('pl', 'get');
		if($from == 'welcome-page' && $pl == CFS_CODE && frameCfs::_()->getModule('user')->isAdmin()) {
			$welcomeSent = (int) get_option(CFS_DB_PREF. 'welcome_sent');
			if(!$welcomeSent) {
				$this->getModel()->welcomePageSaveInfo();
				update_option(CFS_DB_PREF. 'welcome_sent', 1);
			}
			$skipTutorial = (int) reqCfs::getVar('skip_tutorial', 'get');
			if($skipTutorial) {
				$tourHst = $this->getModel()->getTourHst();
				$tourHst['closed'] = 1;
				$this->getModel()->setTourHst( $tourHst );
			}
		}
	}
	public function getContactLink() {
		return $this->getMainLink(). '#contact';
	}
	public function addMainOpts($opts) {
		$title = 'WordPress Form Plugin';
		$opts['options']['love_link_html'] = '<a title="'. $title. '" style="color: #26bfc1 !important; font-size: 9px; position: absolute; bottom: 15px; right: 15px;" href="'. $this->generateMainLink('utm_source=plugin&utm_medium=love_link&utm_campaign=forms'). '" target="_blank">'
			. $title
			. '</a>';
		return $opts;
	}
	public function checkSaveOpts($newValues) {
		$loveLinkEnb = (int) frameCfs::_()->getModule('options')->get('add_love_link');
		$loveLinkEnbNew = isset($newValues['opt_values']['add_love_link']) ? (int) $newValues['opt_values']['add_love_link'] : 0;
		if($loveLinkEnb != $loveLinkEnbNew) {
			$this->getModel()->saveUsageStat('love_link.'. ($loveLinkEnbNew ? 'enb' : 'dslb'));
		}
	}
	public function checkProTpls($list) {
		if(!$this->isPro()) {
			$imgsPath = frameCfs::_()->getModule('forms')->getAssetsUrl(). 'img/preview/';
			$promoList = array(	// No pro tpls for now
//array('label' => 'List Building Layered', 'img_preview' => 'list-building-layered.jpg', 'sort_order' => 18),
);
			foreach($promoList as $i => $t) {
				$promoList[ $i ]['img_preview_url'] = $imgsPath. $promoList[ $i ]['img_preview'];
				$promoList[ $i ]['promo'] = strtolower(str_replace(array(' ', '!'), '', $t['label']));
				$promoList[ $i ]['promo_link'] = $this->generateMainLink('utm_source=plugin&utm_medium='. $promoList[ $i ]['promo']. '&utm_campaign=forms');
			}
			foreach($list as $i => $t) {
				if(isset($t['is_pro']) && $t['is_pro']) {
					unset($list[ $i ]);
				}
			}
			$list = array_merge($list, $promoList);
		}
		return $list;
	}
	public function loadTutorial() {
		return;	// No tutorial for now
		// Don't run on WP < 3.3
		if ( get_bloginfo( 'version' ) < '3.3' )
			return;

		if ( is_admin() && current_user_can(frameCfs::_()->getModule('adminmenu')->getMainCap()) ) {

			$this->checkToShowTutorial();
        }
	}
	public function checkToShowTutorial() {
		if(reqCfs::getVar('tour', 'get') == 'clear-hst') {
			$this->getModel()->clearTourHst();
		}
		$hst = $this->getModel()->getTourHst();
		if((isset($hst['closed']) && $hst['closed'])
			|| (isset($hst['finished']) && $hst['finished'])
		) {
			return;
		}
		$tourData = array();
		$tourData['tour'] = array(
			'welcome' => array(
				'points' => array(
					'first_welcome' => array(
						'target' => '#toplevel_page_contact-form-supsystic',
						'options' => array(
							'position' => array(
								'edge' => 'bottom',
								'align' => 'top',
							),
						),
						'show' => 'not_plugin',
					),
				),
			),
			'create_first' => array(
				'points' => array(
					'create_bar_btn' => array(
						'target' => '.supsystic-content .supsystic-navigation .supsystic-tab-forms_add_new',
						'options' => array(
							'position' => array(
								'edge' => 'left',
								'align' => 'right',
							),
						),
						'show' => array('tab_forms', 'tab_settings', 'tab_overview'),
					),
					'enter_title' => array(
						'target' => '#cfsCreateFormForm input[type=text]',
						'options' => array(
							'position' => array(
								'edge' => 'top',
								'align' => 'bottom',
							),
						),
						'show' => 'tab_forms_add_new',
					),
					'select_tpl' => array(
						'target' => '.forms-list',
						'options' => array(
							'position' => array(
								'edge' => 'bottom',
								'align' => 'top',
							),
						),
						'show' => 'tab_forms_add_new',
					),
					'save_first_forms' => array(
						'target' => '#cfsCreateFormForm .button-primary',
						'options' => array(
							'position' => array(
								'edge' => 'left',
								'align' => 'right',
							),
						),
						'show' => 'tab_forms_add_new',
					),
				),
			),
			'first_edit' => array(
				'points' => array(
					'forms_main_opts' => array(
						'target' => '#cfsFormEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'left',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_forms_edit',
					),
					'forms_design_opts' => array(
						'target' => '#cfsFormEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'top',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_forms_edit',
						'sub_tab' => '#cfsFormTpl',
					),
					'forms_subscribe_opts' => array(
						'target' => '#cfsFormEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'top',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_forms_edit',
						'sub_tab' => '#cfsFormSubscribe',
					),
					'forms_statistics_opts' => array(
						'target' => '#cfsFormEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'left',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_forms_edit',
						'sub_tab' => '#cfsFormStatistics',
					),
					'forms_code_opts' => array(
						'target' => '#cfsFormEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'left',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_forms_edit',
						'sub_tab' => '#cfsFormEditors',
					),
					'final' => array(
						'target' => '#cfsFormMainControllsShell .cfsFormSaveBtn',
						'options' => array(
							'position' => array(
								'edge' => 'top',
								'align' => 'bottom',
							),
							'pointerWidth' => 500,
						),
						'show' => 'tab_forms_edit',
					),
				),
			),
		);
		$isAdminPage = frameCfs::_()->isAdminPlugOptsPage();
		$activeTab = frameCfs::_()->getModule('options')->getActiveTab();
		foreach($tourData['tour'] as $stepId => $step) {
			foreach($step['points'] as $pointId => $point) {
				$pointKey = $stepId. '-'. $pointId;
				if(isset($hst['passed'][ $pointKey ]) && $hst['passed'][ $pointKey ]) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				$show = isset($point['show']) ? $point['show'] : 'plugin';
				if(!is_array($show))
					$show = array( $show );
				if((in_array('plugin', $show) && !$isAdminPage) || (in_array('not_plugin', $show) && $isAdminPage)) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				$showForTabs = false;
				$hideForTabs = false;
				foreach($show as $s) {
					if(strpos($s, 'tab_') === 0) {
						$showForTabs = true;
					}
					if(strpos($s, 'tab_not_') === 0) {
						$showForTabs = true;
					}
				}
				if($showForTabs && (!in_array('tab_'. $activeTab, $show) || !$isAdminPage)) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				if($hideForTabs && (in_array('tab_not_'. $activeTab, $show) || !$isAdminPage)) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				$pointKeyContinue = false;
				switch($pointKey) {
					case 'create_first-create_bar_btn':
						// Pointer for Create new Form we can show only if there are no created Forms
						$createdFormsNum = frameCfs::_()->getModule('forms')->getModel()->addWhere('original_id != 0')->getCount();
						if(!empty($createdFormsNum)) {
							unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
							$pointKeyContinue = true;
						}
				}
				// Yeah, this is not neccesarry - but........... ;)
				if($pointKeyContinue) {
					continue;
				}
			}
		}
		foreach($tourData['tour'] as $stepId => $step) {
			if(!isset($step['points']) || empty($step['points'])) {
				unset($tourData['tour'][ $stepId ]);
			}
		}
		if(empty($tourData['tour']))
			return;
		$tourData['html'] = $this->getView()->getTourHtml();
		frameCfs::_()->getModule('templates')->loadCoreJs();
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'wp-pointer' );
		frameCfs::_()->addScript(CFS_CODE. 'admin.tour', $this->getModPath(). 'js/admin.tour.js');
		frameCfs::_()->addJSVar(CFS_CODE. 'admin.tour', 'cfsAdminTourData', $tourData);
	}
	public function showFeaturedPluginsPage() {
		return $this->getView()->showFeaturedPluginsPage();
	}
	public function getDiscountMsg() {
		if($this->isPro()
			&& frameCfs::_()->getModule('options')->getActiveTab() == 'license'
			&& frameCfs::_()->getModule('license')
			&& frameCfs::_()->getModule('license')->getModel()->isActive()
		) {
			$proPluginsList = array(
				'ultimate-maps-by-supsystic-pro', 'newsletters-by-supsystic-pro', 'contact-form-by-supsystic-pro', 'live-chat-pro',
				'digital-publications-supsystic-pro', 'coming-soon-supsystic-pro', 'price-table-supsystic-pro', 'tables-generator-pro',
				'social-share-pro', 'popup-by-supsystic-pro', 'supsystic_slider_pro', 'supsystic-gallery-pro', 'google-maps-easy-pro',
				'backup-supsystic-pro',
			);
			$activePluginsList = get_option('active_plugins', array());
			$activeProPluginsCount = 0;
			foreach($activePluginsList as $actPl) {
				foreach($proPluginsList as $proPl) {
					if(strpos($actPl, $proPl) !== false) {
						$activeProPluginsCount++;
					}
				}
			}
			if($activeProPluginsCount === 1) {
				$buyLink = $this->getDiscountBuyUrl();
				$this->getView()->getDiscountMsg($buyLink);
			}
		}
	}
	public function getDiscountBuyUrl() {
		$license = frameCfs::_()->getModule('license')->getModel()->getCredentials();
		$license['key'] = md5($license['key']);
		$license = urlencode(base64_encode(implode('|', $license)));
		$plugin_code = 'contact_form_by_supsystic_pro';
		return 'http://supsystic.com/?mod=manager&pl=lms&action=applyDiscountBuyUrl&plugin_code='. $plugin_code. '&lic='. $license;
	}
}
