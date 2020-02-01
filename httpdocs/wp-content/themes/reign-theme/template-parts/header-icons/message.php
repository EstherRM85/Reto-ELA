<?php
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'messages' ) ) {
	?>
	<div class="rg-msg">
		<a class="rg-icon-wrap" href="<?php echo bp_loggedin_user_domain() . bp_get_messages_slug(); ?>">
			<span class="fa fa-envelope-o"></span>
			<?php
			if ( function_exists( 'bp_total_unread_messages_count' ) ) {
				$count = bp_get_total_unread_messages_count();

				if ( $count > 0 ) {
					?>
					<span class="rg-count"><?php bp_total_unread_messages_count(); ?></span><?php
				} else {
					echo '<span class="rg-count">0</span>';
				}
			}
			?>
		</a>
	</div>
	<?php
}