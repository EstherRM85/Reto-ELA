<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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

// Current IP.
$current_ip = '-';
if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
	$current_ip = $_SERVER['REMOTE_ADDR'];
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

if ( is_callable( 'gzopen' ) ) {
	$gzip = true;
} else {
	$gzip = false;
}
$gzip_status = ( $gzip ) ? 'Yes' : 'No';

if ( date_default_timezone_get() == 'UTC' ) {
	$date_default_timezone = 'Yes';
} else {
	$date_default_timezone = 'No';
}
?>
<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding your server which is helping run your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-server-log-tbl">
		<table class="form-table">
			<tbody>
				<!-- SERVER INFO. -->
				<tr>
					<th scope="row"><label for="server-info"><?php esc_attr_e( 'Server Info', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $server_software; ?></p></td>
				</tr>

				<!-- SERVER NAME. -->
				<tr>
					<th scope="row"><label for="server-name"><?php esc_attr_e( 'Server Name', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $server_name; ?></p></td>
				</tr>

				<!-- CURRENT IP ADDRESS. -->
				<tr>
					<th scope="row"><label for="current-ip"><?php esc_attr_e( 'Current I.P. Adresss ', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $current_ip; ?></p></td>
				</tr>

				<!-- PHP VERSION. -->
				<tr>
					<th scope="row"><label for="php-version"><?php esc_attr_e( 'PHP Version', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo PHP_VERSION; ?></p></td>
				</tr>

				<!-- POST MAX. SIZE. -->
				<tr>
					<th scope="row"><label for="post-max-size"><?php esc_attr_e( 'Post Max. Size', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo ini_get( 'post_max_size' ); ?></p></td>
				</tr>

				<!-- MAX. EXECUTION TIME. -->
				<tr>
					<th scope="row"><label for="max-execution-time"><?php esc_attr_e( 'Max. Execution Time', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo ini_get( 'max_execution_time' ); ?></p></td>
				</tr>

				<!-- MAX. INPUT VARS. -->
				<tr>
					<th scope="row"><label for="max-input-vars"><?php esc_attr_e( 'Max. Input Vars', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo ini_get( 'max_input_vars' ); ?></p></td>
				</tr>

				<!-- fsockopen/curl. -->
				<tr>
					<th scope="row"><label for="fsockopen-curl"><?php esc_attr_e( 'fsockopen/curl', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( $curls ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>

				<!-- cURL VERSION. -->
				<tr>
					<th scope="row"><label for="curl-version"><?php esc_attr_e( 'cURL Version', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $curl; ?></p></td>
				</tr>

				<!-- SUHOSIN INSTALLED. -->
				<tr>
					<th scope="row"><label for="suhosin-installed"><?php esc_attr_e( 'SUHOSIN Installed', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( $suhosin ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>

				<!-- MAX. UPLOAD SIZE. -->
				<tr>
					<th scope="row"><label for="max-upload-size"><?php esc_attr_e( 'Max. Upload Size', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo ini_get( 'upload_max_filesize' ); ?></p></td>
				</tr>

				<!-- DEFAULT TIMEZONE. -->
				<tr>
					<th scope="row"><label for="default-timezone"><?php esc_attr_e( 'Default Timezone', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo date_default_timezone_get(); ?></p></td>
				</tr>

				<!-- SOAP CLIENT. -->
				<tr>
					<th scope="row"><label for="soap-client"><?php esc_attr_e( 'SOAP Client', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( $soap ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>

				<!-- MULTIBYTE STRING. -->
				<tr>
					<th scope="row"><label for="multibyte-string"><?php esc_attr_e( 'Multibyte String', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( $mbstring ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>

				<!-- DOM. -->
				<tr>
					<th scope="row"><label for="multibyte-string"><?php esc_attr_e( 'DOM', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( $dom ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>

				<!-- GZIP. -->
				<tr>
					<th scope="row"><label for="multibyte-string"><?php esc_attr_e( 'Gzip', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( $gzip ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
