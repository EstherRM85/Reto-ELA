<?php
/**
 * BuddyPress - Groups Loop
 *
 * @since 3.0.0
 * @version 3.1.0
 */
?>

<?php
$loops_layout = 1;
if ( isset( $_POST[ 'customized' ][ 'bp_nouveau_appearance_groups_layout' ] ) ) {
	$loops_layout = intval( $_POST[ 'customized' ][ 'bp_nouveau_appearance_groups_layout' ] );
} else {
	$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
	if ( !isset( $bp_nouveau_appearance[ 'groups_layout' ] ) ) {
		$loops_layout = 1;
	} else {
		$loops_layout = $bp_nouveau_appearance[ 'groups_layout' ];
	}
}
?>

<?php if ( 1 === $loops_layout ) : ?>

	<?php bp_nouveau_before_loop(); ?>

	<?php if ( bp_get_current_group_directory_type() ) : ?>
		<p class="current-group-type"><?php bp_current_group_directory_type_message(); ?></p>
	<?php endif; ?>

	<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

		<?php bp_nouveau_pagination( 'top' ); ?>

		<ul id="groups-list" class="<?php bp_nouveau_loop_classes(); ?>">

			<?php
			while ( bp_groups() ) :
				bp_the_group();
				?>

				<li <?php bp_group_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups">
					<div class="list-wrap">

						<?php if ( !bp_disable_group_avatar_uploads() ) : ?>
							<div class="item-avatar">
								<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( bp_nouveau_avatar_args() ); ?></a>
							</div>
						<?php endif; ?>

						<div class="item">

							<div class="item-block">

								<h2 class="list-title groups-title"><?php bp_group_link(); ?></h2>

								<?php if ( bp_nouveau_group_has_meta() ) : ?>

									<p class="item-meta group-details"><?php bp_nouveau_group_meta(); ?></p>

								<?php endif; ?>

								<p class="last-activity item-meta">
									<?php
									printf(
									/* translators: %s = last activity timestamp (e.g. "active 1 hour ago") */
									esc_html( 'active %s', 'buddypress' ), bp_get_group_last_active()
									);
									?>
								</p>

							</div>

							<div class="group-desc"><p><?php bp_nouveau_group_description_excerpt(); ?></p></div>

							<?php bp_nouveau_groups_loop_item(); ?>

							<?php bp_nouveau_groups_loop_buttons(); ?>

						</div>


					</div>
				</li>

			<?php endwhile; ?>

		</ul>

		<?php bp_nouveau_pagination( 'bottom' ); ?>

	<?php else : ?>

		<?php bp_nouveau_user_feedback( 'groups-loop-none' ); ?>

	<?php endif; ?>

	<?php
	bp_nouveau_after_loop();
	?>

<?php else: ?>

	<?php bp_nouveau_before_loop(); ?>

	<?php
	global $wbtm_reign_settings;
	$group_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] : 'wbtm-group-directory-type-2';
	?>
	<?php
	if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
		$img_class = 'img-card';
	}
	?>

	<?php if ( bp_get_current_group_directory_type() ) : ?>
		<p class="current-group-type"><?php bp_current_group_directory_type_message(); ?></p>
	<?php endif; ?>

	<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

		<?php bp_nouveau_pagination( 'top' ); ?>

		<ul id="groups-list" class="<?php bp_nouveau_loop_classes(); ?> <?php echo esc_attr( $group_directory_type ); ?> rg-group-list">

			<?php
			while ( bp_groups() ) :
				bp_the_group();
				?>

				<li <?php bp_group_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups">
					<div class="list-wrap">

						<?php
						/**
						 * Fires inside the listing of an individual group listing item.
						 * Added by Reign Theme
						 * @since 1.0.7
						 */
						do_action( 'wbtm_before_group_avatar_group_directory' );
						?>

						<?php if ( !bp_disable_group_avatar_uploads() ) : ?>
							<div class="item-avatar">
								<?php
								if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
									echo '<figure class="img-dynamic aspect-ratio avatar">';
								}
								?>
								<a class="item-avatar-group <?php echo esc_attr($img_class); ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( bp_nouveau_avatar_args() ); ?></a><?php
								if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
									echo '</figure>';
								}
								?>
							</div>
						<?php endif; ?>

						<div class="group-content-wrap">
							<div class="item">

								<div class="item-block">

									<h2 class="list-title groups-title"><?php bp_group_link(); ?></h2>

									<?php if ( bp_nouveau_group_has_meta() ) : ?>

										<p class="item-meta group-details"><?php bp_nouveau_group_meta(); ?></p>

									<?php endif; ?>

									<p class="last-activity item-meta">
										<?php
										printf(
										/* translators: %s = last activity timestamp (e.g. "active 1 hour ago") */
										esc_html( 'active %s', 'buddypress' ), bp_get_group_last_active()
										);
										?>
									</p>

								</div>

								<div class="group-desc"><p><?php bp_nouveau_group_description_excerpt(); ?></p></div>

								<?php bp_nouveau_groups_loop_item(); ?>

								<?php //bp_nouveau_groups_loop_buttons();  ?>

							</div>

							<?php do_action( 'wbtm_bp_directory_groups_data' ); ?>

							<div class="group-admins-wrap">
								<?php reign_bp_group_list_admins(); ?>
							</div>

							<!-- Added action buttons here -->
							<?php
							if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
								echo '<div class="action-wrap"><i class="fa fa-plus-circle"></i>';
							}
							bp_nouveau_groups_loop_buttons();
							if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
								echo '</div>';
							}
							?>
						</div>

					</div>
				</li>

			<?php endwhile; ?>

		</ul>

		<?php bp_nouveau_pagination( 'bottom' ); ?>

	<?php else : ?>

		<?php bp_nouveau_user_feedback( 'groups-loop-none' ); ?>

	<?php endif; ?>

	<?php
	bp_nouveau_after_loop();
	?>

<?php endif; ?>