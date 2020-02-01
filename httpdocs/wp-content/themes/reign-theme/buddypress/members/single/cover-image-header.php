<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */
?>

<?php
/**
 * Fires before the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_header' );
?>
<?php
global $wbtm_reign_settings;
$member_header_class = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] : 'wbtm-cover-header-type-1';
$member_header_class = apply_filters( 'wbtm_rth_manage_member_header_class', $member_header_class );
?>
<div id="cover-image-container" class="wbtm-member-cover-image-container <?php echo esc_attr($member_header_class); ?>">
	<a id="header-cover-image" href="<?php bp_displayed_user_link(); ?>"></a>

	<div id="item-header-cover-image">

		<div class="wbtm-member-info-section"><!-- custom wrapper for main content :: start -->
			<div id="item-header-avatar">
				<a href="<?php bp_displayed_user_link(); ?>">

					<?php bp_displayed_user_avatar( 'type=full' ); ?>

				</a>
			</div><!-- #item-header-avatar -->

			<div id="item-header-content">

				<?php
				/**
				 * Fires before the bp_displayed_user_mentionname.
				 * Added by Reign Theme
				 * @since 1.0.7
				 */
				do_action( 'wbtm_bp_before_displayed_user_mentionname' );
				?>

				<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
					<h2 class="user-nicename">@<?php bp_displayed_user_mentionname(); ?></h2>
				<?php endif; ?>

				<div class="wbtm-item-buttons-wrapper">
					<!-- custom section to hover and reveal actions buttons :: start -->
					<div class="wbtm-show-item-buttons"><i class="fa fa-ellipsis-v"></i></div>
					<!-- custom section to hover and reveal actions buttons :: end -->

					<div id="item-buttons">
						<?php
						/**
						 * Fires in the member header actions section.
						 *
						 * @since 1.2.6
						 */
						do_action( 'bp_member_header_actions' );
						?>
					</div><!-- #item-buttons -->
				</div>

				<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_user_last_activity( bp_displayed_user_id() ) ); ?>"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>

				<div class="wbtm-badge">
					<?php
					if ( function_exists( 'reign_profile_achievements' ) ):
						reign_profile_achievements();
					endif;
					?>
				</div>

				<?php
				/**
				 * Fires before the display of the member's header meta.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_before_member_header_meta' );
				?>

				<div id="item-meta">

					<?php if ( bp_is_active( 'activity' ) ) : ?>

						<div id="latest-update">

							<?php bp_activity_latest_update( bp_displayed_user_id() ); ?>

						</div>

					<?php endif; ?>

					<?php
					/**
					 * Fires after the group header actions section.
					 *
					 * If you'd like to show specific profile fields here use:
					 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_profile_header_meta' );
					?>

				</div><!-- #item-meta -->

			</div><!-- #item-header-content -->

		</div><!-- custom wrapper for main content :: end -->

		<!-- custom section for extra content :: start -->
		<div class="wbtm-cover-extra-info-section">
			<?php
			/**
			 * Fires after main content to show extra information.
			 *
			 * @since 1.0.7
			 */
			do_action( 'wbtm_member_extra_info_section' );
			?>
		</div>
		<!-- custom section for extra content :: start -->

	</div><!-- #item-header-cover-image -->

</div><!-- #cover-image-container -->

<?php
/**
 * Fires after the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_header' );
?>

<div id="template-notices" role="alert" aria-atomic="true">
	<?php
	/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
	do_action( 'template_notices' );
	?>

</div>