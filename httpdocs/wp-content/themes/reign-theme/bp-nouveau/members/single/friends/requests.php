<?php
/**
 * BuddyPress - Members Friends Requests
 *
 * @since 3.0.0
 * @version 3.0.0
 */
?>

<?php
$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
if ( !isset( $bp_nouveau_appearance[ 'members_friends_layout' ] ) ) {
	$bp_nouveau_appearance[ 'members_friends_layout' ] = 1;
}
?>

<?php if ( 1 === $bp_nouveau_appearance[ 'members_friends_layout' ] ) : ?>

	<h2 class="screen-heading friendship-requests-screen"><?php esc_html_e( 'Friendship Requests', 'buddypress' ); ?></h2>

	<?php bp_nouveau_member_hook( 'before', 'friend_requests_content' ); ?>

	<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>

		<?php bp_nouveau_pagination( 'top' ); ?>

		<ul id="friend-list" class="<?php bp_nouveau_loop_classes(); ?>" data-bp-list="friendship_requests">
			<?php
			while ( bp_members() ) :
				bp_the_member();
				?>

				<li id="friendship-<?php bp_friend_friendship_id(); ?>" <?php bp_member_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_friend_friendship_id(); ?>" data-bp-item-component="members">
					<div class="list-wrap">
						<div class="item-avatar">
							<a href="<?php bp_member_link(); ?>"><?php bp_member_avatar( array( 'type' => 'full' ) ); ?></a>
						</div>

						<div class="item">
							<div class="item-block">
								<div class="item-title"><h2 class="list-title member-name"><a href="<?php bp_member_link(); ?>"><?php bp_member_name(); ?></a></h2></div>
								<div class="item-meta last-activity"><span class="activity"><?php bp_member_last_active(); ?></span></div>

								<?php bp_nouveau_friend_hook( 'requests_item' ); ?>

								<?php
								bp_nouveau_members_loop_buttons(
								array(
									'container'		 => 'ul',
									'button_element' => 'button',
								)
								);
								?>
							</div>
						</div>

						<?php //bp_nouveau_members_loop_buttons(); ?>

					</div>
				</li>

			<?php endwhile; ?>
		</ul>

		<?php bp_nouveau_friend_hook( 'requests_content' ); ?>

		<?php bp_nouveau_pagination( 'bottom' ); ?>

	<?php else : ?>

		<?php bp_nouveau_user_feedback( 'member-requests-none' ); ?>

	<?php endif; ?>

	<?php
	bp_nouveau_member_hook( 'after', 'friend_requests_content' );
	?>

<?php else: ?>

	<?php
	global $wbtm_reign_settings;
	$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
	?>
	<?php
	if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
		$img_class = 'img-card';
	}
	?>

	<h2 class="screen-heading friendship-requests-screen"><?php esc_html_e( 'Friendship Requests', 'buddypress' ); ?></h2>

	<?php bp_nouveau_member_hook( 'before', 'friend_requests_content' ); ?>

	<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>

		<?php bp_nouveau_pagination( 'top' ); ?>

		<ul id="friend-list" class="<?php bp_nouveau_loop_classes(); ?> <?php echo esc_attr($member_directory_type); ?> rg-member-list" data-bp-list="friendship_requests">
			<?php
			while ( bp_members() ) :
				bp_the_member();
				?>

				<?php $user_id = bp_get_member_user_id(); ?>

				<li id="friendship-<?php bp_friend_friendship_id(); ?>" <?php bp_member_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_friend_friendship_id(); ?>" data-bp-item-component="members">
					<div class="list-wrap">

						<?php do_action( 'wbtm_before_member_avatar_member_directory' ); ?>

						<div class="item-avatar">
							<?php
							if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
								echo '<figure class="img-dynamic aspect-ratio avatar">';
							}
							?>
							<a class="<?php echo esc_attr($img_class); ?>" href="<?php bp_member_link(); ?>"><?php bp_member_avatar( array( 'type' => 'full' ) ); ?></a>
							<?php
							if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
								echo '</figure>';
							}
							?>
						</div>

						<?php
						if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
							echo '<div class="item-wrapper">';
						}
						?>
						<div class="item">
							<div class="item-title"><a href="<?php bp_member_link(); ?>"><?php bp_member_name(); ?></a></div>
							<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>

							<?php bp_nouveau_friend_hook( 'requests_item' ); ?>

							<?php do_action( 'wbtm_bp_nouveau_directory_members_item' ); ?>
						</div>

						<!-- Added actions buttons outside "item" section :: Start  -->
						<div class="action-wrap">
							<i class="fa fa-plus-circle"></i>
							<?php
							bp_nouveau_members_loop_buttons(
							array(
								'container'		 => 'ul',
								'button_element' => 'button',
							)
							);
							?>
						</div>
						<!-- Added actions buttons outside "item" section :: End  -->
						<?php
						if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
							echo '</div>';
						}
						?>
					</div>
					<?php //bp_nouveau_members_loop_buttons(); ?>
				</li>

			<?php endwhile; ?>
		</ul>

		<?php bp_nouveau_friend_hook( 'requests_content' ); ?>

		<?php bp_nouveau_pagination( 'bottom' ); ?>

	<?php else : ?>

		<?php bp_nouveau_user_feedback( 'member-requests-none' ); ?>

	<?php endif; ?>

	<?php
	bp_nouveau_member_hook( 'after', 'friend_requests_content' );
	?>

<?php endif; ?>