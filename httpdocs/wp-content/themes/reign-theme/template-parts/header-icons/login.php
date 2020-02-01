 <?php
 if ( !is_user_logged_in() ) {
 	global $wbtm_reign_settings;
 	$login_page_url = wp_login_url();
 	if ( isset( $settings[ 'reign_pages' ][ 'reign_login_page' ] ) && ( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_login_page' ] != '-1' ) ) {
 		$login_page_id	 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_login_page' ];
 		$login_page_url	 = get_permalink( $login_page_id );
 	}
 	?>
 	<div class="rg-icon-wrap">
 		<a href="<?php echo $login_page_url; ?>" class="btn-login" title="Login">	<span class="fa fa-sign-in"></span>
 		</a>
 	</div>
 	<?php
 }
