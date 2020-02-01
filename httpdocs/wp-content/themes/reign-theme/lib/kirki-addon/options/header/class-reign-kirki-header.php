<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Header' ) ) :

	/**
	 * @class Reign_Kirki_Header
	 */
	class Reign_Kirki_Header {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Header
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Header Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Header is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Header - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Header Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
			$this->includes();
		}

		public function includes() {
			include_once 'class-reign-kirki-header-main-menu.php';
			include_once 'class-reign-kirki-header-sub-menu.php';
			include_once 'class-reign-kirki-header-sticky-menu.php';
			include_once 'class-reign-kirki-header-mobile-menu.php';
			include_once 'class-reign-kirki-header-topbar.php';
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
			add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		}

		public function add_panels_and_sections( $wp_customize ) {

			$wp_customize->add_panel(
			'reign_header_panel', array(
				'priority'		 => 21,
				'title'			 => __( 'Desktop Header', 'reign' ),
				'description'	 => '',
			)
			);

			$wp_customize->add_section(
			'reign_header_style', array(
				'title'			 => __( 'Layout', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_header_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			$fields[] = array(
				'type'				 => 'radio-image',
				'settings'			 => 'reign_header_layout',
				'label'				 => esc_attr__( 'Layout', 'reign' ),
				'description'		 => esc_attr__( 'Allows you to select header layout for header on your site.', 'reign' ),
				'section'			 => 'reign_header_style',
				'default'			 => 'v2',
				'priority'			 => 10,
				'choices'			 => apply_filters( 'reign_theme_header_choices', array(
					'v1' => REIGN_THEME_URI . '/lib/images/header-v1.jpg',
					'v2' => REIGN_THEME_URI . '/lib/images/header-v2.jpg',
					'v3' => REIGN_THEME_URI . '/lib/images/header-v3.jpg',
					'v4' => REIGN_THEME_URI . '/lib/images/header-v4.png',
				) ),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			// 'partial_refresh' => array(
			// 	'reign_header_layout' => array(
			// 		'selector'        => 'header#masthead .site-branding',
			// 		'render_callback' => function() {
			// 		},
			// 	),
			// ),
			);

//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_bg_color',
//				'label'				 => esc_attr__( 'Background Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you can choose background color for your header.', 'reign' ),
//				'section'			 => 'reign_header_style',
//				'default'			 => $default_value_set[ 'reign_header_bg_color' ],
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => 'body #masthead.site-header',
//						'property'	 => 'background-color',
//					)
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => 'body #masthead.site-header',
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
				'type'				 => 'sortable',
				'settings'			 => 'reign_header_icons_set',
				'label'				 => esc_attr__( 'Manage Icons Options', 'reign' ),
				'description'		 => '',
				'section'			 => 'reign_header_style',
				'priority'			 => 10,
				'default'			 => $default_value_set[ 'reign_header_icons_set' ],
				'choices'			 => array(
					'search'		 => esc_html__( 'Search', 'reign' ),
					'cart'			 => esc_html__( 'Cart', 'reign' ),
					'message'		 => esc_html__( 'Message', 'reign' ),
					'notification'	 => esc_html__( 'Notification', 'reign' ),
					'user-menu'		 => esc_html__( 'User Menu', 'reign' ),
					'login'			 => esc_html__( 'Login', 'reign' ),
					'register-menu'	 => esc_html__( 'Register', 'reign' ),
				),
				/* comment below to make layout meun icon section available for elementor header */
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
				'partial_refresh'	 => array(
					'reign_header_icons_set' => array(
						'selector'			 => '#masthead .reign-fallback-header .search-wrap',
						'render_callback'	 => function() {

						},
					),
				),
			);

//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_icon_color',
//				'label'				 => esc_attr__( 'Icon Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose icon color.', 'reign' ),
//				'section'			 => 'reign_header_style',
//				'default'			 => '#ffffff',
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '.rg-search-icon:before, .rg-icon-wrap span:before, #masthead .rg-icon-wrap, #masthead .user-link-wrap .user-link, #masthead .ps-user-name, #masthead .ps-dropdown--userbar .ps-dropdown__toggle, #masthead .ps-widget--userbar__logout>a',
//						'property'	 => 'color',
//					),
//					array(
//						'element'	 => '.wbcom-nav-menu-toggle span',
//						'property'	 => 'background',
//					),
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '.rg-search-icon:before, .rg-icon-wrap span:before, #masthead .rg-icon-wrap, #masthead .user-link-wrap .user-link, #masthead .ps-user-name, #masthead .ps-dropdown--userbar .ps-dropdown__toggle, #masthead .ps-widget--userbar__logout>a',
//						'property'	 => 'color',
//					),
//					array(
//						'function'	 => 'css',
//						'element'	 => '.wbcom-nav-menu-toggle span',
//						'property'	 => 'background',
//					),
//				),
//				'active_callback'	 => array(
//					array(
//						'setting'	 => 'reign_header_header_type',
//						'operator'	 => '!==',
//						'value'		 => true,
//					),
//				),
//			);
//
//
//			$fields[] = array(
//				'type'				 => 'color',
//				'settings'			 => 'reign_header_icon_hover_color',
//				'label'				 => esc_attr__( 'Icon Hover Color', 'reign' ),
//				'description'		 => esc_attr__( 'Allows you to choose icon hover color.', 'reign' ),
//				'section'			 => 'reign_header_style',
//				'default'			 => '#ffffff',
//				'priority'			 => 10,
//				'choices'			 => array( 'alpha' => true ),
//				'transport'			 => 'postMessage',
//				'output'			 => array(
//					array(
//						'element'	 => '.rg-search-icon:hover:before, .rg-icon-wrap span:hover:before, #masthead .rg-icon-wrap:hover, #masthead .user-link-wrap .user-link:hover, #masthead .ps-user-name:hover, #masthead .ps-dropdown--userbar .ps-dropdown__toggle:hover, #masthead .ps-widget--userbar__logout>a:hover',
//						'property'	 => 'color',
//					),
//				),
//				'js_vars'			 => array(
//					array(
//						'function'	 => 'css',
//						'element'	 => '.rg-search-icon:hover:before, .rg-icon-wrap span:hover:before, #masthead .rg-icon-wrap:hover, #masthead .user-link-wrap .user-link:hover, #masthead .ps-user-name:hover, #masthead .ps-dropdown--userbar .ps-dropdown__toggle:hover, #masthead .ps-widget--userbar__logout>a:hover',
//						'property'	 => 'color',
//					),
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
				'settings'			 => 'reign_title_tagline_typography',
				'label'				 => esc_attr__( 'Site Title Font', 'reign' ),
				'description'		 => esc_attr__( 'Allows you to select font properties of site-title for your site.', 'reign' ),
				'section'			 => 'reign_header_style',
				'default'			 => $default_value_set[ 'reign_title_tagline_typography' ],
				'priority'			 => 10,
				'output'			 => array(
					array(
						'element' => '.site-branding .site-title a',
					),
				),
				'transport'			 => 'postMessage',
				'js_vars'			 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => '.site-branding .site-title a',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => '.site-branding .site-title a',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => '.site-branding .site-title a',
						'property'	 => 'font-size',
					),
					// array(
					//     'choice'   => 'line-height',
					//     'element'  => '.site-branding .site-title a',
					//     'property' => 'line-height',
					// ),
//					array(
//						'choice'	 => 'color',
//						'element'	 => '.site-branding .site-title a',
//						'property'	 => 'color',
//					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => '.site-branding .site-title a',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => '.site-branding .site-title a',
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
			// 	'type'        => 'number',
			// 	'settings'    => 'reign_title_tagline_typography_size',
			// 	'label'       => esc_attr__( 'Site Title Font Size In Mobile View (px)', 'reign' ),
			// 	'description'       => esc_attr__( 'Allows you to select font properties of site-title for your site in mobile view.', 'reign' ),
			// 	'section'     => 'reign_header_style',
			// 	'default'     => '18',
			// 	'priority'    => 10,
			// 	'active_callback' => array(
			// 		array(
			// 			'setting'  => 'reign_header_header_type',
			// 			'operator' => '!==',
			// 			'value'    => true,
			// 		),
			// 	),
			// );

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Header.
 * @return Reign_Kirki_Header
 */
Reign_Kirki_Header::instance();
