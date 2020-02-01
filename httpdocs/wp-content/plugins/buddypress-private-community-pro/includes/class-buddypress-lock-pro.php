<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Lock_Pro
 * @subpackage Buddypress_Lock_Pro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Buddypress_Lock_Pro
 * @subpackage Buddypress_Lock_Pro/includes
 * @author     wbcomdesigns <admin@wbcomdesigns.com>
 */
class Buddypress_Lock_Pro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Buddypress_Lock_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BLPRO_NAME_VERSION' ) ) {
			$this->version = BLPRO_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'buddypress-private-community-pro';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Buddypress_Lock_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Buddypress_Lock_Pro_i18n. Defines internationalization functionality.
	 * - Buddypress_Lock_Pro_Admin. Defines all hooks for the admin area.
	 * - Buddypress_Lock_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-lock-pro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-lock-pro-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-buddypress-lock-pro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-buddypress-lock-pro-public.php';

		/* Enqueue wbcom plugin folder file. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-admin-settings.php';

		/* Enqueue wbcom plugin folder file. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-paid-plugin-settings.php';
		
		$this->loader = new Buddypress_Lock_Pro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Buddypress_Lock_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Buddypress_Lock_Pro_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Buddypress_Lock_Pro_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( bp_core_admin_hook(), $plugin_admin, 'blpro_add_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'blpro_admin_register_settings' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Buddypress_Lock_Pro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode( 'blpro_login_form', $plugin_public, 'blpro_login_form_template' );
		$this->loader->add_filter( 'template_include', $plugin_public, 'blpro_lock_wordpress_pages' );
		$this->loader->add_filter( 'template_include', $plugin_public, 'blpro_lock_cpt_pages',999, 1 );
		$this->loader->add_filter( 'single_template', $plugin_public, 'blpro_lock_cpt_single', 999, 1 );
		$this->loader->add_filter( 'bp_located_template', $plugin_public, 'blpro_lock_bp_components', 999, 2 );
		$this->loader->add_filter( 'pre_get_posts', $plugin_public, 'blpro_exclude_search' );
		$this->loader->add_action( 'wp_ajax_nopriv_blpro_login', $plugin_public, 'blpro_login' );
		$this->loader->add_action( 'wp_ajax_nopriv_blpro_register', $plugin_public, 'blpro_register' );
		
		/*logged in user settings hooks*/
		$this->loader->add_filter( 'bp_ajax_querystring', $plugin_public, 'blpro_hide_admin_plus_other_users', 20, 2 );
		$this->loader->add_filter( 'bp_core_get_active_member_count', $plugin_public, 'blpro_members_count_at_directory', 10, 1);
		$this->loader->add_action( 'bp_profile_header_meta', $plugin_public, 'blpro_bp_profile_header_meta' );
		$this->loader->add_action( 'bp_core_xprofile_settings_before_submit', $plugin_public, 'blpro_profile_visibility_settings' );
		$this->loader->add_action( 'bp_init', $plugin_public, 'blpro_custom_bp_init' );
		$this->loader->add_action( 'bp_init', $plugin_public, 'blpro_tol_start', 0 );

		/*friend button filter*/
		$this->loader->add_filter( 'bp_get_add_friend_button', $plugin_public, 'blpro_bp_get_add_friend_button', 10, 1 );
		/*private message button filter*/
		$this->loader->add_filter( 'bp_get_send_message_button', $plugin_public, 'blpro_bp_get_send_message_button', 10, 1 );
		/*public mesaage button filter*/
		$this->loader->add_filter( 'bp_get_send_public_message_button', $plugin_public, 'blpro_bp_get_send_public_message_button', 10, 1 );
		/*comment functionality*/
		$this->loader->add_filter( 'bp_activity_can_comment', $plugin_public, 'blpro_bp_activity_can_comment', 10, 2 );
		/*disable posting*/
		$this->loader->add_filter( 'bp_get_template_part', $plugin_public, 'blpro_bp_get_template_part', 10, 3 );
		/*remove create a group tab*/
		$this->loader->add_filter( 'bp_get_group_create_nav_item', $plugin_public, 'blpro_bp_get_group_create_nav_item', 10, 1 );
		
		/*remove group join button*/
		$this->loader->add_filter( 'bp_get_group_join_button', $plugin_public, 'blpro_bp_get_group_join_button', 10, 2 );
		
		/*remove send invites tab from members page*/
		$this->loader->add_action( 'bp_groups_user_can_send_invites', $plugin_public, 'blpro_bp_groups_user_can_send_invites', 10, 4 );
		
		/*reset accept group invite if limit is reached*/
		$this->loader->add_action( 'groups_accept_invite', $plugin_public, 'blpro_groups_accept_invite', 10, 3 );
		
		/* remove create group content if limit is reached */
		$this->loader->add_filter( 'bp_user_can_create_groups', $plugin_public, 'blpro_bp_user_can_create_groups', 10, 2);
		$this->loader->add_filter( 'bp_actions', $plugin_public, 'blpro_bp_actions');
		
		$this->loader->add_filter( 'groups_member_user_id_before_save', $plugin_public, 'blpro_groups_member_user_id_before_save',10,1);
		/*restrict compose message url*/
		$this->loader->add_filter( 'bp_actions', $plugin_public, 'blpro_restrict_compose_message');

		$this->loader->add_action( 'bp_before_member_body', $plugin_public, 'blpro_profile_visility_home_override_plugin_code');

		$this->loader->add_filter( 'blpro_profile_visility_home_override', $plugin_public, 'blpro_override_members_home_file', 10, 1 );
		
		$this->loader->add_action( 'admin_init', $plugin_public, 'blpro_remove_ajax_join_group_request',13 );

		/* Hide buddypress members primary nav*/
		$this->loader->add_action( 'bp_actions', $plugin_public, 'blpro_hide_desired_members_primary_nav', 100 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Buddypress_Lock_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
