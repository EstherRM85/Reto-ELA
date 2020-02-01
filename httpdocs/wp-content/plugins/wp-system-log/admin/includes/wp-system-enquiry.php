<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( isset( $_POST['wpsl-submit-enquiry'] ) && wp_verify_nonce( $_POST['wpsl-send-enquiry-nonce'], 'wpsl-enquiry' ) ) {
	$email = sanitize_text_field( $_POST['wpsl-support-agent-email'] );

	if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
		$err_msg  = "<div class='error' id='message'>";
		$err_msg .= '<p>' . __( 'Please provide a valid support agent email.', 'wp-system-log' ) . '</p>';
		$err_msg .= '</div>';
		echo $err_msg;
	} else {
		global $wpdb;
		$message = sanitize_text_field( $_POST['wpsl-enquiry-message'] );
		$subject = __( 'Need Help!', 'wp-system-log' );

		$multisite  = ( is_multisite() ) ? 'Yes' : 'No';
		$debug_mode = ( WP_DEBUG ) ? 'On' : 'Off';
		$wp_cron    = ( _get_cron_array() ) ? 'Yes' : 'No';

		// Server Software.
		$server_software = '-';
		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server_software = $_SERVER['SERVER_SOFTWARE'];
		}

		// Server Name.
		$server_name = '-';
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$server_name = $_SERVER['HTTP_HOST'];
		}

		// cURL Info.
		$curl_ver = curl_version();
		if ( ! empty( $curl_ver ) ) {
			$curl = $curl_ver['version'] . ' ' . $curl_ver['ssl_version'];
		} else {
			$curl = '-';
		}

		// Suhosin Installed.
		$suhosin = true;
		if ( ! extension_loaded( 'suhosin' ) ) {
			$suhosin = false;
		}

		$suhosin_status = ( $suhosin ) ? 'Yes' : 'No';
		if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
			$curls = true;
		} else {
			$curls = false;
		}
		$curl_status = ( $curls ) ? 'Yes' : 'No';

		if ( class_exists( 'DOMDocument' ) ) {
			$dom = true;
		} else {
			$dom = false;
		}
		$dom_status = ( $dom ) ? 'Yes' : 'No';

		if ( class_exists( 'SoapClient' ) ) {
			$soap = true;
		} else {
			$soap = false;
		}
		$soap_status = ( $soap ) ? 'Yes' : 'No';

		if ( extension_loaded( 'mbstring' ) ) {
			$mbstring = true;
		} else {
			$mbstring = false;
		}
		$mbstring_status = ( $mbstring ) ? 'Yes' : 'No';

		$gzip_status = 'No';
		if ( is_callable( 'gzopen' ) ) {
			$gzip_status = 'Yes';
		}

		$link        = mysqli_connect( $wpdb->dbhost, $wpdb->dbuser, $wpdb->dbpassword );
		$db_password = ( $wpdb->dbpassword ) ? $wpdb->dbpassword : 'No Password';

		/**
		 * Get Plugins Environment Details
		 */
		$all_plugins = get_plugins();
		$plugin_str  = '';
		$count       = 0;
		foreach ( $all_plugins as $index => $plugin ) {
			$plugin_status = ( $index ) ? 'Active' : '';
			$odd           = 'background-color: #e0e0e0;';
			$style         = ( ++$count % 2 ? $odd : '' );
			$plugin_str   .= '<tr style="' . $style . '"><td style="padding: 10px;">' . '<b>Plugin Name : </b>' . $plugin['Name'] . '  ' . '<b>Plugin by : </b>' . $plugin['Author'] . '   ' . '<b>Version :  </b>' . $plugin['Version'] . '  ' . '<b>Status : </b>' . $plugin_status . '</td></tr>';
		}

		$cust_email = get_option( 'admin_email' );

		$htmlContent = '
		<html>
			<head>
				<title>' . __( 'Need to help', 'wp-system-log' ) . '</title>
			</head>
			<body>
				<h3>' . __( 'Customer Information', 'wp-system-log' ) . '</h3>
				<table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%; height: 90px;">
					<tr style="background-color: #e0e0e0;">
						<th>Email:</th><td>' . $cust_email . '</td>
					</tr>
					<tr>
						<th>Message:</th><td>' . $message . '</td>
					</tr>
				</table>

				<h3>' . __( 'WordPress Environment', 'wp-system-log' ) . '</h3>
				<table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%; height: 90px;">
					<tr>
						<th style="padding: 10px;">Home URL :</th><td>' . home_url() . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">Site URL:</th><td>' . get_site_url() . '</td>
					</tr>
					<tr>
                        <th style="padding: 10px;">WordPress Version:</th><td>' . get_bloginfo( 'version' ) . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
                        <th style="padding: 10px;">WordPress MultiSite:</th><td>' . $multisite . '</td>
					</tr>
					<tr>
                        <th style="padding: 10px;">WordPress Memory Limit:</th><td>' . WP_MEMORY_LIMIT . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
                        <th style="padding: 10px;">WordPress Debug Mode:</th><td>' . $debug_mode . '</td>
					</tr>
					<tr>
                        <th style="padding: 10px;">WordPress Cron:</th><td>' . $wp_cron . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">Language:</th><td>' . get_locale() . '</td>
					</tr>
				</table>

				<h3>' . __( 'Server Environment', 'wp-system-log' ) . '</h3>
				<table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%; height: 90px;">
					<tr>
						<th style="padding: 10px;">Server Info :</th><td>' . $server_software . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">Server Name:</th><td>' . $server_name . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">Current IP:</th><td>' . $_SERVER['REMOTE_ADDR'] . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">PHP Version:</th><td>' . PHP_VERSION . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">PHP Post Max Size:</th><td>' . ini_get( 'post_max_size' ) . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">PHP Time Limit:</th><td>' . ini_get( 'max_execution_time' ) . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">PHP Max Input Vars:</th><td>' . ini_get( 'max_input_vars' ) . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">cURL available:</th><td>' . $curl_status . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">cURL Version:</th><td>' . $curl . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">SUHOSIN Installed:</th><td>' . $suhosin_status . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">Max Upload Size:</th><td>' . ini_get( 'upload_max_filesize' ) . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">Default Timezone:</th><td>' . date_default_timezone_get() . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">Dom Available:</th><td>' . $dom_status . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">Soap Client:</th><td>' . $soap_status . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">Gzip:</th><td>' . $gzip_status . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">Multibyte String:</th><td>' . $mbstring_status . '</td>
					</tr>
				</table>

				<h3>' . __( 'WordPress Database Environment', 'wp-system-log' ) . '</h3>
				<table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%; height: 90px;">
					<tr>
						<th style="padding: 10px;">MySql Version :</th><td>' . mysqli_get_server_info( $link ) . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">WPDB Prefix:</th><td>' . $wpdb->prefix . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">DB User:</th><td>' . $wpdb->dbuser . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">DB Password:</th><td>' . $db_password . '</td>
					</tr>
					<tr>
						<th style="padding: 10px;">DB Name:</th><td>' . $wpdb->dbname . '</td>
					</tr>
					<tr style="background-color: #e0e0e0;">
						<th style="padding: 10px;">DB Host:</th><td>' . $wpdb->dbhost . '</td>
					</tr>
				</table>

				<h3>' . __( 'Plugins Environment', 'wp-system-log' ) . '</h3>
				<table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%; height: 90px;">' . $plugin_str . '</table>
			</body>
		</html>';

		// Set content-type header for sending HTML email.
		$headers  = '';
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

		// Additional headers.
		$headers .= 'From: <' . get_option( 'admin_email' ) . '>' . "\r\n";
		$enquiry  = wp_mail( $email, $subject, $htmlContent, $headers );
		if ( $enquiry ) {
			$success_msg  = "<div class='updated' id='message'>";
			$success_msg .= '<p><strong>' . __( 'Enquiry submitted successfully!', 'wp-system-log' ) . '</strong></p>';
			$success_msg .= '</div>';
			echo $success_msg;
		} else {
			$err_msg  = "<div class='error' id='message'>";
			$err_msg .= '<p>' . __( 'Enquiry not sent due to some error. Please try again later.', 'wp-system-log' ) . '</p>';
			$err_msg .= '</div>';
			echo $err_msg;
		}
	}
}
?>
<div class="wpsl-enquiry">
	<p class="description"><?php esc_attr_e( 'This tab will let you get enquiry of all the data of the environment on which your site is running.', 'wp-system-log' ); ?></p>
	<div class="wpsl-enquiry-tbl">
		<form action="" method="POST">
			<table class="form-table">
				<tbody>
					<!-- SUPPORT AGENT EMAIL -->
					<tr>
						<th scope="row"><label for="support-agent-email"><?php _e( 'Support Agent Email', 'wp-system-log' ); ?></label></th>
						<td><input type="text" class="regular-text" name="wpsl-support-agent-email" required placeholder="<?php esc_attr_e( 'Email', 'wp-system-log' ); ?>"></td>
					</tr>

					<!-- MESSAGE -->
					<tr>
						<th scope="row"><label for="enquiry-message"><?php esc_attr_e( 'Message', 'wp-system-log' ); ?></label></th>
						<td><textarea placeholder="<?php esc_attr_e( 'Message', 'wp-system-log' ); ?>" name="wpsl-enquiry-message" id="wpsl-enquiry-message" rows="4" required></textarea></td>
					</tr>

					<!-- SUBMIT THE ENQUIRY -->
					<tr>
						<th scope="row"></th>
						<td>
							<?php wp_nonce_field( 'wpsl-enquiry', 'wpsl-send-enquiry-nonce' ); ?>
							<input type="submit" class="button button-secondary wpsl-submit-enquiry" name="wpsl-submit-enquiry" value="<?php esc_attr_e( 'Enquire', 'wp-system-log' ); ?>">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
