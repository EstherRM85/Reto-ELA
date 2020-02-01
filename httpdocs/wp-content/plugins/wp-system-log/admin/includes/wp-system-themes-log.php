<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$themes       = wp_get_themes();
$active_theme = wp_get_theme(); // gets the current theme.
?>
<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding the themes running on your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-themes-log-tbl">
		<table class="form-table">
			<tbody>
				<?php foreach ( $themes as $index => $theme ) { ?>
					<tr>
						<th scope="row" class="wpsl-theme-name-th"><label for="theme-name"><?php echo $theme['Name'] . ' v' . $theme['Version']; ?></label></th>
						<td class="wpsl-theme-author-td">-<a href="<?php echo $theme['AuthorURI']; ?>" target="_blank"><?php echo $theme['Author']; ?></a></td>
						<?php if ( $active_theme == $theme['Name'] ) { ?>
							<td><span><i class="fa fa-check" aria-hidden="true"></i></span></td>
						<?php } ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
