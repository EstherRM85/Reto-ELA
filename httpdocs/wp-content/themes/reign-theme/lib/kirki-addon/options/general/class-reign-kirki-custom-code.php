<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Reign_Kirki_Custom_Code' ) ) :

/**
 * @class Reign_Kirki_Custom_Code
 */
class Reign_Kirki_Custom_Code {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Reign_Kirki_Custom_Code
	 */
	protected static $_instance = null;
	
	/**
	 * Main Reign_Kirki_Custom_Code Instance.
	 *
	 * Ensures only one instance of Reign_Kirki_Custom_Code is loaded or can be loaded.
	 *
	 * @return Reign_Kirki_Custom_Code - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Reign_Kirki_Custom_Code Constructor.
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
			'reign_custom_code',
			array(
				'title'       => __( 'Custom Code', 'reign' ),
				'priority'    => 10,
				'panel'       => 'reign_general_panel',
				'description' => '',
			)
		);
		
		$wp_customize->add_section(
			'reign_comment_section',
			array(
				'title'       => __( 'Comment Box', 'reign' ),
				'priority'    => 10,
				'panel'       => 'reign_general_panel',
				'description' => '',
			)
		);

	}

	public function add_fields( $fields ) {

		$fields[] = array(
			'type'        => 'code',
			'settings'    => 'reign_tracking_code',
			'label'       => esc_attr__( 'Tracking Code', 'reign' ),
			'description'       => esc_attr__( 'You can enter your tracking codes here. This code will be enqueued in site header. For example : Google Tacking Code, Facebook Pixel Code, etc.', 'reign' ),
			'section'     => 'reign_custom_code',
			'priority'    => 10,
			'default'    => '',
			'transport' => 'postMessage',
			'choices'     => array(
				'language' => 'html',
			),
		);

		$fields[] = array(
			'type'        => 'code',
			'settings'    => 'reign_custom_js_header',
			'label'       => esc_attr__( 'Custom JS : Header', 'reign' ),
			'description'       => esc_attr__( 'Just want to do some quick JS changes? Enter them here, they will be applied to your theme.', 'reign' ),
			'section'     => 'reign_custom_code',
			'priority'    => 10,
			'default'    => '',
			'transport' => 'postMessage',
			'choices'     => array(
				'language' => 'js',
			),
		);

		$fields[] = array(
			'type'        => 'code',
			'settings'    => 'reign_custom_js_footer',
			'label'       => esc_attr__( 'Custom JS : Footer', 'reign' ),
			'description'       => esc_attr__( 'Just want to do some quick JS changes? Enter them here, they will be applied to your theme.', 'reign' ),
			'section'     => 'reign_custom_code',
			'priority'    => 10,
			'default'    => '',
			'transport' => 'postMessage',
			'choices'     => array(
				'language' => 'js',
			),
		);
		
		
		$exclude_post_types = array( 'attachment', 'elementor_library', 'download', 'product', 'bp-email', 'scheduled-action','shop_order','rtmedia_album');
		$post_types = get_post_types_by_support( array('comments') );
		foreach( $post_types as $post_type ) {
			$post_type_info = get_post_type_object( $post_type );
			if ( !in_array( $post_type_info->name, $exclude_post_types)) {			
				$comment_post_types[$post_type_info->name] = $post_type_info->label;
			}
		}
		$fields[] = array(
			'type'        	=> 'multicheck',
			'settings'    	=> 'reign_comment',
			'label'       	=> esc_attr__( 'Enable Comment box', 'reign' ),
			'description'   => esc_attr__( 'you can enable comment box on single page.', 'reign' ),
			'section'     	=> 'reign_comment_section',
			'priority'    	=> 10,
			'default'    	=> array('post'),
			'transport' 	=> 'postMessage',
			'choices'     	=> $comment_post_types,
		);
		return $fields;
	}
		
}

endif;

/**
 * Main instance of Reign_Kirki_Custom_Code.
 * @return Reign_Kirki_Custom_Code
 */
Reign_Kirki_Custom_Code::instance();