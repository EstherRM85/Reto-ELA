<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
/**
 * Fires before the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_members_loop' );
?>

<?php if ( bp_get_current_member_type() ) : ?>
	<p class="current-member-type"><?php bp_current_member_type_message(); ?></p>
<?php endif; ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php
	/**
	 * Fires before the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members_list' );
	?>
	<?php
	global $wbtm_reign_settings;
	$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
	?>
	<?php
	if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
		$img_class = 'img-card';
	}
	?>
	<ul id="members-list" class="item-list rg-member-list wb-grid <?php echo esc_attr($member_directory_type); ?>" aria-live="assertive" aria-relevant="all">

		<?php
		while ( bp_members() ) :
			bp_the_member();
			?>
			<?php $user_id = bp_get_member_user_id(); ?>
			<li <?php bp_member_class( array( 'wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-2 lg-wb-grid-1-3' ) ); ?>>
				<div class="bp-inner-wrap">

					<?php do_action( 'wbtm_before_member_avatar_member_directory' ); ?>

					<div class="item-avatar">
						<?php
						if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
							echo '<figure class="img-dynamic aspect-ratio avatar">';
						}
						?>
						<a class="<?php echo esc_attr($img_class); ?>" href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?><?php echo reign_get_online_status( $user_id ); ?></a>
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
							<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
							<?php if ( bp_get_member_latest_update() ) : ?>
								<span class="update"> <?php bp_member_latest_update(); ?></span>
							<?php endif; ?>
						</div>

						<div class="item-meta">
							<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>"><?php bp_member_last_active(); ?></span>
						</div>

						<?php
						/**
						 * Fires inside the display of a directory member item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_members_item' );
						?>

						<?php
						/*
						 * *
						 * If you want to show specific profile fields here you can,
						 * but it'll add an extra query for each member in the loop
						 * (only one regardless of the number of fields you show):
						 *
						 * bp_member_profile_data( 'field=the field name' );
						 */
						?>
					</div>

					<div class="action-wrap">
						<i class="fa fa-plus-circle"></i>
						<div class="action rg-dropdown"><?php do_action( 'bp_directory_members_actions' ); ?></div>
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
	 * Fires after the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members_list' );
	?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no members were found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php
/**
 * Fires after the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_members_loop' );
