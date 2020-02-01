<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding your WordPress which is running your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-wp-log-tbl">
		<table class="form-table">
			<tbody>
				<!-- HOME URL. -->
				<tr>
					<th scope="row"><label for="home-url"><?php esc_attr_e( 'Home URL', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo home_url(); ?></p></td>
				</tr>

				<!-- SITE URL. -->
				<tr>
					<th scope="row"><label for="site-url"><?php esc_attr_e( 'Site URL', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo get_site_url(); ?></p></td>
				</tr>

				<!-- WP VERSION. -->
				<tr>
					<th scope="row"><label for="wp-version"><?php esc_attr_e( 'WordPress Version', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo get_bloginfo( 'version' ); ?></p></td>
				</tr>

				<!-- IS MULTISITE?. -->
				<tr>
					<th scope="row"><label for="fsockopen-curl"><?php esc_attr_e( 'Is Multisite?', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( is_multisite() ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>

				<!-- MEMORY LIMIT. -->
				<tr>
					<th scope="row"><label for="memory-limit"><?php esc_attr_e( 'Memory Limit', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo WP_MEMORY_LIMIT; ?></p></td>
				</tr>

				<!-- WP DEBUG MODE. -->
				<tr>
					<th scope="row"><label for="wp-debug-mode"><?php esc_attr_e( 'Debug Mode', 'wp-system-log' ); ?></label></th>
					<td class="wpsl-wp-debug-mode">
						<?php
						if ( WP_DEBUG ) {
							?>
							<span><i class="fa fa-check" aria-hidden="true"></i></span>
							<button type="button" class="button button-primary" id="wpsl-turn-debug-off"><?php esc_attr_e( 'Turn Off', 'wp-system-log' ); ?></button>
							<?php
						} else {
							?>
							<span><i class="fa fa-times" aria-hidden="true"></i></span>
							<button type="button" class="button button-primary" id="wpsl-turn-debug-on"><?php esc_attr_e( 'Turn On', 'wp-system-log' ); ?></button>
							<?php
						}
						?>
					</td>
				</tr>

				<!-- WP CRON. -->
				<tr>
					<th scope="row"><label for="wp-cron"><?php esc_attr_e( 'WordPress Cron', 'wp-system-log' ); ?></label></th>
					<td>
						<?php
						if ( ! empty( _get_cron_array() ) ) {
							echo '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
						} else {
							echo '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
						}
						?>
					</td>
				</tr>

				<!-- LANGUAGE. -->
				<tr>
					<th scope="row"><label for="wp-language"><?php esc_attr_e( 'Language', 'wp-system-log' ); ?></label></th>
					<td><p class=""><?php echo get_locale(); ?></p></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
