<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $wpdb;

$link = mysqli_connect( $wpdb->dbhost, $wpdb->dbuser, $wpdb->dbpassword );
if ( mysqli_connect_errno() ) {
	$link = '';
}

$link = mysqli_connect( $wpdb->dbhost, $wpdb->dbuser, $wpdb->dbpassword );
?>
<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding your WordPress Database which is storing data of your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-wp-wpdb-tbl">
		<table class="form-table">
			<tbody>
				<!-- MYSQL VERSION. -->
				<tr>
					<th scope="row"><label for="mysql-version"><?php esc_attr_e( 'MySql Version', 'wp-system-log' ); ?></label></th>
					<td><p class="">
						<?php
						if ( mysqli_connect_errno() ) {
							echo $link;
						} else {
							echo mysqli_get_server_info( $link );
						}
						?>
					</p></td>
				</tr>

				<!-- WPDB PREFIX. -->
				<tr>
					<th scope="row"><label for="wpdb-prefix"><?php esc_attr_e( 'WPDB Prefix', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $wpdb->prefix; ?></p></td>
				</tr>

				<!-- DB USER.-->
				<tr>
					<th scope="row"><label for="db-user"><?php esc_attr_e( 'DB User', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $wpdb->dbuser; ?></p></td>
				</tr>

				<!-- DB PASSWORD. -->
				<tr>
					<th scope="row"><label for="db-password"><?php esc_attr_e( 'DB Password', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo empty( $wpdb->dbpassword ) ? __( 'No Password', 'wp-system-log' ) : $wpdb->dbpassword; ?></p></td>
				</tr>

				<!-- DB NAME. -->
				<tr>
					<th scope="row"><label for="db-name"><?php esc_attr_e( 'Databse Name', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $wpdb->dbname; ?></p></td>
				</tr>

				<!-- DB HOST. -->
				<tr>
					<th scope="row"><label for="db-host"><?php esc_attr_e( 'DB Host', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo $wpdb->dbhost; ?></p></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
