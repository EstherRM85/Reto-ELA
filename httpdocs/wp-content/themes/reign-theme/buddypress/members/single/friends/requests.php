<?php
/**
 * BuddyPress - Members Friends Requests
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
/**
 * Fires before the display of member friend requests content.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_friend_requests_content' );
?>

<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>

	<h2 class="bp-screen-reader-text">
		<?php
		/* translators: accessibility text */
		_e( 'Friendship requests', 'buddypress' );
		?>
	</h2>

	<div id="pag-top" class="pagination no-ajax">

		<div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php
	global $wbtm_reign_settings;
	$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
	?>
	<?php
	if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
		$img_class = 'img-card';
	}
	?>

	<ul id="friend-list" class="item-list rg-member-list wb-grid <?php echo $member_directory_type; ?>">
		<?php
		while ( bp_members() ) :
			bp_the_member();
			?>

			<li id="friendship-<?php bp_friend_friendship_id(); ?>" class="wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-2">
				<div class="bp-inner-wrap">

					<?php do_action( 'wbtm_before_member_avatar_member_directory' ); ?>

					<div class="item-avatar">
						<?php
						if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
							echo '<figure class="img-dynamic aspect-ratio avatar">';
						}
						?>
						<a class="<?php echo esc_attr($img_class); ?>" href="<?php bp_member_link(); ?>"><?php bp_member_avatar(); ?></a>
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

						<div class="item-title">
							<a href="<?php bp_member_link(); ?>"><?php bp_member_name(); ?></a>
						</div>

						<div class="item-meta">
							<span class="activity"><?php bp_member_last_active(); ?></span>
						</div>

						<?php
						/**
						 * Fires inside the display of a member friend request item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_friend_requests_item' );
						?>
					</div>

					<div class="action-wrap">
						<i class="fa fa-plus-circle"></i>
						<div class="action rg-dropdown">
							<div class="generic-button"><a class="button accept" href="<?php bp_friend_accept_request_link(); ?>"><?php _e( 'Accept', 'buddypress' ); ?></a></div>
							<div class="generic-button"><a class="button reject" href="<?php bp_friend_reject_request_link(); ?>"><?php _e( 'Reject', 'buddypress' ); ?></a></div>
							<?php
							/**
							 * Fires inside the member friend request actions markup.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_friend_requests_item_action' );
							?>
						</div>
					</div>
					<?php
					if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
						echo '</div>';
					}
					?>
				</div>
			</li>

		<?php endwhile; ?>
	</ul>

	<?php
	/**
	 * Fires and displays the member friend requests content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_friend_requests_content' );
	?>

	<div id="pag-bottom" class="pagination no-ajax">

		<div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'You have no pending friendship requests.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php
/**
 * Fires after the display of member friend requests content.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_friend_requests_content' );
