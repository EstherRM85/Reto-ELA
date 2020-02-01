 <?php
 if ( !is_user_logged_in() ) {
 	global $wbtm_reign_settings;
 	$registration_page_url = wp_registration_url();
 	if ( isset( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ] ) && ( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ] != '-1' ) ) {
 		$registration_page_id	 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ];
 		$registration_page_url	 = get_permalink( $registration_page_id );
 	}
 	if ( get_option( 'users_can_register' ) ) {
 		?>
 		<span class="sep">|</span>
 		<div class="rg-icon-wrap">
 			<a href="<?php echo $registration_page_url; ?>" class="btn-register" title="Register">
 				<span class="fa fa-address-book-o"></span>
 			</a>
 		</div>
 		<?php
 	}
}
