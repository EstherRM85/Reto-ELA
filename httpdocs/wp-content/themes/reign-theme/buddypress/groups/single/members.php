<?php
/**
 * BuddyPress - Groups Members
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
?>

<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php
	/**
	 * Fires before the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_content' );
	?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php
	/**
	 * Fires before the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_list' );
	?>
	<?php
	global $wbtm_reign_settings;
	$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
	?>
	<?php
	$addition_class = '';
	if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
		$addition_class = 'lg-wb-grid-1-3';
	}
	?>
	<?php
	if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
		$img_class = 'img-card';
	}
	?>
	<ul id="member-list" class="item-list rg-member-list wb-grid <?php echo esc_attr($member_directory_type); ?>" aria-live="assertive" aria-relevant="all">

		<?php
		while ( bp_group_members() ) :
			bp_group_the_member();
			?>

			<?php $user_id = bp_get_member_user_id(); ?>
			<li <?php bp_member_class( array( "wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-2 '. $addition_class .'" ) ); ?>>

				<div class="bp-inner-wrap">
					<?php do_action( 'wbtm_before_member_avatar_member_directory' ); ?>
					<div class="item-avatar">
						<?php
						if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
							echo '<figure class="img-dynamic aspect-ratio avatar">';
						}
						?>
						<a class="<?php echo esc_attr($img_class); ?>" href="<?php bp_group_member_domain(); ?>"><?php bp_group_member_avatar_thumb(); ?><?php echo reign_get_online_status( $user_id ); ?></a>
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
							<?php bp_group_member_link(); ?>
						</div>
						<div class="item-meta">
							<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_member_joined_since( array( 'relative' => false ) ) ); ?>"><?php bp_group_member_joined_since(); ?></span>
						</div>

						<?php
						/**
						 * Fires inside the listing of an individual group member listing item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_group_members_list_item' );
						?>
					</div>

					<?php if ( bp_is_active( 'friends' ) ) : ?>
						<div class="action-wrap">
							<i class="fa fa-plus-circle"></i>
							<div class="action rg-dropdown"><?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?><?php do_action( 'bp_group_members_list_item_action' ); ?></div>
						</div>
					<?php endif; ?>
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
	 * Fires after the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_list' );
	?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php
	/**
	 * Fires after the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_content' );
	?>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'No members were found.', 'buddypress' ); ?></p>
	</div>

<?php
endif;
