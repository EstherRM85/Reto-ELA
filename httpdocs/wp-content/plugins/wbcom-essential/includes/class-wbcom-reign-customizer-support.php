<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Wbcom_Reign_Customizer_Support' ) ) :

/**
 * @class Wbcom_Reign_Customizer_Support
 */
class Wbcom_Reign_Customizer_Support {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Wbcom_Reign_Customizer_Support
	 */
	protected static $_instance = null;
	
	/**
	 * Main Wbcom_Reign_Customizer_Support Instance.
	 *
	 * Ensures only one instance of Wbcom_Reign_Customizer_Support is loaded or can be loaded.
	 *
	 * @return Wbcom_Reign_Customizer_Support - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Wbcom_Reign_Customizer_Support Constructor.
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

		add_filter( 'reign_header_topbar_fields_on_hold', array( $this, 'manage_fields_on_hold' ), 10, 1 );

		// add_action( 'wbcom_before_masthead', array( $this, 'render_theme_topbar' ), 18 );



		
	}


	public function add_panels_and_sections( $wp_customize ) {

		$wp_customize->add_section(
			'reign_header_select_header',
			array(
				'title'       => __( 'Select Header', 'reign' ),
				'priority'    => 8,
				'panel'       => 'reign_header_panel',
				'description' => __( '', 'reign' ),
			)
		);


		$wp_customize->add_section(
			'reign_footer_select_footer',
			array(
				'title'       => __( 'Select Footer', 'reign' ),
				'priority'    => 8,
				'panel'       => 'reign_footer_panel',
				'description' => __( '', 'reign' ),
			)
		);
	}

	// public function render_theme_topbar() {
	// 	$reign_header_topbar_type = get_theme_mod( 'reign_header_topbar_type', false );
	// 	if( $reign_header_topbar_type ) {
	// 		remove_action( 'wbcom_before_masthead', array( $this, 'render_theme_topbar' ), 20 );
	// 	}
	// }

	public function manage_fields_on_hold( $fields_on_hold ) {
		$_fields_on_hold = array();
		foreach ( $fields_on_hold as $key => $value ) {
			$value['active_callback'][] = array(
				'setting'  => 'reign_header_topbar_type',
				'operator' => '===',
				'value'    => false,
			);
			$_fields_on_hold[$key] = $value;
		}
		$fields_on_hold = $_fields_on_hold;
		return $fields_on_hold;
	}

	public function add_fields( $fields ) {

		/**
		* Fields for topbar.
		*/ 
		$fields[] = array(
			'type'        => 'switch',
			'settings'    => 'reign_header_topbar_type',
			'label'       => esc_attr__( 'Topbar Type', 'wbcom-essential' ),
			'description'       => esc_attr__( 'Allows you to select default theme topbar or topbar made using Elementor.', 'wbcom-essential' ),
			'section'     => 'reign_header_topbar',
			'default'   => 0,
			'priority'    => 11,
			'choices'     => array(
				'on' => esc_attr__( 'Elementor Topbar', 'wbcom-essential' ),
				'off'  => esc_attr__( 'Theme Default', 'wbcom-essential' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'reign_header_topbar_enable',
					'operator' => '===',
					'value'    => true,
				),
			),
		);

		$args = array(
			'post_type'		 => 'reign-elemtr-header',
			'post_status'	 => 'publish',
			'posts_per_page'	=> -1,
			'meta_query' => array(
				array(
					'key' => 'reign_ele_header_topbar',
					'value' => 'topbar',
					'compare'	=> '=='
				)
			),
		);
		$posts = get_posts( $args );
		$topbar_choices = array();
		if( !empty( $posts ) && is_array( $posts ) ) {
			$topbar_choices[''] = __( '-- Select --', 'wbcom-essential' );
			foreach ( $posts as $key => $post ) {
				$topbar_choices[$post->post_name] = $post->post_title;
			}
		}
		
		$fields[] = array(
			'type'        => 'select',
			'settings'    => 'reign_elementor_topbar',
			'label'       => esc_attr__( 'Select Topbar ', 'wbcom-essential' ),
			'description'       => esc_attr__( '', 'wbcom-essential' ),
			'section'     => 'reign_header_topbar',
			'default'  => '',
			'priority'    => 15,
			'choices'     => $topbar_choices,
			'active_callback' => array(
				array(
					'setting'  => 'reign_header_topbar_type',
					'operator' => '===',
					'value'    => true,
				),
			),
		);

