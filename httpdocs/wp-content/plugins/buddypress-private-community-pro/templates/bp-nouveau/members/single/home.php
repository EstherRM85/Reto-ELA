<?php
/**
 * BuddyPress - Members Home
 *
 * @since   1.0.0
 * @version 3.0.0
 */
?>

	<?php bp_nouveau_member_hook( 'before', 'home_content' ); ?>

	<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">

		<?php bp_nouveau_member_header_template_part(); ?>

	</div><!-- #item-header -->

	<div class="bp-wrap">
		<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

			<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>

		<?php endif; ?>

		<div id="item-body" class="item-body">

			<?php 

			/**
			 * Fires before the display of member body content.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_member_body' );

			$blpro_prof_visib = apply_filters( 'blpro_profile_visility_home_override', $visib = true );

			if ( $blpro_prof_visib ) {

				if ( bp_is_user_front() ) {
					bp_displayed_user_front_template_part();

				} else {
					$template = 'plugins';

					if ( bp_is_user_activity() ) {
						$template = 'activity';
					} elseif ( bp_is_user_blogs() ) {
						$template = 'blogs';
					} elseif ( bp_is_user_friends() ) {
						$template = 'friends';
					} elseif ( bp_is_user_groups() ) {
						$template = 'groups';
					} elseif ( bp_is_user_messages() ) {
						$template = 'messages';
					} elseif ( bp_is_user_profile() ) {
						$template = 'profile';
					} elseif ( bp_is_user_notifications() ) {
						$template = 'notifications';
					} elseif ( bp_is_user_settings() ) {
						$template = 'settings';
					}

					bp_nouveau_member_get_template_part( $template );
				}

			}

			/**
			 * Fires after the display of member body content.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_after_member_body' ); ?>

		</div><!-- #item-body -->
	</div><!-- // .bp-wrap -->

	<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>
