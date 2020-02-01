<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Typography' ) ) :

	/**
	 * @class Reign_Kirki_Typography
	 */
	class Reign_Kirki_Typography {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Typography
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Typography Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Typography is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Typography - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Typography Constructor.
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

			$wp_customize->add_panel(
			'reign_general_panel', array(
				'priority'		 => 15,
				'title'			 => __( 'General', 'reign' ),
				'description'	 => __( 'Kirki integration for SitePoint demo', 'reign' ),
			)
			);

			$wp_customize->add_section(
			'reign_typography', array(
				'title'			 => __( 'Typography', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_general_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			// $v = get_theme_mod( 'reign_body_typography' );
			// print_r($v);
			//
			//Font awesome version selection
			$fields[] = array(
				'type'			 => 'select',
				'settings'		 => 'reign_font_awesome',
				'label'			 => esc_attr__( 'Font Awesome', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select font awesome version for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => 'option-3',
				'priority'		 => 10,
				'multiple'		 => 1,
				'choices'		 => [
					'option-1'	 => esc_html__( 'Font Awesome v4.7', 'reign' ),
					'option-2'	 => esc_html__( 'Font Awesome v5', 'reign' ),
					'option-3'	 => esc_html__( 'Font Awesome v4.7 + v5', 'reign' ),
				],
			);

			$fields[] = array(
				'type'			 => 'typography',
				'settings'		 => 'reign_body_typography',
				'label'			 => esc_attr__( 'Body Font', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select all font properties of body tag for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => $default_value_set[ 'reign_body_typography' ],
				'priority'		 => 10,
				'output'		 => array(
					array(
						'element' => 'body',
					),
					array(
						'choice'	 => 'font-family',
						'element'	 => 'body, .peepso *:not(.fa):not(.fab):not(.fad):not(.fal):not(.far):not(.fas), .ps-lightbox *:not(.fa):not(.fab):not(.fad):not(.fal):not(.far):not(.fas), .ps-dialog *:not(.fa):not(.fab):not(.fad):not(.fal):not(.far):not(.fas), .ps-hovercard *:not(.fa):not(.fab):not(.fad):not(.fal):not(.far):not(.fas)',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => '.widget-area a:not(.button)',
						'property'	 => 'font-size',
					),
				),
				'transport'		 => 'postMessage',
				'js_vars'		 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => 'body',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => 'body',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => 'body',
						'property'	 => 'font-size',
					),
					array(
						'choice'	 => 'line-height',
						'element'	 => 'body',
						'property'	 => 'line-height',
					),
					array(
						'choice'	 => 'color',
						'element'	 => 'body',
						'property'	 => 'color',
					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => 'body',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => 'body',
						'property'	 => 'text-align',
					),
				)
			);

			$fields[] = array(
				'type'			 => 'typography',
				'settings'		 => 'reign_h1_typography',
				'label'			 => esc_attr__( 'Heading 1', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select all font properties of H1 tag for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => $default_value_set[ 'reign_h1_typography' ],
				'priority'		 => 10,
				'output'		 => array(
					array(
						'element' => 'h1',
					),
				),
				'transport'		 => 'postMessage',
				'js_vars'		 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => 'h1',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => 'h1',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => 'h1',
						'property'	 => 'font-size',
					),
					array(
						'choice'	 => 'line-height',
						'element'	 => 'h1',
						'property'	 => 'line-height',
					),
					array(
						'choice'	 => 'color',
						'element'	 => 'h1',
						'property'	 => 'color',
					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => 'h1',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => 'h1',
						'property'	 => 'text-align',
					),
				)
			);

			$fields[] = array(
				'type'			 => 'typography',
				'settings'		 => 'reign_h2_typography',
				'label'			 => esc_attr__( 'Heading 2', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select all font properties of H2 tag for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => $default_value_set[ 'reign_h2_typography' ],
				'priority'		 => 10,
				'output'		 => array(
					array(
						'element' => 'h2',
					),
				),
				'transport'		 => 'postMessage',
				'js_vars'		 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => 'h2',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => 'h2',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => 'h2',
						'property'	 => 'font-size',
					),
					array(
						'choice'	 => 'line-height',
						'element'	 => 'h2',
						'property'	 => 'line-height',
					),
					array(
						'choice'	 => 'color',
						'element'	 => 'h2',
						'property'	 => 'color',
					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => 'h2',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => 'h2',
						'property'	 => 'text-align',
					),
				)
			);

			$fields[] = array(
				'type'			 => 'typography',
				'settings'		 => 'reign_h3_typography',
				'label'			 => esc_attr__( 'Heading 3', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select all font properties of H3 tag for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => $default_value_set[ 'reign_h3_typography' ],
				'priority'		 => 10,
				'output'		 => array(
					array(
						'element' => 'h3',
					),
				),
				'transport'		 => 'postMessage',
				'js_vars'		 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => 'h3',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => 'h3',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => 'h3',
						'property'	 => 'font-size',
					),
					array(
						'choice'	 => 'line-height',
						'element'	 => 'h3',
						'property'	 => 'line-height',
					),
					array(
						'choice'	 => 'color',
						'element'	 => 'h3',
						'property'	 => 'color',
					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => 'h3',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => 'h3',
						'property'	 => 'text-align',
					),
				)
			);

			$fields[] = array(
				'type'			 => 'typography',
				'settings'		 => 'reign_h4_typography',
				'label'			 => esc_attr__( 'Heading 4', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select all font properties of H4 tag for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => $default_value_set[ 'reign_h4_typography' ],
				'priority'		 => 10,
				'output'		 => array(
					array(
						'element' => 'h4',
					),
				),
				'transport'		 => 'postMessage',
				'js_vars'		 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => 'h4',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => 'h4',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => 'h4',
						'property'	 => 'font-size',
					),
					array(
						'choice'	 => 'line-height',
						'element'	 => 'h4',
						'property'	 => 'line-height',
					),
					array(
						'choice'	 => 'color',
						'element'	 => 'h4',
						'property'	 => 'color',
					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => 'h4',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => 'h4',
						'property'	 => 'text-align',
					),
				)
			);


			$fields[] = array(
				'type'			 => 'typography',
				'settings'		 => 'reign_h5_typography',
				'label'			 => esc_attr__( 'Heading 5', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select all font properties of H5 tag for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => $default_value_set[ 'reign_h5_typography' ],
				'priority'		 => 10,
				'output'		 => array(
					array(
						'element' => 'h5',
					),
				),
				'transport'		 => 'postMessage',
				'js_vars'		 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => 'h5',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => 'h5',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => 'h5',
						'property'	 => 'font-size',
					),
					array(
						'choice'	 => 'line-height',
						'element'	 => 'h5',
						'property'	 => 'line-height',
					),
					array(
						'choice'	 => 'color',
						'element'	 => 'h5',
						'property'	 => 'color',
					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => 'h5',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => 'h5',
						'property'	 => 'text-align',
					),
				)
			);


			$fields[] = array(
				'type'			 => 'typography',
				'settings'		 => 'reign_h6_typography',
				'label'			 => esc_attr__( 'Heading 6', 'reign' ),
				'description'	 => esc_attr__( 'Allows you to select all font properties of H6 tag for your site.', 'reign' ),
				'section'		 => 'reign_typography',
				'default'		 => $default_value_set[ 'reign_h6_typography' ],
				'priority'		 => 10,
				'output'		 => array(
					array(
						'element' => 'h6',
					),
				),
				'transport'		 => 'postMessage',
				'js_vars'		 => array(
					array(
						'choice'	 => 'font-family',
						'element'	 => 'h6',
						'property'	 => 'font-family',
					),
					array(
						'choice'	 => 'variant',
						'element'	 => 'h6',
						'property'	 => 'font-weight',
					),
					array(
						'choice'	 => 'font-size',
						'element'	 => 'h6',
						'property'	 => 'font-size',
					),
					array(
						'choice'	 => 'line-height',
						'element'	 => 'h6',
						'property'	 => 'line-height',
					),
					array(
						'choice'	 => 'color',
						'element'	 => 'h6',
						'property'	 => 'color',
					),
					array(
						'choice'	 => 'text-transform',
						'element'	 => 'h6',
						'property'	 => 'text-transform',
					),
					array(
						'choice'	 => 'text-align',
						'element'	 => 'h6',
						'property'	 => 'text-align',
					),
				)
			);

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Typography.
 * @return Reign_Kirki_Typography
 */
Reign_Kirki_Typography::instance();
