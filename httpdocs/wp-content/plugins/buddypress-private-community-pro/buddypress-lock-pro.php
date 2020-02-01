<?php

/**
 * The plugin bootstrap file
 *
 * This plugin makes your BuddyPress community private. You can control which areas of your
 * site are accessible to logged out and logged in users in two different settings.Site
 * admin can also configure groups restrictions settings.
 *
 * @link              http://www.wbcomdesigns.com
 * @since             1.0.0
 * @package           Buddypress_Lock_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       BuddyPress Private Community Pro
 * Plugin URI:        http://www.wbcomdesigns.com/plugins/
 * Description:       This plugin makes your BuddyPress community private. You can control which areas of your site are accessible to logged out and logged in users in two different settings.Site admin can also configure groups restrictions settings.
 * Version:           1.7.3
 * Author:            wbcomdesigns
 * Author URI:        http://www.wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       buddypress-private-community-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BLPRO_NAME_VERSION', '1.7.3' );
define( 'BLPRO_DIR', dirname( __FILE__ ) );
define( 'BLPRO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BLPRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BP_LOCK_PRO_PLUGIN_BASENAME',  plugin_basename( __FILE__ ) );
if ( ! defined( 'BLPRO_PLUGIN_FILE' ) ) {
	define( 'BLPRO_PLUGIN_FILE', __FILE__ );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buddypress-lock-pro-activator.php
 */
function activate_buddypress_lock_pro() {
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buddypress-lock-pro-deactivator.php
 */
function deactivate_buddypress_lock_pro() {
}

register_activation_hook( __FILE__, 'activate_buddypress_lock_pro' );
register_deactivation_hook( __FILE__, 'deactivate_buddypress_lock_pro' );

/**
 * Include needed files if required plugin is active
 *  @since   1.0.0
 *  @author  Wbcom Designs
 */
add_action( 'bp_include', 'blpro_plugin_init' );
function blpro_plugin_init(){
	if ( bp_lock_pro_check_config() ){
		require plugin_dir_path( __FILE__ ) . 'includes/class-buddypress-lock-pro.php';
		require plugin_dir_path(__FILE__) . 'edd-license/edd-plugin-license.php';
		run_buddypress_lock_pro();
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'blpro_admin_page_link' );
	}
}

function bp_lock_pro_check_config(){
	global $bp;
	$config = array(
		'blog_status'    => false,
		'network_active' => false,
		'network_status' => true
	);
	if ( get_current_blog_id() == bp_get_root_blog_id() ) {
		$config['blog_status'] = true;
	}

	$network_plugins = get_site_option( 'active_sitewide_plugins', array() );

	// No Network plugins
	if ( empty( $network_plugins ) )

	// Looking for BuddyPress and bp-activity plugin
	$check[] = $bp->basename;
	$check[] = BP_LOCK_PRO_PLUGIN_BASENAME;

	// Are they active on the network ?
	$network_active = array_diff( $check, array_keys( $network_plugins ) );

	// If result is 1, your plugin is network activated
	// and not BuddyPress or vice & versa. Config is not ok
	if ( count( $network_active ) == 1 )
		$config['network_status'] = false;

	// We need to know if the plugin is network activated to choose the right
	// notice ( admin or network_admin ) to display the warning message.
	$config['network_active'] = isset( $network_plugins[ BP_LOCK_PRO_PLUGIN_BASENAME ] );

	// if BuddyPress config is different than bp-activity plugin
	if ( !$config['blog_status'] || !$config['network_status'] ) {

		$warnings = array();
		if ( !bp_core_do_network_admin() && !$config['blog_status'] ) {
			add_action( 'admin_notices', 'bplock_same_blog' );
			$warnings[] = __( 'BuddyPress Private Community Pro requires to be activated on the blog where BuddyPress is activated.', 'buddypress-private-community-pro' );
		}

		if ( bp_core_do_network_admin() && !$config['network_status'] ) {
			add_action( 'admin_notices', 'bplock_same_network_config' );
			$warnings[] = __( 'BuddyPress Private Community Pro and BuddyPress need to share the same network configuration.', 'buddypress-private-community-pro' );
		}

		if ( ! empty( $warnings ) ) :
			return false;
		endif;
	}
	return true;
}

function bplock_same_blog(){
	echo '<div class="error"><p>'
	. esc_html( __( 'BuddyPress Private Community Pro requires to be activated on the blog where BuddyPress is activated.', 'buddypress-private-community-pro' ) )
	. '</p></div>';
}

function bplock_same_network_config(){
	echo '<div class="error"><p>'
	. esc_html( __( 'BuddyPress Private Community Pro and BuddyPress need to share the same network configuration.', 'buddypress-private-community-pro' ) )
	. '</p></div>';
}

/**
 * Settings link for this plugin
 *  @since   1.0.0
 *  @author  Wbcom Designs
 */
function blpro_admin_page_link( $links ) {
	$page_link = array( '<a href="' . admin_url( 'admin.php?page=buddypress-private-community-pro' ) . '">' . __( 'Settings', 'buddypress-private-community-pro' ) . '</a>' );
	return array_merge( $links, $page_link );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_buddypress_lock_pro() {

	$plugin = new Buddypress_Lock_Pro();
	$plugin->run();

}
