<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Header_Topbar' ) ) :

	/**
	 * @class Reign_Kirki_Header_Topbar
	 */
	class Reign_Kirki_Header_Topbar {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Header_Topbar
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Header_Topbar Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Header_Topbar is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Header_Topbar - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Header_Topbar Constructor.
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
			'reign_header_topbar', array(
				'title'			 => __( 'Topbar', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_header_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			$fields[] = array(
				'type'			 => 'switch',
				'settings'		 => 'reign_header_topbar_enable',
				'label'			 => esc_attr__( 'Enable Topbar', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to enable or disable topbar for your site.', 'reign' ),
				'section'		 => 'reign_header_topbar',
				'default'		 => 1,
				'priority'		 => 10,
				'choices'		 => array(
					'on'	 => esc_attr__( 'Enable', 'reign' ),
					'off'	 => esc_attr__( 'Disable', 'reign' ),
				),
			);

			$fields_on_hold		 = array();
			$fields_on_hold[]	 = array(
				'type'				 => 'switch',
				'settings'			 => 'reign_header_topbar_mobile_view_disable',
				'label'				 => esc_attr__( 'Disable Topbar On Mobile View', 'reign' ),
				'description'		 => esc_attr__( 'Allows you to disable or disable topbar for your site on mobile view.', 'reign' ),
				'section'			 => 'reign_header_topbar',
				'default'			 => 0,
				'priority'			 => 10,
				'choices'			 => array(
					'on'	 => esc_attr__( 'Yes', 'reign' ),
					'off'	 => esc_attr__( 'No', 'reign' ),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_topbar_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
			);
			$fields_on_hold[]	 = array(
				'type'				 => 'repeater',
				'settings'			 => 'reign_header_topbar_info_links',
				'label'				 => esc_attr__( 'Info Links', 'reign' ),
				'description'		 => __( 'Fontawesome classes are used to set icons. Check <a href="https://fontawesome.com/icons" target="_blank">https://fontawesome.com/</a> for further assistance.', 'reign' ),
				'section'			 => 'reign_header_topbar',
				'priority'			 => 13,
				'row_label'			 => array(
					'type'	 => 'field',
					'value'	 => esc_attr__( 'Info Link', 'reign' ),
					'field'	 => 'link_text',
				),
				'button_label'		 => esc_attr__( 'Add', 'reign' ),
				'transport'			 => 'postMessage',
				'default'			 => $default_value_set[ 'reign_header_topbar_info_links' ],
				'fields'			 => array(
					'link_text'	 => array(
						'type'			 => 'text',
						'label'			 => esc_attr__( 'Title', 'reign' ),
						'description'	 => '',
						'default'		 => '',
					),
					'link_icon'	 => array(
						'type'			 => 'textarea',
						'label'			 => esc_attr__( 'Icon', 'reign' ),
						'description'	 => '',
						'default'		 => '',
					),
					'link_url'	 => array(
						'type'			 => 'text',
						'label'			 => esc_attr__( 'Link', 'reign' ),
						'description'	 => '',
						'default'		 => '',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_topbar_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
				'partial_refresh'	 => array(
					'reign_header_topbar_info_links' => array(
						'selector'			 => '.reign-header-top .header-top-aside.header-top-left',
						'render_callback'	 => function() {

						},
					),
				),
			);

			$fields_on_hold[] = array(
				'type'				 => 'repeater',
				'settings'			 => 'reign_header_topbar_social_links',
				'label'				 => esc_attr__( 'Social Links', 'reign' ),
				'description'		 => __( 'Fontawesome classes are used to set icons. Check <a href="https://fontawesome.com/icons" target="_blank">https://fontawesome.com/</a> for further assistance.', 'reign' ),
				'section'			 => 'reign_header_topbar',
				'priority'			 => 13,
				'row_label'			 => array(
					'type'	 => 'field',
					'value'	 => esc_attr__( 'Social Link', 'reign' ),
					'field'	 => 'link_text',
				),
				'button_label'		 => esc_attr__( 'Add', 'reign' ),
				'transport'			 => 'postMessage',
				'default'			 => $default_value_set[ 'reign_header_topbar_social_links' ],
				'fields'			 => array(
					'link_text'	 => array(
						'type'			 => 'text',
						'label'			 => esc_attr__( 'Title', 'reign' ),
						'description'	 => '',
						'default'		 => '',
					),
					'link_icon'	 => array(
						'type'			 => 'textarea',
						'label'			 => esc_attr__( 'Icon', 'reign' ),
						'description'	 => '',
						'default'		 => '<i class="fa fa-facebook"></i>',
					),
					'link_url'	 => array(
						'type'			 => 'url',
						'label'			 => esc_attr__( 'Link', 'reign' ),
						'description'	 => '',
						'default'		 => '',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_topbar_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
				'partial_refresh'	 => array(
					'reign_header_topbar_social_links' => array(
						'selector'			 => '.reign-header-top .header-top-aside.header-top-right',
						'render_callback'	 => function() {

						},
					),
				),
			);

//			$fields_on_hold[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_topbar_bg_color',
//				'label'				 => esc_attr__( 'Background Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a background color for topbar.', 'reign' ),
//				'section'			 => 'reign_header_topbar',
//				'default'			 => $default_value_set[ 'reign_header_topbar_bg_color' ],
//				'priority'			 => 13,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '.reign-header-top',
//						'property'	 => 'background-color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '.reign-header-top',
//						'property'	 => 'background-color',
//					)
//				),
//				'active_callback'	 => array(
//					array(
//						'setting'	 => 'reign_header_topbar_enable',
//						'operator'	 => '===',
//						'value'		 => true,
//					),
//				),
//			);
//
//			$fields_on_hold[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_topbar_text_color',
//				'label'				 => esc_attr__( 'Text Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a text color.', 'reign' ),
//				'section'			 => 'reign_header_topbar',
//				'default'			 => $default_value_set[ 'reign_header_topbar_text_color' ],
//				'priority'			 => 13,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '.reign-header-top, .reign-header-top a',
//						'property'	 => 'color',
//					),
//					array(
//						'element'	 => '.reign-header-top .header-top-left span',
//						'property'	 => 'border-color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '.reign-header-top, .reign-header-top a',
//						'property'	 => 'color',
//					),
//					array(
//						'function'	 => 'css',
//						'element'	 => '.reign-header-top .header-top-left span',
//						'property'	 => 'border-color',
//					)
//				),
//				'active_callback'	 => array(
//					array(
//						'setting'	 => 'reign_header_topbar_enable',
//						'operator'	 => '===',
//						'value'		 => true,
//					),
//				),
//			);
//
//
//			$fields_on_hold[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_topbar_text_hover_color',
//				'label'				 => esc_attr__( 'Text Hover Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a text hover color.', 'reign' ),
//				'section'			 => 'reign_header_topbar',
//				'default'			 => $default_value_set[ 'reign_header_topbar_text_hover_color' ],
//				'priority'			 => 13,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '.reign-header-top a:hover',
//						'property'	 => 'color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '.reign-header-top a:hover',
//						'property'	 => 'color',
//					)
//				),
//				'active_callback'	 => array(
//					array(
//						'setting'	 => 'reign_header_topbar_enable',
//						'operator'	 => '===',
//						'value'		 => true,
//					),
//				),
//			);

			$fields_on_hold = apply_filters( 'reign_header_topbar_fields_on_hold', $fields_on_hold );

			foreach ( $fields_on_hold as $key => $value ) {
				$fields[] = $value;
			}

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Header_Topbar.
 * @return Reign_Kirki_Header_Topbar
 */
Reign_Kirki_Header_Topbar::instance();
