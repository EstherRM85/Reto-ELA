<?php
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( !class_exists( 'Wbcom_Kirki_Theme_Customizer' ) ) :

	/**
	 * Main Wbcom_Kirki_Theme_Customizer Class.
	 *
	 * @class Wbcom_Kirki_Theme_Customizer
	 * @version 1.0.0
	 */
	class Wbcom_Kirki_Theme_Customizer {

		/**
		 * Wbcom_Kirki_Theme_Customizer version.
		 *
		 * @var string
		 */
		public $version				 = '1.0.0';

		/**
		 * The single instance of the class.
		 *
		 * @var Wbcom_Kirki_Theme_Customizer
		 * @since 1.0.0
		 */
		protected static $_instance	 = null;

		/**
		 * Main Wbcom_Kirki_Theme_Customizer Instance.
		 *
		 * Ensures only one instance of Wbcom_Kirki_Theme_Customizer is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see INSTANTIATE_Wbcom_Kirki_Theme_Customizer()
		 * @return Wbcom_Kirki_Theme_Customizer - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Wbcom_Kirki_Theme_Customizer Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
			$this->includes();
			do_action( 'wbcom_kirki_theme_customizer_loaded' );
		}

		public function includes() {

			include_once 'inc/class-kirki-installer-section.php';

			include_once 'general-functions/general-functions.php';
			// remove_theme_mods();
			// reign_reset_customizer_to_default();

			include_once 'options/colors/class-reign-kirki-colors.php';

			include_once 'options/general/class-reign-kirki-typography.php';
			include_once 'options/general/class-reign-kirki-site-layout.php';
			include_once 'options/general/class-reign-kirki-sub-header.php';
			include_once 'options/general/class-reign-kirki-page-mapping.php';
			include_once 'options/general/class-reign-kirki-custom-code.php';

			include_once 'options/forms/class-reign-kirki-forms.php';

			include_once 'options/header/class-reign-kirki-header.php';

			include_once 'options/post-types/class-reign-kirki-post-types.php';

			include_once 'options/footer/class-reign-kirki-footer.php';

			include_once 'options/extras/class-reign-kirki-plugins-support.php';
		}

		/**
		 * Hook into actions and filters.
		 * @since  1.0.0
		 */
		private function init_hooks() {

			/**
			 * Configure Kirki to use the proper URL path.
			 *
			 * Kirki loads some files when in the customizer and therefore needs you to tell it exactly where these files are located.
			 */
			add_filter( 'kirki/config', array( $this, 'reign_kirki_configuration' ) );

			// add_action( 'wp_head', array( $this, 'apply_theme_color' ) );
		}

		public function apply_theme_color() {
			global $rtm_color_scheme;
			?>
			<style type="text/css">
				div#lm-course-archive-data.lm-wb-grid-view .lm-value a {
					color: <?php echo get_theme_mod( $rtm_color_scheme.'-'.'reign_colors_theme' ); ?>;
				}
				.lm-course-item-wrapper a.lm-course-readmore-button,
				.lm-course-item-wrapper a.lm-course-readmore-button:hover {
					background: <?php echo get_theme_mod( $rtm_color_scheme.'-'.'reign_colors_theme' ); ?>;
				}
			</style>
			<?php
		}

		public function reign_kirki_configuration() {
			return array( 'url_path' => get_template_directory_uri() . '/lib/kirki/' );
		}

	}

	endif;
/**
 * Main instance of Wbcom_Kirki_Theme_Customizer.
 */
Wbcom_Kirki_Theme_Customizer::instance();
return;

add_action( 'customize_register', 'superminimal_demo_panels_sections' );

function superminimal_demo_panels_sections( $wp_customize ) {
	/**
	 * Add Panel
	 */
	$wp_customize->add_panel( 'sitepoint_demo_panel', array(
		'priority'		 => 10,
		'title'			 => __( 'SitePoint Demo Panel', 'superminimal' ),
		'description'	 => __( 'Kirki integration for SitePoint demo', 'superminimal' ),
	) );

	//More code to come


	/**
	 * Add a Section for Site Text Colors
	 */
	$wp_customize->add_section( 'superminimal_text_colors', array(
		'title'			 => __( 'Site Text Colors', 'superminimal' ),
		'priority'		 => 10,
		'panel'			 => 'sitepoint_demo_panel',
		'description'	 => __( 'Section description.', 'superminimal' ),
	) );

	/**
	 * Add a Section for Site Layout
	 */
	$wp_customize->add_section( 'superminimal_site_layout', array(
		'title'			 => __( 'Site Layout', 'superminimal' ),
		'priority'		 => 10,
		'panel'			 => 'sitepoint_demo_panel',
		'description'	 => __( 'Section description.', 'superminimal' ),
	) );

	/**
	 * Add a Section for Footer Text
	 */
	$wp_customize->add_section( 'superminimal_footer_text', array(
		'title'			 => __( 'Footer Text', 'superminimal' ),
		'priority'		 => 10,
		'panel'			 => 'sitepoint_demo_panel',
		'description'	 => __( 'Section description.', 'superminimal' ),
	) );


	$wp_customize->add_section( 'typography', array(
		'title'			 => __( 'Typography', 'kirki' ),
		'priority'		 => 20,
		'panel'			 => 'sitepoint_demo_panel',
		'description'	 => __( 'Section description.', 'superminimal' ),
	) );
}

