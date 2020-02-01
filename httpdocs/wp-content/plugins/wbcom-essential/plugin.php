<?php

namespace WbcomElementorAddons;

use Elementor\Utils;

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
/**
 * Main class plugin
 */

class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	public $modules_manager;

	/**
	 * @deprecated
	 *
	 * @return string
	 */
	public function get_version() {
		return WBCOM_ELEMENTOR_ADDONS_VERSION;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wbcom-essential' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wbcom-essential' ), '1.0.0' );
	}

	/**
	 * @return \Elementor\Plugin
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function includes() {
		require WBCOM_ELEMENTOR_ADDONS_PATH . 'includes/modules-manager.php';
		require WBCOM_ELEMENTOR_ADDONS_PATH . 'includes/form-ajax-handler.php';
		require WBCOM_ELEMENTOR_ADDONS_PATH . 'includes/global-header-footer.php';
		require WBCOM_ELEMENTOR_ADDONS_PATH . 'includes/global-header-footer-posttype.php';
		require WBCOM_ELEMENTOR_ADDONS_PATH . 'includes/global-settings-manager.php';
		require WBCOM_ELEMENTOR_ADDONS_PATH . 'includes/class-wbcom-reign-customizer-support.php';
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}
		$filename	 = strtolower(
		preg_replace(
		[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ], [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ], $class
		)
		);
		$filename	 = WBCOM_ELEMENTOR_ADDONS_PATH . $filename . '.php';
		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	public function enqueue_styles() {
		$suffix				 = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$direction_suffix	 = is_rtl() ? '-rtl' : '';
		wp_enqueue_style(
		'wbcom-essential', WBCOM_ELEMENTOR_ADDONS_ASSETS_URL . 'css/frontend' . $direction_suffix . $suffix . '.css', [], WBCOM_ELEMENTOR_ADDONS_VERSION
		);
	}

	public function enqueue_frontend_scripts() {
		// $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		// wp_enqueue_script(
		// 	'wbcom-elementor-addons-frontend',
		// 	WBCOM_ELEMENTOR_ADDONS_URL . 'assets/js/frontend' . $suffix . '.js',
		// 	[
		// 		'jquery',
		// 	],
		// 	WBCOM_ELEMENTOR_ADDONS_VERSION,
		// 	true
		// );
		// $locale_settings = [
		// 	'ajaxurl' => admin_url( 'admin-ajax.php' ),
		// 	'nonce' => wp_create_nonce( 'wbcom-elementor-addons-frontend' ),
		// ];
		// wp_localize_script(
		// 	'wbcom-elementor-addons-frontend',
		// 	'WbcomElementorAddonsFrontendConfig',
		// 	apply_filters( 'wbcom_elementor_addons/frontend/localize_settings', $locale_settings )
		// );
		/* Login Widget Script */
		wp_register_script(
		$handle		 = 'wbcom_elementor_login_module_js', $src		 = plugin_dir_url( __FILE__ ) . 'assets/js/login-module.js', $deps		 = array( 'jquery' ), $ver		 = time(), $in_footer	 = true
		);
		wp_localize_script(
		'wbcom_elementor_login_module_js', 'wbcom_elementor_login_module_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		)
		);
		wp_enqueue_script( 'wbcom_elementor_login_module_js' );


		wp_register_script(
		$handle									 = 'wbcom_elementor_main_min_js', $src									 = plugin_dir_url( __FILE__ ) . 'assets/js/main.min.js', $deps									 = array( 'jquery' ), $ver									 = time(), $in_footer								 = true
		);
		$reign_header_topbar_mobile_view_disable = get_theme_mod( 'reign_header_topbar_mobile_view_disable', false );

		$rtl = false;
		if ( is_rtl() ) {
			$rtl = true;
		}

		wp_localize_script(
		'wbcom_elementor_main_min_js', 'essential_js_obj', array(
			'reign_rtl' => $rtl
		)
		);
		wp_enqueue_script( 'wbcom_elementor_main_min_js' );
	}

	// public function enqueue_editor_scripts() {
	// 	$suffix = Utils::is_script_debug() ? '' : '.min';
	// 	wp_enqueue_script(
	// 		'wbcom-essential',
	// 		WBCOM_ELEMENTOR_ADDONS_URL . 'assets/js/editor' . $suffix . '.js',
	// 		[
	// 			'backbone-marionette',
	// 		],
	// 		WBCOM_ELEMENTOR_ADDONS_VERSION,
	// 		true
	// 	);
	// 	$is_license_active = false;
	// 	$is_license_active = true;
	// 	$locale_settings = [
	// 		'i18n' => [],
	// 		'isActive' => $is_license_active,
	// 	];
	// 	wp_localize_script(
	// 		'wbcom-essential',
	// 		'WbcomElementorAddonsConfig',
	// 		apply_filters( 'wbcom_elementor_addons/editor/localize_settings', $locale_settings )
	// 	);
	// }
	// public function register_frontend_scripts() {
	// 	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// 	wp_register_script(
	// 		'smartmenus',
	// 		WBCOM_ELEMENTOR_ADDONS_URL . 'assets/lib/smartmenus/jquery.smartmenus' . $suffix . '.js',
	// 		[
	// 			'jquery',
	// 		],
	// 		'1.0.1',
	// 		true
	// 	);
	// 	wp_register_script(
	// 		'social-share',
	// 		WBCOM_ELEMENTOR_ADDONS_URL . 'assets/lib/social-share/social-share' . $suffix . '.js',
	// 		[
	// 			'jquery',
	// 		],
	// 		'0.2.17',
	// 		true
	// 	);
	// }
	// public function enqueue_editor_styles() {
	// 	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// 	wp_enqueue_style(
	// 		'wbcom-essential',
	// 		WBCOM_ELEMENTOR_ADDONS_URL . 'assets/css/editor' . $suffix . '.css',
	// 		[
	// 			'elementor-editor'
	// 		],
	// 		WBCOM_ELEMENTOR_ADDONS_VERSION
	// 	);
	// }
	public function elementor_init() {
		$this->modules_manager	 = new Manager();
		$elementor				 = \Elementor\Plugin::$instance;
		// Add element category in panel
		$elementor->elements_manager->add_category(
		'wbcom-elements', [
			'title'	 => __( 'WBCOM Elements', 'wbcom-essential' ),
			'icon'	 => 'font',
		], 1
		);
		do_action( 'wbcom_elementor_addons/init' );
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );
		// add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );
		// add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		// add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );
		$this->includes();
		$this->setup_hooks();
	}

}

if ( !defined( 'WBCOM_ELEMENTOR_ADDONS_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}