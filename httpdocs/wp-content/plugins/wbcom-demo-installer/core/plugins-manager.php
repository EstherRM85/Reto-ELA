<?php if(! defined('ABSPATH')){ return; }

class WBCOM_Demo_Importer_Plugins_Manager {

	/**
	 * @var WBCOM_Demo_Importer_Plugins_Manager The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	var $plugins = array();

	/**
	 * @var TGM_Plugin_Activation Instance
	 */
	var $tgmpa;

	const WP_REPO_REGEX = '|^http[s]?://wordpress\.org/(?:extend/)?plugins/|';
	const IS_URL_REGEX = '|^http[s]?://|';
       

	/**
	 * Main WBCOM_Demo_Importer_Plugins_Manager Instance
	 *
	 * Ensures only one instance of WBCOM_Demo_Importer_Plugins_Manager is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WBCOM_Demo_Importer_Plugins_Manager()
	 * @return WBCOM_Demo_Importer_Plugins_Manager - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Main class constructor
	 */
	function __construct() {

		//register the plugins in our class
		add_action( 'init', array( $this ,'populate_plugins' ) );

		// Register Ajax actions
		add_action('wp_ajax_wbcom_manage_plugin_installation', array( $this, 'do_plugin_action' ) );

		add_action( 'tgmpa_register', array( $this, 'required_plugins' ) );

		

		//run code on class init
		// do_action( 'WBCOM_Demo_Importer_Plugins_Manager_init' );

		// add_filter( 'tgmpa_load', array( $this, 'tgmpa_load_hook' ) );
	}

	public function required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array();

		$plugins = $this->get_required_plugins();

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = array(
			'id'           => 'wbcom',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'plugins.php',            // Parent menu slug.
			'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);

		tgmpa( $plugins, $config );
	}

	
	public function populate_plugins() {

		include_once 'class-tgm-plugin-activation.php';

		$this->tgmpa = TGM_Plugin_Activation::get_instance();
		
		$this->tgmpa->populate_file_path();

		// $this->plugins = $this->tgmpa->plugins;
		// $this->plugins = $this->get_required_plugins();

		$get_required_plugins = $this->get_required_plugins();
		$_get_required_plugins = array();
		if( !empty( $get_required_plugins ) && is_array( $get_required_plugins ) ) {
			foreach ( $get_required_plugins as $key => $value ) {
				$_get_required_plugins[$value['slug']] = $value;
			}
		}
		$this->plugins = $_get_required_plugins;

	}

	public function tgmpa_load_hook() {
		return is_admin();
	}

	public function do_plugin_action() {

		$action		= !empty( $_POST['plugin_action'] ) ? $_POST['plugin_action'] 	: false;
		$slug		= !empty( $_POST['plugin_slug'] ) 			? $_POST['plugin_slug'] 			: false;
		
		$get_required_plugins = $this->get_required_plugins();
		$_get_required_plugins = array();
		foreach ( $get_required_plugins as $key => $value ) {
			$_get_required_plugins[$value['slug']] = $value;
		}
		$this->plugins = $_get_required_plugins;

		switch ( $action ) {
			case 'enable_plugin':
				$this->do_plugin_activate( $slug );
				break;
			case 'install_plugin':
				$this->do_plugin_install( $slug );
				break;
			default:
				break;
		}

	}

	/**
	 * Performs the plugin update
	 * @param string $slug [description]
	 */
	function do_plugin_update( $slug ) {

		$status = $this->get_plugin_status( $slug );

		$active = FALSE;
		if ( $this->is_plugin_active( $slug ) ) {
			$active = TRUE;
		}

		if( empty( $this->plugins[$slug] ) ){
			$status['error'] = 'We have no data about this plugin.';
			wp_send_json_error( $status );
		}

		if( $this->does_plugin_have_update( $slug ) ) {
			
			if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
			// Inject our info into the update transient.
			$source        				= $this->get_download_url( $slug );
			$to_inject                    = array( $slug => $this->plugins[ $slug ] );
			$to_inject[ $slug ]['source'] = $source;
			$this->inject_update_info( $to_inject );
			$result = $upgrader->upgrade( $this->plugins[ $slug ]['file_path']  );

			if ( is_wp_error( $result ) ) {
				$status['error'] = $result->get_error_message();
				wp_send_json_error( $status );
			}


			if ( $active === TRUE ) {
				$this->tgmpa->populate_file_path( $slug );
				$result = activate_plugin( $this->plugins[$slug]['file_path'] );
				if ( is_wp_error( $result ) ) {
					$status['error'] = wp_kses_post( $result->get_error_message() );
				}
			}

			// Return the status of the plugin
			$status = $this->get_plugin_status( $slug );
			wp_send_json_success( $status );
		}

		$status['error'] = 'The plugin does not have an update.';
		wp_send_json_error( $status );

	}

