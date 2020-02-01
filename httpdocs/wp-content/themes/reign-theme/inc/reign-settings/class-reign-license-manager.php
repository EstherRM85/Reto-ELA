<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_License_Manager' ) ) :

	/**
	 * @class Reign_License_Manager
	 */
	class Reign_License_Manager {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_License_Manager
		 */
		protected static $_instance	 = null;
		protected static $_slug		 = 'license-manager';

		/**
		 * Main Reign_License_Manager Instance.
		 *
		 * Ensures only one instance of Reign_License_Manager is loaded or can be loaded.
		 *
		 * @return Reign_License_Manager - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_License_Manager Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_filter( 'alter_reign_admin_tabs', array( $this, 'alter_reign_admin_tabs' ), 45, 1 );
			add_action( 'render_content_after_form', array( $this, 'render_get_started_with_customization_section' ), 10, 1 );

			add_action( 'admin_menu', array( $this, 'add_reign_setting_submenu' ), 45 );
		}

		public function add_reign_setting_submenu() {
			add_submenu_page(
				'reign-settings',
				__( 'License', 'reign' ),
				__( 'License', 'reign' ),
				'manage_options',
				admin_url( 'admin.php?page=reign-options&tab=' . self::$_slug )
			);
		}

		public function alter_reign_admin_tabs( $tabs ) {
			$tabs[ self::$_slug ] = __( 'License', 'reign' );
			return $tabs;
		}

		public function render_get_started_with_customization_section( $tab ) {
			if( $tab != self::$_slug ) { return; }

			// Includes the files needed for the theme updater
			if( !class_exists( 'EDD_Reign_Theme_Updater_Admin' ) ) {
				include( REIGN_INC_DIR . 'edd-updater/theme-updater-admin.php' );
			}

			// Loads the updater classes
			$updater = new EDD_Reign_Theme_Updater_Admin(
				// Config settings
				$config = array(
					'remote_api_url' => 'https://wbcomdesigns.com/', // Site where EDD is hosted
					'item_name'      => 'Reign BuddyPress Theme', // Name of theme
					'theme_slug'     => 'reign-buddypress-theme', // Theme slug
					'version'        => REIGN_THEME_VERSION, // The current version of this theme
					'author'         => 'Wbcom Designs', // The author of this theme
					'download_id'    => '', // Optional, used for generating a license renewal link
					'renew_url'      => '', // Optional, allows for a custom license renewal link
					'beta'           => false, // Optional, set to true to opt into beta versions
				),
				// Strings
				$strings = array(
					'theme-license'             => __( 'Reign Theme - License', 'reigntm' ),
					'enter-key'                 => __( 'Enter your license key.', 'reigntm' ),
					'license-key'               => __( 'License Key', 'reigntm' ),
					'license-action'            => __( 'License Action', 'reigntm' ),
					'deactivate-license'        => __( 'Deactivate License', 'reigntm' ),
					'activate-license'          => __( 'Activate License', 'reigntm' ),
					'status-unknown'            => __( 'License status is unknown.', 'reigntm' ),
					'renew'                     => __( 'Renew?', 'reigntm' ),
					'unlimited'                 => __( 'unlimited', 'reigntm' ),
					'license-key-is-active'     => __( 'License key is active.', 'reigntm' ),
					'expires%s'                 => __( 'Expires %s.', 'reigntm' ),
					'expires-never'             => __( 'Lifetime License.', 'reigntm' ),
					'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'reigntm' ),
					'license-key-expired-%s'    => __( 'License key expired %s.', 'reigntm' ),
					'license-key-expired'       => __( 'License key has expired.', 'reigntm' ),
					'license-keys-do-not-match' => __( 'License keys do not match.', 'reigntm' ),
					'license-is-inactive'       => __( 'License is inactive.', 'reigntm' ),
					'license-key-is-disabled'   => __( 'License key is disabled.', 'reigntm' ),
					'site-is-inactive'          => __( 'Site is inactive.', 'reigntm' ),
					'license-status-unknown'    => __( 'License status is unknown.', 'reigntm' ),
					'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'reigntm' ),
					'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'reigntm' ),
				)
			);

			echo '<div class="reign_support_faq">';
				$updater->license_page();
			echo '</div>';

			?>
			<style type="text/css">
				/*div#poststuff {
					display: none;
				}*/

				.reign_support_faq{
					background: #fff;
    				padding: 40px;
    				overflow: hidden;
				}
				
			</style>
			
			<?php

			do_action( 'reign_other_premium_addon_license_panel' );
		}

	}

	endif;

/**
 * Main instance of Reign_License_Manager.
 * @return Reign_License_Manager
 */
Reign_License_Manager::instance();