add_filter( 'kirki/fields', 'superminimal_demo_fields' );

function superminimal_demo_fields( $fields ) {
	//Add code here

	/**
	 * Add a Field to change the body text color in the Text Colors Section
	 */
	$fields[] = array(
		'type'			 => 'color',
		'settings'		 => 'superminimal_body_color',
		'label'			 => __( 'Body Color', 'superminimal' ),
		'description'	 => __( 'Description here', 'superminimal' ),
		'section'		 => 'superminimal_text_colors',
		'priority'		 => 10,
		'default'		 => '#555555',
		'output'		 => array(
			array(
				'element'	 => 'body, p',
				'property'	 => 'color'
			),
		),
		'transport'		 => 'postMessage',
		'js_vars'		 => array(
			array(
				'element'	 => 'body, p',
				'function'	 => 'css',
				'property'	 => 'color',
			),
		)
	);




	/**
	 * Add a Field to change the footer text only if checkbox is checked
	 */
	$fields[]	 = array(
		'type'			 => 'checkbox',
		'settings'		 => 'superminimal_reveal_footer_text',
		'label'			 => __( 'Change Footer Text', 'superminimal' ),
		'description'	 => __( 'Description here', 'superminimal' ),
		'section'		 => 'superminimal_footer_text',
		'default'		 => 0,
		'priority'		 => 10,
	);
	$fields[]	 = array(
		'type'			 => 'textarea',
		'settings'		 => 'superminimal_footer_text',
		'label'			 => __( 'Footer Text', 'superminimal' ),
		'description'	 => __( 'Add some text to the footer', 'superminimal' ),
		'section'		 => 'superminimal_footer_text',
		'default'		 => 'Superminimal Theme – Kirki Toolkit Demo for SitePoint',
		'priority'		 => 20,
		'required'		 => array(
			array(
				'setting'	 => 'superminimal_reveal_footer_text',
				'operator'	 => '==',
				'value'		 => 1,
			),
		),
		'transport'		 => 'postMessage',
		'js_vars'		 => array(
			array(
				'element'	 => '#dev-footer',
				'function'	 => 'html'
			),
		),
	);


	$fields[] = array(
		'type'			 => 'select',
		'settings'		 => 'font_family',
		'label'			 => __( 'Font-Family', 'kirki' ),
		'description'	 => __( 'Please choose a font for your site. This font-family will be applied to all elements on your page, including headers and body.', 'kirki' ),
		'section'		 => 'typography',
		'default'		 => 'Roboto',
		'priority'		 => 10,
		'choices'		 => Kirki_Fonts::get_font_choices(),
		'output'		 => array(
			array(
				'element'	 => 'body, h1, h2, h3, h4, h5, h6',
				'property'	 => 'font-family',
			),
		),
		'transport'		 => 'postMessage',
		'js_vars'		 => array(
			array(
				'element'	 => 'body, h1, h2, h3, h4, h5, h6',
				'function'	 => 'css',
				'property'	 => 'font-family',
			),
		),
	);


	$fields[] = array(
		'type'			 => 'slider',
		'settings'		 => 'font_size',
		'label'			 => __( 'Font-Size', 'kirki' ),
		'description'	 => __( 'Please choose a font-size for your body.', 'kirki' ),
		'section'		 => 'typography',
		'default'		 => 1,
		'priority'		 => 20,
		'choices'		 => array(
			'min'	 => .7,
			'max'	 => 2,
			'step'	 => .01
		),
		'output'		 => array(
			array(
				'element'	 => 'body',
				'property'	 => 'font-size',
				'units'		 => 'em',
			),
		),
		'transport'		 => 'postMessage',
		'js_vars'		 => array(
			array(
				'element'	 => 'body, h1, h2, h3, h4, h5, h6',
				'function'	 => 'css',
				'property'	 => 'font-size',
			),
		),
	);



	return $fields;
}