	/**
	 * Enable a child theme
	 * @param  string $slug The slug used in the addons config file for the child theme
	 * @return string A json formatted value
	 */
	function enable_child_theme( $slug ) {

		$status = $this->get_plugin_status( $slug );

		// Get all installed themes
		$current_installed_themes = wp_get_themes();
		// Get the themes currently installed
		$active_theme = wp_get_theme();
		$theme_folder_name = $active_theme->get_template();

		$child_theme = false;

		if( is_array( $current_installed_themes ) ){
			foreach ($current_installed_themes as $key => $theme_obj) {
				if( $theme_obj->get('Template') === $theme_folder_name ){
					$child_theme = $theme_obj;
				}
			}
		}

		if( $child_theme !== false ){
			switch_theme( $child_theme->get_stylesheet() );
			$status = $this->get_plugin_status( $slug );
		}

		wp_send_json_success( $status );
	}

	function install_child_theme( $slug ) {
		if( empty( $this->plugins[$slug] ) ){
			wp_send_json_error( array( 'error' => 'We don\'t know anything about this theme' ) );
		}

		$url = $this->get_download_url( $slug );
		$status = $this->get_plugin_status( $slug );

		if( ! current_user_can( 'install_themes' ) ){
			$status['error'] = 'You don\'t have permissions to install install_themes';
			wp_send_json_error( array( 'error' => '' ) );
		}

		if ( ! class_exists( 'Theme_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin = new Automatic_Upgrader_Skin();
		$upgrader = new Theme_Upgrader( $skin, array( 'clear_destination' => true ) );
		$result = $upgrader->install( $url );

		// There is a bug in WP where the install method can return null in case the folder already exists
		// see https://core.trac.wordpress.org/ticket/27365
		if( $result === null && ! empty( $skin->result ) ){
			$result = $skin->result;
		}

		if ( is_wp_error( $skin->result ) ) {
			$status['error'] = $result->get_error_message();
			wp_send_json_error( $status );
		}

		$status = $this->get_plugin_status( $slug );
		wp_send_json_success( $status );
	}

	/**
	 * Will check if a child theme is installed for the current theme
	 * @return boolean true/false if a child theme is installed or not
	 */
	function is_child_theme_installed(){

		// Get all installed themes
		$current_installed_themes = wp_get_themes();
		// Get the themes currently installed
		$active_theme = wp_get_theme();
		$theme_folder_name = $active_theme->get_template();

		if( is_array( $current_installed_themes ) ){
			foreach ($current_installed_themes as $key => $theme_obj) {
				if( $theme_obj->get('Template') === $theme_folder_name ){
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Checks if a child theme is active or not
	 * @return boolean If the child theme is in use
	 */
	function is_child_theme_active(){
		$active_theme = wp_get_theme();
		$template = $active_theme->get('Template');
		return ! empty( $template );
	}

	function get_addon_config( $plugin_slug ){
		if( ! empty( $this->plugins[ $plugin_slug ] ) ){
			return $this->plugins[ $plugin_slug ];
		}
	}

	/**
	 * Returns the status and actions for a plugin
	 * @param  string $plugin_slug The plugin slug
	 * @return array  The status and actions for the requested plugin
	 */
	function get_plugin_status( $plugin_slug ){

		$status = array();
		$plugin_config = $this->get_addon_config( $plugin_slug );

		if( isset( $plugin_config['addon_type'] ) && $plugin_config['addon_type'] === 'child_theme' ){
			// We have a theme
			if( $this->is_child_theme_installed() ){
				// Check if the theme is active or not
				if ( $this->is_child_theme_active() ) {
					$status['status']      = 'wbcom-active wbcom-addons-disabled';
					$status['status_text'] = __( 'Active', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action_text'] = __( 'Child theme installed and active', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']      = 'no_action';
				} else  {
					$status['status']      = 'wbcom-inactive';
					$status['status_text'] = __( 'Inactive', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action_text'] = __( 'Activate child theme', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']      = 'enable_child_theme';
				}
			}
			else{
				$status['status']      = 'wbcom-needs-install';
				$status['status_text'] = __( 'Not installed', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
				$status['action_text'] = __( 'Install child theme', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
				$status['action']      = 'install_theme';

				if( ! current_user_can( 'install_themes' ) ){
					$status['status']         = 'wbcom-not-installed wbcom-addons-disabled';
					$status['action_text']    = __( 'Permissions needed to install child themes. Contact site administrator.', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']         = 'contact_network_admin';
				}

			}
		}
		else{
			if( $this->is_plugin_installed( $plugin_slug ) ) {
				if ( $this->is_plugin_active( $plugin_slug ) ) {
					$status['status']         = 'wbcom-active';
					$status['status_text'] = __( 'Active', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action_text']           = __( 'Already Installed & Activated', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']         = 'disable_plugin';
				} else  {
					$status['status']         = 'wbcom-inactive';
					$status['status_text'] = __( 'Inactive', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action_text']           = __( 'Activate Plugin', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']         = 'enable_plugin';
				}
			}
			else{
				$status['status']         = 'wbcom-not-installed';
				$status['status_text'] = __( 'Not Installed', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
				$status['action_text']           = __( 'Install Plugin', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
				$status['action']         = 'install_plugin';

				if( ! current_user_can( 'install_plugins' ) ){
					$status['status']         = 'wbcom-not-installed wbcom-addons-disabled';
					$status['action_text']    = __( 'You don\'t have permission to install plugins. Contact site administrator.', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']         = 'contact_network_admin';
				}

			}
		}


		return $status;
	}

	/**
	 * Inject information into the 'update_plugins' site transient as WP checks that before running an update.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugins The plugin information for the plugins which are to be updated.
	 */
	public function inject_update_info( $plugins ) {
		$this->tgmpa->inject_update_info( $plugins );
	}

	/**
	 * Performs plugin update
	 * @return type
	 */
	function plugin_has_update( $slug ){
		if( empty( $this->plugins[$slug] ) ){
			return false;
		}

		$installed_version = $this->get_installed_version( $slug );
		$minimum_version   = $this->plugins[ $slug ]['version'];

		return version_compare( $minimum_version, $installed_version, '>' );

	}

	/**
	 * Performs plugins installation
	 * @param string $slug
	 * @param boolean $echo
	 * @return void | array
	 */
	function do_plugin_install( $slug, $echo = true ) {

		if( empty( $this->plugins[$slug] ) ) {
			return false;
		}

		$url = $this->get_download_url( $slug );
		
		$status = $this->get_plugin_status( $slug );

		if( ! current_user_can( 'install_plugins' ) ){
			$status['error'] = 'You don\'t have permissions to install plugins';
			if ( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.

		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, array() ) ) ) {
			return true;
		}

		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, array() ); // Setup WP_Filesystem.
			return true;
		}

		if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin = new Automatic_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin, array( 'clear_destination' => true ) );

		$result = $upgrader->install( $url );

		// There is a bug in WP where the install method can return null in case the folder already exists
		// see https://core.trac.wordpress.org/ticket/27365
		if( $result === null && ! empty( $skin->result ) ){
			$result = $skin->result;
		}

		if ( is_wp_error( $skin->result ) ) {
			$status['error'] = $result->get_error_message();
			if ( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$this->tgmpa->populate_file_path( $slug );
		$plugin_activate = $upgrader->plugin_info();
		$activate = activate_plugin( $plugin_activate );
		if ( is_wp_error( $activate ) ) {
			$status['error'] = wp_kses_post( $activate->get_error_message() );
			if ( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$status = $this->get_plugin_status( $slug );

		if ( $echo ) {
			wp_send_json_success( $status );
		} else {
			return $status;
		}

	}

	/**
	 * Performs a plugin deactivation
	 * @return type
	 */
	function do_plugin_deactivate( $slug ){

		$status = $this->get_plugin_status( $slug );

		if( empty( $this->plugins[$slug] ) ){
			$status['error'] = 'We have no data about this plugin.';
			wp_send_json_error( $status );
		}

		deactivate_plugins( $this->plugins[$slug]['file_path'] );

		$status = $this->get_plugin_status( $slug );
		wp_send_json_success( $status );

	}

	/**
	 * Performs plugins activation
	 * @param string $slug
	 * @param bool $echo
	 * @return void | array
	 */
	function do_plugin_activate( $slug, $echo = true ){

		$status = $this->get_plugin_status( $slug );

		if( empty( $this->plugins[$slug] ) ){
			$status['error'] = 'We have no data about this plugin.';
			if( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
		$result = activate_plugin( $plugin_file_path );

		if ( is_wp_error( $result ) ) {
			$status['error'] = $result->get_error_message();
			if( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$status = $this->get_plugin_status( $slug );
		if( $echo ) {
			wp_send_json_success( $status );
		} else {
			return $status;
		}
	}

	function _get_plugin_file_path_from_slug( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins_list = get_plugins();
		$keys = array_keys( $plugins_list );
		foreach ( $keys as $key ) {
			if ( preg_match( '|^' . $slug . '/|', $key ) ) {
				return $key;
			}
		}
		return $slug;
	}

	/**
	 * Returns the install url for the current plugin
	 * @param string $slug
	 * @return string
	 */
	public function get_download_url( $slug ) {
		$dl_source = '';

		if( isset( $this->plugins[ $slug ]['external_url'] ) && !empty( $this->plugins[ $slug ]['external_url'] ) ) {
			return $this->plugins[ $slug ]['external_url'];
			// $plugin_source_type = $this->_get_plugin_source_type( $this->plugins[ $slug ]['source'] );
		}
		else {
			$plugin_source_type = 'repo';
		}
		
		switch ( $plugin_source_type ) {
			case 'repo':
				return $this->get_wp_repo_download_url( $slug );
			case 'external':
				return $this->plugins[ $slug ]['source'];
			case 'bundled':
				return $this->tgmpa->default_path . $this->plugins[ $slug ]['source'];
		}

		return $dl_source; // Should never happen.
	}

	function _get_plugin_source_type( $source ) {
		if ( 'repo' === $source || preg_match( self::WP_REPO_REGEX, $source ) ) {
			return 'repo';
		} elseif ( preg_match( self::IS_URL_REGEX, $source ) ) {
			return 'external';
		} else {
			return 'bundled';
		}
	}

	function get_wp_repo_download_url( $slug ){
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); // for plugins_api..
		$api = plugins_api('plugin_information', array('slug' => $slug, 'fields' => array('sections' => false) ) ); //Save on a bit of bandwidth.
		if ( is_wp_error( $api ) ) {
			$status['error'] = $api->get_error_message();
			wp_send_json_error( $status );
		}

		return $api->download_link;
	}


	/**
	 * Check if a plugin is installed. Does not take must-use plugins into account.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 * @return bool True if installed, false otherwise.
	 */
	public function is_plugin_installed( $slug ) {

		return $this->tgmpa->is_plugin_installed( $slug );
	}

	/**
	 * Check whether there is an update available for a plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 * @return false|string Version number string of the available update or false if no update available.
	 */
	public function does_plugin_have_update( $slug ) {
		return $this->tgmpa->does_plugin_have_update( $slug );
	}

	/**
	 * Check if a plugin is active.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 * @return bool True if active, false otherwise.
	 */
	public function is_plugin_active( $slug ) {
		return $this->tgmpa->is_plugin_active( $slug );
	}

	/**
	 * Retrieve the version number of an installed plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 * @return string Version number as string or an empty string if the plugin is not installed
	 *                or version unknown (plugins which don't comply with the plugin header standard).
	 */
	public function get_installed_version( $slug ) {

		return $this->tgmpa->get_installed_version( $slug );
	}

	/**
	 * Wrapper around the core WP get_plugins function, making sure it's actually available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_folder Optional. Relative path to single plugin folder.
	 * @return array Array of installed plugins with plugin information.
	 */
	public function get_plugins( $plugin_folder = '' ) {
		return $this->tgmpa->get_plugins( $plugin_folder );
	}

	public function get_required_plugins() {
		if( !isset( $_GET['theme_slug'] ) ) {
			return get_option( 'wbcom_theme_demo_req_plugins', array() );
		}
		if( empty( get_option( 'wbcom_theme_demo_req_plugins', array() ) ) ) {
			// $url_to_request = WBCOM_Theme_Demo_Installer_URL_TO_REQUEST;
			if( !isset( $_GET['target_url'] ) ) { return array(); }
			
			// $url_to_request = $_GET['target_url'] . 'wp-admin/?wbcom_theme_demo_listing=yes';
			// $response = wp_remote_post( $url_to_request, array(
			// 	'method' => 'POST',
			// 	'timeout' => 45,
			// 	'headers' => array(),
			// 	'body' => array(
			// 		'theme_slug'	=> $_GET['theme_slug'],
			// 		'demo_slug'	=> $_GET['demo_slug'],
			// 		'plugins_list' => 'get_plugins_list',
			// 	)
			// ) );

			$url_to_request = WBCOM_Theme_Demo_Installer_PARENT_URL_TO_REQUEST . "plugins_json/" . $_GET['plugins_json_key'] . "/plugins.json";
			$response = wp_remote_get( $url_to_request, array( 'timeout' => 120 ) );

			if ( !is_wp_error( $response ) ) {
				if ( isset( $response['response']['code'] ) &&  ( $response['response']['code'] == 200 ) ) {
					$response = isset( $response['body'] ) ? $response['body'] : '';
					if( !empty( $response ) ) {
						$response = json_decode( $response, true );
					}
					if( !empty( $response ) && is_array( $response ) ) {
						update_option( 'wbcom_theme_demo_req_plugins', $response );
					}
				}
			}
		}
		return get_option( 'wbcom_theme_demo_req_plugins', array() );
	}

}

/**
 * Shortcut to WBCOM_Demo_Importer_Plugins_Manager class
 */
function instantiate_wbcom_demo_importer_plugins_manager(){
	return WBCOM_Demo_Importer_Plugins_Manager::instance();
}
instantiate_wbcom_demo_importer_plugins_manager();
?>