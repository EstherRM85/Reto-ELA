<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Header_Sub_Menu' ) ) :

	/**
	 * @class Reign_Kirki_Header_Sub_Menu
	 */
	class Reign_Kirki_Header_Sub_Menu {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Header_Sub_Menu
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Header_Sub_Menu Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Header_Sub_Menu is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Header_Sub_Menu - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Header_Sub_Menu Constructor.
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
			'reign_header_sub_menu', array(
				'title'			 => __( 'Sub Menu', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_header_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_sub_menu_bg_color',
//				'label'				 => esc_attr__( 'Background Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a background color for sub menu.', 'reign' ),
//				'section'			 => 'reign_header_sub_menu',
//				'default'			 => $default_value_set[ 'reign_header_sub_menu_bg_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '#primary-menu .children, #primary-menu .sub-menu, #primary-menu .children:after, #primary-menu .sub-menu:after, #primary-menu ul li ul li a',
//						'property'	 => 'background-color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '#primary-menu .children, #primary-menu .sub-menu, #primary-menu .children:after, #primary-menu .sub-menu:after, #primary-menu ul li ul li a',
//						'property'	 => 'background-color',
//					)
//				),
//				'active_callback'	 => array(
//					array(
//						'setting'	 => 'reign_header_header_type',
//						'operator'	 => '!==',
//						'value'		 => true,
//					),
//				),
//			);


			$fields[] = array(
				'type'				 => 'typography',
				'settings'			 => 'reign_header_sub_menu_font',
				'label'				 => esc_attr__( 'Sub Menu Font', 'reign' ),
				'description'		 => esc_attr__( 'Allows you to select font properties for sub-menu of site.', 'reign' ),
				'section'			 => 'reign_header_sub_menu',
				'default'			 => $default_value_set[ 'reign_header_sub_menu_font' ],
				'priority'			 => 10,
				'output'			 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'font-size',
					),
					// array(
					//     'choice'   => 'line-height',
					//     'element'  => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
					//     'property' => 'line-height',
					// ),
//					array(
//						'choice'	 => 'color',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
//						'property'	 => 'color',
//					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'text-align',
					),
				),
				'transport'			 => 'postMessage',
				'js_vars'			 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'font-size',
					),
					// array(
					//     'choice'   => 'line-height',
					//     'element'  => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
					//     'property' => 'line-height',
					// ),
//                array(
//                    'choice'   => 'color',
//                    'element'  => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
//                    'property' => 'color',
//                ),
					array(
						'choice'	 => 'text-transform',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
						'property'	 => 'text-align',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			/* $fields[] = array(
			  'type'        => 'color',
			  'settings'    => 'reign_header_sub_menu_text_color',
			  'label'       => esc_attr__( 'Text Color', 'reign' ),
			  'description' => esc_attr__( 'Allows you to choose a text color.', 'reign' ),
			  'section'     => 'reign_header_sub_menu',
			  'default'     => $default_value_set['reign_header_sub_menu_text_color'],
			  'priority'    => 10,
			  'choices'     => array ('alpha'     => true),
			  'transport'   => 'postMessage',
			  'output'      => array(
			  array(
			  'element'  => '#primary-menu .children li a, #primary-menu .sub-menu li a',
			  'property' => 'color',
			  )
			  ),
			  'js_vars'   => array(
			  array(
			  'function' => 'css',
			  'element'  => '#primary-menu .children li a, #primary-menu .sub-menu li a',
			  'property' => 'color',
			  )
			  )
			  );
			 */


//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_sub_menu_text_hover_color',
//				'label'				 => esc_attr__( 'Text Hover Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a text hover color.', 'reign' ),
//				'section'			 => 'reign_header_sub_menu',
//				'default'			 => $default_value_set[ 'reign_header_sub_menu_text_hover_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover, #masthead.site-header.sticky .main-navigation .primary-menu ul li a:hover',
//						'property'	 => 'color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover, #masthead.site-header.sticky .main-navigation .primary-menu ul li a:hover',
//						'property'	 => 'color',
//					)
//				),
//				'active_callback'	 => array(
//					array(
//						'setting'	 => 'reign_header_header_type',
//						'operator'	 => '!==',
//						'value'		 => true,
//					),
//				),
//			);
//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_sub_menu_bg_hover_color',
//				'label'				 => esc_attr__( 'Background Hover Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a background hover color.', 'reign' ),
//				'section'			 => 'reign_header_sub_menu',
//				'default'			 => $default_value_set[ 'reign_header_sub_menu_bg_hover_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover',
//						'property'	 => 'background',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover',
//						'property'	 => 'background',
//					)
//				),
//				'active_callback'	 => array(
//					array(
//						'setting'	 => 'reign_header_header_type',
//						'operator'	 => '!==',
//						'value'		 => true,
//					),
//				),
//			);

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Header_Sub_Menu.
 * @return Reign_Kirki_Header_Sub_Menu
 */
Reign_Kirki_Header_Sub_Menu::instance();
