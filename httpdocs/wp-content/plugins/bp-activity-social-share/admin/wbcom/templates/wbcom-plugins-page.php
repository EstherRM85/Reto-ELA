<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
	<?php
	$wbcom_setting_obj	 = new Wbcom_Admin_Settings();
	$free_plugins		 = $wbcom_setting_obj->wbcom_all_free_plugins();
	$paid_plugins		 = $wbcom_setting_obj->wbcom_all_paid_plugins();
	?>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Free Addons', 'buddypress-share' ); ?></h4>
	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php
			foreach ( $free_plugins as $key => $plugin_details ) {
				if ( 'not_installed' == $plugin_details[ 'status' ] ) {
					$plugin_btn_text = esc_html__( 'Install', 'buddypress-share' );
					$toggle_class	 = 'fa fa-toggle-off';
					$plugin_action	 = 'install_plugin';
				} else if ( 'installed' == $plugin_details[ 'status' ] ) {
					$plugin_btn_text = esc_html__( 'Activate', 'buddypress-share' );
					$toggle_class	 = 'fa fa-toggle-off';
					$plugin_action	 = 'activate_plugin';
				} else {
					$plugin_btn_text = esc_html__( 'Deactivate', 'buddypress-share' );
					$toggle_class	 = 'fa fa-toggle-on';
					$plugin_action	 = 'deactivate_plugin';
				}
				?>
				<div class="wbcom-req-plugin-card">
					<div class="wbcom_single_left">
						<div class="wbcom_single_icon_wrapper">
							<i class="<?php echo esc_attr( $plugin_details[ 'icon' ] ); ?>" aria-hidden="true"></i>
						</div>
					</div>
					<div class="wbcom_single_right">
						<h3><a href="<?php echo esc_url( $plugin_details[ 'wp_url' ] ); ?>"><?php echo esc_html( $plugin_details[ 'name' ] ); ?></a></h3>
						<p class="plugin-description"><?php echo esc_html( $plugin_details[ 'description' ] ); ?></p>
						<input type="hidden" class="plugin-slug" name="plugin-slug" value="<?php echo esc_attr( $plugin_details[ 'slug' ] ); ?>">
						<input type="hidden" class="plugin-action" name="plugin-action" value="<?php echo esc_attr( $plugin_action ); ?>">
						<div class="activation_button_wrap">
							<a href="" class="wbcom-plugin-action-button wb_btn wb_btn_default" >
								<i class="<?php echo $toggle_class; ?>"></i>
								<?php echo $plugin_btn_text; ?>
								<i class="fa fa-spinner fa-spin" style="display:none"></i>
							</a>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Paid Addons', 'buddypress-share' ); ?></h4>
	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php foreach ( $paid_plugins as $key => $plugin_details ) {
				?>
				<div class="wbcom-req-plugin-card">
					<div class="wbcom_single_left">
						<div class="wbcom_single_icon_wrapper">
							<i class="<?php echo esc_attr( $plugin_details[ 'icon' ] ); ?>" aria-hidden="true"></i>
						</div>
					</div>
					<div class="wbcom_single_right">
						<h3><?php echo esc_html( $plugin_details[ 'name' ] ); ?></h3>
						<p class="plugin-description"><?php echo esc_html( $plugin_details[ 'description' ] ); ?></p>
						<div class="activation_button_wrap">
							<a href="<?php echo esc_url( $plugin_details[ 'download_url' ] ); ?>" class="wb_btn wb_btn_default" target="_blank" >
								<i class="fa fa-eye"></i>
								<?php esc_html_e( 'View', 'buddypress-share' ); ?>
							</a>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>