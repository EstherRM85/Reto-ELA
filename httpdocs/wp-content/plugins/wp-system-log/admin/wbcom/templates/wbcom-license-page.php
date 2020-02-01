<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
    <h1 class="wbcom-plugin-heading"><?php esc_html_e( 'Plugin License Settings', 'wp-system-log' ); ?></h1>
    <div class="wb-plugins-license-tables-wrap">
    	<table class="form-table wb-license-form-table desktop-license-heading">
			<thead>
				<tr>
					<th class="wb-product-th"><?php esc_html_e( 'Product', 'wp-system-log' ); ?></th>
					<th class="wb-version-th"><?php esc_html_e( 'Version', 'wp-system-log' ); ?></th>
					<th class="wb-key-th"><?php esc_html_e( 'Key', 'wp-system-log' ); ?></th>
					<th class="wb-status-th"><?php esc_html_e( 'Status', 'wp-system-log' ); ?></th>
					<th class="wb-action-th"><?php esc_html_e( 'Action', 'wp-system-log' ); ?></th>
					<th></th>
				</tr>
			</thead>
		</table>
    	<?php do_action( 'wbcom_add_plugin_license_code' ); ?>
    	<table class="form-table wb-license-form-table">
			<tfoot>
				<tr>
					<th class="wb-product-th"><?php esc_html_e( 'Product', 'wp-system-log' ); ?></th>
					<th class="wb-version-th"><?php esc_html_e( 'Version', 'wp-system-log' ); ?></th>
					<th class="wb-key-th"><?php esc_html_e( 'Key', 'wp-system-log' ); ?></th>
					<th class="wb-status-th"><?php esc_html_e( 'Status', 'wp-system-log' ); ?></th>
					<th class="wb-action-th"><?php esc_html_e( 'Action', 'wp-system-log' ); ?></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>