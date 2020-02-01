<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/includes
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
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Share {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Buddypress_Share_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * @access public
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name		 = 'buddypress-share';
		$this->version			 = '2.0.0';

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
	 * - Buddypress_Share_Loader. Orchestrates the hooks of the plugin.
	 * - Buddypress_Share_i18n. Defines internationalization functionality.
	 * - Buddypress_Share_Admin. Defines all hooks for the admin area.
	 * - Buddypress_Share_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-share-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-share-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-admin-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-buddypress-share-admin.php';
		/**
		 * The class responsible for defining custom settings
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-share-settings.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-buddypress-share-public.php';

		$this->loader = new Buddypress_Share_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Buddypress_Share_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Buddypress_Share_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'bp_share_load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin		 = new Buddypress_Share_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_page	 = new Buddypress_Share_Options_Page( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( bp_core_admin_hook(), $plugin_admin_page, 'bp_share_plugin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin_page, 'bp_share_settings_init' );
		$this->loader->add_action( 'wp_ajax_bp_share_insert_services_ajax', $plugin_admin_page, 'bp_share_insert_services_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_bp_share_insert_services_ajax', $plugin_admin_page, 'bp_share_insert_services_ajax' );
		$this->loader->add_action( 'wp_ajax_bp_share_delete_services_ajax', $plugin_admin_page, 'bp_share_delete_services_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_bp_share_delete_services_ajax', $plugin_admin_page, 'bp_share_delete_services_ajax' );
		$this->loader->add_action( 'wp_ajax_bp_share_chb_services_ajax', $plugin_admin_page, 'bp_share_chb_services_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_bp_share_chb_services_ajax', $plugin_admin_page, 'bp_share_chb_services_ajax' );
		$this->loader->add_action( 'wp_ajax_bp_share_delete_user_services_ajax', $plugin_admin_page, 'bp_share_delete_user_services_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_bp_share_delete_user_services_ajax', $plugin_admin_page, 'bp_share_delete_user_services_ajax' );
		$this->loader->add_action( 'bp_share_add_services_options', $plugin_admin_page, 'bp_share_add_options', 10, 2 );
		$this->loader->add_action( 'bp_share_user_services', $plugin_admin_page, 'bp_share_user_added_services', 10, 3 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Buddypress_Share_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'language_attributes', $plugin_public, 'bp_share_doctype_opengraph' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'bp_share_opengraph', 999 );
		$this->loader->add_action( 'bp_init', $plugin_public, 'bp_activity_share_button_dis' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 * @access public
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
	 * @access public
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @access public
	 * @return    Buddypress_Share_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @access public
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
