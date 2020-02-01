<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="wpsl-adming-setting">
	<div class="wpsl-tab-header"><h3><?php esc_attr_e( 'Have some questions?', 'wp-system-log' ); ?></h3></div>
		<div class="wpsl-admin-settings-block">
		<div id="wpsl-settings-tbl">
			<div class="wpsl-admin-row">
				<div>
					<button class="wpsl-accordion"><?php esc_attr_e( 'What plugin does this plugin require?', 'wp-system-log' ); ?></button>
					<div class="panel">
						<p><?php esc_attr_e( 'This plugin simply deals with your system details, thereby requires no such plugin.', 'wp-system-log' ); ?></p>
					</div>
				</div>
			</div>

			<div class="wpsl-admin-row">
				<div>
					<button class="wpsl-accordion"><?php esc_attr_e( 'How does this plugin work?', 'wp-system-log' ); ?></button>
					<div class="panel">
						<p><?php esc_attr_e( 'This plugin shows you all the basic details of the system on which your site is currently running, that includes all the server details, the WordPress that you\'re using, database details and everything.', 'wp-system-log' ); ?></p>
						<p><?php esc_attr_e( 'This plugin greatly helps developers when they debug the site for some error.', 'wp-system-log' ); ?></p>
					</div>
				</div>
			</div>

			<div class="wpsl-admin-row">
				<div>
					<button class="wpsl-accordion"><?php esc_attr_e( 'How to go for any custom development?', 'wp-system-log' ); ?></button>
					<div class="panel">
						<p><?php esc_attr_e( 'If you need additional help you can contact us for <a href="https://wbcomdesigns.com/contact/" target="_blank" title="Custom Development by Wbcom Designs">Custom Development</a>.', 'wp-system-log' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
