<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Lock_Pro
 * @subpackage Buddypress_Lock_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Lock_Pro
 * @subpackage Buddypress_Lock_Pro/admin
 * @author     wbcomdesigns <admin@wbcomdesigns.com>
 */
class Buddypress_Lock_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {
		// if($hook != 'wbcom_page_buddypress-private-community-pro') {
		// 	return;
		// }
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Lock_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Lock_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-lock-pro-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name.'-selectize-css', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {
		// if($hook != 'wbcom_page_buddypress-private-community-pro') {
		// 	return;
		// }
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Lock_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Lock_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name.'-selectize-js', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-lock-pro-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register admin menu for plugin.
	 *
	 * @since    1.0.0
	 */
	public function blpro_add_admin_menu() {
		
		if ( empty ( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			// add_menu_page( esc_html__( 'WBCOM', 'buddypress-private-community-pro' ), __( 'WBCOM', 'buddypress-private-community-pro' ), 'manage_options', 'wbcomplugins', array( $this, 'blpro_settings_page' ), BLPRO_PLUGIN_URL . 'admin/wbcom/assets/imgs/bulb.png', 59 );

			add_menu_page( esc_html__( 'WB Plugins', 'buddypress-private-community-pro' ), esc_html__( 'WB Plugins', 'buddypress-private-community-pro' ), 'manage_options', 'wbcomplugins', array( $this, 'blpro_settings_page' ), 'dashicons-lightbulb', 59 );
		 	add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'buddypress-private-community-pro' ), esc_html__( 'General', 'buddypress-private-community-pro' ), 'manage_options', 'wbcomplugins' );
			}
		add_submenu_page( 'wbcomplugins', esc_html__( 'BuddyPress Private Community Pro Settings Page', 'buddypress-private-community-pro' ), esc_html__( 'Community Pro', 'buddypress-private-community-pro' ), 'manage_options', 'buddypress-private-community-pro', array( $this, 'blpro_settings_page' ) );	
		// add_menu_page( __( 'BuddyPress Private Community Pro Settings Page', 'buddypress-private-community-pro' ), __( 'Community Pro', 'buddypress-private-community-pro' ), 'manage_options', 'buddypress-private-community-pro', array( $this, 'blpro_settings_page' ), 'dashicons-groups');
	}


	/**
	 * Callable function for admin menu setting page.
	 *
	 * @since    1.0.0
	 */
	public function blpro_settings_page(){
		$current = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
		?>

		<div class="wrap">
			<div class="blpro-header">
				<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				<h1 class="wbcom-plugin-heading">
					<?php esc_html_e( 'BuddyPress Private Community Pro Settings', 'buddypress-private-community-pro' ); ?>
				</h1>
			</div>
			<div class="wbcom-admin-settings-page">
		<?php
		$blpro_tabs = array(
			'general'        => __( 'Logged-Out user settings', 'buddypress-private-community-pro' ),
			'loggedin-settings' => __( 'Logged-In user settings', 'buddypress-private-community-pro' ),
			'member-groups'  => __( 'Member Groups', 'buddypress-private-community-pro' ),
			'support'        => __( 'Support', 'buddypress-private-community-pro' ),
		);

		$tab_html = '<div class="wbcom-tabs-section"><h2 class="nav-tab-wrapper">';
		foreach ( $blpro_tabs as $blpro_tab => $blpro_name ) {
			$class     = ( $blpro_tab == $current ) ? 'nav-tab-active' : '';
			$tab_html .= '<a class="nav-tab ' . $class . '" href="admin.php?page=buddypress-private-community-pro&tab=' . $blpro_tab . '">' . $blpro_name . '</a>';
		}
		$tab_html .= '</h2></div>';
		echo $tab_html;
		include 'inc/blpro-tabs-options.php';
		echo '</div>';
		echo '</div>';
	}

	public function blpro_admin_register_settings(){
		if(isset($_POST['blpro_nl_settings'])){
			bp_update_option('blpro_nl_settings',$_POST['blpro_nl_settings']);
		}
		if(isset($_POST['blpro_login_settings'])){
			bp_update_option('blpro_login_settings',$_POST['blpro_login_settings']);
		}
		if(isset($_POST['blpro_groups_settings'])){
			bp_update_option('blpro_groups_settings',$_POST['blpro_groups_settings']);
		}
		if(isset($_POST['blpro_nl_settings']) || isset($_POST['blpro_login_settings']) || isset($_POST['blpro_groups_settings'])){
			wp_redirect($_POST['_wp_http_referer']);
			exit();
		}
	}
}
