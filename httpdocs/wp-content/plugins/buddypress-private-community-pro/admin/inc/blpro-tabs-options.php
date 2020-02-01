<?php
/**
 *
 * This template file is used for fetching desired options page file at admin settings.
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_GET['tab'] ) ) {
	$blpro_tab = sanitize_text_field( $_GET['tab'] );
} else {
	$blpro_tab = 'general';
}

blpro_include_setting_tabs( $blpro_tab );

/**
 *
 * Function to select desired file for tab option.
 *
 * @param string $blpro_tab The current tab string.
 */
function blpro_include_setting_tabs( $blpro_tab ) {

	switch ( $blpro_tab ) {
		case 'general':
			include 'blpro-general-setting-tab.php';
			break;
		case 'loggedin-settings':
			include 'blpro-loggedin-setting-tab.php';
			break;
		case 'member-groups':
			include 'blpro-member-groups-setting-tab.php';
			break;
		case 'support':
			include 'blpro-support-setting-tab.php';
			break;
		default:
			include 'blpro-general-setting-tab.php';
			break;
	}

}

