<?php
if ( is_user_logged_in() ) {
	// For PeepSo notification icons.
	if ( class_exists( 'PeepSo' ) ) {
	    if ( is_active_sidebar( 'reign-header-widget-area' ) ) :
		  dynamic_sidebar( 'reign-header-widget-area' );
		endif;
	} else {
		$current_user = wp_get_current_user();
		if ( ($current_user instanceof WP_User ) ) {
			$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
			echo '<div class="user-link-wrap">';
			echo '<a class="user-link" href="' . $user_link . '">';
			?>
			<span class="rg-user"><?php echo $current_user->display_name; ?></span>
			<?php
			echo get_avatar( $current_user->user_email, 200 );
			echo '</a>';
			wp_nav_menu( array( 'theme_location' => 'menu-2', 'menu_id' => 'user-profile-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'user-profile-menu', ) );
			echo '</div>';
		}
	}	
}