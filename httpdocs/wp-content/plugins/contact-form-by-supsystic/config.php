<?php
    global $wpdb;
    if (!defined('WPLANG') || WPLANG == '') {
        define('CFS_WPLANG', 'en_GB');
    } else {
        define('CFS_WPLANG', WPLANG);
    }
    if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

    define('CFS_PLUG_NAME', basename(dirname(__FILE__)));
    define('CFS_DIR', WP_PLUGIN_DIR. DS. CFS_PLUG_NAME. DS);
    define('CFS_TPL_DIR', CFS_DIR. 'tpl'. DS);
    define('CFS_CLASSES_DIR', CFS_DIR. 'classes'. DS);
    define('CFS_TABLES_DIR', CFS_CLASSES_DIR. 'tables'. DS);
	define('CFS_HELPERS_DIR', CFS_CLASSES_DIR. 'helpers'. DS);
    define('CFS_LANG_DIR', CFS_DIR. 'languages'. DS);
    define('CFS_IMG_DIR', CFS_DIR. 'img'. DS);
    define('CFS_TEMPLATES_DIR', CFS_DIR. 'templates'. DS);
    define('CFS_MODULES_DIR', CFS_DIR. 'modules'. DS);
    define('CFS_FILES_DIR', CFS_DIR. 'files'. DS);
    define('CFS_ADMIN_DIR', ABSPATH. 'wp-admin'. DS);

	define('CFS_PLUGINS_URL', plugins_url());
    define('CFS_SITE_URL', get_bloginfo('wpurl'). '/');
    define('CFS_JS_PATH', CFS_PLUGINS_URL. '/'. CFS_PLUG_NAME. '/js/');
    define('CFS_CSS_PATH', CFS_PLUGINS_URL. '/'. CFS_PLUG_NAME. '/css/');
    define('CFS_IMG_PATH', CFS_PLUGINS_URL. '/'. CFS_PLUG_NAME. '/img/');
    define('CFS_MODULES_PATH', CFS_PLUGINS_URL. '/'. CFS_PLUG_NAME. '/modules/');
    define('CFS_TEMPLATES_PATH', CFS_PLUGINS_URL. '/'. CFS_PLUG_NAME. '/templates/');
    define('CFS_JS_DIR', CFS_DIR. 'js/');

    define('CFS_URL', CFS_SITE_URL);

    define('CFS_LOADER_IMG', CFS_IMG_PATH. 'loading.gif');
	define('CFS_TIME_FORMAT', 'H:i:s');
    define('CFS_DATE_DL', '/');
    define('CFS_DATE_FORMAT', 'm/d/Y');
    define('CFS_DATE_FORMAT_HIS', 'm/d/Y ('. CFS_TIME_FORMAT. ')');
    define('CFS_DATE_FORMAT_JS', 'mm/dd/yy');
    define('CFS_DATE_FORMAT_CONVERT', '%m/%d/%Y');
    define('CFS_WPDB_PREF', $wpdb->prefix);
    define('CFS_DB_PREF', 'cfs_');
    define('CFS_MAIN_FILE', 'cfs.php');

    define('CFS_DEFAULT', 'default');
    define('CFS_CURRENT', 'current');
	
	define('CFS_EOL', "\n");    
    
    define('CFS_PLUGIN_INSTALLED', true);
    define('CFS_VERSION', '1.7.3');
    define('CFS_USER', 'user');
    
    define('CFS_CLASS_PREFIX', 'cfsc');     
    define('CFS_FREE_VERSION', false);
	define('CFS_TEST_MODE', true);
    
    define('CFS_SUCCESS', 'Success');
    define('CFS_FAILED', 'Failed');
	define('CFS_ERRORS', 'cfsErrors');
	
	define('CFS_ADMIN',	'admin');
	define('CFS_LOGGED','logged');
	define('CFS_GUEST',	'guest');
	
	define('CFS_ALL',		'all');
	
	define('CFS_METHODS',		'methods');
	define('CFS_USERLEVELS',	'userlevels');
	/**
	 * Framework instance code
	 */
	define('CFS_CODE', 'cfs');

	define('CFS_LANG_CODE', 'contact-form-by-supsystic');
	/**
	 * Plugin name
	 */
	define('CFS_WP_PLUGIN_NAME', 'Contact Form by Supsystic');
	/**
	 * Allow minification
	 */
	define('CFS_MINIFY_ASSETS', true);
	/**
	 * Custom defined for plugin
	 */
	define('CFS_COMMON', 'common');
	define('CFS_FB_LIKE', 'fb_like');
	define('CFS_VIDEO', 'video');
	define('CFS_IFRAME', 'iframe');
	define('CFS_SIMPLE_HTML', 'simple_html');
	define('CFS_PDF', 'pdf');
	define('CFS_AGE_VERIFY', 'age_verify');
	define('CFS_FULL_SCREEN', 'full_screen');
	define('CFS_LOGIN_REGISTER', 'login_register');
	define('CFS_BAR', 'bar');
	define('CFS_SHORTCODE', 'supsystic-form');
	define('CFS_SHORTCODE_SUBMITTED', 'supsystic-form-submitted');

