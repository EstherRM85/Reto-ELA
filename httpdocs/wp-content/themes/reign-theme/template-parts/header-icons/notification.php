<?php
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'notifications' ) ) {

	global $bp;
	?>

	<div class="user-notifications">

		<a class="rg-icon-wrap" href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>" title="<?php _e( esc_attr( 'Notifications' ), 'reign' ); ?>">
			<span class="fa fa-bell-o"></span>

			<?php
			if ( function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
				$count = bp_notifications_get_unread_notification_count( get_current_user_id() );

				//if ( $count > 0 ) {
				?>
				<span class="rg-count"> <?php echo esc_html( $count ); ?></span><?php
				//}
			}
			?>
		</a>

		<?php
		$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id() );
		if ( $notifications ) {
			?>
			<ul id="rg-notify" class="rg-header-submenu rg-dropdown"><?php
				rsort( $notifications );
				foreach ( $notifications as $notification ) {
					?>
					<li><?php echo $notification; ?></li><?php
				}
				?>
				<li class="rg-view-all">
					<a href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>"><?php _e( 'View all notifications', 'reign' ); ?></a>
				</li>
			</ul>
		<?php } else {
			?>
			<ul id="rg-notify" class="rg-header-submenu rg-dropdown rg-notify"><?php
			?>
				<li><a href="<?php bp_loggedin_user_domain() . BP_NOTIFICATIONS_SLUG ?>"><?php _e( "No new notifications", "buddypress" ); ?></a></li>
			</ul>
		<?php }
		?>
	</div><?php
}
