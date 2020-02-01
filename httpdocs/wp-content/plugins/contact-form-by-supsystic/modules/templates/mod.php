<?php
class templatesCfs extends moduleCfs {
    protected $_styles = array();
	private $_cdnUrl = '';
	
	public function __construct($d) {
		parent::__construct($d);
		$this->getCdnUrl();	// Init CDN URL
	}
	public function getCdnUrl() {
		if(empty($this->_cdnUrl)) {
			$this->_cdnUrl = (uriCfs::isHttps() ? 'https' : 'http'). '://supsystic-42d7.kxcdn.com/';
		}
		return $this->_cdnUrl;
	}
    public function init() {
        if (is_admin()) {
			if($isAdminPlugOptsPage = frameCfs::_()->isAdminPlugOptsPage()) {
				$this->loadCoreJs();
				$this->loadAdminCoreJs();
				$this->loadCoreCss();
				$this->loadChosenSelects();
				frameCfs::_()->addScript('adminOptionsCfs', CFS_JS_PATH. 'admin.options.js', array(), false, true);
				add_action('admin_enqueue_scripts', array($this, 'loadMediaScripts'));
				add_action('init', array($this, 'connectAdditionalAdminAssets'));
			}
			// Some common styles - that need to be on all admin pages - be careful with them
			frameCfs::_()->addStyle('supsystic-for-all-admin-'. CFS_CODE, CFS_CSS_PATH. 'supsystic-for-all-admin.css');
		}
        parent::init();
    }
	public function connectAdditionalAdminAssets() {
		if(is_rtl()) {
			frameCfs::_()->addStyle('styleCfs-rtl', CFS_CSS_PATH. 'style-rtl.css');
		}
	}
	public function loadMediaScripts() {
		if(function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}
	public function loadAdminCoreJs() {
		frameCfs::_()->addScript('jquery-ui-dialog');
		frameCfs::_()->addScript('jquery-ui-slider');
		frameCfs::_()->addScript('wp-color-picker');
		frameCfs::_()->addScript('icheck', CFS_JS_PATH. 'icheck.min.js');
		$this->loadTooltipster();
	}
	public function loadCoreJs() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addScript('jquery');
			$suf = CFS_MINIFY_ASSETS ? '.min' : '';
			frameCfs::_()->addScript('commonCfs', CFS_JS_PATH. 'common'. $suf. '.js');
			frameCfs::_()->addScript('coreCfs', CFS_JS_PATH. 'core'. $suf. '.js');

			$ajaxurl = admin_url('admin-ajax.php');
			$jsData = array(
				'siteUrl'					=> CFS_SITE_URL,
				'imgPath'					=> CFS_IMG_PATH,
				'cssPath'					=> CFS_CSS_PATH,
				'loader'					=> CFS_LOADER_IMG, 
				'close'						=> CFS_IMG_PATH. 'cross.gif', 
				'ajaxurl'					=> $ajaxurl,
				'options'					=> frameCfs::_()->getModule('options')->getAllowedPublicOptions(),
				'CFS_CODE'					=> CFS_CODE,
				//'ball_loader'				=> CFS_IMG_PATH. 'ajax-loader-ball.gif',
				//'ok_icon'					=> CFS_IMG_PATH. 'ok-icon.png',
				'jsPath'					=> CFS_JS_PATH,
			);
			if(is_admin()) {
				$jsData['isPro'] = frameCfs::_()->getModule('supsystic_promo')->isPro();
			}
			$jsData = dispatcherCfs::applyFilters('jsInitVariables', $jsData);
			frameCfs::_()->addJSVar('coreCfs', 'CFS_DATA', $jsData);
			$loaded = true;
		}
	}
	public function loadTooltipster() {
		frameCfs::_()->addScript('tooltipster', $this->_cdnUrl. 'lib/tooltipster/jquery.tooltipster.min.js');
		frameCfs::_()->addStyle('tooltipster', $this->_cdnUrl. 'lib/tooltipster/tooltipster.css');
	}
	public function loadSlimscroll() {
		frameCfs::_()->addScript('jquery.slimscroll', $this->_cdnUrl. 'js/jquery.slimscroll.js');
	}
	public function loadCodemirror() {
		frameCfs::_()->addStyle('cfsCodemirror', $this->_cdnUrl. 'lib/codemirror/codemirror.css');
		frameCfs::_()->addStyle('codemirror-addon-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/show-hint.css');
		frameCfs::_()->addScript('cfsCodemirror', $this->_cdnUrl. 'lib/codemirror/codemirror.js');
		frameCfs::_()->addScript('codemirror-addon-show-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/show-hint.js');
		frameCfs::_()->addScript('codemirror-addon-xml-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/xml-hint.js');
		frameCfs::_()->addScript('codemirror-addon-html-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/html-hint.js');
		frameCfs::_()->addScript('codemirror-mode-xml', $this->_cdnUrl. 'lib/codemirror/mode/xml/xml.js');
		frameCfs::_()->addScript('codemirror-mode-javascript', $this->_cdnUrl. 'lib/codemirror/mode/javascript/javascript.js');
		frameCfs::_()->addScript('codemirror-mode-css', $this->_cdnUrl. 'lib/codemirror/mode/css/css.js');
		frameCfs::_()->addScript('codemirror-mode-htmlmixed', $this->_cdnUrl. 'lib/codemirror/mode/htmlmixed/htmlmixed.js');
	}
	public function loadCoreCss() {
		$this->_styles = array(
			'styleCfs'			=> array('path' => CFS_CSS_PATH. 'style.css', 'for' => 'admin'), 
			'supsystic-uiCfs'	=> array('path' => CFS_CSS_PATH. 'supsystic-ui.css', 'for' => 'admin'), 
			'dashicons'			=> array('for' => 'admin'),
			'bootstrap-alerts'	=> array('path' => CFS_CSS_PATH. 'bootstrap-alerts.css', 'for' => 'admin'),
			'icheck'			=> array('path' => CFS_CSS_PATH. 'jquery.icheck.css', 'for' => 'admin'),
			//'uniform'			=> array('path' => CFS_CSS_PATH. 'uniform.default.css', 'for' => 'admin'),
			'wp-color-picker'	=> array('for' => 'admin'),
		);
		foreach($this->_styles as $s => $sInfo) {
			if(!empty($sInfo['path'])) {
				frameCfs::_()->addStyle($s, $sInfo['path']);
			} else {
				frameCfs::_()->addStyle($s);
			}
		}
		$this->loadFontAwesome();
	}
	public function loadJqueryUi() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('jquery-ui', CFS_CSS_PATH. 'jquery-ui.min.css');
			frameCfs::_()->addStyle('jquery-ui.structure', CFS_CSS_PATH. 'jquery-ui.structure.min.css');
			frameCfs::_()->addStyle('jquery-ui.theme', CFS_CSS_PATH. 'jquery-ui.theme.min.css');
			frameCfs::_()->addStyle('jquery-slider', CFS_CSS_PATH. 'jquery-slider.css');
			$loaded = true;
		}
	}
	public function loadJqGrid() {
		static $loaded = false;
		if(!$loaded) {
			$this->loadJqueryUi();
			frameCfs::_()->addScript('jq-grid', $this->_cdnUrl. 'lib/jqgrid/jquery.jqGrid.min.js');
			frameCfs::_()->addStyle('jq-grid', $this->_cdnUrl. 'lib/jqgrid/ui.jqgrid.css');
			$langToLoad = utilsCfs::getLangCode2Letter();
			$availableLocales = array('ar','bg','bg1251','cat','cn','cs','da','de','dk','el','en','es','fa','fi','fr','gl','he','hr','hr1250','hu','id','is','it','ja','kr','lt','mne','nl','no','pl','pt','pt','ro','ru','sk','sr','sr','sv','th','tr','tw','ua','vi');
			if(!in_array($langToLoad, $availableLocales)) {
				$langToLoad = 'en';
			}
			frameCfs::_()->addScript('jq-grid-lang', $this->_cdnUrl. 'lib/jqgrid/i18n/grid.locale-'. $langToLoad. '.js');
			$loaded = true;
		}
	}
	public function loadFontAwesome() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('font-awesomeCfs', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
			$loaded = true;
		}
	}
	public function loadChosenSelects() {
		frameCfs::_()->addStyle('jquery.chosen', $this->_cdnUrl. 'lib/chosen/chosen.min.css');
		frameCfs::_()->addScript('jquery.chosen', $this->_cdnUrl. 'lib/chosen/chosen.jquery.min.js');
	}
	public function loadDatePicker() {
		frameCfs::_()->addScript('jquery-ui-datepicker');
	}
	public function loadJqplot() {
		static $loaded = false;
		if(!$loaded) {
			$jqplotDir = $this->_cdnUrl. 'lib/jqplot/';

			frameCfs::_()->addStyle('jquery.jqplot', $jqplotDir. 'jquery.jqplot.min.css');

			frameCfs::_()->addScript('jplot', $jqplotDir. 'jquery.jqplot.min.js');
			frameCfs::_()->addScript('jqplot.canvasAxisLabelRenderer', $jqplotDir. 'jqplot.canvasAxisLabelRenderer.min.js');
			frameCfs::_()->addScript('jqplot.canvasTextRenderer', $jqplotDir. 'jqplot.canvasTextRenderer.min.js');
			frameCfs::_()->addScript('jqplot.dateAxisRenderer', $jqplotDir. 'jqplot.dateAxisRenderer.min.js');
			frameCfs::_()->addScript('jqplot.canvasAxisTickRenderer', $jqplotDir. 'jqplot.canvasAxisTickRenderer.min.js');
			frameCfs::_()->addScript('jqplot.highlighter', $jqplotDir. 'jqplot.highlighter.min.js');
			frameCfs::_()->addScript('jqplot.cursor', $jqplotDir. 'jqplot.cursor.min.js');
			frameCfs::_()->addScript('jqplot.barRenderer', $jqplotDir. 'jqplot.barRenderer.min.js');
			frameCfs::_()->addScript('jqplot.categoryAxisRenderer', $jqplotDir. 'jqplot.categoryAxisRenderer.min.js');
			frameCfs::_()->addScript('jqplot.pointLabels', $jqplotDir. 'jqplot.pointLabels.min.js');
			frameCfs::_()->addScript('jqplot.pieRenderer', $jqplotDir. 'jqplot.pieRenderer.min.js');
			$loaded = true;
		}
	}
	public function loadSortable() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addScript('jquery-ui-core');
			frameCfs::_()->addScript('jquery-ui-widget');
			frameCfs::_()->addScript('jquery-ui-mouse');

			frameCfs::_()->addScript('jquery-ui-draggable');
			frameCfs::_()->addScript('jquery-ui-sortable');
			$loaded = true;
		}
	}
	public function loadMagicAnims() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('magic.anim', $this->_cdnUrl. 'css/magic.min.css');
			$loaded = true;
		}
	}
	public function loadCssAnims() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('animate.styles', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.4.0/animate.min.css');
			$loaded = true;
		}
	}
	public function loadBootstrapPartial() {
		static $loaded = false;
		if(!$loaded) {
			$this->loadBootstrapPartialOnlyCss();
			frameCfs::_()->addScript('bootstrap', CFS_JS_PATH. 'bootstrap.min.js');
			frameCfs::_()->addStyle('jasny-bootstrap', CFS_CSS_PATH. 'jasny-bootstrap.min.css');
			frameCfs::_()->addScript('jasny-bootstrap', CFS_JS_PATH. 'jasny-bootstrap.min.js');
			$loaded = true;
		}
	}
	public function loadBootstrapPartialOnlyCss() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('bootstrap.partial', frameCfs::_()->getModule('forms')->getAssetsUrl(). 'css/bootstrap.partial.min.css');
			$loaded = true;
		}
	}
	public function connectWpMceEditor() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addScript('tiny_mce');
			$loaded = true;
		}
	}
	public function loadSerializeJson() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addScript('jquery.serializejson', CFS_JS_PATH. 'jquery.serializejson.min.js');
			$loaded = true;
		}
	}
	public function loadTimePicker() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('jquery.timepicker', CFS_CSS_PATH. 'jquery.timepicker.css');
			frameCfs::_()->addScript('jquery.timepicker', CFS_JS_PATH. 'jquery.timepicker.min.js');
			$loaded = true;
		}
	}
	public function loadBootstrapSimple() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('bootstrap-simple', CFS_CSS_PATH. 'bootstrap-simple.css');
			$loaded = true;
		}
	}
	public function loadGoogleFont( $font ) {
		static $loaded = array();
		if(!isset($loaded[ $font ])) {
			frameCfs::_()->addStyle('google.font.'. str_replace(array(' '), '-', $font), 'https://fonts.googleapis.com/css?family='. urlencode($font));
			$loaded[ $font ] = 1;
		}
	}
	public function loadBxSlider() {
		static $loaded = false;
		if(!$loaded) {
			frameCfs::_()->addStyle('bx-slider', CFS_JS_PATH. 'bx-slider/jquery.bxslider.css');
			frameCfs::_()->addScript('bx-slider', CFS_JS_PATH. 'bx-slider/jquery.bxslider.min.js');
			$loaded = true;
		}
	}
}
