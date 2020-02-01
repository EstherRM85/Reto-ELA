<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Get_Started_Options' ) ) :

	/**
	 * @class Reign_Get_Started_Options
	 */
	class Reign_Get_Started_Options {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Get_Started_Options
		 */
		protected static $_instance	 = null;
		protected static $_slug		 = 'get_started';

		/**
		 * Main Reign_Get_Started_Options Instance.
		 *
		 * Ensures only one instance of Reign_Get_Started_Options is loaded or can be loaded.
		 *
		 * @return Reign_Get_Started_Options - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Get_Started_Options Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_filter( 'alter_reign_admin_tabs', array( $this, 'alter_reign_admin_tabs' ), 10, 1 );
			add_action( 'render_content_after_form', array( $this, 'render_get_started_with_customization_section' ), 10, 1 );
		}

		public function alter_reign_admin_tabs( $tabs ) {
			$tabs[ self::$_slug ] = __( 'Get Started', 'reign' );
			return $tabs;
		}

		public function render_get_started_with_customization_section( $tab ) {
			if( $tab != self::$_slug ) { return; }
			?>
			<style type="text/css">
				div#poststuff {
					display: none;
				}
			</style>
			<?php	
			if( class_exists( 'WBCOM_Elementor_Global_Header_Footer' ) ) {
				$global_header_footer = WBCOM_Elementor_Global_Header_Footer::instance();
				$header_pid	 = $global_header_footer->get_hf_post_id( 'reign-elemtr-header' );
				$footer_pid	 = $global_header_footer->get_hf_post_id( 'reign-elemtr-footer' );
			}

			$theme_options_quick_links = array();
			$theme_options_quick_links['site_logo']	= array(
				'option_title'	=>	__( 'Upload Your Logo', 'reign' ),
				'option_desc'	=>	__( 'Add your own logo here.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[control]=custom_logo&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);
			$theme_options_quick_links['site_icon']	= array(
				'option_title'	=>	__( 'Add Your Favicon', 'reign' ),
				'option_desc'	=>	__( 'The favicon is used as a browser and app icon for your website.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[control]=site_icon&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);
			
			$theme_options_quick_links['typography']	= array(
				'option_title'	=>	__( 'Set Your Typography', 'reign' ),
				'option_desc'	=>	__( 'Choose your own typography for any parts of your website.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[section]=reign_typography&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);

			$theme_options_quick_links['page_mapping']	= array(
				'option_title'	=>	__( 'Let\'s Map Pages', 'reign' ),
				'option_desc'	=>	__( 'Map login, register and 404 page with custom pages.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[section]=reign_page_mapping&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);

			$theme_options_quick_links['site_layout']	= array(
				'option_title'	=>	__( 'Switch Site Layout', 'reign' ),
				'option_desc'	=>	__( 'Switch site layout between default view and boxed view.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[control]=reign_site_layout&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);

			
			$theme_options_quick_links['colors']	= array(
				'option_title'	=>	__( 'Pick Your Colors', 'reign' ),
				'option_desc'	=>	__( 'Replace the default primary and hover color by your own colors.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[section]=colors&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);

			$theme_options_quick_links['site_header']	= array(
				'option_title'	=>	__( 'Play With Header', 'reign' ),
				'option_desc'	=>	__( 'Manage the look of your header in all way possible.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[panel]=reign_header_panel&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);

			$theme_options_quick_links['site_footer']	= array(
				'option_title'	=>	__( 'Play With Footer', 'reign' ),
				'option_desc'	=>	__( 'Manage the copyright text, widgets and colors for footer.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[panel]=reign_footer_panel&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);


			$theme_options_quick_links['wp_login_screen']	= array(
				'option_title'	=>	__( 'Design WP Login Screen', 'reign' ),
				'option_desc'	=>	__( 'Play with colors and images to give your default login screen a brand oriented look.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[section]=reign_wp_login_screen_panel&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);

			$theme_options_quick_links['custom_code']	= array(
				'option_title'	=>	__( 'Add Custom Code', 'reign' ),
				'option_desc'	=>	__( 'Add google analytics or facebook pixel tracking code here. Add custom javascript code also.', 'reign' ),
				'link_title'	=>	__( 'Go To The Option', 'reign' ),
				'link_url'	=>	esc_url( admin_url( 'customize.php?autofocus[section]=reign_custom_code&return=' . admin_url( 'admin.php?page=reign-options' ) ) ),
			);


			if( post_type_exists( 'reign-elemtr-header' ) ) {
				$theme_options_quick_links['reign-elemtr-header'] = array(
					'option_title'	=>	__( 'Manage Elementor Header', 'reign' ),
					'option_desc'	=>	__( 'Choose the elements, style, height and colors for your site header made using Elementor.', 'reign' ),
					'link_title'	=>	__( 'Go To The Option', 'reign' ),
					'link_url'	=>	esc_url( admin_url( 'edit.php?post_type=reign-elemtr-header' ) ),
				);
			}
			if( post_type_exists( 'reign-elemtr-footer' ) ) {
				$theme_options_quick_links['reign-elemtr-footer'] = array(
					'option_title'	=>	__( 'Manage Elementor Footer', 'reign' ),
					'option_desc'	=>	__( 'Choose the elements, style, height and colors for your site footer using Elementor.', 'reign' ),
					'link_title'	=>	__( 'Go To The Option', 'reign' ),
					'link_url'	=>	esc_url( admin_url( 'edit.php?post_type=reign-elemtr-footer' ) ),
				);
			}

			$theme_options_quick_links = apply_filters( 'reign_alter_theme_options_quick_links', $theme_options_quick_links );

			?>
			<div class="reign-option-section">
				<div class="reign-option-info">
					<h1><?php _e( 'Getting started with customization', 'reign' ); ?></h1>
					<p><?php _e( 'Begin customizing the website to give it a unique look.', 'reign' ); ?></p>
				</div>	
				<div class="reign-option-boxes">	
					<?php
					foreach ( $theme_options_quick_links as $key => $theme_option ) {
						?>
						<div class="reign-option-box">
							<div class="option-wrapper">
								<h3 class="option-title"><?php echo $theme_option['option_title']; ?></h3>
								<p class="option-desc"><?php echo $theme_option['option_desc']; ?></p>
								<div class="option-link-area">
									<a class="option-link" href="<?php echo esc_url($theme_option['link_url']); ?>" target="_blank"><?php echo $theme_option['link_title']; ?></a>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>	
			<?php
		}

	}

	endif;

/**
 * Main instance of Reign_Get_Started_Options.
 * @return Reign_Get_Started_Options
 */
Reign_Get_Started_Options::instance();
