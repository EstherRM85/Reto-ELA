<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Reign_Kirki_Page_Mapping' ) ) :

/**
 * @class Reign_Kirki_Page_Mapping
 */
class Reign_Kirki_Page_Mapping {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Reign_Kirki_Page_Mapping
	 */
	protected static $_instance = null;
	
	/**
	 * Main Reign_Kirki_Page_Mapping Instance.
	 *
	 * Ensures only one instance of Reign_Kirki_Page_Mapping is loaded or can be loaded.
	 *
	 * @return Reign_Kirki_Page_Mapping - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Reign_Kirki_Page_Mapping Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
	}

	public function add_panels_and_sections( $wp_customize ) {
    
		$wp_customize->add_section(
			'reign_page_mapping',
			array(
				'title'       => __( 'Page Mapping', 'reign' ),
				'priority'    => 10,
				'panel'       => 'reign_general_panel',
				'description' => '',
			)
		);

	}

	public function add_fields( $fields ) {

		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'reign_login_page',
			'label'       => esc_attr__( 'Login Page', 'reign' ),
			'description'       => esc_attr__( 'You can redirect user to custom login page using this setting.', 'reign' ),
			'section'     => 'reign_page_mapping',
			'priority'    => 10,
			'default'    => 0,
			'transport' => 'postMessage',
		);

		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'reign_registration_page',
			'label'       => esc_attr__( 'Registration Page', 'reign' ),
			'description'       => esc_attr__( 'You can redirect user to custom registration page using this setting.', 'reign' ),
			'section'     => 'reign_page_mapping',
			'priority'    => 10,
			'default'    => 0,
			'transport' => 'postMessage',
		);

		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'reign_404_page',
			'label'       => esc_attr__( '404', 'reign' ),
			'description'       => esc_attr__( 'You can redirect user to custom 404 page using this setting.', 'reign' ),
			'section'     => 'reign_page_mapping',
			'priority'    => 10,
			'default'    => 0,
			'transport' => 'postMessage',
		);

		return $fields;
	}
		
}

endif;

/**
 * Main instance of Reign_Kirki_Page_Mapping.
 * @return Reign_Kirki_Page_Mapping
 */
Reign_Kirki_Page_Mapping::instance();