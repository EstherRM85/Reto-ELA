<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$all_plugins = get_plugins();
?>
<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding the plugins running on your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-plugins-log-tbl">
		<table class="form-table">
			<tbody>
				<?php foreach ( $all_plugins as $index => $plugin ) { ?>
					<tr>
						<th scope="row" class="wpsl-plugin-name-th"><label for="plugin-name"><?php echo $plugin['Name'] . ' v' . $plugin['Version']; ?></label></th>
						<td class="wpsl-plugin-author-td">-<a href="<?php echo $plugin['AuthorURI']; ?>" target="_blank"><?php echo $plugin['Author']; ?></a></td>
						<?php if ( is_plugin_active( $index ) ) { ?>
							<td><span><i class="fa fa-check" aria-hidden="true"></i></span></td>
						<?php } ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
