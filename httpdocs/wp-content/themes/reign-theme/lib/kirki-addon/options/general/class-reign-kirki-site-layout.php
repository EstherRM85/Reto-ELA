<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Site_Layout' ) ) :

	/**
	 * @class Reign_Kirki_Site_Layout
	 */
	class Reign_Kirki_Site_Layout {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Site_Layout
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Site_Layout Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Site_Layout is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Site_Layout - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Site_Layout Constructor.
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
			'reign_site_layout_options', array(
				'title'			 => __( 'Layouts', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_general_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$fields[] = array(
				'type'			 => 'switch',
				'settings'		 => 'reign_enable_preloading',
				'label'			 => esc_attr__( 'Pre Content Loader', 'reign' ),
				'description'	 => esc_attr__( 'Show loader before loading content or not.', 'reign' ),
				'section'		 => 'reign_site_layout_options',
				'default'		 => 0,
				'priority'		 => 10,
				'choices'		 => array(
					'on'	 => esc_attr__( 'Enable', 'reign' ),
					'off'	 => esc_attr__( 'Disable', 'reign' ),
				),
			);

			$fields[] = array(
				'type'				 => 'radio-image',
				'settings'			 => 'reign_preloading_icon',
				'label'				 => esc_attr__( 'Loader Icon', 'reign' ),
				'description'		 => '',
				'section'			 => 'reign_site_layout_options',
				'default'			 => REIGN_THEME_URI . '/lib/images/loader-1.svg',
				'priority'			 => 10,
				'choices'			 => array(
					REIGN_THEME_URI . '/lib/images/loader-1.svg' => REIGN_THEME_URI . '/lib/images/loader-1.svg',
					REIGN_THEME_URI . '/lib/images/loader-2.svg' => REIGN_THEME_URI . '/lib/images/loader-2.svg',
					REIGN_THEME_URI . '/lib/images/loader-3.svg' => REIGN_THEME_URI . '/lib/images/loader-3.svg',
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_enable_preloading',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_preloading_bg_color',
				'label'				 => esc_attr__( 'Loader Background Color', 'reign' ),
				'description'		 => '',
				'section'			 => 'reign_site_layout_options',
				'default'			 => '#ffffff',
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'transport'			 => 'postMessage',
				'js_vars'			 => array(
					array(
						'function'	 => 'css',
						'element'	 => 'body #masthead.site-header',
						'property'	 => 'background-color',
					)
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_enable_preloading',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
			);


			$fields[] = array(
				'type'			 => 'radio-image',
				'settings'		 => 'reign_site_layout',
				'label'			 => esc_attr__( 'Site Layout', 'reign' ),
				'description'	 => esc_attr__( 'Select your site layout here.', 'reign' ),
				'section'		 => 'reign_site_layout_options',
				'default'		 => 'full_width',
				'priority'		 => 10,
				'choices'		 => array(
					'full_width' => REIGN_THEME_URI . '/lib/images/full-width.jpg',
					'box_width'	 => REIGN_THEME_URI . '/lib/images/box-width.jpg',
				),
			);

			// $fields[] = array(
			// 	'type'        => 'switch',
			// 	'settings'    => 'reign_site_enable_header_image',
			// 	'label'       => esc_attr__( 'Enable Header Image', 'reign' ),
			// 	'description'       => '',
			// 	'section'     => 'reign_site_layout_options',
			// 	'default'   => 1,
			// 	'priority'    => 10,
			// 	'choices'     => array(
			// 		'on'  => esc_attr__( 'Enable', 'reign' ),
			// 		'off' => esc_attr__( 'Disable', 'reign' ),
			// 	),
			// );

			$fields[] = array(
				'type'			 => 'switch',
				'settings'		 => 'reign_sticky_sidebar',
				'label'			 => esc_attr__( 'Sticky Sidebar', 'reign' ),
				'description'	 => '',
				'section'		 => 'reign_site_layout_options',
				'default'		 => 1,
				'priority'		 => 10,
				'choices'		 => array(
					'on'	 => esc_attr__( 'Enable', 'reign' ),
					'off'	 => esc_attr__( 'Disable', 'reign' ),
				),
			);

			$fields[] = array(
				'type'			 => 'dimension',
				'settings'		 => 'site_container_width',
				'label'			 => esc_attr__( 'Site Container Width', 'reign' ),
				'description'	 => esc_attr__( 'Set the width of the container that holds the site area ( px or % ). Default is 1170px.', 'reign' ),
				'section'		 => 'reign_site_layout_options',
				'default'		 => '1170px',
				'priority'		 => 10,
				'transport'		 => 'auto',
				'output'		 => array(
					array(
						'element'	 => '.container, .container-fluid, .reign-stretched_view .footer-wrap .container, .reign-stretched_view_no_title .footer-wrap .container, .reign-stretched_view .reign-fallback-header .container, .reign-stretched_view_no_title .reign-fallback-header .container',
						'function'	 => 'css',
						'property'	 => 'max-width',
					),
				),
			);

			$fields[] = array(
				'type'			 => 'dimension',
				'settings'		 => 'site_sidebar_width',
				'label'			 => esc_attr__( 'Site Sidebar Width', 'reign' ),
				'description'	 => esc_attr__( 'Set the width of the sidebar ( px or % ). Default is 28.125%.', 'reign' ),
				'section'		 => 'reign_site_layout_options',
				'default'		 => '28.125%',
				'priority'		 => 10,
				'transport'		 => 'auto',
				'output'		 => array(
					array(
						'element'	 => '.site-content .widget-area',
						'function'	 => 'css',
						'property'	 => 'max-width',
					),
				),
			);

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Site_Layout.
 * @return Reign_Kirki_Site_Layout
 */
Reign_Kirki_Site_Layout::instance();
