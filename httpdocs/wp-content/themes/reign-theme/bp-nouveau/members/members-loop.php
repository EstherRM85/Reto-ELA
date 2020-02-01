<?php
/**
 * BuddyPress - Members Loop
 *
 * @since 3.0.0
 * @version 3.0.0
 */
?>

<?php
$loops_layout = 1;
if ( isset( $_POST[ 'customized' ] ) ) {
	if ( bp_is_friends_component() ) {
		$loops_layout = intval( $_POST[ 'customized' ][ 'bp_nouveau_appearance_members_friends_layout' ] );
	} else {
		$loops_layout = intval( $_POST[ 'customized' ][ 'bp_nouveau_appearance_members_layout' ] );
	}
} else {
	if ( bp_is_friends_component() ) {
		$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
		if ( !isset( $bp_nouveau_appearance[ 'members_friends_layout' ] ) ) {
			$loops_layout = 1;
		} else {
			$loops_layout = $bp_nouveau_appearance[ 'members_friends_layout' ];
		}
	} else {
		$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
		if ( !isset( $bp_nouveau_appearance[ 'members_layout' ] ) ) {
			$loops_layout = 1;
		} else {
			$loops_layout = $bp_nouveau_appearance[ 'members_layout' ];
		}
	}
}
?>

<?php if ( 1 === $loops_layout ) : ?>

	<?php bp_nouveau_before_loop(); ?>

	<?php if ( bp_get_current_member_type() ) : ?>
		<p class="current-member-type"><?php bp_current_member_type_message(); ?></p>
	<?php endif; ?>

	<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

		<?php bp_nouveau_pagination( 'top' ); ?>

		<ul id="members-list" class="<?php bp_nouveau_loop_classes(); ?>">

			<?php while ( bp_members() ) : bp_the_member(); ?>

				<li <?php bp_member_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_member_user_id(); ?>" data-bp-item-component="members">
					<div class="list-wrap">

						<div class="item-avatar">
							<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar( bp_nouveau_avatar_args() ); ?></a>
						</div>

						<div class="item">

							<div class="item-block">

								<h2 class="list-title member-name">
									<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
								</h2>

								<?php if ( bp_nouveau_member_has_meta() ) : ?>
									<p class="item-meta last-activity">
										<?php bp_nouveau_member_meta(); ?>
									</p><!-- #item-meta -->
								<?php endif; ?>

								<?php
								bp_nouveau_members_loop_buttons(
								array(
									'container'		 => 'ul',
									'button_element' => 'button',
								)
								);
								?>

							</div>

							<?php if ( bp_get_member_latest_update() && !bp_nouveau_loop_is_grid() ) : ?>
								<div class="user-update">
									<p class="update"> <?php bp_member_latest_update(); ?></p>
								</div>
							<?php endif; ?>

						</div><!-- // .item -->
					</div>
				</li>

			<?php endwhile; ?>

		</ul>

		<?php bp_nouveau_pagination( 'bottom' ); ?>

		<?php
	else :

		bp_nouveau_user_feedback( 'members-loop-none' );

	endif;
	?>

	<?php bp_nouveau_after_loop(); ?>

<?php else: ?>

	<?php bp_nouveau_before_loop(); ?>

	<?php
	global $wbtm_reign_settings;
	$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
	?>
	<?php
	if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
		$img_class = 'img-card';
	}
	?>
	<?php if ( bp_get_current_member_type() ) : ?>
		<p class="current-member-type"><?php bp_current_member_type_message(); ?></p>
	<?php endif; ?>

	<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

		<?php bp_nouveau_pagination( 'top' ); ?>

		<ul id="members-list" class="<?php bp_nouveau_loop_classes(); ?> <?php echo $member_directory_type; ?> rg-member-list">

			<?php while ( bp_members() ) : bp_the_member(); ?>
				<?php $user_id = bp_get_member_user_id(); ?>
				<li <?php bp_member_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_member_user_id(); ?>" data-bp-item-component="members">
					<div class="list-wrap">

						<?php do_action( 'wbtm_before_member_avatar_member_directory' ); ?>

						<div class="item-avatar">
							<?php
							if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
								echo '<figure class="img-dynamic aspect-ratio avatar">';
							}
							?>
							<a class="<?php echo $img_class; ?>" href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar( bp_nouveau_avatar_args() ); ?><?php echo reign_get_online_status( $user_id ); ?></a>
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

							<div class="item-block">

								<h2 class="list-title member-name">
									<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
								</h2>

								<?php if ( bp_nouveau_member_has_meta() ) : ?>
									<p class="item-meta last-activity">
										<?php bp_nouveau_member_meta(); ?>
									</p><!-- #item-meta -->
								<?php endif; ?>

								<?php do_action( 'wbtm_bp_nouveau_directory_members_item' ); ?>

								<!-- <div class="action-wrap">
									<i class="fa fa-plus-circle"></i>
									<div class="action rg-dropdown">
								<?php
								bp_nouveau_members_loop_buttons(
								array(
									'container'		 => 'ul',
									'button_element' => 'button',
								)
								);
								?>
									</div>
								</div> -->

							</div>

							<?php if ( FALSE && bp_get_member_latest_update() && !bp_nouveau_loop_is_grid() ) : ?>
								<div class="user-update">
									<p class="update"> <?php bp_member_latest_update(); ?></p>
								</div>
							<?php endif; ?>

						</div><!-- // .item -->

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
				</li>

			<?php endwhile; ?>

		</ul>

		<?php bp_nouveau_pagination( 'bottom' ); ?>

		<?php
	else :

		bp_nouveau_user_feedback( 'members-loop-none' );

	endif;
	?>

	<?php bp_nouveau_after_loop(); ?>

<?php endif; ?>