		/**
		* Fields for header.
		*/
		$fields[] = array(
			'type'        => 'switch',
			'settings'    => 'reign_header_header_type',
			'label'       => esc_attr__( 'Header Type', 'wbcom-essential' ),
			'description'       => esc_attr__( 'Allows you to select default theme header or header made using Elementor.', 'wbcom-essential' ),
			'section'     => 'reign_header_select_header',
			'default'   => 0,
			'priority'    => 11,
			'choices'     => array(
				'on' => esc_attr__( 'Elementor Header', 'wbcom-essential' ),
				'off'  => esc_attr__( 'Theme Header', 'wbcom-essential' ),
			),
		);

		$args = array(
			'post_type'		 => 'reign-elemtr-header',
			'post_status'	 => 'publish',
			'posts_per_page'	=> -1,
			'meta_query' => array(
				array(
					'key' => 'reign_ele_header_topbar',
					'value' => 'header',
					'compare'	=> '=='
				) 
			),
		);
		$posts = get_posts( $args );
		$header_choices = array();
		if( !empty( $posts ) && is_array( $posts ) ) {
			$header_choices[''] = __( '-- Select --', 'wbcom-essential' );
			foreach ( $posts as $key => $post ) {
				$header_choices[$post->post_name] = $post->post_title;
			}
		}
		
		$fields[] = array(
			'type'        => 'select',
			'settings'    => 'reign_elementor_header',
			'label'       => esc_attr__( 'Select Header ', 'wbcom-essential' ),
			'description'       => esc_attr__( '', 'wbcom-essential' ),
			'section'     => 'reign_header_select_header',
			'default'  => '',
			'priority'    => 15,
			'choices'     => $header_choices,
			'active_callback' => array(
				array(
					'setting'  => 'reign_header_header_type',
					'operator' => '===',
					'value'    => true,
				),
			),
		);

		/**
		 *
		 * Setting to enable/disable elementor header in mobile view.
		 *
		 */
		$fields[] = array(
			'type'        => 'switch',
			'settings'    => 'reign_elementor_header_mobile',
			'label'       => esc_attr__( 'Elementor Header in Mobile', 'wbcom-essential' ),
			'description'       => esc_attr__( 'Allows you enable/disable elementor heador in mobile view.', 'wbcom-essential' ),
			'section'     => 'reign_header_select_header',
			'default'   => 0,
			'priority'    => 16,
			'choices'     => array(
				'on' => esc_attr__( 'Use Elementor', 'wbcom-essential' ),
				'off'  => esc_attr__( 'Use Default', 'wbcom-essential' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'reign_header_header_type',
					'operator' => '===',
					'value'    => true,
				),
			),
		);


		/**
		* Fields for footer.
		*/
		$fields[] = array(
			'type'        => 'switch',
			'settings'    => 'reign_footer_footer_type',
			'label'       => esc_attr__( 'Footer Type', 'wbcom-essential' ),
			'description'       => esc_attr__( 'Allows you to select default theme footer or footer made using Elementor.', 'wbcom-essential' ),
			'section'     => 'reign_footer_select_footer',
			'default'   => 0,
			'priority'    => 11,
			'choices'     => array(
				'on' => esc_attr__( 'Elementor Footer', 'wbcom-essential' ),
				'off'  => esc_attr__( 'Theme Footer', 'wbcom-essential' ),
			),
		);

		$args = array(
			'post_type'		 => 'reign-elemtr-footer',
			'post_status'	 => 'publish',
			'posts_per_page'	=> -1,
		);
		$posts = get_posts( $args );
		$footer_choices = array();
		if( !empty( $posts ) && is_array( $posts ) ) {
			$footer_choices[''] = __( '-- Select --', 'wbcom-essential' );
			foreach ( $posts as $key => $post ) {
				$footer_choices[$post->post_name] = $post->post_title;
			}
		}
		
		$fields[] = array(
			'type'        => 'select',
			'settings'    => 'reign_elementor_footer',
			'label'       => esc_attr__( 'Select Footer ', 'wbcom-essential' ),
			'description'       => esc_attr__( '', 'wbcom-essential' ),
			'section'     => 'reign_footer_select_footer',
			'default'  => '',
			'priority'    => 15,
			'choices'     => $footer_choices,
			'active_callback' => array(
				array(
					'setting'  => 'reign_footer_footer_type',
					'operator' => '===',
					'value'    => true,
				),
			),
		);

		return $fields;

	}
		
}

endif;

/**
 * Main instance of Wbcom_Reign_Customizer_Support.
 * @return Wbcom_Reign_Customizer_Support
 */
Wbcom_Reign_Customizer_Support::instance();