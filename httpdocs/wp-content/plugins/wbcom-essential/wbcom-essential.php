<?php
/**
 * Plugin Name: Wbcom Essential
 * Description: Wbcom Essential Addons.
 * Plugin URI: https://wbcomdesigns.com/
 * Author: Wbcom Designs
 * Version: 2.1.0
 * Author URI: https://wbcomdesigns.com/
 *
 * Text Domain: wbcom-essential
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WBCOM_ELEMENTOR_ADDONS_VERSION', '2.5.14' );
define( 'WBCOM_ELEMENTOR_ADDONS_PREVIOUS_STABLE_VERSION', '2.5.14' );

define( 'WBCOM_ELEMENTOR_ADDONS__FILE__', __FILE__ );
define( 'WBCOM_ELEMENTOR_ADDONS_PLUGIN_BASE', plugin_basename( WBCOM_ELEMENTOR_ADDONS__FILE__ ) );
define( 'WBCOM_ELEMENTOR_ADDONS_PATH', plugin_dir_path( WBCOM_ELEMENTOR_ADDONS__FILE__ ) );
define( 'WBCOM_ELEMENTOR_ADDONS_MODULES_PATH', WBCOM_ELEMENTOR_ADDONS_PATH . 'modules/' );
define( 'WBCOM_ELEMENTOR_ADDONS_URL', plugins_url( '/', WBCOM_ELEMENTOR_ADDONS__FILE__ ) );
define( 'WBCOM_ELEMENTOR_ADDONS_ASSETS_URL', WBCOM_ELEMENTOR_ADDONS_URL . 'assets/' );
define( 'WBCOM_ELEMENTOR_ADDONS_MODULES_URL', WBCOM_ELEMENTOR_ADDONS_URL . 'modules/' );
define( 'WBCOM_ELEMENTOR_ADDONS_PLUGIN_FILE', __FILE__ );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */

function wbcom_elementor_addons_load_plugin() {
	
	load_plugin_textdomain( 'wbcom-essential', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'wbcom_elementor_addons_fail_load' );
		return;
	}

	$elementor_version_required = '2.5.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'wbcom_elementor_addons_fail_load_out_of_date' );
		return;
	}

	$elementor_version_recommendation = '2.5.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'wbcom_elementor_addons_admin_notice_upgrade_recommendation' );
	}

	require( WBCOM_ELEMENTOR_ADDONS_PATH . 'plugin.php' );

}
add_action( 'plugins_loaded', 'wbcom_elementor_addons_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wbcom_elementor_addons_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'WBCOM Elementor Addons not working because you need to activate the Elementor plugin.', 'wbcom-essential' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'wbcom-essential' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'WBCOM Elementor Addons not working because you need to install the Elementor plugin', 'wbcom-essential' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'wbcom-essential' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function wbcom_elementor_addons_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'WBCOM Elementor Addons not working because you are using an old version of Elementor.', 'wbcom-essential' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'wbcom-essential' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function wbcom_elementor_addons_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of Elementor is available. For better performance and compatibility of WBCOM Elementor Addons, we recommend updating to the latest version.', 'wbcom-essential' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'wbcom-essential' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

// Add the default posts on plugin activation
register_activation_hook( __FILE__, '_wb_create_elementor_custom_header_footer' );
function _wb_create_elementor_custom_header_footer() {
	require 'includes/global-header-footer-posttype.php';
	$global_header_footer_posttype = WBCOM_Elementor_Global_Header_Footer_PostType::instance();
	$global_header_footer_posttype->add_header_footer_post();

	/* importing dummy data */
	$global_header_footer_posttype->import_elementor_dummy_data();
}
