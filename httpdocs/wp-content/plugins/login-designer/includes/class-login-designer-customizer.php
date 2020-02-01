<?php
/**
 * Customizer functionality
 *
 * @package Login Designer
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Login_Designer_Customizer' ) ) :

	/**
	 * Enqueues JS & CSS assets
	 */
	class Login_Designer_Customizer {

		/**
		 * The class constructor.
		 */
		public function __construct() {
			add_action( 'body_class', array( $this, 'body_class' ) );
			add_action( 'login_body_class', array( $this, 'body_class' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ), 11 );
		}

		/**
		 * Adds the associated template to the body.
		 *
		 * @access public
		 * @param array $classes Existing body classes to be filtered.
		 */
		public function body_class( $classes ) {
			if ( is_customize_preview() ) {
				$classes[] = 'customize-partial-edit-shortcuts-shown';
			}

			if ( is_customize_preview() && is_page_template( 'template-login-designer.php' ) ) {
				$classes[] = 'login-designer';
			}

			return $classes;
		}

		/**
		 * Register Customizer Settings.
		 *
		 * @param WP_Customize_Manager $wp_customize the Customizer object.
		 */
		public function customize_register( $wp_customize ) {

			// Return early if the class is missing.
			if ( ! class_exists( 'Login_Designer_Customizer_Output' ) ) {
				return;
			}

			// Add custom controls.
			require_once LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR . 'class-login-designer-range-control.php';
			require_once LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR . 'class-login-designer-toggle-control.php';
			require_once LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR . 'class-login-designer-template-control.php';
			require_once LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR . 'class-login-designer-title-control.php';
			require_once LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR . 'class-login-designer-gallery-control.php';
			require_once LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR . 'class-login-designer-upgrade-control.php';
			require_once LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR . 'class-login-designer-license-control.php';

			// Register the control types that we're using as JavaScript controls.
			if ( class_exists( 'Login_Designer_Toggle_Control' ) ) {
				$wp_customize->register_control_type( 'Login_Designer_Toggle_Control' );
			}
			if ( class_exists( 'Login_Designer_Range_Control' ) ) {
				$wp_customize->register_control_type( 'Login_Designer_Range_Control' );
			}
			if ( class_exists( 'Login_Designer_Title_Control' ) ) {
				$wp_customize->register_control_type( 'Login_Designer_Title_Control' );
			}
			if ( class_exists( 'Login_Designer_Gallery_Control' ) ) {
				$wp_customize->register_control_type( 'Login_Designer_Gallery_Control' );
			}
			if ( class_exists( 'Login_Designer_Template_Control' ) ) {
				$wp_customize->register_control_type( 'Login_Designer_Template_Control' );
			}

			// Get the default options.
			$defaults = new Login_Designer_Customizer_Output();
			$defaults = $defaults->defaults();

			// Get the admin default options.
			$admin_defaults = new Login_Designer_Customizer_Output();
			$admin_defaults = $admin_defaults->admin_defaults();

			/**
			 * Add the main panel and sections.
			 */
			$wp_customize->add_panel(
				'login_designer',
				array(
					'title'       => esc_html__( 'Login Designer', 'login-designer' ),
					'capability'  => 'edit_theme_options',
					'description' => esc_html__( 'Click the Templates icon at the top left of the preview window to change your template. To customize further, simply click on any element, or it\'s corresponding shortcut icon, to edit it\'s styling. ', 'login-designer' ),
					'priority'    => 150,
				)
			);

			// Style Editor (Initially hidden from the Customizer).
			$wp_customize->add_section(
				'login_designer__section--styles',
				array(
					'title' => esc_html__( 'Styles', 'login-designer' ),
					'panel' => 'login_designer',
				)
			);

			// Templates.
			$wp_customize->add_section(
				'login_designer__section--templates',
				array(
					'title' => esc_html__( 'Templates', 'login-designer' ),
					'panel' => 'login_designer',
				)
			);

			// Settings.
			$wp_customize->add_section(
				'login_designer__section--settings',
				array(
					'title' => esc_html__( 'Settings', 'login-designer' ),
					'panel' => 'login_designer',
				)
			);

			/**
			 * Add the theme upgrade section, only if the pro version is available.
			 *
			 * @see https://github.com/justintadlock/trt-customizer-pro
			 */
			if ( Login_Designer()->has_pro() ) {
				$wp_customize->register_section_type( 'Login_Designer_Upgrade_Control' );

				// Retrieve the Login Designer shop URL.
				$url = Login_Designer()->get_store_url(
					'extensions',
					array(
						'utm_medium'   => 'login-designer-lite',
						'utm_source'   => 'customizer',
						'utm_campaign' => 'extensions-section',
						'utm_content'  => 'discover-add-ons',
					)
				);

				$wp_customize->add_section(
					new Login_Designer_Upgrade_Control(
						$wp_customize,
						'upgrade',
						array(
							'type'     => 'upgrade',
							'panel'    => 'login_designer',
							'title'    => esc_html__( 'Extensions', 'login-designer' ),
							'pro_text' => esc_html__( 'Discover Add-ons', 'login-designer' ),
							'pro_url'  => $url,
						)
					)
				);
			}

			/**
			 * Add sections.
			 */
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/templates.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/logo.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/background.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/form.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/fields.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/labels.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/button.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/remember.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/checkbox.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/below.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/license.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/settings/branding.php';
		}

		/**
		 * Sanitize Checkbox.
		 *
		 * @param string|bool $checked Customizer option.
		 */
		public function sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true === $checked ) ? true : false );
		}

		/**
		 * Image sanitization callback.
		 *
		 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
		 * send back the filename, otherwise, return the setting default.
		 *
		 * - Sanitization: image file extension
		 * - Control: text, WP_Customize_Image_Control
		 *
		 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
		 *
		 * @param string|string        $image Image filename.
		 * @param WP_Customize_Setting $setting Setting instance.
		 * @return string The image filename if the extension is allowed; otherwise, the setting default.
		 */
		public static function sanitize_image( $image, $setting ) {

			// The array includes image mime types that are included in wp_get_mime_types().
			$mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
				'bmp'          => 'image/bmp',
				'tif|tiff'     => 'image/tiff',
				'ico'          => 'image/x-icon',
			);

			// Return an array with file extension and mime_type.
			$file = wp_check_filetype( $image, $mimes );

			// If $image has a valid mime_type, return it; otherwise, return the default.
			return ( $file['ext'] ? $image : $setting->default );
		}

		/**
		 * Returns an array of layout choices.
		 *
		 * @param array|array $choices Template option.
		 */
		public static function get_choices( $choices ) {
			$layouts                 = $choices;
			$layouts_control_options = array();

			foreach ( $layouts as $layout => $value ) {
				$layouts_control_options[ $layout ] = $value;
			}

			return $layouts_control_options;
		}

		/**
		 * Get background images.
		 */
		public static function get_background_images() {
			$image_dir = LOGIN_DESIGNER_PLUGIN_URL . 'assets/images/backgrounds/';

			$backgrounds = array(
				'none'  => esc_url( $image_dir ) . '00.jpg',
				'bg_01' => esc_url( $image_dir ) . '01-sml.jpg',
				'bg_02' => esc_url( $image_dir ) . '02-sml.jpg',
				'bg_03' => esc_url( $image_dir ) . '03-sml.jpg',
				'bg_04' => esc_url( $image_dir ) . '04-sml.jpg',
				'bg_05' => esc_url( $image_dir ) . '05-sml.jpg',
				'bg_06' => esc_url( $image_dir ) . '06-sml.jpg',
				'bg_07' => esc_url( $image_dir ) . '07-sml.jpg',
				'bg_08' => esc_url( $image_dir ) . '08-sml.jpg',
				'bg_09' => esc_url( $image_dir ) . '09-sml.jpg',
			);

			return apply_filters( 'login_designer_backgrounds', $backgrounds );
		}

		/**
		 * Returns the background choices.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public static function get_background_choices() {
			$choices = array(
				'repeat'   => array(
					'no-repeat' => esc_html__( 'No Repeat', 'login-designer' ),
					'repeat'    => esc_html__( 'Tile', 'login-designer' ),
					'repeat-x'  => esc_html__( 'Tile Horizontally', 'login-designer' ),
					'repeat-y'  => esc_html__( 'Tile Vertically', 'login-designer' ),
				),
				'size'     => array(
					'auto'    => esc_html__( 'Auto', 'login-designer' ),
					'cover'   => esc_html__( 'Cover', 'login-designer' ),
					'contain' => esc_html__( 'Contain', 'login-designer' ),
				),
				'position' => array(
					'left top'      => esc_html__( 'Left Top', 'login-designer' ),
					'left center'   => esc_html__( 'Left Center', 'login-designer' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'login-designer' ),
					'right top'     => esc_html__( 'Right Top', 'login-designer' ),
					'right center'  => esc_html__( 'Right Center', 'login-designer' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'login-designer' ),
					'center top'    => esc_html__( 'Center Top', 'login-designer' ),
					'center center' => esc_html__( 'Center Center', 'login-designer' ),
					'center bottom' => esc_html__( 'Center Bottom', 'login-designer' ),
				),
				'attach'   => array(
					'fixed'  => esc_html__( 'Fixed', 'login-designer' ),
					'scroll' => esc_html__( 'Scroll', 'login-designer' ),
				),
			);

			return apply_filters( 'login_designer_background_choices', $choices );
		}

		/**
		 * Returns an array of Google Font options
		 *
		 * @return array of font styles.
		 */
		public static function get_fonts() {
			$fonts = array(
				'default'             => esc_html__( 'Default', 'login-designer' ),
				'Abril Fatface'       => 'Abril Fatface',
				'Georgia'             => 'Georgia',
				'Helvetica'           => 'Helvetica',
				'Lato'                => 'Lato',
				'Lora'                => 'Lora',
				'Karla'               => 'Karla',
				'Josefin Sans'        => 'Josefin Sans',
				'Montserrat'          => 'Montserrat',
				'Open Sans'           => 'Open Sans',
				'Oswald'              => 'Oswald',
				'Overpass'            => 'Overpass',
				'Poppins'             => 'Poppins',
				'PT Sans'             => 'PT Sans',
				'Roboto'              => 'Roboto',
				'Fira Sans Condensed' => 'Fira Sans',
				'Times New Roman'     => 'Times New Roman',
				'Nunito'              => 'Nunito',
				'Merriweather'        => 'Merriweather',
				'Rubik'               => 'Rubik',
				'Playfair Display'    => 'Playfair Display',
				'Spectral'            => 'Spectral',
			);

			return apply_filters( 'login_designer_fonts', $fonts );
		}
	}

endif;

new Login_Designer_Customizer();
