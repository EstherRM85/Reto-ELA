<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WBCOM_Demo_Importer_PreRequisites_Checker' ) ) :

/**
 * @class WBCOM_Demo_Importer_PreRequisites_Checker
 * @version	1.0.0
 */
class WBCOM_Demo_Importer_PreRequisites_Checker {
	
	/**
	 * The single instance of the class.
	 *
	 * @var WBCOM_Demo_Importer_PreRequisites_Checker
	 * @since 1.0.0
	 */
	protected static $_instance = null;
	
	/**
	 * Main WBCOM_Demo_Importer_PreRequisites_Checker Instance.
	 *
	 * Ensures only one instance of WBCOM_Demo_Importer_PreRequisites_Checker is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WBCOM_Demo_Importer_PreRequisites_Checker()
	 * @return WBCOM_Demo_Importer_PreRequisites_Checker - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	/**
	 * WBCOM_Demo_Importer_PreRequisites_Checker Constructor.
	 */
	public function __construct() {
		// $this->init_hooks();
	}

	public function isCurlEnabled() {
		if ( in_array( 'curl', get_loaded_extensions() ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isZipEnabled() {
		if ( in_array( 'zip', get_loaded_extensions() ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isXmlEnabled() {
		if ( in_array( 'xml', get_loaded_extensions() ) ) {
			return true;
		}
		else {
			return false;
		}
	}

}

endif;

/**
 * Main instance of WBCOM_Demo_Importer_PreRequisites_Checker.
 * @since  1.0.0
 * @return WBCOM_Demo_Importer_PreRequisites_Checker
 */
WBCOM_Demo_Importer_PreRequisites_Checker::instance();
?>