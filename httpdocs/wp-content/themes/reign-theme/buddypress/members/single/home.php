<?php
/**
 * BuddyPress - Members Home
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
?>
<?php
global $wbtm_reign_settings;
$member_header_position = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';
$member_header_position = apply_filters( 'wbtm_rth_manage_member_header_position', $member_header_position );
?>
<div id="buddypress">

	<?php if ( $member_header_position == 'top' ) : ?>
		<div id="item-header" class="wbtm-item-header-full" role="complementary">
			<?php
			/**
			 * If the cover image feature is enabled, use a specific header
			 */
			if ( bp_displayed_user_use_cover_image_header() ) :
				bp_get_template_part( 'members/single/cover-image-header' );
			else :
				bp_get_template_part( 'members/single/member-header' );
			endif;
			?>
		</div><!-- #item-header -->
	<?php endif; ?>

	<div class="wb-grid">
		<?php echo get_sidebar( 'activity-left' ); ?>

		<div class="bp-content-area member-profile-content-area">

			<?php
			/**
			 * Fires before the display of member home content.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_member_home_content' );
			?>

			<?php if ( $member_header_position == 'inside' ) : ?>
				<div id="item-header" role="complementary">

					<?php
					/**
					 * If the cover image feature is enabled, use a specific header
					 */
					if ( bp_displayed_user_use_cover_image_header() ) :
						bp_get_template_part( 'members/single/cover-image-header' );
					else :
						bp_get_template_part( 'members/single/member-header' );
					endif;
					?>

				</div><!-- #item-header -->
			<?php endif; ?>

			<div id="item-body">

				<div class="wb-grid">

					<div class="wb-grid-cell item-body-wrap">

						<div class="inner-item-body-wrap">
							<?php
							/**
							 * Fires before the display of member body content.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_member_body' );
							if ( bp_is_user_front() ) :
								bp_displayed_user_front_template_part();
							elseif ( bp_is_user_activity() ) :
								bp_get_template_part( 'members/single/activity' );
							elseif ( bp_is_user_blogs() ) :
								bp_get_template_part( 'members/single/blogs' );
							elseif ( bp_is_user_friends() ) :
								bp_get_template_part( 'members/single/friends' );
							elseif ( bp_is_user_groups() ) :
								bp_get_template_part( 'members/single/groups' );
							elseif ( bp_is_user_messages() ) :
								bp_get_template_part( 'members/single/messages' );
							elseif ( bp_is_user_profile() ) :
								bp_get_template_part( 'members/single/profile' );
							// elseif ( bp_is_user_forums() ) :
							// 	bp_get_template_part( 'members/single/forums' );
							elseif ( bp_is_user_notifications() ) :
								bp_get_template_part( 'members/single/notifications' );
							elseif ( bp_is_user_settings() ) :
								bp_get_template_part( 'members/single/settings' );
							// If nothing sticks, load a generic template.
							else :
								bp_get_template_part( 'members/single/plugins' );
							endif;
							/**
							 * Fires after the display of member body content.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_after_member_body' );
							?>

						</div>
					</div>
					<?php echo get_sidebar( 'buddypress' ); ?>

				</div>
			</div><!-- #item-body -->

			<?php
			/**
			 * Fires after the display of member home content.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_after_member_home_content' );
			?>

		</div>
	</div>

</div><!-- #buddypress -->
