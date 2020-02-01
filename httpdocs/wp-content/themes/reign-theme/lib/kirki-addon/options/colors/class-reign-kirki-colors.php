<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Colors' ) ) :

	/**
	 * @class Reign_Kirki_Colors
	 */
	class Reign_Kirki_Colors {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Colors
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Colors Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Colors is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Colors - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Colors Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {

			/* remove default background color from theme. */
			add_action( 'customize_register', array( $this, 'reign_remove_wp_background_color' ) );

			add_filter( 'kirki/fields', array( $this, 'reign_add_fields' ) );
			//add_filter( 'kirki/fields', array( $this, 'add_fields' ) );

			add_action( 'init', array( $this, 'reign_map_color_scheme_values' ) );
		}

		public function reign_remove_wp_background_color( $wp_customize ) {
			$wp_customize->remove_control( 'background_color' );
		}

		public function reign_add_fields( $fields ) {

			$default_value_set = reign_color_scheme_set();

			$selector_for_section_bg = '';
			$selector_for_section_bg = apply_filters( 'reign_selector_set_to_apply_section_bg_color', $selector_for_section_bg );

			$selector_for_border_color	 = '';
			$selector_for_border_color	 = apply_filters( 'reign_selector_set_to_apply_border_color', $selector_for_border_color );

			$fields[] = array(
				'type'		 => 'radio-buttonset',
				'settings'	 => 'reign_color_scheme',
				'label'		 => __( 'Color Scheme', 'reign' ),
				'section'	 => 'colors',
				'default'	 => 'reign_default',
				'priority'	 => 10,
				'choices'	 => [
					'reign_default'		 => esc_html__( 'Default', 'reign' ),
					'reign_clean'		 => esc_html__( 'Clean', 'reign' ),
					'reign_dark'		 => esc_html__( 'Dark', 'reign' ),
					'reign_ectoplasm'	 => esc_html__( 'Ectoplasm', 'reign' ),
					'reign_sunrise'		 => esc_html__( 'Sunrise', 'reign' ),
					'reign_coffee'		 => esc_html__( 'Coffee', 'reign' ),
				],
			);

			foreach ( $default_value_set as $color_scheme_key => $default_set ) {

				// Top Bar Color Scheme
				$fields_on_hold		 = array();
				$fields_on_hold[]	 = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_topbar_bg_color',
					'label'				 => esc_attr__( 'Top Bar Background Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a background color for topbar.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_topbar_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.reign-header-top',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.reign-header-top',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields_on_hold[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_topbar_text_color',
					'label'				 => esc_attr__( 'Top Bar Text Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a text color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_topbar_text_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.reign-header-top, .reign-header-top a',
							'property'	 => 'color',
						),
						array(
							'element'	 => '.reign-header-top .header-top-left span',
							'property'	 => 'border-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.reign-header-top, .reign-header-top a',
							'property'	 => 'color',
						),
						array(
							'function'	 => 'css',
							'element'	 => '.reign-header-top .header-top-left span',
							'property'	 => 'border-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);


				$fields_on_hold[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_topbar_text_hover_color',
					'label'				 => esc_attr__( 'Top Bar Text Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a text hover color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_topbar_text_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.reign-header-top a:hover',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.reign-header-top a:hover',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields_on_hold = apply_filters( 'reign_header_topbar_fields_on_hold', $fields_on_hold );

				foreach ( $fields_on_hold as $key => $value ) {
					$fields[] = $value;
				}

				// Header Color Scheme: Header BG
				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_bg_color',
					'label'				 => esc_attr__( 'Header Background Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you can choose background color for your header.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'body #masthead.site-header',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'body #masthead.site-header',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				// Header Color Scheme: Header Site Title
				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_title_tagline_typography',
					'label'				 => esc_attr__( 'Site Title Font Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a site title color for your site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_title_tagline_typography' ],
					'priority'			 => 10,
					'output'			 => array(
						array(
							'element'	 => '.site-branding .site-title a',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.site-branding .site-title a',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				// Header Color Scheme: Header Main Menu
				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_main_menu_font',
					'label'				 => esc_attr__( 'Main Menu Text Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a menu text color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_main_menu_font' ],
					'priority'			 => 10,
					'output'			 => array(
						array(
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);


				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_main_menu_text_hover_color',
					'label'				 => esc_attr__( 'Main Menu Text Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a text hover color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_main_menu_text_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a:hover',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li a:hover',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_main_menu_text_active_color',
					'label'				 => esc_attr__( 'Main Menu Text Active Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a text active color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_main_menu_text_active_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li.current-menu-item a',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li.current-menu-item a',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_main_menu_bg_hover_color',
					'label'				 => esc_attr__( 'Main Menu Background Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a background hover color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_main_menu_bg_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.primary-menu > li a:hover:before, .version-one .primary-menu > li a:hover:before, .version-two .primary-menu > li a:hover:before, .version-three .primary-menu > li a:hover:before, .version-one .primary-menu > li a:before, .version-two .primary-menu > li a:before, .version-three .primary-menu > li a:before',
							'property'	 => 'background',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.primary-menu > li a:hover:before, .version-one .primary-menu > li a:hover:before, .version-two .primary-menu > li a:hover:before, .version-three .primary-menu > li a:hover:before, .version-one .primary-menu > li a:before, .version-two .primary-menu > li a:before, .version-three .primary-menu > li a:before',
							'property'	 => 'background',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_main_menu_bg_active_color',
					'label'				 => esc_attr__( 'Main Menu Background Active Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a background active color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_main_menu_bg_active_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.primary-menu > li.current-menu-item a:before, .version-one .primary-menu > li.current-menu-item a:before, .version-two .primary-menu > li.current-menu-item a:before, .version-three .primary-menu > li.current-menu-item a:before',
							'property'	 => 'background',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.primary-menu > li.current-menu-item a:before, .version-one .primary-menu > li.current-menu-item a:before, .version-two .primary-menu > li.current-menu-item a:before, .version-three .primary-menu > li.current-menu-item a:before',
							'property'	 => 'background',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				// Header Color Scheme: Header Sub Menu
				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_sub_menu_bg_color',
					'label'				 => esc_attr__( 'Sub Menu Background Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a background color for sub menu.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_sub_menu_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '#primary-menu .children, #primary-menu .sub-menu, #primary-menu .children:after, #primary-menu .sub-menu:after, #primary-menu ul li ul li a',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#primary-menu .children, #primary-menu .sub-menu, #primary-menu .children:after, #primary-menu .sub-menu:after, #primary-menu ul li ul li a',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_sub_menu_font',
					'label'				 => esc_attr__( 'Sub Menu Text Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a menu text color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_sub_menu_font' ],
					'priority'			 => 10,
					'output'			 => array(
						array(
							'choice'	 => 'color',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
							'property'	 => 'color',
						),
					),
					'transport'			 => 'postMessage',
					'js_vars'			 => array(
						array(
							'choice'	 => 'color',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a',
							'property'	 => 'color',
						),
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_sub_menu_text_hover_color',
					'label'				 => esc_attr__( 'Sub Menu Text Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a text hover color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_sub_menu_text_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover, #masthead.site-header.sticky .main-navigation .primary-menu ul li a:hover',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover, #masthead.site-header.sticky .main-navigation .primary-menu ul li a:hover',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_sub_menu_bg_hover_color',
					'label'				 => esc_attr__( 'Sub Menu Background Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a background hover color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_sub_menu_bg_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover',
							'property'	 => 'background',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#masthead.site-header .main-navigation .primary-menu ul li a:hover',
							'property'	 => 'background',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				// Header Color Scheme: Header Icon
				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_icon_color',
					'label'				 => esc_attr__( 'Header Icon Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose icon color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_icon_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.rg-search-icon:before, .rg-icon-wrap span:before, #masthead .rg-icon-wrap, #masthead .user-link-wrap .user-link, #masthead .ps-user-name, #masthead .ps-dropdown--userbar .ps-dropdown__toggle, #masthead .ps-widget--userbar__logout>a',
							'property'	 => 'color',
						),
						array(
							'element'	 => '.wbcom-nav-menu-toggle span',
							'property'	 => 'background',
						),
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.rg-search-icon:before, .rg-icon-wrap span:before, #masthead .rg-icon-wrap, #masthead .user-link-wrap .user-link, #masthead .ps-user-name, #masthead .ps-dropdown--userbar .ps-dropdown__toggle, #masthead .ps-widget--userbar__logout>a',
							'property'	 => 'color',
						),
						array(
							'function'	 => 'css',
							'element'	 => '.wbcom-nav-menu-toggle span',
							'property'	 => 'background',
						),
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_header_icon_hover_color',
					'label'				 => esc_attr__( 'Header Icon Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose icon hover color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_header_icon_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.rg-search-icon:hover:before, .rg-icon-wrap span:hover:before, #masthead .rg-icon-wrap:hover, #masthead .user-link-wrap .user-link:hover, #masthead .ps-user-name:hover, #masthead .ps-dropdown--userbar .ps-dropdown__toggle:hover, #masthead .ps-widget--userbar__logout>a:hover',
							'property'	 => 'color',
						),
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.rg-search-icon:hover:before, .rg-icon-wrap span:hover:before, #masthead .rg-icon-wrap:hover, #masthead .user-link-wrap .user-link:hover, #masthead .ps-user-name:hover, #masthead .ps-dropdown--userbar .ps-dropdown__toggle:hover, #masthead .ps-widget--userbar__logout>a:hover',
							'property'	 => 'color',
						),
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_body_bg_color',
					'label'				 => esc_attr__( 'Body Background Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a body text color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_body_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'body:not(.elementor-page), .edd-rvi-wrapper-single, .edd-rvi-wrapper-checkout, #edd-rp-single-wrapper, #edd-rp-checkout-wrapper, .edd-sd-share, #isa-related-downloads, .edd_review, .edd-reviews-form-inner, body .lm-distraction-free-reading, .rlla-distraction-free-reading-active .rlla-distraction-free-reading',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'body:not(.elementor-page), .edd-rvi-wrapper-single, .edd-rvi-wrapper-checkout, #edd-rp-single-wrapper, #edd-rp-checkout-wrapper, .edd-sd-share, #isa-related-downloads, .edd_review, .edd-reviews-form-inner, body .lm-distraction-free-reading, .rlla-distraction-free-reading-active .rlla-distraction-free-reading',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_body_text_color',
					'label'				 => esc_attr__( 'Body Text Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a body text color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_body_text_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'body:not(.elementor-page), body:not(.elementor-page) p, body #masthead p, .rg-woocommerce_mini_cart ul.woocommerce-mini-cart li .quantity, #buddypress .field-visibility-settings, #buddypress .field-visibility-settings-notoggle, #buddypress .field-visibility-settings-toggle, #buddypress .standard-form p.description',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'body:not(.elementor-page), body:not(.elementor-page) p, body #masthead p, .rg-woocommerce_mini_cart ul.woocommerce-mini-cart li .quantity, #buddypress .field-visibility-settings, #buddypress .field-visibility-settings-notoggle, #buddypress .field-visibility-settings-toggle, #buddypress .standard-form p.description',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_sections_bg_color',
					'label'				 => esc_attr__( 'Sections Background Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a sections background color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_sections_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '.search-wrap .rg-search-form-wrap, [off-canvas], .rg-dropdown, .user-notifications .rg-dropdown:after, .user-notifications:hover .rg-dropdown:after, #masthead .user-profile-menu, #masthead .user-profile-menu:after, #masthead .user-profile-menu li ul.sub-menu, #masthead .user-profile-menu li ul.sub-menu:before, #masthead .rg-woocommerce_mini_cart, #masthead .rg-woocommerce_mini_cart:after, #masthead .rg-edd_mini_cart, #masthead .rg-edd_mini_cart:after, .blog .default-view, .blog .thumbnail-view, .blog .wb-grid-view, .archive .default-view, .archive .thumbnail-view, .archive .wb-grid-view, .masonry .masonry-view, .search .post, .search .hentry, .widget-area-inner .widget, .widget-area .widget, .bp-widget-area .widget, .bp-plugin-widgets, #buddypress .activity .item-list > li, body.activity #buddypress #item-body div.item-list-tabs#subnav, body.group-home #buddypress #item-body div.item-list-tabs#subnav, #buddypress #whats-new-textarea, #buddypress #whats-new-content #whats-new-options, #buddypress #whats-new-content.active #whats-new-options, #buddypress form#whats-new-form textarea, .woocommerce ul.products li.product, .bp-inner-wrap, .bp-group-inner-wrap, #buddypress div.pagination .pagination-links a, #buddypress div.pagination .pagination-links span, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown:after, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown:after, body:not(.activity) .inner-item-body-wrap, .bp-nouveau .activity-update-form, body.bp-nouveau.activity-modal #bp-nouveau-activity-form, .bp-nouveau #buddypress form#whats-new-form textarea, .bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links), .bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links), .activity-list .activity-item .activity-content .activity-inner, .activity-list .activity-item .activity-content blockquote, .activity-list .activity-item .activity-meta.action, .bp-nouveau .buddypress-wrap form.bp-dir-search-form button[type=submit], .bp-nouveau .buddypress-wrap form.bp-invites-search-form button[type=submit], .bp-nouveau .buddypress-wrap form.bp-messages-search-form button[type=submit], .bp-nouveau .buddypress-wrap form#media_search_form button[type=submit], .bp-nouveau #buddypress div.bp-pagination .bp-pagination-links a, .bp-nouveau #buddypress div.bp-pagination .bp-pagination-links span, .bp-nouveau .bp-list:not(.grid) > li, .buddypress-wrap .bp-feedback, .bp-nouveau .grid.bp-list > li .list-wrap, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action:after, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action:after, .bp-nouveau .buddypress-wrap .bp-tables-user tbody tr, .bp-nouveau .buddypress-wrap table.forum tbody tr, .bp-nouveau .buddypress-wrap .profile, .bp-nouveau .bp-messages-content, .bp-nouveau .bupr-bp-member-reviews-block, .bp-nouveau .bp-member-add-form, .media .rtmedia-container, .bp-nouveau .bptodo-adming-setting, .bp-nouveau .bptodo-form-add, .bp-nouveau #send-invites-editor, .bp-nouveau form#group-settings-form, .bp-nouveau form#settings-form, .bp-nouveau form#account-group-invites-form, .bp-nouveau form#account-capabilities-form, .bp-nouveau .buddypress-wrap table.wp-profile-fields tbody tr, .buddypress-wrap .profile.edit .editfield, .buddypress-wrap .standard-form .description, .bp-nouveau .bp-messages-content #thread-preview, .bp-nouveau .buddypress-wrap table.notification-settings, .bp-messages-content #thread-preview .preview-content .preview-message, .bp-nouveau #message-threads, .rtmedia-uploader .drag-drop, .bp-nouveau .groups-header .desc-wrap .group-description, .bp-nouveau .bp-single-vert-nav .bp-navs.vertical:not(.tabbed-links) ul, .buddypress-wrap:not(.bp-single-vert-nav) .bp-navs:not(.group-create-links) li, .bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links):before, .bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links):before, .bp-nouveau #buddypress div#item-header.single-headers #item-header-cover-image #item-header-content #item-buttons, .bp-nouveau #buddypress div#item-header.single-headers #item-header-cover-image #item-header-content #item-buttons:after, #buddypress div#invite-list, #bptodo-tabs, .bplock-login-form-container .tab-content, ul.bplock-login-shortcode-tabs li.current, #buddypress #cover-image-container.wbtm-cover-header-type-3, .bp-nouveau #buddypress #cover-image-container.wbtm-cover-header-type-3, .comment-list article, .comment-list .pingback, .comment-list .trackback, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, #add_payment_method #payment div.payment_box, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, .woocommerce-error, .woocommerce-info, .woocommerce-message, .select2-dropdown, #bbpress-forums div.odd, #bbpress-forums ul.odd, nav.fes-vendor-menu, .single-download article, .rtm-download-item-bottom, .bp-nouveau .badgeos-achievements-list-item, #edd_checkout_wrap, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce div.product .woocommerce-tabs .panel, #component, .woocommerce #content div.product div.summary, .woocommerce div.product div.summary, .woocommerce-page #content div.product div.summary, .woocommerce-page div.product div.summary, .wbtm-member-directory-type-4 .item-wrapper, .wbtm-group-directory-type-4 .group-content-wrap, .mycred-table, .bp-nouveau .groups-type-navs, .fes-vendor-dashboard, .bp-nouveau .bp-vertical-navs .rg-nouveau-sidebar-menu, .bp-nouveau .buddypress-wrap .select-wrap' . $selector_for_section_bg,
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.search-wrap .rg-search-form-wrap, [off-canvas], .rg-dropdown, .user-notifications .rg-dropdown:after, .user-notifications:hover .rg-dropdown:after, #masthead .user-profile-menu, #masthead .user-profile-menu:after, #masthead .user-profile-menu li ul.sub-menu, #masthead .user-profile-menu li ul.sub-menu:before, #masthead .rg-woocommerce_mini_cart, #masthead .rg-woocommerce_mini_cart:after, #masthead .rg-edd_mini_cart, #masthead .rg-edd_mini_cart:after, .blog .default-view, .blog .thumbnail-view, .blog .wb-grid-view, .archive .default-view, .archive .thumbnail-view, .archive .wb-grid-view, .masonry .masonry-view, .search .post, .search .hentry, .widget-area-inner .widget, .widget-area .widget, .bp-widget-area .widget, .bp-plugin-widgets, #buddypress .activity .item-list > li, body.activity #buddypress #item-body div.item-list-tabs#subnav, body.group-home #buddypress #item-body div.item-list-tabs#subnav, #buddypress #whats-new-textarea, #buddypress #whats-new-content #whats-new-options, #buddypress #whats-new-content.active #whats-new-options, #buddypress form#whats-new-form textarea, .woocommerce ul.products li.product, .bp-inner-wrap, .bp-group-inner-wrap, #buddypress div.pagination .pagination-links a, #buddypress div.pagination .pagination-links span, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown:after, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown:after, body:not(.activity) .inner-item-body-wrap, .bp-nouveau .activity-update-form, body.bp-nouveau.activity-modal #bp-nouveau-activity-form, .bp-nouveau #buddypress form#whats-new-form textarea, .bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links), .bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links), .activity-list .activity-item .activity-content .activity-inner, .activity-list .activity-item .activity-content blockquote, .activity-list .activity-item .activity-meta.action, .bp-nouveau .buddypress-wrap form.bp-dir-search-form button[type=submit], .bp-nouveau .buddypress-wrap form.bp-invites-search-form button[type=submit], .bp-nouveau .buddypress-wrap form.bp-messages-search-form button[type=submit], .bp-nouveau .buddypress-wrap form#media_search_form button[type=submit], .bp-nouveau #buddypress div.bp-pagination .bp-pagination-links a, .bp-nouveau #buddypress div.bp-pagination .bp-pagination-links span, .bp-nouveau .bp-list:not(.grid) > li, .buddypress-wrap .bp-feedback, .bp-nouveau .grid.bp-list > li .list-wrap, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action:after, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action:after, .bp-nouveau .buddypress-wrap .bp-tables-user tbody tr, .bp-nouveau .buddypress-wrap table.forum tbody tr, .bp-nouveau .buddypress-wrap .profile, .bp-nouveau .bp-messages-content, .bp-nouveau .bupr-bp-member-reviews-block, .bp-nouveau .bp-member-add-form, .media .rtmedia-container, .bp-nouveau .bptodo-adming-setting, .bp-nouveau .bptodo-form-add, .bp-nouveau #send-invites-editor, .bp-nouveau form#group-settings-form, .bp-nouveau form#settings-form, .bp-nouveau form#account-group-invites-form, .bp-nouveau form#account-capabilities-form, .bp-nouveau .buddypress-wrap table.wp-profile-fields tbody tr, .buddypress-wrap .profile.edit .editfield, .buddypress-wrap .standard-form .description, .bp-nouveau .bp-messages-content #thread-preview, .bp-nouveau .buddypress-wrap table.notification-settings, .bp-messages-content #thread-preview .preview-content .preview-message, .bp-nouveau #message-threads, .rtmedia-uploader .drag-drop, .bp-nouveau .groups-header .desc-wrap .group-description, .bp-nouveau .bp-single-vert-nav .bp-navs.vertical:not(.tabbed-links) ul, .buddypress-wrap:not(.bp-single-vert-nav) .bp-navs:not(.group-create-links) li, .bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links):before, .bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links):before, .bp-nouveau #buddypress div#item-header.single-headers #item-header-cover-image #item-header-content #item-buttons, .bp-nouveau #buddypress div#item-header.single-headers #item-header-cover-image #item-header-content #item-buttons:after, #buddypress div#invite-list, #bptodo-tabs, .bplock-login-form-container .tab-content, ul.bplock-login-shortcode-tabs li.current, #buddypress #cover-image-container.wbtm-cover-header-type-3, .bp-nouveau #buddypress #cover-image-container.wbtm-cover-header-type-3, .comment-list article, .comment-list .pingback, .comment-list .trackback, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, #add_payment_method #payment div.payment_box, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, .woocommerce-error, .woocommerce-info, .woocommerce-message, .select2-dropdown, #bbpress-forums div.odd, #bbpress-forums ul.odd, nav.fes-vendor-menu, .single-download article, .rtm-download-item-bottom, .bp-nouveau .badgeos-achievements-list-item, #edd_checkout_wrap, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce div.product .woocommerce-tabs .panel, #component, .woocommerce #content div.product div.summary, .woocommerce div.product div.summary, .woocommerce-page #content div.product div.summary, .woocommerce-page div.product div.summary, .wbtm-member-directory-type-4 .item-wrapper, .wbtm-group-directory-type-4 .group-content-wrap, .mycred-table, .bp-nouveau .groups-type-navs, .fes-vendor-dashboard, .bp-nouveau .bp-vertical-navs .rg-nouveau-sidebar-menu, .bp-nouveau .buddypress-wrap .select-wrap' . $selector_for_section_bg,
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_colors_theme',
					'label'				 => esc_attr__( 'Theme Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a primary color, active color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_colors_theme' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'auto',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.widget_categories ul li:before, .widget_archive ul li:before, .widget.widget_nav_menu ul li:before, .widget.widget_meta ul li:before, .widget.widget_recent_comments ul li:before, .widget_rss ul li:before, .widget_pages ul li:before, .widget.widget_links ul li:before, .widget.widget_recent_entries ul li:before, ul.pmpro_billing_info_list li:before, .widget_edd_categories_tags_widget ul li:before, .widget_edd_cart_widget ul li:before, #buddypress div.item-list-tabs ul li.current a, #buddypress div.item-list-tabs ul li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.current a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a:focus,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a:hover,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a:focus,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a:hover,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li a:hover,.buddypress-wrap.bp-vertical-navs .dir-navs.activity-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.groups-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.members-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.sites-nav-tabs ul li.selected a,
								.buddypress-wrap.bp-vertical-navs .main-navs.group-nav-tabs ul li.selected a,
								.buddypress-wrap.bp-vertical-navs .main-navs.user-nav-tabs ul li.selected a,
								.bp-dir-vert-nav .dir-navs ul li.selected a,
								.bp-single-vert-nav .bp-navs.vertical li.selected a,
								.buddypress-wrap .bp-navs.tabbed-links ul li.current a,
								.bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li.current a,
								.bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li a:hover,
								.buddypress-wrap .bp-navs.tabbed-links ul li a:hover,
								.buddypress-wrap .bp-navs li:not(.selected) a:hover,

								.buddypress-wrap .bp-navs li.current a,
								.buddypress-wrap .bp-navs li.current a:focus,
								.buddypress-wrap .bp-navs li.current a:hover,
								.buddypress-wrap .bp-navs li.selected a,
								.buddypress-wrap .bp-navs li.selected a:focus,
								.buddypress-wrap .bp-navs li.selected a:hover,
								.widget-area .widget.buddypress div.item-options a.selected,
								footer div.footer-wrap a:hover,
								footer .widget-area .widget.buddypress div.item-options a:hover,
								footer .widget-area .widget.buddypress div.item-options a.selected,
								.wbtm-member-directory-type-1 .action-wrap:hover,
								.wbtm-member-directory-type-3 .action-wrap:hover,
								.bp-nouveau .wbtm-member-directory-type-1 .action-wrap:hover,
								.bp-nouveau .wbtm-member-directory-type-3 .action-wrap:hover,
								.woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a,
								.fes-vendor-menu ul li.active a, .fes-vendor-menu .edd-tabs li.active a, .fes-vendor-menu .edd-tabs li.active .icon, .fes-vendor-menu .edd-tabs li:hover a, .fes-vendor-menu .edd-tabs li:hover .icon, .rg-woo-breadcrumbs a.current',
							'property'	 => 'color',
						),
						array(
							'function'	 => 'css',
							'element'	 => '.rg-count, h3.lm-header-title:after, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_pmpro_price_top, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_levels_table_button .pmpro_btn, .woocommerce div.product .woocommerce-tabs ul.tabs li.active:before, #pmpro_account-membership .pmpro_actionlinks a, #pmpro_account-profile .pmpro_actionlinks a, #pmpro_cancel .pmpro_actionlinks a, #pmpro_form .pmpro_btn,

							.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links) li:after,
							.bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li:after,

							.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links) li:hover:after,
							.bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li:hover:after,
							.bp-nouveau .groups-type-navs li:after, .bp-nouveau .groups-type-navs li:hover:after,

							.buddypress-wrap .bp-navs li.current a .count,
							.buddypress-wrap .bp-navs li.selected a .count,
							.buddypress_object_nav .bp-navs li.current a .count,
							.buddypress_object_nav .bp-navs li.selected a .count,
							.bp-nouveau .rtm-bp-navs.bp-navs ul.subnav li.selected span,

							.buddypress-wrap.bp-vertical-navs .dir-navs.activity-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.groups-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.members-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.sites-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .main-navs.group-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .main-navs.user-nav-tabs ul li.selected a span,
							#bbpress-forums li.bbp-header,
							.widget_edd_cart_widget p.edd-cart-number-of-items span.edd-cart-quantity,
							.single-download .type-download input[type="radio"]:checked + label span:before, .single-download .type-download .edd_price_options label:before, label.selectit:before,
							input[type="radio"]:checked + label span:before, .edd_price_options label:before, label.selectit:before,
							.fes-vendor-menu ul li.active a:after, .fes-vendor-menu ul li a:hover:after, #edd_user_history th, .fes-table th, .edd-table th, .fes-vendor-dashboard table th, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link a:before,
							.rg-hdr-v4-row-2, body.reign-header-v4 #masthead.sticky.site-header,
							.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current',
							'property'	 => 'background',
						),
						array(
							'function'	 => 'css',
							'element'	 => '.widget-title span, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_pmpro_price_top, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_levels_table_button .pmpro_btn, #pmpro_account-membership .pmpro_actionlinks a, #pmpro_account-profile .pmpro_actionlinks a, #pmpro_cancel .pmpro_actionlinks a, #pmpro_form .pmpro_btn, body #buddypress #item-body div.item-list-tabs#subnav li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.current a, #buddypress div.activity-comments form textarea:focus, body #buddypress div.activity-comments ul li form textarea:focus, .single-download .type-download .edd_price_options input[type="radio"] + label span:after, .single-download .type-download .edd_price_options label:after, .edd_price_options input[type="radio"] + label span:after, .edd_price_options label:after, .edd_pagination span.current, .rg-has-border, .woocommerce div.product div.images .flex-control-thumbs li img.flex-active, .woocommerce div.product div.images .flex-control-thumbs li img:hover',
							'property'	 => 'border-color',
						),
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_headings_color',
					'label'				 => esc_attr__( 'Headings Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a headings color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_headings_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'h1, h2, h3, h4, h5, h6',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'h1, h2, h3, h4, h5, h6',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_link_color',
					'label'				 => esc_attr__( 'Link Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a links color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_link_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'a, .entry-meta span.author.vcard a, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .llms-loop-item-content .llms-loop-link, .llms-loop-item-content .llms-loop-link:visited, .dokan-single-store .dokan-store-tabs ul li a, #buddypress .activity-list .activity-item .activity-meta.action div.generic-button a.button, #buddypress .activity-list .activity-item .activity-meta.action a.button, .bp-nouveau #buddypress .activity-comments .activity-meta a, .bp-nouveau #buddypress .activity-meta .bp-share-btn .bp-share-button, #shiftnav-toggle-main .rg-mobile-header-icon-wrap a',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'a, .entry-meta span.author.vcard a, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .llms-loop-item-content .llms-loop-link, .llms-loop-item-content .llms-loop-link:visited, .dokan-single-store .dokan-store-tabs ul li a, #buddypress .activity-list .activity-item .activity-meta.action div.generic-button a.button, #buddypress .activity-list .activity-item .activity-meta.action a.button, .bp-nouveau #buddypress .activity-comments .activity-meta a, .bp-nouveau #buddypress .activity-meta .bp-share-btn .bp-share-button, #shiftnav-toggle-main .rg-mobile-header-icon-wrap a',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_link_hover_color',
					'label'				 => esc_attr__( 'Link Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a links hover color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_link_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'a:hover, .entry-meta span.author.vcard a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, .llms-loop-item-content .llms-loop-link:hover, .llms-loop-item-content .llms-loop-link:visited:hover, .dokan-single-store .dokan-store-tabs ul li a:hover, #buddypress .activity-list .activity-item .activity-meta.action div.generic-button a.button:hover, #buddypress .activity-list .activity-item .activity-meta.action a.button:hover, .bp-nouveau #buddypress .activity-comments .activity-meta a:hover, .bp-nouveau #buddypress .activity-meta .bp-share-btn .bp-share-button:hover, #shiftnav-toggle-main .rg-mobile-header-icon-wrap a:hover',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'a:hover, .entry-meta span.author.vcard a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, .llms-loop-item-content .llms-loop-link:hover, .llms-loop-item-content .llms-loop-link:visited:hover, .dokan-single-store .dokan-store-tabs ul li a:hover, #buddypress .activity-list .activity-item .activity-meta.action div.generic-button a.button:hover, #buddypress .activity-list .activity-item .activity-meta.action a.button:hover, .bp-nouveau #buddypress .activity-comments .activity-meta a:hover, .bp-nouveau #buddypress .activity-meta .bp-share-btn .bp-share-button:hover, #shiftnav-toggle-main .rg-mobile-header-icon-wrap a:hover',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_button_text_color',
					'label'				 => esc_attr__( 'Button Text Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a button text color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_button_text_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit]',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit]',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_button_text_hover_color',
					'label'				 => esc_attr__( 'Button Text Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a button text hover color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_button_text_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_button_bg_color',
					'label'				 => esc_attr__( 'Button Background Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a button background color for your site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_button_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit]',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit]',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_button_bg_hover_color',
					'label'				 => esc_attr__( 'Button Background Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a button background hover color for your site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_button_bg_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_accent_color',
					'label'				 => esc_attr__( 'Accent Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose links color of content area for your site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_accent_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.post .entry-content a:not(.read-more), .bbp-forum-content a, span#subscription-toggle a',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.post .entry-content a:not(.read-more), .bbp-forum-content a, span#subscription-toggle a',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_accent_hover_color',
					'label'				 => esc_attr__( 'Accent Hover Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose links hover color of content area for your site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_accent_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.post .entry-content a:not(.read-more):hover, .bbp-forum-content a:hover, span#subscription-toggle a:hover',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '.post .entry-content a:not(.read-more):hover, .bbp-forum-content a:hover, span#subscription-toggle a:hover',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_border_color',
					'label'				 => esc_attr__( 'Border Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose site border color.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_border_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => '#scroll-top, .blog .hentry, .search .hentry, .archive .hentry, .post-navigation, .posts-navigation, th, td, .widget-title, form#search-groups-form input#groups_search, form#search-members-form input#members_search, #buddypress .activity-content .activity-meta, .content-wrapper .entry-header.page-header, .content-wrapper header.woocommerce-products-header, .bp-content-area header.entry-header, #buddypress #whats-new-options, #buddypress div.activity-comments form.ac-form, #buddypress div.activity-comments form textarea, body #buddypress div.activity-comments ul li form textarea, body #buddypress #item-body div.item-list-tabs#subnav ul, #buddypress div.activity-comments ul, body.activity-permalink #buddypress div.activity-comments ul, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-2 .action-wrap, .bp-inner-wrap, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown:after, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown:after, .bp-group-inner-wrap, #buddypress .item-list.rg-group-list.wbtm-group-directory-type-2 div.action, #buddypress div.message-search input#messages_search, #buddypress table#message-threads tr.unread td, .bp-nouveau .activity-update-form, .bp-nouveau #buddypress form#whats-new-form textarea, .bp-nouveau .activity-list .activity-item .activity-content .activity-inner, .activity-list .activity-item > .activity-meta.action, .bp-nouveau #buddypress .activity-content .activity-meta a, .bp-nouveau #buddypress div.activity-meta a, .buddypress-wrap .activity-comments .acomment-content, .buddypress.widget ul.item-list li, .bp-nouveau .buddypress-wrap form.bp-dir-search-form, .bp-nouveau .buddypress-wrap form.bp-invites-search-form, .bp-nouveau .buddypress-wrap form.bp-messages-search-form, .bp-nouveau .buddypress-wrap form#media_search_form, .bp-nouveau .buddypress-wrap .select-wrap, .bp-nouveau .buddypress-wrap .bptodo-form-add select, .buddypress-wrap .members-list li .user-update, .bp-nouveau .bp-list:not(.grid) > li, .bp-nouveau .grid.bp-list > li .list-wrap, .buddypress-wrap .groups-list li .group-desc, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action:after, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action:after, .bp-nouveau #buddypress .item-list.rg-member-list.grid.bp-list.wbtm-member-directory-type-2 > li .action, .buddypress-wrap .profile.edit .editfield, .bp-messages-content #thread-preview, .bp-messages-content .preview-pane-header, .bp-messages-content .single-message-thread-header, .bp-nouveau #message-threads li, .bp-nouveau #message-threads, .buddypress .bp-invites-content ul.item-list>li, .groups-header .desc-wrap, .buddypress-wrap .bp-tables-user tr td.label, .buddypress-wrap table.forum tr td.label, .buddypress-wrap table.wp-profile-fields tr td.label, .wbtm-show-item-buttons, .bp-nouveau .wbtm-show-item-buttons, .bp-dir-vert-nav .screen-content, .bp-single-vert-nav .item-body:not(#group-create-body), .buddypress-wrap .tabbed-links ol, .buddypress-wrap .tabbed-links ul, .buddypress-wrap .single-screen-navs li, #buddypress form fieldset, #buddypress table.forum tr td.label, #buddypress table.messages-notices tr td.label, #buddypress table.notifications tr td.label, #buddypress table.notifications-settings tr td.label, #buddypress table.profile-fields tr td.label, #buddypress table.wp-profile-fields tr td.label, .bp-nouveau .bpolls-html-container, .bpolls-polls-option-html, a.bpolls-cancel, .ui-tabs .ui-tabs-panel, #bptodo-dashboard ul li, #bptodo-tabs .ui-widget-header, #bptodo-tabs.ui-tabs .ui-tabs-nav li.ui-tabs-active, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .bprm_resume_form fieldset, .bprm_resume_form select, .bprm-container, #buddypress div#item-header .wbtm-cover-header-type-3 div#item-header-cover-image, .bp-nouveau #buddypress div#item-header .wbtm-cover-header-type-3 div#item-header-cover-image, .bp-messages-content #bp-message-thread-list .message-metadata, .bp-messages-content #bp-message-thread-list, .widget.woocommerce.widget_product_categories ul.product-categories li.cat-item, .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs .panel, .woocommerce div.product .woocommerce-tabs ul.tabs::before, .woocommerce table.shop_table, .woocommerce .woocommerce-checkout #payment.woocommerce-checkout-payment, #add_payment_method #payment ul.payment_methods, .woocommerce-cart #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods, #bbpress-forums fieldset.bbp-form, .edd_pagination a, .edd_pagination span, .edd-rvi-wrapper-single, .edd-rvi-wrapper-checkout, #edd-rp-single-wrapper, #edd-rp-checkout-wrapper, .edd-sd-share, #isa-related-downloads, .edd_review, .edd-reviews-form-inner, #edd_checkout_form_wrap fieldset, #edd_checkout_cart td, #edd_checkout_cart th, .entry-content ul.edd-cart, .entry-content ul#edd_discounts_list, .entry-content ul.edd-cart > li, .entry-content ul#edd_discounts_list > li, .edd-wish-list li, .edd-wl-create, .edd-wl-wish-lists, #edd_user_history td, #edd_user_history, #edd_user_history th, .fes-table, .edd-table, .fes-vendor-dashboard table, .fes-table, .edd-table, .fes-vendor-dashboard table, .fes-table th, .edd-table th, .fes-vendor-dashboard table th, .edd_form fieldset, .fes-form fieldset, .user-notifications ul#rg-notify li + li, .woocommerce .quantity .product_quantity_minus, .woocommerce .quantity .product_quantity_plus, .woocommerce .quantity .qty, .product_meta span+span, #add_payment_method table.cart td.actions .coupon .input-text, .woocommerce-cart table.cart td.actions .coupon .input-text, .woocommerce-checkout table.cart td.actions .coupon .input-text, .woocommerce .woocommerce-checkout #payment.woocommerce-checkout-payment ul li, .woocommerce .woocommerce-MyAccount-navigation, .woocommerce-account .woocommerce-MyAccount-navigation ul > li + li, .woocommerce-account .woocommerce .woocommerce-MyAccount-content, .fes-vendor-menu ul li.fes-vendor-menu-tab + li.fes-vendor-menu-tab, .buddypress-wrap .activity-comments ul li, .woocommerce nav.woocommerce-pagination ul, .woocommerce nav.woocommerce-pagination ul li, footer div#reign-copyright-text, .widget-area-inner .widget, .widget-area .widget, .bp-widget-area .widget, .bp-plugin-widgets' . $selector_for_border_color,
							'property'	 => 'border-color',
						),
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => '#scroll-top, .blog .hentry, .search .hentry, .archive .hentry, .post-navigation, .posts-navigation, th, td, .widget-title, form#search-groups-form input#groups_search, form#search-members-form input#members_search, #buddypress .activity-content .activity-meta, .content-wrapper .entry-header.page-header, .content-wrapper header.woocommerce-products-header, .bp-content-area header.entry-header, #buddypress #whats-new-options, #buddypress div.activity-comments form.ac-form, #buddypress div.activity-comments form textarea, body #buddypress div.activity-comments ul li form textarea, body #buddypress #item-body div.item-list-tabs#subnav ul, #buddypress div.activity-comments ul, body.activity-permalink #buddypress div.activity-comments ul, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-2 .action-wrap, .bp-inner-wrap, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown:after, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown:after, .bp-group-inner-wrap, #buddypress .item-list.rg-group-list.wbtm-group-directory-type-2 div.action, #buddypress div.message-search input#messages_search, #buddypress table#message-threads tr.unread td, .bp-nouveau .activity-update-form, .bp-nouveau #buddypress form#whats-new-form textarea, .bp-nouveau .activity-list .activity-item .activity-content .activity-inner, .activity-list .activity-item > .activity-meta.action, .bp-nouveau #buddypress .activity-content .activity-meta a, .bp-nouveau #buddypress div.activity-meta a, .buddypress-wrap .activity-comments .acomment-content, .buddypress.widget ul.item-list li, .bp-nouveau .buddypress-wrap form.bp-dir-search-form, .bp-nouveau .buddypress-wrap form.bp-invites-search-form, .bp-nouveau .buddypress-wrap form.bp-messages-search-form, .bp-nouveau .buddypress-wrap form#media_search_form, .bp-nouveau .buddypress-wrap .select-wrap, .bp-nouveau .buddypress-wrap .bptodo-form-add select, .buddypress-wrap .members-list li .user-update, .bp-nouveau .bp-list:not(.grid) > li, .bp-nouveau .grid.bp-list > li .list-wrap, .buddypress-wrap .groups-list li .group-desc, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action:after, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action:after, .bp-nouveau #buddypress .item-list.rg-member-list.grid.bp-list.wbtm-member-directory-type-2 > li .action, .buddypress-wrap .profile.edit .editfield, .bp-messages-content #thread-preview, .bp-messages-content .preview-pane-header, .bp-messages-content .single-message-thread-header, .bp-nouveau #message-threads li, .bp-nouveau #message-threads, .buddypress .bp-invites-content ul.item-list>li, .groups-header .desc-wrap, .buddypress-wrap .bp-tables-user tr td.label, .buddypress-wrap table.forum tr td.label, .buddypress-wrap table.wp-profile-fields tr td.label, .wbtm-show-item-buttons, .bp-nouveau .wbtm-show-item-buttons, .bp-dir-vert-nav .screen-content, .bp-single-vert-nav .item-body:not(#group-create-body), .buddypress-wrap .tabbed-links ol, .buddypress-wrap .tabbed-links ul, .buddypress-wrap .single-screen-navs li, #buddypress form fieldset, #buddypress table.forum tr td.label, #buddypress table.messages-notices tr td.label, #buddypress table.notifications tr td.label, #buddypress table.notifications-settings tr td.label, #buddypress table.profile-fields tr td.label, #buddypress table.wp-profile-fields tr td.label, .bp-nouveau .bpolls-html-container, .bpolls-polls-option-html, a.bpolls-cancel, .ui-tabs .ui-tabs-panel, #bptodo-dashboard ul li, #bptodo-tabs .ui-widget-header, #bptodo-tabs.ui-tabs .ui-tabs-nav li.ui-tabs-active, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .bprm_resume_form fieldset, .bprm_resume_form select, .bprm-container, #buddypress div#item-header .wbtm-cover-header-type-3 div#item-header-cover-image, .bp-nouveau #buddypress div#item-header .wbtm-cover-header-type-3 div#item-header-cover-image, .bp-messages-content #bp-message-thread-list .message-metadata, .bp-messages-content #bp-message-thread-list, .widget.woocommerce.widget_product_categories ul.product-categories li.cat-item, .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs .panel, .woocommerce div.product .woocommerce-tabs ul.tabs::before, .woocommerce table.shop_table, .woocommerce .woocommerce-checkout #payment.woocommerce-checkout-payment, #add_payment_method #payment ul.payment_methods, .woocommerce-cart #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods, #bbpress-forums fieldset.bbp-form, .edd_pagination a, .edd_pagination span, .edd-rvi-wrapper-single, .edd-rvi-wrapper-checkout, #edd-rp-single-wrapper, #edd-rp-checkout-wrapper, .edd-sd-share, #isa-related-downloads, .edd_review, .edd-reviews-form-inner, #edd_checkout_form_wrap fieldset, #edd_checkout_cart td, #edd_checkout_cart th, .entry-content ul.edd-cart, .entry-content ul#edd_discounts_list, .entry-content ul.edd-cart > li, .entry-content ul#edd_discounts_list > li, .edd-wish-list li, .edd-wl-create, .edd-wl-wish-lists, #edd_user_history td, #edd_user_history, #edd_user_history th, .fes-table, .edd-table, .fes-vendor-dashboard table, .fes-table, .edd-table, .fes-vendor-dashboard table, .fes-table th, .edd-table th, .fes-vendor-dashboard table th, .edd_form fieldset, .fes-form fieldset, .user-notifications ul#rg-notify li + li, .woocommerce .quantity .product_quantity_minus, .woocommerce .quantity .product_quantity_plus, .woocommerce .quantity .qty, .product_meta span+span, #add_payment_method table.cart td.actions .coupon .input-text, .woocommerce-cart table.cart td.actions .coupon .input-text, .woocommerce-checkout table.cart td.actions .coupon .input-text, .woocommerce .woocommerce-checkout #payment.woocommerce-checkout-payment ul li, .woocommerce .woocommerce-MyAccount-navigation, .woocommerce-account .woocommerce-MyAccount-navigation ul > li + li, .woocommerce-account .woocommerce .woocommerce-MyAccount-content, .fes-vendor-menu ul li.fes-vendor-menu-tab + li.fes-vendor-menu-tab, .buddypress-wrap .activity-comments ul li, .woocommerce nav.woocommerce-pagination ul, .woocommerce nav.woocommerce-pagination ul li, footer div#reign-copyright-text, .widget-area-inner .widget, .widget-area .widget, .bp-widget-area .widget, .bp-plugin-widgets' . $selector_for_border_color,
							'property'	 => 'border-color',
						),
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_site_hr_color',
					'label'				 => esc_attr__( 'HR Color', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to choose a hr color for site.', 'reign' ),
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_site_hr_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'hr',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'hr',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				// Footer Color Scheme
				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_widget_area_bg_color',
					'label'				 => esc_attr__( 'Footer Background Color', 'reign' ),
					'description'		 => 'Allows you can choose footer background color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_widget_area_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div.footer-wrap',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div.footer-wrap',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_widget_title_color',
					'label'				 => esc_attr__( 'Footer Widget Title Color', 'reign' ),
					'description'		 => 'Allows you can choose footer widget title color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_widget_title_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div.footer-wrap .widget-title',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div.footer-wrap .widget-title',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_widget_text_color',
					'label'				 => esc_attr__( 'Footer Text Color', 'reign' ),
					'description'		 => 'Allows you can choose footer text color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_widget_text_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div.footer-wrap .widget',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div.footer-wrap .widget',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_widget_link_color',
					'label'				 => esc_attr__( 'Footer Link Color', 'reign' ),
					'description'		 => 'Allows you can choose footer links color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_widget_link_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div.footer-wrap a, footer .widget-area .widget.buddypress div.item-options a',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div.footer-wrap a, footer .widget-area .widget.buddypress div.item-options a',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_widget_link_hover_color',
					'label'				 => esc_attr__( 'Footer Link Hover Color', 'reign' ),
					'description'		 => 'Allows you can choose footer links hover color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_widget_link_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div.footer-wrap a:hover, footer .widget-area .widget.buddypress div.item-options a:hover, footer .widget-area .widget.buddypress div.item-options a.selected',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div.footer-wrap a:hover, footer .widget-area .widget.buddypress div.item-options a:hover, footer .widget-area .widget.buddypress div.item-options a.selected',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				// Footer Color Scheme: Copyright
				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_copyright_bg_color',
					'label'				 => esc_attr__( 'Copyright Background Color', 'reign' ),
					'description'		 => 'Allows you can choose copyright background color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_copyright_bg_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div#reign-copyright-text',
							'property'	 => 'background-color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div#reign-copyright-text',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_copyright_text_color',
					'label'				 => esc_attr__( 'Copyright Text Color', 'reign' ),
					'description'		 => 'Allows you can choose copyright text color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_copyright_text_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div#reign-copyright-text',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div#reign-copyright-text',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_copyright_link_color',
					'label'				 => esc_attr__( 'Copyright Link Color', 'reign' ),
					'description'		 => 'Allows you can choose copyright link color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_copyright_link_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div#reign-copyright-text a',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div#reign-copyright-text a',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_footer_copyright_link_hover_color',
					'label'				 => esc_attr__( 'Copyright Link Hover Color', 'reign' ),
					'description'		 => 'Allows you can choose copyright links hover color.',
					'section'			 => 'colors',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_footer_copyright_link_hover_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'transport'			 => 'postMessage',
					'output'			 => array(
						array(
							'element'	 => 'footer div#reign-copyright-text a:hover',
							'property'	 => 'color',
						)
					),
					'js_vars'			 => array(
						array(
							'function'	 => 'css',
							'element'	 => 'footer div#reign-copyright-text a:hover',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					)
				);
			}
			return $fields;
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			$fields[] = array(
				'type'			 => 'color',
				'settings'		 => 'reign_colors_theme',
				'label'			 => esc_attr__( 'Theme Color', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to choose a primary color for site.', 'reign' ),
				'section'		 => 'colors',
				'default'		 => $default_value_set[ 'reign_colors_theme' ],
				'priority'		 => 10,
				'choices'		 => array( 'alpha' => true ),
			);

			$fields[] = array(
				'type'			 => 'color',
				'settings'		 => 'reign_site_link_hover_color',
				'label'			 => esc_attr__( 'Link Hover Color', 'reign' ),
				'description'	 => '',
				'section'		 => 'colors',
				'default'		 => '#3b5998',
				'priority'		 => 10,
				'choices'		 => array( 'alpha' => true ),
				'transport'		 => 'postMessage',
				'output'		 => array(
					array(
						'function'	 => 'css',
						'element'	 => 'a:hover',
						'property'	 => 'color',
					)
				),
				'js_vars'		 => array(
					array(
						'function'	 => 'css',
						'element'	 => 'a:hover',
						'property'	 => 'color',
					)
				),
			);

			$fields[] = array(
				'type'			 => 'color',
				'settings'		 => 'reign_colors_button_bg',
				'label'			 => esc_attr__( 'Button Background Color', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to choose a button background color for your site.', 'reign' ),
				'section'		 => 'colors',
				'default'		 => '#3b5998',
				'priority'		 => 10,
				'choices'		 => array( 'alpha' => true ),
				'transport'		 => 'postMessage',
				'output'		 => array(
					array(
						'element'	 => 'button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover',
						'property'	 => 'background-color',
					)
				),
				'js_vars'		 => array(
					array(
						'function'	 => 'css',
						'element'	 => 'button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover',
						'property'	 => 'background-color',
					)
				)
			);

			$fields[] = array(
				'type'			 => 'color',
				'settings'		 => 'reign_accent_color',
				'label'			 => esc_attr__( 'Accent Color', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to choose links color of content area for your site.', 'reign' ),
				'section'		 => 'colors',
				'default'		 => '#a8943d',
				'priority'		 => 10,
				'choices'		 => array( 'alpha' => true ),
				'transport'		 => 'postMessage',
				'output'		 => array(
					array(
						'function'	 => 'css',
						'element'	 => '.post .entry-content a:not(.read-more), .bbp-forum-content a, span#subscription-toggle a',
						'property'	 => 'color',
					)
				),
				'js_vars'		 => array(
					array(
						'function'	 => 'css',
						'element'	 => '.post .entry-content a:not(.read-more), .bbp-forum-content a, span#subscription-toggle a',
						'property'	 => 'color',
					)
				),
			);

			$fields[] = array(
				'type'			 => 'color',
				'settings'		 => 'reign_accent_hover_color',
				'label'			 => esc_attr__( 'Accent Hover Color', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to choose links hover color of content area for your site.', 'reign' ),
				'section'		 => 'colors',
				'default'		 => '#027891',
				'priority'		 => 10,
				'choices'		 => array( 'alpha' => true ),
				'transport'		 => 'postMessage',
				'output'		 => array(
					array(
						'function'	 => 'css',
						'element'	 => '.post .entry-content a:not(.read-more):hover, .bbp-forum-content a:hover, span#subscription-toggle a:hover',
						'property'	 => 'color',
					)
				),
				'js_vars'		 => array(
					array(
						'function'	 => 'css',
						'element'	 => '.post .entry-content a:not(.read-more):hover, .bbp-forum-content a:hover, span#subscription-toggle a:hover',
						'property'	 => 'color',
					)
				),
			);

			return $fields;
		}

		public function reign_map_color_scheme_values() {

			$color_scheme_key = 'reign_default';

			/* Background Color */
			$background_color		 = get_theme_mod( 'background_color' );
			$new_background_color	 = get_theme_mod( $color_scheme_key . '-' . 'reign_site_body_bg_color', false );
			if ( !$new_background_color && $background_color ) {
				set_theme_mod( $color_scheme_key . '-' . 'reign_site_body_bg_color', $background_color );
			}

			/* Theme Color */
			$theme_color	 = get_theme_mod( 'reign_colors_theme' );
			$new_theme_color = get_theme_mod( $color_scheme_key . '-' . 'reign_colors_theme', false );
			if ( !$new_theme_color && $theme_color ) {
				set_theme_mod( $color_scheme_key . '-' . 'reign_colors_theme', $theme_color );
			}

			/* Link Hover Color */
			$link_hover_color		 = get_theme_mod( 'reign_site_link_hover_color' );
			$new_link_hover_color	 = get_theme_mod( $color_scheme_key . '-' . 'reign_site_link_hover_color', false );
			if ( !$new_link_hover_color && $link_hover_color ) {
				set_theme_mod( $color_scheme_key . '-' . 'reign_site_link_hover_color', $link_hover_color );
			}

			/* Button Background Color */
			$button_bg_color	 = get_theme_mod( 'reign_site_button_bg_color' );
			$new_button_bg_color = get_theme_mod( $color_scheme_key . '-' . 'reign_site_button_bg_color', false );
			if ( !$new_button_bg_color && $button_bg_color ) {
				set_theme_mod( $color_scheme_key . '-' . 'reign_site_button_bg_color', $button_bg_color );
			}

			/* Accent Color */
			$accent_color		 = get_theme_mod( 'reign_accent_color' );
			$new_accent_color	 = get_theme_mod( $color_scheme_key . '-' . 'reign_accent_color', false );
			if ( !$new_accent_color && $accent_color ) {
				set_theme_mod( $color_scheme_key . '-' . 'reign_accent_color', $accent_color );
			}

			/* Accent Hover Color */
			$accent_hover_color		 = get_theme_mod( 'reign_accent_hover_color' );
			$new_accent_hover_color	 = get_theme_mod( $color_scheme_key . '-' . 'reign_accent_hover_color', false );
			if ( !$new_accent_hover_color && $accent_hover_color ) {
				set_theme_mod( $color_scheme_key . '-' . 'reign_accent_hover_color', $accent_hover_color );
			}
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Colors.
 * @return Reign_Kirki_Colors
 */
Reign_Kirki_Colors::instance();
