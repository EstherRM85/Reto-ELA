<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Theme_Options_Manager' ) ) :

	/**
	 * @class Reign_Theme_Options_Manager
	 */
	class Reign_Theme_Options_Manager {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Theme_Options_Manager
		 */
		protected static $_instance	 = null;
		protected static $_slug		 = 'reign_pages';

		/**
		 * Main Reign_Theme_Options_Manager Instance.
		 *
		 * Ensures only one instance of Reign_Theme_Options_Manager is loaded or can be loaded.
		 *
		 * @return Reign_Theme_Options_Manager - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Theme_Options_Manager Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
			$this->includes();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'admin_menu', array( $this, 'reign_settings_page_init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'render_vertical_skeleton_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'render_wp_core_scripts' ), 50 );
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {
			include_once 'get-started-options.php';
			// include_once 'reign-pages-options.php';
			include_once 'buddy-extender-options.php';
			include_once 'peepso-extender-options.php';
			include_once 'wbcom-support-tab.php';
		}

		public function reign_settings_page_init() {
			add_submenu_page(
			'reign-settings', __( 'Reign Settings', 'reign' ), __( 'Reign Settings', 'reign' ), 'manage_options', 'reign-options', array( $this, 'reign_settings_page' )
			);
		}

		public function reign_settings_page() {
			global $pagenow;
			$theme_data = wp_get_theme();
			?>
			<div class="wrap">
				<h2><?php echo $theme_data->get( 'Name' ) . __( ' Theme Settings', 'reign' ); ?></h2>
				<?php
				if ( isset( $_GET[ 'updated' ] ) && 'true' == esc_attr( $_GET[ 'updated' ] ) )
					echo '<div class="updated" ><p>' . __( ' Theme Settings updated.', 'reign' ) . '</p></div>';

				$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'get_started';
				$this->reign_admin_tabs( $tab );
				?>
				<div id="poststuff">
					<div class="reign-animation-container">
						<div class="reign-animate seven" id="reign-default-animation">
							<span>r</span>
							<span>e</span>
							<span>i</span>
							<span>g</span>
							<span>n</span>
						</div>
					</div>
					<form id="reign-theme-options-form" method="post" action="<?php admin_url( 'admin.php?page=reign-options' ); ?>" style="display:none;">
						<?php
						wp_nonce_field( "reign-options" );
						if ( $pagenow == 'admin.php' && $_GET[ 'page' ] == 'reign-options' ) {
							do_action( 'render_theme_options_page_for_' . $tab );
						}
						?>
					</form>
				</div>
				<?php do_action( 'render_content_after_form', $tab ); ?>
			</div>
			<?php
		}

		public function reign_admin_tabs( $current ) {
			$tabs	 = array();
			$tabs	 = apply_filters( 'alter_reign_admin_tabs', $tabs );

			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $tabs as $tab => $name ) {
				$class = ( $tab == $current ) ? ' nav-tab-active' : '';
				echo "<a class='nav-tab$class' href='?page=reign-options&tab=$tab'>$name</a>";
			}
			echo '</h2>';
		}

		public function render_vertical_skeleton_scripts() {
			// $screen = get_current_screen();
			// if ( $screen->id != 'reign-settings_page_reign-options' ) {
			// 	return;
			// }
			if ( (!isset( $_GET[ 'page' ] ) ) || ( 'reign-options' !== $_GET[ 'page' ] ) ) {
				return;
			}

			wp_register_script(
			$handle		 = 'reign_vertical_tabs_skeleton_js', $src		 = get_template_directory_uri() . '/assets/js/vertical-tabs-skeleton.js', $deps		 = array( 'jquery' ), $ver		 = time(), $in_footer	 = true
			);

			$wb_social_links_html	 = '';
			ob_start();
			?>
			<div class="wbtm_social_links_container">
				<div class="wbtm_social_link_section">
					<h3 class="wbtm_social_link_toggle_head">
						<?php _e( 'New Site', 'reign' ); ?>
					</h3>
					<div class="wbtm_social_link_info_box">
						<div class="img_section">
							<?php if ( class_exists( 'PeepSo' )) { ?>
								<input class="reign_default_cover_image_url" type="hidden" name="reign_peepsoextender[wbtm_social_links][{{unique_key}}][img_url]" value="<?php if( isset($social_link['img_url'])) { echo $social_link['img_url']; } ?>" required="required" />
							<?php } else { ?>
								<input class="reign_default_cover_image_url" type="hidden" name="reign_buddyextender[wbtm_social_links][{{unique_key}}][img_url]" value="<?php echo $social_link[ 'img_url' ]; ?>" required="required" />
							<?php } ?>	
							<img class="reign_default_cover_image" src="<?php if( isset($social_link['img_url'])) { echo $social_link[ 'img_url' ]; }?>" style="display: none;" />
							<input id="reign-upload-button" type="button" class="button reign-upload-button" value="<?php _e( 'Upload Icon', 'reign' ); ?>" />
							<a href="#" class="reign-remove-file-button" rel="avatar_default_image" style="display: none;" >
								<?php _e( 'Remove Icon', 'reign' ); ?>
							</a>
						</div>
						<div class="name_section">
							<?php if ( class_exists( 'PeepSo' )) { ?>
								<input type="text" class="wbtm-social-link-inp" name="reign_peepsoextender[wbtm_social_links][{{unique_key}}][name]" placeholder="<?php _e( 'New Site', 'reign' ); ?>" required="required" />
							<?php } else { ?>
								<input type="text" class="wbtm-social-link-inp" name="reign_buddyextender[wbtm_social_links][{{unique_key}}][name]" placeholder="<?php _e( 'New Site', 'reign' ); ?>" required="required" />
							<?php } ?> 	
						</div>
						<div class="del_section">
							<button><?php _e( 'Delete', 'reign' ); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
			$wb_social_links_html	 = ob_get_clean();
			wp_localize_script(
			'reign_vertical_tabs_skeleton_js', 'reign_vertical_tabs_skeleton_js_params', array(
				'ajax_url'					 => admin_url( 'admin-ajax.php' ),
				'home_url'					 => get_home_url(),
				'wb_social_links_html'		 => $wb_social_links_html,
				'wb_social_links_default'	 => __( 'New Site', 'reign' ),
			)
			);
			wp_enqueue_script( 'reign_vertical_tabs_skeleton_js' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			if ( !wp_script_is( 'jquery-ui-accordion', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery-ui-accordion' );
			}
			if ( !wp_script_is( 'jquery-ui-sortable', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

			$css_path = is_rtl() ? '/assets/css/rtl' : '/assets/css';

			wp_register_style(
			$handle	 = 'reign-vertical-tabs-skeleton-css', $src	 = get_template_directory_uri() . $css_path . '/vertical-tabs-skeleton.css', $deps	 = array(), $ver	 = time(), $media	 = 'all'
			);
			wp_enqueue_style( 'reign-vertical-tabs-skeleton-css' );

			wp_register_style(
			$handle	 = 'reign-tooltip-css', $src	 = get_template_directory_uri() . $css_path . '/reign-tooltip.css', $deps	 = array(), $ver	 = time(), $media	 = 'all'
			);
			wp_enqueue_style( 'reign-tooltip-css' );
		}

		public function render_wp_core_scripts() {
			$screen = get_current_screen();
			if ( $screen->id != 'reign-settings_page_reign-options' ) {
				return;
			}

			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-ui-accordion' );
			wp_register_script(
			$handle		 = 'reign-media-lib-uploader-js', $src		 = get_template_directory_uri() . '/assets/js/reign-media-lib-uploader.js', $deps		 = array( 'jquery', 'wp-color-picker', 'jquery-ui-accordion' ), $ver		 = time(), $in_footer	 = true
			);
			wp_enqueue_script( 'reign-media-lib-uploader-js' );
		}

	}

	endif;

/**
 * Main instance of Reign_Theme_Options_Manager.
 * @return Reign_Theme_Options_Manager
 */
Reign_Theme_Options_Manager::instance();
?>