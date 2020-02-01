<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Header_Main_Menu' ) ) :

	/**
	 * @class Reign_Kirki_Header_Main_Menu
	 */
	class Reign_Kirki_Header_Main_Menu {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Header_Main_Menu
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Header_Main_Menu Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Header_Main_Menu is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Header_Main_Menu - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Header_Main_Menu Constructor.
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
			'reign_header_main_menu', array(
				'title'			 => __( 'Main Menu', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_header_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			$fields[] = array(
				'type'				 => 'typography',
				'settings'			 => 'reign_header_main_menu_font',
				'label'				 => esc_attr__( 'Menu Font', 'reign' ),
				'description'		 => esc_attr__( 'Allows you to select font properties for menu of site.', 'reign' ),
				'section'			 => 'reign_header_main_menu',
				'default'			 => $default_value_set[ 'reign_header_main_menu_font' ],
				'priority'			 => 10,
				'output'			 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'font-size',
					),
					// array(
					//     'choice'   => 'line-height',
					//     'element'  => '#masthead.site-header .main-navigation .primary-menu > li a',
					//     'property' => 'line-height',
					// ),
//					array(
//						'choice'	 => 'color',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
//						'property'	 => 'color',
//					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'text-align',
					),
				),
				'transport'			 => 'postMessage',
				'js_vars'			 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'font-size',
					),
					// array(
					//     'choice'   => 'line-height',
					//     'element'  => '#masthead.site-header .main-navigation .primary-menu > li a',
					//     'property' => 'line-height',
					// ),
//					array(
//						'choice'	 => 'color',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
//						'property'	 => 'color',
//					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
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


			// $fields[] = array(
			// 	'type'        => 'color',
			// 	'settings'    => 'reign_header_main_menu_text_color',
			// 	'label'       => esc_attr__( 'Text Color', 'reign' ),
			// 	'description'       => esc_attr__( 'Allows you to choose a text color.', 'reign' ),
			// 	'section'     => 'reign_header_main_menu',
			// 	'default'   => $default_value_set['reign_header_main_menu_text_color'],
			// 	'priority'    => 10,
			// 	'choices' => array ('alpha'     => true),
			// 	'transport' => 'postMessage',
			// 	'output'   => array(
			//            array(
			//                'element'  => '.primary-menu > li > a',
			//                'property' => 'color',
			//            )
			//        ),
			//        'js_vars'   => array(
			//            array(
			//                'function' => 'css',
			//                'element'  => '.primary-menu > li > a',
			//                'property' => 'color',
			//            )
			//        )
			// );
//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_main_menu_text_hover_color',
//				'label'				 => esc_attr__( 'Text Hover Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a text hover color.', 'reign' ),
//				'section'			 => 'reign_header_main_menu',
//				'default'			 => $default_value_set[ 'reign_header_main_menu_text_hover_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a:hover',
//						'property'	 => 'color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a:hover',
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
//				'settings'			 => 'reign_header_main_menu_text_active_color',
//				'label'				 => esc_attr__( 'Text Active Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a text active color.', 'reign' ),
//				'section'			 => 'reign_header_main_menu',
//				'default'			 => $default_value_set[ 'reign_header_main_menu_text_active_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li.current-menu-item a',
//						'property'	 => 'color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '#masthead.site-header .main-navigation .primary-menu > li.current-menu-item a',
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
//				'settings'			 => 'reign_header_main_menu_bg_hover_color',
//				'label'				 => esc_attr__( 'Background Hover Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a background hover color.', 'reign' ),
//				'section'			 => 'reign_header_main_menu',
//				'default'			 => $default_value_set[ 'reign_header_main_menu_bg_hover_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '.primary-menu > li a:hover:before, .version-one .primary-menu > li a:hover:before, .version-two .primary-menu > li a:hover:before, .version-three .primary-menu > li a:hover:before, .version-one .primary-menu > li a:before, .version-two .primary-menu > li a:before, .version-three .primary-menu > li a:before',
//						'property'	 => 'background',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '.primary-menu > li a:hover:before, .version-one .primary-menu > li a:hover:before, .version-two .primary-menu > li a:hover:before, .version-three .primary-menu > li a:hover:before, .version-one .primary-menu > li a:before, .version-two .primary-menu > li a:before, .version-three .primary-menu > li a:before',
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
//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_main_menu_bg_active_color',
//				'label'				 => esc_attr__( 'Background Active Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose a background active color.', 'reign' ),
//				'section'			 => 'reign_header_main_menu',
//				'default'			 => $default_value_set[ 'reign_header_main_menu_bg_active_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '.primary-menu > li.current-menu-item a:before, .version-one .primary-menu > li.current-menu-item a:before, .version-two .primary-menu > li.current-menu-item a:before, .version-three .primary-menu > li.current-menu-item a:before',
//						'property'	 => 'background',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '.primary-menu > li.current-menu-item a:before, .version-one .primary-menu > li.current-menu-item a:before, .version-two .primary-menu > li.current-menu-item a:before, .version-three .primary-menu > li.current-menu-item a:before',
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

			$fields[] = array(
				'type'			 => 'switch',
				'settings'		 => 'reign_header_main_menu_more_enable',
				'label'			 => esc_attr__( 'Enable \'More\' menus wrap in header menu.', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to enable or disable \'More\' menus option for header menu.', 'reign' ),
				'section'		 => 'reign_header_main_menu',
				'default'		 => 0,
				'priority'		 => 10,
				'choices'		 => array(
					'on'	 => esc_attr__( 'Enable', 'reign' ),
					'off'	 => esc_attr__( 'Disable', 'reign' ),
				),
			);

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Header_Main_Menu.
 * @return Reign_Kirki_Header_Main_Menu
 */
Reign_Kirki_Header_Main_Menu::instance();
