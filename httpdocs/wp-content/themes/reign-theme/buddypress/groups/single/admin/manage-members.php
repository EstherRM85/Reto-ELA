<?php
/**
 * BuddyPress - Groups Admin - Manage Members
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
?>

<h2 class="bp-screen-reader-text"><?php esc_html_e( 'Manage Members', 'buddypress' ); ?></h2>

<?php
/**
 * Fires before the group manage members admin display.
 *
 * @since 1.1.0
 */
do_action( 'bp_before_group_manage_members_admin' );
?>

<div aria-live="polite" aria-relevant="all" aria-atomic="true">

	<div class="bp-widget group-members-list group-admins-list">
		<h3 class="section-header"><?php _e( 'Administrators', 'buddypress' ); ?></h3>

		<?php
		if ( bp_group_has_members(
		array(
			'per_page'	 => 15,
			'group_role' => array( 'admin' ),
			'page_arg'	 => 'mlpage-admin',
		)
		) ) :
			?>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>
			<?php
			global $wbtm_reign_settings;
			$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
			?>
			<?php
			if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
				$addition_class = 'lg-wb-grid-1-3';
			}
			?>
			<?php
			if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
				$img_class = 'img-card';
			}
			?>
			<ul id="admins-list" class="item-list wb-grid rg-member-list wb-grid <?php echo $member_directory_type; ?>" aria-live="assertive" aria-relevant="all">
				<?php
				while ( bp_group_members() ) :
					bp_group_the_member();
					?>
					<!-- <li class="wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-2"> -->
					<?php $user_id = bp_get_member_user_id(); ?>
					<li <?php bp_member_class( array( "wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-2 '. $addition_class .'" ) ); ?>>
						<div class="bp-inner-wrap">
							<?php do_action( 'wbtm_before_member_avatar_member_directory' ); ?>
							<!-- <div class="item-avatar"> -->
							<?php //bp_group_member_avatar_thumb();  ?>
							<!-- </div> -->
							<div class="item-avatar">
								<?php
								if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
									echo '<figure class="img-dynamic aspect-ratio avatar">';
								}
								?>
								<a class="<?php echo $img_class; ?>" href="<?php bp_group_member_domain(); ?>"><?php bp_group_member_avatar_thumb(); ?><?php echo reign_get_online_status( $user_id ); ?></a>
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
								<p class="joined item-meta">
									<?php bp_group_member_joined_since(); ?>
								</p>
								<?php
								/**
								 * Fires inside the item section of a member admin item in group management area.
								 *
								 * @since 1.1.0
								 * @since 2.7.0 Added $section parameter.
								 *
								 * @param $section Which list contains this item.
								 */
								do_action( 'bp_group_manage_members_admin_item', 'admins-list' );
								?>
							</div>

							<div class="action-wrap">
								<i class="fa fa-plus-circle"></i>
								<div class="action rg-dropdown">
									<?php if ( count( bp_group_admin_ids( false, 'array' ) ) > 1 ) : ?>
										<div><a class="button confirm admin-demote-to-member" href="<?php bp_group_member_demote_link(); ?>"><?php _e( 'Demote to Member', 'buddypress' ); ?></a></div>
									<?php endif; ?>

									<?php
									/**
									 * Fires inside the action section of a member admin item in group management area.
									 *
									 * @since 2.7.0
									 *
									 * @param $section Which list contains this item.
									 */
									do_action( 'bp_group_manage_members_admin_actions', 'admins-list' );
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

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php else : ?>

			<div id="message" class="info">
				<p><?php _e( 'No group administrators were found.', 'buddypress' ); ?></p>
			</div>

		<?php endif; ?>
	</div>

	<div class="bp-widget group-members-list group-mods-list">
		<h3 class="section-header"><?php _e( 'Moderators', 'buddypress' ); ?></h3>

		<?php
		if ( bp_group_has_members(
		array(
			'per_page'	 => 15,
			'group_role' => array( 'mod' ),
			'page_arg'	 => 'mlpage-mod',
		)
		) ) :
			?>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

			<ul id="mods-list" class="item-list wb-grid rg-member-list wb-grid <?php echo $member_directory_type; ?>" aria-live="assertive" aria-relevant="all">

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
								<a class="<?php echo $img_class; ?>" href="<?php bp_group_member_domain(); ?>"><?php bp_group_member_avatar_thumb(); ?><?php echo reign_get_online_status( $user_id ); ?></a>
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
								<p class="joined item-meta">
									<?php bp_group_member_joined_since(); ?>
								</p>
								<?php
								/**
								 * Fires inside the item section of a member admin item in group management area.
								 *
								 * @since 1.1.0
								 * @since 2.7.0 Added $section parameter.
								 *
								 * @param $section Which list contains this item.
								 */
								do_action( 'bp_group_manage_members_admin_item', 'admins-list' );
								?>
							</div>

							<div class="action-wrap">
								<i class="fa fa-plus-circle"></i>
								<div class="action rg-dropdown">
									<div><a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm mod-promote-to-admin"><?php _e( 'Promote to Admin', 'buddypress' ); ?></a></div>
									<div><a class="button confirm mod-demote-to-member" href="<?php bp_group_member_demote_link(); ?>"><?php _e( 'Demote to Member', 'buddypress' ); ?></a></div>

									<?php
									/**
									 * Fires inside the action section of a member admin item in group management area.
									 *
									 * @since 2.7.0
									 *
									 * @param $section Which list contains this item.
									 */
									do_action( 'bp_group_manage_members_admin_actions', 'mods-list' );
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

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php else : ?>

			<div id="message" class="info">
				<p><?php _e( 'No group moderators were found.', 'buddypress' ); ?></p>
			</div>

		<?php endif; ?>
	</div>

	<div class="bp-widget group-members-list">
		<h3 class="section-header"><?php _e( 'Members', 'buddypress' ); ?></h3>

		<?php
		if ( bp_group_has_members(
		array(
			'per_page'		 => 15,
			'exclude_banned' => 0,
		)
		) ) :
			?>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

			<ul id="members-list" class="item-list wb-grid rg-member-list wb-grid <?php echo esc_attr($member_directory_type); ?>" aria-live="assertive" aria-relevant="all">
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
									<?php
									if ( bp_get_group_member_is_banned() ) {
										echo ' <span class="banned">';
										_e( '(banned)', 'buddypress' );
										echo '</span>';
									}
									?>
								</div>
								<p class="joined item-meta">
									<?php bp_group_member_joined_since(); ?>
								</p>
								<?php
								/**
								 * Fires inside the item section of a member admin item in group management area.
								 *
								 * @since 1.1.0
								 * @since 2.7.0 Added $section parameter.
								 *
								 * @param $section Which list contains this item.
								 */
								do_action( 'bp_group_manage_members_admin_item', 'admins-list' );
								?>
							</div>
							<div class="action-wrap">
								<i class="fa fa-plus-circle"></i>
								<div class="action rg-dropdown">
									<?php if ( bp_get_group_member_is_banned() ) : ?>

										<div><a href="<?php bp_group_member_unban_link(); ?>" class="button confirm member-unban"><?php _e( 'Remove Ban', 'buddypress' ); ?></a></div>

									<?php else : ?>

										<div><a href="<?php bp_group_member_ban_link(); ?>" class="button confirm member-ban"><?php _e( 'Kick &amp; Ban', 'buddypress' ); ?></a></div>
										<div><a href="<?php bp_group_member_promote_mod_link(); ?>" class="button confirm member-promote-to-mod"><?php _e( 'Promote to Mod', 'buddypress' ); ?></a></div>
										<div><a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm member-promote-to-admin"><?php _e( 'Promote to Admin', 'buddypress' ); ?></a></div>

									<?php endif; ?>

									<div><a href="<?php bp_group_member_remove_link(); ?>" class="button confirm"><?php _e( 'Remove from group', 'buddypress' ); ?></a></div>

									<?php
									/**
									 * Fires inside the action section of a member admin item in group management area.
									 *
									 * @since 2.7.0
									 *
									 * @param $section Which list contains this item.
									 */
									do_action( 'bp_group_manage_members_admin_actions', 'members-list' );
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

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php else : ?>

			<div id="message" class="info">
				<p><?php _e( 'No group members were found.', 'buddypress' ); ?></p>
			</div>

		<?php endif; ?>
	</div>

</div>

<?php
/**
 * Fires after the group manage members admin display.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_manage_members_admin' );
