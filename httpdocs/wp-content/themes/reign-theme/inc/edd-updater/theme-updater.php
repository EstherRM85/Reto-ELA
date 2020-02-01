<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */
// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Reign_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
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
		'theme-license'             => __( 'Theme License', 'reigntm' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'reigntm' ),
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